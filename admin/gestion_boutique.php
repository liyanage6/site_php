<?php
require_once("../inc/init.inc.php");
//ici c'est le BACKOFFICE : donc restreindre l'accès à cette partie. Uniquement visible pour l'administrateur : 
if(!utilisateurEstConnecteEtAdmin())
{
    header("location:../connexion.php"); //redirection pour tous les membres qui ne sont pas administrateurs (donc tous les autres connectés dont la statut est égal à 0 mais aussi les simples visiteurs !!). Seuls les membres ayant un statut == 1 ont accès à cette page. Cf. BDD
    exit(); //permet de stopper l'éxécution du script
}
###############################################
#ENREGISTREMENT : AJOUT OU MODIFICATION ARTICLE
###############################################
//1- on teste le bouton submit : 
if(isset($_POST['enregistrement']))
{
    //echo 'test'; //ok !
    //debug($_POST);
    $reference = executeRequete("SELECT reference FROM article WHERE reference='$_POST[reference]'");
    if($reference->num_rows != 0 && isset($_GET['action']) && $_GET['action']=='ajout') //double vérif !!
    {
        $msg .= '<div class="erreur">La référence est déjà attribuée. Veuillez vérifier votre saisie.</div>';
    }
    else
        //si la référence existe et que nous sommes dans le cas d'un ajoût (cf. $_GET = récup URL)
    {
        //je crée ici une variable vide pour éviter une erreur si l'administrateur ne poste pas de photo :


        //ICI ON S'OCCUPE DE L'UPLOAD DE LA PHOTO :
        $photo_bdd = "";
        if(isset($_GET['action']) && $_GET['action'] == 'modification')
        {
            //en cas de modification, nous récupérons la photo déjà uploadée :
            $photo_bdd = $_POST['photo_actuelle'];//je place ma photo dans ma variable vide !

        }
        if(!empty($_FILES['photo']['name'])) //s'il y a bien une photo !! c'est-à-dire inverse de vide (!empty)
        {
            if(verificationExtensionPhoto()) //je vérifie l'extension de la photo : est-ce que l'extension est en minuscule ? si oui :
            {
                $nom_photo = $_POST['reference'] . '_' .$_FILES['photo']['name']; //on renomme la photo en nous récupérant la référence UNIQUE de notre article
                $photo_bdd = RACINE_SITE . "photo/$nom_photo"; //ici on pointe le chemin où la photo sera enregistrer
                $photo_dossier = RACINE_SERVEUR . RACINE_SITE . "/photo/$nom_photo";  //on récupère le chemin de la photo placée dans le dossier temporaire
                copy($_FILES['photo']['tmp_name'],$photo_dossier); //on copie la photo du dossier temporaire (=> $_FILES['photo']['tmp_name']) dans le dossier de réception (=> $photo_dossier)
                //cf. http://php.net/manual/fr/function.copy.php
            }
            else
            {
                $msg .= "<div class='erreur'>L'extension n'est pas valide</div>";
            }
        }
        if(empty($msg)) //si aucun message d'erreurs n'a été généré on passe directement à cette condition :
        {
            $msg .= '<div class="validation">Enregistrement de l\'article</div>';
            //je modifie les données déjà présente par les nouvelles saisies
            executeRequete("REPLACE INTO article (id_article,reference,categorie,titre,description,couleur,taille,sexe,photo,prix,stock) VALUES ('$_POST[id_article]', '$_POST[reference]', '$_POST[categorie]', '$_POST[titre]', '$_POST[description]', '$_POST[couleur]', '$_POST[taille]', '$_POST[sexe]',  '$photo_bdd',  '$_POST[prix]',  '$_POST[stock]')");
            $_GET['action'] = 'affichage';
        }
    }
}

if(isset($_GET['action']) && $_GET['action'] == "suppression")
{

    $resultat = informationSurUnArticle($_GET['id_article']);
    $article_a_supprimer = $resultat->fetch_assoc();
    //debug($resultat);debug($article_a_supprimer);
    $chemin_photo_a_supprimer = RACINE_SERVEUR . $article_a_supprimer['photo'];
    if(!empty($article_a_supprimer['photo']) && file_exists($chemin_photo_a_supprimer))
    {
        unlink($chemin_photo_a_supprimer);
        //cf. http://php.net/manual/fr/function.unlink.php
    }
    echo "<div class='validation'>Suppression de l'article : $_GET[id_article] - $article_a_supprimer[titre] -
    $article_a_supprimer[reference]
    </div>";
    executeRequete("DELETE FROM article WHERE id_article=$_GET[id_article]");
    $_GET['action'] = 'affichage';
}

include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");
echo $msg;
echo '<ul><li> <a href="?action=affichage">Affichage des articles</a></li><li><a href="?action=ajout">Ajout d\'un
article</a></li></ul><hr
 />';

if(isset($_GET['action']) && $_GET['action'] == "affichage")
{
    echo '<div style="background: #c0c0c0;padding: 10px; ">
	<h1> Affichage des Articles </h1>';
    $resultat = executeRequete("SELECT * FROM article");
    echo "Nombre d'article(s) dans la boutique : " . $resultat->num_rows;

    $nbcol = $resultat->field_count;
    echo "<table style='border-color:red' border=10> <tr>";
    for ($i=0; $i < $nbcol; $i++)
    {
        $colonne = $resultat->fetch_field();
        echo '<th>' . $colonne->name . '</th>';
    }
    echo "<th>Modif.</th>";
    echo "<th>Supr.</th>";
    echo "</tr>";

    while ($ligne = $resultat->fetch_assoc())
    {
        //crée-moi autant de lignes <tr> qu'il y a de résultats dans la BDD (utilisation de fecth_assoc() qui nous ressort les informations d'array(). Donc récupération par l'intermédiaire d'une boucle foreach()
        echo '<tr>';
        foreach ($ligne as $indice => $information)
            //on récupère les indices et à les informations. Exemple : $article['id_article'] = 1
        {
            if($indice == "photo")//s'il trouve un élément ayant un indice 'photo', affichage particulier : emploi de la balise <img>
            {
                echo "<td><img src='" . $information . "' width='70' height='70' /></td>";
            }
            else  //dans le cas contraire (indices != 'photo')
            {
                echo "<td>" . $information . "</td>";
            }
        }
        echo '<td><a href="?action=modification&id_article=' . $ligne['id_article'] .'">---</a></td>';
        echo '<td><a href="?action=suppression&id_article=' . $ligne['id_article'] .'" OnClick="return(confirm(\'En êtes vous certain ?\'));">x</a></td>';
        echo '</tr>';
    }
    echo '</table>';
    echo "</div>";
}


if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification'))
{
    if(isset($_GET['id_article']))
    {
        $resultat = informationSurUnArticle($_GET['id_article']);
        $article_actuel = $resultat->fetch_assoc();
        print_r($article_actuel);
    }
    ?>

    <div style="background: #684322;padding: 10px;">
        <h1> Formulaire Article </h1>
        <form method="post" enctype="multipart/form-data" action=""> <!--enctype est très important pour l'upload des fichiers-->

            <input type="hidden" id="id_article" name="id_article" value="<?php if(isset($article_actuel['id_article'])) echo $article_actuel['id_article']; ?>" />
            <!--l'attribut 'value=""' permet de conserver les valeurs déjà définies de nos éléments. -->
            <label for="reference">reference</label>
            <input type="text" id="reference" name="reference" value="<?php if(isset($article_actuel['reference'])) echo $article_actuel['reference']; elseif(isset($_POST['reference'])) echo $_POST['reference']; ?>" <?php if(isset($article_actuel)) echo 'readonly'; ?> /> <br />

            <label for="categorie">categorie</label>
            <input type="text" id="categorie" name="categorie" value="<?php if(isset($article_actuel['categorie']))  echo $article_actuel['categorie']; elseif(isset($_POST['categorie'])) echo $_POST['categorie']; ?>"/><br />

            <label for="titre">titre</label>
            <input type="text" id="titre" name="titre" value="<?php if(isset($article_actuel['titre']))  echo $article_actuel['titre']; elseif(isset($_POST['titre'])) echo $_POST['titre']; ?>"/> <br />

            <label for="description">description</label>
            <textarea name="description" id="description" value=""><?php if(isset($article_actuel['description'])) echo $article_actuel['description']; elseif(isset($_POST['description'])) echo $_POST['description']; ?></textarea><br />

            <label for="couleur">couleur</label>
            <input type="color" id="couleur" name="couleur" value="<?php if(isset($article_actuel['couleur']))  echo $article_actuel['couleur']; elseif(isset($_POST['couleur'])) echo $_POST['couleur']; ?>"/> <br />

            <label for="taille">Taille</label>
            <select name="taille">
                <option value="S"/>S</option>
                <option value="M"<?php if(isset($article_actuel) && $article_actuel['taille'] == 'M') echo ' selected'; ?>>M</option>
                <option value="L"<?php if(isset($article_actuel) && $article_actuel['taille'] == 'L') echo ' selected'; ?>> L</option>
                <option value="XL"<?php if(isset($article_actuel) && $article_actuel['taille'] == 'XL') echo ' selected'; ?>> 	XL</option>
            </select><br />

            <label for="sexe">sexe</label>
            <input type="radio" name="sexe" value="m" <?php if(isset($_POST['sexe']) && $_POST['sexe'] == 'm') echo 'checked'; elseif(isset($article_actuel) && $article_actuel['sexe'] == 'm') echo 'checked'; elseif(!isset($article_actuel) && !isset($_POST['sexe'])) echo 'checked'; ?> />Homme</option>
            <input type="radio" name="sexe" value="f" <?php if(isset($_POST['sexe']) && $_POST['sexe'] == 'f') echo 'checked'; elseif(isset($article_actuel) && $article_actuel['sexe'] == 'f') echo 'checked'; ?> />Femme</option><br />

            <label for="photo">photo</label>
            <input type="file" id="photo" name="photo" /><br />

            <?php
            if(isset($article_actuel))
            {
                echo "Photo actuelle : <img src=\"$article_actuel[photo]\"  width=\"90\" height=\"90\" /><br />";
                echo "<input type=\"hidden\" name=\"photo_actuelle\" value=\"$article_actuel[photo]\" /><br />";
            }
            ?>
            <label for="prix">prix</label>
            <input type="text" id="prix" name="prix" value="<?php if(isset($article_actuel['prix'])) echo $article_actuel['prix']; elseif(isset($_POST['prix'])) echo $_POST['prix']; ?>"/><br />

            <label for="stock">stock</label>
            <input type="text" id="stock" name="stock" value="<?php if(isset($article_actuel['stock'])) echo $article_actuel['stock']; elseif(isset($_POST['stock'])) echo $_POST['stock'];  ?>"/> <br /> <br />

            <input type="submit" name="enregistrement" value="<?php echo ucfirst($_GET['action']); ?>"/>
        </form>
    </div>
    <?php
}
?>