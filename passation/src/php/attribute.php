<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

/*###################################################################
			Modification de la BDD selon la demande
###################################################################*/
	$bd = bd_connect();

	// Demande de Modifiction d'un élément
	if(isset($_POST['id'])){
		$id = bd_protect($bd, $_POST['id']);

		$sql = "UPDATE realisation_fiche SET rf_em_id = '".$_SESSION['em_id']."' WHERE rf_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	}

	dashboard_content($bd);

	mysqli_close($bd);
	ob_end_flush();
?>