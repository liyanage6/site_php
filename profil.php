<?php
require_once('inc/init.inc.php');

//Si l'utilisateur n'est pas connecté : il est redirigé vers la page d'inscription
if(!utilisateurEstConnecte()){
	header('location:inscription.php');
}

//j'inclus les parties de mon site :
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');

/**
 * TODO: Modification du profil
 */
//debug($_SESSION); //Voir les informations contenues dans $_SESSION
echo "<div class='profil'>";
print"<p>Bonjour ".$_SESSION['utilisateur']['pseudo']." !</p>";
print"<p>Votre email ".$_SESSION['utilisateur']['email']."</p>";
print"<p>Votre nom ".$_SESSION['utilisateur']['nom']."</p>";
print"<p>Votre prenom ".$_SESSION['utilisateur']['prenom']."</p>";
print"<p>Votre adresse ".$_SESSION['utilisateur']['adresse']."</p>";
print"<p>Votre code postal ".$_SESSION['utilisateur']['cp']."</p>";
print"<p>Votre ville ".$_SESSION['utilisateur']['ville']."</p>";

if($_SESSION['utilisateur']['statut']==1){
	echo "<p>Vous êtes administrateur du site </p>";
}
else
	{
		echo "Vous êtes un utilisateur ! ";
	}
echo "</div>";

echo $msg;  //affichage des messages

echo "</div>";
require_once("inc/footer.inc.php");