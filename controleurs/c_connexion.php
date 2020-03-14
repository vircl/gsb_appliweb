<?php
/**
 * Gestion de la connexion
 *
 * Si l'utlisateur est connecté, affiche la page d'accueil
 * en fonction de son profil.
 * Si l'utilisateur n'est pas connecté, affiche
 * le formulaire d'authentification
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
    $uc = 'demandeConnexion';
}

switch ($action) {
case 'valideConnexion':
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);

    $utilisateur = $pdo->getInfosUtilisateur($login, $mdp);
    if (!is_array($utilisateur)) {
        ajouterErreur('Login ou mot de passe incorrect');
        include 'vues/v_connexion.php';
    } else {
        $id       = $utilisateur['id'];
        $nom      = $utilisateur['nom'];
        $prenom   = $utilisateur['prenom'];
        $idProfil = $utilisateur['idProfil'];
        connecter($id, $nom, $prenom, $idProfil);
        header('Location: index.php');
    }
    break;
case 'demandeConnexion':
default:
    include 'vues/v_connexion.php';
    break;
}

