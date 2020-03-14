<?php
/**
 * Gestion de la liste des fiches de frais
 *
 * Sous-contrôleur de génération d'une liste de fiche de frais
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

$idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_STRING);

$etat = false;
$messagePasDeResultat = '';
$nbFiches             = 0;
$boutonAction         = null;
$fiches               = array();

// Vérifie si accès autorisé, récupère message erreur sinon
$auth = verifierAcces($idVisiteur);


if ($auth) {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    switch ($action) {

    // Liste des fiches de frais en attente de clôture
    case 'fichesACloturer':
        $titrePage     = 'Fiches de frais à clôturer';
        $fiches        = $pdo->getLesFichesParEtat('CR', getMois(), '<');
        $nbFiches      = count($fiches);
        $infosNbFiches = count($fiches) . ' fiches à clôturer';
        if (!$nbFiches) {
            $messagePasDeResultat = 'Aucune fiche de frais à clôturer !';
        } else {
            $boutonAction = './vues/boutons/v_boutonCloturer.php';
        }
        break;
    // Liste des fiches de frais clôturées en attente de validation
    case 'fichesAValider':
        $titrePage     = 'Fiches de frais à valider';
        $fiches        = $pdo->getLesFichesParEtat('CL');
        $nbFiches      = count($fiches);
        $infosNbFiches = count($fiches) . ' fiches à valider';
        if (!$nbFiches) {
            $messagePasDeResultat = 'Aucune fiche de frais à valider !';
        }
        break;
    // Liste des fiches de frais validées, en attente de mise en paiement
    case 'fichesARembourser':
        $titrePage     = 'Fiches de frais à rembourser';
        $fiches        = $pdo->getLesFichesParEtat('VA');
        $nbFiches      = count($fiches);
        $infosNbFiches = count($fiches) . ' fiches à rembourser';
        if (!$nbFiches) {
            $messagePasDeResultat = 'Aucune fiche de frais à rembourser !';
        }
        break;
    // Par défaut, affiche les fiches de frais de l'utilisateur connecté
    // Ou une erreur s'il est comptable
    default :
        if ($estVisiteur) {
            $titrePage = 'Fiches de frais';
            $sousTitrePage = getNomUtilisateur();
            $fiches = $pdo->getLesFichesParVisiteur($idVisiteur);
            $infosNbFiches = count($fiches) . ' fiches de frais enregistrées';
        } else {
            $titrePage = TITRE_APPLI;
            ajouterErreur('Accès interdit');
        }
        break;
    }
}

// Affichage de la vue
require 'vues/v_listeFiches.php';

