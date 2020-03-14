-- Script de création de la base de données gsb_tests
-- Version 3


-- Administration de la base de données
DROP DATABASE IF EXISTS gsb_tests;
CREATE DATABASE gsb_tests;
GRANT SHOW DATABASES ON *.* TO userGsb@localhost IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON `gsb_tests`.* TO userGsb@localhost;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
USE gsb_tests ;

-- Création de la structure de la base de données
CREATE TABLE IF NOT EXISTS fraisforfait (
  id char(3) NOT NULL,
  libelle char(20) DEFAULT NULL,
  montant decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS etat (
  id char(2) NOT NULL,
  libelle varchar(30) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS vehicule (
    id INT(11) NOT NULL AUTO_INCREMENT,
    libelle varchar(32) DEFAULT NULL,
    montant decimal(5,2) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS profil (
    id int(11) NOT NULL AUTO_INCREMENT,
    libelle varchar(32) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS utilisateur (
  id char(4) NOT NULL,
  nom char(30) DEFAULT NULL,
  prenom char(30)  DEFAULT NULL,
  login char(20) DEFAULT NULL,
  mdp varchar(255) DEFAULT NULL,
  adresse char(30) DEFAULT NULL,
  cp char(5) DEFAULT NULL,
  ville char(30) DEFAULT NULL,
  dateembauche date DEFAULT NULL,
  idvehicule int(11) DEFAULT NULL,
  idprofil int(11) DEFAULT 1,
  PRIMARY KEY (id),
  FOREIGN KEY (idvehicule) REFERENCES vehicule(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fichefrais (
  idvisiteur char(4) NOT NULL,
  mois char(6) NOT NULL,
  nbjustificatifs int(11) DEFAULT NULL,
  montantvalide decimal(10,2) DEFAULT NULL,
  datemodif date DEFAULT NULL,
  idetat char(2) DEFAULT 'CR',
  PRIMARY KEY (idvisiteur,mois),
  FOREIGN KEY (idetat) REFERENCES etat(id),
  FOREIGN KEY (idvisiteur) REFERENCES utilisateur(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lignefraisforfait (
  idvisiteur char(4) NOT NULL,
  mois char(6) NOT NULL,
  idfraisforfait char(3) NOT NULL,
  quantite int(11) DEFAULT NULL,
  datemodif datetime DEFAULT NOW(),
  PRIMARY KEY (idvisiteur,mois,idfraisforfait),
  FOREIGN KEY (idvisiteur, mois) REFERENCES fichefrais(idvisiteur, mois),
  FOREIGN KEY (idfraisforfait) REFERENCES fraisforfait(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lignefraishorsforfait (
  id int(11) NOT NULL auto_increment,
  idvisiteur char(4) NOT NULL,
  mois char(6) NOT NULL,
  libelle varchar(100) DEFAULT NULL,
  date date DEFAULT NULL,
  montant decimal(10,2) DEFAULT NULL,
  datemodif datetime DEFAULT NOW(),
  idandroid int(11) DEFAULT NULL,
  actif tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  FOREIGN KEY (idvisiteur, mois) REFERENCES fichefrais(idvisiteur, mois)
) ENGINE=InnoDB;

-- Alimentation des profils
INSERT INTO profil (libelle) VALUES
('Visiteur'),
('Comptable');

-- Alimentation des données paramètres
INSERT INTO fraisforfait (id, libelle, montant) VALUES
('ETP', 'Forfait Etape', 110.00),
('KM', 'Frais Kilométrique', 0.62),
('NUI', 'Nuitée Hôtel', 80.00),
('REP', 'Repas Restaurant', 25.00);

-- Alimentation des états
INSERT INTO etat (id, libelle) VALUES
('RB', 'Remboursée'),
('CL', 'Saisie clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('VA', 'Validée et mise en paiement');

-- Alimentation de la table des véhicules
INSERT INTO vehicule (libelle,montant)
VALUES
('Véhicule 4CV Diésel',0.52),
('Véhicule 5/6CV Diésel', 0.58),
('Véhicule 4CV Essence', 0.62),
('Véhciule 5/6CV Essence', 0.67);


-- Alimentation de la table des utilisateurs
INSERT INTO utilisateur (id, nom, prenom, login, mdp, adresse, cp, ville, dateembauche, idvehicule, idprofil) VALUES
('a17', 'Andre', 'David', 'dandre', '$2y$10$XtKDYCX4.qBrwMsmEyeJIe0FWC8.EgtraKns5Myzgfnejt8/m51iG', '1 rue Petit', '46200', 'Lalbenque', '1998-11-23', 2, 1),
('f21', 'Finck', 'Jacques', 'jfinck', '$2y$10$LFgicBJUuF5787tPSk.aQ.GIMgi/3LM3mOvcgR/4xqqKHQkqehE02', '10 avenue du Prado', '13002', 'Marseille', '2001-11-10', null , 2);
