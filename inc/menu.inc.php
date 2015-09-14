<nav>

<?php
	if(utilisateurEstConnecteEtAdmin())
    {
        echo '<a href="'. RACINE_SITE . 'admin/gestion_membre.php">Gestion des membres</a>';
        echo '<a href="'. RACINE_SITE . 'admin/gestion_commande.php">Gestion des commandes</a>';
        echo '<a href="'. RACINE_SITE . 'admin/gestion_boutique.php">Gestion de la boutique</a>';
    }
    if(utilisateurEstConnecte())
    {
        echo '<a href="'. RACINE_SITE . 'profil.php">Voir votre profil</a>';
        echo '<a href="'. RACINE_SITE . 'boutique.php">Accès à la boutique</a>';
        echo '<a href="'. RACINE_SITE . 'panier.php">Voir votre panier</a>';
        echo '<a href="?action=deconnexion">Se déconnecter</a>';
    }
    else  //menu pour le simple visiteur
    {
        echo '<a href="'. RACINE_SITE . 'inscription.php">Inscription</a>';
        echo '<a href="'. RACINE_SITE . 'connexion.php">Connexion</a>';
        echo '<a href="'. RACINE_SITE . 'boutique.php">Accès à la boutique</a>';
        echo '<a href="'. RACINE_SITE . 'panier.php">Voir votre panier</a>';
    }
	//preparation deconnexion : 
	if(utilisateurEstConnecte() && isset($_GET['action']) && $_GET['action'] == 'deconnexion') 
	//si internaute demande une déconnexion
	{
		session_destroy();
	}
?>	

</nav>
<section>
	


