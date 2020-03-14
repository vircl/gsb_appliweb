<?php
/**
 * Contrôleur de clôture des fiches de frais
 *
 * Ce contrôleur permet la clôture automatique
 * des fiches du ou des mois précédent le mois en cours
 * et étant encore au statut CR
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
if ($estComptable) {

    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    $pdo->cloturerFichesFrais();
    ajouterMessage('Clôture effectuée');
    include 'vues/v_clotureFiches.php';
    // Lecture des infos de la base de données
} else {
    if (estConnecte()) {
        include 'vues/v_accueilVisiteur.php';
    } else {
        include 'vues/v_connexion.php';
    }
}