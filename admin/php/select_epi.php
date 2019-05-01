  <?php
	require_once 'bibli_generale.php';
	session_start();
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
	$bd = bd_connect();

	$sql = "SELECT * FROM EPI";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div class="list">';
	while($tableau = mysqli_fetch_assoc($res)){
		$replace = str_replace(' ', '-', $tableau['epi_designation']);
  		echo '<a class="return" id="',$tableau['epi_designation'],'" data-toggle="modal" data-target="#SelectModal" href="#">', $tableau['epi_designation'] ,'</a>';
  	}
  	echo '</div>';
    mysqli_close($bd);


  ?>