<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("ID", "Code", "Designation", '', '');

	$bd = bd_connect();
	$sql = "SELECT *
			FROM organisation";
	$content =array();



	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['or_id'], $tableau['or_code'],  $tableau['or_designation'], 'Voir', 'Modifier');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Organisations");
	

	ob_end_flush();
?>