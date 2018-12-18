<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page view_user
	###################################################################*/

	echo '<img src="../img/icones/PNG/avatar/man.png" style="height:150px">';
	
	$bd = bd_connect();
	$id = $_POST['id'];
	$sql = "SELECT * FROM EMPLOYE WHERE em_id=".$id;
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	$t = mysqli_fetch_assoc($res);

	$status = '';

	switch($t['em_status'])
	{
		case 'ADMIN':
			$status = 'Administrateur';
			break;
		case 'CE':
			$status= 'Chef-Equipe';
			break;
		case 'TECH': 
			$status= 'Technicien';
			break;
	}

	echo 
		'<div class="perso">',
			$t['em_nom'],' ',$t['em_prenom'],' ',($t['em_inactif'])? '<span class="marge inactif" style="color:red">Inactif ' : '<span class="marge inactif" style="color:green">Actif ','</span><br><span class="status">',$status,'</span>',
		'</div><div class="info">Mon Code Acteur<span class="marge">',$t['em_code'],'</span></div>',
		'<div class="info">Mot de Passe<span class="marge">****************</span>',
		'</div>';

	echo '<div>',
		'<button type="button" class="btn btn-primary ajaxphplink" href="user.php">RETOUR</button>', 
        ' <button type="button" id="'.$id.'" class="btn btn-modal btn-success"  data-toggle="modal" href="user_modify.php" data-target="#ModifyModal">MODIFIER</button>',
      '</div>';

	echo '<form method="post" action="../test.php">';
		modal_start(MODIFIER);
	echo '</form>';

	mysqli_close($bd);
	ob_end_flush();
?>