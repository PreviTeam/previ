<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page Utilisateurs
	###################################################################*/

	echo '<div class="scroller">';			
	generic_top_title("../img/Admin.png", "Administration");							
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
			case 'ADMIN':
				$status = 'Admin';
				break;
			case 'CE':
				$status= 'Chef-Equipe';
				break;
			case 'TECH' : 
				$status= 'Technicien';
				break;
		}
		$ligne=array($tableau['em_code'], 
					$tableau['em_nom'], 
					$tableau['em_prenom'], 
					$status, 
					'<button id="'. $tableau['em_id'] .'" class="btn btn-link ajaxphplink" href="view_user.php">Voir</button>',
					'<button type="button" id="'.$tableau['em_code'].'" class="btn btn-modal btn-link" data-toggle="modal" href="user_modify.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);

		$ligne[] += $i;
		$memoire[] = $ligne;
		
		$i++;
	}

	echo '<div class="alert alert-success alert-dismissible fade show" role="alert">',
  			'Modifications réalisées avec Succès !',
			'</div>',
		  '<div class="alert alert-danger alert-dismissible fade show" role="alert">',
  			'Erreur lors de la modification, vérifiez les champs saisis !',
			'</div>';
	create_table($entete, $content, null, "Utilisateurs");
	echo '</div>',
		'<div class="adder">',
			'<a id="add" href="user_modify.php" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
		'</div>';

	// Ajout des fenêtres modales
	modal_start(MODIFIER, 'user');
	modal_start(NOUVEAU, 'user');


	mysqli_close($bd);
	ob_end_flush();

	
?>