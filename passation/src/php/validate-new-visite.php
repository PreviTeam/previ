  <?php

	session_start();
	require_once 'bibli_generale.php';
	verify_loged(isset($_SESSION['em_pass_id']));
	$_GET && redirection("./deconnexion.php");

	/*############################################################################
		Création d'une nouvelle réalisation_visite et des fiches associées
	#############################################################################*/
	$bd = bd_connect();


	// ON crée une nouvelle réalisation de visite
	$sql1 = "INSERT INTO realisation_visite (rv_vi_id, rv_ou_id, rv_debut, rv_fin , rv_etat) 
			VALUES (".$_POST['id_visite'].",".$_POST['id_outil'].",'". date('Y-m-d') . "', NULL, 0)";
	$resFirstInsert = mysqli_query($bd, $sql1) or bd_erreur($bd, $sql1);
	$idVisite = mysqli_insert_id($bd);


	// Récupération de l'ensemble des fiches composant la visite
	$sqlfiche = "SELECT * FROM compo_visite WHERE cv_vi_id =".$_POST['id_visite'];
	$res = mysqli_query($bd, $sqlfiche) or bd_erreur($bd, $sqlfiche);
    $sql="";
	

    // Création des fiches
	while($tableau = mysqli_fetch_assoc($res)){
		$sql = "INSERT INTO realisation_fiche (rf_fi_id, rf_rv_id , rf_em_id, rf_debut, rf_fin, rf_etat) 
				 VALUES (".$tableau['cv_fi_id'].",". $idVisite.", NULL,'".date('Y-m-d') ."', NULL, 0);";
		$res2 = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
  	}

  	// Renvoie de l'id de la réalisation_visite créée
  	echo $idVisite;
  	
    mysqli_close($bd);
  ?>