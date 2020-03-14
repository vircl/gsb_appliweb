<?php
/**
 * Vue Liste Fiches
 * Affiche un tableau avec la liste des fiches de frais
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

require './vues/parts/v_titrePage.php';?>

<section id="listeFiches" class="py-5">
    <div class="container">
    <?php
    if (!nbErreurs()) {
        if (!empty($fiches)) {
            ?>
            <table class="table table-responsive-xl datatable">
                <!-- Entete tableau -->
                <thead class="thead-dark">
                    <tr>
                        <th>Mois</th>
                        <?php if (estComptable()) {
                            ?>
                            <th class="col-2">Visiteur</th>
                            <?php
                        }
                        ?>
                        <th>Etat</th>
                        <th>Montant</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- Corps tableau -->
                <tbody>
                <?php
                foreach ($fiches as $fiche) {
                    $etatFiche = $fiche['etat'];
                    $id = $fiche['idvisiteur'];
                    $mois = $fiche['mois'];
                    ?>
                    <tr>
                        <!-- Mois -->
                        <td>
                            <?php echo $fiche['mois']; ?>
                        </td>
                        <?php
                        if (estComptable()) {
                            ?>
                            <!-- Identité du visiteur -->
                            <td class="col-2">
                                <?php echo $fiche['nom'] . ' ' . $fiche['prenom']; ?>
                            </td>
                            <?php
                        }
                        ?>
                        <!-- Etat de la fiche de frais -->
                        <td>
                            <?php
                            echo $fiche['libetat']
                                . ' depuis le '
                                . dateAnglaisVersFrancais($fiche['date']);
                            ?>
                        </td>
                        <!-- Montant de la fiche de frais -->
                        <td>
                            <?php echo $fiche['montant']; ?> €
                        </td>
                        <!-- Boutons action -->
                        <td>
                            <!-- Voir la fiche -->
                            <a href="index.php?uc=gererFrais&action=voir&idVisiteur=<?php echo $fiche['idvisiteur']; ?>&mois=<?php echo $mois; ?>"
                               class="btn">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php
                            if ($etatFiche === 'RB') {
                                ?>
                                <!-- Télécharger la fiche au format pdf -->
                                <a href="index.php?uc=genererPDF&idVisiteur=<?php echo $fiche['idvisiteur']; ?>&mois=<?php echo $mois; ?>"
                                   class="btn">
                                    <i class="fas fa-file-download"></i>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        } else {?>
            <!-- Aucune fiche de frais trouvée -->
            <h2> <?php echo $messagePasDeResultat; ?></h2>
            <?php
        }
    }

    ?>
    </div>
</section>
<?php
if ($nbFiches && !nbErreurs()) { ?>
    <section class="bg-dark py-5">
        <div class="container">
            <h2 class="text-white"><?php echo $infosNbFiches; ?> </h2>
        </div>
    </section>
    <?php
}
?>

<section class="bg-black py-5">
    <div class="container text-center">
        <?php
        if ($boutonAction) {
            include $boutonAction;
        }
        require './vues/boutons/v_boutonRetour.php';
        ?>
    </div>
</section>



