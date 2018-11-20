<?php

	ob_start('ob_gzhandler');
	session_start();

	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 


	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/

	echo '<div class="popup">',
			'<div class="entete-pop">',
				'<h2>Modifier l\'utilisateur</h2>',
				'<a href="#" id="close">',
				'<img src="../img/icones/SVG/autre/cancel.svg" alt="close"/>',
				'</a>',
			'</div>',

			// Contenu de la popup Midifier l'utilisateur

		'</div>';

	
	ob_end_flush();
?>