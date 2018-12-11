
##########################################################
#					Equipement
##########################################################


# id, codecourt, designation, inactif
INSERT INTO ORGANISATION VALUES
(0, "BUS"	, "DMPP BUS"	, false),
(1, "TRAM"	, "DMPP TRAM"	, false),
(2, "MSE"	, "DSI MSE"		, false),
(3, "IF"	, "DSPP IF"		, false);

# id, codecourt, designation, idOrganisation, inactif
INSERT INTO MODELE VALUES
(0, "URBOS3", "URBOS3", 1, false),
(1, "URB", "URBANWAY", 0, false),
(2, "URB", "URBANWAY", 2, false),
(3, "GX317", "MERCEDES GX317 €2", 0, false),
(4, "GX327", "MERCEDES GX327 €2", 0, false),
(5, "CITARO2010", "MERCEDES CITARO STD GO EEV 2010", 0, false),
(6, "URBOS3", "URBOS3", 2, false),
(7, "GX317", "MERCEDES GX317 €2", 2, false),
(8, "LAC", "LIGNE AERIENNE DE CONTACT", 3, false),
(9, "CAMERA", "Camera de sécurité AB", 2, false);

# id, codecourt, designation, idModele, inactif
INSERT INTO OUTIL VALUES
(0, "801", "801 VICTOR HUGO", 0, false),
(1, "802", "802 FRERE LUMIERES", 0, false),
(2, "803", "803 COLETTE", 0, false),
(3, "804", "804 LOUIS PASTEUR", 0, false),
(4, "411", "URBANWAY 411", 1, false),
(5, "422", "URBANWAY 412", 1, false),
(6, "413", "URBANWAY 413", 1, false),
(7, "411", "URBANWAY 411", 2, false),
(8, "422", "URBANWAY 412", 2, false),
(9, "413", "URBANWAY 413", 2, false),
(10, "505", "URBINOS 505", 3, false),
(11, "506", "URBINOS 506", 4, false),
(12, "507", "URBINOS 507", 5, false),
(13, "801", "801 VICTOR HUGO", 6, false),
(14, "802", "802 FRERE LUMIERES", 6, false),
(15, "803", "803 COLETTE", 6, false),
(16, "505", "URBINOS 505", 7, false),
(17, "TIR1", "TIR CHAMAR", 8, false),
(18, "010-01", "CAMERA AIGUILLAGE ATELIER CDM", 9, false);



##########################################################
#					Employé
##########################################################

#codeActeur, prenom, nom, status, mdp, inactif
INSERT INTO EMPLOYE VALUES
(0,"02451", "Jean", "LEBLANC", "ADMIN", "7951c6075a0694c5c0ef0c77db80e4d2", false), 
(1,"5065", "Paul", "Guillert", "CE", "7951c6075a0694c5c0ef0c77db80e4d2", false), 
(2,"STAG1", "Henri", "Dupont", "TECH", "7951c6075a0694c5c0ef0c77db80e4d2", false);



##########################################################
#					Visites
##########################################################

INSERT INTO EPI VALUES
(0, "Casque"),
(1, "Lunettes"),
(2, "Anti-bruit"),
(3, "Gants"),
(4, "isolant electriques"),
(5, "Gilet de signalisation"),
(6, "Masque"),
(7, "Combinaison"),
(8, "Chaussures de sécurité");

#id, designation, version, type, inactif
# type :   0 = preventif    1 = reglementaire
INSERT INTO VISITE VALUES
(0, "VP2500", "1.0", 0, false),
(1, "VP5000", "1.0", 0, false),
(2, "VP15000", "1.0", 0, false),
(3, "VP70000", "1.0", 0, false),
(4, "Graissage URBANWAY", "1.0", 0, false),
(5, "GRAISSAGE + VMOT URBANWAY", "1.0", 0, false),
(6, "GRAISSAGE + VMOT + VB + VP URBANWAY", "1.0", 0, false),
(7, "PREVENTIF BUS MSE", "1.0", 0, false),
(8, "PREVENTIF TRAM MSE", "1.0", 0, false),
(9, "Preventif LAC", "1.0", 0, false),
(10, "Prepa Mines", "1.0", 1, false),
(11, "Mines", "1.0", 1, false);

#id, designation, version, inactif
INSERT INTO FICHE VALUES
(0, "Contrôle VP2500", "V152-562", false),
(1, "Opérations VP2500", "V152-65", false),
(2, "Contrôle VP5000", "V152-562", false),
(3, "Opérations VP5000", "V152-65", false),
(4, "Contrôle VP15000", "V152-562", false),
(5, "Opérations VP1500", "V152-65", false),
(6, "Contrôle VP70000", "V152-562", false),
(7, "Opérations VP70000", "V152-65", false),
(8, "Contrôle UR6", "MG45-565", false),
(9, "Opérations UR6", "MG45-565", false),
(10, "Vidange Moteur URB", "MG45-565", false),
(11, "Vidange Boite Vitesse / Pont", "MG45-565", false),
(12, "Fiche prépa Mines Bus", "MG45-565", false),
(13, "Fiche Mines Bus", "MG45-565", false);

#id, contenu, , type
# Type : 0 = Oui/Non    1 = Texte
INSERT INTO OPERATION VALUES
(0, "Contrôler les filtres", 0),
(1, "Relever le niveau d'huile", 1),
(2, "Contrôler le serrage Roues",  0),
(3, "Verifier la charge batterie", 0),
(4, "Mesure Roue AVG", 1),
(5, "Mesure Roue AVD", 1),
(6, "Mesure Roue ARG", 1),
(7, "Mesure Roue ARD", 1);

##########################################################
#					Compositions
##########################################################

#idOperation, IdEPI
INSERT INTO COMPO_OPERATION VALUES
(1, 1),
(4, 3),
(4, 4),
(5, 3),
(5, 4),
(6, 3),
(6, 4),
(7, 3),
(7, 4);


#IdFiche, IdOperation, Ordre
INSERT INTO COMPO_FICHE VALUES
(1, 4, 1),
(1, 5, 3),
(1, 6, 2),
(1, 7, 4),
(0, 1, 1),
(3, 4, 1),
(3, 5, 3),
(3, 6, 2),
(3, 7, 4),
(2, 1, 1),
(5, 4, 1),
(5, 5, 3),
(5, 6, 2),
(5, 7, 4),
(4, 1, 1),
(7, 4, 1),
(7, 5, 3),
(7, 6, 2),
(7, 7, 4),
(6, 1, 1),
(8, 0, 1),
(8, 2, 2),
(9, 1, 1),
(10, 1, 1),
(11, 1, 1),
(12, 0, 1),
(12, 1, 3),
(12, 2, 2),
(13, 0, 1),
(13, 1, 3),
(13, 2, 2),
(13, 3, 4),
(13, 4, 6),
(13, 5, 5),
(13, 6, 8),
(13, 7, 7);


#IdVisite, IdFiche
INSERT INTO COMPO_VISITE VALUES
(0, 0),
(0, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(3, 6),
(3, 7),
(4, 8),
(4, 9),
(5, 8),
(5, 9),
(5, 10),
(6, 8),
(6, 9),
(6, 11),
(10, 12),
(11, 13);


#idvisite, idModele
INSERT INTO VISITE_ATTACHEMENT VALUES
(0, 0),
(0, 1),
(0, 2),
(0, 3),
(1, 4),
(1, 5),
(1, 6);
#(1, 10),
#(1, 11),
#(3, 10),
#(3, 11),
#(4, 10),
#(4, 11),
#(5, 10),
#(5, 11);


##########################################################
#					REALISATION
##########################################################

#idreal, idVisite, idOutil, DateDebut, DateFin, Etat
#etat : false = En Cours,  True = Terminée
INSERT INTO REALISATION_VISITE VALUES
(0, 0, 0, '2018-05-01',  '2018-05-02', true);

#idRealisation, idFiche, idRealVisite, idActeur, DateDebut, DateFin, Etat 
#etat : false = En Cours,  True = Terminée
INSERT INTO REALISATION_FICHE VALUES
(0, 0, 0, 0, '2018-05-01', '2018-05-01', true),
(1, 1, 0, 0, '2018-05-02', '2018-05-02', true);

#idReal, idOperation, IdRealFiche, resultat
INSERT INTO REALISATION_OPERATION VALUES
(0, 1, 0, "oui"),
(1, 4, 1, "47.5"),
(2, 5, 1, "52.2"),
(3, 6, 1, "62.6"),
(4, 7, 1, "47.6");
