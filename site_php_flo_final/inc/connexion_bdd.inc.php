<?php
$mysqli = @new Mysqli("localhost","root","","site_php");
//le @ permet d'éviter le message d'erreur généré par PHP. 
if($mysqli->connect_error)
  //connect_error retourne le message d'erreur de connexion Mysql
{
  die('Un prtoblème est survenu lors de la tentative de connexion à la BDD : ' . $mysqli->connect_error);
}
