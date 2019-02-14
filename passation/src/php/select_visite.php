  <?php

	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page Select_operation
	###################################################################*/
	$bd = bd_connect();

	$sql = "SELECT * FROM visite_attachement, visite WHERE va_vi_id = vi_id AND va_mo_id=".$_POST['id_modele'];
	$count = 0;
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div class="list">';
	while($tableau = mysqli_fetch_assoc($res)){
  		echo '<a class="reload-select" data-id="',$tableau['vi_id'],'" href="validate-new-visite.php">', $tableau['vi_designation'] ,'</a>';
  		$count++;
  	}
  	if($count === 0)
  		echo '<p>Aucune visite disponible pour ce modèle</p>';
  	echo '</div>';
    mysqli_close($bd);


  ?>