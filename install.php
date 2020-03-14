<?php
/**
 * Installation
 *
 * Fichier d'installation de l'application GSB Appli-Frais
 * Ce fichier permet de générer la structure de la base de données
 * +/- un jeu de données test
 *
 * La base de données gsb_frais doit avoir été préalablement créée
 *
 * <b>Attention : toutes les données de la base de données gsb_frais sont écrasées
 * à l'exécution de ce script !!!</b>
 *
 * Options possibles :
 *
 * - index.php : génération de la structure de la base de données et
 * des paramètres généraux
 * (montant des éléments forfaitisés, catégories de véhicules ...)
 * - index.php?users : Le script insère également un jeu d'utilisateurs fictifs
 * (les mots de passe de ces utilisateurs fictifs sont stockés dans le fichier
 * tests/gendatas/utilisateurs.json)
 * - index.php?datas : génération d'une série de fiches de frais pour chacun
 * des visiteurs renseignés dans la base de données
 *
 * Les options users & datas sont cumulables (index.php?users&datas)
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
 */

// Scripts de génération de la base de données et d'un jeu de données tests
require_once 'tests/gendatas/majGSBInstall.php';

$estConnecte  = false;
$estComptable = false;
$estVisiteur  = false;


$datas = filter_input(INPUT_GET, 'datas', FILTER_SANITIZE_STRING);
$users = filter_input(INPUT_GET, 'users', FILTER_SANITIZE_STRING);

genererDatabase(
    file_get_contents('tests/gendatas/script.restoreStructure.sql'),
    'Mise à jour des tables'
);
if (isset($users)) {
    genererDatabase(
        file_get_contents('tests/gendatas/script.restoreUtilisateurs.sql'),
        'Ajout des utilisateurs'
    );
}
if (isset($datas)) {
    genererDatas(true);
}






