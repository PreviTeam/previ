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

		$sql = "DELETE FROM visite_attachement WHERE va_vi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM realisation_visite WHERE rv_vi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM compo_visite WHERE cv_vi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM visite WHERE vi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id=bd_protect($bd, $_POST['id_modify']);
		$designation = bd_protect($bd, $_POST['designation']);
		$version = bd_protect($bd, $_POST['version']);
		$inactif = bd_protect($bd, $_POST['inactif']);

		$sql = "UPDATE visite
				SET vi_designation='".$designation."',vi_num_vers='".$version."',vi_inactif=".$inactif." 
				WHERE vi_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "SELECT * FROM compo_visite,fiche WHERE cv_fi_id = fi_id AND cv_vi_id = ".$id;
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
				if(!in_array($tableau['fi_designation'], $row))
				{
					$tabSup[] = $tableau['fi_id'];
				}
			}

			for($i = 0; $i < sizeof($tabSup); $i++)
			{
				$sql = "DELETE FROM compo_visite WHERE cv_vi_id=".$id." AND cv_fi_id=".$tabSup[$i];
				$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			}
		}
		else if($sizeModif > 0)
		{
			for($i = 1; $i < $size+1; $i++)
			{
				if($i >= $size+1-$sizeModif)
				{
					$fiche = bd_protect($bd,$_POST['t'.$i]);

					$sql = "SELECT fi_id FROM fiche WHERE fi_designation = '".$fiche."'";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	        		$tableau = mysqli_fetch_assoc($res);

	        		$sql = "INSERT INTO compo_visite 
	        				VALUES (".$id.",".$tableau['fi_id'].")";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
				}
			}
		}
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_add'])){

    	$designation = bd_protect($bd,$_POST['designation']);
    	$version = bd_protect($bd,$_POST['version']);
    	$inactif = bd_protect($bd,$_POST['inactif']);

    	$sql = "INSERT INTO visite (vi_designation, vi_num_vers, vi_type, vi_inactif)
    			VALUES ('".$designation."','".$version."',0,".$inactif.")";
    	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	$id = mysqli_insert_id($bd);
    	$size = sizeof($_POST)-4;

    	for($i = 1; $i < $size+1; $i++)
    	{
    		$fiche = bd_protect($bd,$_POST['t'.$i]);

    		$sql = "SELECT fi_id FROM fiche WHERE fi_designation = '".$fiche."'";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    		$tableau = mysqli_fetch_assoc($res);

    		$sql = "INSERT INTO compo_visite 
    				VALUES (".$id.",".$tableau['fi_id'].")";
    		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    	}
     
	}

	echo 'ok';

	mysqli_close($bd);
	ob_end_flush();
?>