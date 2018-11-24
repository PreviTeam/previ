<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Utilisateurs
	###################################################################*/

	// ------------------------ Contenu de la Page -----------------------------------------------//
	$entete=array("Code Utilisateur", "Nom", "Prénom", "Status", '' ,'');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM employe";
	$content =array();

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	$memoire = array(); // mémorisation pour la modification ulterieure
	$i = 0;

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
		$ligne=array($tableau['em_code'], 
					$tableau['em_nom'], 
					$tableau['em_prenom'], 
					$status, 'Voir', 
					'<button type="button" id="', $tableau['em_code'] ,'" class="btn btn-link" data-toggle="modal" href="user_modify.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);

		$ligne[] += $i;
		$memoire[] = $ligne;
		
		$i++;
	}

	create_table($entete, $content, null, "Utilisateurs");
	echo '<div class="adder">',
			'<a  href="#" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
			'</div>';

	// Ajout des fenêtres modales
	modal_start(MODIFIER);
	modal_start(NOUVEAU);

	mysqli_close($bd);
	ob_end_flush();

	
?>