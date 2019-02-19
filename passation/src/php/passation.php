<?php

	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

/*###################################################################
			Passation d'une fiche
###################################################################*/
	$bd = bd_connect();

	if(isset($_POST['id'])){

		$id = bd_protect($bd, $_POST['id']);
		$content = array();

		// Calcul des opérations déja réalisées
    	$sql2="SELECT count(ro_op_id) as nbOpRealisee
			   FROM realisation_operation
			   WHERE ro_rf_id = ".$id;

	    $res2 = mysqli_query($bd, $sql2) or bd_erreur($bd, $sql2);
        $count = mysqli_fetch_assoc($res2);
        $content[] = array($count['nbOpRealisee'], $id);
	    

		/* Récupération de l'ensemble des opération de la fiche à réaliser
		 * Ces informations sont stockés dans un tableau qui sera envoyé pour traitement à JavaScript
		 * Le pré chargement de toutes les informations permettera le fonctionnement hors ligne de l'application
		 * Les informations contenues dans le tableau sont :
		 * id de l'opération, ordre de l'opération, contenu à afficher, type de remplissage, designation de la fiche en cours, tableau d'epi
		*/
		
		$sql="SELECT *
			  FROM realisation_fiche, fiche, compo_fiche, operation
			  WHERE rf_fi_id = fi_id
			  AND cf_fi_id = fi_id
			  AND cf_op_id = op_id
			  AND rf_id=".$id.
			  " ORDER BY cf_ordre";

	    $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	    
        while($tableau = mysqli_fetch_assoc($res)){

        	// récupération des EPI pour chaque operation
        	$sql_epi = "SELECT * 
        				FROM compo_operation, epi
        				WHERE co_epi_id = epi_id
        				AND co_op_id = ".$tableau['op_id'];
        	$res_epi = mysqli_query($bd, $sql_epi) or bd_erreur($bd, $sql_epi);
        	$epis = array();
        	while($tableau_epi = mysqli_fetch_assoc($res_epi)){
        		$epis[] = $tableau_epi['epi_designation'];
        	}

        	$content[] = array($tableau['cf_op_id'], $tableau['cf_ordre'], $tableau['op_contenu'], $tableau['op_type'], $tableau['fi_designation'], $epis);
        }

	    echo json_encode($content);
		
	}

	mysqli_close($bd);
	ob_end_flush();
?>

