<?php
/**
 * Vue Messages
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
<?php if (nbMessages()) { ?>
    <!-- Messages d'info -->
    <section id="messages" class="py-5">
        <div class="container">
            <div class="alert alert-success" role="alert">
                <?php
                foreach ($_REQUEST['messages'] as $msg) {
                    echo '<p>' . htmlspecialchars($msg) . '</p>';
                }
                ?>
            </div>
        </div>
    </section>
    <?php
}
