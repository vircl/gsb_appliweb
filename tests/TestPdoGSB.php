<?php
/**
 * Class Testant les fonctions de la classe PDOGsb
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Messages
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

use PHPUnit\Framework\TestCase;
require_once '../includes/config.inc.php';
require_once '../includes/class.pdogsb.inc.php';
require_once 'gendatas/majGSBTests.php';

/**
 * Class Testant les fonctions de la classe PDOGsb
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Messages
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
class TestPdoGSB extends TestCase
{
    protected static $pdo;

    /**
     * Réinitialisation des données de la base test
     * La table utilisateur contient un utilisateur et un comptable
     * Les fiches de frais du visiteur sont générées à partir d'octobre 2019
     *
     * @return void
     */
    public static function setUpBeforeClass():void
    {
        genererDatas(
            file_get_contents('gendatas/script.restoreStructureTest.sql'),
            false
        );
        self::$pdo = PdoGsb::getPdoGsb('gsb_tests');
    }

    /**
     * Test de la fonction getInfosUtilisateur
     * Cette fonction retourne, sous la forme d'un tableau,
     * les informations de l'utilisateur
     * dont le login et le mdp sont donnés en paramètres
     *
     * @return void
     */
    function testGetInfosUtilisateur():void
    {

        // Login et mdp vides
        $utilisateur = self::$pdo->getInfosUtilisateur('', '');
        $this->assertFalse(is_array($utilisateur));

        // Login vide
        $utilisateur = self::$pdo->getInfosUtilisateur('', 'oppg5');
        $this->assertFalse(is_array($utilisateur));

        // mdp vide
        $utilisateur = self::$pdo->getInfosUtilisateur('dandre', '');
        $this->assertFalse(is_array($utilisateur));

        // identifiants ok
        $utilisateur = self::$pdo->getInfosUtilisateur('dandre', 'oppg5');
        $this->assertTrue(is_array($utilisateur));

        // Teste la présence des clés sur le tableau
        $this->assertArrayHasKey('id', $utilisateur);
        $this->assertArrayHasKey('login', $utilisateur);
        $this->assertArrayHasKey('nom', $utilisateur);
        $this->assertArrayHasKey('prenom', $utilisateur);
        $this->assertArrayHasKey('idProfil', $utilisateur);
        $this->assertArrayHasKey('profil', $utilisateur);

        // Teste que la clé mdp a bien été supprimée
        $this->assertArrayNotHasKey('mdp', $utilisateur);

    }

    /**
     * Test de la fonction estPremierFraisMois
     * Cette fonction teste si une fiche de frais existe pour le mois
     * et le visiteur qui lui sont donnés en paramètres
     *
     * @return void
     */
    function testEstPremierFraisMois():void
    {
        $mois     = getMois(date('d/m/Y'));
        $visiteur = 'a17';

        // Test de valeurs erronées
        $this->assertTrue(self::$pdo->estPremierFraisMois('', '202002'));
        $this->assertTrue(self::$pdo->estPremierFraisMois($visiteur, ''));
        $this->assertTrue(self::$pdo->estPremierFraisMois($visiteur, 'aaaamm'));

        // Avant 10/2019
        $this->assertTrue(self::$pdo->estPremierFraisMois($visiteur, '201907'));

        // Mois en cours
        $this->assertTrue(self::$pdo->estPremierFraisMois($visiteur, $mois));

        // Dans longtemps
        $this->assertTrue(self::$pdo->estPremierFraisMois($visiteur, '202510'));

        // après 10/2019 (premier mois généré dans le jeu de données test)
        $this->assertFalse(self::$pdo->estPremierFraisMois($visiteur, '201912'));

    }

    /**
     * Test de la fonction creerNouvellesLignesFrais
     * Cette fonction génère une fiche de frais pour le visiteur et le mois en cours
     * Elle initialise les éléments forfaitisés à 0
     *
     * @return void
     */
    function testCreerNouvellesLignesFrais():void
    {
        $visiteur = 'a17';
        $mois     = getMois(date('d/m/Y'));
        $prec     = getMoisPrecedent($mois);

        // La fiche de frais n'existe pas
        $this->assertTrue(self::$pdo->estPremierFraisMois($visiteur, $mois));

        // La fiche de frais du mois précédent est au format CR
        $this->assertEquals(self::$pdo->getStatutFiche($visiteur, $prec), 'CR');

        // Génération de le la fiche de frais
        self::$pdo->creeNouvellesLignesFrais($visiteur, $mois);

        // La fiche de frais existe
        $this->assertFalse(self::$pdo->estPremierFraisMois($visiteur, $mois));

        // La fiche de frais est au statut CR
        $this->assertEquals(self::$pdo->getStatutFiche($visiteur, $mois), 'CR');

        // La fiche de frais du mois précédent est au statut CL
        $this->assertEquals(self::$pdo->getStatutFiche($visiteur, $prec), 'CL');

    }

}
