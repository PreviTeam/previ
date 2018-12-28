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

		$sql = "DELETE FROM outil WHERE ou_mo_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM visite_attachement WHERE va_mo_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM modele WHERE mo_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id = bd_protect($bd,$_POST['id_modify']);
		$code = bd_protect($bd,$_POST['code']);
		$designation = bd_protect($bd,$_POST['designation']);
		$orga = bd_protect($bd,$_POST['orga']);
		$inactif = bd_protect($bd,$_POST['inactif']);

		$sql = "SELECT or_id FROM organisation WHERE or_designation = '".$orga."'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$sql = "UPDATE modele
				SET mo_designation='".$designation."',mo_code = '".$code."', mo_or_id = ".$tableau['or_id'].",mo_inactif=".$inactif." 
				WHERE mo_id=".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "SELECT * FROM visite_attachement,visite WHERE va_vi_id = vi_id AND va_mo_id = ".$id;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sizeModif = sizeof($_POST)-mysqli_num_rows($res)-5;
		$size = sizeof($_POST)-5;

		if($sizeModif < 0)
		{
			$tabSup = array();
			$row = array();

			for($i = 1; $i < $size+1; $i++){
				$row[] = $_POST['t'.$i];
			}

			while($tableau = mysqli_fetch_assoc($res))
			{
				if(!in_array($tableau['vi_designation'], $row))
				{
					$tabSup[] = $tableau['vi_id'];
				}
			}

			for($i = 0; $i < sizeof($tabSup); $i++)
			{
				$sql = "DELETE FROM visite_attachement WHERE va_mo_id=".$id." AND va_vi_id=".$tabSup[$i];
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

					$sql = "SELECT vi_id FROM visite WHERE vi_designation = '".$fiche."'";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	        		$tableau = mysqli_fetch_assoc($res);

	        		$sql = "INSERT INTO visite_attachement
	        				VALUES (".$tableau['vi_id'].",".$id.")";
	        		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
				}
			}
		}
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_add'])){
		$id = bd_protect($bd,$_POST['id_add']);
		$code = bd_protect($bd,$_POST['code']);
		$designation = bd_protect($bd,$_POST['designation']);
		$orga = bd_protect($bd,$_POST['orga']);
		$inactif = bd_protect($bd,$_POST['inactif']);

		$sql = "SELECT or_id FROM organisation WHERE or_designation = '".$orga."'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$sql = "INSERT INTO modele
				VALUES (".$id.",'".$code."','".$designation."',".$tableau['or_id'].",".$inactif.")";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$size = sizeof($_POST)-5;

        for($i = 1; $i < $size+1; $i++)
        {
       		$visite = bd_protect($bd,$_POST['t'.$i]);

        	$sql = "SELECT vi_id FROM visite WHERE vi_designation = '".$visite."'";
        	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
        	$tableau = mysqli_fetch_assoc($res);

        	$sql = "INSERT INTO visite_attachement
        			VALUES (".$tableau['vi_id'].",".$id.")";
        	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
        }
	}

	mysqli_close($bd);
	ob_end_flush();
?>