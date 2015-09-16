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
	//cf. http://php.net/manual/fr/function.strrchr.php
	//ici j'utilise la superglobale $_FILES qui me permet de d'uploader des fichiers. Il s'agit d'un array() : ici un tableau
	$extension = strrchr($_FILES['photo']['name'],'.');
	//fonction me permettant d'obtenir la dernière occurrence d'un caractère dans une chaine
	//cf. http://php.net/manual/fr/reserved.variables.files.php
	$extension = strtolower(substr($extension,1)); //=> nous coupons le point pour transformer par exemple : ".jpg en jpg"
	//cf. strtolower : permet de formater une chaine de caractères en minuscule. => http://php.net/manual/fr/function.strtolower.php

	$tab_extension_valide = array('gif','jpg','jpeg','png'); //liste des extensions autorisées
	//debug($_FILES);
	//A présent nous allons comparer les extensions formatéees (cf. => $extension) et les extensions permises définies dans notre variable : ($tab_extension_valide) en utilisant la fonction prédéfinie in_array() dont le principe est de comparer le contenu de deux tableaux array() : cf. http://php.net/manual/fr/function.in-array.php
	$verif_extension = in_array($extension,$tab_extension_valide);
	return $verif_extension;  //retournera TRUE OU FALSE en fonction de la comparaison
}

//Nous créons ici une fonction qui nous permettra d'obtenir des informations propres à un article
function informationSurUnArticle($id)
{
	$resultat = executeRequete("SELECT * FROM article WHERE id_article=$id");//si l'id_article correspond Ã  l'argument de notre fonction
	return $resultat;
}

/****************
FONCTIONS PANIER
 ****************/
function creationDuPanier()
{
	if(!isset($_SESSION['panier'])) //si le panier (la SESSION) n'existe pas : on le crée
	{
		$_SESSION['panier'] = array();
		$_SESSION['panier']['titre'] = array();
		$_SESSION['panier']['id_article'] = array();
		$_SESSION['panier']['quantite'] = array();
		$_SESSION['panier']['prix'] = array();
	}
	return true;
	//soit le panier n'existe pas : on le crée et on retourne true
	//soit le panier existe déjà, on return true directement
}

/************************************
FONCTION AJOUTER UN ARTICLE AU PANIER
 ************************************/
function ajouterArticleDansPanier($titre,$id_article,$quantite,$prix)
{
#on veut tout d'abord savoir si l'id_article que l'on souhaite ajouter est déjà présent dans notre panier....
	$position_article = array_search($id_article,$_SESSION['panier']['id_article']); //Recherche dans un tableau la clé associée à une valeur
	// var_dump($_SESSION['panier']['id_article']);
	//cf. http://php.net/manual/fr/function.array-search.php

#si le produit est déjà présent dans le panier : donc true => !== FALSE
	if($position_article !== FALSE)
	{
		$_SESSION['panier']['quantite'][$position_article] += $quantite; //nous allons précisemment à l'indice de ce produit et nous allons lui rajouter une nouvelle quantité (exemple : +1)

	}
	else  //dans le cas contraire : si le produit est absent du panier, on ajoute l'id_article du produit dans un nouvel indice du tableau.
	{
		$_SESSION['panier']['titre'][] = $titre; //on récupère les arguments de notre fonction
		$_SESSION['panier']['id_article'][] = $id_article;
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['prix'][] = $prix;
	}
}
/***********************
FONCTION MONTANT PANIER
 ***********************/
function montantTotal() //fonction qui va nous permettre de calculer le montant total de notre panier
{
	$total = 0;
	for($i=0; $i < count($_SESSION['panier']['id_article']); $i++)
	{
		$total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];//on multiplie la quantité par le prix. Ex: 1*10€ ou 3*30€. Sans remplacer pour autant la dernière valeur contenue dans la variable $total (+=)
	}
	return round($total,2);//(ici deux chiffres après la virgule)
}

/************************************
FONCTION RETIRER UN ARTICLE DU PANIER
 *************************************/
function retirerArticleDuPanier($id_article_a_supprimer)
{
	#on récupère la position de id_article dans notre panier donc utilisation de la fonction array_search(). Qui nous retournera un chiffre afin de savoir à quel indice se trouve le produit à supprimer.
	$position_article = array_search($id_article_a_supprimer, $_SESSION['panier']['id_article']);

	#si le produit est présent dans le panier : on le retire :
	if ($position_article !== FALSE) // == TRUE
	{
		#on utilise un array_splice() qui efface et remplace une portion de tableau => retire un élément et réordonne les indices en conséquence
		//cf. http://php.net/manual/fr/function.array-splice.php
		array_splice($_SESSION['panier']['titre'], $position_article, 1); //l'élément supprimé sera remplacé par l'élément suivant (cf. 1)
		array_splice($_SESSION['panier']['id_article'], $position_article, 1);
		array_splice($_SESSION['panier']['quantite'], $position_article, 1);
		array_splice($_SESSION['panier']['prix'], $position_article, 1);

		#array_splice() != array_slice()

	}
}






	?>