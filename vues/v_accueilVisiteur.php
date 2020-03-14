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
            <!-- Saisie -->
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <a class="bouton-accueil"
                   href="index.php?uc=gererFrais&action=saisirFrais&idVisiteur=<?php echo $userID;?>">
                    <i class="fas fa-edit"></i>
                </a>
                <h3 class="text-center w-100">
                    <a href="index.php?uc=gererFrais&action=saisirFrais&idVisiteur=<?php echo $userID;?>">
                        Saisie fiche frais
                    </a>
                </h3>
            </div>
            <!-- Liste -->
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <a class="bouton-accueil"
                   href="index.php?uc=listeFrais&idVisiteur=<?php echo $userID; ?>">
                    <i class="fas fa-list"></i>
                </a>
                <h3 class="text-center w-100">
                    <a href="index.php?uc=listeFrais&idVisiteur=<?php echo $userID; ?>">
                        Voir mes fiches de frais
                    </a>
                </h3>
            </div>
            <!-- Taux -->
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
            <!-- Appli android -->
            <div class="col-lg-3 col-md-6 col-12">
                <a class="bouton-accueil" href="index.php?uc=android">
                    <i class="fab fa-android"></i>
                </a>
                <h3 class="text-center w-100">
                    <a class="disabled">
                        Application android
                    </a>
                </h3>
            </div>
        </div>
    </div>
</section>

