<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Code Visite", "Désignation", "Fiches", "Vesions", "Modèle");
	$bd = bd_connect();
	$sql = "SELECT *
			FROM visite";
	$content =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['vi_id'], $tableau['vi_designation'], 'Autre', $tableau['vi_num_vers'], 'Autre', 'Modifier');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Visites");
	

	ob_end_flush();
?>