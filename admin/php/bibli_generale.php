<?php

/* ----------- Constantes -----------------*/

define("MODIFIER", "Modify");
define("NOUVEAU", "Add");
define("SELECTEUR", "Select");

/*################################################################################################
									Bibliotheque Générale HTML
################################################################################################*/

function get_chart($bd,$id)
{
  $data  = '[';
  $label = '[';
  $month = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");

  for($i = 5; $i >= 0; $i--)
  {
    $currentMonth = date('n', mktime(0, 0, 0, date('m')-$i));
    $sql = 'SELECT COUNT(DISTINCT h_rv_vi_id) AS numVi
            FROM HISTO_REALISATION_VISITE, HISTO_REALISATION_FICHE
            WHERE h_rf_rv_id = h_rv_id 
            AND h_rf_etat="1"
            AND h_rf_em_id = '.$id.'
            AND h_rv_fin LIKE "'. date('Y-m', mktime(0, 0, 0, date('m')-$i)) . '-%"';
    $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    $tab = mysqli_fetch_assoc($res);

    $data .= $tab["numVi"];
    $label .= '"'.$month[$currentMonth].'"';
    
    if($i > 0){
      $label .= ',';
      $data .= ',';
    }
    else{
      $label .= ']';
      $data .= ']';
    }
  }

  echo  '<script>',
          'var canvas = document.getElementById("chrt");',
          'var chrt = new Chart(canvas,{',
            'type: "bar",',
            'data: {',
              'labels: ',$label,',',
              'datasets: [{',
                'label: "visites",',
                'backgroundColor: "rgb(0, 123, 255)",',
                'data: ',$data,',',
              '}]',
            '},',

            'options: {',
                 ' scales: {',
                      'yAxes: [{',
                          'ticks: {',
                              'beginAtZero:true',
                         '}',
                      '}]',
                  '}',
              '}',
          '});',
      '</script>';
}

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
function get_sider_stats(&$nbVisiteMoisEnCours, &$nbFichesMoisEnCours,&$nbVisiteMoisDernier, &$nbFicheMoisDernier, $bd){

  $m = date('m');
  $lastM = $m-1;


  //Modifier pour limiter la sélection au deux mois voulus
  $sql='SELECT *
            FROM HISTO_REALISATION_VISITE, HISTO_REALISATION_FICHE
            WHERE h_rf_rv_id = h_rv_id
            AND h_rf_etat = 1
            AND h_rv_fin LIKE "%-'. $m . '-%"
        UNION 
        SELECT *
            FROM HISTO_REALISATION_VISITE, HISTO_REALISATION_FICHE
            WHERE h_rf_rv_id = h_rv_id 
            AND h_rf_etat = 1
            AND h_rv_fin LIKE "%-'. $lastM. '-%"';

  $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
  


  $lastVisite = -1;

  while($tableau = mysqli_fetch_assoc($res)){

    if(explode('-',$tableau['h_rv_fin'])[1] === $m ){
      if($lastVisite === -1 || $lastVisite != $tableau['h_rv_id'])
        $nbVisiteMoisEnCours++;

        $nbFichesMoisEnCours++;
    }
    else{
      if($lastVisite === -1 || $lastVisite != $tableau['h_rv_id'])
        $nbVisiteMoisDernier++;

        $nbFichesMoisDernier++;
    }
     
    $lastVisite =  $tableau['h_rv_id'];
  } 

}

/**
 * Création du code HTM du sider statistiques, affichant le nombre total de visites, fiches et opéraitons
 * 
 * @param       $nbVisites   Nombre de visites crées
 * @param       $nbFiches    Nombre de fiches crées
 * @param       $nbOp        Nombre d'opération crées'
 * @param       $bd          connexion à la base de donnée
 *
 * @return  void 
 */   
function get_sider_admin(&$nbVisites, &$nbFiches,&$nbOp, $bd){

  //Modifier pour limiter la sélection au deux mois voulus
  $sql='SELECT count(vi_id) as nb FROM VISITE
        UNION
        SELECT count(fi_id) FROM FICHE
        UNION
        SELECT count(op_id) FROM OPERATION';

  $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

  $countLine = 1;

  while($tableau = mysqli_fetch_assoc($res)){

     switch($countLine){
      case 1 :
        $nbVisites = $tableau['nb'];
      break;

      case 2 : 
        $nbFiches = $tableau['nb'];
      break;

      case 3 : 
        $nbOp = $tableau['nb'];
      break ;
     }

     $countLine++;
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
   $classe = $class != null ? $class : '';
   echo '<h2 class="title_table">', $titre,'</h1><table class="table-fill" class="', $classe, '"><thead><tr>';
   $colones_entete = sizeof($tableau_entete);
   $colones_ligne = sizeof($tableau_ligne);

   //Formation de la ligne d'entête
  for($i=0; $i < $colones_entete; $i++){
    echo '<th scope="col">', $tableau_entete[$i], '</th>';
  }
  echo '</tr></thead>',
      '<tbody class="tableBody">';

  for($i=0; $i < $colones_ligne; $i++){
    echo $tableau_ligne[$i];
  }
  echo'</tbody class="table-hover"></table>';
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
    if($i == 0){
       $res.= '<td class="cell">'.$tableau_contenu[$i].'</td>';
    }
    else{
       $res.= '<td>'.$tableau_contenu[$i].'</td>';
    }
   
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

/**
 * Créatoin de fenêtres modales dans la page
 * 
 * @param       String    $type      type de la fenetre modale. Constantes : MODIFIER pour une fenetre de modification NOUVEAU pour une fenetre d'ajout
 *
 * @return      void
 */ 
function modal_start($type, $currentPage){
  $titre = ($type === 'Modify') ? 'Modifier' : 'Nouveau';
  $btns = ($type === 'Modify') ? '<a data-bddAction="delete" data-refresh="'.$currentPage.'"  href="bdd_'.$currentPage.'.php" class="btn btn-danger bdd_request">Supprimer</a> <a data-refresh="'.$currentPage.'" data-bddAction="modify" href="bdd_'.$currentPage.'.php" class="btn btn-success bdd_request">Modifier</a>' :
                                ' <a class="btn btn-secondary" data-dismiss="modal">Annuler</a><a data-bddAction="create" data-refresh="'.$currentPage.'" href="bdd_'.$currentPage.'.php" class="btn btn-primary bdd_request">Créer</a>';
  echo 
  '<div class="modal fade" id="', $type, 'Modal" data-backdrop="static" tabindex="-2" role="dialog" aria-hidden="true" >',
    '<div class="modal-dialog" role="document">',
      '<div class="modal-content">',
        '<div class="modal-header">',
          '<h5 class="modal-title" class="ModalLabel">', $titre, '</h5>',
          '<button type="button" class="close" data-dismiss="modal" aria-label="Close">',
            '<span aria-hidden="true">&times;</span>',
         ' </button>',
        '</div>',
        '<div id="', $type, 'modal-body" class="modal-body">',
          // Contenu de la modale insérée dynamiquement via JS 
        '</div>',
          '<div class="modal-footer">',
          $btns,
          '</div>',
      '</div>',
    '</div>',
  '</div>';
}

/**
 * Créatoin de fenêtres modales de sélection dans la page
 * 
 * @param       String    $type      type de la fenetre modale. Constantes : MODIFIER pour une fenetre de modification NOUVEAU pour une fenetre d'ajout
 *
 * @return      void
 */ 
function modal_select(){

  echo 
  '<div class="content-modal">',
    '<div class="modal fade" id="SelectModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="Selecteur" aria-hidden="true">',
      '<div class="modal-dialog" role="document">',
        '<div class="modal-content">',
        '  <div class="modal-header">',
            '<h5 class="modal-title" class="ModalLabel">Selection</h5>',
            '<button type="button" class="close"  id="closeSelectModal">',
              '<span aria-hidden="true">&times;</span>',
           ' </button>',
          '</div>',
         ' <div id="Selectmodal-body" class="Selectmodal-body">',
         '</div>',
          '</div>',
       ' </div>',
      '</div>',
    '</div>';
}


/**
 * création de la fenêtres modales des préférences utilisateur, définissant la terminologie pour la base
 * 
 * @param       $bd                     connexion à la base de donnée
 *
 * @return  void 
 */  
function modal_preferences($bd){

  $sql = "SELECT * FROM admin_parameters";

  $ps1 = '';
  $ps2 = '';
  $ps3 = '';
  $eq1 = '';
  $eq2 = '';
  $eq3 = '';

  $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
  $tableau = mysqli_fetch_assoc($res);

  $ps1 = $tableau['ap_pslvl1']; 
  $ps2 = $tableau['ap_pslvl2'];
  $ps3 = $tableau['ap_pslvl3'];
   
  $eq1 = $tableau['ap_eqlvl1']; 
  $eq2 = $tableau['ap_eqlvl2'];
  $eq3 = $tableau['ap_eqlvl3'];
  


  echo 
  '<div class="modal fade" id="preferenceModal" data-backdrop="static" tabindex="-2" role="dialog" aria-hidden="true">',
    '<div class="modal-dialog" role="document">',
      '<div class="modal-content">',
        '<div class="modal-header">',
          '<h5 class="modal-title" class="ModalLabel">Préférences</h5>',
          '<button class="close" data-dismiss="modal" aria-label="Close">',
            '<span aria-hidden="true">&times;</span>',
          '</button>',
        '</div>',
        '<div id="preferencemodal-body" class="modal-body">';

      echo '<div class="container-fluid">',

            '<h3>Administration</h3>',
            '<div class="input-group mb-3">',
              '<div class="input-group-prepend">',
                '<span class="input-group-text" class="inputGroup-sizing-default">Niveau 1</span>',
              '</div>',
              '<input type="text" class="form-control input" data-input="ps1" value="', $ps1 ,'" aria-label="Default" required>',
            '</div>',

            '<div class="input-group mb-3">',
              '<div class="input-group-prepend">',
                '<span class="input-group-text" class="inputGroup-sizing-default">Niveau 2</span>',
              '</div>',
              '<input type="text" class="form-control input" data-input="ps2" value="', $ps2 ,'" aria-label="Default" required>',
            '</div>',

            '<div class="input-group mb-3">',
              '<div class="input-group-prepend">',
                '<span class="input-group-text" class="inputGroup-sizing-default">Niveau 3</span>',
              '</div>',
              '<input type="text" class="form-control input" data-input="ps3" value="', $ps3 ,'" aria-label="Default" required>',
            '</div>',

          '<h3>Equipements</h3>',
            '<div class="input-group mb-3">',
              '<div class="input-group-prepend">',
                '<span class="input-group-text" class="inputGroup-sizing-default">Niveau 1</span>',
              '</div>',
              '<input type="text" class="form-control input" data-input="eq1" value="', $eq1 ,'" aria-label="Default" required>',
            '</div>',

            '<div class="input-group mb-3">',
              '<div class="input-group-prepend">',
                '<span class="input-group-text" class="inputGroup-sizing-default">Niveau 2</span>',
              '</div>',
              '<input type="text" class="form-control input" data-input="eq2" value="', $eq2 ,'" aria-label="Default" required>',
            '</div>',

            '<div class="input-group mb-3">',
              '<div class="input-group-prepend">',
                '<span class="input-group-text" class="inputGroup-sizing-default">Niveau 3</span>',
              '</div>',
              '<input type="text" class="form-control input" data-input="eq3" value="', $eq3 ,'" aria-label="Default" required>',
            '</div>',
            '<p class="text-danger">La modification va réinitialiser les préférences de l\'application et forcer son redémarage</p>',
          '</div>';

  echo  '</div>',
          '<div class="modal-footer">',
          ' <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button><a class="btn btn-success bdd_request" data-bddAction="updatePrefs" href="bdd_preferences.php" >Modifier</button>',
          '</div>',
      '</div>',
    '</div>',
  '</div>';
}

/**
 * Affichage des visites en cours dans une table
 * 
 * @param       $entete   entête de la table
 * @param       $bd       connexion à la base de donnée
 *
 * @return  void 
 */  
function get_visites($bd, $entete){
    
    $sql = "SELECT vi_id, vi_designation, ou_designation, rv_debut, count(rf_fi_id) as totFiches
        FROM VISITE LEFT OUTER JOIN REALISATION_VISITE ON vi_id = rv_vi_id, REALISATION_FICHE,  OUTIL
        WHERE rv_id = rf_rv_id
        AND rv_ou_id = ou_id
        AND rv_etat = 0
        AND rf_etat = 0
        GROUP BY rv_id";

    $content =array();
    $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

    while($tableau = mysqli_fetch_assoc($res)){
      $sql2 = "SELECT count(fi_id) as nbFiches
           FROM FICHE, COMPO_VISITE, VISITE 
           WHERE fi_id = cv_fi_id
           AND vi_id = cv_vi_id
           AND vi_id = '".$tableau['vi_id']."'";
      $res2 = mysqli_query($bd, $sql2) or bd_erreur($bd, $sql);
      $nbFicheParVisite =  mysqli_fetch_assoc($res2);

      $ligne=array($tableau['vi_designation'],
             $tableau['ou_designation'], 
             $tableau['rv_debut'],
             (( $nbFicheParVisite['nbFiches'] - $tableau['totFiches']) * 100 / $nbFicheParVisite['nbFiches']).'%');
      $content[] = create_table_ligne(null, $ligne);
    }

    if(empty($content))
      $content[] = create_table_ligne(null, array("Rien à afficher"));
    create_table($entete, $content, null, $_SESSION['ps1'] . " en Cours");
  }

/**
 * Affichage des fiches en cours dans une table
 * 
 * @param       $entete   entête de la table
 * @param       $bd       connexion à la base de donnée
 *
 * @return  void 
 */  
function get_fiches($bd, $entete){
    
  $sql = "SELECT fi_id, fi_designation, ou_designation, rf_debut, rf_em_id, rf_id
             FROM REALISATION_VISITE, OUTIL, FICHE, REALISATION_FICHE
             WHERE rf_fi_id = fi_id
             AND rf_rv_id = rv_id
             AND rv_ou_id = ou_id
             AND rf_etat = 0
             GROUP BY rf_id";

  $content =array();
  $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

  while($tableau = mysqli_fetch_assoc($res)){

    $sql3 = "SELECT * FROM REALISATION_OPERATION WHERE ro_rf_id = ".$tableau['rf_id'];
     $res3 = mysqli_query($bd, $sql3) or bd_erreur($bd, $sql3);
     $nbOpRealisees = 0;
     while($tableau2 = mysqli_fetch_assoc($res3)){
      $nbOpRealisees++;
     }
         
    $sql2 = "SELECT count(op_id) as nbOp
         FROM FICHE, COMPO_FICHE, OPERATION
         WHERE fi_id = cf_fi_id
         AND op_id = cf_op_id
         AND fi_id = '".$tableau['fi_id']."'";
    $res2 = mysqli_query($bd, $sql2) or bd_erreur($bd, $sql);
    $nbOperationParFiche =  mysqli_fetch_assoc($res2);

    $ligne=array($tableau['fi_designation'],
           $tableau['ou_designation'], 
           $tableau['rf_debut'],
           ($nbOpRealisees *100 / $nbOperationParFiche['nbOp'])."%");
    $content[] = create_table_ligne(null, $ligne);
  }

  if(empty($content))
    $content[] = create_table_ligne(null, array("Rien a afficher"));

  create_table($entete, $content, null, $_SESSION['ps2'] ." en Cours");
}


function create_fiche($tab_ligne,$titre){
    echo '<h1 class="title_table">',$titre,'</h1>';
    echo '<table>',
            '<tr>',
              '<th>Operation</th>',
              '<th>Resultat</th>',
            '</tr>';

    foreach($tab_ligne as $val)
    {
      echo $val;
    }
    echo '</table>';
  }

  function create_fiche_ligne($tab_contenu){
    $res = '<tr>';

    foreach($tab_contenu as $val)
    {
      $res .= '<td>'.$val.'</td>';
    }

    $res .= '</tr>';
    return $res;
  }



/*################################################################################################
									Génération de la page générique (dashboard)
################################################################################################*/


/**
 * Génération de l'image d'entête des pages
 * 
 * @param       $img   image a afficher
 * @param       $title titre à afficher
 *
 * @return  void 
 */  
function generic_top_title($img, $title){
  echo '<div id="topper" style="background-image: url(', $img,');">',
          '<p class="topper-text">', $title, '</p>',
        '</div>';
}

/**
 * Fonction d'affichage de la page générique Dashboard jusqu'à son bloc contenu. Doit être suivi de la 
 * fonction generic_page_ending pour cloturer la page correctement.
 * 
 * @param       $status  Status de l'utilisateur
 * @param       $bd      Connexion à la base de donnée
 */ 
function generic_page_start($status, $bd){


  $sql = "SELECT * FROM admin_parameters";

  $ps1 = '';
  $ps2 = '';
  $ps3 = '';
  $eq1 = '';
  $eq2 = '';
  $eq3 = '';

  $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
  $tableau = mysqli_fetch_assoc($res);

  $ps1 = $tableau['ap_pslvl1'];
  $ps2 = $tableau['ap_pslvl2'];
  $ps3 = $tableau['ap_pslvl3'];
  $eq1 = $tableau['ap_eqlvl1'];
  $eq2 = $tableau['ap_eqlvl2'];
  $eq3 = $tableau['ap_eqlvl3'];

  $_SESSION['ps1'] = $tableau['ap_pslvl1'];
  $_SESSION['ps2'] = $tableau['ap_pslvl2'];
  $_SESSION['ps3'] = $tableau['ap_pslvl3'];
  $_SESSION['eq1'] = $tableau['ap_eqlvl1'];
  $_SESSION['eq2'] = $tableau['ap_eqlvl2'];
  $_SESSION['eq3'] = $tableau['ap_eqlvl3'];


  echo  '<!DOCTYPE html>',
    '<html lang="fr">',

      '<head>',
       '<meta charset="utf-8">',
       ' <meta http-equiv="X-UA-Compatible" content="IE=edge">',

       '<title>Previ</title>',

        '<link href="../css/bootstrap.min.css" rel="stylesheet">',
        '<link href="../css/dashboard.css" rel="stylesheet">',
        '<meta name="viewport" content="width-device-width, initial-scale=1.0">',


        '<script src="../js/jquery-3.3.1.slim.min.js"></script>',
        '<script src="../js/popper.min.js"></script>',
        '<script src="../js/bootstrap.min.js"></script>',
        '<script src="../js/previ_scripts.js"></script>',
        '<script src="../js/Chart.min.js"></script>',

      '</head>',


      '<body>';

  echo
        '<div id="bloc_page">',

          '<nav class="navbar header static-top">',

            '<a id="logo" href="dashboard.php">PREVI</a>',
             '<form class="d-md-inline-block form-inline">',
                '<div class="input-group">',
                  '<input id="searchBar" type="text" class="form-control form-control-sm" placeholder="Rechercher..." aria-label="Search">',
                  '<div class="input-group-append">',
                    '<a id="searchBtn" href="recherche.php" class="btn btn-primary">Recherche',
                    '</a>',
                  '</div>',
                '</div>',
              '</form>',
             ' <div class="inline-icone">';
               
               echo
                    '<div id="menu-deroulant" class="dropdown">',
                      '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Menu',
                         '<span class="caret"></span></button>',
                          '<ul class="dropdown-menu">',
                             '<li><a class="nav-link-mobile phplink" href="dashboard_content.php">Tableau de bord</li></a>';

                        if($status === 'ADMIN'){
                        echo
                            '<li class="dropdown-submenu">',
                              '<span class="caret">Administration</span></a>',
                              '<ul class="">',
                                '<li><a class="nav-link-mobile phplink" href="user.php">Users</a></li>',
                                '<li><a class="nav-link-mobile phplink" href="visite.php">',$ps1,'</a></li>',
                                '<li><a class="nav-link-mobile phplink" href="fiche.php">',$ps2,'</a></li>',
                                '<li><a class="nav-link-mobile phplink" href="operation.php">',$ps3,'</a></li>',
                              '</ul>',
                            '</li>';
                        }

                        echo
                            '<li class="dropdown-submenu">',
                              '<span class="caret">Passations</span></a>',
                              '<ul>',
                                '<li><a class="nav-link-mobile phplink" href="encours.php">En Cours</a></li>',
                                '<li><a class="nav-link-mobile phplink" href="historise.php">Historisées</a></li>',
                              '</ul>',
                            '</li>';


                        if($status != 'TECH'){
                          echo 
                              '<li class="dropdown-submenu">',
                                '<span class="caret">Equipements</span></a>',
                                '<ul>',
                                  '<li><a class="nav-link-mobile phplink" href="organisation.php">',$eq1,'</a></li>',
                                  '<li><a class="nav-link-mobile phplink" href="modele.php">',$eq2,'</a></li>',
                                  '<li><a class="nav-link-mobile phplink" href="outil.php">',$eq3,'</a></li>',
                                '</ul>',
                              '</li>';
                        }
                echo
                          '</ul>',
                    '</div>',

                    '<a class="nav_icone phplink" href="account.php">',
                      '<img src="../img/icones/PNG/avatar/man.png" alt="avatar" height="30">',
                     ' </a>',
                    
                    '<a  class="nav_icone btn-modal" data-toggle="modal" data-target="#preferenceModal" href="#">',
                      '<img src="../img/icones/SVG/autre/settings.svg" alt="options" height="30">',
                    '</a>',

                    '<a  class="nav_icone" href="deconnexion.php">',
                      '<img src="../img/icones/SVG/autre/padlock.svg" alt="logout" height="30">',
                   '</a>',
                    

              '</div>',
          '</nav>',


          '<div id="menu" class="wrapper">',
                  '<ul class=" sidebar navbar-nav components">',
                      '<li class="nav-item"><a class="nav-link phplink" href="dashboard_content.php">',
                      '<img class="nav-icon" src="../img/icones/SVG/autre/briefcase.svg" alt="logout"/>', 
                      'Tableau de bord</a></li>';

                  // Affiche les éléments vu seulement par l'administrateur
                  if($status === 'ADMIN'){
                    echo 
                      '<li class="nav-item">',
                            '<a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                            '<img class="nav-icon" src="../img/icones/SVG/autre/shield.svg" alt="a"/>', 
                            'Administration</a>',
                            '<ul class="collapse" id="homeSubmenu">',
                                '<li class="nav-item">',
                                   '<a class="nav-link sub-item phplink highligh-menu" href="user.php">',
                                   '<img class="nav-icon" src="../img/icones/SVG/autre/avatar-1.svg" alt="a"/>',
                                   '<span>Utilisateurs</span></a>',
                                '</li>',
                                '<li class="nav-item">',
                                   '<a class="nav-link sub-item phplink highligh-menu" href="visite.php">',
                                   '<img class="nav-icon" src="../img/icones/SVG/autre/map.svg" alt="a"/>',
                                   '<span>',$ps1,'</span></a>',
                                '</li>',
                                '<li class="nav-item">',
                                    '<a class="nav-link sub-item phplink highligh-menu" href="fiche.php">',
                                    '<img class="nav-icon" src="../img/icones/SVG/autre/copy.svg" alt="a"/>',
                                    '<span>',$ps2,'</span></a>',
                               '</li>',
                              ' <li class="nav-item">',
                                    '<a class="nav-link sub-item phplink highligh-menu" href="operation.php">',
                                    '<img class="nav-icon" src="../img/icones/SVG/autre/file.svg" alt="a"/>',
                                    '<span>',$ps3,'</span></a>',
                               '</li>',
                            '</ul>',
                       ' </li>';
                  }
                      

                  echo  '<li class="nav-item">',
                          '<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                          '<img class="nav-icon" src="../img/icones/SVG/autre/book.svg" alt="a"/>',
                          'Passations</a>',
                          '<ul class="collapse" id="pageSubmenu">',
                             '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink highligh-menu" href="encours.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/calendar.svg" alt="a"/>',
                                  '<span>En Cours</span></a>',
                              '</li>',
                            ' <li class="nav-item">',
                                  '<a class="nav-link sub-item phplink highligh-menu" href="historise.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/folder.svg" alt="a"/>',
                                  '<span>Historisées</span></a>',
                             ' </li>',
                          '</ul>',
                      '</li>';

                    // Accès restreint au technicien
                    if($status != 'TECH'){
                      echo 
                       '<li class="nav-item">',
                          '<a href="#pageSubequip" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                          '<img class="nav-icon" src="../img/icones/SVG/autre/rocket-launch.svg" alt="a"/>',
                          'Equipements</a>',
                          '<ul class="collapse" id="pageSubequip">',
                             '<li class="nav-item">',
                                  '<a class="nav-link sub-item phplink highligh-menu" href="organisation.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/settings-1.svg" alt="a"/>',
                                  '<span>',$eq1,'</span></a>',
                              '</li>',
                             '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink highligh-menu" href="modele.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/copy.svg" alt="a"/>',
                                  '<span>',$eq2,'</span></a>',
                              '</li>',
                              '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink highligh-menu" href="outil.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/puzzle.svg" alt="a"/>',
                                  '<span>',$eq3,'</span></a>',
                              '</li>',
                          '</ul>',
                      '</li>';
                    }
                      

                  echo '<li class="nav-item">',
                          '<a href="#pageSubarbo" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">',
                          '<img class="nav-icon" src="../img/icones/SVG/autre/family-tree.svg" alt="a"/>',
                          'Arborescence</a>',
                          '<ul class="collapse" id="pageSubarbo">',
                             '<li class="nav-item">',
                                  '<a class="nav-link sub-item phplink highligh-menu" href="arborescence_equ.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/wrench.svg" alt="a"/>',
                                  '<span>Equipements</span></a>',
                              '</li>',
                             '<li class="nav-item ">',
                                  '<a class="nav-link sub-item phplink highligh-menu" href="arborescence_vis.php">',
                                  '<img class="nav-icon" src="../img/icones/SVG/autre/padnote.svg" alt="a"/>',
                                  '<span>Visites</span></a>',
                              '</li>',
                              '</ul>',
                      '</li>',
                  '</ul>',

              '<div id="content">',
                '<div id="middle">',

                  '<div id="content-data" class="scrollbar">';
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
  get_sider_stats($nbVisiteMoisEnCours, $nbFichesMoisEnCours, $nbVisiteMoisDernier, $nbFichesMoisDernier, $bd);
  $ecartVisitesMois =   $nbVisiteMoisEnCours  -  $nbVisiteMoisDernier;
  $ecartFichesMois  =   $nbFichesMoisEnCours   -  $nbFichesMoisDernier;
  $couleurVisite    =   $ecartVisitesMois >= 0  ? 'class="positif"' : 'class="negatif"';
  $couleurFiche     =   $ecartFichesMois  >= 0  ? 'class="positif"' : 'class="negatif"';

  $nbFiches = 0;
  $nbVisites = 0;
  $nbOp = 0;
  get_sider_admin($nbVisites, $nbFiches, $nbOp, $bd);
 
        echo  

                    ' </div>',

                    '<div id="right_sider">',

                      '<div id="notifications">',          
                       '<img class="sider_icone" src="../img/icones/PNG/sider_droit/notification-flat.png" alt="b"/>',
                       '<h4>Plans de Maintenance</h4>',
                       '<p>Nombre de ',$_SESSION['ps1'],' Crées : ', $nbVisites , '</p>',
                       '<p>Nombre de ',$_SESSION['ps2'],' Crées : ', $nbFiches, '</p>',
                       '<p>Nombre de ',$_SESSION['ps3'],' Crées : ', $nbOp, '</p>',
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
                              '<th scope="row">',$_SESSION['ps1'],'</th>',
                              '<td>',$nbVisiteMoisDernier,'</td>',
                              '<td>', $nbVisiteMoisEnCours, '</td>',
                              '<td ', $couleurVisite,'>', $ecartVisitesMois ,'</td>',
                            '</tr>',

                            '<tr>',
                              '<th scope="row">',$_SESSION['ps2'],'</th>',
                              '<td>',$nbFichesMoisDernier,'</td>',
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
                      '<div class="media">',
                        '<a href="https://github.com/PreviTeam/previ" class="github"><img class="nav-icon" src="../img/icones/SVG/social/github-logo.svg" alt="a"/></a>',
                        '<a href="#" class="facebook"><img class="nav-icon" src="../img/icones/SVG/social/facebook.svg" alt="a"/></a>',
                      '</div>',
                  '</footer>',

              '</div>',
            '</div>';
            $_SESSION['em_status'] === 'ADMIN' && modal_preferences($bd);
  echo
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
    $id && redirection("../deconnexion.php");
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
define('BS_USER', 'glopulus'); // nom de l'utilisateur de la base
define('BS_PASS', 'dbRootP@sswrd'); // mot de passe de l'utilisateur de la base

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
function bd_erreurExit($msg) {
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

    bd_erreurExit($msg);	// => ARRET DU SCRIPT
}


?>