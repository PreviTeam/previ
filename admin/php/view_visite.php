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

	$sql = "SELECT DISTINCT fi_id, vi_id, mo_designation, fi_designation, vi_designation, vi_num_vers 
			FROM  compo_visite, fiche, visite LEFT OUTER JOIN visite_attachement ON va_vi_id = vi_id LEFT OUTER JOIN modele ON va_mo_id = mo_id
			WHERE vi_id = cv_vi_id
			AND cv_fi_id = fi_id
			AND vi_id=".$_POST['id'];
			
	$content = array();
	$fiche  =  '';
	$modeles = '';
	$lastFiche = '';
	$lastModel = '';
	$firstFiche='';
	$id="Aucune Fiche dans cette visite";
	$designation= '';
	$version='';


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
	create_table($entete, $content, null, $designation);

	echo '<div>',
			'<button type="button" class="btn btn-primary ajaxphplink" href="visite.php">RETOUR</button>', 
            ' <button type="button" id="'.$id.'" class="btn btn-modal btn-success"  data-toggle="modal" href="modify_visite.php" data-target="#ModifyModal">MODIFIER</button>',
          '</div>';

    // Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER);
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>