<?php
require_once('../inc/init.inc.php');

//ici c'est le BACKOFFICE : donc restreindre l'accès à cette partie. Uniquement visible pour l'administrateur :
if(!utilisateurEstConnecteEtAdmin())
{
    header("location:connexion.php"); //redirection pour tous les membres qui ne sont pas administrateurs (donc tous les autres connectés dont la statut est égal à 0 mais aussi les simples visiteurs !!). Seuls les membres ayant un statut == 1 ont accès à cette page. Cf. BDD
    exit(); //permet de stopper l'éxécution du script
}

//j'inclus les parties de mon site :
require_once('haut_de_site.inc.php');
require_once('../inc/menu.inc.php');

echo '<div style="background-color: rgba(201, 201, 201,0.5);padding: 10px;margin: 10px; ">
	<h1> Affichage des Commandes </h1>';
$resultat = executeRequete("SELECT * FROM commande");
echo "Nombre de commande(s) : " . $resultat->num_rows;

$nbcol = $resultat->field_count;
echo "<table style='border-color:red' border=10> <tr>";
for ($i=0; $i < $nbcol; $i++)
{
    $colonne = $resultat->fetch_field();
    echo '<th>' . $colonne->name . '</th>';
}
echo "<th>Annulation</th>";
echo "<th>Validation</th>";
echo "</tr>";

while ($ligne = $resultat->fetch_assoc())
{
    //crée-moi autant de lignes <tr> qu'il y a de résultats dans la BDD (utilisation de fecth_assoc() qui nous ressort les informations d'array(). Donc récupération par l'intermédiaire d'une boucle foreach()
    //debug($ligne);
    echo '<tr>';
    foreach ($ligne as $indice => $information)
        //on récupère les indices et à les informations. Exemple : $article['id_article'] = 1
    {
        if($indice == "photo")//s'il trouve un élément ayant un indice 'photo', affichage particulier : emploi de la balise <img>
        {
            echo "<td><img src='" . $information . "' width='70' height='70' /></td>";
        }
        else  //dans le cas contraire (indices != 'photo')
        {
            echo "<td>" . $information . "</td>";
        }
    }
    echo '<td><a href="?action=annulation&id_commande=' . $ligne['id_commande'] .'">No</a></td>';
    echo '<td><a href="?action=validation&id_commande=' . $ligne['id_commande'] .'";">Yes</a></td>';
    echo '</tr>';
}
echo '</table>';
echo "</div>";


if(isset($_GET['action']) && $_GET['action'] == "annulation")
{

    $resultat = informationSurUneCommande($_GET['id_commande']);
    $commande_a_supprimer = $resultat->fetch_assoc();
    //debug($resultat);debug($commande_a_supprimer);

    echo "<div class='validation'>Annulation de la commande : numéro $_GET[id_commande] - d'un montant de
    $commande_a_supprimer[montant] - faite le $commande_a_supprimer[date] par membre d'id $commande_a_supprimer[id_membre]
    </div>";
    executeRequete("UPDATE commande SET etat = 'annule' WHERE id_commande=$commande_a_supprimer[id_commande]");
    header('location: gestion_commandes.php');
}

if(isset($_GET['action']) && $_GET['action'] == "validation")
{
    $resultat = informationSurUneCommande($_GET['id_commande']);
    $commande_a_valide = $resultat->fetch_assoc();
    //debug($resultat);debug($commande_a_valide);


    echo "<div class='validation'>Validation de la commande : numéro $_GET[id_commande] - d'un montant de
    $commande_a_valide[montant] - faite le $commande_a_valide[date] par membre d'id  $commande_a_valide[id_membre]
    </div>";
    executeRequete("UPDATE commande SET etat='valide' WHERE id_commande=$commande_a_valide[id_commande]");
    header('location: gestion_commandes.php');
}

echo'</div>';
require_once('footer.inc.php');
?>