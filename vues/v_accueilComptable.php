<?php
/**
 * Vue Accueil
 *
 * Template d'affichage de la page d'accueil
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

require './vues/parts/v_titrePage.php';
?>

<!-- Accueil -->
<section id="accueil-container" class="py-5">
    <div class="container">
        <div class="row justify-content-around">
            <!-- Clôture -->
            <div class="col-lg-3 col-md-6 col-12">
                <a class="bouton-accueil"
                   href="index.php?uc=listeFrais&action=fichesACloturer">
                    <i class="fas fa-lock"></i>
                </a>
                <h3 class="text-center w-100">
                    <a class="disabled">
                        Clôture des fiches
                    </a>
                </h3>
            </div>
            <!-- Valider -->
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <a class="bouton-accueil"
                   href="index.php?uc=listeFrais&action=fichesAValider">
                    <i class="fas fa-check"></i>
                </a>
                <h3 class="text-center w-100">
                    <a href="index.php?uc=listeFrais&action=fichesAValider">
                        Valider fiches
                    </a>
                </h3>
            </div>
            <!-- Rembourser -->
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <a class="bouton-accueil"
                   href="index.php?uc=listeFrais&action=fichesARembourser">
                    <i class="fas fa-search-dollar mr-1"></i>
                </a>
                <h3 class="text-center w-100">
                    <a href="index.php?uc=listeFrais&action=fichesARembourser">
                        Suivi remboursement
                    </a>
                </h3>
            </div>
            <!-- Tarifs -->
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <a class="bouton-accueil" href="index.php?uc=infosTarifs">
                    <i class="fas fa-euro-sign"></i>
                </a>
                <h3 class="text-center w-100">
                    <a class="disabled">
                        Infos tarifs
                    </a>
                </h3>
            </div>
        </div>
    </div>
</section>
