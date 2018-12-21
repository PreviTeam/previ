<?php

ob_start('ob_gzhandler');
session_start();

require_once 'bibli_generale.php';
error_reporting(E_ALL); 

verify_loged(isset($_SESSION['em_id']));
$_GET && redirection("./deconnexion.php");

//__________________________________________   CONTENU    ______________________________________________ //

$bd = bd_connect();

generic_page_start($_SESSION['em_status'], $bd);

generic_page_ending($bd);

mysqli_close($bd);
ob_end_flush();

?>