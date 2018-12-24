<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 

	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page Historise
	###################################################################*/


	echo '<div class="scroller">';			
	generic_top_title("../img/passations.jpg", 'Passations');			
	$entete=array($_SESSION['ps1'], "Equipement", "Début", "Fin");
	$content= array();

	if(empty($content))
			$content[] = create_table_ligne(null, array("Rien a afficher"));
	create_table($entete, $content, null, "Historisées");
	echo '</div>';

	ob_end_flush();
?>