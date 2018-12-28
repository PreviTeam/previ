<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page arboresence
	###################################################################*/

	echo '<div class="scroller">';						
	$bd = bd_connect();
	$sql = "SELECT *
			FROM organisation, modele, outil
			where or_id = mo_or_id
			and ou_mo_id = mo_id";

	$content =array();
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	$last_org = -1;
	$last_mod = -1;

	$org = array();
	$models = array();
	$outil = array();

	while($tableau = mysqli_fetch_assoc($res)){

		if($last_org != -1){

			if($last_mod != $tableau['mo_designation']){
				$models[] = array($last_mod, $outil);
				$outil = array();
			}
			if($last_org != $tableau['or_designation']){
				$org[] = array($last_org, $models);
				$models = array();
				$outil = array();
			}
			
		}

		$outil[] = entities_protect($tableau['ou_designation']);
		$last_org= entities_protect($tableau['or_designation']);
		$last_mod= entities_protect($tableau['mo_designation']);
		
	}
	$models[] = array($last_mod, $outil);
	$org[] = array($last_org, $models);

	create_treeview("Arborescence Equipement", $org);
    echo '</div>';
	mysqli_close($bd);
	ob_end_flush();
?>