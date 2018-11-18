<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("En Cours", "Equipement", "Agent", "Fait");
	create_table($entete, array(), null, "Visites");
	create_table($entete, array(), null, "Fiches");
	

	ob_end_flush();
?>