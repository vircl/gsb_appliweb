<?php
/**
 * Vue Menu Visiteur
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Vues
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT: <1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>

<!-- Menu -->
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
        <!-- Accueil -->
        <li class="nav-item
        <?php echo (!$uc || $uc === 'accueil' ? 'active' : '');?>">
            <a class="nav-link" href="index.php">
                <i class="fas fa-home mr-2"></i>
                Accueil
            </a>
        </li>

        <!-- Saisir -->
        <li class="nav-item
        <?php echo ($uc === 'gererFrais'
        && $action === 'saisirFrais' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=gererFrais&action=saisirFrais&idVisiteur=<?php echo $userID;?>">
                <i class="fas fa-edit mr-2"></i>
                Saisir fiche
            </a>
        </li>

        <!-- Rembourser -->
        <li class="nav-item
        <?php echo ($uc === 'listeFrais' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=listeFrais&idVisiteur=<?php echo $userID;?>">
                <i class="fas fa-list mr-2"></i>
                Mes fiches
            </a>
        </li>

        <li class="nav-item
        <?php echo ($uc === 'infosTarifs' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=infosTarifs&idVisiteur=<?php echo $userID; ?>">
                <i class="fas fa-euro-sign mr-1"></i>
                Infos tarifs
            </a>
        </li>

        <!-- Android appli -->
        <li class="nav-item
         <?php echo ($uc === 'android' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=android&idVisiteur=<?php echo $userID; ?>">
                <i class="fab fa-android mr-2"></i>
                Android
            </a>
        </li>

        <!-- Déconnexion -->
        <li class="nav-item
        <?php echo ($uc === 'deconnexion' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=deconnexion&action=demandeDeconnexion">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Déconnexion
            </a>
        </li>
    </ul>
</div>







