<?php
require_once('../inc/init.inc.php');

//ici c'est le BACKOFFICE : donc restreindre l'accès à cette partie. Uniquement visible pour l'administrateur :
if(!utilisateurEstConnecteEtAdmin())
{
    header("location:../connexion.php"); //redirection pour tous les membres qui ne sont pas administrateurs (donc tous les autres connectés dont la statut est égal à 0 mais aussi les simples visiteurs !!). Seuls les membres ayant un statut == 1 ont accès à cette page. Cf. BDD
    exit(); //permet de stopper l'éxécution du script
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
echo "<th>Modif.</th>";
echo "<th>Supr.</th>";
echo "</tr>";

while ($ligne = $resultat->fetch_assoc())
{
    //crée-moi autant de lignes <tr> qu'il y a de résultats dans la BDD (utilisation de fecth_assoc() qui nous ressort les informations d'array(). Donc récupération par l'intermédiaire d'une boucle foreach()
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
    echo '<td><a href="?action=modification&id_article=' . $ligne['id_membre'] .'">---</a></td>';
    echo '<td><a href="?action=suppression&id_article=' . $ligne['id_membre'] .'" OnClick="return(confirm(\'En êtes vous certain ?\'));">x</a></td>';
    echo '</tr>';
}
echo '</table>';
echo "</div>";


?>