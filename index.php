<?php

require_once('inc/init.inc.php');
//j'inclus les parties de mon site :
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');


echo "<div class='droite'>";
$donnees = executeRequete("select * from article order by RAND() LIMIT 4");
while($article = $donnees->fetch_assoc()) //je récupère les informations
{
    echo '<div class="article">';
    echo "<h4>$article[titre]</h4>";
    echo "<img src='$article[photo]' width='140' height='140'><br><br>";
    echo "<a href='fiche_article.php?id_article=$article[id_article]'>Voir détail</a>";
    echo '</div>';
}
echo '</div>';


echo '</div>';
require_once('inc/footer.inc.php');
?>

