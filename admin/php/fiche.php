<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 

	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

						
	echo '<div class="scroller scrollbar" >';
	generic_top_title("../img/Admin.png", "Administration");	
	$entete=array("Code Visite", "Désignation", "Versions", '', '');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM FICHE";
	$content =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['fi_id'],
					 $tableau['fi_designation'], 
					 $tableau['fi_num_vers'], 
					  '<button id="'. $tableau['fi_id'] .'" class="btn btn-link ajaxphplink" href="view_fiche.php">Voir</button>',
					'<button type="button" id="'.$tableau['fi_id'].'" class="btn btn-modal btn-link" data-toggle="modal" href="modify_fiche.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}

	echo '<div class="alert alert-success alert-dismissible fade show" role="alert">',
  			'Modifications réalisées avec Succès !',
			'</div>';
	create_table($entete, $content, null, $_SESSION['ps2']);

	echo '</div>',
		'<div class="adder">',
			'<a id="add" href="modify_fiche.php" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
		'</div>';
	
	// Ajout des fenêtres modales
	modal_start(MODIFIER, 'fiche');
	modal_start(NOUVEAU, 'fiche');
	modal_select();


	mysqli_close($bd);
	ob_end_flush();
?>