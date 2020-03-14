<?php
/**
 * Génération d'un jeu d'essai
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright 2017 Réseau CERTA
 * @copyright 2020 Virginie CLAUDE
 * @license   Réseau CERTA | Virginie CLAUDE
 * @version   GIT: <1>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$moisDebut = '201910';
require 'fonctions.php';


/**
 * Génération de la base test
 *
 * @param String  $requete   SQL à exécuter
 * @param boolean $affichage True si le rapport doit être affiché
 *
 * @return void
 */
function genererDatas($requete, $affichage = true)
{
    $pdo = new PDO('mysql:host=localhost:3308;dbname=gsb_tests', 'root', '');
    $pdo->query('SET CHARACTER SET utf8');

    $requetePrepare = $pdo->prepare($requete);
    $requetePrepare->execute();
    $requetePrepare->closeCursor();


    set_time_limit(0);
    creationFichesFrais($pdo);
    creationFraisForfait($pdo);
    creationFraisHorsForfait($pdo);
    majFicheFrais($pdo);
    if ($affichage) {
        echo '<br>' . getNbTable($pdo, 'fichefrais') . ' fiches de frais créées !';
        echo '<br>' . getNbTable($pdo, 'lignefraisforfait')
        . ' lignes de frais au forfait créées !';
        echo '<br>' . getNbTable($pdo, 'lignefraishorsforfait')
        . ' lignes de frais hors forfait créées !';
    }

}


