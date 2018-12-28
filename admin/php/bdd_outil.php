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
		$id=bd_protect($bd, $_POST['id_delete']);

		$sql = "DELETE FROM realisation_visite WHERE rv_ou_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM outil WHERE ou_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id=bd_protect($bd, $_POST['id_modify']);
		$code = bd_protect($bd, $_POST['code']);
		$designation = bd_protect($bd, $_POST['designation']);
		$modele = bd_protect($bd, $_POST['modele']);
		$inactif = bd_protect($bd, $_POST['inactif']);

		$sql = "SELECT mo_id FROM modele WHERE mo_designation = '".$modele."'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$sql = "UPDATE outil 
				SET ou_code = '".$code."', ou_designation = '".$designation."', ou_mo_id = ".$tableau['mo_id'].", ou_inactif = ".$inactif."
				WHERE ou_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_add'])){
		$id=bd_protect($bd, $_POST['id_add']);
		$code = bd_protect($bd, $_POST['code']);
		$designation = bd_protect($bd, $_POST['designation']);
		$modele = bd_protect($bd, $_POST['modele']);
		$inactif = bd_protect($bd, $_POST['inactif']);

		$sql = "SELECT mo_id FROM modele WHERE mo_designation = '".$modele."'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$sql = "INSERT INTO outil
				VALUES (".$id.",'".$code."','".$designation."',".$tableau['mo_id'].",".$inactif.")";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}

	mysqli_close($bd);
	ob_end_flush();
?>