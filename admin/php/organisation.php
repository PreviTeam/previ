<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("ID", "Code", "Designation", '', '');

	$bd = bd_connect();
	$sql = "SELECT *
			FROM organisation";
	$content =array();



	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['or_id'], 
					 $tableau['or_code'],  
					 $tableau['or_designation'], 
					 'Voir', 
					'<button type="button" id="', $tableau['or_id'] ,'" class="btn btn-link" data-toggle="modal" href="modify_organisation.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Organisations");

	echo '<div class="adder">',
			'<a  href="#" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
			'</div>';
	

	// Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start('Modify');
	modal_start('Add');

	mysqli_close($bd);
	ob_end_flush();


?>