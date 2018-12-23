
##########################################################
#					Equipement
##########################################################


# id, codecourt, designation, inactif
INSERT INTO ORGANISATION (or_code, or_designation, or_inactif) VALUES
("BUS"	, "DMPP BUS"	, false),
("TRAM"	, "DMPP TRAM"	, false),
("MSE"	, "DSI MSE"		, false),
("IF"	, "DSPP IF"		, false);

# id, codecourt, designation, idOrganisation, inactif
INSERT INTO MODELE (mo_code, mo_designation, mo_or_id, mo_inactif) VALUES
("URBOS3", "URBOS3", 2, false),
("URB", "URBANWAY", 1, false),
("URB", "URBANWAY", 3, false),
("GX317", "MERCEDES GX317 €2", 1, false),
("GX327", "MERCEDES GX327 €2", 1, false),
("CITARO2010", "MERCEDES CITARO STD GO EEV 2010", 1, false),
("URBOS3", "URBOS3", 3, false),
("GX317", "MERCEDES GX317 €2", 3, false),
("LAC", "LIGNE AERIENNE DE CONTACT", 4, false),
("CAMERA", "Camera de sécurité AB", 3, false);

# id, codecourt, designation, idModele, inactif
INSERT INTO OUTIL (ou_code, ou_designation, ou_mo_id, ou_inactif) VALUES
("801", "801 VICTOR HUGO", 1, false),
( "802", "802 FRERE LUMIERES", 1, false),
( "803", "803 COLETTE", 1, false),
( "804", "804 LOUIS PASTEUR", 1, false),
( "411", "URBANWAY 411", 2, false),
( "422", "URBANWAY 412", 2, false),
( "413", "URBANWAY 413", 2, false),
( "411", "URBANWAY 411", 3, false),
( "422", "URBANWAY 412", 3, false),
( "413", "URBANWAY 413", 3, false),
( "505", "URBINOS 505", 4, false),
( "506", "URBINOS 506", 5, false),
( "507", "URBINOS 507", 6, false),
( "801", "801 VICTOR HUGO", 7, false),
( "802", "802 FRERE LUMIERES", 7, false),
( "803", "803 COLETTE", 7, false),
( "505", "URBINOS 505", 8, false),
( "TIR1", "TIR CHAMAR", 9, false),
( "010-01", "CAMERA AIGUILLAGE ATELIER CDM", 10, false);



##########################################################
#					Employé
##########################################################

#codeActeur, prenom, nom, status, mdp, inactif
INSERT INTO EMPLOYE (em_code, em_prenom, em_nom, em_status, em_mdp, em_inactif) VALUES
("02451", "Jean", "LEBLANC", "ADMIN", "7951c6075a0694c5c0ef0c77db80e4d2", false), 
("5065", "Paul", "Guillert", "CE", "7951c6075a0694c5c0ef0c77db80e4d2", false), 
("STAG1", "Henri", "Dupont", "TECH", "7951c6075a0694c5c0ef0c77db80e4d2", false);



##########################################################
#					Visites
##########################################################

INSERT INTO EPI (epi_designation) VALUES
("Casque"),
("Lunettes"),
( "Anti-bruit"),
("Gants"),
("isolant electriques"),
("Gilet de signalisation"),
("Masque"),
("Combinaison"),
("Chaussures de sécurité");

#id, designation, version, type, inactif
# type :   0 = preventif    1 = reglementaire
INSERT INTO VISITE (vi_designation, vi_num_vers, vi_type, vi_inactif) VALUES
( "VP2500", "1.0", 1, false),
( "VP5000", "1.0", 1, false),
("VP15000", "1.0", 1, false),
("VP70000", "1.0", 1, false),
("Graissage URBANWAY", "1.0", 1, false),
("GRAISSAGE + VMOT URBANWAY", "1.0", 1, false),
("GRAISSAGE + VMOT + VB + VP URBANWAY", "1.0", 1, false),
("PREVENTIF BUS MSE", "1.0", 1, false),
("PREVENTIF TRAM MSE", "1.0", 1, false),
("Preventif LAC", "1.0", 1, false),
( "Prepa Mines", "1.0", 1, false),
("Mines", "1.0", 1, false);

#id, designation, version, inactif
INSERT INTO FICHE (fi_designation, fi_num_vers, fi_inactif ) VALUES
("Contrôle VP2500", "V152-562", false),
 ("Opérations VP2500", "V152-65", false),
 ("Contrôle VP5000", "V152-562", false),
("Opérations VP5000", "V152-65", false),
("Contrôle VP15000", "V152-562", false),
("Opérations VP1500", "V152-65", false),
("Contrôle VP70000", "V152-562", false),
("Opérations VP70000", "V152-65", false),
("Contrôle UR6", "MG45-565", false),
( "Opérations UR6", "MG45-565", false),
("Vidange Moteur URB", "MG45-565", false),
( "Vidange Boite Vitesse / Pont", "MG45-565", false),
( "Fiche prépa Mines Bus", "MG45-565", false),
( "Fiche Mines Bus", "MG45-565", false);

#id, contenu, type, inactif
# Type : 1 = Oui/Non    2 = Texte
INSERT INTO OPERATION (op_contenu, op_type, op_inactif) VALUES
( "Contrôler les filtres", 1, 0),
("Relever le niveau d'huile", 2, 0),
("Contrôler le serrage Roues",  1, 0),
( "Verifier la charge batterie", 1, 0),
( "Mesure Roue AVG", 2, 0),
( "Mesure Roue AVD", 2, 0),
( "Mesure Roue ARG", 2, 0),
( "Mesure Roue ARD", 2, 0);


##########################################################
#				  	Parametres
##########################################################

INSERT INTO admin_parameters VALUES();

##########################################################
#					Compositions
##########################################################

#idOperation, IdEPI
INSERT INTO COMPO_OPERATION (co_op_id, co_epi_id) VALUES
(2, 2),
(5, 4),
(5, 5),
(6, 4),
(6, 5),
(7, 4),
(7, 5),
(8, 4),
(8, 5);


#IdFiche, IdOperation, Ordre
INSERT INTO COMPO_FICHE (cf_fi_id, cf_op_id, cf_ordre) VALUES
(2, 4, 1),
(2, 5, 3),
(2, 6, 2),
(2, 7, 4),
(1, 1, 1),
(4, 4, 1),
(4, 5, 3),
(4, 6, 2),
(4, 7, 4),
(3, 1, 1),
(6, 4, 1),
(6, 5, 3),
(6, 6, 2),
(6, 7, 4),
(5, 1, 1),
(8, 4, 1),
(8, 5, 3),
(8, 6, 2),
(8, 7, 4),
(7, 1, 1),
(9, 0, 1),
(9, 2, 2),
(10, 1, 1),
(11, 1, 1),
(12, 1, 1),
(13, 0, 1),
(13, 1, 3),
(13, 2, 2),
(14, 0, 1),
(14, 1, 3),
(14, 2, 2),
(14, 3, 4),
(14, 4, 6),
(14, 5, 5),
(14, 6, 8),
(14, 7, 7);


#IdVisite, IdFiche
INSERT INTO COMPO_VISITE (cv_vi_id, cv_fi_id ) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(4, 7),
(4, 8),
(5, 9),
(5, 10),
(6, 9),
(6, 10),
(6, 11),
(7, 9),
(7, 10),
(7, 12),
(11, 13),
(12, 14);


#idvisite, idModele
INSERT INTO VISITE_ATTACHEMENT (va_vi_id , va_mo_id) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7);


##########################################################
#					REALISATION
##########################################################

#idreal, idVisite, idOutil, DateDebut, DateFin, Etat
#etat : false = En Cours,  True = Terminée
INSERT INTO REALISATION_VISITE (rv_vi_id, rv_ou_id, rv_debut, rv_fin , rv_etat) VALUES
(1, 1, '2018-05-01',  '2018-05-02', true);

#idRealisation, idFiche, idRealVisite, idActeur, DateDebut, DateFin, Etat 
#etat : false = En Cours,  True = Terminée
INSERT INTO REALISATION_FICHE (rf_fi_id, rf_rv_id , rf_em_id, rf_debut, rf_fin, rf_etat) VALUES
(1, 1, 1, '2018-05-01', '2018-05-01', true),
(2, 1, 1, '2018-05-02', '2018-05-02', true);

#idReal, idOperation, IdRealFiche, resultat
INSERT INTO REALISATION_OPERATION (ro_op_id, ro_rf_id , ro_res) VALUES
(2, 1, "oui"),
(5, 2, "47.5"),
(6, 2, "52.2"),
(7, 2, "62.6"),
(8, 2, "47.6");
