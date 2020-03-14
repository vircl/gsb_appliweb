<?php
/**
 * Index du projet GSB
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
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$uc            = '';
$action        = '';
$titrePage     = '';
$sousTitrePage = '';
$infosNbFiches = '';
$idVisiteur    = '';
$etat          = '';

require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
require_once 'vendor/autoload.php';


session_start();
$pdo          = PdoGsb::getPdoGsb();
$estConnecte  = estConnecte();
$estComptable = estComptable();
$estVisiteur  = estVisiteur();

$userID =($estConnecte ? $_SESSION['idUtilisateur'] : '');

$uc     = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);

if ($uc && !$estConnecte) {
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil';
}

require 'vues/parts/v_entete.php';

switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil':
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php';
    break;
case 'listeFrais':
    include 'controleurs/c_listeFrais.php';
    break;
case 'genererPDF':
    include 'controleurs/c_genererPDF.php';
    break;
case 'infosTarifs':
    include 'controleurs/c_infosTarifs.php';
    break;
case 'android':
    include 'controleurs/c_android.php';
    break;
case 'cloturerFiches':
    include 'controleurs/c_cloturerFiches.php';
    break;
case 'interdit':
    include 'controleurs/c_interdit.php';
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
default:
    echo '404';
    break;
}

require 'vues/parts/v_pied.php';
