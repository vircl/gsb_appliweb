<?php
/**
 * Index de l'API GSB
 *
 * Intialisation de l'API et déclaration de la classe GestionFraisController.php
 *
 * Prérequis : Framework RestServer installé
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright 2020 Virginie CLAUDE
 * @license   Virginie CLAUDE
 * @version   GIT:<1>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

session_start();
require '../vendor/jacwright/restserver/source/Jacwright/RestServer/RestServer.php';
require 'GestionFraisController.php';
use Jacwright\RestServer\RestServer;

// Les classes ne sont chargées que si elles ont besoin d'être utilisées
spl_autoload_register();

$mode   = 'debug'; // debug | production
$server = new RestServer($mode);
$server->useCors = true;
$server->allowedOrigin = 'https://gsb.virginie-claude.fr';
try {
    $server->addClass('GestionFraisController');
} catch (Exception $e) {
    echo 'exception : ' . $e->getMessage();
}
try {
    $server->handle();
} catch (Exception $e) {
    echo 'exception handle : ' . $e->getMessage();
}


