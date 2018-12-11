<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
/*	echo $_POST['id'];*/

	$id = '';
	$vers = '';
	$de = '';
	$op = array();

	if(isset($_POST['id']))
	{
		$bd = bd_connect();
		$id2 = bd_protect($bd,$_POST['id']);
		$sql = "SELECT * 
				FROM fiche left outer join compo_fiche on fi_id = cf_fi_id
				left outer join operation on cf_op_id = op_id
				WHERE fi_id = ".$id2." ORDER BY cf_ordre";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		while($tableau = mysqli_fetch_assoc($res))
		{
			$id = ' disabled value="'.entities_protect($tableau['fi_id']).'"';
			if($tableau['op_contenu'] != null && $tableau['op_id'] != null && $tableau['cf_ordre'] != null){
				$op[] = create_table_ligne(null, array(entities_protect($tableau['op_id']), $tableau['op_contenu'], $tableau['cf_ordre']));
			}
			$de = 'value="'.entities_protect($tableau['fi_designation']).'"';
			$vers = 'value="'.entities_protect($tableau['fi_num_vers']).'"';
		}
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
				    '<span class="input-group-text" id="inputGroup-sizing-default">Code Fiche</span>',
				  '</div>',
				  '<input type="text" class="form-control"',$id,' aria-label="Default">',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Num. Version</span>',
				  '</div>',
				  '<input type="text" class="form-control"',$vers,' aria-label="Default">',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
				  '</div>',
				  '<input type="text" class="form-control"',$de,' aria-label="Default">',
				'</div>',
			'</div>',
			'<div class="tableForm">';

			$entete=array("NumOperation", "Designation", "Ordre", '' , '');

			create_table($entete, $op, null, "Operations");

				
echo		'</div>',

		'</div>';
	ob_end_flush();
?>