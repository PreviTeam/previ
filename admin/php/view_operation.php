<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Id", "Contenu", "Type", "EPI");
	$bd = bd_connect();
	$sql = "SELECT *
			FROM operation LEFT OUTER JOIN compo_operation ON co_op_id = op_id LEFT OUTER JOIN epi ON co_epi_id = epi_id
			WHERE op_id=".$_POST['id'];
	$content = array();
	$epi = '';

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	while($tableau = mysqli_fetch_assoc($res)){
		$id=entities_protect($tableau['op_id']);
		$designation = entities_protect($tableau['op_contenu']);

		if((int)$tableau['op_type'] === 1){
			$type = "Oui / Non";
		}else if((int)$tableau['op_type'] === 2){
			$type = "Texte";
		}
		else{
			$type = "Type non reconnu";
		}

		if($tableau['epi_designation'] != null){
			$epi.= $tableau['epi_designation'].'<br>';
		}
		
	}
	$ligne=array($id,
		    	 $designation,
				 $type,
				 $epi);
	$content[] = create_table_ligne(null, $ligne);
	
	create_table($entete, $content, null, $designation);

	echo '<div>',
			'<button type="button" class="btn btn-primary ajaxphplink" href="operation.php">RETOUR</button>', 
            ' <button type="button" id="'.$id.'" class="btn btn-modal btn-success"  data-toggle="modal" href="modify_operation.php" data-target="#ModifyModal">MODIFIER</button>',
          '</div>';

    // Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER);
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>