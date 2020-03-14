<?php
/**
 * Vue Erreurs
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
<?php if (nbErreurs()) { ?>
    <!-- Messages d'erreur -->
    <section class="py-5" id="erreurs">
        <div class="container">
            <div class="alert alert-danger" role="alert">
                <?php
                foreach ($_REQUEST['erreurs'] as $erreur) {
                    echo '<p>' . htmlspecialchars($erreur) . '</p>';
                }
                ?>
            </div>
        </div>
    </section>
    <?php
}
