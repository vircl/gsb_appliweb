<?php
/**
 * Class Testant les fonctions de contrôle des données
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Controle_Donnees
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require '../includes/fct.inc.php';

use PHPUnit\Framework\TestCase;
/**
 * Class Testant les fonctions de contrôle des données
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Controle_Donnees
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
class TestFonctionsControleDonnees extends TestCase
{
    /**
     * Test de la fonction estTableauEntier
     * Cette fonction teste si la variable qui lui est donnée en
     * paramètres est bien un tableau d'entiers positifs ou nuls
     *
     * @return void
     */
    function testEstTableauEntier()
    {
        // Test d'une valeur nulle
        $this->assertEquals(estTableauEntiers(0), false);

        // Test d'une valeur numérique non nulle
        $this->assertEquals(estTableauEntiers(11), false);

        // Test d'une valeur chaîne
        $this->assertEquals(estTableauEntiers("test"), false);

        // Test d'un array non numérique
        $this->assertEquals(estTableauEntiers(array("test", "essai")), false);

        // Test d'un array mixte
        $this->assertEquals(estTableauEntiers(array(1, "test")), false);

        // Test de valeurs négatives
        $this->assertEquals(estTableauEntiers(array(-1)), false);

        // Test d'un array d'une seule valeur numérique nulle
        $this->assertEquals(estTableauEntiers(array(0)), true);

        // Test d'un array d'une seule valeur numérique
        $this->assertEquals(estTableauEntiers(array(1)), true);

        // Test d'un array de plusieurs valeurs numériques
        $this->assertEquals(estTableauEntiers(array(1,2,3)), true);

    }

    /**
     * Test fonction estEntierPositif
     * Indique si une valeur est un entier positif ou nul
     *
     * @return void
     */
    function testEstEntierPositif()
    {
        // Valeur texte
        $this->assertEquals(estEntierPositif("test"), false);

        // Valeur négative
        $this->assertEquals(estEntierPositif(-1), false);

        // Valeur nulle
        $this->assertEquals(estEntierPositif(0), true);

        // Valeur nulle
        $this->assertEquals(estEntierPositif(null), true);

        // Valeur positive
        $this->assertEquals(estEntierPositif(1), true);
    }
}
