<?php
/**
 * Vue Entête
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
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title><?php echo TITRE_APPLI;?></title>
        <meta name="description"
        content="Application de gestion des frais de visite">
        <meta name="author" content="Virginie CLAUDE">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Google fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap"
              rel="stylesheet">

        <!-- Fontawesome -->
        <script src="https://kit.fontawesome.com/b6110c3f38.js"
                crossorigin="anonymous"></script>

        <!-- datatables css -->
        <link rel="stylesheet"
              type="text/css"
              href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.20/datatables.min.css"/>

        <!-- Chargement de la feuille de style en fonction du profil -->
        <?php if ($estComptable) {
            ?>
            <!-- Comptable -->
            <link rel="stylesheet" href="<?php echo CSS;?>comptable.css">
            <?php
        } elseif ($estVisiteur) {
            ?>
            <!-- Visiteur médical -->
            <link rel="stylesheet" href="<?php echo CSS;?>visiteur.css">
            <?php
        } else {
            ?>
            <!-- Utilisateur non identifié -->
            <link rel="stylesheet" href="<?php echo CSS;?>guest.css">
            <?php
        }
        ?>
        <!-- jquery-->
        <script type="text/javascript"
                src ="<?php echo VENDOR;?>components/jquery/jquery.slim.js"></script>
        <!-- bootstrap 4 -->
        <script type="text/javascript"
                src="<?php echo VENDOR;?>twbs/bootstrap/dist/js/bootstrap.js"></script>
        <!-- datatables scripts -->
        <script type="text/javascript"
                src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.20/datatables.min.js">
        </script>
        <!-- gsb -->
        <script type="text/javascript"
                src="<?php echo JS;?>scripts.js">
        </script>
    </head>
    <body>