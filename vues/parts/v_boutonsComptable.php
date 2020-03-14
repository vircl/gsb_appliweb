<?php
/**
 * Vue Bouton Fiche Comptable
 * Liste des boutons affichés aux comptables en bas de la fiche visiteur
 * En fonction de l'état de la fiche
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


$ucRetour = "";
$actionRetour = "";
?>
<div class="text-center bg-black py-5">
    <?php
    switch ($etat) {
    case 'CR' :
        include 'vues/boutons/v_boutonRetour.php';
        break;
    case 'CL' :
        include 'vues/boutons/v_boutonValider.php';
        include 'vues/boutons/v_boutonRetour.php';
        break;
    case 'VA' :
        $ucRetour     = "gererFrais";
        $actionRetour = "suiviPaiement";
        include 'vues/boutons/v_boutonRembourser.php';
        include 'vues/boutons/v_boutonRetour.php';
        break;
    case 'RB' :
        include 'vues/boutons/v_boutonPDF.php';
        include 'vues/boutons/v_boutonRetour.php';
        break;
    default:
        break;
    }
    ?>
</div>
