  <?php

	require_once 'bibli_generale.php';

	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
	$bd = bd_connect();

	$sql = "SELECT * FROM modele";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div class="list">';
	while($tableau = mysqli_fetch_assoc($res)){
		$replace = str_replace(' ', '-', $tableau['mo_designation']);
  		echo '<a class="return" id="',$tableau['mo_designation'],'" data-toggle="modal" data-target="#SelectModal" href="#">', $tableau['mo_designation'] ,'</a>';
  	}
  	echo '</div>';
    mysqli_close($bd);


  ?>