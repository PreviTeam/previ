<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page Operation
	###################################################################*/

	
	echo '<div class="scroller">';	
	generic_top_title("../img/Admin.png", "Administration");					
	$entete=array("Code Opération", "Contenu", "Demande", '', '');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM operation";
	$content =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$demande = null;
		switch($tableau['op_type']){
			case 1:
				$demande = 'Oui / Non';
				break;
			case 2:
				$demande= 'Texte';
				break;
		}

		$ligne=array($tableau['op_id'],  
					 $tableau['op_contenu'], 
					 $demande, 
					'<button id="'. $tableau['op_id'] .'" class="btn btn-link ajaxphplink" href="view_operation.php">Voir</button>',
					'<button type="button" id="'.$tableau['op_id'].'" class="btn btn-modal btn-link" data-toggle="modal" href="modify_operation.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Opérations");

	echo '</div>',
		'<div class="adder">',
			'<a id="add" href="modify_operation.php" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
		'</div>';

	// Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER);
	modal_start(NOUVEAU);
	modal_select();

	mysqli_close($bd);
	ob_end_flush();

?>