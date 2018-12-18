<?php

ob_start('ob_gzhandler');
session_start();

require_once 'bibli_generale.php';
error_reporting(E_ALL); 

verify_loged(isset($_SESSION['em_id']));
$_GET && redirection("./deconnexion.php");


/*###################################################################
						Contenu de la page Dashboard
###################################################################*/

    echo '<div class="scroller">';	
	generic_top_title("../img/search.png", '');
	$bd = bd_connect();
	$entete=array("En Cours", "Equipement", "Débuté le", "Fait");
	get_visites($bd, $entete);
	get_fiches($bd, $entete);
	echo '</div>';

mysqli_close($bd);
ob_end_flush();


?>