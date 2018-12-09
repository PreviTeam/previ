  <?php

	require_once 'bibli_generale.php';

	/*###################################################################
							Contenu de la page Dashboard
	###################################################################*/
	$bd = bd_connect();

	$sql = "SELECT * FROM epi";

	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div id="epi_list">';
	while($tableau = mysqli_fetch_assoc($res)){
		$replace = str_replace(' ', '-', $tableau['epi_designation']);
  		echo '<a class="return" id="',$tableau['epi_designation'],'" data-toggle="modal" data-target="#SelectModal" href="#">', $tableau['epi_designation'] ,'</a>';
  	}
  	echo '</div>';
    mysqli_close($bd);


  ?>