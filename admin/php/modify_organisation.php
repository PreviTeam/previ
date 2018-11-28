<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

    $code = '';
    $id = '';
	$design = '';

	if(isset($_POST['id'])){
		$bd = bd_connect();
		$id=bd_protect($bd, $_POST['id']);
		$sql = "SELECT * FROM organisation WHERE or_id='$id'";

		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		$tableau = mysqli_fetch_assoc($res);

		$cd=entities_protect($tableau['or_code']);
		$de=entities_protect($tableau['or_designation']);
		$id = ' disabled value="'.entities_protect($tableau['or_id']).'"';
		$code = "value='$cd'";
		$design = "value='$de'";

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
			    '<span class="input-group-text" id="inputGroup-sizing-default">Id</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $id ,' aria-label="Default">',
			'</div>',

			 '<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Code Perso</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $code ,' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $design ,' aria-label="Default">',
			'</div>',
		'</div>';

	ob_end_flush();
?>