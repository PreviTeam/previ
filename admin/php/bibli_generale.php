<?php
/*################################################################################################
									Bibliotheque Générale HTML
################################################################################################*/
function test(){
  echo 'test';
}


/*################################################################################################
									Génération de la page générique (dashboard)
################################################################################################*/

function generic_page_start(){
echo  '<!DOCTYPE html>',
    '<html lang="fr">',

      '<head>',
       '<meta charset="utf-8">',
       ' <meta http-equiv="X-UA-Compatible" content="IE=edge">',

       '<title>Previ</title>',

        '<link href="../css/bootstrap.min.css" rel="stylesheet">',
        '<link href="../css/login.css" rel="stylesheet">',


        '<script src="../js/jquery-3.3.1.slim.min.js"></script>',
        '<script src="../js/popper.min.js"></script>',
        '<script src="../js/bootstrap.min.js"></script>',
        '<script src="../js/previ_scripts.js"></script>',

      '</head>',


      '<body>',

        '<div id="bloc_page">',

          '<nav class="navbar header static-top">',

            '<a id="logo" class="col-md-2" href="dashboarard.php"">PREVI</a>',
             '<form class="d-md-inline-block form-inline col-md-8">',
                '<div class="input-group">',
                  '<input type="text" class="form-control form-control-sm" placeholder="Rechercher..." aria-label="Search" aria-describedby="basic-addon2">',
                  '<div class="input-group-append">',
                    '<button class="btn btn-primary" type="button">Recherche',
                    '</button>',
                  '</div>',
                '</div>',
              '</form>',
             ' <div class="inline-icon col-md-2">',
               '<a  class="nav_icone" href="#">',
                  '<img src="../img/icones/SVG/autre/padlock.svg" alt="logout" height="30">',
               '</a>',
                '<a  class="nav_icone" href="#">',
                  '<img src="../img/icones/SVG/autre/settings.svg" alt="options" height="30">',
                '</a>',
                '<a class="nav_icone phplink" href="account.php">',
                  '<img src="../img/icones/PNG/avatar/man.png" alt="avatar" height="30">',
              ' </a>',
              '</div>',
          '</nav>',



          '<div id="menu" class="wrapper">',
                  '<ul class=" sidebar navbar-nav components">',
                      '<li class="nav-item"><a class="nav-link phplink" href="dashboard_content.php">',
                      '<img class="nav-icon" src="../img/icones/SVG/autre/briefcase.svg" alt="logout"/>', 
                      'Dashboard</a></li>',

                      '<li class="nav-item">',
                          '<a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                          '<img class="nav-icon" src="../img/icones/SVG/autre/shield.svg" alt="a"/>', 
                          'Administration</a>',
                          '<ul class="collapse" id="homeSubmenu">',
                              '<li class="nav-item">',
                                 '<a class="nav-link sub-item phplink" href="user.php">',
                                 '<img class="nav-icon" src="../img/icones/SVG/autre/avatar-1.svg" alt="a"/>',
                                 '<span>Users</span></a>',
                              '</li>',
                              '<li class="nav-item">',
                                 '<a class="nav-link sub-item phplink" href="visite.php">',
                                 '<img class="nav-icon" src="../img/icones/SVG/autre/map.svg" alt="a"/>',
                                 '<span>Visites</span></a>',
                              '</li>',
                              '<li class="nav-item">',
                                  '<a class="nav-link sub-item phplink" href="fiche.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/copy.svg" alt="a"/>',
                                  '<span>Fiches</span></a>',
                             '</li>',
                            ' <li class="nav-item">',
                                  '<a class="nav-link sub-item phplink" href="operation.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/file.svg" alt="a"/>',
                                  '<span>Opération</span></a>',
                             '</li>',
                          '</ul>',
                     ' </li>',

                      '<li class="nav-item">',
                          '<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                          '<img class="nav-icon" src="../img/icones/SVG/autre/book.svg" alt="a"/>',
                          'Passations</a>',
                          '<ul class="collapse" id="pageSubmenu">',
                             '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink" href="encours.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/calendar.svg" alt="a"/>',
                                  '<span>En Cours</span></a>',
                              '</li>',
                            ' <li class="nav-item">',
                                  '<a class="nav-link sub-item phplink" href="historise.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/folder.svg" alt="a"/>',
                                  '<span>Historisées</span></a>',
                             ' </li>',
                          '</ul>',
                      '</li>',

                       '<li class="nav-item">',
                          '<a href="#pageSubequip" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                          '<img class="nav-icon" src="../img/icones/SVG/autre/rocket-launch.svg" alt="a"/>',
                          'Equipements</a>',
                          '<ul class="collapse" id="pageSubequip">',
                             '<li class="nav-item">',
                                  '<a class="nav-link sub-item phplink" href="organisation.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/settings-1.svg" alt="a"/>',
                                  '<span>Organisation</span></a>',
                              '</li>',
                             '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink" href="modele.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/copy.svg" alt="a"/>',
                                  '<span>Modèles</span></a>',
                              '</li>',
                              '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink" href="outil.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/puzzle.svg" alt="a"/>',
                                  '<span>Outils</span></a>',
                              '</li>',
                          '</ul>',
                      '</li>',

                      '<li class="nav-item"><a class="nav-link phplink" href="arborescence.php">',
                      '<img class="nav-icon" src="../img/icones/SVG/autre/family-tree.svg" alt="a"/>',
                      'Arborescence</a></li>',
                  '</ul>',

              '<div id="content">',

              '<div id="content-data">';
}

function generic_page_ending(){
    echo    
            ' </div>',

               ' <footer class="sticky-footer">',
                    '<div class="foot-link col-md-11">',
                      '<a href="#">Aide</a>',
                      '<a href="#">Contacter le support</a>',
                      '<a href="#">Licence</a>',
                    '</div>',
                    '<div class="media" col-md-1>',
                      '<a href="#" class="github"><img class="nav-icon" src="../img/icones/SVG/social/github-logo.svg" alt="a"/></a>',
                      '<a href="#" class="facebook"><img class="nav-icon" src="../img/icones/SVG/social/facebook.svg" alt="a"/></a>',
                    '</div>',
                '</footer>',

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
function bd_erreur($bd, $sql) {
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