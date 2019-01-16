<!DOCTYPE html>
<html lang="fr">

  <head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Previ Login</title>

   <link href="../css/bootstrap.min.css" rel="stylesheet">
   <link href="../css/styles.css" rel="stylesheet">
   <link href="../css/login.css" rel="stylesheet">

   <script src="../js/jquery-3.3.1.slim.min.js"></script>
   <script src="../js/bootstrap.min.js"></script>
   <script src="../js/passation.js"></script>
   

  </head>

  <body class="back-img">
    <div class="container">
      <div class="style-container">
        <div class="card-header text-header">ADMINISTRATION</div>
        <div class="card-body">
          <?php
              session_start();

              require_once 'bibli_generale.php';

              if (isset($_SESSION['em_id'])){
                  $page = 'dashboard.php';
                  if (isset($_SERVER['HTTP_REFERER'])){
                      $page = $_SERVER['HTTP_REFERER'];
                      $nom_page = url_get_nom_fichier($page);
                      if ($nom_page == 'login.php'){
                          $page = 'dashboard.php'; 
                      }
                  }
                  redirige($page);
              }

              $err = ($_POST) ? traitement() : 0;
              contenu($err);

              function contenu($err){
                echo ($err != 0) ? '<div id="erreur">Identifiant ou Mot de passe incorrect</div>' : ' ',
                '<form method="post" action="login.php">',
                '<div class="form-group">',
                  '<div class="form-label-group">',
                    '<label for="inputEmail">Identifiant</label>',
                    '<input type="text" name="inputEmail" id="inputEmail" class="form-control" placeholder="Ex: 25468" required="required" autofocus="autofocus">', 
                  '</div>',
                '</div>',
                '<div class="form-group">',
                  '<div class="form-label-group">',
                    '<label for="inputPassword">Mot de Passe</label>',
                    '<input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="*********" required="required">',
                  '</div>',
                '</div>',
                '<input type="submit" class="btn btn-primary btn-block" value="Connexion">',
                '</form>';
              }
              
              function traitement(){
                $bd = bd_connect();
                $code = bd_protect($bd,$_POST['inputEmail']);
                $pass = bd_protect($bd,md5($_POST['inputPassword']));

                $sql = "SELECT em_id, em_status FROM EMPLOYE WHERE em_code='$code' AND em_mdp='$pass'";

                $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

                if(mysqli_num_rows($res) != 1)
                {
                  mysqli_free_result($res);
                  mysqli_close($bd);
                  return -1;
                }
                else
                {
                  $t = mysqli_fetch_assoc($res);
                  $id = $t['em_id'];
                  $status = $t['em_status'];

                  $_SESSION['em_id'] = $id;
                  $_SESSION['em_status'] = $status;

                  mysqli_free_result($res);
                  mysqli_close($bd);
                  redirige('dashboard.php');
                }
              }

              function redirige($page) {
                header("Location: $page");
                exit();
              }

              function url_get_nom_fichier($url){
                $nom = basename($url);
                $pos = mb_strpos($nom, '?', 0, 'UTF-8');
                if ($pos !== false){
                    $nom = mb_substr($nom, 0, $pos, 'UTF-8');
                }
                return $nom;
              }
          ?>
          <div class="text-center">
            <p class="d-block small mt-3">Mot de passe oublié ? </p><p><a href="forgot-password.html">Contactez un administrateur</a></p>
          </div>
        </div>
      </div>
    </div>
  </body>

</html>
