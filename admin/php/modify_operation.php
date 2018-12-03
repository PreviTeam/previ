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
	$cd='';
	$de='';
	$content='';
	$text ='';
	$o_n = '';
	$epi = array();

	if(isset($_POST['id'])){
		$bd = bd_connect();
		$id2=bd_protect($bd, $_POST['id']);
		$sql = "SELECT * 
		        FROM operation left OUTER JOIN compo_operation ON op_id = co_op_id 
		        left OUTER JOIN epi ON epi_id = co_epi_id 
		        where op_id =".$id2;

		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		while($tableau = mysqli_fetch_assoc($res)){
			$id = ' disabled value="'.entities_protect($tableau['op_id']).'"';
			if($tableau['epi_designation'] != null)
				$epi[] = create_table_ligne(null, array(entities_protect($tableau['epi_designation']), "Supprimer"));
			$de=entities_protect($tableau['op_type']);
			$content = entities_protect($tableau['op_contenu']);

			switch($tableau['op_type']){
			case 0:
				$o_n = 'selected';			
				break;
			case 1:
				$text = 'selected';
				break;
			}
		}

		mysqli_close($bd);
	}

echo '<div class="container-fluid">',
			'<div class="inputs">',
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
				    '<span class="input-group-text" id="inputGroup-sizing-default">Contenu</span>',
				  '</div>',
				  '<textarea class="form-control"> ', entities_protect($content) ,'</textarea>',
				'</div>',

				'<select class="custom-select">',
					'<option value="oui_non" ', $o_n, '>Oui - Non</option>',
				 	'<option value="texte" ', $text,'>Texte</option>',
				'</select>',

			'</div>',
			'<div class="tableForm">';

			$entete=array("EPI", "Supprimer");

			create_table($entete, $epi, null, "EPI");

				
echo		'</div>',

		'</div>';

	ob_end_flush();
?>