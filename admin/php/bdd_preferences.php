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

	if(isset($_POST['ps1'])){

		$sql = "UPDATE admin_parameters SET
				ap_pslvl1 ='".$_POST['ps1']."',
				ap_pslvl2 ='".$_POST['ps2']."',
				ap_pslvl3 ='".$_POST['ps3']."',
				ap_eqlvl1 ='".$_POST['eq1']."',
				ap_eqlvl2 ='".$_POST['eq2']."',
				ap_eqlvl3 ='".$_POST['eq3']."'
				WHERE ap_KEY = 1";  

		$_SESSION['ps1'] = $_POST['ps1'];
		$_SESSION['ps2'] = $_POST['ps2'];
		$_SESSION['ps3'] = $_POST['ps3'];
		$_SESSION['eq1'] = $_POST['eq1'];
		$_SESSION['eq2'] = $_POST['eq2'];
		$_SESSION['eq3'] = $_POST['eq3'];

		mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		echo 'ok';
	}


?>