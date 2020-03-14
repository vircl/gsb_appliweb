<?php
/**
 * Gestion des frais
 *
 * Sous-contrôleur de modification d'une fiche de frais.
 *
 * La fiche de frais est modifiable par son propriétaire lorsqu'elle
 * est à l'état CR (Création)
 * ou par un comptable lorsqu'elle a un statut > à CR
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Controleurs
 * @author     Réseau CERTA <contact@reseaucerta.org>
 * @author     José GIL <jgil@ac-nice.fr>
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2017 Réseau CERTA
 * @license    Réseau CERTA | Virginie CLAUDE
 * @version    GIT:<1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$etat       = false;
$infosFiche = null;

// Action à traiter
$action     = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_STRING);
$mois       = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_STRING);

if (!isset($mois) || $mois === '') {
    $mois = getMois(date('d/m/Y'));
}

// Affiche une erreur si l'utilisateur n'est pas autorisé à afficher cette ressource
$auth = verifierAcces($idVisiteur);

if ($auth) {

    /* ----------------- TRAITEMENTS --------------------------*/
    switch ($action) {

    // Création de la fiche de frais si non existante (accès visiteur)
    case 'saisirFrais':

        if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
            $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
        }
        break;

    // Mise à jour des éléments forfaitisés (accès visiteur & comptable)
    case 'validerMajFraisForfait':

        $lesFrais = filter_input(
            INPUT_POST,
            'lesFrais',
            FILTER_DEFAULT,
            FILTER_FORCE_ARRAY
        );
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            ajouterMessage('Elements forfaitisés mis à jour');
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
        }
        break;


    // Création d'un élément hors forfait (accès visiteur & comptable)
    case 'validerCreationFrais':

        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);

        valideInfosFrais($dateFrais, $libelle, $montant);

        if (!nbErreurs()) {
            $pdo->creeNouveauFraisHorsForfait(
                $idVisiteur,
                $mois,
                $libelle,
                $dateFrais,
                $montant
            );
        }
        break;

    // Mise à jour des éléments hors forfait (accès visiteur et comptable)
    case 'majFraisHorsForfait':

        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

        valideInfosFrais($dateFrais, $libelle, $montant);

        if (!nbErreurs()) {
            $pdo->majFraisHorsForfait(
                $id,
                $libelle,
                $dateFrais,
                $montant
            );
        }
        ajouterMessage('Frais hors forfait mis à jour');
        //include 'vues/messages/v_messages.php';
        break;


    // Refus d'un frais hors forfait (accès comptable)
    case 'refuserFraisHorsForfait':

        redirectionSiNonComptable('gererFrais');

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $infos = $pdo->getInfosFraisHorsForfait($id);
        $libelle = $infos['libelle'];
        $montant = $infos['montant'];
        $dateFrais = dateAnglaisVersFrancais($infos['date']);

        if (!estFraisRefuse($libelle)) {
            $libelle = 'REFUSE ' . $libelle;

            valideInfosFrais($dateFrais, $libelle, $montant);

            if (!nbErreurs()) {
                $pdo->majFraisHorsForfait(
                    $id,
                    $libelle,
                    $dateFrais,
                    $montant
                );
            }
        }
        ajouterMessage('Frais refusé');
        break;


    // Accepter un frais hors forfait
    // si le frais a été précédemment refusé (accès comptable)
    case 'accepterFraisHorsForfait' :

        redirectionSiNonComptable('gererFrais');

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $infos = $pdo->getInfosFraisHorsForfait($id);
        $libelle = $infos['libelle'];
        $montant = $infos['montant'];
        $dateFrais = dateAnglaisVersFrancais($infos['date']);

        if (estFraisRefuse($libelle)) {
            $libelle = substr($libelle, 7, strlen($libelle) - 7);

            valideInfosFrais($dateFrais, $libelle, $montant);

            if (!nbErreurs()) {
                $pdo->majFraisHorsForfait(
                    $id,
                    $libelle,
                    $dateFrais,
                    $montant
                );
            }
        }
        ajouterMessage('Frais accepté');
        break;

    // Reporter le frais au mois suivant (accès comptable)
    case 'reporterFraisHorsForfait':

        redirectionSiNonComptable('gererFrais');

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $infos = $pdo->getInfosFraisHorsForfait($id);
        $libelle = $infos['libelle'];
        $montant = $infos['montant'];
        $mois = $infos['mois'];
        $dateFrais = dateAnglaisVersFrancais($infos['date']);
        $moisSuivant = getMoisSuivant($mois);

        valideInfosFrais($dateFrais, $libelle, $montant);

        if (!nbErreurs()) {

            //Vérifie que la fiche du mois suivant existe sinon la créer
            if ($pdo->estPremierFraisMois($idVisiteur, $moisSuivant)) {
                $pdo->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
            }

            //Ajoute le frais hors forfait à la fiche du mois suivant
            $pdo->creeNouveauFraisHorsForfait(
                $idVisiteur,
                $moisSuivant,
                $libelle,
                $dateFrais,
                $montant
            );

            // Supprime le frais hors forfait du mois en cours
            $pdo->supprimerFraisHorsForfait($id);

            ajouterMessage('Frais reporté');
        }
        break;

    // Suppression d'un frais hors forfait
    case 'supprimerFrais':

        $idFrais = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $pdo->supprimerFraisHorsForfait($idFrais);
        break;

    // Mise à jour du nombre de justificatifs reçus pour cette fiche de frais
    // (accès comptable)
    case 'majNbJustificatif':

        redirectionSiNonComptable('gererFrais');

        $nb = filter_input(INPUT_POST, 'nbJustificatifs', FILTER_SANITIZE_STRING);
        $pdo->majNbJustificatifs(
            $idVisiteur,
            $mois,
            $nb
        );
        ajouterMessage('Nombre de justificatifs mis à jour');
        //include 'vues/messages/v_messages.php';
        break;

    // Validation de la fiche de frais (accès comptable)
    // Passe la fiche de frais à l'état "VA"
    case 'validerFicheFrais':

        redirectionSiNonComptable('gererFrais');

        $montant = $pdo->getMontantValide($idVisiteur, $mois);
        $pdo->validerFicheFrais($idVisiteur, $mois, $montant);
        ajouterMessage('Fiche de frais validée');
        break;

    // Remboursement de la fiche de frais (accès comptable)
    // Passe la fiche d e frais à l'état "RB"
    case 'rembourserFicheFrais' :

        redirectionSiNonComptable('gererFrais');

        if ($idVisiteur && $mois) {
            // Met à jour le statut de la fiche de frais
            $pdo->majEtatFicheFrais($idVisiteur, $mois, 'RB');
            header("Location: index.php?uc=gererFrais&action=ficheRemboursee&idVisiteur=" . $idVisiteur . "&mois=" . $mois);

        } else {
            ajouterErreur('Aucune fiche de frais sélectionnée');
        }
        break;

    // Indique que la fiche a été remboursée
    case 'ficheRemboursee' :
        ajouterMessage('Fiche de frais remboursée');
        break;

    // L'utilisateur n'est pas habilité à afficher la ressource
    case 'accesInterdit':
        ajouterErreur('Action non autorisée');
        //include 'vues/messages/v_erreurs.php';
        break;
    }


    /* --------- VARIABLES POUR AFFICHAGE DE LA FICHE --------------------*/
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
    $infosFiche = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
    $tauxKM = $pdo->getTauxKM($idVisiteur);
    $montantValide = $pdo->getMontantValide($idVisiteur, $mois);
    $nbJustificatifs = $pdo->getNbJustificatifs($idVisiteur, $mois);
    $infosVisiteur = $pdo->getLesInfosVisiteur($idVisiteur);
    $identiteVisiteur = strtoupper($infosVisiteur['nom'])
        . ' ' . ucfirst($infosVisiteur['prenom']);
    $numAnnee = getNumAnnee($mois);
    $numMois = getNumMois($mois);
    $etat = $infosFiche['idEtat'];

    switch ($etat) {
    case 'CR' :
        $titrePage = 'Fiche de frais ' . getNomMois($mois) . ' ' . $numAnnee;
        $sousTitrePage = $infosFiche['libEtat']
            . ' depuis le ' . dateAnglaisVersFrancais($infosFiche['dateModif']);
        //$titre = 'Renseigner la fiche de frais';
        break;
    case 'CL' :
        $titrePage = $identiteVisiteur . ' ' . getNomMois($mois) . ' ' . $numAnnee;
        $sousTitrePage = $infosFiche['libEtat']
            . ' depuis le ' . dateAnglaisVersFrancais($infosFiche['dateModif']);
        //$titre = 'Valider la fiche de frais';
        break;
    case 'VA' :
        $titrePage = $identiteVisiteur . ' ' . getNomMois($mois) . ' ' . $numAnnee;
        $sousTitrePage = $infosFiche['libEtat']
            . ' depuis le ' . dateAnglaisVersFrancais($infosFiche['dateModif']);
        break;
    default :
        $titrePage = 'Fiche de frais';
        break;
    }
}
/* -------------- AFFICHAGE DE LA FICHE --------------------------*/
if (estComptable()) {
    include 'vues/v_ficheFraisComptable.php';
} else {
    include 'vues/v_ficheFraisVisiteur.php';
}
