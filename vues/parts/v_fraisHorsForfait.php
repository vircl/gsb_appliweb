<?php
/**
 * Vue Frais forfait
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
<section id="fraisHF" class="pb-5">
    <div class="container">
        <h2>Eléments hors forfait</h2>
        <table class="table table-responsive-lg mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Libelle</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesFraisHorsForfait as $unFraisHorsForfait) { ?>
                <tr>
                    <td><?php echo $unFraisHorsForfait['date'];?></td>
                    <td><?php echo $unFraisHorsForfait['libelle']?></td>
                    <td><?php echo $unFraisHorsForfait['montant'];?> €</td>
                </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
