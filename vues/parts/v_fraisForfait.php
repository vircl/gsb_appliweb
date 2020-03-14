<?php
/**
 * Vue Frais forfait
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

<section id="fraisForfait" class="py-5">
    <div class="container">
        <h2>Elements forfaitisés</h2>
        <table class="table table-responsive-lg mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Frais forfaitaires</th>
                    <th>Quantité</th>
                    <th>Montant Unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($lesFraisForfait as $unFrais) {
                $libelle = $unFrais['libelle'];
                $montant = $unFrais['montant'];
                $total   = $unFrais['total'];
                if ($unFrais['idfrais'] == 'KM') {
                    $montant = $tauxKM;
                    $total = $unFrais['quantite'] * $montant;
                    $vehicule = $infosVisiteur['typeVehicule'];
                    $libelle .= ($vehicule ? '(' . $vehicule . ')'
                        : ' (Véhicule non renseigné)');
                }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($libelle); ?></td>
                    <td><?php echo $unFrais['quantite']; ?></td>
                    <td><?php echo $montant ?> €</td>
                    <td><?php echo $total; ?> €</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</section>

