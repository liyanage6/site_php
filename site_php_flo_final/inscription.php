<?php
require_once('inc/init.inc.php');

//Si l'utilisateur est connecté : il est redirigé vers son profil
if(utilisateurEstConnecte()){
  header('location:profil.php');
}





if(isset($_POST['inscription']))
{
  #echo'test'; //OK !
  #debug($_POST); //juste pour le test !!
  $verif_caracteres = preg_match('#^[a-zA-Z0-9._-]+$#',$_POST['pseudo']);
  //echo $verif_caracteres;
  if(!$verif_caracteres && !empty($_POST['pseudo']))
    //si l'utilisateur a posté un pseudo et qu'il a un mauvais caractère
  {
    $msg .= "<div class='erreur'>Caractères acceptés : A à Z et de 0 à 9</div>";
  }
  //vérification de la taille du pseudo
  if(strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 14) //on aurait également pu utiliser la fonction trim()
  {
    $msg .= "<div class='erreur'>Le pseudo doit être compris entre 4 et 14 caractères</div>";
  }
    //vérification de la taille du mot de passe
  if(strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 14)//on aurait également pu utiliser la fonction trim()
  {
    $msg .= "<div class='erreur'>Le mot de passe doit être compris entre 4 et 14 caractères</div>";
  }    
  if(empty($msg)) //si la variable $msg est vide => donc pas d'erreurs !
  {
    //on vérifie dans un premier temps si le pseudo est unique : 
    $membre = executerequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
    if($membre->num_rows > 0) //si supérieur à 0 : le pseudo est déjà pris
    {
       $msg .= "<div class='erreur'>Pseudo indisponible</div>";
    }
    else  //dans le cas contraire : le pseudo est unique : => on lance l'inscription
    {
      foreach($_POST as $indices => $valeurs)
      {
        $_POST[$indices] = htmlEntities(addslashes($valeurs));
      }
      executeRequete("INSERT INTO membre (pseudo,mdp,nom,prenom,email,sexe,ville,cp,adresse) VALUES ('$_POST[pseudo]','$_POST[mdp]','$_POST[nom]','$_POST[prenom]','$_POST[email]','$_POST[sexe]','$_POST[ville]','$_POST[cp]','$_POST[adresse]')");
      
      $msg .="<div class='validation'>Félicitations !! vous voilà inscrit</div>";
    }
      
  }



  
  
/*preg_match() : expression régulière qui est toujours entourée de # afin de préciser des options : 
      -> ^ indique le début de la chaine
      -> $ indique la fin de la chaine
      -> + pour dire que les lettres autorisées peuvent apparaître plusieurs fois (exemple : la lettre A peut être empruntée plusieurs fois)
//retourne 0 si présence d'un mauvais caractère
//retourne 1 si l'expression est correcte

*/
#cf.http://openclassrooms.com/courses/concevez-votre-site-web-avec-php-et-mysql/les-expressions-regulieres-partie-1-2
#cf. http://php.net/manual/fr/function.preg-match.php
  
  
  
}


//j'inclus les parties de mon site : 
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');

echo $msg;  //affichage des messages
?>
      <form method="post" action="">
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" value="" maxlength="14" placeholder="pseudo" title="caractères acceptés : a-zA-Z0-9_." ><br>
        <!--required="required" pattern="[a-zA-Z0-9_.]"-->
        <label for="mdp">Mot de passe</label>
        <input type="text" id="mdp" name="mdp" value="" maxlength="14" placeholder="mot de passe" title="caractères acceptés : a-zA-Z0-9_." ><br>

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value=""  placeholder="nom" title="caractères acceptés : a-zA-Z0-9_." ><br>
        
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="" placeholder="prenom" title="caractères acceptés : a-zA-Z0-9_." ><br>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="" placeholder="email" title="votre email" ><br>
        
        <label for="sexe">Sexe</label>
        <input type="radio" name="sexe" value="m" checked>Homme
        <input type="radio" name="sexe" value="f">Femme<br>
        
        <label for="ville">ville</label>
        <input type="text" id="ville" name="ville" value="" placeholder="ville" title="caractères acceptés : a-zA-Z0-9_."><br>
        
        <label for="cp">Code Postal</label>
        <input type="text" id="cp" name="cp" value="" placeholder="Code postal" title="5 chiffres requis : [0-9]" maxlength="5" ><br>  
        
        <label for="adresse">Adresse</label>
        <textarea id="adresse" name="adresse" placeholder="adresse" title="caractères acceptés : a-zA-Z0-9_." ></textarea><br><br>
        <input type="submit" name="inscription" value="inscription">
      </form>
    </div>
  </body>
</html>
<?php 
//require_once('inc/footer.inc.php');
?>






