<?php
/**
 * Vue Formulaire Frais forfait
 * Affiche le formulaire de modification des éléments forfaitisés
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
<!-- Elements forfaitisés -->
<section id="fraisForfait" class="py-5">
    <div class="container">
        <h2>Elements forfaitisés</h2>
        <form name="fraisForfait"
              action="index.php?uc=gererFrais&action=validerMajFraisForfait&idVisiteur=<?php echo $idVisiteur;?>&mois=<?php echo $mois;?>"
              method="post">
            <div class="row justify-content-between">
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais  = $unFrais['idfrais'];
                    $libelle  = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite'];
                    $step     = 1;
                    if ($idFrais === 'KM') {
                        $step = 10;
                    }
                    ?>
                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="number" id="frais-<?php echo $idFrais; ?>"
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5"
                               step="<?php echo $step;?>"
                               value="<?php echo $quantite ?>"
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="form-group text-center pt-4">
                <button class="btn btn-primary" type="submit">Mettre à jour</button>
            </div>
        </form
    </div>
</section>

