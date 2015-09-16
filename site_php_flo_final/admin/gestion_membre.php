<?php
require_once("../inc/init.inc.php");

if(!utilisateurEstConnecteEtAdmin())//si le connecté n'est pas administrateur...
{
	header("location:../connexion.php"); //...je renvoie vers la page connexion.php
	exit(); //et je stoppe le script !
}
if(isset($_GET['msg']) && $_GET['msg'] == "suppression")
{
	executeRequete("DELETE FROM membre WHERE id_membre=$_GET[id_membre]");
	header("Location:gestion_membre.php"); //après la suppression, je renvoie l'admin vers la page gestion_membre.php
}
//------ Affichage ------------------//
require_once("../inc/haut_de_site.inc.php");
require_once("../inc/menu.inc.php");

echo '<h1> Voici les membres inscrits au site </h1>';
  //je récupère toutes les informations relatives aux membres
	$resultat = executeRequete("SELECT * FROM membre");
  
  //je compte le nombre de résultat : 
	echo "Nombre de membre(s) : " . $resultat->num_rows;
  
  //je récupère le nombre de champs : 
	$nbcol = $resultat->field_count;
	echo "<table style='border-color:red' border=10> <tr>";
	for ($i=0; $i < $nbcol; $i++)
	{   
  //j'affiche le nom des champs : 
		$colonne = $resultat->fetch_field(); 
		echo '<th>' . $colonne->name . '</th>';
	}
	echo '<th> Supprimer </th>';
	echo "</tr>";
  
  //je récupère le contenu : 
	while ($membre = $resultat->fetch_assoc())
	{
		echo '<tr>';//début création de lignes pour stocker un utilisateur. Celle-ci se répètera tant qu'il y a des résultats
		foreach ($membre as $information)
		{
			echo '<td>' . $information . '</td>';
		}
		echo "<td><a href='gestion_membre.php?msg=suppression&&id_membre=" . $membre['id_membre'] . "' onclick='return(confirm(\"Etes-vous sûr de vouloir supprimer ce membre?\"));'> X </a></td>";
		echo '</tr>';//fin création de lignes pour stocker un utilisateur. 
	}
	echo '</table>';