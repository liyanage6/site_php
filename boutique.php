<?php
require_once('inc/init.inc.php');
//j'inclus les parties de mon site : 
require_once('inc/haut_de_site.inc.php');
require_once('inc/menu.inc.php');

?>

<div class="gauche">
    <a href="?action=chemise">- Chemise</a><br>
    <a href="?action=tshirt">- T-Shirt</a><br>
    <a href="?action=pantalon">- Pantalon</a><br>
</div>
<div classe="droite">
    <?php
        if(utilisateurEstConnecte() && isset($_GET['action']) && $_GET['action'] === 'chemise'){
            $afficheChemise = executeRequete("SELECT * FROM article WHERE categorie = 'Chemise'");
            echo'<table class="droite">
                    <tr>
                        <th hidden>ID</th>
                        <th>Reference</th>
                        <th>Categorie</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Couleur</th>
                        <th>Taille</th>
                        <th>Sexe</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Ajouter au panier</th>
                        <th>Retirer du panier</th>
                    </tr>';
            while($row = mysqli_fetch_assoc($afficheChemise)){
                echo'<tr>
                        <td hidden>'.$row['id_article'].'</td>
                        <td>'.$row['reference'].'</td>
                        <td>'.$row['categorie'].'</td>
                        <td>'.$row['titre'].'</td>
                        <td>'.$row['description'] .'</td>
                        <td>'.$row['couleur'] .'</td>
                        <td>'.$row['taille'] .'</td>
                        <td>'.$row['sexe'] .'</td>
                        <td>'.$row['prix'] .'</td>
                        <td>'.$row['stock'] . '</td>
                        <td><a href="?action=ajoutPanier" name="ajoutPanier">+</a></td>
                        <td><a href="?action=retirePanier" name="retirePanier" onclick=alert(\' Vous avez supprimer cette article\')>--</a></td>
                     </tr>';
            }
            echo'</table>';
        }

    if(utilisateurEstConnecte() && isset($_GET['action']) && $_GET['action'] === 'tshirt'){
        $afficheChemise = executeRequete("SELECT * FROM article WHERE categorie = 'T-shirt'");
        echo'<table class="droite">
                    <tr>
                        <th hidden>ID</th>
                        <th>Reference</th>
                        <th>Categorie</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Couleur</th>
                        <th>Taille</th>
                        <th>Sexe</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Ajouter au panier</th>
                        <th>Retirer du panier</th>
                    </tr>';
        while($row = mysqli_fetch_assoc($afficheChemise)){
            echo'<tr>
                        <td hidden>'.$row['id_article'].'</td>
                        <td>'.$row['reference'].'</td>
                        <td>'.$row['categorie'].'</td>
                        <td>'.$row['titre'].'</td>
                        <td>'.$row['description'] .'</td>
                        <td>'.$row['couleur'] .'</td>
                        <td>'.$row['taille'] .'</td>
                        <td>'.$row['sexe'] .'</td>
                        <td>'.$row['prix'] .'</td>
                        <td>'.$row['stock'] . '</td>
                        <td><a href="?action=ajoutPanier" name="ajoutPanier">+</a></td>
                        <td><a href="?action=retirePanier" name="retirePanier" onclick=alert(\' Vous avez supprimer cette article\')>--</a></td>
                     </tr>';
        }
        echo'</table>';
    }
    ?>

</div>