# gsb_appliweb
Application de saisie et de gestion des frais de déplacement des visiteurs médicaux du laboratoire GSB.
(Ce site est un projet fictif)

## Installation locale 
### Prérequis
Composer

npm

### Installation
Cloner le dépôt 
Lancer la commande composer update pour mettre à jour les dépendances
Lancer la commande npm install rfs

Copier le fichier config.inc.modele.php vers config.inc.php
Renseigner les identifiants de connexion à la base de données. 

Créer une base de données vierge nommée gsb_frais
Note : pour utilisation d'une bdd ayant un nom différent, modifier le fichier config.inc.php et les scripts d'installation situés dans le répertoire tests/gendatas

Lancer le script d'installation
/install.php

Pour générer un jeu d'utilisateurs ainsi que des fiches de frais aléatoires :
/install.php?users&datas

Attention le script install.php écrase les données de la base gsb_frais. Ce fichier doit être supprimé lors de la mise en production. 


### Démonstration
Une version de démonstration est accessible ici : https://www.gsb.virginie-claude.fr
