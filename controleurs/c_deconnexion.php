<?php
/**
 * Gestion de la déconnexion
 *
 * Si l'utilisateur n'est pas connecté, affiche le formulaire
 * d'authentification et un message d'erreur
 * Si l'utilisateur est connecté, affiche la vue deconnexion
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Controleurs
 * @author     Réseau CERTA <contact@reseaucerta.org>
 * @author     José GIL <jgil@ac-nice.fr>
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2017 Réseau CERTA
 * @license    Réseau CERTA | Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeDeconnexion':
    include 'vues/v_deconnexion.php';
    break;
case 'valideDeconnexion':
    if (estConnecte()) {
        include 'vues/v_deconnexion.php';
    } else {
        ajouterErreur("Vous n'êtes pas connecté");
        include 'vues/v_connexion.php';
    }
    break;
default:
    include 'vues/v_connexion.php';
    break;
}

