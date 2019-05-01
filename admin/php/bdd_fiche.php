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

		$sql = "DELETE FROM COMPO_VISITE WHERE cv_fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM HISTO_REALISATION_FICHE WHERE h_rf_fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM REALISATION_FICHE WHERE rf_fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM COMPO_FICHE WHERE cf_fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM FICHE WHERE fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id=bd_protect($bd, $_POST['id_modify']);
		$designation = bd_protect($bd, $_POST['designation']);
		$version = bd_protect($bd, $_POST['version']);
		$inactif = bd_protect($bd, $_POST['inactif']);

		$sql = "UPDATE FICHE
				SET fi_designation='".$designation."',fi_num_vers='".$version."',fi_inactif=".$inactif." 
				WHERE fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "SELECT * FROM COMPO_FICHE, OPERATION WHERE cf_op_id = op_id AND cf_fi_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$size = sizeof($_POST)-4;

		$bddRow = array();

		while($tableau = mysqli_fetch_assoc($res))
		{
			$bddRow[] = $tableau["op_contenu"];
		}

		$row = array();

		for($i = 1; $i < $size+1; $i++){
			$row[] = $_POST['t'.$i];
		}

		$add = getTab($row,$bddRow);
		$sup = getTab($bddRow,$row);

		if(!empty($sup))
		{
			foreach($sup as $value)
			{
				$sql = "SELECT op_id FROM OPERATION WHERE op_contenu = '".$bddRow[$value]."'";
	        	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	        	$tableau = mysqli_fetch_assoc($res);

				$sql = "DELETE FROM COMPO_FICHE WHERE cf_fi_id = ".$id." AND cf_op_id = ".$tableau['op_id'];
				$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			}
		}

		if(!empty($add))
		{
			foreach($add as $value)
			{
				$sql = "SELECT op_id FROM OPERATION WHERE op_contenu = '".$row[$value]."'";
	        	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	        	$tableau = mysqli_fetch_assoc($res);

				$sql = "INSERT INTO compo_fiche
	        			VALUES (".$id.",".$tableau['op_id'].",0)";
				$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			}
		}

		

		$sql = "SELECT * FROM COMPO_FICHE, OPERATION WHERE cf_op_id = op_id AND cf_fi_id = ".$id." ORDER BY cf_ordre";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		while($tableau = mysqli_fetch_assoc($res))
		{
			for($i = 0; $i < $size; $i++)
			{
				if($row[$i] === $tableau['op_contenu'])
				{
					$sql = "UPDATE COMPO_FICHE
							SET cf_ordre = ".($i+1)." 
							WHERE cf_fi_id=".$id." AND cf_op_id=".$tableau['op_id'];
					$res2 = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
				}
			}
		}	
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_add'])){

    	$designation = bd_protect($bd,$_POST['designation']);
    	$version = bd_protect($bd,$_POST['version']);
    	$inactif = bd_protect($bd,$_POST['inactif']);

    	$sql = "INSERT INTO FICHE (fi_designation, fi_num_vers, fi_inactif)
    			VALUES ('".$designation."','".$version."',".$inactif.")";
    	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	$id = mysqli_insert_id($bd);
    	$size = sizeof($_POST)-4;

    	for($i = 1; $i < $size+1; $i++)
    	{
    		$operation = bd_protect($bd,$_POST['t'.$i]);

    		$sql = "SELECT op_id FROM OPERATION WHERE op_contenu = '".$operation."'";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    		$tableau = mysqli_fetch_assoc($res);

    		$sql = "INSERT INTO COMPO_FICHE
    				VALUES (".$id.",".$tableau['op_id'].",".$i.")";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	}
        
	}

	echo 'ok';

	mysqli_close($bd);
	ob_end_flush();

	function getTab($tab1, $tab2)
	{
		$res = array();

		foreach ($tab1 as $key => $value) {
			if(!in_array($value, $tab2))
			{
				$res[] = $key; 
			}
		}

		return $res;
	}
?>