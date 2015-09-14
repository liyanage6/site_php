<?php
require_once('inc/init.inc.php');


if(utilisateurEstConnecte())//Si l'utilisateur est deja connecté, il n'a pas à accéder à nouveau à cette page connexion.php
{
	header('location:profil.php'); //Permet de rediriger le membre connecté vers sa page profil
}

if (isset($_POST['connexion'])) //Si le bouton foncctionne, j'effectue le code suivant : lorque 
//l'utilisateur se connectera, il puisse accéder à la page profil.php
{ 
	//echo "OKAY BABA !!";
	//1- on récupère les informations d'un membre en particulier 
	$selection_membre = executeRequete("SELECT * FROM membre WHERE pseudo ='$_POST[pseudo]'");

	//2- On vérifie si le pseudo existe : 
	if($selection_membre->num_rows !=0)
	{
		$membre = $selection_membre->fetch_assoc();
		if($membre['mdp'] == $_POST['mdp'])//Si le mdp stoké en BDD est égal  au mdp saisi par l'utilisateur :
		{
			//$msg .= '<div class= validation >Mot de passe OK !</div>';
			foreach($membre as $indice => $valeur)
			{
				if($indice != 'mdp')
				{
					$_SESSION['utilisateur'][$indice]=$valeur;
				}
			}
            /**
			header("location:profil.php"); //Si le pseudo et le mdp sont corrects => accés page profil
            */
		}
		else //Si le mdp et/ou le pseudo sont incorrects : mesage d'erreur : 
		{
			$msg .= "<div class='erreur'> Mot de passe incorrect </div>";
		}
	}
	else //Si le pseudo est incorrect : message d'erreur 
	{
		$msg .= "<div class='erreur'> Pseudo incorrect </div>";
	}






}

//j'inclus les parties de mon site : 
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');
echo $msg; //affichage des messages



?>

		<form method="POST" action="">
		    <label for="pseudo">Pseudo</label>
		    <input type="text" name="pseudo" id="pseudo" value=""  placeholder="pseudo" >
		    <br>

		    <label for="mdp">Mot de passe</label>
		    <input type="password" name="mdp" id="mdp" value=''  placeholder="mot de passe" >
		    
		    <br><br>
			

			<input type="submit" name="connexion" value="connexion">

		</form>




