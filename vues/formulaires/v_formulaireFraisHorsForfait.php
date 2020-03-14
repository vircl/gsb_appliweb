<?php
/**
 * Vue Formulaire Frais Hors forfait
 * Affiche le formulaire de modification des éléments hors forfait
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


<!-- Elements hors forfait -->
<section id="fraisHorsForfait" class="bg-dark py-5">
    <div class="container">
        <h2 class="text-white"> Elements hors forfait </h2>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date    = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant'];
            $id      = $unFraisHorsForfait['id'];
            ?>
            <form name="frmFraisHorsForfait-<?php echo $id; ?>"
              method="post"
              action="index.php?uc=gererFrais&action=majFraisHorsForfait&idVisiteur=<?php echo $idVisiteur;?>&mois=<?php echo $mois; ?>">

                <div class="row">
                    <!-- Date -->
                    <div class="form-group col-md-2 col-12">
                        <label for="dateFrais">Date</label>
                        <input type="text"
                               name="dateFrais"
                               id="dateFrais-<?php echo $id;?>"
                               value="<?php echo $date; ?>"
                               class="form-control">
                    </div>
                    <!-- Libelle -->
                    <div class="form-group col-md-6 col-12">
                        <label for="libelle">Libellé</label>
                        <input type="text"
                               name="libelle"
                               id="libelle-<?php echo $id;?>"
                               value="<?php echo $libelle; ?>"
                               class="form-control">
                    </div>
                    <!-- Montant -->
                    <div class="form-group col-md-2 col-12">
                        <label for="montant">Montant</label>
                        <input type="text"
                               name="montant"
                               id="montant-<?php echo $id;?>"
                               value="<?php echo $montant; ?>"
                               class="form-control">
                    </div>
                    <!-- Actions -->
                    <div class="form-group col-md-2 col-12 mb-5 mb-md-0">
                        <label>Actions</label>
                        <div class="bg-secondary
                        d-flex justify-content-center text-white">
                            <!-- Modification du frais -->
                            <button type="submit"
                                    class="btn btn-hf"
                                    title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if (estVisiteur()) { ?>
                                <!-- Suppression du frais -->
                                <a href="index.php?uc=gererFrais&action=supprimerFrais&idVisiteur=<?php echo $idVisiteur; ?>&mois=<?php echo $mois; ?>&id=<?php echo $id; ?>"
                                   class="btn btn-hf"
                                   title="Supprimer le frais"
                                   onclick="return confirm(
                               'Voulez-vous vraiment supprimer ce frais?'
                               );">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php
                            }?>
                            <?php if (estcomptable()) {
                                if (!estFraisRefuse($libelle)) { ?>
                                    <!-- Report -->
                                    <a href="index.php?uc=gererFrais&action=reporterFraisHorsForfait&idVisiteur=<?php echo $idVisiteur; ?>&mois=<?php echo $mois; ?>&id=<?php echo $id; ?>"
                                       class="btn btn-hf"
                                       title="Reporter le frais"
                                       onclick="return confirm(
                                       'Voulez-vous vraiment reporter ce frais?'
                                       );">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>

                                    <!-- Refuser frais -->
                                    <a href="index.php?uc=gererFrais&action=refuserFraisHorsForfait&idVisiteur=<?php echo $idVisiteur; ?>&mois=<?php echo $mois; ?>&id=<?php echo $id; ?>"
                                       class="btn btn-hf"
                                       title="Refuser le frais"
                                       onclick="return confirm(
                                           'Voulez-vous vraiment refuser ce frais?'
                                           );">
                                                <i class="fas fa-minus-circle"></i>
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <!-- Accepter frais -->
                                    <a href="index.php?uc=gererFrais&action=accepterFraisHorsForfait&idVisiteur=<?php echo $idVisiteur; ?>&mois=<?php echo $mois; ?>&id=<?php echo $id; ?>"
                                       class="btn btn-hf"
                                       title="Rétablir le frais"
                                       onclick="return confirm(
                                           'Voulez-vous vraiment rétablir ce frais?'
                                           );">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                    <?php
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
        if ($etat === 'CR') { ?>
            <form name="frmFraisHorsForfait-ajout"
                  method="post"
                  action="index.php?uc=gererFrais&action=validerCreationFrais&idVisiteur=<?php echo $idVisiteur; ?>&mois=<?php echo $mois; ?>">
                <div class="row">
                    <!-- Date -->
                    <div class="form-group col-md-2 col-12">
                        <label for="dateFrais">Date</label>
                        <input type="text"
                               name="dateFrais"
                               id="dateFrais-ajout"
                               value="<?php echo date('d/m/Y'); ?>"
                               class="form-control">
                    </div>
                    <!-- Libelle -->
                    <div class="form-group col-md-6 col-12">
                        <label for="libelle">Libellé</label>
                        <input type="text"
                               name="libelle"
                               id="libelle-ajout"
                               placeholder="Libellé"
                               class="form-control">
                    </div>
                    <!-- Montant -->
                    <div class="form-group col-md-2 col-12">
                        <label for="montant">Montant</label>
                        <input type="text"
                               name="montant"
                               id="montant-ajout"
                               placeholder="montant"
                               class="form-control">
                    </div>
                    <!-- Actions -->
                    <div class="form-group col-md-2 col-12">
                        <label>Actions</label>
                        <div class="bg-secondary
                        d-flex justify-content-center text-white">
                            <button type="submit"
                                    class="btn btn-hf"
                                    title="Ajouter un frais">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
        ?>
    </div>
</section>




