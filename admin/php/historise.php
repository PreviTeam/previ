<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 

	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page Historise
	###################################################################*/


	echo '<div class="scroller">';			
	generic_top_title("../img/passations.jpg", 'Passations');			
	$entete=array("Visite", "Equipement", "Code" ,"Début", "Fin", '');
	$content= array();

	$bd = bd_connect();
	$sql = "select h_rv_id, vi_designation, mo_designation, em_code, ou_code, h_rv_debut, h_rv_fin
			from histo_realisation_visite, histo_realisation_fiche, visite, modele, visite_attachement, employe, outil
			where h_rv_id = h_rf_rv_id 
			and vi_id = h_rv_vi_id 
			and va_vi_id = vi_id
			and mo_id = va_mo_id
			and em_id = h_rf_em_id
			and ou_id = h_rv_ou_id
			and h_rv_etat = true
			group by vi_id";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	while($tableau = mysqli_fetch_assoc($res)){
		$content[] = create_table_ligne(null, array($tableau['vi_designation'],
													$tableau['mo_designation']." - ".$tableau['ou_code'],
													$tableau['em_code'],
													date_html($tableau['h_rv_debut']),
													date_html($tableau['h_rv_fin']),
													'<button id="'. $tableau['h_rv_id'] .'" class="btn btn-link ajaxphplink" href="view_historise.php">Voir</button>',));
	}

	if(empty($content))
			$content[] = create_table_ligne(null, array("Rien a afficher"));
	create_table($entete, $content, null, "Historisées");
	echo '</div>';

	mysqli_close($bd);
	ob_end_flush();

	function date_html($date)
	{
		$tab = explode("-",$date);
		return $tab[2]."/".$tab[1]."/".$tab[0];
	}
?>