<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page user_modify
	###################################################################*/

	$adin='';
	$ce='';
	$tech = '';
	$codeAct = '';
	$nomAct = '';
	$prenomAct = '';

	if(isset($_POST['id'])){
		$bd = bd_connect();
		$id=bd_protect($bd, $_POST['id']);
		$sql = "SELECT * FROM employe WHERE em_code='$id'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$tableau = mysqli_fetch_assoc($res);

		$nom=entities_protect($tableau['em_nom']);
		$prenom=entities_protect($tableau['em_prenom']);
		$codeAct = ' disabled value="'.entities_protect($tableau['em_code']).'"';
		$nomAct = "value='$nom'";
		$prenomAct = "value='$prenom'";

		
		switch($tableau['em_status']){
			case 'ADMIN':
				$admin = 'selected';
				break;
			case 'CE':
				$ce  = 'selected';
				break;
			case 'TECH' :
				$tech = 'selected';
				break;
		}

		mysqli_close($bd);
	}


	echo '<div>',
			'<div class="form-check-inline">',
			  '<label class="form-check-label">',
			   ' <input type="checkbox" class="form-check-input">Inactif',
			  '</label>',
			'</div>',

			 '<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Code acteur</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $codeAct, ' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Nom</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $nomAct ,' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Prénom</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $prenomAct, ' aria-label="Default">',
			'</div>',

			'<select class="custom-select">',
				' <option value="admin" ', $admin, '>Administrateur</option>',
				 '<option value="CE" ', $ce, '>Chef d\'équipe</option>',
				 '<option value="tech" ', $tech,'>Technicien</option>',
			'</select>',
		'</div>';

	
	ob_end_flush();
?>