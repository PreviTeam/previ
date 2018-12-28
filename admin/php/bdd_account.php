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

	// Demande de Modifiction d'un élément
	if(isset($_POST['id_modify'])){
		$id = bd_protect($bd, $_POST['id_modify']);
		$ancien = bd_protect($bd, $_POST['ancien']);
		$nouveau = bd_protect($bd, $_POST['nouv']);
		$verif = bd_protect($bd, $_POST['verif']);

		if($nouveau === $verif)
		{
			$sql = "SELECT em_mdp FROM EMPLOYE WHERE em_id = ".$id;
			$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			$t = mysqli_fetch_assoc($res);

			if($t['em_mdp'] === md5($ancien))
			{
				$sql = "UPDATE EMPLOYE SET em_mdp = '".md5($nouveau)."' WHERE em_id = ".$id;
				$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
			}
		}
	}

	echo 'ok';

	mysqli_close($bd);
	ob_end_flush();
?>