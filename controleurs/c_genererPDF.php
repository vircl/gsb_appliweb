<?php
/**
 * Gestion de l'export en PDF
 *
 * Teste si l'utilisateur est conncecté et est autorisé
 * à consulter la fiche de frais
 * Génère la fiche de frais au format PDF en utilisant la
 * librairie TCPDF
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Controleurs
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2020 Virginie CLAUDE
 * @license    Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link       https://tcpdf.org/ Documentation de la librairie TCPDF
 */

$idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_STRING);
$mois       = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_STRING);

$auth = verifierAcces($idVisiteur);

if ($auth) {
    if ($idVisiteur && $mois) {
        ob_start();

        include_once VENDOR.'tecnickcom/tcpdf/tcpdf.php';

        $lesFraisForfait     = $pdo->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $nbJustificatifs     = $pdo->getNbJustificatifs($idVisiteur, $mois);
        $montantValide       = $pdo->getMontantValide($idVisiteur, $mois);
        $infosFiche          = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
        $infosVisiteur       = $pdo->getLesInfosVisiteur($idVisiteur);
        $tauxKM              = $pdo->getTauxKM($idVisiteur);

        $nomFichier = $mois . '_' . $idVisiteur . '_fichefrais.pdf';
        $numMois  = getNumMois($mois);
        $numAnnee = getNumAnnee($mois);
        $nomMois  = getNomMois($mois);
        $identiteVisiteur = strtoupper($infosVisiteur['nom'])
            . ' ' . ucfirst($infosVisiteur['prenom']);


        // Création d'un nouveau fichier PDF
        $pdf = new TCPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );

        // Informations document
        $pdf->SetCreator('GSB');
        $pdf->SetAuthor('GSB');
        $pdf->SetTitle('Fiche de Frais');
        $pdf->SetSubject(
            'Fiche de frais du visiteur ' . $identiteVisiteur
        );
        $pdf->SetKeywords('GSB, fiche de frais, visiteur');

        // Supprime le Header et le footer par défaut
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Définition des marges
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // Saut de page automatiuqe
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // Police de caractères utilisée
        $pdf->SetFont('Helvetica', '', 11);

        // Ajout d'une page au document
        $pdf->AddPage();

        // Contenu HTML à afficher
        // (la variable $html est définie dans le fichier include)
        include './vues/v_pdf.php';

        // Ecriture du contenu html dans le document
        $pdf->writeHTML($html, true, false, true, false, '');

        // Positionne le pointeur sur la dernière page
        $pdf->lastPage();

        // Affiche le document
        $pdf->Output($nomFichier, 'I');

        ob_end_flush();
    } else {
        include './vues/v_404.php';
    }
} else {
    include './vues/v_interdit.php';
}




