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

	// Demande de supression d'un élément
	if(isset($_POST['id_delete'])){
		$id = bd_protect($bd,$_POST['id_delete']);

		$sql = "DELETE FROM modele WHERE mo_or_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM organisation WHERE or_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id = bd_protect($bd,$_POST['id_modify']);
		$designation = bd_protect($bd,$_POST['designation']);
		$code = bd_protect($bd,$_POST['code']);
		$inactif = bd_protect($bd,$_POST['inactif']);

		$sql = "UPDATE organisation
				SET or_designation='".$designation."',or_code = '".$code."',or_inactif=".$inactif." 
				WHERE or_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_add'])){

		$designation = bd_protect($bd,$_POST['designation']);
		$code = bd_protect($bd,$_POST['code']);
		$inactif = bd_protect($bd,$_POST['inactif']);

		$sql = "INSERT INTO organisation (or_code, or_designation, or_inactif)
        			VALUES ('".$code."','".$designation."',".$inactif.")";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}

	echo 'ok';

	mysqli_close($bd);
	ob_end_flush();
?>