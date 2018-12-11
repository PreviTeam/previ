<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
/*	echo $_POST['id'];*/

	if(isset($_POST['id']))
	{
		$bd = bd_connect();
		$id2 = bd_protect($bd,$_POST['id']);
		$sql = "SELECT *
				FROM visite left outer join compo_visite on vi_id = cv_vi_id
				left outer join fiche on fi_id = cv_fi_id
				WHERE vi_id=".$id2;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		while($tableau = mysqli_fetch_assoc($res))
		{
			$id = ' disabled value="'.entities_protect($tableau['vi_id']).'"';
			if($tableau['fi_id'] != null && $tableau['fi_designation'] != null)
			{
				$fi[] = create_table_ligne(null, array(entities_protect($tableau['fi_id']), $tableau['fi_designation']));
			}
			$de = 'value ="'.entities_protect($tableau['vi_designation']).'"';
			$vers = 'value ="'.entities_protect($tableau['vi_num_vers']).'"';
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
				    '<span class="input-group-text" id="inputGroup-sizing-default">Code Visite</span>',
				  '</div>',
				  '<input type="text" class="form-control"',$id,' aria-label="Default">',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Numero Version</span>',
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

			$entete=array("NumFiche", "Designation");

			create_table($entete, $fi, null, "Fiches");

				
echo		'</div>',

		'</div>';

	ob_end_flush();
?>