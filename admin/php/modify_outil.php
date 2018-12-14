<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page Modify_outils
	###################################################################*/


	$code = '';
	$design = '';
	$id = '';
	$modele = '';
	$caller = "add";

	if(isset($_POST['id'])){
		$caller="modify";
		$bd = bd_connect();
		$id=bd_protect($bd, $_POST['id']);
		$sql = "SELECT * 
				FROM modele, outil 
				WHERE ou_mo_id = mo_id 
				AND ou_id=$id";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$cd=entities_protect($tableau['ou_code']);
		$de=entities_protect($tableau['ou_designation']);
		$mo=entities_protect($tableau['mo_designation']);
		$id = ' disabled value="'.entities_protect($tableau['ou_id']).'"';
		$code = "value='$cd'";
		$design = "value='$de'";
		$modele = $mo;

		mysqli_close($bd);
	}


echo '<div>',
			'<div class="form-check-inline">',
			  '<label class="form-check-label">',
			   ' <input type="checkbox" class="form-check-input" value="">Inactif',
			  '</label>',
			'</div>',


			 '<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Code Perso</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $id , ' aria-label="Default">',
			'</div>',

			 '<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Code Perso</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $code , ' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $design , ' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Modele</span>',
			  '</div>',
			  '<input  id="', $caller,'UniqueSelector" type="text" class="form-control" value="', $modele ,'"  aria-label="Default">',
			   '<a class="selecteurUnique" id="', $caller,'call" href="select_modele.php" data-toggle="modal" data-target="#SelectModal"><img class="assoc_icone" src="../img/seo.png" alt="explore"</a>',
			'</div>',
		'</div>';
	ob_end_flush();
?>