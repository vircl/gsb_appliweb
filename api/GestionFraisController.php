<?php
/**
 * Fichier de définition de la classe GestionFraisController
 *
 * Prérequis :
 * Framework RestServer Installé
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright 2020 Virginie CLAUDE
 * @license   Virginie CLAUDE
 * @version   GIT:<1>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

use Jacwright\RestServer\RestException;
require_once '../includes/fct.inc.php';
require_once '../includes/class.pdogsb.inc.php';

/**
 * Classe GestionFraisController
 *
 * Cette classe permet l'analyse et le traitement de requêtes HTTP
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright 2020 Virginie CLAUDE
 * @license   Virginie CLAUDE
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
class GestionFraisController
{

    /**
     * Objet PDO
     *
     * @var PdoGsb
     */
    private $_pdo;

    /**
     * Constructeur de la classe GestionFraisController
     * Récupère l'instance de la classe PDOGSB
     */
    public function __construct()
    {
        $this->_pdo = PdoGsb::getPdoGsb();
    }


    /**
     * Teste si l'utilisateur est autorisé à accéder à la ressource
     *
     * @param String $token      Token jwt
     * @param String $idVisiteur Identifiant du visiteur dont la fiche est appelée
     *
     * @return bool True : utilisateur autorisé
     *              False : utilisateur non autorisé
     * @throws RestException
     */
    private function _authorize($token, $idVisiteur)
    {
        if (!verifierToken($token, $idVisiteur)) {
            throw new RestException(
                403,
                'Accès interdit'
            );
        }
        return true;
    }


    /**
     * Login
     *
     * Demande d'authentificaiton d'un utilisateur
     * par l'envoi d'un couple login/mot de passe en méthode POST
     * L'API connecte l'utilisateur et retourne son login, son id ainsi
     * qu'un token jwt permettant de maintenir la connexion active.
     *
     * @url POST /login
     *
     * @return array Infos de l'utilisateur si la connexion a réussi
     *
     * @throws RestException Retourne une erreur 405 si l'authentification échoue
     */
    public function login()
    {
        $erreurs = '';

        // Lecture du login et du pwd
        $data        = urldecode($_POST['data']);
        $json_decode = json_decode($data, true);

        // Teste si le json est valide
        if (!key_exists('login', $json_decode)) {
            $erreurs .= "- Clé login absente ";
        }
        if (!key_exists('mdp', $json_decode)) {
            $erreurs .= "- Clé mdp absente ";
        }
        // Retourne une exception si les champs sont manquants
        if ($erreurs !== '') {
            throw new RestException(
                405,
                'Paramètres de connexion non ou mal définis : ' . $erreurs
            );
        }

        // Récupère le login et le mdp envoyés en $_POST
        $login = $json_decode['login'];
        $mdp   = $json_decode['mdp'];

        // Tente de connecter l'utilisateur
        $utilisateur = $this->_pdo->getInfosUtilisateur($login, $mdp);

        // Si on a bien récupéré un array, authentification ok
        // sinon on retourne une exception
        if (!is_array($utilisateur)) {
            throw new RestException(
                401,
                'Login ou mdp incorrect'
            );
        }

        // Si authentification ok on connecte l'utilisateur
        $id       = $utilisateur['id'];
        $nom      = $utilisateur['nom'];
        $prenom   = $utilisateur['prenom'];
        $idProfil = $utilisateur['idProfil'];
        $login    = $utilisateur['login'];
        $token    = connecter($id, $nom, $prenom, $idProfil);
        return array(
            "token" => $token,
            "id"    => $id,
            "login" => $login
        );
    }

    /**
     * Retourne sous la forme d'un tableau
     * tous les frais du visiteur dont l'id est passé en paramètres
     *
     * @param string $idVisiteur identifiant du visiteur
     *
     * @url GET /$idVisiteur
     *
     * @return array Liste des éléments forfaitisés
     *               et hors forfait appartenant à ce visiteur
     * @throws RestException
     */
    public function getInfosVisiteur($idVisiteur)
    {

        $this->_authorize($_GET['token'], $idVisiteur);
        $return = array();
        $return['fraisForfait'] = $this->_pdo->getLesFraisForfait($idVisiteur);
        $return['fraisHf']      = $this->_pdo->getLesFraisHorsForfait($idVisiteur);
        return $return;
    }

    /**
     * Lecture des frais d'un visiteur pour une année et un mois donnés en paramètres
     *
     * @param string $idVisiteur Identifiant du visiteur
     * @param string $annee      Annee sur 4 chiffres
     * @param string $mois       Mois sur 2 chiffres
     *
     * @url GET /$idVisiteur/$annee/$mois
     *
     * @return array la liste des éléments forfaitisés et des frais hors forfait
     * @throws RestException
     */
    public function getInfosMoisVisiteur($idVisiteur, $annee, $mois)
    {
        $this->_authorize($_GET['token'], $idVisiteur);
        $leMois = $annee . $mois;
        $return['fraisForfait'] = $this->_pdo->getLesFraisForfait(
            $idVisiteur,
            $leMois
        );
        $return['fraisHf'] = $this->_pdo->getLesFraisHorsForfait(
            $idVisiteur,
            $leMois
        );
        return $return;
    }


    /**
     * Synchronisation des fiches de frais
     * Les données reçues écrasent elles de la base de données
     * MySQL si elles sont plus récentes
     *
     * @param string $idVisiteur Identifiant du visiteur
     *
     * @url POST /$idVisiteur
     *
     * @return array liste de tous les éléments forfaitisés et hors forfait
     *               concernant ce visiteur
     *               après mise à jour de la base de données
     * @throws RestException
     */
    public function majInfosVisiteur($idVisiteur)
    {

        $this->_authorize($_GET['token'], $idVisiteur);
        date_default_timezone_set('UTC');
        $return = array();
        $return['erreurs']      = array();
        $return['infos']        = array();
        $return['fraisForfait'] = array();
        $return['fraisHf']      = array();

        // Lecture de la variable $_POST
        $data        = urldecode($_POST['data']);
        $json_decode = json_decode($data, true);

        if (!$json_decode) {
            $return["erreurs"][] = "JSON invalide";
            return $return["erreurs"];
        }

        $fraisSuppr   = null;
        $fraisForfait = null;
        $fraisHf      = null;
        $dateSynchro  = null;

        // Date de synchronisation
        if (key_exists('dateSynchro', $json_decode)) {
            $dateSynchro = $json_decode['dateSynchro'];
            $return["infos"][] = 'date synchronisation = ' . $dateSynchro;
        } else {
            $return["infos"][] = 'Date de synchronisation non renseignée';
        }


        // Elements à supprimer
        if (key_exists('delete', $json_decode)) {
            $fraisSuppr = $json_decode['delete'];
            $return["infos"][] = count($fraisSuppr) . ' éléments à supprimer';
        } else {
            $return["infos"][] = 'Clé delete non renseignée';
        }
        if ($fraisSuppr) {
            foreach ($fraisSuppr as $frais) {
                if (key_exists('id', $frais)
                    && key_exists('datemodif', $frais)
                ) {
                    $id = $frais['id'];
                    $statut = $this->_pdo->getStatutFicheFraisHF($id);

                    // Si la fiche de frais est à l'état CR ou CL
                    if (estFicheModifiable($statut)) {
                        $return["infos"][] = 'Suppression frais HF n° ' . $id;
                        $this->_pdo->supprimerFraisHorsForfaitAndroid(
                            $id,
                            $frais['datemodif']
                        );
                    } else {
                        // Sinon on réinitialise l'id Android
                        // pour le réintégrer sur l'appli
                        $this->_pdo->resetIdAndroid($idVisiteur, $id);
                        $return["infos"][] = 'Frais HF n° '
                            . $id
                            . ' non supprimé, la fiche est clôturée';
                    }

                } else {
                    $return["erreurs"][] = 'Erreur structure json (delete)';
                }
            }
        }

        // Elements forfaitisés
        if (key_exists('fraisForfait', $json_decode)) {
            $fraisForfait = $json_decode['fraisForfait'];
            $return["infos"][] = count($fraisForfait) . ' frais forfait';
        } else {
            $return["infos"][] = 'Clé fraisFrofait non renseignée';
        }
        if ($fraisForfait) {
            foreach ($fraisForfait as $frais) {

                if (key_exists('mois', $frais)) {
                    $leMois = $frais['mois'];
                    // Crée la fiche de frais concernée si elle n'existe pas
                    // Crée la fiche de frais concernée si elle n'existe pas
                    $this->_creerFicheFrais($idVisiteur, $leMois, $dateSynchro);
                    $statut = $this->_pdo->getStatutFiche(
                        $idVisiteur,
                        $leMois
                    );

                    if (estFicheModifiable($statut)) {
                        $this->_pdo->majFraisForfaitAndroid($idVisiteur, $frais);
                    }

                } else {
                    $return["erreurs"][] = 'Clé mois manquante';
                }

            }
        }
        // Liste des frais forfaitisés enregistrés depuis la date de dernière synchro
        $return['fraisForfait'] = $this->_pdo->getLesFraisForfait(
            $idVisiteur,
            null,
            $dateSynchro
        );

        // Elements hors forfait
        if (key_exists('fraisHf', $json_decode)) {
            $fraisHf = $json_decode['fraisHf'];
            $return["infos"][] = count($fraisHf) . ' frais HF';
        } else {
            $return["infos"][] = 'Clé fraisHf non renseignée';
        }
        if ($fraisHf) {
            foreach ($fraisHf as $frais) {

                $idAndroid = $frais['id'];

                if (key_exists('mois', $frais)) {
                    $leMois = $frais['mois'];
                    // Crée la fiche de frais concernée si elle n'existe pas
                    $this->_creerFicheFrais($idVisiteur, $leMois, $dateSynchro);
                    $statut = $this->_pdo->getStatutFiche($idVisiteur, $leMois);

                    // Si la fiche de frais est à l'état CR ou CL
                    if (estFicheModifiable($statut)) {
                        if ($this->_pdo->fraisHorsForfaitAndroidExiste($idVisiteur, $idAndroid)) {

                            // Si l'id android est présent dans la bdd
                            // mise à jour
                            $this->_pdo->majFraisHorsForfaitAndroid(
                                $idVisiteur,
                                $frais
                            );
                        } else {

                            $annee   = substr($leMois, 0, 4);
                            $mois    = substr($leMois, 4, 2);
                            $date    = $frais['jour'] . '/' . $mois . '/' . $annee;
                            $libelle = $frais['libelle'];
                            $montant = $frais['montant'];
                            $idMySQL = $frais['idMySQL'];

                            // Si l'id mysql est connu c'est une mise à jour
                            if ($idMySQL) {
                                $this->_pdo->majFraisHorsForfait(
                                    $idMySQL,
                                    $libelle,
                                    $date,
                                    $montant,
                                    $idAndroid
                                );
                            } else {
                                // sinon le frais est inconnu on l'ajoute
                                $this->_pdo->creeNouveauFraisHorsForfait(
                                    $idVisiteur,
                                    $leMois,
                                    $libelle,
                                    $date,
                                    $montant,
                                    $idAndroid
                                );
                            }
                        }
                    }
                } else {
                    $return["erreurs"][] = 'Clé mois manquante pour maj frais Hf';
                }
            }
        }
        // Liste des frais HF enregistrés depuis la dernière synchro
        $return['fraisHf'] =  $this->_pdo->getLesFraisHorsForfait(
            $idVisiteur,
            null,
            $dateSynchro
        );

        return $return;
    }

    /**
     * Crée une fiche de frais si elle n'existe pas déjà.
     *
     * @param String $idVisiteur Identifiant du visiteur
     * @param String $mois       Mois de la fiche
     * @param String $dateCrea   Date et heure création de la fiche de frais
     *
     * @return void
     */
    private function _creerFicheFrais($idVisiteur, $mois, $dateCrea=null)
    {
        if ($this->_pdo->estPremierFraisMois($idVisiteur, $mois)) {
            $this->_pdo->creeNouvellesLignesFrais($idVisiteur, $mois, $dateCrea);
        }
    }

}
