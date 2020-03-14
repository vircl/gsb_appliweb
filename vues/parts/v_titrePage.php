<?php
/**
 * Vue Titre page
 *
 * Cette vue affiche le bandeau contenant le titre
 * et le sous-titre de la page actuelle
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
 */?>
<!-- Header -->
<header>
    <?php
    if (!isset($_GET['uc'])
        || $_GET['uc'] !== 'genererPDF'
    ) {
        include './vues/parts/v_navbar.php';
    } ?>
    <!-- Bandeau titre page -->
    <section class="bg-light bandeau-titre">
        <div class="container text-center">
            <h1 class="titrePage">
                <?php echo $titrePage !=='' ? $titrePage : TITRE_APPLI ;?>
            </h1>
            <p class="sousTitrePage">
                <?php echo $sousTitrePage; ?>
            </p>
        </div>
    </section>
</header>
 <?php
include './vues/parts/v_erreurs.php';
include './vues/parts/v_messages.php';  ?>
