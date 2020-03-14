<?php
/**
 * Class Testant les fonctions dates
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Dates
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require '../includes/fct.inc.php';

use PHPUnit\Framework\TestCase;
/**
 * Class Testant les fonctions dates
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage Dates
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
class TestFonctionsDates extends TestCase
{
    /**
     * Test de la fonction dateAnglaisVersFrancais
     * Format attendu en entrée aaaa-mm-jj
     * Format attendu en sortie jj/mm/aaaa
     *
     * @return void
     */
    function testDateAnglaisVersFrancais()
    {
        // Teste chaine vide
        $this->assertEquals(dateAnglaisVersFrancais(''), false);

        // Teste chaîne ne correspondant pas au masque aaaa-mm-dd
        $this->assertEquals(dateAnglaisVersFrancais('une chaine'), false);
        $this->assertEquals(dateAnglaisVersFrancais('19/02/2020'), false);

        // Teste chaîne correspondant au masque avec mois invalide
        $this->assertEquals(dateAnglaisVersFrancais('2020-13-31'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2020-0-31'), false);

        // chaîne corresopndant au masque avec jour invalide
        $this->assertEquals(dateAnglaisVersFrancais('2020-1-32'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2020-01-32'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2020-1-0'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2020-01-0'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2020-2-30'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2020-02-30'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2019-2-29'), false);
        $this->assertEquals(dateAnglaisVersFrancais('2019-02-29'), false);

        // chaîne correspondant au masque valide
        $this->assertEquals(dateAnglaisVersFrancais('2020-1-1'), '01/01/2020');
        $this->assertEquals(dateAnglaisVersFrancais('2020-2-29'), '29/02/2020');
        $this->assertEquals(dateAnglaisVersFrancais('2020-2-12'), '12/02/2020');
        $this->assertEquals(dateAnglaisVersFrancais('2020-01-01'), '01/01/2020');
        $this->assertEquals(dateAnglaisVersFrancais('2020-02-29'), '29/02/2020');
        $this->assertEquals(dateAnglaisVersFrancais('2020-02-12'), '12/02/2020');
    }

    /**
     * Test de la fonction dateFrancaisVersAnglais
     * Format attendu en entrée jj/mm/aaaa
     * Format attendu en sortie Y-m-d (aaaa-mm-dd)
     *
     * @return void
     */
    function testDateFrancaisVersAnglais()
    {
        // Teste chaine vide
        $this->assertEquals(dateFrancaisVersAnglais(''), false);

        // Teste chaîne ne correspondant pas au masque aaaa-mm-dd
        $this->assertEquals(dateFrancaisVersAnglais('une chaine'), false);
        $this->assertEquals(dateFrancaisVersAnglais('2020-02-19'), false);

        // Teste chaîne correspondant au masque avec mois invalide
        $this->assertEquals(dateFrancaisVersAnglais('31/13/2020'), false);
        $this->assertEquals(dateFrancaisVersAnglais('31/0/2020'), false);

        // chaîne corresopndant au masque avec jour invalide
        $this->assertEquals(dateFrancaisVersAnglais('32/01/2020'), false);
        $this->assertEquals(dateFrancaisVersAnglais('0/1/2020'), false);
        $this->assertEquals(dateFrancaisVersAnglais('0/01/2020'), false);
        $this->assertEquals(dateFrancaisVersAnglais('30/2/2020'), false);
        $this->assertEquals(dateFrancaisVersAnglais('30/02/2020'), false);
        $this->assertEquals(dateFrancaisVersAnglais('29/2/2019'), false);
        $this->assertEquals(dateFrancaisVersAnglais('29/02/2019'), false);

        // chaîne correspondant au masque valide
        $this->assertEquals(dateFrancaisVersAnglais('1/1/2020'), '2020-01-01');
        $this->assertEquals(dateFrancaisVersAnglais('29/2/2020'), '2020-02-29');
        $this->assertEquals(dateFrancaisVersAnglais('2/12/2020'), '2020-12-02');
        $this->assertEquals(dateFrancaisVersAnglais('01/01/2020'), '2020-01-01');
        $this->assertEquals(dateFrancaisVersAnglais('29/02/2020'), '2020-02-29');
        $this->assertEquals(dateFrancaisVersAnglais('02/12/2020'), '2020-12-02');

    }

    /**
     * Test de la fonction estDateDepassee
     * Cette fonction renvoie true si la date passée en paramètres
     * est dépassée d'un an ou plus ou n'est pas une date valide
     *
     * @return void
     */
    function testEstDateDepassee()
    {
        // Formats non valides
        $this->assertEquals(estDateDepassee(''), true);
        $this->assertEquals(estDateDepassee('une date'), true);

        // Date antérieures
        $this->assertEquals(estDateDepassee('29/02/2018'), true);
        $this->assertEquals(estDateDepassee('18/02/2009'), true);

        // Date antérieure non valide
        $this->assertEquals(estDateDepassee('01/13/2018'), true);

        // Date postérieure non valide
        $this->assertEquals(estDateDepassee('35/02/2025'), true);

        // Dates ok
        $this->assertEquals(estDateDepassee('20/02/2025'), false);

        //$this->assertEquals(estDateDepassee('20/02/25'), false); // Fails

    }

    /**
     * Test d ela fonction estDateValide
     * Cette fonction teste si une chaîne passée en paramètres
     * est une date valide
     * La date doit être saisie au format français (jj/mm/aaaa)
     *
     * @return void
     */
    function testEstDateValide()
    {
        // Formats non valides
        $this->assertEquals(estDateValide(''), false);
        $this->assertEquals(estDateValide('test'), false);
        $this->assertEquals(estDateValide('jj/mm/aaaa'), false);

        // Dates incorrectes
        $this->assertEquals(estDateValide('2020-02-19'), false);
        $this->assertEquals(estDateValide('29/02/2019'), false);
        $this->assertEquals(estDateValide('29/13/2019'), false);

        // Dates ok
        $this->assertEquals(estDateValide('19/2/2019'), true);
        $this->assertEquals(estDateValide('19/02/19'), true);
        $this->assertEquals(estDateValide('1/02/2019'), true);
        $this->assertEquals(estDateValide('19/02/2020'), true);
        $this->assertEquals(estDateValide('29/02/2020'), true);
    }

    /**
     * Test de la fonction getMois
     * Cette fonction retourne une chaîne de caractère
     * sous la forme aaaamm
     * d'après la date qui lui est donnée en paramètres
     *
     * @return void
     */
    function testGetMois()
    {
        // Formats invalides
        $this->assertEquals(getMois(''), false);
        $this->assertEquals(getMois('test'), false);
        $this->assertEquals(getMois('2020-02-25'), false);

        // Dates inexistantes
        $this->assertEquals(getMois('29/02/2018'), false);
        $this->assertEquals(getMois('25/13/2020'), false);

        // Dates ok
        $this->assertEquals(getMois('19/02/2020'), '202002');
        $this->assertEquals(getMois('19/2/2020'), '202002');
        $this->assertEquals(getMois('19/2/20'), '202002');
        $this->assertEquals(getMois('19/12/2020'), '202012');

    }

    /**
     * Test de la fonction getDateFormat
     * Cette fonction retourne un masque sous la forme d/m/Y
     * La date doit être saisie au format français
     *
     * @return void
     */
    function testGetDateFormat()
    {
        // Format en entrée non valides
        $this->assertEquals(getDateFormat(''), '');
        $this->assertEquals(getDateFormat('test'), '');
        $this->assertEquals(getDateFormat('2020-02-19'), '');
        $this->assertEquals(getDateFormat('29/02/2018'), '');

        // Dates en entrée valides
        $this->assertEquals(getDateFormat('1/02/2020'), 'j/m/Y');
        $this->assertEquals(getDateFormat('01/2/2020'), 'd/n/Y');
        $this->assertEquals(getDateFormat('01/02/20'), 'd/m/y');
        $this->assertEquals(getDateFormat('1/2/20'), 'j/n/y');
        $this->assertEquals(getDateFormat('01/02/2020'), 'd/m/Y');

    }

    /**
     * Test de la fonction estMoisValide
     * qui vérifie que le mois donné en paramètres est bien
     * sous la forme aaaamm
     *
     * @return void
     */
    function testEstMoisValide()
    {
        $this->assertEquals(estMoisValide('202002'), true);
        $this->assertEquals(estMoisValide('20202'), false);
        $this->assertEquals(estMoisValide('2014565645'), false);
        $this->assertEquals(estMoisValide('201454'), false);
        $this->assertEquals(estMoisValide(''), false);
        $this->assertEquals(estMoisValide('aaaamm'), false);
    }

    /**
     * Test de la fonction getMoisPrecedent
     * Cette fonction retourne, sous la forme aaaamm,  le mois précédent
     * celui donné en paramètres
     * Paramètre en entrée aaaamm
     * Paramètre en sortie aaaamm
     *
     * @return void
     */
    function testGetMoisPrecedent()
    {
        // Formats incorrects
        $this->assertEquals(getMoisPrecedent(''), '');
        $this->assertEquals(getMoisPrecedent('aaaamm'), '');
        $this->assertEquals(getMoisPrecedent('201'), '');
        $this->assertEquals(getMoisPrecedent('201452454'), '');

        // formats corrects
        $this->assertEquals(getMoisPrecedent('202002'), '202001');
        $this->assertEquals(getMoisPrecedent('201901'), '201812');
    }

    /**
     * Test de la fonction getMoisPrecedent
     * Cette fonction retourne, sous la forme aaaamm,  le mois suivant
     * celui donné en paramètres
     * Paramètre en entrée aaaamm
     * Paramètre en sortie aaaamm
     *
     * @return void
     */
    function testGetMoisSuivant()
    {
        // Formats incorrects
        $this->assertEquals(getMoisSuivant(''), '');
        $this->assertEquals(getMoisSuivant('aaaamm'), '');
        $this->assertEquals(getMoisSuivant('201'), '');
        $this->assertEquals(getMoisSuivant('201452454'), '');

        // formats corrects
        $this->assertEquals(getMoisSuivant('202002'), '202003');
        $this->assertEquals(getMoisSuivant('201912'), '202001');
    }

    /**
     * Test de la fonction getNumAnnee
     * Paramètre en entree aaaamm
     * Paramètre en sortie : aaaa
     *
     * @return void
     */
    function testGetNumAnnee()
    {
        // Formats incorrects
        $this->assertEquals(getNumAnnee(''), '');
        $this->assertEquals(getNumAnnee('aaaamm'), '');
        $this->assertEquals(getNumAnnee('20200201'), '');

        // Format correct
        $this->assertEquals(getNumAnnee('202002'), '2020');
    }

    /**
     * Test de la fonction getNomMois
     * Cette fonction retourne le nom du mois
     * Paramètre sen entrée aaaamm
     * Paramètre en sortie (janvier, février ...)
     *
     * @return void
     */
    function testGetNomMois()
    {
        // formats incorrects
        $this->assertEquals(getNomMois(''), '');
        $this->assertEquals(getNomMois('aaaamm'), '');
        $this->assertEquals(getNomMois('12'), '');

        // date ok
        $this->assertEquals(getNomMois('202001'), 'janvier');
    }

    /**
     * Test de la fonction getNumMois
     * Paramètre en entree aaaamm
     * Paramètre en sortie : mm
     *
     * @return void
     */
    function testGetNumMois()
    {
        // Formats incorrects
        $this->assertEquals(getNumMois(''), '');
        $this->assertEquals(getNumMois('aaaamm'), '');
        $this->assertEquals(getNumMois('20200201'), '');

        // Format correct
        $this->assertEquals(getNumMois('202002'), '02');
        $this->assertEquals(getNumMois('202012'), '12');
    }
}
