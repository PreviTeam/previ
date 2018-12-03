<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Code Visite", "Désignation", "Fiches", "Vesions", "Modeles", '', '');
	$bd = bd_connect();
	$sql = "SELECT *
			FROM visite, compo_visite, fiche, visite_attachement LEFT OUTER JOIN modele ON va_mo_id = mo_id
			WHERE vi_id = cv_vi_id
			AND va_vi_id = vi_id
			AND cv_fi_id = fi_id
			AND vi_id=".$_POST['id'];
	$content = array();
	$fiche  =  '';
	$modeles = '';
	$lastFiche = '';
	$lastModel = '';
	$firstFiche='';

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	while($tableau = mysqli_fetch_assoc($res)){

		if($tableau['fi_id'] === $firstFiche){
			$modeles .=  $tableau['mo_designation'].'<br>';
		}
			

		if($firstFiche === '' ){
			$firstFiche = $tableau['fi_id'];
			$modeles .=  $tableau['mo_designation'].'<br>';
		}
		
		if($tableau['fi_designation'] != $lastFiche){
			$fiche.= $tableau['fi_designation'].'<br>';
			$lastFiche = $tableau['fi_designation'];
		}

		$id = $tableau['vi_id'];
		$designation = $tableau['vi_designation'];
		$version = $tableau['vi_num_vers'];
	}
	$ligne=array($id,
				$designation, 
				$fiche,
				$version, 
				$modeles);
	$content[] = create_table_ligne(null, $ligne);
	create_table($entete, $content, null, "Visites");

	echo '<div>',
			'<button type="button" class="btn btn-primary ajaxphplink" href="modify_visite.php">RETOUR</button>', 
            ' <button type="button" id="'.$id.'" class="btn btn-modal btn-success"  data-toggle="modal" href="modify_visite.php" data-target="#ModifyModal">MODIFIER</button>',
          '</div>';

    // Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start('Modify');

	mysqli_close($bd);
	ob_end_flush();
?>