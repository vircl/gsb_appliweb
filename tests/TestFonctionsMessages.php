<?php
/**
 * Class Testant les fonctions d'affichage des messages
 * définies dans le fichier fct.inc.php
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

require '../includes/fct.inc.php';

use PHPUnit\Framework\TestCase;
/**
 * Class Testant les fonctions d'affichage des messages
 * définies dans le fichier fct.inc.php
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
class TestFonctionsMessages extends TestCase
{
    /**
     * Test de la fonction AjouterMessage
     *
     * @return void
     */
    function testAjouterMessage()
    {
        $message = 'Un message';

        // Le tableau des messages n'existe pas
        $this->assertEquals(isset($_REQUEST['messages']), false);

        // Test d'ajout d'un message vide
        ajouterMessage('');
        $this->assertEquals(isset($_REQUEST['messages']), false);

        // Test d'ajout d'un message non vide
        ajouterMessage($message);

        // La variable $_REQUEST['messages'] contient une valeur
        $this->assertEquals(isset($_REQUEST['messages']), true);

        // Le message d'erreur est contenu dans le tableau des messages
        $this->assertContains($message, $_REQUEST['messages']);
    }

    /**
     * Test de la fonction nbMessages
     * Cette fonction retourne le nombre d'erreurs
     * contenus dans le tableau $_REQUEST['messages']
     *
     * @return void
     */
    function testNbErreurs()
    {
        $this->assertEquals(nbMessages(), 0);
        ajouterMessage('un message test');
        $this->assertEquals(nbMessages(), 1);

        unset($_REQUEST['messages']);

        // les messages vides ne doivent pas être pris en compte
        $this->assertEquals(nbMessages(), 0);
        ajouterMessage('');
        $this->assertEquals(nbMessages(), 0);

    }
}
