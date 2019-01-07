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
	$inactif = "";

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
		$id = ' value="'.entities_protect($tableau['ou_id']).'"';
		$code = "value='$cd'";
		$design = "value='$de'";
		$modele = $mo;
		$inactif = ($tableau['ou_inactif'])? 'checked' : '';

		mysqli_close($bd);
	}


echo '<div>',
			'<div class="form-check-inline">',
			  '<label class="form-check-label">',
			   ' <input type="checkbox" data-input="inactif" class="form-check-input form_',$caller,'" ',$inactif,' value="">Inactif',
			  '</label>',
			'</div>',


			 '<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">ID</span>',
			  '</div>',
			  '<input type="text" data-input="id_',$caller,'" class="id form-control form_',$caller,'" ', $id , ' aria-label="Default" disabled>',
			'</div>',

			 '<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Code Perso</span>',
			  '</div>',
			  '<input type="text" data-input="code" class="form-control form_',$caller,'" ', $code , ' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
			  '</div>',
			  '<input type="text" data-input="designation" class="form-control form_',$caller,'" ', $design , ' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">',$_SESSION['eq2'],'</span>',
			  '</div>',
			  '<input data-input="modele" id="', $caller,'UniqueSelector" type="text" class="form-control form_',$caller,'" value="', $modele ,'"  aria-label="Default">',
			   '<a class="selecteurUnique" id="', $caller,'call" href="select_modele.php" data-toggle="modal" data-target="#SelectModal"><img class="assoc_icone" src="../img/seo.png" alt="explore"</a>',
			'</div>',
		'</div>';
	ob_end_flush();
?>