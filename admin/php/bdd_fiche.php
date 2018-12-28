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

		$sql = "DELETE FROM compo_visite WHERE cv_fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM realisation_fiche WHERE rf_fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM compo_fiche WHERE cf_fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM fiche WHERE fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id=bd_protect($bd, $_POST['id_modify']);
		$designation = bd_protect($bd, $_POST['designation']);
		$version = bd_protect($bd, $_POST['version']);
		$inactif = bd_protect($bd, $_POST['inactif']);

		$sql = "UPDATE fiche
				SET fi_designation='".$designation."',fi_num_vers='".$version."',fi_inactif=".$inactif." 
				WHERE fi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "SELECT * FROM compo_fiche,operation WHERE cf_op_id = op_id AND cf_fi_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sizeModif = sizeof($_POST)-mysqli_num_rows($res)-4;
		$size = sizeof($_POST)-4;
		$row = array();

		for($i = 1; $i < $size+1; $i++){
			$row[] = $_POST['t'.$i];
		}

		if($sizeModif < 0)
		{
			$tabSup = array();

			while($tableau = mysqli_fetch_assoc($res))
			{
				if(!in_array($tableau['op_contenu'], $row))
				{
					$tabSup[] = $tableau['op_id'];
				}
			}

			for($i = 0; $i < sizeof($tabSup); $i++)
			{
				$sql = "DELETE FROM compo_fiche WHERE cf_fi_id=".$id." AND cf_op_id=".$tabSup[$i];
				$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			}
		}
		else if($sizeModif > 0)
		{
			for($i = 1; $i < $size+1; $i++)
			{
				if($i >= $size+1-$sizeModif)
				{
					$operation = bd_protect($bd,$_POST['t'.$i]);

					$sql = "SELECT op_id FROM operation WHERE op_contenu = '".$operation."'";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	        		$tableau = mysqli_fetch_assoc($res);

	        		$sql = "INSERT INTO compo_fiche
	        				VALUES (".$id.",".$tableau['op_id'].",".$i.")";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
				}
			}
		}

		$sql = "SELECT * FROM compo_fiche,operation WHERE cf_op_id = op_id AND cf_fi_id = ".$id." ORDER BY cf_ordre";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		while($tableau = mysqli_fetch_assoc($res))
		{
			for($i = 0; $i < $size; $i++)
			{
				if($row[$i] === $tableau['op_contenu'])
				{
					$sql = "UPDATE compo_fiche
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

    	$sql = "INSERT INTO fiche (fi_designation, fi_num_vers, fi_inactif )
    			VALUES ('".$designation."','".$version."',".$inactif.")";
    	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	$id = mysqli_insert_id($bd);
    	$size = sizeof($_POST)-4;

    	for($i = 1; $i < $size+1; $i++)
    	{
    		$operation = bd_protect($bd,$_POST['t'.$i]);

    		$sql = "SELECT op_id FROM operation WHERE op_contenu = '".$operation."'";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    		$tableau = mysqli_fetch_assoc($res);

    		$sql = "INSERT INTO compo_fiche
    				VALUES (".$id.",".$tableau['op_id'].",".$i.")";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	}
        
	}

	mysqli_close($bd);
	ob_end_flush();
?>