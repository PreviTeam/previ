<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
/*	echo $_POST['id'];*/

echo '<div class="container-fluid">',
			'<div class="inputs">',
				'<div class="form-check-inline">',
				  '<label class="form-check-label">',
				   ' <input type="checkbox" class="form-check-input" value="">Inactif',
				  '</label>',
				'</div>',

				 '<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Code Operation</span>',
				  '</div>',
				  '<input type="text" class="form-control" aria-label="Default">',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Type</span>',
				  '</div>',
				  '<input type="text" class="form-control" aria-label="Default">',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Contenu</span>',
				  '</div>',
				  '<textarea class="form-control"></textarea>',
				'</div>',

				'<select class="custom-select">',
					' <option value="un">Voir BDD</option>',
					 '<option value="deux">Voir BDD</option>',
					 '<option value="trois">Voir BBD</option>',
				'</select>',

			'</div>',
			'<div class="tableForm">';

			$entete=array("EPI", "Supprimer");
			$content =array();

			create_table($entete, $content, null, "EPI");

				
echo		'</div>',

		'</div>';

	ob_end_flush();
?>