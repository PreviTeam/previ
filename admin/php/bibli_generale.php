<?php
/*################################################################################################
									Bibliotheque Générale HTML
################################################################################################*/



/*################################################################################################
									Génération page générique
################################################################################################*/

function generic_page_start(){
echo '<!DOCTYPE html>',
    '<html lang="fr">',


      '<head>',
       ' <meta charset="utf-8">',
        '<meta http-equiv="X-UA-Compatible" content="IE=edge">',

       ' <title>Previ</title>',

        '<link href="../css/bootstrap.min.css" rel="stylesheet">',
        '<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">',
        '<link href="../css/login.css" rel="stylesheet">',
      '</head>',


      '<body id="page-top">',
        '<nav class="navbar header static-top">',

          '<a id="logo" class="col-md-2" href="dashboard.php">PREVI</a>',
          '<form class="d-md-inline-block form-inline col-md-8">',
            '<div class="input-group">',
              '<input type="text" class="form-control form-control-sm" placeholder="Rechercher..." aria-label="Search" aria-describedby="basic-addon2">',
              '<div class="input-group-append">',
                '<button class="btn btn-primary" type="button">Recherche',
                '</button>',
              '</div>',
            '</div>',
          '</form>',
            '<div class"inline-icon col-md-2">',
             ' <a  class="nav_icone" href="#">',
                '<img src="../img/icones/SVG/autre/padlock.svg" alt="logout" height="30">',
             ' </a>',
              '<a  class="nav_icone" href="#">',
                '<img src="../img/icones/SVG/autre/settings.svg" height="30">',
              '</a>',
              '<a class="nav_icone" href="#">',
                '<img src="../img/icones/PNG/avatar/man.png" height="30">',
             ' </a>',
            '</div>',
        '</nav>',


         '<div id="wrapper">',
              '<ul class="sidebar navbar-nav">',
                '<li class="nav-item">',
                  '<a class="nav-link" href="#">',
                    '<i class="fas fa-fw fa-tachometer-alt"></i>',
                    '<span>Dashboard</span>',
                  '</a>',
                '</li>',
                '<li class="nav-item">',
                  '<a class="nav-link" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">',
                    '<i class="fas fa-fw fa-folder"></i>',
                   ' <span>Administration</span>',
                  '</a>',
                '</li>',
               ' <li class="nav-item">',
                  '<a class="nav-link" href="#">',
                    '<i class="fas fa-fw fa-chart-area"></i>',
                   ' <span>Passation</span>',
                  '</a>',
                '</li>',
                '<li class="nav-item">',
                 ' <a class="nav-link" href="#">',
                    '<i class="fas fa-fw fa-table"></i>',
                    '<span>Equipements</span>',
                  '</a>',
                '</li>',
              '</ul>',
         '</div>',
          '<div id="wrapper">';
}

function generic_page_ending(){
    echo '</div>', 
        '<!-- Sticky Footer -->',
        '<footer class="sticky-footer">',
         ' <div class="container my-auto">',
            '<div class="copyright text-center my-auto">',
              '<span>Copyright © Your Website 2018</span>',
            '</div>',
          '</div>',
        '</footer>',

      '</div>',
      '<!-- /.content-wrapper -->',

    '</div>',
   ' <!-- /#wrapper -->',

    '<!-- Scroll to Top Button-->',
    '<a class="scroll-to-top rounded" href="#page-top">',
      '<i class="fas fa-angle-up"></i>',
   ' </a>',

   ' <!-- Logout Modal-->',
    '<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">',
      '<div class="modal-dialog" role="document">',
        '<div class="modal-content">',
          '<div class="modal-header">',
            '<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>',
            '<button class="close" type="button" data-dismiss="modal" aria-label="Close">',
              '<span aria-hidden="true">×</span>',
            '</button>',
          '</div>',
          '<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>',
          '<div class="modal-footer">',
           ' <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>',
            '<a class="btn btn-primary" href="login.html">Logout</a>',
          '</div>',
        '</div>',
      '</div>',
    '</div>',
  '</body>',

'</html>';
}

/*################################################################################
                  Fonctions relative à l'espace Utilisateur
##################################################################################*/


//____________________________________________________________________________
/**
 * Vérifie qu'un utilisateur est bien connecté pour acceder à une page, dans le cas contraire on le renvoie vers l'index
 * 
 * @param   Boolean  $id    resultat de l'isset de l'id d'un utilisateur dans le tableau $_SESSION 
 *
 * @return  void 
 */
function verify_loged($id){
    !$id && redirection("./deconnexion.php");
}

//____________________________________________________________________________
/**
 * Vérifie qu'un utilisateur n'est pas connecté pour acceder à une page, dans le cas contraire on le renvoie vers l'index
 * 
 * @param   Boolean  $id    resultat de l'isset de l'id d'un utilisateur dans le tableau $_SESSION 
 *
 * @return  void 
 */
function verify_unloged($id){
    $id && redirection("../index.php");
}


/*################################################################################
                  Fonctions de traitement des données entrée/sorties
##################################################################################*/

/** 
 *  Protection des sorties (code HTML généré à destination du client).
 *
 *  Fonction à appeler pour toutes les chaines provenant de :
 *      - de saisies de l'utilisateur (formulaires)
 *      - de la bdD
 *  Permet de se protéger contre les attaques XSS (Cross site scripting)
 *  Convertit tous les caractères éligibles en entités HTML, notamment :
 *      - les caractères ayant une signification spéciales en HTML (<, >, ...)
 *      - les caractères accentués
 *
 *  @param  string  $text   la chaine à protéger    
 *  @return string  la chaîne protégée
 */
function entities_protect($str) {
    $str = trim($str);
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

//____________________________________________________________________________
/**
 * Protection des chaînes avant insertion dans une requête SQL
 *
 * Avant insertion dans une requête SQL, toutes les chaines contenant certains caractères spéciaux (", ', ...) 
 * doivent être protégées. En particulier, toutes les chaînes provenant de saisies de l'utilisateur doivent l'être. 
 * Echappe les caractères spéciaux d'une chaîne (en particulier les guillemets) 
 * Permet de se protéger contre les attaques de type injections SQL
 *
 * @param   objet       $bd     La connexion à la base de données
 * @param   string      $str    La chaîne à protéger
 * @return  string              La chaîne protégée
 */
function bd_protect($bd, $str) {
    $str = trim($str);
    return mysqli_real_escape_string($bd, $str);
}

//____________________________________________________________________________
/**
 * Redirection de l'utilisateur vers une page donnée
 *
 * @param   String  $destination    Adresse de destination
 *
 * @return  void
 */
function redirection($destination){
    header('location: '.$destination);
    exit();
}

/*################################################################################
								Gestion Base de donnée
##################################################################################*/

define ('BS_SERVER', 'localhost'); // nom d'hôte ou adresse IP du serveur MySQL
define('BS_DB', 'Previ'); // nom de la base sur le serveur MySQL
define('BS_USER', 'root'); // nom de l'utilisateur de la base
define('BS_PASS', ''); // mot de passe de l'utilisateur de la base

/** 
 *	Ouverture de la connexion à la base de données
 *
 *	@return objet 	connecteur à la base de données
 */
function bd_connect() {
    $conn = mysqli_connect(BS_SERVER, BS_USER, BS_PASS, BS_DB);
    if ($conn !== FALSE) {
        //mysqli_set_charset() définit le jeu de caractères par défaut à utiliser lors de l'envoi
        //de données depuis et vers le serveur de base de données.
        mysqli_set_charset($conn, 'utf8') 
        or bd_erreurExit('<h4>Erreur lors du chargement du jeu de caractères utf8</h4>');
        return $conn;     // ===> Sortie connexion OK
    }
    // Erreur de connexion
    // Collecte des informations facilitant le debugage
    $msg = '<h4>Erreur de connexion base MySQL</h4>'
            .'<div style="margin: 20px auto; width: 350px;">'
            .'BD_SERVER : '. BS_SERVER
            .'<br>BS_USER : '. BS_USER
            .'<br>BS_PASS : '. BS_PASS
            .'<br>BS_DB : '. BS_DB
            .'<p>Erreur MySQL numéro : '.mysqli_connect_errno()
            .'<br>'.htmlentities(mysqli_connect_error(), ENT_QUOTES, 'ISO-8859-1')  
            //appel de htmlentities() pour que les éventuels accents s'affiche correctement
            .'</div>';
    bd_erreurExit($msg);
}

//____________________________________________________________________________
/**
 * Arrêt du script si erreur base de données 
 *
 * Affichage d'un message d'erreur, puis arrêt du script
 * Fonction appelée quand une erreur 'base de données' se produit :
 * 		- lors de la phase de connexion au serveur MySQL
 *		- ou indirectement lorsque l'envoi d'une requête échoue
 *
 * @param string	$msg	Message d'erreur à afficher
 */
function tbd_erreurExit($msg) {
    ob_end_clean();	// Supression de tout ce qui a pu être déja généré
    ob_start('ob_gzhandler');
    echo    '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>',
            'Erreur base de données</title>',
            '<style>table{border-collapse: collapse;}td{border: 1px solid black;padding: 4px 10px;}</style>',
            '</head><body>',
            $msg,
            '</body></html>';

    exit(1);
}

//____________________________________________________________________________
/**
 * Gestion d'une erreur de requête à la base de données.
 *
 * A appeler impérativement quand un appel de mysqli_query() échoue 
 * Appelle la fonction xx_bd_erreurExit() qui affiche un message d'erreur puis termine le script
 *
 * @param objet		$bd		Connecteur sur la bd ouverte
 * @param string	$sql	requête SQL provoquant l'erreur
 */
function tbd_erreur($bd, $sql) {
    $errNum = mysqli_errno($bd);
    $errTxt = mysqli_error($bd);

    // Collecte des informations facilitant le debugage
    $msg =  '<h4>Erreur de requête</h4>'
            ."<pre><b>Erreur mysql :</b> $errNum"
            ."<br> $errTxt"
            ."<br><br><b>Requête :</b><br> $sql"
            .'<br><br><b>Pile des appels de fonction</b></pre>';

    // Récupération de la pile des appels de fonction
    $msg .= '<table>'
            .'<tr><td>Fonction</td><td>Appelée ligne</td>'
            .'<td>Fichier</td></tr>';

    $appels = debug_backtrace();
    for ($i = 0, $iMax = count($appels); $i < $iMax; $i++) {
        $msg .= '<tr style="text-align: center;"><td>'
                .$appels[$i]['function'].'</td><td>'
                .$appels[$i]['line'].'</td><td>'
                .$appels[$i]['file'].'</td></tr>';
    }

    $msg .= '</table>';

    tbd_erreurExit($msg);	// => ARRET DU SCRIPT
}


?>