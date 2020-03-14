<?php
/**
 * Vue Menu Comptable
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
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
?>

<!-- Menu -->
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">

        <!-- Accueil -->
        <li class="nav-item
        <?php echo (!$uc || $uc === 'accueil' ? 'active' : '');?>">
            <a class="nav-link" href="index.php">
                <i class="fas fa-home mr-1"></i>
                Accueil
            </a>
        </li>

        <!-- Clôture -->
        <li class="nav-item">
            <a class="nav-link"
               href="index.php?uc=listeFrais&action=fichesACloturer">
                <i class="fas fa-lock mr-1"></i>
                Clôturer les fiches
            </a>
        </li>

        <!-- Valider -->
        <li class="nav-item
        <?php echo ($uc === 'listeFrais'
        && $action === 'fichesAValider' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=listeFrais&action=fichesAValider">
                <i class="fas fa-check mr-1"></i>
                Valider les fiches de frais
            </a>
        </li>

        <!-- Rembourser -->
        <li class="nav-item
        <?php echo ($uc === 'listeFrais'
        && $action === 'fichesARembourser' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=listeFrais&action=fichesARembourser">
                <i class="fas fa-search-dollar mr-1"></i>
                Suivire le paiement des fiches de frais
            </a>
        </li>

        <!-- Taux -->
        <li class="nav-item
        <?php echo ($uc === 'infosTarifs' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=infosTarifs">
                <i class="fas fa-euro-sign mr-1"></i>
                Infos tarifs
            </a>
        </li>

        <!-- Déconnexion -->
        <li class="nav-item
        <?php echo ($uc === 'deconnexion' ? 'active' : '');?>">
            <a class="nav-link"
               href="index.php?uc=deconnexion&action=demandeDeconnexion">
                <i class="fas fa-sign-out-alt mr-1"></i>
                Déconnexion
            </a>
        </li>
    </ul>
</div>

