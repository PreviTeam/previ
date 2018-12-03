<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Code Visite", "Désignation", "Vesions", '', '');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM visite";
	$content =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['vi_id'],
					 $tableau['vi_designation'], 
					 $tableau['vi_num_vers'], 
					 '<a id="'. $tableau['vi_id'] .'" class="btn btn-link ajaxphplink" href="view_visite.php">Voir</a>',
					'<button type="button" id="'. $tableau['vi_id'] .'" class="btn btn-modal btn-link" data-toggle="modal" href="modify_visite.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Visites");

	echo '<div class="adder">',
			'<a  id="add" href="modify_visite.php" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
			'</div>';
	
	// Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start('Modify');
	modal_start('Add');


	
	ob_end_flush();


?>