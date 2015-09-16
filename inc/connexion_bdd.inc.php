<?php

$mysqli = @new Mysqli("localhost","root","","site_php");
//le @ devant new, permet d'éviter le message d'erreur générer par PHP.
if($mysqli->connect_error) // CONNECT_ERROR retourne le message d'erreur de connexion Mysql
{
	die('Un problème est survenue lors de la connexion a la BDD : '.$mysqli->connect_error);
}
//var_dump($mysqli);


?>
