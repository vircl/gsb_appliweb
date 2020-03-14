<?php
/**
 * Vue Déconnexion
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
deconnecter();
$titrePage = TITRE_APPLI;
require './vues/parts/v_titrePage.php';
?>
<section id="deconnexion" class="py-5">
    <div class="container alert alert-info" role="alert">
        <p>Vous avez bien été déconnecté ! <a href="index.php">Cliquez ici</a>
            pour revenir à la page de connexion.</p>
    </div>
</section>

<?php
header("Refresh: 3;URL=index.php");
