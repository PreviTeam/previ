<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	// ------------------------ Contenu de la Page -----------------------------------------------//
	$entete=array("Code Utilisateur", "Nom", "Prénom", "Status", '' ,'');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM employe";
	$content =array();

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){
		$status = null;
		switch($tableau['em_status']){
			case 0:
				$status = 'Admin';
				break;
			case 1:
				$status= 'Chef-Equipe';
				break;
			case 2 : 
				$status= 'Technicien';
				break;
		}
		$ligne=array($tableau['em_code'], $tableau['em_nom'], $tableau['em_prenom'], $status, 'Voir', '<a href="user_modify.php" class="modal-link">Modifier</a>');
		$content[] = create_table_ligne(null, $ligne);
	}

	create_table($entete, $content, null, "Utilisateurs");

	// ------------------------ Contenu Modal -----------------------------------------------//

	// Conteneur des fenêtres popup Ajout / Mofification de l'utilisateur
	// Le contenu de ces fenêtres sont chargés dynamiquement via Ajax
	echo 
	'<div id="modal">',
	'</div>';
	
	mysqli_close($bd);
	ob_end_flush();
?>