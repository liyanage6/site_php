<?php

require_once('inc/init.inc.php');
//j'inclus les parties de mon site :
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');



echo "<div class='droite' style='margin-left: 10px;'>";
$donnees = executeRequete("select * from article order by RAND() LIMIT 12");
while($article = $donnees->fetch_assoc()) //je récupère les informations
{
    echo '<div class="article">';
    echo "<h4>$article[titre] </h4><h4> Taille: $article[taille]</h4>";
    echo "<img src='$article[photo]' width='180' height='180'><br><br>";
    echo "<a href='fiche_article.php?id_article=$article[id_article]'>Voir détail</a>";
    echo '</div>';
}
echo '</div>';

echo '<div class="emplacement">

				<h1 class="titreemplacement"> Emplacemment </h1>

				<div class="imgemplacement">
					<img src="photo/emplacement.jpg" alt="emplacement">
				</div>
				<div class="imgemplacement">
					<img src="photo/emplacement2.jpg" alt="emplacement">
				</div>
		</div>';


echo '</div>';
require_once('inc/footer.inc.php');
?>

