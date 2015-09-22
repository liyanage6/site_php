<nav>
<?php
	//preparation deconnexion :
	if(utilisateurEstConnecte() && isset($_GET['action']) && $_GET['action'] === 'deconnexion')
	//si internaute demande une déconnexion
	{

        header('location: connexion.php');
        session_destroy();
	}
	if(utilisateurEstConnecteEtAdmin())
    {
        echo '<ul>';
            echo '<li><a href="'. RACINE_SITE . 'admin/gestion_membres.php">Gestion des membres</a></li>';
            echo '<li><a href="'. RACINE_SITE . 'admin/gestion_commandes.php">Gestion des commandes</a></li>';
            echo '<li><a href="'. RACINE_SITE . 'admin/gestion_boutique.php">Gestion de la boutique</a></li>';
    }
    if(utilisateurEstConnecte())
    {
            echo '<li><a href="'. RACINE_SITE . 'profil.php">Voir votre profil</a></li>';
            echo '<li><a href="'. RACINE_SITE . 'boutique.php">Accès à la boutique</a></li>';
            echo '<li><a href="'. RACINE_SITE . 'panier.php">Voir votre panier</a></li>';
            echo '<li><a href="?action=deconnexion" onclick="alert(\'Vous venez de vous déconnecter !\')">Se déconnecter</a></li>';
    }
    else  //menu pour le simple visiteur
    {
            echo '<li><a href="'. RACINE_SITE . 'boutique.php">Accès à la boutique</a></li>';
            echo '<li><a href="'. RACINE_SITE . 'inscription.php">Inscription</a></li>';
            echo '<li><a href="'. RACINE_SITE . 'connexion.php">Connexion</a></li>';
            echo '<li><a href="'. RACINE_SITE . 'panier.php">Voir votre panier</a></li>';
        echo '</ul>';
    }
?>

</nav>
<section>

