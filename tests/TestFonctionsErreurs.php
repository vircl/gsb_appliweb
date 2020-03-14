<?php
/**
 * Class Testant les fonctions d'affichage des erreurs
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Erreurs
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require '../includes/fct.inc.php';

use PHPUnit\Framework\TestCase;
/**
 * Class Testant les fonctions d'affichage des erreurs
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Erreurs
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
class TestFonctionsErreurs extends TestCase
{
    /**
     * Test de la fonction AjouterErreur
     *
     * @return void
     */
    function testAjouterErreur()
    {

        $erreur = 'Un message d\'erreur';

        // Le tableau des erreurs n'existe pas
        $this->assertEquals(isset($_REQUEST['erreurs']), false);

        // Test d'ajout d'un message vide
        ajouterErreur('');
        $this->assertEquals(isset($_REQUEST['erreurs']), false);

        // Test d'ajout d'un message non vide
        ajouterErreur($erreur);

        // La variable $_REQUEST['erreurs'] contient une valeur
        $this->assertEquals(isset($_REQUEST['erreurs']), true);

        // Le message d'erreur est contenu dans le tableau des erreurs
        $this->assertContains($erreur, $_REQUEST['erreurs']);
    }

    /**
     * Test de la fonction nbErreurs
     * Cette fonction retourne le nombre d'erreurs
     * contenus dans le tableau $_REQUEST['erreurs']
     *
     * @return void
     */
    function testNbErreurs()
    {
        $this->assertEquals(nbErreurs(), 0);
        ajouterErreur('une erreur test');
        $this->assertEquals(nbErreurs(), 1);

        unset($_REQUEST['erreurs']);
        // les messages vides ne doivent pas être pris en compte
        $this->assertEquals(nbErreurs(), 0);
        ajouterErreur('');
        $this->assertEquals(nbErreurs(), 0);
    }
}
