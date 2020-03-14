<?php
/**
 * Vue Navbar
 *
 * Cette vue défini les éléments communs
 * à la barre de navigation comptable et visiteur
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
<!-- NAVBAR -->
<nav class="navbar navbar-expand-xl navbar-light bg-light navbar-gsb shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <img src="./assets/images/logo.png" width="75"/>
            <?php
            if (estConnecte()) {
                echo getNomUtilisateur();
            } else {
                echo NOM_LABO;
            }
            ?>
        </a>
        <!-- Bouton toggler -->
        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        if (estComptable()) {
            include './vues/parts/v_menuComptable.php';
        }
        if (estVisiteur()) {
            include './vues/parts/v_menuVisiteur.php';
        }
        ?>
    </div>
</nav>