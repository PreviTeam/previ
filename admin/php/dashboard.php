<?php

ob_start('ob_gzhandler');
session_start();

require_once 'bibli_generale.php';
error_reporting(E_ALL); 

/*verify_unloged(isset($_SESSION['idUser']));*/


//__________________________________________   CONTENU    ______________________________________________ //

generic_page_start();

// Page unique dont le contenu sera actualisé en JS par un clique ?

generic_page_ending();
ob_end_flush();

?>