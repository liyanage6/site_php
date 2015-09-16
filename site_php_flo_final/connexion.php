<?php
require_once('inc/init.inc.php');


if(utilisateurEstConnecte())//si l'utilisateur est déjà connecté, il n'a pas à accéder à nouveau à cette page connexion.php
{
   header('location:profil.php');//permet de rediriger le membre connecté vers sa page profil
}

if(isset($_POST['connexion'])) //si le bouton submit fonction, j'effectue le code suivant : lorsque l'utilisateur se connectera, il puisse accéder à la page profil.php
{
  echo 'test'; 
  //1- on récupère les informations d'un membre en particulier : 
  $selection_membre =  executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
  //2- on vérifie si le pseudo existe : 
  if($selection_membre->num_rows !=0)
  {
    $membre = $selection_membre->fetch_assoc();
    if($membre['mdp'] == $_POST['mdp']) //si le mdp stocké en BDD est égal au mdp saisi par l'utilisateur
    {
      //$msg .='<div class="validation">Mot de passe okay !</div>'; //test : OK !
      foreach($membre as $indice => $valeurs)
      {
        if($indice != 'mdp')
        {
            $_SESSION['utilisateur'][$indice] = $valeurs;
        }
      }
      header("location:profil.php");//si le pseudo et le mdp sont corrects => accès page profil.php
    }
    else  //si le mdp est incorrect : message d'erreur : 
    {
      $msg .="<div class='erreur'>Mot de passe incorrect</div>";
    }
  }
  else//si le pseudo est incorrect : message d'erreur :
  {
      $msg .="<div class='erreur'>Pseudo incorrect</div>";    
  }
}
//j'inclus les parties de mon site : 
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');
echo $msg;  //affichage des messages
?>
<form method="post" action="">
  <label for="pseudo">Pseudo</label>
  <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" value="" ><br>
  <label for="mdp">Mot de passe</label>
  <input type="password" id="mdp" name="mdp" placeholder="mot de passe" value="" ><br>  
  <input type="submit" name="connexion" value="connexion">
</form>