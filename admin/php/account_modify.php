<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	echo '<div>',
			'<div class="input-group mb-3">',
			  '<input type="hidden" data-input="id_modify" class="id form-control form_modify" aria-label="Default" name="id" value="',$_SESSION['em_id'],'">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Ancien MDP</span>',
			  '</div>',
			  '<input type="password" data-input="ancien" class="form-control form_modify" aria-label="Default" name="ancienMDP">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Nouveau MDP</span>',
			  '</div>',
			  '<input type="password" data-input="nouv" class="form-control form_modify" aria-label="Default" name="nouvMDP">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Vérification Nouveau</span>',
			  '</div>',
			  '<input type="password" data-input="verif" class="form-control form_modify" aria-label="Default" name="verifMDP">',
			'</div>',
		'</div>';

	ob_end_flush();
?>