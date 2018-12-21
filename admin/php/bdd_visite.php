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
		echo $_POST['id_delete'];
		//$id=bd_protect($bd, $_POST['id_delete']);
		//$sql = "DELETE FROM visite WHERE vi_id='".$id."'";

		// trouver l'exécution
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		echo $_POST['id_modify'];
		echo $_POST['t1'];
		//$id=bd_protect($bd, $_POST['id_modify']);
		//$sql = "UPDATE visite SET (
				
		//		) 
		//		WHERE vi_id='".$id."'";
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_new']))
		echo "new";

	mysqli_close($bd);
	ob_end_flush();
?>