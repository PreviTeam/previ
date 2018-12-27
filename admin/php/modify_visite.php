<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");


	/*###################################################################
							Contenu de la page Modify_visite
	###################################################################*/

	$id = '';
	$vers='';
	$de='';
	$inactif = '';
	$fi=array();
	$caller = 'add';

	if(isset($_POST['id'])){
		$caller = 'modify';
		$bd = bd_connect();
		$id2 = bd_protect($bd,$_POST['id']);
		$sql = "SELECT *
				FROM visite left outer join compo_visite on vi_id = cv_vi_id
				left outer join fiche on fi_id = cv_fi_id
				WHERE vi_id=".$id2;
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		while($tableau = mysqli_fetch_assoc($res))
		{
			$id = ' value="'.entities_protect($tableau['vi_id']).'"';
			if($tableau['fi_id'] != null && $tableau['fi_designation'] != null){
				$fi[] = create_table_ligne("line-table", array(entities_protect($tableau['fi_designation']), '<button class="supress btn btn-link">Supprimer</button>'));
			}
			$de = 'value ="'.entities_protect($tableau['vi_designation']).'"';
			$vers = 'value ="'.entities_protect($tableau['vi_num_vers']).'"';
			$inactif = ($tableau['vi_inactif'])? 'checked' : '';
		}
		mysqli_close($bd);
	}

echo '<div class="container-fluid">',
			'<div class="inputs">',
				'<div class="form-check-inline">',
				  '<label class="form-check-label">',
				   ' <input  type="checkbox" data-input="inactif" class="form-check-input form_',$caller,'" ',$inactif,' value="">Inactif',
				  '</label>',
				'</div>',

				 '<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Code</span>',
				  '</div>',
				  '<input type="text" data-input="id_',$caller,'" class="id form-control form_',$caller,'"',$id,' aria-label="Default" disabled>',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Numero Version</span>',
				  '</div>',
				  '<input type="text" data-input="version" class="form-control form_',$caller,'"',$vers,' aria-label="Default">',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
				  '</div>',
				  '<input type="text" data-input="designation" class="form-control form_',$caller,'"',$de,' aria-label="Default">',
				'</div>',

			'</div>',
			'<div class="tableForm">';

			$entete=array("Num".$_SESSION['ps2'], "Designation");

			create_table($entete, $fi,  $caller."Table", $_SESSION['ps2']);

			echo
			'<div class="adder">',
				'<a class="selecteur" id="', $caller,'call" href="select_fiche.php" data-toggle="modal" data-target="#SelectModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
			'</div>';
				
echo		'</div>',

		'</div>';

	
	ob_end_flush();
?>