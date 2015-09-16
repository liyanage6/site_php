<?php
require_once('inc/init.inc.php');

//Si l'utilisateur n'est pas connecté : il est redirigé vers la page d'inscription
if(!utilisateurEstConnecte()){
  header('location:connexion.php');
}
//j'inclus les parties de mon site : 
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');

//debug($_SESSION); //voir les informations contenues dans $_SESSION
print "<p> Bonjour  " .$_SESSION['utilisateur']['pseudo'] ."</p>";
print "<p>Votre Email : " .$_SESSION['utilisateur']['email'] ."</p>";
print "<p>Votre nom : " .$_SESSION['utilisateur']['nom'] ."</p>";
print "<p> Votre prénom  " .$_SESSION['utilisateur']['prenom'] ."</p>";
print "<p> Votre Adresse  " .$_SESSION['utilisateur']['adresse'] ."</p>";
print "<p> Votre Code postal  " .$_SESSION['utilisateur']['cp'] ."</p>";
print "<p> Votre Ville  " .$_SESSION['utilisateur']['ville'] ."</p>";

if($_SESSION['utilisateur']['statut'] == 1)
{
  echo 'vous êtes admin';
}
else
{
  echo 'vous êtes un simple membre';
}

echo $msg;  //affichage des messages




