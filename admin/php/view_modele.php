<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Id", "Code", "Designation", "Organisation", "Visites",);
	$bd = bd_connect();
	

	$sql = "SELECT *
			FROM organisation, modele LEFT OUTER JOIN visite_attachement ON va_mo_id = mo_id LEFT OUTER JOIN visite ON va_vi_id = vi_id
			WHERE or_id = mo_or_id
            AND mo_id=".$_POST['id'];

    $visites = '';
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	while($tableau = mysqli_fetch_assoc($res)){

		$id= $tableau['mo_id'];
		$code = $tableau['mo_code'];
		$designation = $tableau['mo_designation'];
		$organisation = $tableau['or_designation'];
		if($tableau['vi_designation'] != null)
			$visites .= $tableau['vi_designation'].'<br>';
	}

	$ligne=array($id,
				$code, 
				$designation,
				$organisation, 
				$visites);
	$content[] = create_table_ligne(null, $ligne);
	create_table($entete, $content, null, $designation);
	echo '<div>',
			'<button type="button" class="btn btn-primary ajaxphplink" href="modele.php">RETOUR</button>', 
            ' <button type="button" id="'.$id.'" class="btn btn-modal btn-success"  data-toggle="modal" href="modify_modele.php" data-target="#ModifyModal">MODIFIER</button>',
          '</div>';

    // Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER);
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>