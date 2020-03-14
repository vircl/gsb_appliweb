<?php
/**
 * Class Testant les fonctions d'évaluation des fiches de frais
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage FicheFrais
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require '../includes/fct.inc.php';

use PHPUnit\Framework\TestCase;
/**
 * Class Testant les fonctions d'évaluation des fiches de frais
 * définies dans le fichier fct.inc.php
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage FicheFrais
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
class TestFonctionsFicheFrais extends TestCase
{

    /**
     * Test du statut de la fiche de frais
     * La fiche de frais est modifiable si elle a le statut CR ou CL
     *
     * @return void
     */
    function testEstFicheModifiable()
    {
        // Statut inexistant
        $this->assertEquals(estFicheModifiable('XX'), false);

        // Statut numérique
        $this->assertEquals(estFicheModifiable(56), false);

        // Statut numérique 0
        $this->assertEquals(estFicheModifiable(0), false);

        // Statut Saisie en cours (modifiable par les visiteurs)
        $this->assertEquals(estFicheModifiable('CR'), true);

        // Statut clôturée (modfiable par les comptables)
        $this->assertEquals(estFicheModifiable('CL'), true);

        // Statut validée (non modifiable)
        $this->assertEquals(estFicheModifiable('VA'), false);

        // Statut remboursée (non modifiable)
        $this->assertEquals(estFicheModifiable('RB'), false);
    }

    /**
     * Test de la fonction estFraisRefusé
     * Le frais est refusé si son libellé commence par "REFUSE "
     *
     * @return void
     */
    function testEstFraisRefuse()
    {
        // Test libellé vide
        $this->assertEquals(estFraisRefuse(''), false);

        // Test libellé numérique
        $this->assertEquals(estFraisRefuse(11), false);

        // Test libellé 0
        $this->assertEquals(estFraisRefuse(0), false);

        // Test libellé texte
        $this->assertEquals(estFraisRefuse('Un frais test'), false);

        // Test frais refuse
        $this->assertEquals(estFraisRefuse('refuse un frais test'), true);
        $this->assertEquals(estFraisRefuse('refusé un frais test'), true);
        $this->assertEquals(estFraisRefuse('réfuse un frais test'), true);
        $this->assertEquals(estFraisRefuse('REFUSE un frais test'), true);

    }

    /**
     * Test de la fonction getLienRetour
     * Utilisateur visiteur
     *
     * @return void
     */
    function testGetLienRetourVisiteur()
    {

        $visiteur = 'a131';

        // Etat CR
        $this->assertEquals(
            getLienRetour($visiteur, 'CR'),
            "index.php?idVisiteur=$visiteur"
        );

        // Etat CL
        $this->assertEquals(
            getLienRetour($visiteur, 'CL'),
            "index.php?idVisiteur=$visiteur&uc=listeFrais"
        );

        // Etat VA
        $this->assertEquals(
            getLienRetour($visiteur, 'VA'),
            "index.php?idVisiteur=$visiteur&uc=listeFrais"
        );

        // Etat RB
        $this->assertEquals(
            getLienRetour($visiteur, 'RB'),
            "index.php?idVisiteur=$visiteur&uc=listeFrais"
        );

        // Etat inconnu
        $this->assertEquals(
            getLienRetour($visiteur, 'XX'),
            "index.php?idVisiteur=$visiteur&uc=listeFrais"
        );

        // Etat Numérique
        $this->assertEquals(
            getLienRetour($visiteur, 11),
            "index.php?idVisiteur=$visiteur&uc=listeFrais"
        );

        // Etat 0
        $this->assertEquals(
            getLienRetour($visiteur, 0),
            "index.php?idVisiteur=$visiteur"
        );

    }


    /**
     * Test de la fonction getLienRetour
     * Utilisateur visiteur
     *
     * @return void
     */
    function testGetLienRetourComptable()
    {

        $visiteur = 'a131';

        connecter('f21', 'FINCK', 'Jacques', '2');

        $this->assertEquals(estComptable(), true);

        // Etat CR
        $this->assertEquals(
            getLienRetour($visiteur, 'CR'),
            "index.php?idVisiteur=$visiteur"
        );

        // Etat CL
        $this->assertEquals(
            getLienRetour($visiteur, 'CL'),
            "index.php?idVisiteur=$visiteur&uc=listeFrais&action=fichesAValider"
        );

        // Etat VA
        $this->assertEquals(
            getLienRetour($visiteur, 'VA'),
            "index.php?idVisiteur=$visiteur&uc=listeFrais&action=fichesAValider"
        );

        // Etat RB
        $this->assertEquals(
            getLienRetour($visiteur, 'RB'),
            "index.php?idVisiteur=$visiteur&uc=listeFrais&action=fichesARembourser"
        );

        // Etat inconnu
        $this->assertEquals(
            getLienRetour($visiteur, 'XX'),
            "index.php?idVisiteur=$visiteur"
        );

        // Etat Numérique
        $this->assertEquals(
            getLienRetour($visiteur, 11),
            "index.php?idVisiteur=$visiteur"
        );

        // Etat 0
        $this->assertEquals(
            getLienRetour($visiteur, 0),
            "index.php?idVisiteur=$visiteur"
        );

        deconnecter();

    }


    /**
     * Test fonction lesQteFraisValides
     * Cette fonction renvoie true si l'argument passé
     * en paramètres est un tableau d'entiers
     *
     * @return void
     */
    function testLesQteFraisValides()
    {
        // Test valeur numérique nulle
        $this->assertEquals(lesQteFraisValides(0), false);

        // Test valeur numérique non nulle
        $this->assertEquals(lesQteFraisValides(1), false);

        // Test valeur numérique négative
        $this->assertEquals(lesqteFraisValides(-1), false);

        // Test valeur chaîne
        $this->assertEquals(lesQteFraisValides("test"), false);

        // Test tableau chaîne
        $this->assertEquals(lesQteFraisValides(array("test", "essai")), false);

        // Test tableau mixte
        $this->assertEquals(lesQteFraisValides(array("test", 1)), false);

        // Tableau une valeur négative
        $this->assertEquals(lesQteFraisValides(array(-1)), false);

        // Tableau une valeur nulle
        $this->assertEquals(lesQteFraisValides(array(0)), true);

        // Tableau une valeur non nulle
        $this->assertEquals(lesQteFraisValides(array(1)), true);

        // Tableau plusieurs valeurs non nulles
        $this->assertEquals(lesQteFraisValides(array(1,2,3)), true);
    }

    /**
     * Test fonction valideInfosFrais
     * Cette fonction ajoute un ou plusieurs message d'erreurs
     * si les frais ne sont pas valides
     *
     * @return void
     */
    function testValideInfosFrais()
    {
        // Le tableau des erreurs est vide
        $this->assertEquals(isset($_REQUEST['erreurs']), false);

        // TESTS SUR LA DATE

        // Date vide
        valideInfosFrais('', 'un libellé', 15);
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'Le champ date ne doit pas être vide',
            $_REQUEST['erreurs']
        );

        // Réinitialise le tableau des erreurs
        unset($_REQUEST['erreurs']);
        $this->assertEquals(isset($_REQUEST['erreurs']), false);

        // Date non valide
        valideInfosFrais('test', 'un libellé', 15);
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'Date invalide',
            $_REQUEST['erreurs']
        );

        unset($_REQUEST['erreurs']);

        // Date dépassée
        valideInfosFrais('19/01/2019', 'un libellé', 15);
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'date d\'enregistrement du frais dépassé, plus de 1 an',
            $_REQUEST['erreurs']
        );

        unset($_REQUEST['erreurs']);

        // Date ok (test effectué le 19/02/2020)
        valideInfosFrais('19/01/2020', 'un libellé', 15);
        $this->assertEquals(isset($_REQUEST['erreurs']), false);

        // TESTS LIBELLE

        // Libellé vide
        valideInfosFrais('19/02/2020', '', 15);
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'Le champ description ne peut pas être vide',
            $_REQUEST['erreurs']
        );
        unset($_REQUEST['erreurs']);

        // Libellé numérique
        valideInfosFrais('19/02/2020', 1234, 15);
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'Le champ description doit contenir uniquement du texte',
            $_REQUEST['erreurs']
        );
        unset($_REQUEST['erreurs']);

        // Libellé ok
        valideInfosFrais('19/02/2020', 'un libellé', 15);
        $this->assertEquals(isset($_REQUEST['erreurs']), false);


        // TESTS MONTANT

        // Montant vide
        valideInfosFrais('19/02/2020', 'un libellé', '');
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'Le champ montant ne peut pas être vide',
            $_REQUEST['erreurs']
        );
        unset($_REQUEST['erreurs']);

        // Montant textuel
        valideInfosFrais('19/02/2020', 'un libellé', 'texte');
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'Le champ montant doit être numérique',
            $_REQUEST['erreurs']
        );
        unset($_REQUEST['erreurs']);

        // Montant négatif
        valideInfosFrais('19/02/2020', 'un libellé', -1);
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'La valeur du montant doit être strictement positive',
            $_REQUEST['erreurs']
        );
        unset($_REQUEST['erreurs']);

        // Montant nul
        valideInfosFrais('19/02/2020', 'un libellé', 0);
        $this->assertEquals(isset($_REQUEST['erreurs']), true);
        $this->assertContains(
            'Le champ montant ne peut pas être vide',
            $_REQUEST['erreurs']
        );
        unset($_REQUEST['erreurs']);

        // Montant entier positif
        valideInfosFrais('19/02/2020', 'un libellé', 1);
        $this->assertEquals(isset($_REQUEST['erreurs']), false);

        // Montant décimal
        valideInfosFrais('19/02/2020', 'un libellé', 1.5);
        $this->assertEquals(isset($_REQUEST['erreurs']), false);

    }

}
