<?php


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
 * @param       String[]  $tableau_entete     Tableau contenant le Nom des entêtes de colones
 * @param       Lignes[]  $tableau_ligne      Tableau contenant les lignes à inserrer. A créer avec create_table_ligne
 * @param       String    $class              Classe à attribuer au la DataGrid.Si aucune classe n'est à ajouter, mettre la valeur null
 * @param       String    $titre              Titre de la datagid
 *
 * @return      void
 */ 
function create_table($tableau_entete, $tableau_ligne, $class, $titre){
   $classe = $class != null ? $class : '';
   echo '<h2 class="title_table">', $titre,'</h1><table class="table-fill" id="', $classe, '"><thead><tr>';
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


function get_preferences($bd){
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
    $_SESSION['ps1'] = $ps1;
    $ps2 = $tableau['ap_pslvl2'];
    $_SESSION['ps2'] = $ps2;
    $ps3 = $tableau['ap_pslvl3'];
    $_SESSION['ps3'] = $ps3;
     
    $eq1 = $tableau['ap_eqlvl1']; 
    $_SESSION['eq1'] = $eq1;
    $eq2 = $tableau['ap_eqlvl2'];
    $_SESSION['eq2'] = $eq2;
    $eq3 = $tableau['ap_eqlvl3'];
    $_SESSION['eq3'] = $eq3;
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
            '<h5 class="modal-title" id="ModalLabel">Selection</h5>',
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


function dashboard_content($bd){

   /* ----------------------------- Affichage des Fiches En cours de l'utilisateur  ------------------------------------ */
    get_preferences($bd);

     $sql = "SELECT fi_id, fi_designation, ou_designation, rf_debut, rf_em_id, rf_id
             FROM realisation_visite, outil, fiche, realisation_fiche
             WHERE rf_fi_id = fi_id
             AND rf_rv_id = rv_id
             AND rv_ou_id = ou_id
             AND rf_etat = 0
             AND rf_em_id = ".$_SESSION['em_pass_id']."
             GROUP BY rf_id";

      $content =array();
      $entete=array("Fiche", "Equipement", "%", "");
      $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

      while($tableau = mysqli_fetch_assoc($res)){

         $sql3 = "SELECT * FROM realisation_operation WHERE ro_rf_id = ".$tableau['rf_id'];
         $res3 = mysqli_query($bd, $sql3) or bd_erreur($bd, $sql3);
         $nbOpRealisees = 0;
         while($tableau2 = mysqli_fetch_assoc($res3)){
          $nbOpRealisees++;
         }

         $sql2 = "SELECT count(op_id) as nbOp
         FROM fiche, compo_fiche, operation
         WHERE fi_id = cf_fi_id
         AND op_id = cf_op_id
         AND fi_id = '".$tableau['fi_id']."'";
          $res2 = mysqli_query($bd, $sql2) or bd_erreur($bd, $sql2);
          $nbOperationParFiche =  mysqli_fetch_assoc($res2);

          $ligne=array($tableau['fi_designation'],
                 $tableau['ou_designation'],
                 ($nbOpRealisees * 100 / $nbOperationParFiche['nbOp'])."%",
                 '<button type="button" data-id="'.$tableau['rf_id'].'" class="btn btn-link" href="passation.php">Réaliser</button>');
          $content[] = create_table_ligne(null, $ligne);
      }

      if(empty($content))
        $content[] = create_table_ligne(null, array("Rien a afficher"));

      create_table($entete, $content, null, "Mes " .$_SESSION['ps2']." en Cours");


      /* ----------------------------- Affichage des Fiches Non débutées ------------------------------------ */

       $sql = "SELECT rf_id, fi_id, fi_designation, ou_designation
                FROM realisation_fiche, realisation_visite, outil, fiche
                WHERE rf_fi_id = fi_id
                AND rf_rv_id = rv_id
                AND rv_ou_id = ou_id
                AND rf_em_id IS NULL
                GROUP BY rf_id";

      $content =array();
      $entete=array("Fiche", "Equipement", '');
      $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
      while($tableau = mysqli_fetch_assoc($res)){
          $ligne=array(
                      $tableau['fi_designation'],
                      $tableau['ou_designation'], 
                      '<button class="btn btn-link attribute" data-id="'.$tableau['rf_id'].'" href="attribute.php">Débuter</button>');
          $content[] = create_table_ligne(null, $ligne);
      }

      if(empty($content))
        $content[] = create_table_ligne(null, array("Rien a afficher"));

      create_table($entete, $content, null, $_SESSION['ps2'] ." en Attente");
}



function get_nb_fiches_en_cours($bd, $id_rf){

  // Récupération de la réalisation visite
    $sql2 = "SELECT *
            FROM realisation_fiche
            WHERE rf_id = ".$id_rf;
    $res2 = mysqli_query($bd, $sql2) or bd_erreur($bd, $sql2);
    $visite = mysqli_fetch_assoc($res2);

    // Calcul du nombre de fiche en cours dans la visite ciblée
    $sql3 = "SELECT count(rf_fi_id) as nbFichesEnCours
         FROM realisation_fiche
         WHERE rf_etat = 0
         AND rf_rv_id = ".$visite['rf_rv_id'];
    $res3 = mysqli_query($bd, $sql3) or bd_erreur($bd, $sql3);
    $fiches_en_cours = mysqli_fetch_assoc($res3);

    return array(intval($fiches_en_cours['nbFichesEnCours']), $visite['rf_rv_id']);

}


?>