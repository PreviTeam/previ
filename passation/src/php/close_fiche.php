<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

/*#########################################################################
			Mofification d'une réalisation_fiche comme terminée
###########################################################################*/
	$bd = bd_connect();

	$id_rf = bd_protect($bd, $_POST['rf_id']);

	// Modification de la réalisation_fiche pour passer à l'état terminé
	$sql = "UPDATE realisation_fiche
            SET rf_etat = 1, rf_fin = '".date('Y-m-d') ."'
            WHERE rf_id = ".$id_rf;
    $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);


    $nbFichesEnCours = get_nb_fiches_en_cours($bd, $id_rf);

    // Si la visite n'as plus de fiche en cours, alors on clos la visite
    if( $nbFichesEnCours[0] === 0){
      $sql4 = "UPDATE realisation_visite
                SET rv_etat = 1, rv_fin = '".date('Y-m-d') ."'
                WHERE rv_id = ".$nbFichesEnCours[1] ;
      $res4 = mysqli_query($bd, $sql4) or bd_erreur($bd, $sql4);
    }

    dashboard_content($bd);

	mysqli_close($bd);
	ob_end_flush();
?>