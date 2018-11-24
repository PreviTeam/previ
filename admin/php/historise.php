<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Visite", "Equipement", "Début", "Fin");
	$content= array();

	if(empty($content))
			$content[] = create_table_ligne(null, array("Rien a afficher"));
	create_table($entete, $content, null, "Historisées");
	

	ob_end_flush();
?>