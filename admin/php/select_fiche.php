  <?php

	require_once 'bibli_generale.php';

	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
	$bd = bd_connect();

	$sql = "SELECT * FROM fiche";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div class="list">';
	while($tableau = mysqli_fetch_assoc($res)){
		$replace = str_replace(' ', '-', $tableau['fi_designation']);
  		echo '<a class="return" id="',$tableau['fi_designation'],'" data-toggle="modal" data-target="#SelectModal" href="#">', $tableau['fi_designation'] ,'</a>';
  	}
  	echo '</div>';
    mysqli_close($bd);


  ?>