<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("ID", "Designation");
	create_table($entete, array(), null, "Organisations");
	

	ob_end_flush();
?>