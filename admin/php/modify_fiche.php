<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");
	
	/*###################################################################
							Contenu de la page modify_fiche
	###################################################################*/

	$id = '';
	$vers = '';
	$de = '';
	$caller = 'add';
	$op = array();
	$inactif = '';

	if(isset($_POST['id'])){
		$caller = 'modify';
		$bd = bd_connect();
		$id2 = bd_protect($bd,$_POST['id']);
		$sql = "SELECT * 
				FROM fiche left outer join compo_fiche on fi_id = cf_fi_id
				left outer join operation on cf_op_id = op_id
				WHERE fi_id = ".$id2." ORDER BY cf_ordre";
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		while($tableau = mysqli_fetch_assoc($res))
		{
			$id = ' value="'.entities_protect($tableau['fi_id']).'"';
			if($tableau['op_contenu'] != null && $tableau['op_id'] != null && $tableau['cf_ordre'] != null){
				$op[] = create_table_ligne("line-table", array($tableau['op_contenu'], 
											'<span class="ordre">'.$tableau['cf_ordre'].'</span>', 
											'<button class="btn btn-link upper">up</button>',  
											'<button class="btn btn-link downer">down</button>',
											'<button class="supress btn btn-link">Supprimer</button>'));
			}
			$de = 'value="'.entities_protect($tableau['fi_designation']).'"';
			$vers = 'value="'.entities_protect($tableau['fi_num_vers']).'"';
			$inactif = ($tableau['fi_inactif'])? 'checked' : '';
		}
		mysqli_close($bd);
	}


echo '<div class="container-fluid">',
				'<div class="inputs">',
				'<div class="form-check-inline">',
				  '<label class="form-check-label">',
				   ' <input type="checkbox" data-input="inactif" class="form-check-input form_',$caller,'"',$inactif,' value="">Inactif',
				  '</label>',
				'</div>',

				 '<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Code</span>',
				  '</div>',
				  '<input type="text" data-input="id_',$caller,'" class="id form-control form_',$caller,'"',$id,' aria-label="Default"  disabled>',
				'</div>',

				'<div class="input-group mb-3">',
				  '<div class="input-group-prepend">',
				    '<span class="input-group-text" id="inputGroup-sizing-default">Num. Version</span>',
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

			$entete=array("Designation", "Ordre", '' , '', '');

			create_table($entete, $op, $caller."Table", $_SESSION['ps3']);

			echo
			'<div class="adder">',
				'<a class="selecteur" id="', $caller,'call" href="select_operation.php" data-toggle="modal" data-target="#SelectModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
			'</div>';

				
echo		'</div>',

		'</div>';
	ob_end_flush();
?>