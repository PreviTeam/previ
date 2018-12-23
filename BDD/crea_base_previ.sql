CREATE USER 'previuser'@'localhost' IDENTIFIED BY '123';
GRANT SELECT, UPDATE, INSERT ON previ.* TO 'previuser'@'localhost';

##########################################################
#					Equipement
##########################################################

create table ORGANISATION(
	or_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	or_code VARCHAR(10),
	or_designation VARCHAR(50),
	or_inactif BOOLEAN
);

create table MODELE(
	mo_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	mo_code VARCHAR(10),
	mo_designation VARCHAR(50),
	mo_or_id INT,
	mo_inactif BOOLEAN
);

create table OUTIL(
	ou_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	ou_code VARCHAR(10),
	ou_designation VARCHAR(50),
	ou_mo_id INT,
	ou_inactif BOOLEAN
);

##########################################################
#					Employé
##########################################################

create table EMPLOYE(
	em_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	em_code VARCHAR(10),
	em_prenom VARCHAR(60),
	em_nom VARCHAR(40),
	em_status VARCHAR(10),
	em_mdp VARCHAR(32),
	em_inactif BOOLEAN
);

##########################################################
#					Visites
##########################################################

create table EPI(
	epi_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	epi_designation VARCHAR(50)
);

create table VISITE(
	vi_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	vi_designation VARCHAR(100),
	vi_num_vers VARCHAR(10),
	vi_type INT,
	vi_inactif BOOLEAN
);

create table FICHE(
	fi_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	fi_designation VARCHAR(100),
	fi_num_vers VARCHAR(10),
	fi_inactif BOOLEAN
);

create table OPERATION(
	op_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	op_contenu VARCHAR(600),
	op_type INT,
	op_inactif BOOLEAN
);

##########################################################
#					Compositions
##########################################################

create table COMPO_OPERATION(
	co_op_id INT NOT NULL,
	co_epi_id INT NOT NULL,
	constraint pk_compoO PRIMARY KEY (co_op_id,co_epi_id)
);

create table COMPO_FICHE(
	cf_fi_id INT NOT NULL,
	cf_op_id INT NOT NULL,
	cf_ordre INT,
	constraint pk_compoF PRIMARY KEY (cf_fi_id,cf_op_id)
);

create table COMPO_VISITE(
	cv_vi_id INT NOT NULL,
	cv_fi_id INT NOT NULL,
	constraint pk_compoV PRIMARY KEY (cv_vi_id,cv_fi_id)
);

create table VISITE_ATTACHEMENT(
	va_vi_id INT NOT NULL,
	va_mo_id INT NOT NULL,
	constraint pk_vAtt PRIMARY KEY (va_vi_id,va_mo_id)
);

##########################################################
#					REALISATION
##########################################################

create table REALISATION_VISITE(
	rv_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	rv_vi_id INT,
	rv_ou_id INT,
	rv_debut DATE,
	rv_fin DATE,
	rv_etat BOOLEAN
);

create table REALISATION_FICHE(
	rf_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	rf_fi_id INT,
	rf_rv_id INT,
	rf_em_id INT,
	rf_debut DATE,
	rf_fin DATE,
	rf_etat BOOLEAN
);

create table REALISATION_OPERATION(
	ro_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	ro_op_id INT,
	ro_rf_id INT,
	ro_res VARCHAR(10)
);


##########################################################
#					Historisation
##########################################################

create table HISTO_REALISATION_VISITE(
	rv_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	rv_vi_id INT,
	rv_ou_id INT,
	rv_debut DATE,
	rv_fin DATE,
	rv_etat BOOLEAN
);

create table HISTO_REALISATION_FICHE(
	rf_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	rf_fi_id INT,
	rf_rv_id INT,
	rf_em_id INT,
	rf_debut DATE,
	rf_fin DATE,
	rf_etat BOOLEAN
);

create table HISTO_REALISATION_OPERATION(
	ro_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	ro_op_id INT,
	ro_rf_id INT,
	ro_res VARCHAR(10)
);


##########################################################
#					ADMIN PARAM
##########################################################

create table admin_parameters(
	ap_KEY INT NOT NULL DEFAULT 1,
	ap_pslvl1 VARCHAR(15) NOT NULL DEFAULT 'Visites',
	ap_pslvl2 VARCHAR(15) NOT NULL DEFAULT 'Fiches',
	ap_pslvl3 VARCHAR(15) NOT NULL DEFAULT 'Operations',
	ap_eqlvl1 VARCHAR(15) NOT NULL DEFAULT 'Organisaions',
	ap_eqlvl2 VARCHAR(15) NOT NULL DEFAULT 'Modeles',
	ap_eqlvl3 VARCHAR(15) NOT NULL DEFAULT 'Equipements',
	constraint pk_params_admin PRIMARY KEY (ap_KEY, ap_pslvl1, ap_pslvl2, ap_pslvl3, ap_eqlvl1, ap_eqlvl2, ap_eqlvl3)
);

##########################################################
#					FOREIGN KEY
##########################################################

alter table MODELE
	add constraint fk_mo_or FOREIGN KEY (mo_or_id) REFERENCES ORGANISATION(or_id);

alter table OUTIL
	add constraint fk_ou_mo FOREIGN KEY (ou_mo_id) REFERENCES MODELE(mo_id);

alter table COMPO_OPERATION
	add constraint fk_co_op FOREIGN KEY (co_op_id) REFERENCES OPERATION(op_id);

alter table COMPO_OPERATION
	add constraint fk_co_epi FOREIGN KEY (co_epi_id) REFERENCES EPI(epi_id);

alter table COMPO_FICHE
	add constraint fk_cf_fi FOREIGN KEY (cf_fi_id) REFERENCES FICHE(fi_id);

alter table COMPO_FICHE
	add constraint fk_cf_op FOREIGN KEY (cf_op_id) REFERENCES OPERATION(op_id);

alter table COMPO_VISITE
	add constraint fk_cv_vi FOREIGN KEY (cv_vi_id) REFERENCES VISITE(vi_id);

alter table COMPO_VISITE
	add constraint fk_cv_fi FOREIGN KEY (cv_fi_id) REFERENCES FICHE(fi_id);

alter table VISITE_ATTACHEMENT
	add constraint fk_va_vi FOREIGN KEY (va_vi_id) REFERENCES VISITE(vi_id);

alter table VISITE_ATTACHEMENT
	add constraint fk_va_mo FOREIGN KEY (va_mo_id) REFERENCES MODELE(mo_id);

alter table REALISATION_VISITE
	add constraint fk_rv_vi FOREIGN KEY (rv_vi_id) REFERENCES VISITE(vi_id);

alter table REALISATION_VISITE
	add constraint fk_rv_ou FOREIGN KEY (rv_ou_id) REFERENCES OUTIL(ou_id);

alter table REALISATION_FICHE
	add constraint fk_rf_fi FOREIGN KEY (rf_fi_id) REFERENCES FICHE(fi_id);

alter table REALISATION_FICHE
	add constraint fk_rf_rv FOREIGN KEY (rf_rv_id) REFERENCES REALISATION_VISITE(rv_id);

alter table REALISATION_FICHE
	add constraint fk_rf_em FOREIGN KEY (rf_em_id) REFERENCES EMPLOYE(em_id);

alter table REALISATION_OPERATION
	add constraint fk_ro_op FOREIGN KEY (ro_op_id) REFERENCES OPERATION(op_id);

alter table REALISATION_OPERATION
	add constraint fk_ro_rf FOREIGN KEY (ro_rf_id) REFERENCES REALISATION_FICHE(rf_id);

alter table HISTO_REALISATION_FICHE
	add constraint fk_rf_em FOREIGN KEY (rf_em_id) REFERENCES EMPLOYE(em_id);

alter table HISTO_REALISATION_OPERATION
	add constraint fk_ro_op FOREIGN KEY (ro_op_id) REFERENCES OPERATION(op_id);

alter table HISTO_REALISATION_OPERATION
	add constraint fk_ro_rf FOREIGN KEY (ro_rf_id) REFERENCES REALISATION_FICHE(rf_id);