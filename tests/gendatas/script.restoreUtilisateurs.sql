-- Script d'ajout d'un jeu d'utilisateurs test
-- Note : les mots de passe décryptés sont disponibles dans le fichier utilisateurs.json


-- Alimentation de la table des utilisateurs
INSERT INTO gsb_frais.utilisateur (id, nom, prenom, login, mdp, adresse, cp, ville, dateembauche, idvehicule, idprofil) VALUES
('a131', 'Villechalane', 'Louis', 'lvillachane', '$2y$10$ujXaw6VCHYtohjsByY/fpueTEKzNJaQzdRZCBEtsioOFfuPsoJlHy', '8 rue des Charmes', '46000', 'Cahors', '2005-12-21', 2, 1),
('a17', 'Andre', 'David', 'dandre', '$2y$10$XtKDYCX4.qBrwMsmEyeJIe0FWC8.EgtraKns5Myzgfnejt8/m51iG', '1 rue Petit', '46200', 'Lalbenque', '1998-11-23', 2, 1),
('a55', 'Bedos', 'Christian', 'cbedos', '$2y$10$RO1jnSlEmqzmC6C6FP2NluYfZ2Rig6vXhYFZK5PVIp4H7TXBFrUmy', '1 rue Peranud', '46250', 'Montcuq', '1995-01-12', 4, 1),
('a93', 'Tusseau', 'Louis', 'ltusseau', '$2y$10$EfliNIige0oF1ROGnaym/.4zuAMvlsHJWYu.diRsixnZ1lqOEKiiu', '22 rue des Ternes', '46123', 'Gramat', '2000-05-01', 3, 1),
('b13', 'Bentot', 'Pascal', 'pbentot', '$2y$10$5n7bEiDNXb0pnghs7mmAcuS6d7cRRsFeU6YxGGa0Fa9BwJF8tiYUy', '11 allée des Cerises', '46512', 'Bessines', '1992-07-09', 2, 1),
('b16', 'Bioret', 'Luc', 'lbioret', '$2y$10$zsjegOLfhMDqhlt2Og6ddup989eTZepaQMZO1WkrQoiqiHdVNp7Aq', '1 Avenue gambetta', '46000', 'Cahors', '1998-05-11', 3, 1),
('b19', 'Bunisset', 'Francis', 'fbunisset', '$2y$10$xroUlkAogYFh9JmnvlCIYOAQ3nMZhYRuP9WozMAZefH06xkN0qABO', '10 rue des Perles', '93100', 'Montreuil', '1987-10-21', 4, 1),
('b25', 'Bunisset', 'Denise', 'dbunisset', '$2y$10$1uFAb2Hmu.1Lp/NpVjrkpeNaFSvNSbR58seS7WkJdJ.ET205u.O1G', '23 rue Manin', '75019', 'paris', '2010-12-05', 2, 1),
('b28', 'Cacheux', 'Bernard', 'bcacheux', '$2y$10$fL.s6r/039enSqWtfa8rTeHmSk8d9GWpbw3xq0vcbwDg14yyubvVS', '114 rue Blanche', '75017', 'Paris', '2009-11-12', 3, 1),
('b34', 'Cadic', 'Eric', 'ecadic', '$2y$10$qjN536a7LkUf53ImJDIS1uDKUR1TgTYhIhj8Pdmr8vZ383ITHPJ6W', '123 avenue de la République', '75011', 'Paris', '2008-09-23', 2, 1),
('b4', 'Charoze', 'Catherine', 'ccharoze', '$2y$10$w0jgz3BEs1NmBYDTSZV3t..oaLSb2u/N1.Eq01d1cdWTRbZak6V8C', '100 rue Petit', '75019', 'Paris', '2005-11-12', 2, 1),
('b50', 'Clepkens', 'Christophe', 'cclepkens', '$2y$10$v225I4OHyfIVJB3NiPxNZeGRUpkxiBX1eiiw7ODwjhdDn8GuTY4/.', '12 allée des Anges', '93230', 'Romainville', '2003-08-11', 2, 1),
('b59', 'Cottin', 'Vincenne', 'vcottin', '$2y$10$L9LemO3IvhNoGAOaQqesQOC4qRBGNDGz2q.xtxsKXpNHlNtEqP0b2', '36 rue Des Roches', '93100', 'Monteuil', '2001-11-18', 1, 1),
('c14', 'Daburon', 'François', 'fdaburon', '$2y$10$NWR8oL6wv6yQib4IkLoO9uSJIMsw8FkHJhxB5A7XNVkzOGa3i7Ove', '13 rue de Chanzy', '94000', 'Créteil', '2002-02-11', 3, 1),
('c3', 'De', 'Philippe', 'pde', '$2y$10$pwpPhsR9QlG.u.G0/eG5ZOrThbe1/J3qWt6NzFNO5PsKGdRMcEYQa', '13 rue Barthes', '94000', 'Créteil', '2010-12-14', 1, 1),
('c54', 'Debelle', 'Michel', 'mdebelle', '$2y$10$NOnjEte99PoaLkIP1S4UXOVBsv6s/kfP5JcOjJlPwFvJp41Wedwti', '181 avenue Barbusse', '93210', 'Rosny', '2006-11-23', 2, 1),
('d13', 'Debelle', 'Jeanne', 'jdebelle', '$2y$10$dC2mQWYsuYsHhePjCYQRIu4x8LENZtR/2fvqnLYIrK62PbgflPzIC', '134 allée des Joncs', '44000', 'Nantes', '2000-05-11', 2, 1),
('d51', 'Debroise', 'Michel', 'mdebroise', '$2y$10$G6SpYOZ6AO9gUiHBLB6FnehvN5t0UZvwH/Ej2gRG9kRpGPEBfP4rC', '2 Bld Jourdain', '44000', 'Nantes', '2001-04-17', 3, 1),
('e22', 'Desmarquest', 'Nathalie', 'ndesmarquest', '$2y$10$JynWpSgdXS7vN8N0Dtrr/OerV7B45WWp5M9k1TRF/PuEd2BvSg4HK', '14 Place d Arc', '45000', 'Orléans', '2005-11-12', 2, 1),
('e24', 'Desnost', 'Pierre', 'pdesnost', '$2y$10$YTbBlZ8CyMxawYdIMDzuqeFLT7zU429QPAQfMeQO265JKJ4sa/18S', '16 avenue des Cèdres', '23200', 'Guéret', '2001-02-05', 1, 1),
('e39', 'Dudouit', 'Frédéric', 'fdudouit', '$2y$10$igVkLZ6f4NSW/s85RfJexOBD1fEO9R7pgfroVyqDVlU.f12vpkvkK', '18 rue de l église', '23120', 'GrandBourg', '2000-08-01', 1, 1),
('e49', 'Duncombe', 'Claude', 'cduncombe', '$2y$10$sewWkTSr3DsV9B6ysT884uBCFkck7hGcjR5xx1NSS1pF7TmWfpo7G', '19 rue de la tour', '23100', 'La souteraine', '1987-10-10', 1, 1),
('e5', 'Enault-Pascreau', 'Céline', 'cenault', '$2y$10$ebMvP7AnIkgwNXUfNhPbI.tDc5SzBTJfgje7zT8OTKVSM0q.HJHPi', '25 place de la gare', '23200', 'Gueret', '1995-09-01', 2, 1),
('e52', 'Eynde', 'Valérie', 'veynde', '$2y$10$L4IuKxNsWP.k9ObJO0VhKejylkyUyxijZ8iAJwtXtWuPZSWn1/EIu', '3 Grand Place', '13015', 'Marseille', '1999-11-01', 3, 1),
('f21', 'Finck', 'Jacques', 'jfinck', '$2y$10$LFgicBJUuF5787tPSk.aQ.GIMgi/3LM3mOvcgR/4xqqKHQkqehE02', '10 avenue du Prado', '13002', 'Marseille', '2001-11-10', null , 2),
('f39', 'Frémont', 'Fernande', 'ffremont', '$2y$10$1jE2N4OVltzPeIZhNYBs8.lC/OYi3Inuhr1ZVFrRpSbT20meFyziS', '4 route de la mer', '13012', 'Allauh', '1998-10-01', 3, 1),
('f4', 'Gest', 'Alain', 'agest', '$2y$10$qvAo1fymC3//ui/Hv8agHeN6UxddlLawl5b3YohjRqV4i1o3jFucq', '30 avenue de la mer', '13025', 'Berre', '1985-11-01', null , 2);