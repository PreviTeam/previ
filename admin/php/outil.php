<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
	echo '<div class="scroller">';						
	$entete=array("ID", "Code", "Designation", '');

	$bd = bd_connect();
	$sql = "SELECT *
			FROM outil";
	$content =array();



	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){

		$ligne=array($tableau['ou_id'], 
					 $tableau['ou_code'],  
					 $tableau['ou_designation'],  
					'<button type="button" id="'.$tableau['ou_id'].'" class="btn btn-modal btn-link" data-toggle="modal" href="modify_outil.php" data-target="#ModifyModal">Modifier</button>');
		$content[] = create_table_ligne(null, $ligne);
	}
	create_table($entete, $content, null, "Outils");

	echo '</div>',
		'<div class="adder">',
			'<a id="add" href="modify_outil.php" data-toggle="modal" data-target="#AddModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
		'</div>';
	

	// Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER);
	modal_start(NOUVEAU);
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>