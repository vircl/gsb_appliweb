<?php
/**
 * Fonctions pour l'application GSB
 *
 * Fonctions métier utilisées dans l'application GSB
 * et l'api REST
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage GSB_Fonctions
 * @author     Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author     José GIL <jgil@ac-nice.fr>
 * @author     Virignie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2017 Réseau CERTA
 * @copyright  2020 Virginie CLAUDE
 * @license    Réseau CERTA | Virginie CLAUDE
 * @version    GIT: <1>
 * @link       http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require_once 'config.inc.php';

/*--- AUTHENTIFICATION, AUTORISATIONS --- */
/**
 * Gestion authentification
 * Enregistre dans une variable session les infos d'un visiteur
 *
 * @param String $idUtilisateur ID de l'utilisateur
 * @param String $nom           Nom de l'utilisateur
 * @param String $prenom        Prénom de l'utilisateur
 * @param int    $idProfil      Identifiant du profil utilisateur
 *
 * @subpackage auth
 *
 * @return String token
 */
function connecter($idUtilisateur, $nom, $prenom, $idProfil)
{
    $_SESSION['idUtilisateur'] = $idUtilisateur;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['idProfil'] = $idProfil;
    return genererToken($idUtilisateur);
}

/**
 * Gestion authentification
 * Détruit la session active
 *
 * @subpackage auth
 *
 * @return null
 */
function deconnecter()
{
    //session_destroy();
    unset($_SESSION['idUtilisateur']);
    unset($_SESSION['nom']);
    unset($_SESSION['prenom']);
    unset($_SESSION['idProfil']);
    unset($_SESSION['token']);
}

/**
 * Teste si un quelconque visiteur est connecté
 *
 * @return boolean vrai si un utilisateur est connecté
 *
 * @subpackage auth
 */
function estConnecte()
{
    return isset($_SESSION['idUtilisateur']);
}

/**
 * Teste si l'utilisateur connecté a le profil comptable
 *
 * @subpackage auth
 *
 * @return bool True si l'utilisateur est comptable
 */
function estComptable()
{
    return estConnecte() && $_SESSION['idProfil'] === '2';
}
/**
 * Retourne true si l'identifiant passé en paramètres correspond
 * à celui de l'utilisateur connecté
 *
 * @param string $idVisiteur Identifiant à tester
 *
 * @subpackage auth
 *
 * @return bool Vrai si les identifiants correspondent
 */
function estProprietaire($idVisiteur)
{
    return isset($_SESSION['idUtilisateur'])
        && $_SESSION['idUtilisateur'] === $idVisiteur;
}

/**
 * Teste si l'utilisateur connecté à le profil visiteur
 *
 * @subpackage auth
 *
 * @return bool True si l'utilisateur connecté est visiteur
 */
function estVisiteur()
{
    return estConnecte() && $_SESSION['idProfil'] === '1';
}

/**
 * Retourne le nom de l'utilisateur connecté
 *
 * @subpackage auth
 *
 * @return string
 */
function getNomUtilisateur()
{
    if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
        return strtoupper($_SESSION['nom'])
            . ' '
            . ucfirst($_SESSION['prenom']);
    }
}
/**
 * Génère un token pour authentification utilisateur
 *
 * @param String $id Identifiant de l'utilisateur
 *
 * @subpackage auth
 *
 * @return String Token au format jwt
 */
function genererToken($id)
{
    $now      = time();
    $header   = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    $payload  = json_encode(
        [
            'user_id' => $id,
            'iat' => $now,
            'exp' => $now + 3600
        ]
    );
    $cle      = API_KEY;

    $base64Header = str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($header)
    );
    $base64PayLoad = str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($payload)
    );
    $signature = hash_hmac(
        'sha256',
        $base64Header . "."
        . $base64PayLoad,
        $cle,
        true
    );
    $base64Signature = str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($signature)
    );

    $token = $base64Header . "." . $base64PayLoad . "." . $base64Signature;

    return $token;
}

/**
 * Redirection si l'utilisateur n'est pas un comptable authentifié
 *
 * @param string $uc Nom du controleur à appeler
 *
 * @subpackage auth
 *
 * @return void
 */
function redirectionSiNonComptable($uc=null)
{
    if (!estComptable()) {
        header("Location: index.php?uc=interdit");
    }
}

/**
 * Vérifie que l'utilisateur est autorisé à afficher la ressource
 *
 * @param string $idVisiteur Identifiant du visiteur autorisé
 *                           à afficher la ressource
 *
 * @subpackage auth
 *
 * @return bool true si l'utilisateur est autorisé à accéder à la ressource
 */
function verifierAcces($idVisiteur)
{

    if (!estComptable() && !estProprietaire($idVisiteur)) {
        ajouterErreur('Accès interdit');
        return false;
    } else {
        return true;
    }
}

/**
 * Vérifie la validité du token
 * //TODO : retourne un nouveau token
 *
 * @param String $jwt Token jwt
 *
 * @subpackage auth
 *
 * @return bool
 */
function verifierToken($jwt, $idVisiteur)
{
    $timestamp = time();

    $token = explode('.', $jwt);
    if (count($token) !== 3) {
        return false;
    }

    list($base64Header, $base64PayLoad, $base64Signature) = $token;

    // Regénère une avec le header et le payload du jwt
    $jwtSignature = hash_hmac(
        'sha256',
        $base64Header . "."
        . $base64PayLoad,
        API_KEY,
        true
    );
    $base64JwtSignature = str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($jwtSignature)
    );

    $payload = json_decode(base64_decode($base64PayLoad), true);

    return ($base64JwtSignature === $base64Signature) && $payload['exp'] > $timestamp && $payload['user_id'] === $idVisiteur;

}




/* ---- FONCTIONS EVALUATION FICHE DE FRAIS ---  */

/**
 * Retourne true si la fiche est modifiable
 * Visiteur connecté et fiche à l'état Création
 * ou Comptable connecté et fiche à l'état Clôturée
 *
 * @param string $statutFiche Id Etat fiche frais
 *
 * @subpackage ficheFrais
 *
 * @return bool
 */
function estFicheModifiable($statutFiche)
{
    return $statutFiche && ($statutFiche === 'CR' || $statutFiche === 'CL');
}

/**
 * Retourne TRUE si le libellé du frais commence par REFUSE
 *
 * @param string $libelle Le libellé du frais à tester
 *
 * @subpackage ficheFrais
 *
 * @return bool
 */
function estFraisRefuse($libelle)
{
    return substr(strtoupper(str_replace('é', 'e', $libelle)), 0, 6) == 'REFUSE';
}

/**
 * Définit le lien affecté au bouton retour
 * En fonction du profil connecté
 * Et de l'état de la fiche de frais affichée
 *
 * @param string $idVisiteur Identifiant du visiteur
 * @param string $etat       Etat de la fiche de frais
 *                           CR | CL | VA | RB
 *
 * @subpackage ficheFrais
 *
 * @return string lien retour
 */
function getLienRetour($idVisiteur='', $etat='')
{
    $retour = "index.php?idVisiteur=" . $idVisiteur;
    if ($etat) {
        if (estComptable()) {
            switch ($etat) {
            case 'CL' :
                $retour .= "&uc=listeFrais&action=fichesAValider";
                break;
            case 'VA' :
                $retour .= "&uc=listeFrais&action=fichesARembourser";
                break;
            default:
                break;
            }
        } elseif (estVisiteur()) {
            switch($etat) {
            case 'CR' :
                break;
            default :
                $retour .= "&uc=listeFrais";
                break;
            }
        }
    }
    return $retour;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * @param array $lesFrais Tableau d'entier
 *
 * @subpackage ficheFrais
 *
 * @return Boolean vrai ou faux
 */
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 * et le montant
 * des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * @param String $dateFrais Date des frais
 * @param String $libelle   Libellé des frais
 * @param Float  $montant   Montant des frais
 *
 * @subpackage ficheFrais
 *
 * @return void
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                    "date d'enregistrement du frais dépassé, plus de 1 an"
                );
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    } elseif (!is_string($libelle)) {
        ajouterErreur('Le champ description doit contenir uniquement du texte');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    } elseif ($montant <= 0) {
        ajouterErreur('La valeur du montant doit être strictement positive');
    }
}



/* --- FONCTIONS DATES --- */

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 *
 * @param String $maDate au format  aaaa-mm-jj
 *
 * @subpackage dates
 *
 * @return String date au format format français jj/mm/aaaa
 *         False si la date en entrée est non valide
 */
function dateAnglaisVersFrancais($maDate)
{
    $date = false;
    if ($maDate) {
        $tab = explode('-', $maDate);
        if (count($tab) == 3 && estTableauEntiers($tab)) {
            @list($annee, $mois, $jour) = $tab;
            if (checkdate($mois, $jour, $annee)) {
                $date = sprintf('%02d', $jour)
                    . '/'
                    .  sprintf('%02d', $mois)
                    . '/' . $annee;
            }
        }
    }
    return $date;
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 *
 * @param String $maDate au format  jj/mm/aaaa
 *
 * @subpackage dates
 *
 * @return String date au format anglais aaaa-mm-jj
 *         False si la date en entrée est non valide
 */
function dateFrancaisVersAnglais($maDate)
{
    $date = false;

    if (estDateValide($maDate)) {
        @list($jour, $mois, $annee) = explode('/', $maDate);;
        if (checkdate($mois, $jour, $annee)) {
            $date = date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
        }
    }

    return $date;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 * TODO : année sur deux chiffres à traiter (valider ou rejet)
 *
 * @param String $dateTestee Date à tester
 *
 * @subpackage dates
 *
 * @return Boolean vrai ou faux
 */
function estDateDepassee($dateTestee)
{
    $retour = true;

    $dateActuelle = date('d/m/Y');

    if (estDateValide($dateTestee)) {
        @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
        if (checkdate($moisTeste, $jourTeste, $anneeTeste)) {
            @list($jour, $mois, $annee) = explode('/', $dateActuelle);
            $annee--;
            $anPasse = $annee . $mois . $jour;

            $retour = $anneeTeste. $moisTeste . $jourTeste < $anPasse;
        }

    }

    return $retour;

}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * @param String $date Date à tester
 *
 * @subpackage dates
 *
 * @return Boolean vrai ou faux
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = false;
    if (count($tabDate) === 3 && estTableauEntiers($tabDate)) {
        @list($jour, $mois, $annee) = $tabDate;
        $dateOK = checkdate($mois, $jour, $annee);
    }
    return $dateOK;
}
/**
 * Teste si le mois donné en paramètres
 * est bien sous la forme aaaamm
 *
 * @param String $mois chaîne à tester
 *
 * @subpackage dates
 *
 * @return bool
 */
function estMoisValide($mois)
{
    $retour = false;

    if (strlen($mois) === 6) {
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);
        $retour = (
            is_numeric($numMois)
            && is_numeric($numAnnee)
            && ($numMois >= 1 && $numMois <= 12)
        );
    }
    return $retour;
}
/**
 * Retourne le format de la date
 * donnée en paramètres
 *
 * @param String $date La date à tester
 *
 * @subpackage dates
 *
 * @return String
 *
 * @link https://www.php.net/manual/fr/datetime.createfromformat.php
 * formatage des dates en php
 */
function getDateFormat($date)
{
    $retour = '';
    if (estDateValide($date)) {
        @list($jour, $mois, $annee) = explode('/', $date);
        $y = 'Y';
        $m = 'm';
        $d = 'd';

        if (strlen($annee) == 2) {
            $y = 'y';
        }
        if (strlen($mois) == 1) {
            $m = 'n';
        }
        if (strlen($jour) == 1) {
            $d = 'j';
        }
        $retour = "$d/$m/$y";

    }
    return $retour;
}
/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param String $date au format  jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 *
 * @subpackage dates
 */
function getMois($date=null)
{
    if (!$date) {
        $date = date('d/m/Y');
    }
    $retour = false;
    if (estDateValide($date)) {
        $retour = DateTime::createFromFormat(
            getDateFormat($date),
            $date
        )->format('Ym');
    }
    return $retour;
}

/**
 * Fonction qui retourne le mois précédent un mois passé en paramètre
 *
 * @param String $mois Contient le mois à utiliser
 *
 * @subpackage dates
 *
 * @return String le mois d'avant
 */
function getMoisPrecedent($mois)
{
    if (estMoisValide($mois)) {
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);
        if ($numMois == '01') {
            $numMois = '12';
            $numAnnee--;
        } else {
            $numMois--;
        }
        if (strlen($numMois) == 1) {
            $numMois = '0' . $numMois;
        }
        return $numAnnee . $numMois;
    } else {
        return '';
    }

}


/**
 * Fonction qui retourne le mois suivant un mois passé en paramètre
 *
 * @param String $mois Contient le mois à utiliser
 *
 * @subpackage dates
 *
 * @return String le mois d'après
 */
function getMoisSuivant($mois)
{
    if (estMoisValide($mois)) {
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);
        if ($numMois == '12') {
            $numMois = '01';
            $numAnnee++;
        } else {
            $numMois++;
        }
        if (strlen($numMois) == 1) {
            $numMois = '0' . $numMois;
        }
        return $numAnnee . $numMois;
    } else {
        return '';
    }

}

/**
 * Retourne le numéro de l'année en fonction du mois
 * passé en paramètres
 *
 * @param string $mois identifiant du mois sous la forme aaaamm
 *
 * @subpackage dates
 *
 * @return bool|string
 */
function getNumAnnee($mois)
{
    if (estMoisValide($mois)) {
        return substr($mois, 0, 4);
    } else {
        return '';
    }

}

/**
 * Retourne le nom du mois d'après l'identifiant mois
 * passé en paramètres
 *
 * @param string $mois Identifiant du mois sous la forme aaaamm
 *
 * @subpackage dates
 *
 * @return string
 */
function getNomMois($mois)
{
    if (estMoisValide($mois)) {
        $numMois = (int)getNumMois($mois);
        $lesMois = array(
            "janvier",
            "février",
            "mars",
            "avril",
            "mai",
            "juin",
            "juillet",
            "août",
            "septembre",
            "octobre",
            "novembre",
            "décembre"
        );
        return $lesMois[$numMois-1];
    } else {
        return '';
    }

}

/**
 * Retourne le numéro du mois en fonction de l'identifiant mois
 * passé en paramètres
 *
 * @param string $mois identifiant du mois sous la forme aaaamm
 *
 * @subpackage dates
 *
 * @return bool|string
 */
function getNumMois($mois)
{
    if (estMoisValide($mois)) {
        return substr($mois, 4, 2);
    } else {
        return '';
    }

}




/* --- FONCTIONS CONTROLE DONNEES --- */

/**
 * Indique si une valeur est un entier positif ou nul
 *
 * @param Integer $valeur Valeur
 *
 * @subpackage controle_donnees
 *
 * @return Boolean vrai ou faux
 */
function estEntierPositif($valeur)
{
    return preg_match('/[^0-9]/', $valeur) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * @param Array $tabEntiers Un tableau d'entier
 *
 * @subpackage controle_donnees
 *
 * @return Boolean vrai ou faux
 */
function estTableauEntiers($tabEntiers)
{
    $boolReturn = true;
    if (is_array($tabEntiers)) {
        foreach ($tabEntiers as $unEntier) {
            if (!estEntierPositif($unEntier)) {
                $boolReturn = false;
            }
        }
    } else {
        $boolReturn = false;
    }

    return $boolReturn;
}




/* --- FONCTIONS GESTION DES ERREURS --- */

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * @param String $msg Libellé de l'erreur
 *
 * @subpackage erreurs
 *
 * @return null
 */
function ajouterErreur($msg)
{
    if (trim($msg) != '') {
        if (!isset($_REQUEST['erreurs'])) {
            $_REQUEST['erreurs'] = array();
        }
        $_REQUEST['erreurs'][] = $msg;
    }
}

/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * @subpackage erreurs
 *
 * @return Integer le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
}



/* --- FONCTIONS GESTION DES MESSAGES ---*/

/**
 * Ajoute un message au tableau des messages
 *
 * @param string $msg le message à ajouter
 *
 * @subpackage messages
 *
 * @return void
 */
function ajouterMessage($msg)
{
    if (trim($msg) != '') {
        if (!isset($_REQUEST['messages'])) {
            $_REQUEST['messages'] = array();
        }
        $_REQUEST['messages'][] = $msg;
    }
}

/**
 * Retourne le nombre de lignes du tableau des messages
 *
 * @subpackage messages
 *
 * @return int le nombre de messages
 */
function nbMessages()
{
    if (!isset($_REQUEST['messages'])) {
        return 0;
    } else {
        return count($_REQUEST['messages']);
    }
}