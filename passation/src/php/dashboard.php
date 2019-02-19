<?php

ob_start('ob_gzhandler');
session_start();

require_once 'bibli_generale.php';
error_reporting(E_ALL); 

verify_loged(isset($_SESSION['em_pass_id']));
$_GET && redirection("./deconnexion.php");

//__________________________________________   CONTENU    ______________________________________________ //

$bd = bd_connect();

get_preferences($bd);

 echo  '<!DOCTYPE html>',
    '<html lang="fr">',

      '<head>',
	       '<meta charset="UTF-8">',
	       ' <meta http-equiv="X-UA-Compatible" content="IE=edge">',

	       '<title>Passation</title>',

	       '<link href="../css/bootstrap.min.css" rel="stylesheet">',
		     '<link href="../css/dashboard.css" rel="stylesheet">',
         '<link rel="manifest" href="./manifest.json">',

		   '<script src="../js/jquery-3.3.1.slim.min.js"></script>',
       '<script src="../js/bootstrap.min.js"></script>',
		   '<script src="../js/dashboard.js"></script>',
       '<script src="./sw.js"></script>',

      '</head>',


      '<body>',

      '<div id="conent-page">',

      	 '<nav class="navbar header static-top">',
         '<a id="logo" href="dashboard.php">PREVI</a>',
      	 	 '<div id="nav-icon-line">',    

                    '<a  class="nav_icone" href="deconnexion.php">',
                      '<img src="../img/icones/SVG/autre/padlock.svg" alt="logout" height="30">',
                   '</a>',

              '</div>',
          '</nav>',

          '<div id="Page">';

dashboard_content($bd);


echo   '</div>',

      '<footer id="adder" class="sticky-footer">',
        '<button id="adder-btn" type="button" class="btn btn-success btn-adder btn-modal" data-toggle="modal" href="select_modele.php" data-target="#SelectModal">Nouvelle Visite</button>',
      '</footer >',

      '</div>';


      /* -----------------------------   Fenetre modale de sélction  ----------------------------------- */

      modal_select();
echo
    '</body>',
'</html>';

mysqli_close($bd);
ob_end_flush();

?>