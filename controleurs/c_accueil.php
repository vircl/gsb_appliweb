<?php
/**
 * Gestion de l'accueil
 *
 * Sous-contrôleur accueil de l'application GSB
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

if ($estConnecte) {
    $titrePage = TITRE_APPLI;
    $sousTitrePage = getNomUtilisateur();
    if (estComptable()) {
        include 'vues/v_accueilComptable.php';
    } else {
        if (estVisiteur()) {
            include 'vues/v_accueilVisiteur.php';
        } else {
            include 'vues/v_404.php';
        }
    }
} else {
    include 'vues/v_connexion.php';
}




