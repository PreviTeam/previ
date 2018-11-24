<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Code Opération", "Contenu", "Demande", '', '');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM operation";
	$content =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$demande = null;
		switch($tableau['op_type']){
			case 0:
				$demande = 'Oui / Non';
				break;
			case 1:
				$demande= 'Texte';
				break;
		}

		$ligne=array($tableau['op_id'],  
					 $tableau['op_contenu'], 
					 $demande, 
					 'Voir', 
					'<button type="button" id="', $tableau['op_id'] ,'" class="btn btn-link" data-toggle="modal" href="modify_operation.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Opérations");

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