<?php

/**
 * Classe d'accès aux données.
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Config
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

// Configuration bdd
define('DB_HOST', 'localhost');
define('DB_BDD', 'gsb_frais');
define('DB_USER', 'root');
define('DB_PWD', '');

// Configuration API
define('ADRESSE_API', 'http://www.url/api');
define(
    'API_KEY',
    'clé de l\api'
);

// Configuration application
define('NOM_LABO', 'Galaxy Swiss Bourdin');
define('TITRE_APPLI', 'Gestion des frais de visite');
define('LOGO', 'logo.png');

// Lien répertoires
define('ASSETS', './assets/');
define('CSS', ASSETS . 'styles/');
define('JS', ASSETS . 'scripts/');
define('IMAGES', ASSETS . 'images');
define('VENDOR', './vendor/');
define('VUES', './vues/');
define('PARTS', VUES . 'parts/');
