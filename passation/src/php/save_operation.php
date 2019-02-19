<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

/*###################################################################
			Sauvegarde en BDD d'une operation
###################################################################*/
	$bd = bd_connect();

	
	$id_op = bd_protect($bd, $_POST['op_id']);
	$id_rf = bd_protect($bd, $_POST['rf_id']);
	$res = bd_protect($bd, $_POST['res']);

	// Sauvegarde de l'opération en BDD
	$sql = "INSERT INTO realisation_operation (ro_op_id, ro_rf_id, ro_res)
            VALUES (".$id_op.",".$id_rf.",'".$res."')";
    $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	mysqli_close($bd);
	ob_end_flush();
?>