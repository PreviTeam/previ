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

		$sql = "DELETE FROM compo_fiche WHERE cf_op_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM REALISATION_OPERATION WHERE ro_op_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM compo_operation WHERE co_op_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM operation WHERE op_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id=bd_protect($bd, $_POST['id_modify']);
		$contenu = bd_protect($bd, $_POST['contenu']);
		$select = ($_POST['select'] === "texte")? 2 : 1;
		$inactif = bd_protect($bd, $_POST['inactif']);

		$sql = "UPDATE operation
				SET op_contenu='".$contenu."',op_type='".$select."',op_inactif=".$inactif." 
				WHERE op_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "SELECT * FROM compo_operation,epi WHERE co_epi_id = epi_id AND co_op_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sizeModif = sizeof($_POST)-mysqli_num_rows($res)-4;
		$size = sizeof($_POST)-4;

		if($sizeModif < 0)
		{
			$tabSup = array();
			$row = array();

			for($i = 1; $i < $size+1; $i++){
				$row[] = $_POST['t'.$i];
			}

			while($tableau = mysqli_fetch_assoc($res))
			{
				if(!in_array($tableau['epi_designation'], $row))
				{
					$tabSup[] = $tableau['epi_id'];
				}
			}

			for($i = 0; $i < sizeof($tabSup); $i++)
			{
				$sql = "DELETE FROM compo_operation WHERE co_op_id=".$id." AND co_epi_id=".$tabSup[$i];
				$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			}
		}
		else if($sizeModif > 0)
		{
			for($i = 1; $i < $size+1; $i++)
			{
				if($i >= $size+1-$sizeModif)
				{
					$epi = bd_protect($bd,$_POST['t'.$i]);

					$sql = "SELECT epi_id FROM epi WHERE epi_designation = '".$epi."'";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	        		$tableau = mysqli_fetch_assoc($res);

	        		$sql = "INSERT INTO compo_operation 
	        				VALUES (".$id.",".$tableau['epi_id'].")";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
				}
			}
		}
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_add'])){

    	$contenu = bd_protect($bd,$_POST['contenu']);
    	$select = ($_POST['select'] === 'texte')? 2 : 1;
    	$inactif = bd_protect($bd,$_POST['inactif']);

    	$sql = "INSERT INTO operation (op_contenu, op_type)
    			VALUES ('".$contenu."',".$select.")";
    	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	$id = mysqli_insert_id($bd);

    	$size = sizeof($_POST)-4;

    	for($i = 1; $i < $size+1; $i++)
    	{
    		$epi = bd_protect($bd,$_POST['t'.$i]);

    		$sql = "SELECT epi_id FROM epi WHERE epi_designation = '".$epi."'";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    		$tableau = mysqli_fetch_assoc($res);

    		$sql = "INSERT INTO compo_operation
    				VALUES (".$id.",".$tableau['epi_id'].")";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	}
        
	}

	mysqli_close($bd);
	ob_end_flush();
?>