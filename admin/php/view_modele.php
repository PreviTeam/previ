<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page view_modele
	###################################################################*/

	$entete=array("Id", "Code", "Designation", $_SESSION['eq1'], $_SESSION['ps1'],);
	$bd = bd_connect();
	

	$sql = "SELECT *
			FROM ORGANISATION, MODELE LEFT OUTER JOIN VISITE_ATTACHEMENT ON va_mo_id = mo_id LEFT OUTER JOIN VISITE ON va_vi_id = vi_id
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
	echo '<div class="bloc-btn">',
			'<button type="button" class="btn btn-primary ajaxphplink" href="modele.php">RETOUR</button>', 
            ' <button type="button" id="'.$id.'" class="btn btn-modal btn-success"  data-toggle="modal" href="modify_modele.php" data-target="#ModifyModal">MODIFIER</button>',
          '</div>';

    // Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER, 'modele');
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>