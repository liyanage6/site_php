<?php
require_once('inc/init.inc.php');
if(isset($_GET['id_article']) && $_GET['id_article'])//je récupère les informations dans l'URL
{
  //récupération des informations sur l'article : 
  $resultat = informationSurUnArticle($_GET['id_article']);
}
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
?>










