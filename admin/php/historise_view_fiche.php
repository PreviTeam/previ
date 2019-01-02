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
			FROM REALISATION_OPERATION, REALISATION_FICHE, OPERATION, COMPO_FICHE, FICHE, MODELE, VISITE_ATTACHEMENT, REALISATION_VISITE, OUTIL
			WHERE rf_id = ro_rf_id
			AND ro_op_id = op_id
			AND op_id = cf_op_id
			AND rf_fi_id = cf_fi_id 
			AND fi_id = rf_fi_id
			AND rf_rv_id = rv_id
			AND rv_vi_id = va_vi_id
			AND mo_id = va_mo_id
			AND rv_ou_id = ou_id
			AND rf_id = ".$_POST['id']."
			GROUP BY cf_ordre
			ORDER BY cf_ordre";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	$content = array();

	while($tableau = mysqli_fetch_assoc($res)){
		$content[] = create_fiche_ligne(array($tableau['cf_ordre'].". ".$tableau['op_contenu'],$tableau['ro_res']));

		$titre = $tableau['fi_designation']." - ".$tableau['mo_designation']." ".$tableau['ou_code'];
	}

	create_fiche($content,$titre);

	echo '<form method="post" action="fiche_pdf.php" target="_blank">',
			'<input type="hidden" name="sql" value="',$sql,'">',
			'<input type="submit" name="button" class="btn btn-primary" value="Télécharger">',
		'</form>';
?>