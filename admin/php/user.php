<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Code Utilisateur", "Nom", "Prénom", "Status");
	create_table($entete, array(), null, "Utilisateurs");
	

	ob_end_flush();
?>