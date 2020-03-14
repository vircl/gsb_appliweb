<?php
/**
 * Vue Bouton Rembourser
 * Bouton remboursement d'une fiche de frais
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
<a href="index.php?uc=gererFrais&action=rembourserFicheFrais&idVisiteur=<?php echo $idVisiteur; ?>&mois=<?php echo $mois; ?>"
   class="btn btn-primary" onclick="return confirm('Rembourser cette fiche de frais ?');">
    Rembourser la fiche
</a>
