<?php
/**
 * Vue Bouton Valider
 * Bouton validation d'une fiche de frais
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
<a href="index.php?uc=genererPDF&idVisiteur=<?php echo $idVisiteur;?>&mois=<?php echo $mois; ?>"
           class="btn btn-primary">
    <i class="glyphicon glyphicon-download"></i> Générer PDF
</a>
