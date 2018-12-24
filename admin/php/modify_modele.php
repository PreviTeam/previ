<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	error_reporting(E_ALL); 
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");
	/*###################################################################
					Contenu de la page Modify_modele
	###################################################################*/

	$code = '';
	$design = '';
	$id = '';
	$organisation = '';
	$caller = "add";
	$visite = array();

	if(isset($_POST['id'])){
		$caller="modify";
		$bd = bd_connect();
		$id=bd_protect($bd, $_POST['id']);
		$sql = "SELECT * 
				FROM organisation, modele LEFT OUTER JOIN visite_attachement ON va_mo_id = mo_id 
										  LEFT OUTER JOIN visite ON va_vi_id = vi_id
				WHERE mo_or_id = or_id 
				AND mo_id='$id'";

		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		while($tableau = mysqli_fetch_assoc($res)){

			if($tableau['vi_designation'] != null){
				$visite[] = create_table_ligne("line-table", array(entities_protect($tableau['vi_designation']), '<button class="supress btn btn-link">Supprimer</button>'));
			}
			$cd=entities_protect($tableau['mo_code']);
			$de=entities_protect($tableau['mo_designation']);
			$org=entities_protect($tableau['or_designation']);
			$id = ' disabled value="'.entities_protect($tableau['or_id']).'"';
			$code = "value='$cd'";
			$design = "value='$de'";
			$organisation = $org;
		}

		mysqli_close($bd);
	}


echo '<div>',
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
			    '<span class="input-group-text" id="inputGroup-sizing-default">Code Perso</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $code ,' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">Designation</span>',
			  '</div>',
			  '<input type="text" class="form-control" ', $design ,' aria-label="Default">',
			'</div>',

			'<div class="input-group mb-3">',
			  '<div class="input-group-prepend">',
			    '<span class="input-group-text" id="inputGroup-sizing-default">',$_SESSION['eq1'],'</span>',
			  '</div>',
			  '<input  id="', $caller,'UniqueSelector" type="text" class="form-control" value="', $organisation ,'" aria-label="Default">',
			  '<a class="selecteurUnique" id="', $caller,'call" href="select_organisation.php" data-toggle="modal" data-target="#SelectModal"><img class="assoc_icone" src="../img/seo.png" alt="explore"></a>',
			'</div>',
		'</div>',

		'<div class="tableForm">';

		$entete=array($_SESSION['ps1'], "Supprimer");

		create_table($entete, $visite, $caller."Table", $_SESSION['ps1']);

				
		echo
			'<div class="adder">',
				'<a class="selecteur" id="', $caller,'call" href="select_visite.php" data-toggle="modal" data-target="#SelectModal"><img class="adder-img" src="../img/icones/SVG/autre/plus.svg"/></a>',
			'</div>';

	ob_end_flush();
?>