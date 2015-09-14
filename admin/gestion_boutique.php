<?php
require_once('../inc/init.inc.php');

//Ici c'est la BACKOFFICE : donc restreindre l'accés à cette partie. Uniquement visible par l'admin
if(!utilisateurEstConnecteEtAdmin())
{
	header('location:../connexion.php'); //redirection pour tout les membres qui ne sont pas administrateur
	//(donc tous les autres connectés dont le statut = 0 mais aussi les simple visiteur !!!). Seul les membres ayant un statu == 1 ont accés 
	// à cette page. Cf BDD
	exit();//permet de stopper l'exécution du script
}
#######################################################
# Enregistrement : Ajout ou Modifiacation des Article #
#######################################################
//1- on test le bouton submit:

if(isset($_POST['ajouter']))
{
	//echo "Bouton OKAY"; // check
	//debug($_POST);
	
	$reference = executeRequete("SELECT reference FROM article WHERE reference='$_POST[reference]'");
	if($reference->num_rows > 0 && isset($_GET['action']) && $_GET['action']=="ajout")
	//si la référence existe et que nous sommes dans le cas d'un ajout(cf. GET = récup URL)
	{
		$msg .= "<div class='erreur'> La référence est déjà attribuée. Veuillez vérifier votre saisie</div>";
	}
	else //la référence est valable : l'artivle peut etre enregistré ! 
	{
		//je créer ici une variable vide pour éviter une erreur  si l'administrateur ne poste pas de photo:

		//ICI ON S'OCCUPE DE L'UPLOAD DE LA PHOTO
		$photo_bdd = "";
		if(isset($_GET['action']) && $_GET['action']=="modification")
		{
			//en cas de modification, nous récuperons la photo deja uploadé:
			$photo_bdd = $_POST['photo']; //je place ma photo dans ma variable vide !
		}
		if (!empty($_FILE['photo']['name'])) 
		{
			

			
		}
	}
}


//j'inclus les parties de mon site : 
require_once('../inc/haut_de_site.inc.php');
require_once('../inc/menu.inc.php');




?>

<!-- Création du formulaire d'ajout d'article ...-->
<div style="background : #ccc; padding:10px;">
	<h1>Formulaire</h1>
	<form method="post" enctype="multipart/form-data"><!-- enctype est très important pour l'upload des fichiers -->
		<input type="hidden" name="id_article" value="">

		<label for="reference">Référence</label>
		<input type="text" id="reference" name="reference" value=""><br>
		
		<label for="categorie">Catégorie</label>
		<input type="text" id="categorie" name="categorie" value=""><br>
		
		<label for="titre">Titre</label>
		<input type="text" id="titre" name="titre" value=""><br>
		
		<label for="description">Description</label>
		<textarea id="description" name="description" ></textarea cols="25" rows="7"><br>

		<label for="couleur">Couleur</label>
		<input type="text" id="couleur" name="couleur" value=""><br>
		
		<label for="taille">Taille</label>
		<select name="taille" id="taille">
			<option value="S">S</option>	
			<option value="M">M</option>
			<option value="L">L</option>
			<option value="XL">XL</option>
			<option value="XXL">XXL</option>
		</select><br>

		<label for="sexe">Sexe</label>
		<input type="radio" id="sexe" name="sexe" value="m" checked>homme
		<input type="radio" id="sexe" name="sexe" value="f">femme
		<br>
		
		<label for="photo">photo</label>
		<input type="file" id="photo" name="photo" value=""><br>
		
		<label for="prix">Prix</label>
		<input type="text" id="prix" name="prix" value=""><br>

		<label for="stock">stock</label>
		<input type="text" id="stock" name="stock" value=""><br>

		<input type="submit" name="ajouter" value="Ajouter">
	</form>



