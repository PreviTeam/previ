  <?php

	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page Select_operation
	###################################################################*/
	$bd = bd_connect();

	$sql = "SELECT * FROM modele WHERE mo_inactif=0";
	$count = 0;
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div class="list">';
	while($tableau = mysqli_fetch_assoc($res)){
  		echo '<a class="reload-select" data-id="',$tableau['mo_id'],'" href="select_outil.php">', $tableau['mo_designation'] ,'</a>';
  		$count++;
  	}
  	if($count === 0)
  		echo '<p>Aucunmodèle disponible</p>';
  	echo '</div>';
    mysqli_close($bd);


  ?>