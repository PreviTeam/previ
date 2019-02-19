  <?php

	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_pass_id']));
	$_GET && redirection("./deconnexion.php");

	/*###################################################################
					Contenu de la page Select_modele
	###################################################################*/
	$bd = bd_connect();

	/* Récupération de l'ensemble des modèles et affichage à l'utilisateur
		Les modeles sont sous forme de lien, cliquer sur un modèle le séléctiionne automatiquement
	*/
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