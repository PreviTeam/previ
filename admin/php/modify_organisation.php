<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	
	/*###################################################################
							Contenu de la page Modify_Organisation 
	###################################################################*/

    $code = '';
    $id = '';
	$design = '';
	$caller = 'add';
	$inactif = '';

	if(isset($_POST['id'])){
		$caller = 'modify';
		$bd = bd_connect();
		$id2=bd_protect($bd, $_POST['id']);
		$sql = "SELECT * FROM organisation WHERE or_id='".$id2."'";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$cd=entities_protect($tableau['or_code']);
		$de=entities_protect($tableau['or_designation']);
		$id = ' disabled value="'.entities_protect($tableau['or_id']).'"';
		$code = "value='$cd'";
		$design = "value='$de'";
		$inactif = ($tableau['or_inactif'])? 'checked' : '';

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
			    '<span class="input-group-text" id="inputGroup-sizing-default">Id</span>',
			  '</div>',
			  '<input type="text" data-input="id_',$caller,'" class="id form-control form_',$caller,'" ', $id ,' aria-label="Default">',
			'</div>',

			 '<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Code Perso</span>',
			  '</div>',
			  '<input type="text" data-input="code" class="form-control form_',$caller,'" ', $code ,' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
			  '</div>',
			  '<input type="text" data-input="designation" class="form-control form_',$caller,'" ', $design ,' aria-label="Default">',
			'</div>',
		'</div>';

	ob_end_flush();
?>