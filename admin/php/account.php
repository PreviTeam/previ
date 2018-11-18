<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	echo '<h1>Ceci est le contenu de la page Account</h1>',
	'<div>Un peu de texte</div>';

	

	ob_end_flush();
?>