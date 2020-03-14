<?php
/**
 * Vue Bouton Fiche Visiteur
 * Liste des boutons affichés en bas de la fiche visiteur
 * En fonction de son état
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
    case 'CL' :
    case 'VA' :
        include './vues/boutons/v_boutonRetour.php';
        break;
    case 'RB' :
        include './vues/boutons/v_boutonPDF.php';
        include './vues/boutons/v_boutonRetour.php';
        break;
    default:
        include './vues/boutons/v_boutonRetour.php';
        break;
    }
    ?>
</div>
