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

		$sql = "SELECT em_id FROM employe WHERE em_code = '".$id."'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$sql = "DELETE FROM realisation_fiche WHERE rf_em_id=".$tableau['em_id'];
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$sql = "DELETE FROM employe WHERE em_code= '".$id."'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}
	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id=bd_protect($bd, $_POST['id_modify']);
		$prenom = bd_protect($bd,$_POST['prenom']);
        $nom = bd_protect($bd,$_POST['nom']);
        $inactif = bd_protect($bd,$_POST['inactif']);
        $status = strtoupper($_POST['select']);

        $sql = '';
        
        if(!empty($_POST['pass']))
        {
        	$pass = bd_protect($bd,$_POST['pass']);
        	$pass = md5($pass);
        	$sql = "UPDATE employe
        			SET em_prenom = '".$prenom."', em_nom = '".$nom."', em_status = '".$status."', em_mdp = '".$pass."', em_inactif = ".$inactif."
        			WHERE em_code = '".$id."'";
        }
        else
        {
        	$sql = "UPDATE employe
        			SET em_prenom = '".$prenom."', em_nom = '".$nom."', em_status = '".$status."', em_inactif = ".$inactif."
        			WHERE em_code = '".$id."'";
        }

        $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	}

	// Demande D'ajout D'un élément
	if(isset($_POST['id_add'])){
		$id=bd_protect($bd, $_POST['id_add']);
		$sql = "SELECT * FROM employe WHERE em_id = ".$id;

		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		if(mysqli_num_rows($res) == 1){
            mysqli_free_result($res);
        }
        else{
        	$prenom = bd_protect($bd,$_POST['prenom']);
        	$nom = bd_protect($bd,$_POST['nom']);
        	$inactif = bd_protect($bd,$_POST['inactif']);
        	$status = strtoupper($_POST['select']);
        	$pass = bd_protect($bd,$_POST['pass']);
        	$pass = md5($pass);

        	$sql = "INSERT INTO employe (em_code, em_prenom, em_nom, em_status, em_mdp, em_inactif)
        			VALUES (".$id.",'".$prenom."','".$nom."','".$status."','".$pass."',".$inactif.")";
        	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
        }
	}

	mysqli_close($bd);
	ob_end_flush();
?>