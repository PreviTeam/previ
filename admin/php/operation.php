<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Code Opération", "Type", "Contenu", "Demande");
	create_table($entete, array(), null, "Opérations");
	

	ob_end_flush();
?>