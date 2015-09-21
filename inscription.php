<?php
require_once("inc/init.inc.php");

//Si l'utilisateur est pas connecté : il est redirigé vers sa page profil
if(utilisateurEstConnecte()){
	header('location:profil.php');
}

/**
 * TODO
 */
if(isset($_POST['submit']))
{
	//echo "Bouton Okay";
	//debug($_POST);
	
	$verif_caracteres = preg_match('#^[a-zA-Z-0-9._-]+$#', $_POST['pseudo']);
	//PREG_MATCH(pattern,subject) : expression regulière qui est toujours entouré de # afin de préciser des options 
	//echo $verif_caracteres;
    if(!$verif_caracteres && !empty($_POST['pseudo']))
        //Si l'utilisateur a posté un pseudo et qu'il a un mauvais caractère
    {
        $msg .= "<div class='erreur'>Caractères accepté : A à Z et de 0 à 9 </div>";
    }
    // Vérification de la taille du pseudo
    if(strlen($_POST['pseudo'])<4 || strlen($_POST['pseudo'])>14)
    {
        $msg .= "<div class='erreur'>Le pseudo doit être compris entre 4 et 14 caractères </div>";
    }

	$verif_caracteres = preg_match('#^[a-zA-Z-0-9._-]+$#', $_POST['mdp']);
	// Vérification de la taille du mdp
    if(strlen($_POST['mdp'])<4 || strlen($_POST['mdp'])>14)
    {
        $msg .= "<div class='erreur'>Le mot de passe doit être compris entre 4 et 14 caractères </div>";
    }
    elseif($_POST['mdp'] != $_POST['mdp2']){
        $msg .= "<div class='erreur'>Les mots de passe doient être les même !</div>";
    }

    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $msg .= "<div class='erreur'>Le mail n'est pas valide ! Merci de réessayer</div>";
    }

    if(empty($msg))//Si variable $msg est vide -> donc pas d'erreur
    {
        //On vérifie dans un premier temps si le pseudo est unique :
        $membre = executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
        if($membre->num_rows > 0 )//Si supèrieur a 0 : le pseudo est déjà pris
        {
            $msg .= "<div class='erreur'>Pseudo indisponible </div>";
        }
        else //Dans le cas contraire : le pseudo est unique : => on lance l'inscription
        {
            foreach ($_POST as $indices => $valeurs)
            {
                $_POST[$indices] = htmlentities(addslashes($valeurs));
            }
            executeRequete("INSERT INTO membre (pseudo,mdp,nom,prenom,email,sexe,ville,cp,adresse)
                            VALUES ('$_POST[pseudo]','$_POST[mdp]','$_POST[nom]','$_POST[prenom]','$_POST[email]','$_POST[sexe]','$_POST[ville]','$_POST[cp]','$_POST[adresse]')");
			//header('location: boutique.php');
            $msg .= "<div class='validation'>Félicitation !! Vous êtes inscrit ! </div>";
        }
    }
}



require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');

echo $msg; //Affichage des messages 

?>
			<form method="POST" action="">
			    <label for="pseudo">Pseudo</label>
			    <input type="text" name="pseudo" id="pseudo" value="" maxlength="14" placeholder="pseudo" 
			    	title="caractères acceptés : a-zA-Z-0-9_-." >
			    <br>

			    <label for="mdp">Mot de passe</label>
			    <input type="password" name="mdp" id="mdp" value='' maxlength="14" placeholder="mot de passe"
			    	title="caractères acceptés : a-zA-Z-0-9_-." >
			    <br>

                <label for="mdp2">Confirmez votre mot de passe</label>
                <input type="password" name="mdp2" id="mdp" value='' maxlength="14" placeholder="mot de passe"
                       title="caractères acceptés : a-zA-Z-0-9_-." >
                <br>

			    <label for="nom">Nom</label>
			    <input type="text" name="nom" id="nom" value='' placeholder="nom" 
			    	title="caractères acceptés : a-zA-Z-0-9_-." >
			    <br>

			    <label for="prenom">Prenom</label>
			    <input type="text" name="prenom" id="prenom" value='' placeholder="prenom" 
			    	title="caractères acceptés : a-zA-Z-0-9_-." >
			    <br>

			    <label for="email">email</label>
			    <input type="text" name="email" id="email" value='' placeholder="email" 
			    	title="votre email">
			    <br>
				
				<label for="sexe">Sexe</label>
				<input type="radio" name="sexe" value="M" checked>Homme
				<input type="radio" name="sexe" value="F" >Femme
				<br>

				<label for="ville">Ville</label>
				<input type="text" id="ville" name="ville" value="" placeholder="ville" 
			    	title="caractères acceptés : a-zA-Z-0-9_-." >
			    <br>

				<label for="cp">Code postal</label>
				<input type="text" id="cp" name="cp" value="" placeholder="code postal" maxlength="5" title="5 chiffres requis : [0-9]"
			    	>
			    <br>

			    <label for="adresse">Adresse</label>
			    <textarea type="text" name="adresse" id="adresse" value="" placeholder="adresse" 
			    	title="caractères acceptés : a-zA-Z-0-9_-." ></textarea>
			    <br><br>
				

				<input type="submit" name="submit" value="Inscription">

			</form>
		</div>
<?php
//require_once('inc/footer.inc.php');
?>
	</body>
</html>
