<?php

function executeRequete($req)
{
	global $mysqli; //Permet d'avoir acces a la variable $mysqli définie dans l'espace GLOBAL

	$resultat = $mysqli->query($req); //Ici on execute la requete reçu en argument 

	if(!$resultat) //Si renvoie FALSE => ERREUR !
	{
		die('Erreur sur la requête SQL. <br> Message : '.$mysqli->error. " <br>Code : ".$req);
		//Si la requete échoue, on affiche le message correspondant a l'erreur en récupérant la propriété de l'objet $mysqli
	}
	return $resultat; //On retourne un objet issu de la class mysqli_result 
}

//------------------------------------------------------------------------------------------
function debug($var, $mode = 1)//fonction qui nous évitera de faire un VAR_DUMP ou un PRINT_R
{
	echo '<div style="background: #' . rand(100000,999999).'">'; // Juste pour styliser notre affichage debug
		if($mode=== 1)// Si $mode est strictement égal (===) à 1, on fait un PRINT_R
		{
			print"<pre>";print_r($var);print"</pre>";
		}
		else// Si $mode est différent de 1, on fait un VAR_DUMP
		{
			print"<pre>";var_dump($var);print"</pre>";
		}
		echo "<hr>";
		$trace=debug_backtrace();// Fonction prédéfinie retournant un array contenant des informations tel que la ligne et le fichier ou est executé la fonction
		$trace=array_shift($trace);//extrait la première valeur d'un tableau et nous la retourne
		echo "Debug demandé dans le fichier : $trace[file] à la ligne $trace[line]";

	echo "</div>";
	return;
}

//---------------------- FONCTION UTILISATEUR
function utilisateurEstConnecte() // c'est un fonction qui m'indiquera si l'utilisateur est connecté 
{
	if(!isset($_SESSION['utilisateur'])) //Si la session utilisateur est non-définie
	{
		return false;
	}
	else
	{
		return true;
	}
}

function utilisateurEstConnecteEtAdmin()
{
	if(utilisateurEstConnecte() && $_SESSION['utilisateur']['statut']==1)//si le statut est égal à 1, c'est forcément l'administrateur du site (cf. statu champ BDD)
	{
		return true;
	}
	return false;
	
}


//Fonction afin de vérifier l'extension des photos uploadées:
function verificationExtensionPhoto()
{
	//cf http://php.net/manual/fr/function.strrchr.php
	$extension = strrchr($_FILE['photo']['name']);
	// strrchr() me permet de trouver la dernière occurrence d'un caractère dans une chaîne
	
	$extension = strtolower(substr($extension),1); // => nous coupons le point pour transformer par exemple :".jpg en jpg" 
	// strtolower : permet de formater une chaine de caractère en miniscule.
	//cf http://php.net/manual/fr/function.strtolower.php

}






?>