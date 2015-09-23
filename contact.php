<?php
require_once('inc/init.inc.php');
?>

<!doctype html>
<html>
<head>
<title>HE</title>
<meta charset="utf-8" >
<link rel="stylesheet" type="text/css" href="inc/style.css"/>

<style type="text/css">
@font-face {
    font-family: 'Adine Kirnberg Alternate';
    src: url(inc/AdineKirnberg-Alternate.otf);
}
body{
  margin:0;
  padding:0;
  background-color: #cccccc;
  background: url(photo/bg.jpg);
    background-attachment: fixed;

}

h1.margegauche{
  font-size: 300%;
   border-bottom : 1px dashed #C0A028;
   border-left: 10px solid #C0A028;
   text-indent: 13px;
   font-family: Adine Kirnberg Alternate;

}


.margegauche{
  margin-left: 20px;
  margin-right: 20px;
}


form{
  margin:20px;
  padding : 0;
 /* background-color: olive;*/
}
label{
  display: block; /* La balise devient de type block. */
  width: 150px;
}
 #client,
 #professionel,
 #DE{
  display: inline;
}
#contact{
  margin-left: auto;
  margin-right: auto;
 background-color: #ffffff;
 width: 500px;
 min-height: 400px;
 border: 1px dashed black;
 background-color: rgba(2, 0, 0, 0.8);
 color: white;
 border: solid 2px #C0A028;
 border-radius: 10px;
}
textarea{
  min-height: 200px;
  max-height: 300px;
  min-width: 200px;
  max-width: 300px;
}

nav{
  margin-bottom: 50px;
  
}
@media screen and (max-width:480px){   /* CSS pour les petit ecrans */
  #contact{
  margin-left: auto;
  margin-right: auto;
 background-color: #ffffff;
 width: auto;
 min-height: 400px;
 border: 1px dashed black;
 background-color: rgba(2, 0, 0, 0.8);
 color: white;
}
}
</style>


</head>
<body>
<div class="conteneur">
  <header>
      <a href="http://facebook.fr">
        <img class="social" src="photo/icone-facebook.png" alt="icone faceook">
      </a>
      <a href="http://twitter.fr">
        <img class="social" src="photo/icon-twiter.png" alt="icone twiter">
      </a>
    

      <p class="entete-droite"><a href="contact.php"> Contact </a></p>
      <p class="entete-droite"><a href="index.php"> Acceuil </a></p>
      <a href="index.php"><img class="logoHE" src="photo/HE-logo.png" alt="logo he"></a>
      <h1 class="titre-entete"><a href="index.php"> </a></h1>
      
      
    </header>
    <?php
    if(isset($_POST['envoyer']))
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $expediteur = $_POST['email'];
        $telephone = $_POST['telephone'];
        $sujet = $_POST['sujet'];
        $message = '<html><head><title>Titre</title></head><body>'.$_POST['message'].'</body></html>';

        /* Expression régulière permettant de vérifier si le
        * format d'une adresse e-mail est correct */
        $regex_mail = '/^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$/i';

        /* Expression régulière permettant de vérifier qu'aucun
        * en-tête n'est inséré dans nos champs */
        $regex_head = '/[\n\r]/';

        if(empty($nom) || empty($prenom) || empty($expediteur) || empty($message))
        {
            echo '<div class="erreur">Tous les champs doivent être renseignés</div>';
        }
        /* On vérifie que le format de l'e-mail est correct */
        elseif (!preg_match($regex_mail, $expediteur))
        {
            echo '<div class="erreur">L\'adresse '.$expediteur.' n\'est pas valide</div>';
        }
        /* On vérifie qu'il n'y a aucun header dans les champs */
        elseif (preg_match($regex_head, $expediteur) || preg_match($regex_head, $nom) || preg_match($regex_head, $sujet))
        {
            echo  '<div class="erreur">En-têtes interdites dans les champs du formulaire</div>';
        }
        else
        {
            /* Destinataire (votre adresse e-mail) */
            $to = "liyanage.nicholas@gmail.com";

            /* Construction du message */
            $mail  = 'Bonjour,'."\r\n\r\n";
            $mail .= 'Ce mail a été envoyé depuis nicholasliyanage.com a l\'attention de la direction' ."\r\n\r\n";
            $mail .= 'Voici le message qui est adressé :'."\r\n";
            $mail .= '***************************'."\r\n";
            $mail .= $message."\r\n";
            $mail .= '***************************'."\r\n";

            /* En-têtes de l'e-mail */
            $headers ='From: '.$nom.'<'.$expediteur.'>'."\n";
            $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";

            /* Envoi de l'e-mail */
            if (mail($to, $sujet, $mail, $headers))
            {
                echo '<div class="validation">E-mail envoyé avec succès</div>';
            }
            else
            {
                echo '<div class="erreur">Erreur d\'envoi de l\'e-mail</div>';
            }

        }
    }
    //debug($_POST);
    ?>
    <div id="contact">

      <h1 class="margegauche">Contactez-nous</h1>

      <p class="margegauche">Pour nous contacter, merci de compléter le formulaire ci joint. Nous vous répondrons dans les meilleurs délais.</p>

      <form action="" method="post">

        <p> Vous êtes : <br /><br>
          <input type="radio" value="client" name="question1"  checked="checked " /><label
                id="client">Client</label><br>
          <input type="radio" value="professionel" name="question1"/><label id="professionel"> Professionel</label><br>
          <input type="radio" value="demandeurEmploie" name="question1" /><label id="DE"> Demandeur d'emploi
            </label><br><br>
            <?php
            if(utilisateurEstConnecteEtAdmin())
            {
                echo '<input type="radio" value="administrateur" name="question1" /><label id="DE"> Administrateur </label>';
            }
            ?>
        </p>

        <p> <label for="nom">votre nom *:</label><input type="text" id="nom" name="nom" /></p>
        <p> <label for="prenom">votre prénom *:</label><input type="text" id="prenom" name="prenom"/></p>
        <p> <label for="email">votre email *:</label><input type="email" id="email" name="email"/></p>
        <p> <label for="telephone">votre téléphone :</label><input type="text" id="telephone" name="telephone"/></p>
        <p> <label for="sujet">le sujet du message *:</label><input type="text" id="sujet" name="sujet"></p>
        <p> <label for="message">votre message *:</label>
        <textarea  id="message" class="commentaire" cols="40" rows="5" name="message"></textarea>
        </p>


        <p><input type="submit" id="Envoyer" name="envoyer" value="Envoyer" /></p>
      </form>

      <p style="clear: left"></p>


    </div>


</div>


  
</body>
</html>