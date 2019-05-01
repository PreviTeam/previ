<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page visite
	###################################################################*/


	echo '<div class="scroller">';	
	generic_top_title("../img/Admin.png", "Administration");					
	$entete=array("Code", "Désignation", "Version", '', '');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM VISITE";
	$content =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['vi_id'],
					 $tableau['vi_designation'], 
					 $tableau['vi_num_vers'], 
					 '<button id="'. $tableau['vi_id'] .'" class="btn btn-link ajaxphplink" href="view_visite.php">Voir</button>',
					'<button type="button" id="'. $tableau['vi_id'] .'" class="btn btn-modal btn-link" data-toggle="modal" href="modify_visite.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}

	echo '<div class="alert alert-success alert-dismissible fade show" role="alert">',
  			'Modifications réalisées avec Succès !',
			'</div>',
		  '<div class="alert alert-danger alert-dismissible fade show" role="alert">',
  			'Erreur lors de la modification, vérifiez les champs saisis !',
			'</div>';
	create_table($entete, $content, null,  $_SESSION['ps1']);

	echo '</div>',
		'<div class="adder">',
			'<a id="add" href="modify_visite.php" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
		'</div>';
	
	// Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER, 'visite');
	modal_start(NOUVEAU, 'visite');
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>