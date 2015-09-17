<?php
#SIMULATION DE PAIEMENT : 
require_once('inc/init.inc.php');
creationDuPanier(); //crée le panier au cas où il ne l'est pas. 
//debug($_SESSION);

if(isset($_POST['ajout_panier']) && $_POST['ajout_panier'])//ce post provient de la page fiche_article.php
{
    // debug($_POST);
    // echo $_POST['id_article']; //on récupère bien l'ID !!
    //echo $_POST['quantite'];

    $resultat = informationSurUnArticle($_POST['id_article']); //on renseigne l'argument de ma fonction par le $_POST['id_article'] récupéré depuis la fiche_article.php
    $article = $resultat->fetch_assoc();
    //debug($article,2); //=> var_dump()

    #calcul prix TVA :
    $article['prix'] = $article['prix'] * 1.20;

    ajouterArticleDansPanier($article['titre'],$_POST['id_article'],$_POST['quantite'],$article['prix']);//ajout au panier.

    // debug($_SESSION);
}


#----------DEBUT VIDER LE PANIER----------
//recup infos urp => $_GET
if(isset($_GET['action']) && $_GET['action'] == 'vider')
{
    unset($_SESSION['panier']);
}
#----------FIN VIDER LE PANIER----------



#----------DEBUT RETIRER ARTICLE DU PANIER----------
//recup infos urp => $_GET
if(isset($_GET['action']) && $_GET['action'] == 'retirer')
{
    retirerArticleDuPanier($_GET['id_article']);
}
#----------FIN RETIRER ARTICLE DU PANIER----------





#----------PARTIE PAIEMENT DU PANIER----------

//recup infos formulaire => $_POST
if(isset($_POST['payer']) && $_POST['payer'])
{
    //boucle qui tourne autant de fois qu'il y a d'articles différents dans le panier : 
    for($i = 0; $i < count($_SESSION['panier']['id_article']) ; $i++)
        //cf. count() équivalent du sizeof() => http://php.net/manual/fr/function.sizeof.php
    {
        $resultat = informationSurUnArticle($_SESSION['panier']['id_article'][$i]); //on charge les informations sur un article. Nous sommes dans une boucle, la requête est donc répétée autant de fois qu'il y a d'articles dans le panier.

        $article = $resultat->fetch_assoc();
        //var_dump($article);
        //echo  $_SESSION['panier']['id_article'][$i];

        #verification du stock : (on est toujours dans la boucle dans le but est de nous retourner le contenu du panier)
        if($article['stock'] < $_SESSION['panier']['quantite'][$i]) //si le stock actuel est strictement inférieur à la quantité que l'on souhaite commander...=>PROBLEME !!!
        {
            echo '<hr><div class="erreur">Stock restant : ' . $article['stock'] . '</div>';
            echo '<div class="erreur">Quantité demandée : ' .$_SESSION['panier']['quantite'][$i] . '</div>';

            if($article['stock'] > 0) //si l'internaute demande 5 tshirts, mais qu'il n'en reste que 2. Il va falloir calculer la différence. => ($article['stock'] > 0 AND $article['stock'] < $_SESSION['panier']['quantite'][$i])
            {
                echo '<div class="erreur">la quantité de l\'article ' . $_SESSION['panier']['id_article'][$i] . ' a été réduite car notre stock était insuffisant. Veuillez vérifier vos achats</div>';
                $_SESSION['panier']['quantite'][$i] = $article['stock'];
            }
            else //rupture de stock : on retire carrément les articles du panier.
            {
                echo '<div class="erreur">l\'article ' . $_SESSION['panier']['id_article'][$i] . ' a été retiré de votre panier car nous sommes en rupture de stock, veuillez vérifier vos achats.</div>';
                retirerArticleDuPanier($_SESSION['panier']['id_article'][$i]); //on retire l'article.
                $i--; //on décrémente pour retirer un article. Lorsque l'on souhaite rajouter une valeur à notre variable on incrémente, ici on souhaite enlever une valeur du coup on décrémente.
            }
            $erreur = TRUE;
        }
    }
    if(!isset($erreur)) //si $erreur = FALSE => on enregistre le panier.
    {
        executeRequete("INSERT INTO commande(id_membre,montant,date) VALUES (" . $_SESSION['utilisateur']['id_membre'] . "," . montantTotal() . ", NOW())");

        //récupération du dernier identifiant auto-généré par l'auto-increment de la BDD
        $id_commande = $mysqli->insert_id;

        //pour tous les articles dans le panier, on observe l'id, la quantité, le prix : on récupère tout pour les placer dans la table details_commande :
        for($j = 0 ; $j < count($_SESSION['panier']['id_article'][$j]); $j++)
        {
            //=>
            //ajout des informations dans la table details_commande :
            executeRequete("INSERT INTO details_commande (id_commande,id_article,quantite,prix) VALUES ($id_commande, ". $_SESSION['panier']['id_article'][$j] . "," . $_SESSION['panier']['quantite'][$j] . ",". $_SESSION['panier']['prix'][$j]. ")");

            //on va faire un update de notre stock : on attribue le nouveau stock
            executeRequete("UPDATE article SET stock=stock-".$_SESSION['panier']['quantite'][$j] . " WHERE id_article=" . $_SESSION['panier']['id_article'][$j]);
        }
        //paiement par chèque du coup on vide le panier :
        unset($_SESSION['panier']);
        //envoi mail confirmation achat au client :
        mail($_SESSION['utilisateur']['email'],"Confirmation de la commande", "Votre suivi de commande est le suivante : $id_commande","From:vendeur@site_ecommerce.com");
        echo "<div class='validation'>Merci pour votre commande. Votre n° suivi est le $id_commande</div>";
    }
}

//------AFFICHAGE DU PANIER------
//j'inclus les parties de mon site : 
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');


echo "<table border='1' style='border-collapse:collapse' cellpadding='7'>";
echo '<tr><td colspan="5">VOTRE PANIER</td></tr>';
echo "<tr><th>TITRE</th><th>ARTICLE</th><th>QUANTITE</th><th>PRIX UNITAIRE</th><th>ACTION</th></tr>";
//condition : si le panier est vide : 
if(empty($_SESSION['panier']['id_article']))
{
    echo '<tr><td colspan="5">VOTRE PANIER EST VIDE</td></tr>';
}
else
{
    for($w = 0; $w < count($_SESSION['panier']['id_article']); $w++) //boucle qui tournera autant de fois qu'il y a d'articles dans notre panier
    {
        echo "<tr>";
        echo "<td>" . $_SESSION['panier']['titre'][$w] . "</td>";
        echo "<td>" . $_SESSION['panier']['id_article'][$w] . "</td>";
        echo "<td>" . $_SESSION['panier']['quantite'][$w] . "</td>";
        echo "<td>" . $_SESSION['panier']['prix'][$w] . "</td>";
        echo "<td><a href='?action=retirer&id_article=" . $_SESSION['panier']['id_article'][$w] . "'>retirer</a></td>";
        echo "</tr>";
    }
    echo "<tr><th colspan='3'>TOTAL</th><td colspan='2'>" . montantTotal() . " euros</td></tr>";

    //conditions si le visiteur est connecté ou non-connecté :
    if(utilisateurEstConnecte()) //si l'utilisateur est connecté
    {
        echo '<form method="post" action="">';
        echo '<tr><td colspan="5"><input type="submit" name="payer" value="payer"></td></tr>';
        echo '</form>';
    }
    else  //si l'utilisateur n'est pas connecté
    {
        echo '<tr><td colspan="3">Veuillez-vous <a href="connexion.php">connecter</a> afin de pouvoir payer</td></tr>';
    }
    //proposer au visteur de vider son panier :
    echo "<tr><td colspan='5'><a href='?action=vider'>Vider le panier</a></td></tr>";
}
echo "</table>";





