<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");
	
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	$entete=array("Ordre ".$_SESSION['ps3'], $_SESSION['ps3'], "Demande",);
	$bd = bd_connect();
	$sql = "SELECT *
			FROM FICHE LEFT OUTER JOIN COMPO_FICHE ON cf_fi_id = fi_id LEFT OUTER JOIN OPERATION ON cf_op_id = op_id
			WHERE fi_id=".$_POST['id'];
	$content = array();

	$i = 1;
	$ordre = '';
	$operation='';
	$demande='';
	$id="Aucune Opération à afficher";
	$designation="Oops something went wrong";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	while($tableau = mysqli_fetch_assoc($res)){

		$designation = entities_protect($tableau['fi_designation']);

		if($tableau['op_contenu'] != null)
			$operation = entities_protect($tableau['op_contenu']);
		else{
			$operation = "Aucune Opération rattachée à cette fiche";
			$ligne=array($ordre,
		    	 	 $operation,
				 	 $demande);
			$content[] = create_table_ligne(null, $ligne);
			continue;
		}

		if((int)$tableau['op_type'] === 0){
			$demande = "Oui / Non";
		}else if((int)$tableau['op_type'] === 1){
			$demande = "Texte";
		}
		

		//Random pour le moment à revoir avec le changement de BDD
		$ordre = $i;
		$id = $tableau['fi_id'];
		
		$i += 1;

		$ligne=array($ordre,
		    	 	 $operation,
				 	 $demande);
		$content[] = create_table_ligne(null, $ligne);
	}
	
	create_table($entete, $content, null, $designation);

	echo '<div class="bloc-btn">',
			'<button type="button" class="btn btn-primary ajaxphplink" href="fiche.php">RETOUR</button>', 
            ' <button type="button" id="'.$id.'" class="btn btn-modal btn-success"  data-toggle="modal" href="modify_fiche.php" data-target="#ModifyModal">MODIFIER</button>',
          '</div>';

    // Ajout des fenêtres modales
	// Ajout des fenêtres modales
	modal_start(MODIFIER, 'fiche');
	modal_select();

	mysqli_close($bd);
	ob_end_flush();
?>