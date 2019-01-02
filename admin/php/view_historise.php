<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	generic_top_title("../img/passations.jpg", 'Passations');

	$entete = array("Fiche", "Equipement", "Agent", "Debut", "Fin", "");
	$bd = bd_connect();
	$sql = "SELECT fi_designation, mo_designation, ou_code, em_code, rv_id, rf_debut, rf_fin, vi_designation, rf_id
			FROM realisation_fiche, modele, outil, employe, realisation_visite, visite_attachement, visite, fiche
			WHERE rf_em_id = em_id
			AND rf_rv_id = rv_id
			AND rv_vi_id = vi_id
			AND vi_id = va_vi_id
			AND va_mo_id = mo_id
			AND rv_ou_id = ou_id
			AND rf_fi_id = fi_id
			GROUP BY fi_id
			HAVING rv_id = ".$_POST['id'];

	$content = array();

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	while($tableau = mysqli_fetch_assoc($res)){
		$content[] = create_table_ligne(null,array($tableau['fi_designation'],
													$tableau['mo_designation'],
													$tableau['em_code'],
													$tableau['rf_debut'],
													$tableau['rf_fin'],
													'<button id="'. $tableau['rf_id'] .'" class="btn btn-link ajaxphplink" href="historise_view_fiche.php">Télécharger</button>',));
		$titre = $tableau['vi_designation']." - ".$tableau['mo_designation'];
	}

	create_table($entete, $content, null, $titre);

	mysqli_close($bd);
	ob_end_flush();
?>