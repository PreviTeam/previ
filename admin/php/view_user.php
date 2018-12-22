<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page view_user
	###################################################################*/
	
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

	$inactif = ($t['em_inactif'] === '1') ? '<span class="marge inactif" style="color:red">Inactif </span>' : '<span class="marge inactif" style="color:green">Actif </span>';

	echo '<div class="perso">',
			'<div class="inside-class"><img alt="imgProfil" src="../img/icones/PNG/avatar/man.png" style="height:100px; width:100px;"><span class="collum-dir"><h3>',
			$t['em_nom'],' ',$t['em_prenom'],' </h3>',$status,'</span></div>', 
			$inactif,
		'</div><div class="info">Mon Code Acteur<span class="span-space">',$t['em_code'],'</span></div>',
		'<div class="info">Mot de Passe<span class="span-space">****************</span>',
			'<button type="button" id="modifier" class="btn btn-modal btn-link marge" data-toggle="modal" href="account_modify.php" data-target="#ModifyModal">Modifier</button>',
		'</div>';

	echo '<div class="bloc-btn">',
		   '<button type="button" class="btn btn-primary ajaxphplink" href="user.php">RETOUR</button>', 
           '<button type="button" id="'.$t['em_code'].'" class="btn btn-modal btn-success"  data-toggle="modal" href="user_modify.php" data-target="#ModifyModal">MODIFIER</button>',
        '</div>';

	echo '<form method="post" action="./test.php">';
		modal_start(MODIFIER, 'user');
	echo '</form>';

	mysqli_close($bd);
	ob_end_flush();
?>