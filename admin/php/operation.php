<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Code Opération", "Contenu", "Demande", '', '');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM operation";
	$content =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$demande = null;
		switch($tableau['op_type']){
			case 0:
				$demande = 'Oui / Non';
				break;
			case 1:
				$demande= 'Texte';
				break;
		}

		$ligne=array($tableau['op_id'],  $tableau['op_contenu'], $demande, 'Voir', 'Modifier');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Opérations");
	

	ob_end_flush();
?>