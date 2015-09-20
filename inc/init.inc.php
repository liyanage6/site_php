<?php
session_start();
// Fichier d'initialisation, il sera inclus dans toute les pages
require_once('connexion_bdd.inc.php');
require_once('fonctions.inc.php');
// Je créer ma session pour les utilisateurs connectés. Je la place ici car le init.inc.php est inclu dans l'ensemble de mes pages.


/**
 * Pour éviter d'avoir des problème de liens :
 * define('RACINE_SITE', '/'); (Pour le site en serveur)
 */
define('RACINE_SITE', '/PHP/site_php/');

// Si le site venait a être transferet sur internet, on définit un chemin automatique : 
define('RACINE_SERVEUR', $_SERVER['DOCUMENT_ROOT']);
//var_dump($_SERVER);

//Cette variable vide nous permettra de placer du texte comme par exemple pour généré un texte d'erreur en cas de mauvaise saisie d'un formulaire.
$msg="";

?>