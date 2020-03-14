<?php
/**
 * Classe d'accès aux données.
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB_Includes
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright 2017 Réseau CERTA
 * @copyright 2020 Virginie CLAUDE
 * @license   Réseau CERTA
 * @licence   Virginie CLAUDE
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * PHP Version 7
 *
 * @category   PPE
 * @package    GSB
 * @subpackage PDO
 * @author     Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author     José GIL <jgil@ac-nice.fr>
 * @author     Virginie CLAUDE <dev@virginie-claude.fr>
 * @copyright  2017 Réseau CERTA
 * @copyright  2020 Virginie CLAUDE
 * @license    Réseau CERTA | Virginie CLAUDE
 * @link       http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
class PdoGsb
{
    /**
     * Serveur
     *
     * @var string $serveur Adresse du serveur MySQL
     */
    //private static $_serveur = 'mysql:host=virginieegsb.mysql.db';
    private static $_serveur = 'mysql:host='.DB_HOST;

    /**
     * BDD
     *
     * @var string $bdd Nom de la base de données
     */
    private static $_bdd = 'dbname='.DB_BDD;

    /**
     * User
     *
     * @var string $user Nom utilisateur MySQL
     */
    private static $_user = DB_USER;

    /**
     * Mdp
     *
     * @var string $mdp Mot de passe Utilisateur MySQL
     */
    private static $_mdp = DB_PWD;

    /**
     * Objet PDO
     *
     * @var PDO $monPdo objet PDO
     */
    private static $_monPdo;

    /**
     * PdoGSB
     *
     * @var null $monPdoGsB Objet PDO utilisé pour toutes les méthodes de la classe
     */
    private static $_monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     *
     * @param String $bdd Nom de la base de données
     */
    private function __construct($bdd = null)
    {
        if (!$bdd) {
            $bdd = PdoGsb::$_bdd;
        } else {
            $bdd = 'dbname=' . $bdd;
        }
        PdoGsb::$_monPdo = new PDO(
            PdoGsb::$_serveur . ';' . $bdd,
            PdoGsb::$_user,
            PdoGsb::$_mdp
        );
        PdoGsb::$_monPdo->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct()
    {
        PdoGsb::$_monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @param String $bdd Nom de la base de données
     *
     * @return PDOGSB l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb($bdd = null)
    {
        if (PdoGsb::$_monPdoGsb == null) {
            PdoGsb::$_monPdoGsb = new PdoGsb($bdd);
        }
        return PdoGsb::$_monPdoGsb;
    }


    /* -- lecture / écriture BDD --*/

    /**
     * Script de clôture des fiches de frais ayant le statut CL
     * et un mois < au mois courant
     *
     * @return void
     */
    public function cloturerFichesFrais()
    {
        // Mois courant
        $mois = getMois();
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = "CL", datemodif = now() '
            . 'WHERE mois < :unMois '
            . 'AND idetat = "CR" '
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     * @param int    $idAndroid  Identifiant du frais sur l'application android
     *
     * @return void
     */
    public function creeNouveauFraisHorsForfait(
        $idVisiteur,
        $mois,
        $libelle,
        $date,
        $montant,
        $idAndroid = null
    ) {
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'INSERT INTO lignefraishorsforfait (idvisiteur, mois, libelle, date, montant, datemodif, idandroid)'
            . 'VALUES (:unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
            . ':unMontant, NOW(), :unIdAndroid) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdAndroid', $idAndroid, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $date       Date de synchronisation
     *
     * @return void
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois, $date=null)
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
            . 'montantvalide,datemodif,idetat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0, now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$_monPdo->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                . 'idfraisforfait,quantite, datemodif) '
                . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0, '
                . ($date ? ':uneDate' : 'now()')
                . ')'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(
                ':idFrais',
                $unIdFrais['idfrais'],
                PDO::PARAM_STR
            );
            if ($date) {
                $requetePrepare->bindParam(':uneDate', $date, PDO::PARAM_STR);
            }
            $requetePrepare->execute();
        }
    }


    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return string le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT MAX(mois) as dernierMois '
            . 'FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }


    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return boolean vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois)
    {
        $boolReturn = false;
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }


    /**
     * Retourne le mois et le visiteur concernés par le frais hors forfait
     * dont l'identifiant est donné en paramètres
     *
     * @param int $idFrais Identifiant du frais hors forfait
     *
     * @return array|bool tableau contenant l'id du visiteur et le mois
     */
    public function getFicheFraisParId($idFrais)
    {
        // Récupère l'id visiteur et le mois
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'SELECT '
            . 'idvisiteur AS idVisiteur, '
            . 'mois AS mois '
            . 'FROM lignefraishorsforfait '
            . 'WHERE id = :unIdFrais '
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Récupère les infos du frais hors forfait dont l'ID est passé en paramètres
     *
     * @param int $id Identifiant du frais
     *
     * @return array libelle, montant, date, mois
     */
    public function getInfosFraisHorsForfait($id)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT lignefraishorsforfait.libelle AS libelle, '
            . 'lignefraishorsforfait.montant AS montant, '
            . 'lignefraishorsforfait.date AS date, '
            . 'lignefraishorsforfait.mois AS mois '
            . 'FROM lignefraishorsforfait '
            . 'WHERE id= :unID '
        );
        $requetePrepare->bindParam(':unID', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }


    /**
     * Retourne les informations d'un utilisateur
     *
     * @param string $login Login de l'utilisateur
     * @param string $mdp   Mot de passe de l'utilisateur
     *
     * @return array id, nom, prenom
     */
    public function getInfosUtilisateur($login, $mdp)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT utilisateur.id AS id, '
            . 'utilisateur.mdp AS mdp, '
            . 'utilisateur.nom AS nom, '
            . 'utilisateur.prenom AS prenom, '
            . 'utilisateur.idprofil AS idProfil, '
            . 'utilisateur.login AS login, '
            . 'profil.libelle AS profil '
            . 'FROM utilisateur '
            . 'JOIN profil ON utilisateur.idprofil = profil.id '
            . 'WHERE utilisateur.login = :unLogin '
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        $user = $requetePrepare->fetch();

        // Réucpère le mdp dans une variable interne à la fonction
        $bddMdp = $user['mdp'];

        // Supprime le mdp du tableau des résultats
        unset($user['mdp']);
        unset($user[0]);

        // Si le mot de passe est correct, retourne le tableau de résultats,
        // sinon renvoie false
        return password_verify($mdp, $bddMdp) ? $user : false;
    }

    /**
     * Retourne la liste des comptables
     *
     * @return array id, nom, prénom des visiteurs
     */
    public function getLesComptables()
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT utilisateur.id AS id, '
            . 'utilisateur.nom AS nom, '
            . 'utilisateur.prenom AS prenom '
            . 'FROM utilisateur '
            . 'WHERE idprofil = 2 '
            . 'ORDER BY nom ASC '
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne les infos du visiteur dont l'ID est passé en paramètres
     *
     * @param string $idVisiteur Identifiant du visiteur
     *
     * @return array id, nom, prenom, typeVehicule, tauxKM
     */
    public function getLesInfosVisiteur($idVisiteur)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT '
            . 'utilisateur.nom AS nom, '
            . 'utilisateur.prenom AS prenom, '
            . 'vehicule.libelle AS typeVehicule '
            . 'FROM utilisateur '
            . 'JOIN vehicule ON vehicule.id = utilisateur.idvehicule '
            . 'WHERE utilisateur.id=:unIdVisiteur '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne un tableau contenant les informations
     * des fiches dont l'état est passé en paramètres
     *
     * @param string $etat  Etat des fiches à retourner
     * @param string $mois  pour lequel on souhaite retourner
     *                      les fiches de frais
     * @param string $signe Signe à utiliser pour comparer
     *                      le mois : > < = <>
     *                      Si non renseigné, le signe = est utilisé
     *                      par défaut
     *
     * @return array idvisiteur, mois, nbjustificatif,
     *               montant, etat, libetat, date, nom, prenom
     */
    public function getLesFichesParEtat($etat, $mois=null, $signe=null)
    {

        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT '
            . 'fichefrais.idvisiteur AS idvisiteur, '
            . 'fichefrais.mois AS mois, '
            . 'fichefrais.nbjustificatifs AS nbjustificatif, '
            . 'fichefrais.montantvalide AS montant, '
            . 'fichefrais.idetat AS etat, '
            . 'etat.libelle AS libetat, '
            . 'fichefrais.datemodif AS date, '
            . 'utilisateur.nom AS nom, '
            . 'utilisateur.prenom AS prenom '
            . 'FROM fichefrais '
            . 'JOIN utilisateur ON fichefrais.idvisiteur = utilisateur.id '
            . 'JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE idetat= :unEtat '
            . ($mois ? 'AND mois' . ($signe ? $signe : '=') .':unMois ' : '')
            . ' ORDER BY utilisateur.nom '
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        if ($mois) {
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        }
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne un tableau contenant les informations des fiches de frais
     * Appartenant au visiteur dont l'ID est passé en paramètres
     *
     * @param string $idVisiteur Identifiant du visiteur
     *                           Dont on veut afficher les fiches de frais
     *
     * @return array Tableau contenant toutes les fiches de ce visiteur
     */
    public function getLesFichesParVisiteur($idVisiteur)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT '
            . 'fichefrais.idvisiteur AS idvisiteur, '
            . 'fichefrais.mois AS mois, '
            . 'fichefrais.nbjustificatifs AS nbjustificatif, '
            . 'fichefrais.montantvalide AS montant, '
            . 'fichefrais.idetat AS etat, '
            . 'etat.libelle AS libetat, '
            . 'fichefrais.datemodif AS date, '
            . 'utilisateur.nom AS nom, '
            . 'utilisateur.prenom AS prenom '
            . 'FROM fichefrais '
            . 'JOIN utilisateur ON fichefrais.idvisiteur = utilisateur.id '
            . 'JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE idvisiteur= :unIdVisiteur '
            . 'ORDER BY mois DESC '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur  ID du visiteur
     * @param String $mois        Mois sous la forme aaaamm
     * @param String $dateSynchro Date de synchronisation
     *
     * @return array idfrais, libelle, montant, quantite, total
     * associatif
     */
    public function getLesFraisForfait(
        $idVisiteur,
        $mois = null,
        $dateSynchro = null
    ) {
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'SELECT lignefraisforfait.mois as mois, '
            . 'fraisforfait.id as idfrais, '
            . 'fraisforfait.libelle as libelle, '
            . 'fraisforfait.montant as montant, '
            . 'lignefraisforfait.quantite as quantite, '
            . '(montant * quantite) AS total, '
            . 'lignefraisforfait.datemodif as datemodif '
            . 'FROM lignefraisforfait '
            . 'INNER JOIN fraisforfait '
            . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . ($mois ? 'AND lignefraisforfait.mois = :unMois ' : '')
            . ($dateSynchro
                ? 'AND lignefraisforfait.datemodif > :uneDateSynchro '
                : '')
            . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        if ($mois) {
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        }
        if ($dateSynchro) {
            $requetePrepare->bindParam(
                ':uneDateSynchro',
                $dateSynchro,
                PDO::PARAM_STR
            );
        }
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }


    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur  ID du visiteur
     * @param String $mois        Mois sous la forme aaaamm
     *                            Optionnel, si non renseigné,
     *                            retourne tous les frais hors forfait
     * @param String $dateSynchro Date de synchronisation
     *
     * @return array tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfait(
        $idVisiteur,
        $mois = null,
        $dateSynchro = null
    ) {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT * , DAY(date) AS jour FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND actif = "1" ' // AND actif ne fonctionne pas ???
            . ($mois ? 'AND lignefraishorsforfait.mois = :unMois' : '')
            . ($dateSynchro
                ? 'AND lignefraishorsforfait.datemodif > :uneDateSynchro '
                : '')
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        if ($mois) {
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        }
        if ($dateSynchro) {
            $requetePrepare->bindParam(
                ':uneDateSynchro',
                $dateSynchro,
                PDO::PARAM_STR
            );
        }
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        for ($i = 0; $i < count($lesLignes); $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return array un tableau associatif avec les ID des fiches de frais
     */
    public function getLesIdFrais()
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT fraisforfait.id as idfrais '
            . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return array un tableau avec des champs de jointure entre une fiche de frais
     *               et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'SELECT fichefrais.idetat as idEtat, '
            . 'fichefrais.datemodif as dateModif,'
            . 'fichefrais.nbjustificatifs as nbJustificatifs, '
            . 'fichefrais.montantvalide as montantValide, '
            . 'etat.libelle as libEtat '
            . 'FROM fichefrais '
            . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Retourne les mois pour lesquels des fiches de frais existent
     *
     * @return array liste des mois
     */
    public function getLesMois()
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT DISTINCT fichefrais.mois AS mois FROM fichefrais '
            . 'ORDER BY fichefrais.mois DESC LIMIT 12 '
        );
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne les mois pour lesquels un visiteur a une fiche de frais
     *
     * @param String $idVisiteur Identifiant du visiteur
     *
     * @return array tableau associatif de clés un mois -aaaamm- et de valeurs
     *               l'année et le mois correspondants
     */
    public function getLesMoisDisponibles($idVisiteur)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT fichefrais.mois AS mois '
            . 'FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'ORDER BY fichefrais.mois DESC'
        );

        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois      = $laLigne['mois'];
            $numAnnee  = substr($mois, 0, 4);
            $numMois   = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois'     => $mois,
                'numMois'  => $numMois,
                'numAnnee' => $numAnnee,
            );
        }
        return $lesMois;
    }

    /**
     * Retourne la liste des utilisateurs
     *
     * @return array id, nom, prenom des utilisateurs
     */
    public function getLesUtilisateurs()
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT utilisateur.id AS id, '
            . 'utilisateur.nom AS nom, '
            . 'utilisateur.prenom AS prenom '
            . 'FROM utilisateur '
            . 'ORDER BY nom ASC '
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne la liste des visiteurs
     *
     * @return array id, nom, prénom des visiteurs
     */
    public function getLesVisiteurs()
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT utilisateur.id AS id, '
            . 'utilisateur.nom AS nom, '
            . 'utilisateur.prenom AS prenom '
            . 'FROM utilisateur '
            . 'WHERE idprofil = 1 '
            . 'ORDER BY nom ASC '
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne le montnat total des éléments forfaitisés
     * Pour un visiteur et un mois passés en paramètres
     *
     * @param string $idVisiteur Identifiant du visiteur
     * @param string $mois       Mois
     *
     * @return float Somme des éléments forfaitisés
     */
    public function getMontantForfait($idVisiteur, $mois)
    {
        // Somme des frais (KM exclus)
        $requetePrepare = PDOGSB::$_monPdo->prepare(
            'SELECT SUM(montantfrais) as montant '
            . 'FROM '
            . '(SELECT '
            . '(lignefraisforfait.quantite * fraisforfait.montant) AS montantfrais '
            . 'FROM lignefraisforfait '
            . 'JOIN fraisforfait '
            . 'ON lignefraisforfait.idfraisforfait = fraisforfait.id '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois=:unMois '
            . 'AND fraisforfait.id NOT IN ("KM") '
            . 'GROUP BY fraisforfait.id) AS req '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $montant = $laLigne['montant'];

        // Calcul du montant KM
        $tauxKM = $this->getTauxKM($idVisiteur);
        $requetePrepare = PDOGSB::$_monPdo->prepare(
            'SELECT lignefraisforfait.quantite AS quantite '
            . 'FROM lignefraisforfait '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois=:unMois '
            . 'AND lignefraisforfait.idfraisforfait="KM" '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $montant += ($laLigne['quantite'] * $tauxKM);
        return $montant;
    }


    /**
     * Calcule le montant hors forfait d'une fiche de frais
     *
     * @param string $idVisiteur Identifiant du visiteur
     * @param string $mois       Mois concerné
     *
     * @return float Montant des éléments hors forfait
     */
    public function getMontantHF($idVisiteur, $mois)
    {
        $requetePrepare = PDOGSB::$_monPdo->prepare(
            'SELECT SUM(montant) as montant '
            . 'FROM lignefraishorsforfait WHERE idvisiteur= :unIdVisiteur '
            . 'AND mois= :unMois '
            . 'AND left(libelle,6) NOT LIKE "REFUSE" '
            . 'AND actif = "1" '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['montant'];
    }


    /**
     * Retourne le montant valide d'une fiche de frais
     * Pour un visiteur et un mois passés en paramètres
     *
     * @param string $idVisiteur Identifiant du visiteur
     * @param string $mois       Mois
     *
     * @return float Somme des éléments forfaitisés et hors forfait (non refusés)
     */
    public function getMontantValide($idVisiteur, $mois)
    {
        return $this->getMontantForfait($idVisiteur, $mois)
            + $this->getMontantHF($idVisiteur, $mois);
    }

    /**
     * Retourne le nombre de justificatifs d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return integer le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne un tableau contenant les informations
     * de la table fraisforfait
     *
     * @return array (id, libelle, montant)
     */
    public function getTarifsForfait()
    {
        $requetePrepare = PDOGsb::$_monPdo->prepare(
            'SELECT id AS id, libelle AS libelle, montant AS montant FROM fraisforfait'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne un tableau contenant les taux de remboursement
     * de l'indemnité kilométrique selon le type de véhicule
     *
     * @return array (id, libelle, montant)
     */
    public function getTarifsKm()
    {
        $requetePrepare = PDOGsb::$_monPdo->prepare(
            'SELECT id AS id, libelle AS libelle, montant AS montant FROM vehicule'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    /**
     * Retourne le prix au KM à appliquer au visiteur en fonction
     * de la puissance du véhicule
     * Si la puissance du véhicule n'a pas été renseignée
     * dans le profil du visiteur, le taux par défaut renseigné sur la table
     * fraisforfait est appliqué
     *
     * @param string $idVisiteur Identifiant du visiteur
     *
     * @return float prix du KM
     */
    public function getTauxKM($idVisiteur)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT vehicule.montant AS taux '
            . 'FROM vehicule '
            . 'WHERE vehicule.id IN ( '
            . 'SELECT utilisateur.idvehicule '
            . 'FROM utilisateur WHERE id= :unIdVisiteur '
            . ')'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        if ($laLigne) {
            return $laLigne['taux'];
        } else {
            $requetePrepare = PdoGsb::$_monPdo->prepare(
                'SELECT montant AS taux '
                . 'FROM fraisforfait WHERE id="KM" '
            );
            $requetePrepare->execute();
            $laLigne = $requetePrepare->fetch();
            return $laLigne['taux'];
        }
    }

    /**
     * Retourne le statut de la fiche de frais
     *
     * @param string $idVisiteur Visiteur propriétéiare de la fiche
     * @param string $mois       Période de la fiche
     *
     * @return string Statut de la fiche (CR | CL | VA | RB)
     */
    public function getStatutFiche($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'SELECT idetat '
            . 'FROM fichefrais '
            . 'WHERE idvisiteur = :unIdVisiteur '
            . 'AND mois = :unMois '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $resultat =  $requetePrepare->fetch();
        return $resultat['idetat'];
    }

    /**
     * Retourne le statut de la fiche de frais sur laquelle
     * est enregistré le frais hors forfait dont l'identifiant
     * est donné en paramètres
     *
     * @param int $idFrais identifiant du frais hors forfait
     *
     * @return string  Statut de la fiche (CR | CL | VA | RB)
     */
    public function getStatutFicheFraisHF($idFrais)
    {
        $idFiche    = $this->getFicheFraisParId($idFrais);
        $idVisiteur = $idFiche['idVisiteur'];
        $mois       = $idFiche['mois'];

        return $this->getStatutFiche($idVisiteur, $mois);
    }

    /**
     * Retourne true si l'identifiant android du frais hors forfait
     * existe dans la table pour ce visiteur
     *
     * @param String $idVisiteur ID du visiteur
     * @param int    $idFrais    ID du frais hors forfait
     *
     * @return int
     */
    public function fraisHorsForfaitAndroidExiste($idVisiteur, $idFrais)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'SELECT id FROM lignefraishorsforfait '
            . 'WHERE idvisiteur = :unIdVisiteur '
            . 'AND idandroid = :unIdAndroid '
            . 'AND actif = "1" '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdAndroid', $idFrais, PDO::PARAM_INT);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetch();
        if ($lesLignes) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return void
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat)
    {
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = :unEtat, datemodif = now() '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return void
     */
    public function majFraisForfait(
        $idVisiteur,
        $mois,
        $lesFrais
    ) {
        $lesCles = array_keys($lesFrais);

        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = PdoGSB::$_monPdo->prepare(
                'UPDATE lignefraisforfait '
                . 'SET lignefraisforfait.quantite = :uneQte, '
                . 'lignefraisforfait.datemodif = NOW() '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = :idFrais '
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Mise à jour d'un élément forfaitisé
     *
     * @param String $idVisiteur ID du visiteur
     * @param array  $frais      Informations du frais à enregistrer
     *
     * @return void
     */
    public function majFraisForfaitAndroid($idVisiteur, $frais)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'UPDATE lignefraisforfait '
            . 'SET lignefraisforfait.quantite = :uneQte, '
            . 'lignefraisforfait.datemodif = NOW() '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois = :unMois '
            . 'AND lignefraisforfait.idfraisforfait = :unIdFrais '
            . 'AND lignefraisforfait.datemodif < :uneDateModif '
        );
        $requetePrepare->bindParam(':uneQte', $frais['quantite'], PDO::PARAM_INT);
        $requetePrepare->bindParam(':unMois', $frais['mois'], PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdFrais', $frais['idFrais'], PDO::PARAM_STR);
        $requetePrepare->bindParam(
            ':uneDateModif',
            $frais['datemodif'],
            PDO::PARAM_STR
        );
        $requetePrepare->execute();
    }

    /**
     * Mise à jour d'un frais hors forfait
     *
     * @param int    $id        Identifiant du frais à mettre à jour
     * @param string $libelle   Description du frais
     * @param string $date      Date du frais
     * @param float  $montant   Montant du frais
     * @param int    $idAndroid Identifiant du frais sur l'appli android
     *
     * @return void
     */
    public function majFraisHorsForfait(
        $id,
        $libelle,
        $date,
        $montant,
        $idAndroid = null
    ) {

        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'UPDATE lignefraishorsforfait '
            . 'SET libelle= :unLibelle, date= :uneDateFr, montant= :unMontant, idandroid = :unIdAndroid '
            . 'WHERE id= :unID '
        );

        $requetePrepare->bindParam(':unID', $id, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdAndroid', $idAndroid, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Mise à jour d'un frais hors forfait envoyé depuis l'appli Android
     *
     * @param String $idVisiteur Identifiant du visiteur
     * @param array  $frais      Infos du frais hors forfait
     *
     * @return void
     */
    public function majFraisHorsForfaitAndroid($idVisiteur, $frais)
    {
        $leMois = $frais['mois'];
        $annee = substr($leMois, 0, 4);
        $mois  = substr($leMois, 4, 2);

        $idAndroid = $frais['id'];

        $dateFr = date(
            'd/m/Y',
            mktime(null, null, null, $mois, $frais['jour'], $annee)
        );
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'UPDATE lignefraishorsforfait '
            . 'SET libelle = :unLibelle, date=:uneDateFr, montant= :unMontant '
            . 'WHERE (idandroid = :unID AND idvisiteur = :unIDVisiteur) '

        );
        $requetePrepare->bindParam(':unID', $id, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unLibelle', $frais['libelle'], PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $frais['montant'], PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIDVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return void
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET nbjustificatifs = :unNbJustificatifs '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(
            ':unNbJustificatifs',
            $nbJustificatifs,
            PDO::PARAM_INT
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }



    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param int $idFrais ID du frais
     *
     * @return void
     */
    public function supprimerFraisHorsForfait($idFrais)
    {
        /*$requetePrepare = PdoGSB::$_monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();*/
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'UPDATE lignefraishorsforfait SET actif = "0" '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Suppression d'un frais hors forfait dont l'id android est donné en paramètres
     *
     * @param int    $idFrais   ID du frais
     * @param string $dateModif Date de suppression du frais sur Android
     *
     * @return void
     */
    public function supprimerFraisHorsForfaitAndroid($idFrais, $dateModif)
    {
        /*$requetePrepare = PdoGsb::$_monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idandroid = :unIdFrais '
            . ' AND lignefraishorsforfait.datemodif < :dateModif '
        );*/
        $requetePrepare = PdoGSB::$_monPdo->prepare(
            'UPDATE lignefraishorsforfait SET actif = "0" '
            . 'WHERE lignefraishorsforfait.idandroid = :unIdFrais '
            . ' AND lignefraishorsforfait.datemodif < :dateModif '
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->bindParam(':dateModif', $dateModif, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Réinitialise l'idandroid
     *
     * @param string $idVisiteur ID android à réinitialiser
     * @param int    $idFrais    ID android à réinitialiser
     *
     * @return void
     */
    public function resetIdAndroid($idVisiteur, $idFrais)
    {
        $requetePrepare = PdoGsb::$_monPdo->prepare(
            'UPDATE lignefraishorsforfait '
                . 'SET idandroid = NULL, datemodif = NOW() '
                . 'WHERE idandroid = :unIdFrais '
                . 'AND idvisiteur = :unIdVisiteur '
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    /**
     * Passe la fiche de frais au statut validé
     * Met à jour le montant valide et la date de modification de la fiche
     *
     * @param string $idVisiteur identifiant du visiteur
     * @param string $mois       mois de la fiche     *
     * @param float  $montant    montant à valider
     *
     * @return void
     */
    public function validerFicheFrais($idVisiteur, $mois, $montant)
    {
        $requetePrepare = PDOGSB::$_monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = "VA", '
            . 'datemodif = now(), '
            . 'montantvalide = :unMontant '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
}
