<?php
/*################################################################################################
									Bibliotheque Générale HTML
################################################################################################*/

/**
 * Fonction comptant le nombre de visites et de fiches terminées durant le mois en cours et le mois dernier
 * 
 * @param       $nbVisiteMoisEnCours    Nombre de visites terminées sur le mois en cours à modifier et renvoyer
 * @param       $nbFichesMoisEnCours    Nombre de fiches terminées sur le mois en cours à modifier et renvoyer
  * @param      $nbVisiteMoisDernier    Nombre de visites terminées sur le mois précédent à modifier et renvoyer
 * @param       $nbFichesMoisDernier    Nombre de fiches terminées sur le mois précédent à modifier et renvoyer
 * @param       $bd                     connexion à la base de donnée
 *
 * @return  void 
 */            
function get_sider_stats(&$nbVisiteMoisEnCours, &$nbFicheMoisEnCours,&$nbVisiteMoisDernier, &$nbFicheMoisDernier, $bd){

  //Modifier pour limiter la sélection au deux mois voulus
  $sql='SELECT *
            FROM realisation_visite, realisation_fiche
            WHERE rf_rv_id = rv_id 
            AND rf_etat="1"
            AND rv_fin LIKE "%-11-%"
        UNION 
        SELECT *
            FROM realisation_visite, realisation_fiche
            WHERE rf_rv_id = rv_id 
            AND rf_etat="1"
            AND rv_fin LIKE "%-10-%"';

  $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
  $dateEnCours= date('Y-m');
  $dateMoisDernier= date('Y-m', mktime(0, 0, 0, date('m')-1));


  $lastVisite = -1;

  while($tableau = mysqli_fetch_assoc($res)){

    if(intval(explode('-',$tableau['rv_fin'])[1]) === 11){
       if($lastVisite === -1 || $lastVisite != $tableau['rv_vi_id'])
        $nbVisiteMoisEnCours++;
      if(intval($tableau['rf_etat']) === 1)
        $nbFicheMoisEnCours++;
    }
    else{
      if($lastVisite === -1 || $lastVisite != $tableau['rv_vi_id'])
        $nbVisiteMoisDernier++;
      if(intval($tableau['rf_etat']) === 1)
        $nbFicheMoisDernier++;
    }
     
    $lastVisite =  $tableau['rv_vi_id'];
   } 
}



/**
 * Fonction de création d'une dataGrid
 * 
 * @param       String[]  $tableau_entete     Tableau contenant le Nom des entêtes de colones
 * @param       Lignes[]  $tableau_ligne      Tableau contenant les lignes à inserrer. A créer avec create_table_ligne
 * @param       String    $class              Classe à attribuer au la DataGrid.Si aucune classe n'est à ajouter, mettre la valeur null
 * @param       String    $titre              Titre de la datagid
 *
 * @return      void
 */ 
function create_table($tableau_entete, $tableau_ligne, $class, $titre){
   $classe = $class != null ? 'class="'.$class.'"' : '';
   echo '<h1 class="title_table">', $titre,'</h1><table class="table ', $classe, '"><thead>';
   $colones_entete = sizeof($tableau_entete);
   $colones_ligne = sizeof($tableau_ligne);

   //Formation de la ligne d'entête
  for($i=0; $i < $colones_entete; $i++){
    echo '<th scope="col">', $tableau_entete[$i], '</th>';
  }
  echo '</thead>';

  for($i=0; $i < $colones_ligne; $i++){
    echo $tableau_ligne[$i];
  }

  echo'</table>';
}

/**
 * Fonction de création d'une ligne de DataGrig. La ligne résultante est à ajouter dans un tableau de lignes
 * à envoyer à la fonction create_table.
 * Exemple d'usage : create_table_ligne(null, array(data1, data2, data3))
 * 
 * @param       String  $class              classe de l'élément de ligne. Si aucune classe n'est à ajouter, mettre la valeur null
 * @param       Data[]  $tableau_contenu    tableau de valeurs Une valeur équivaut à une colone dans la table. 
 *
 * @return      Strin   $res                Code HTML d'un ligne de tableau 
 */  
function create_table_ligne($class, $tableau_contenu){
  $classe = $class != null ? 'class="'.$class.'"' : '';
  $res= '<tr '.$classe.'>';
  $colones = sizeof($tableau_contenu);
  for($i=0; $i < $colones; $i++){
    $res.= '<td>'.$tableau_contenu[$i].'</td>';
  }
  $res.= '</tr>';

  return $res;

}

/**
 * Fonction de création d'une dataGrid
 * 
 * @param       String    $title              Titre de l'arborescence
 * @param       Data[]    $niveaux            Arborescence à 3 niveaux à afficher 
 *
 * @return      void
 */ 
function create_treeview($title, $niveaux){
  $sizeOrg=count($niveaux);
  
  echo '<article><h1 class="title_table">', $title, '</h1>';
  echo '<ul class="tree">';

  for($i=0; $i < $sizeOrg; $i++){
    $sizeMod = count($niveaux[$i][1]);

    echo  '<li><a href="#subModel', $i, '" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">', 
          $niveaux[$i][0], 
          '</a><ul class="collapse" id="subModel', $i, '">';

             for($j=0; $j < $sizeMod; $j++){
                echo '<li><a href="#subOutil' , $niveaux[$i][0], $niveaux[$i][1][$j][0], '" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">',
                      $niveaux[$i][1][$j][0] , 
                      '</a><ul class="collapse" id="subOutil' , $niveaux[$i][0], $niveaux[$i][1][$j][0], '">';

                        $sizeOut = count($niveaux[$i][1][$j][1]);
                        for($k=0; $k < $sizeOut; $k++){
                          echo'<li>', $niveaux[$i][1][$j][1][$k], '</li>';
                        }
                         
               echo  '</ul></li>';
              }
    echo  '</ul></li>';    
  }

  echo '</ul></article>';
}
/*################################################################################################
									Génération de la page générique (dashboard)
################################################################################################*/


/**
 * Fonction d'affichage de la page générique Dashboard jusqu'à son bloc contenu. Doit être suivi de la 
 * fonction generic_page_ending pour cloturer la page correctement.
 */ 
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
               '<a  class="nav_icone" href="deconnexion.php">',
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
                                  '<span>Organisations</span></a>',
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

                       '<li class="nav-item">',
                          '<a href="#pageSubarbo" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                          '<img class="nav-icon" src="../img/icones/SVG/autre/family-tree.svg" alt="a"/>',
                          'Arborescence</a>',
                          '<ul class="collapse" id="pageSubarbo">',
                             '<li class="nav-item">',
                                  '<a class="nav-link sub-item phplink" href="arborescence_equ.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/wrench.svg" alt="a"/>',
                                  '<span>Equipements</span></a>',
                              '</li>',
                             '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink" href="arborescence_vis.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/padnote.svg" alt="a"/>',
                                  '<span>Visites</span></a>',
                              '</li>',
                              '</ul>',
                      '</li>',
                  '</ul>',

              '<div id="content">',
                '<div id="middle">',

                  '<div id="content-data">';
}


/**
 * Fonction d'affichage de la seconde partie de la page générique. Doit être précédée de la 
 * fonction generic_page_start pour cloturer la page correctement.
 * @param    $bd   instance de la base de donnée
 */
function generic_page_ending($bd){
  
  //Calcul des statistiques à afficher
  $nbVisiteMoisEnCours= 0;
  $nbFichesMoisEnCours= 0;
  $nbVisiteMoisDernier= 0;
  $nbFichesMoisDernier= 0;
  get_sider_stats($nbVisiteMoisEnCours, $nbFicheMoisEnCours, $nbVisiteMoisDernier, $nbFicheMoisDernier, $bd);
  $ecartVisitesMois =   $nbVisiteMoisEnCours  -  $nbVisiteMoisDernier;
  $ecartFichesMois  =   $nbFichesMoisEnCours   -  $nbFicheMoisDernier;

  $couleurVisite    =   $ecartVisitesMois >= 0  ? 'class="positif"' : 'class="negatif"';
  $couleurFiche     =   $ecartFichesMois  >= 0  ? 'class="positif"' : 'class="negatif"';

        echo    
                    ' </div>',

                    '<div id="right_sider">',

                      '<div id="notifications">',
                      '</div>',

                      '<div id="statistiques">',
                        '<img class="sider_icone" src="../img/icones/PNG/sider_droit/seo.png" alt="a"/>',
                        '<table class="table">',
                            '<thead>',
                              '<th scope="col">Type</th>',
                              '<th scope="col">', date('M', mktime(0, 0, 0, date('m')-1)), '</th>',
                              '<th scope="col">', date('M'), '</th>',
                              '<th scope="col">Value</th>',
                            '</thead>',

                            '<tr>',
                              '<th scope="row">Visites</th>',
                              '<td>',$nbVisiteMoisDernier,'</td>',
                              '<td>', $nbVisiteMoisEnCours, '</td>',
                              '<td ', $couleurVisite,'>', $ecartVisitesMois ,'</td>',
                            '</tr>',

                            '<tr>',
                              '<th scope="row">Fiches</th>',
                              '<td>',$nbFicheMoisDernier,'</td>',
                              '<td>',$nbFichesMoisEnCours,'</td>',
                              '<td ', $couleurFiche,'>', $ecartFichesMois  , '</td>',
                            '</tr>',

                          '</table>',
                      '</div>',

                    '</div>',
                  '</div>',

                 ' <footer class="sticky-footer">',
                      '<div class="foot-link col-md-11">',
                        '<a href="#">Aide</a>',
                        '<a href="#">Contacter le support</a>',
                        '<a href="#">Licence</a>',
                      '</div>',
                      '<div class="media" col-md-1>',
                        '<a href="https://github.com/PreviTeam/previ" class="github"><img class="nav-icon" src="../img/icones/SVG/social/github-logo.svg" alt="a"/></a>',
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