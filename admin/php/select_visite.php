  <?php

	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
					Contenu de la page Select_visite
	###################################################################*/
	$bd = bd_connect();

	$sql = "SELECT * FROM VISITE WHERE vi_inactif=0";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div class="list">';
	while($tableau = mysqli_fetch_assoc($res)){
		$replace = str_replace(' ', '-', $tableau['vi_designation']);
  		echo '<a class="return" id="',$tableau['vi_designation'],'" data-toggle="modal" data-target="#SelectModal" href="#">', $tableau['vi_designation'] ,'</a>';
  	}
  	echo '</div>';
    mysqli_close($bd);

  ?>