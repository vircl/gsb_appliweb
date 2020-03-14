<?php
/**
 * Vue Formulaire Frais Nombre de justificatifs
 * Affiche le formulaire d'édition du nombre de justificatifs
 * reçus
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
<!-- Justificatifs -->

<form name="frmJustificatif"
      method="post"
      action="index.php?uc=gererFrais&action=majNbJustificatif&idVisiteur=<?php echo $idVisiteur; ?>&mois=<?php echo $mois;?>">
    <div class="row">
        <div class="col-xl-2 col-lg-6 col-md-12">
            <h2 class="text-white mr-5">Justificatifs</h2>
        </div>
        <div class="col-xl-8 col-lg-6 col-md-12">
            <div class="form-group form-inline">

                <input type="number"
                       name="nbJustificatifs"
                       id="nbJustificatifs"
                       class="form-control"
                       min="0" value="<?php echo $nbJustificatifs; ?>">
                <button type="submit" class="btn btn-hf" title="Mettre à jour">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>
    </div>
</form>
