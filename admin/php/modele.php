<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 

	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page Modèle
	###################################################################*/

						
	echo '<div class="scroller">';		
	generic_top_title("../img/equipement.jpg", "Equipements");					
	$entete=array("ID", "Code", "Designation", '', '');

	$bd = bd_connect();
	$sql = "SELECT *
			FROM MODELE";
	$content =array();



	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['mo_id'], 
					 $tableau['mo_code'],  
					 $tableau['mo_designation'], 
					 '<button id="'. $tableau['mo_id'] .'" class="btn btn-link ajaxphplink" href="view_modele.php">Voir</button>', 
					 '<button type="button" id="'.$tableau['mo_id'].'" class="btn btn-modal btn-link" data-toggle="modal" href="modify_modele.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}

	echo '<div class="alert alert-success alert-dismissible fade show" role="alert">',
  			'Modifications réalisées avec Succès !',
			'</div>';
	create_table($entete, $content, null,  $_SESSION['eq2']);

	echo '</div>',
		'<div class="adder">',
			'<a id="add" href="modify_modele.php" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
		'</div>';
	

	// Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER, 'modele');
	modal_start(NOUVEAU, 'modele');
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>