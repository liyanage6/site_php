<?php
/**
 * Connection a la BDD sur serveur
     * $mysqli = @new Mysqli("cl1-sql18","liyanage1","eytOiM92Qs1s","liyanage1");
 */

/**
 * Connection a la BDD en local
 */
$mysqli = @new Mysqli("localhost","root","root","site_php");
//le @ devant new, permet d'éviter le message d'erreur générer par PHP.
if($mysqli->connect_error) // CONNECT_ERROR retourne le message d'erreur de connexion Mysql
{
	die('Un problème est survenue lors de la connexion a la BDD : '.$mysqli->connect_error);
}
//var_dump($mysqli);


?>
