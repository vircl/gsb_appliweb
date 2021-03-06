<?php
/**
 * Récupère les tarifs
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Controleurs
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link       https://tcpdf.org/ Documentation de la librairie TCPDF
 */
if ($estConnecte) {
    $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_STRING);
    // Lecture des infos de la base de données
    $titrePage = 'Information tarifs applicables';
    $lesTarifsForfait = array();
    $lesTarifsKm      = array();

    $lesTarifsForfait = $pdo->getTarifsForfait();
    $lesTarifsKm      = $pdo->getTarifsKm();
    include 'vues/v_tarifs.php';
} else {
    include 'vues/v_connexion.php';
}
