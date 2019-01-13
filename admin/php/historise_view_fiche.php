<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	generic_top_title("../img/passations.jpg", 'Passations');

	$bd = bd_connect();
	$sql = "SELECT *
			FROM HISTO_REALISATION_OPERATION, HISTO_REALISATION_FICHE, OPERATION, COMPO_FICHE, FICHE, MODELE, VISITE_ATTACHEMENT, HISTO_REALISATION_VISITE, OUTIL
			WHERE h_rf_id = h_ro_rf_id
			AND h_ro_op_id = op_id
			AND op_id = cf_op_id
			AND h_rf_fi_id = cf_fi_id 
			AND fi_id = h_rf_fi_id
			AND h_rf_rv_id = h_rv_id
			AND h_rv_vi_id = va_vi_id
			AND mo_id = va_mo_id
			AND h_rv_ou_id = ou_id
			AND h_rf_id = ".$_POST['id']."
			GROUP BY cf_ordre
			ORDER BY cf_ordre";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	$content = array();
	$titre = '';

	while($tableau = mysqli_fetch_assoc($res)){
		$content[] = create_fiche_ligne(array($tableau['cf_ordre'].". ".$tableau['op_contenu'],$tableau['h_ro_res']));

		$titre = $tableau['fi_designation']." - ".$tableau['mo_designation']." ".$tableau['ou_code'];
	}

	create_fiche($content,$titre);

	echo '<form method="post" action="fiche_pdf.php" target="_blank">',
			'<input type="hidden" name="sql" value="',$sql,'">',
			'<input type="submit" name="button" class="btn btn-primary" value="Télécharger">',
		'</form>';
?>