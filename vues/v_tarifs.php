<?php
/**
 * Vue Infos Tarifs
 *
 * Retourne les tarifs applicables
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
require './vues/parts/v_titrePage.php';
?>
<section id="tarifs" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-12">
                <h2>Elements forfaitisés</h2>
                <ul>
                    <?php
                    foreach ($lesTarifsForfait as $tarif) {
                        $id      = $tarif['id'];
                        $montant = $tarif['montant'];
                        $libelle = $tarif['libelle'];
                        if ($id !== 'KM') { ?>
                            <li><?php echo $libelle; ?> : <?php echo $montant; ?> €</li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <h2>Indémnisation kilométrique</h2>
                <ul>
                    <?php
                    foreach ($lesTarifsKm as $tarif) {
                        $id      = $tarif['id'];
                        $montant = $tarif['montant'];
                        $libelle = $tarif['libelle'];
                        ?>
                        <li><?php echo $libelle; ?> : <?php echo $montant; ?> €</li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="bg-black py-5">
    <div class="container text-center">
        <?php require './vues/boutons/v_boutonRetour.php';?>
    </div>
</section>
