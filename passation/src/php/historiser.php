<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_pass_id']));
	$_GET && redirection("./deconnexion.php");

/*###################################################################
			Modification de la BDD selon la demande
###################################################################*/
	$bd = bd_connect();


	/*
	 * Vérification à la fermeture d'une fiche si il reste des fiches en cours
	 * Si la fiche est la dernière de la visite, alors on historise l'ensemble de la visite, fiches et opérations
	*/
	if(isset($_POST['rf_id'])){

		$id_rf = bd_protect($bd, $_POST['rf_id']);
		$nbFichesEnCours = get_nb_fiches_en_cours($bd, $id_rf);

		// Si la Fiche est la dernière validée de la visite, on lance une historisation
		if($nbFichesEnCours[0] === 0){

		    $id = $nbFichesEnCours[1];

			// ----------------   Historisation de la réalisation_visite -------------------------------- //
			$sql = "SELECT * 
					FROM  realisation_visite 
					WHERE rv_id = ".$id;
			$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			$tableau = mysqli_fetch_assoc($res);

			$sql_insert = "INSERT INTO histo_realisation_visite (h_rv_vi_id, h_rv_ou_id, h_rv_debut, h_rv_fin, h_rv_etat)
							VALUES ( ".$tableau['rv_vi_id'].", ".$tableau['rv_ou_id']." ,  '".$tableau['rv_debut']."',  '".$tableau['rv_fin']."' , 1)";
			$res2 = mysqli_query($bd, $sql_insert) or bd_erreur($bd, $sql_insert);
			$h_rv_id = mysqli_insert_id($bd);


			$delete = "DELETE FROM realisation_visite WHERE rv_id =".$id;
			$res_delete = mysqli_query($bd, $delete) or bd_erreur($bd, $delete);



			// ----------------   Historisation des réalisation_fiche  associé à la realisation visite -------------------------------- //

			$sql = "SELECT * 
					FROM  realisation_fiche
					WHERE rf_rv_id = ".$id;
			$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			
			while($tableau = mysqli_fetch_assoc($res)){

				// ------------ Historisation de la réalisation fiche et suppression de la realisation ------------------------------------ //

	        	$sql_insert = "INSERT INTO histo_realisation_fiche (h_rf_fi_id, h_rf_rv_id , h_rf_em_id, h_rf_debut, h_rf_fin, h_rf_etat)
							   VALUES ( ".$tableau['rf_fi_id'].", ".$h_rv_id." ,  '".$tableau['rf_em_id']."',  '".$tableau['rf_debut']."' , '".$tableau['rf_fin']."' , 1)";
				$res2 = mysqli_query($bd, $sql_insert) or bd_erreur($bd, $sql_insert);
				$h_rf_id = mysqli_insert_id($bd);

				$delete = "DELETE FROM realisation_fiche WHERE rf_rv_id =".$id;
				$res_delete = mysqli_query($bd, $delete) or bd_erreur($bd, $delete);

				// ------------- Historisation des réalisation_opération attachées à la realisation_fiche ----------------------------------------------------------//

				$sql_op = "SELECT * 
							FROM realisation_operation
							WHERE ro_rf_id = ".$tableau['rf_id'];
				$res_op = mysqli_query($bd, $sql_op) or bd_erreur($bd, $sql_op);

				while($tableau_operation = mysqli_fetch_assoc($res_op)){
					$sql_insert_op = "INSERT INTO histo_realisation_operation (h_ro_op_id, h_ro_rf_id, h_ro_res)
							          VALUES ( ".$tableau_operation['ro_op_id'].", ".$h_rf_id.", '".$tableau_operation['ro_res']."')";
					$res_insert_op = mysqli_query($bd, $sql_insert_op) or bd_erreur($bd, $sql_insert_op);

					$delete = "DELETE FROM realisation_operation WHERE ro_rf_id =".$tableau['rf_id'];
					$res_delete = mysqli_query($bd, $delete) or bd_erreur($bd, $delete);
				}
	        }
		}
	}


	/* 
	 * Vérification à la création d'une visite que celle ci contienne des fiches
	 * Si aucune fiche n'est attaché à la visite, alors historisation automatique de la visite vierge 
	 */
	if(isset($_POST['rv_id'])){

		$id = bd_protect($bd, $_POST['rv_id']);

		// ----------------   Verification du nombre de fiche attachée a la visite  -------------------------------- //
		
		$sql = "SELECT * 
				FROM  realisation_visite 
				WHERE rv_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$sql = "SELECT count(cv_fi_id) as nbFiches
				FROM  compo_visite
				WHERE cv_vi_id = ".$tableau['rv_vi_id'];
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$nbFiches = mysqli_fetch_assoc($res);

		if(intval($nbFiches['nbFiches']) === 0){

			// ----------------   Historisation de la visite -------------------------------- //

			$sql_insert = "INSERT INTO histo_realisation_visite (h_rv_vi_id, h_rv_ou_id, h_rv_debut, h_rv_fin, h_rv_etat)
							VALUES ( ".$id.", ".$tableau['rv_ou_id']." ,  '".$tableau['rv_debut']."',  '".date('Y-m-d') ."' , 1)";
			$res2 = mysqli_query($bd, $sql_insert) or bd_erreur($bd, $sql_insert);
			
			$delete = "DELETE FROM realisation_visite WHERE rv_id =".$id;
			$res_delete = mysqli_query($bd, $delete) or bd_erreur($bd, $delete);

		}
	}		

	dashboard_content($bd);

	mysqli_close($bd);
	ob_end_flush();
?>