<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Id", "Désignation", "Organisation");
	create_table($entete, array(), null, "Modèles");
	

	ob_end_flush();
?>