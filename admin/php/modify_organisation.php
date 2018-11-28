<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
/*	echo $_POST['id'];*/

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
			  '<input type="text" class="form-control" aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
			  '</div>',
			  '<input type="text" class="form-control" aria-label="Default">',
			'</div>',
		'</div>';

	ob_end_flush();
?>