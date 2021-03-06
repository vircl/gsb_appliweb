<?php
/**
 * Vue Fiche de frais
 * Affichage Comptable
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
<!-- Fiche de frais -->

    <?php
    if ($infosFiche) {
        if ($etat === 'CL') {
            // Fiche modifiable si état clôturé
            include 'formulaires/v_formulaireFraisForfait.php';
            include 'formulaires/v_formulaireFraisHorsForfait.php';
            ?>
            <div class="bg-dark pb-5">
                <div class="container info-fiche">
                    <?php
                        include 'formulaires/v_formulaireNbJustificatifs.php';
                        include 'parts/v_montantValide.php';
                    ?>
                </div>
            </div>
            <?php
        } else {
            // Fiche non modifiable
            include 'parts/v_fraisForfait.php';
            include 'parts/v_fraisHorsForfait.php';
            ?>
            <div class=" bg-dark py-5">
                <div class="container info-fiche">
                <?php
                    include 'parts/v_nbJustificatifs.php';
                    include 'parts/v_montantValide.php';
                ?>
                </div>
            </div>
            <?php
        }
        include './vues/parts/v_boutonsComptable.php';
    } else {
        if ($idVisiteur != '' && $mois != '') {
            ajouterErreur('Aucune fiche de frais pour ce visiteur ce mois');
        }
    }?>




