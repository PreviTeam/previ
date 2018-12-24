<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


//__________________________________________   CONTENU    ______________________________________________ //
	
	echo '<div class="scroller">';
	generic_top_title("../img/search.png", '');
	echo '<div> Correspondances avec : ' , $_POST['id'], '</div>';
    

	$entete=array("Id", "Désignation",);
	$bd = bd_connect();
	$insert = '"%'.bd_protect($bd, $_POST['id']).'%"';
	$sql = "SELECT vi_id, vi_designation FROM visite WHERE vi_designation LIKE ".$insert;
	$sql2 = "SELECT fi_id, fi_designation FROM fiche WHERE fi_designation LIKE ".$insert;
	$sql3 = " SELECT op_id, op_contenu FROM operation WHERE op_contenu LIKE ".$insert;
	$sql4 = " SELECT or_id, or_designation FROM organisation WHERE or_designation LIKE ".$insert;
	$sql5 = " SELECT mo_id, mo_designation FROM modele WHERE mo_designation LIKE ".$insert;
	$sql6 = " SELECT ou_id, ou_designation FROM outil WHERE ou_designation LIKE ".$insert;

	$contentVisite =array();
	$contentFiche =array();
	$contentOperation =array();
	$contentOrganisation =array();
	$contentModele =array();
	$contentOutil =array();


	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	$res2 = mysqli_query($bd, $sql2) or bd_erreur($bd, $sql2);
	$res3 = mysqli_query($bd, $sql3) or bd_erreur($bd, $sql3);
	$res4 = mysqli_query($bd, $sql4) or bd_erreur($bd, $sql4);
	$res5 = mysqli_query($bd, $sql5) or bd_erreur($bd, $sql5);
	$res6 = mysqli_query($bd, $sql6) or bd_erreur($bd, $sql6);

	while($tableau = mysqli_fetch_assoc($res6)){

		if(isset($tableau['ou_id'])){
			$ligneOutil=array($tableau['ou_id'],
					 		  $tableau['ou_designation']);

			$contentOutil[] = create_table_ligne(null, $ligneOutil);
		}
	}

	while($tableau = mysqli_fetch_assoc($res5)){
		if(isset($tableau['mo_id'])){
			$ligneModele=array($tableau['mo_id'],
					 		  $tableau['mo_designation']);

			$contentModele[] = create_table_ligne(null, $ligneModele);
		}
	}

	while($tableau = mysqli_fetch_assoc($res4)){
		if(isset($tableau['or_id'])){
			$ligneOrganisation=array($tableau['or_id'],
					 		  $tableau['or_designation']);

			$contentOrganisation[] = create_table_ligne(null, $ligneOrganisation);
		}
	}

	while($tableau = mysqli_fetch_assoc($res3)){
		if(isset($tableau['op_id'])){
			$ligneOperation=array($tableau['op_id'],
					 		  $tableau['op_contenu']);

			$contentOperation[] = create_table_ligne(null, $ligneOperation);
		}
	}

	while($tableau = mysqli_fetch_assoc($res2)){
		if(isset($tableau['fi_id'])){
			$ligneFiche=array($tableau['fi_id'],
					 		  $tableau['fi_designation']);

			$contentFiche[] = create_table_ligne(null, $ligneFiche);
		}
	}
		
	while($tableau = mysqli_fetch_assoc($res)){
		if(isset($tableau['vi_id'])){
			$ligneVisite=array($tableau['vi_id'],
					 		   $tableau['vi_designation']);

			$contentVisite[] = create_table_ligne(null, $ligneVisite);
		}
	}
	create_table($entete, $contentVisite, null, $_SESSION['ps1']);
	create_table($entete, $contentFiche, null, $_SESSION['ps2']);
	create_table($entete, $contentOperation, null, $_SESSION['ps3']);
	create_table($entete, $contentOrganisation, null, $_SESSION['eq1']);
	create_table($entete, $contentModele, null, $_SESSION['eq2']);
	create_table($entete, $contentOutil, null, $_SESSION['eq3']);

	echo '</div>';

mysqli_close($bd);
ob_end_flush();
    
?>