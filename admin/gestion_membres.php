<?php
require_once('../inc/init.inc.php');

//ici c'est le BACKOFFICE : donc restreindre l'accès à cette partie. Uniquement visible pour l'administrateur :
if(!utilisateurEstConnecteEtAdmin())
{
    header("location:../connexion.php"); //redirection pour tous les membres qui ne sont pas administrateurs (donc tous les autres connectés dont la statut est égal à 0 mais aussi les simples visiteurs !!). Seuls les membres ayant un statut == 1 ont accès à cette page. Cf. BDD
    exit(); //permet de stopper l'éxécution du script
}
/**
 * Suppresion d'un membre
 */
if(isset($_GET['action']) && $_GET['action'] == "suppression")
{

    $resultat = informationSurUnMembre($_GET['id_membre']);
    $membre_a_supprimer = $resultat->fetch_assoc();
    //debug($resultat);
    echo "<div class='validation'>Suppression du membre : $_GET[id_membre] - $membre_a_supprimer[email] -
    $membre_a_supprimer[pseudo]
    </div>";
    executeRequete("DELETE FROM membre WHERE id_membre=$_GET[id_membre]");
    $_GET['action'] = 'affichage';
}

//j'inclus les parties de mon site :
require_once('../inc/haut_de_site.inc.php');
require_once('../inc/menu.inc.php');

echo '<div style="background: #c0c0c0;padding: 10px; ">
	<h1> Affichage des Membres </h1>';
$resultat = executeRequete("SELECT * FROM membre");
echo "Nombre de membre(s) : " . $resultat->num_rows;

$nbcol = $resultat->field_count;
echo "<table style='border-color:red' border=10> <tr>";
for ($i=0; $i < $nbcol; $i++)
{
    $colonne = $resultat->fetch_field();
    echo '<th>' . $colonne->name . '</th>';
}
echo "<th>Mail</th>";
echo "<th>Supr.</th>";
echo "</tr>";

while ($ligne = $resultat->fetch_assoc())
{
    //crée-moi autant de lignes <tr> qu'il y a de résultats dans la BDD (utilisation de fecth_assoc() qui nous ressort les informations d'array(). Donc récupération par l'intermédiaire d'une boucle foreach()
    echo '<tr>';
    foreach ($ligne as $indice => $information)
        //on récupère les indices et à les informations. Exemple : $article['id_article'] = 1
    {
        echo "<td>" . $information . "</td>";
    }
    echo '<td><a href="?action=mail&id_membre=' . $ligne['id_membre'] .'">---</a></td>';
    echo '<td><a href="?action=suppression&id_membre=' . $ligne['id_membre'] .'"
OnClick="return(confirm(\'En êtes vous certain ?\'));">x</a></td>';
    echo '</tr>';
}
echo '</table>';
echo "</div>";

/**
 * Envoie de mail a un membre
 */
if(isset($_GET['action']) && $_GET['action'] == 'mail')
{

    $resultat = informationSurUnMembre($_GET['id_membre']);
    $membre_actuel = $resultat->fetch_assoc();
    //debug($membre_actuel);

    ?>

    <form action='' method='post'>
        <input type="hidden" id="id_article" name="id_article" value="<?php if(isset($membre_actuel['id_membre'])) echo $membre_actuel['id_membre']; ?>" />

        <h3>Mail :</h3>
        <p>
            <label for='civilite'>Civilité :</label>
            <select id='civilite' name='civilite'>
                <option value='mr' selected='selected'>Monsieur</option>
                <option value='mme'>Madame</option>
                <option value='mr/mme'>Madame,Monsieur</option>
            </select>
        </p>
        <p>
            <label for='nom'>Nom/Prénom :</label>
            <input type='text' id='nom' name='nom' value="<?php if(isset($membre_actuel['nom'])) echo $membre_actuel['nom']; ?>" />
        </p>
        <p>
            <label for='email'>E-mail :</label>
            <input type='text' id='email' name='email' value="<?php if(isset($membre_actuel['email'])) echo $membre_actuel['email']; ?>" />
        </p>
        <p>
            <label for='sujet'>Sujet :</label>
            <input type='text' id='sujet' name='sujet' />
        </p>
        <p>
            <label for='message'>Message :</label>
            <textarea id='message' name='message' cols='40' rows='4'></textarea>
        </p>
        <p>
            <input type='submit' name='envoyer' value='Envoyer' />
        </p>
    </form>

<?php
    if(isset($_POST['envoyer']))
    {
        $civilite = trim($_POST['civilite']);
        $nom = trim($_POST['nom']);
        $expediteur = trim($_POST['email']);
        $sujet = trim($_POST['sujet']);
        $message = trim($_POST['message']);

        /* Expression régulière permettant de vérifier si le
        * format d'une adresse e-mail est correct */
        $regex_mail = '/^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$/i';

        /* Expression régulière permettant de vérifier qu'aucun
        * en-tête n'est inséré dans nos champs */
        $regex_head = '/[\n\r]/';

        if(empty($civilite) || empty($nom) || empty($expediteur) || empty($sujet) || empty($message))
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
            $to = $expediteur;

            /* Construction du message */
            $mail  = 'Bonjour,'."\r\n\r\n";
            $mail .= 'Ce mail a été envoyé depuis nicholasliyanage.com a l\'attention de  '.$civilite.' '.$nom
            ."\r\n\r\n";
            $mail .= 'Voici le message qui vous est adressé :'."\r\n";
            $mail .= '***************************'."\r\n";
            $mail .= $message."\r\n";
            $mail .= '***************************'."\r\n";

            /* En-têtes de l'e-mail */
            $headers = 'From: Direction -> nicholasliyanage.com'."\r\n\r\n";

            /* Envoi de l'e-mail */
            if (mail($to, $sujet, $mail))
            {
                echo '<div class="validation">E-mail envoyé avec succès</div>';
            }
            else
            {
                echo '<div class="erreur">Erreur d\'envoi de l\'e-mail</div>';
            }

        }
    }
}

?>