<?php
/**
 * Class Testant les fonctions d'authentification
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Auth
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require '../includes/fct.inc.php';

use PHPUnit\Framework\TestCase;
/**
 * Class Testant les fonctions du fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Auth
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
class TestFonctionsAuth extends TestCase
{

    /**
     * Test de la fonction connecter
     *
     * @return void
     */
    function testConnecter()
    {
        // Les variables session sont vides
        $this->assertEquals(isset($_SESSION['idUtilisateur']), false);
        $this->assertEquals(isset($_SESSION['nom']), false);
        $this->assertEquals(isset($_SESSION['prenom']), false);
        $this->assertEquals(isset($_SESSION['idProfil']), false);

        // L'utilisateur n'est pas connecté
        $this->assertEquals(estConnecte(), false);

        // Connexion d'un utilisateur
        connecter('a131', 'VILLECHALANE', 'Louis', '1');

        // Les variables session ont été renseignées
        $this->assertEquals(isset($_SESSION['idUtilisateur']), true);
        $this->assertEquals(isset($_SESSION['nom']), true);
        $this->assertEquals(isset($_SESSION['prenom']), true);
        $this->assertEquals(isset($_SESSION['idProfil']), true);

        // Vérification du contenu des variables
        $this->assertEquals($_SESSION['idUtilisateur'], 'a131');
        $this->assertEquals($_SESSION['nom'], 'VILLECHALANE');
        $this->assertEquals($_SESSION['prenom'], 'Louis');
        $this->assertEquals($_SESSION['idProfil'], '1');

        // Vérifie que l'utilisateur est connecté
        $this->assertEquals(estConnecte(), true);

    }

    /**
     * Test de la fonction Deconnecter
     * TODO : Erreur initialisation session sur fonction deconnecter()
     *
     * @return void
     */
    function testDeconnecter()
    {

        connecter('a131', 'VILLECHALANE', 'Louis', '1');

        // L'utilisateur est connecté
        $this->assertEquals(estConnecte(), true);


        deconnecter();

        // L'utlisateur n'est plus connecté
        $this->assertEquals(estConnecte(), false);

    }

    /**
     * Test de la fonction estConnecte
     *
     * @return void
     */
    function testEstConnecte()
    {
        // L'utilisateur n'est pas connecté
        $this->assertEquals(estConnecte(), false);

        // Connexion d'un utilisateur
        connecter('a131', 'VILLECHALANE', 'Louis', '1');

        // L'utilisateur est connecté
        $this->assertEquals(estConnecte(), true);

        deconnecter();

        // L'utilisateur n'est plus connecté
        // L'utilisateur est connecté
        $this->assertEquals(estConnecte(), false);
    }

    /**
     * Teste de la fonction estComptable 1/2
     *
     * @return void
     */
    function testEstComptableFalse()
    {
        // Connexion d'un utilisateur
        connecter('a131', 'VILLECHALANE', 'Louis', '1');

        // L'utilisateur n'est pas comptable
        $this->assertEquals(estComptable(), false);

        deconnecter();
    }

    /**
     * Test de la fonction estComptable 2/2
     *
     * @return void
     */
    function testEstComptableTrue()
    {
        // Connexion d'un utilisateur
        connecter('f21', 'FINCK', 'Jacques', '2');

        // L'utilisateur est comptable
        $this->assertEquals(estComptable(), true);

        deconnecter();
    }

    /**
     * Test de la fonction estProprietaire
     *
     * @return void
     */
    function testEstProprietaire()
    {
        // Utilisateur non connecté
        $this->assertEquals(estConnecte(), false);

        // Utilisateur non propriétaire de la fiche a131
        $this->assertEquals(estProprietaire('a131'), false);

        // L'utilisateur n'est pas propriétaire de la fiche a17
        $this->assertEquals(estProprietaire('a17'), false);

        // Connexion d'un utilisateur
        connecter('a131', 'VILLECHALANE', 'Louis', '1');

        // L'utilisateur est propriétaire de la fiche a131
        $this->assertEquals(estProprietaire('a131'), true);

        // L'utilisateur n'est pas propriétaire de la fiche a17
        $this->assertEquals(estProprietaire('a17'), false);

        // Deconnexion
        deconnecter();

        // L'utilisateur n'est pas propriétaire de la fiche a131
        $this->assertEquals(estProprietaire('a131'), false);

        // L'utilisateur n'est pas propriétaire de la fiche a17
        $this->assertEquals(estProprietaire('a17'), false);
    }

    // TODO : vérification token


}