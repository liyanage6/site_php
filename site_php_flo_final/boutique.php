﻿<?php
require_once('inc/init.inc.php');
//j'inclus les parties de mon site : 
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');

//AFFICHAGE DES CATEGORIES : 
  $categorie_des_articles = executeRequete("SELECT DISTINCT categorie FROM article");//éviter les doublons

//affichage liens catégories : 
echo "<div class='gauche'>";
echo "<ul>";
while($cat = $categorie_des_articles->fetch_assoc())
{
  echo "<li><a href='?categorie=". $cat['categorie'] ."'>" .$cat['categorie'] . "</a></li>" ;
}

echo "</ul></div>";

//affichage articles : 
echo '<div class="droite">';
  if(isset($_GET['categorie']))//je récupère l'indice 'categorie' de l'url
  {
    $donnees = executeRequete("SELECT id_article,reference,titre,photo,prix FROM article WHERE categorie='$_GET[categorie]'");
    
    while($article = $donnees->fetch_assoc()) //je récupère les informations
    {
      echo '<div>';
      echo "<h3>$article[titre]</h3>";
      echo "<img src='$article[photo]' width='80' height='80'><br>";
      echo "<a href='fiche_article.php?id_article=$article[id_article]'>lien vers $article[titre]</a>";
      echo '</div>';      
    }
  }
echo '</div>';













