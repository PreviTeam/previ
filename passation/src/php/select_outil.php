  <?php

	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
				Contenu de la page Select_outils
	###################################################################*/
	$bd = bd_connect();

	/* Récupération de l'ensemble des outils disponibles et affichage à l'utilisateur
		Les modeles sont sous forme de lien, cliquer sur un modèle le séléctiionne automatiquement
	*/
	$sql = "SELECT * FROM outil WHERE ou_inactif=0 AND ou_mo_id=".$_POST['id_modele'];
	$count = 0;
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	echo'<div class="list">';
	while($tableau = mysqli_fetch_assoc($res)){
		$count++;
  		echo '<a class="reload-select" data-id="',$tableau['ou_id'],'" href="select_visite.php">', $tableau['ou_designation'] ,'</a>';
  	}
  	 if($count === 0)
  		echo '<p>Aucun Outil disponible pour ce modèle</p>';
  	echo '</div>';
    mysqli_close($bd);


  ?>