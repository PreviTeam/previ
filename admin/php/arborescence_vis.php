<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/


	$bd = bd_connect();
	$sql = "SELECT *
			FROM visite, fiche, operation, compo_fiche, compo_visite
			where vi_id = cv_vi_id
			AND fi_id = cv_fi_id
			AND fi_id = cf_fi_id
			AND op_id = cf_op_id";

	$content =array();
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	$last_vi = -1;
	$last_fi = -1;

	$vi = array();
	$fiches = array();
	$ope = array();

	while($tableau = mysqli_fetch_assoc($res)){

		if($last_vi != -1){

			if($last_fi != $tableau['fi_designation']){
				$fiches[] = array($last_fi, $ope);
				$ope = array();
			}
			if($last_vi != $tableau['vi_designation']){
				$vi[] = array($last_vi, $fiches);
				$fiches = array();
				$ope = array();
			}
			
		}

		$ope[] = $tableau['op_contenu'];
		$last_vi=$tableau['vi_designation'];
		$last_fi=$tableau['fi_designation'];
		
	}
	create_treeview("Arborescence Equipement", $vi);
	
	mysqli_close($bd);
	ob_end_flush();
?>