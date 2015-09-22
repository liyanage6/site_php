<?php
require_once('inc/init.inc.php');
if(isset($_GET['id_article']) && $_GET['id_article'])//je récupère les informations dans l'URL
{
    //récupération des informations sur l'article :
    $resultat = informationSurUnArticle($_GET['id_article']);
//debug($resultat);
    if($resultat->num_rows <= 0) //la requête renvoie 0 : c'est-à-dire qu'il n'y a aucun article correspondant à l'id_article de l'url. Exemple : si l'id_article 29 n'est pas présent dans la BDD, alors num_rows sera égal à 0. Donc redirection avec la page boutique.php. Par contre, si l'id_article est présent dans la BDD, alors on affichera les informations propres à l'article en question !
    {
        header("location:boutique.php");
        exit(); //on stoppe TOTALEMENT le script !! on s'arrête là !
    }

    //j'inclus les parties de mon site :
    require_once('inc/haut_de_site.inc.php');
    require_once('inc/menu.inc.php');

    $article = $resultat->fetch_assoc(); //=> je rends exploitable les informations sur l'article à afficher

    #A présent, on affiche les informations :
    echo "<h3>Titre : $article[titre]</h3>";
    echo "<p>Description : $article[description]</p>";
    echo "<p>Catégorie : $article[categorie]</p>";
    echo "<p>Couleur : $article[couleur]</p>";
    echo "<p>Taille : $article[taille]</p>";
    echo "<img src='$article[photo]' width='150' height='150'>";
    echo "<p>Prix HT : $article[prix] € + TVA : 19.6%</p>";

    #gestion du stock :

    if($article['stock'] > 0) //s'il y a du stock disponible. C'est-à-dire si stock est supérieur à 0.
    {
        echo "Nombre d'article(s) disponible : $article[stock]<br>";

        echo "<form method='post' action='panier.php'>";
        echo "<input type='hidden' name='id_article' value='$article[id_article]'>"; //type="hidden". Car on va récupérer cette information qui inutile pour le client. Du coup, on cache cette information.
        echo "<label for='quantite'>Quantité</label>";
        echo '<select id="quantite" name="quantite">'; //en dehors de la boucle !!!
        for($i = 1; $i<= $article['stock'] && $i <= 5; $i++)
            //la boucle for interroge le stock et le 5 => pour voir si on ne dépasse pas ces valeurs
        {
            echo '<option>' . $i . '</option>';
        }
        echo '</select><br>';//en dehors de la boucle !!!
        echo '<input type="submit" name="ajout_panier" value="Ajout au panier">';
        echo '</form>';
    }
    else //sinon pas d'article !
    {
        echo 'Rupture de stock !';
    }
    echo "<br/><a href='boutique.php?categorie=" . $article['categorie'] . "'>Retour vers la catégorie : $article[categorie]</a>";
    echo "  <h2>Commentaires</h2>
            <form action='' method='post'>
                <h5>Rédiger votre commentaire:</h5>
                <textarea class='commentaire' name='commentaire'></textarea><br>
                <input value='Envoyer' name='envoyer' type='submit'>
            </form>
            ";
}
//debug($_SESSION);
//var_dump($_SESSION['utilisateur']['pseudo']);

if(isset($_POST['envoyer']) && $_POST['envoyer'] && utilisateurEstConnecte()){
    if(empty(trim($_POST['commentaire']))){
        echo "<div class='erreur'> Le commentaire est vide !</div>";

    }
    else
    {
        executeRequete("INSERT INTO commentaire (id_membre, pseudo, contenu, email) VALUES (".$_SESSION['utilisateur']['id_membre'].",
    '".$_SESSION['utilisateur']['pseudo']."', '$_POST[commentaire]','".$_SESSION['utilisateur']['email']."')");
        echo "<div class='validation'>Félicitations ! Vous venez d'ajouter un commentaire. Merci pour votre contribution</div> ";

    }
}
elseif(isset($_POST['envoyer']) && $_POST['envoyer'] && !utilisateurEstConnecte())
{
    echo "<div class='erreur'>Connectez-vous pour pouvoir commenter cette article</div> ";
}

$resultat = executeRequete("SELECT * FROM commentaire");
echo "Nombre de commentaire(s) pour cette article : " . $resultat->num_rows;

while($ligne = $resultat->fetch_assoc())
{
    echo "<p>".$ligne['pseudo']." a commenté: </p>";
    echo "<textarea class='commentaire' disabled>".$ligne['contenu']."</textarea>";

    if(utilisateurEstConnecteEtAdmin()){
    echo "<br><a href='?action=suppressionCom?id_commentaire=".$ligne['id_commentaire']."'> Supprimer</a>";
    }
}
/**
 * Suppresion d'un commentaire TODO A finir Non fonctionnel !
 */
if(isset($_GET['action']) && $_GET['action'] == "suppressionCom")
{
    $resultat = informationSurUnCommentaire($_GET['id_commentaire']);
    $commentaire_a_supprimer = $resultat->fetch_assoc();

    executeRequete("DELETE FROM commentaire WHERE id_commentaire='$commentaire_a_supprimer[id_commentaire]'");
    echo "<div class='validation'>Suppression du commentaire de  $commentaire_a_supprimer[nom] - id: $_GET[id_commentaire] - $commentaire_a_supprimer[email] - pseudo: $commentaire_a_supprimer[pseudo]
    </div>";
    header("location: fiche_article.php?id_article".$commentaire_a_supprimer['id_article']);
}





echo "</div>";
require_once("inc/footer.inc.php")
?>



