<?php

ob_start('ob_gzhandler');
session_start();

require_once 'bibli_generale.php';
error_reporting(E_ALL); 

/*verify_unloged(isset($_SESSION['idUser']));*/


//__________________________________________   CONTENU    ______________________________________________ //

generic_page_start();


$bd = bd_connect();
generic_page_ending($bd);
mysqli_close($bd);

ob_end_flush();

?>