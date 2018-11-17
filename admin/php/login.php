<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Login</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/login.css" rel="stylesheet">

  </head>

  <body class="back-img">
    <div class="container">
      <div class="card card-login mx-auto mt-5 style-container">
        <div class="card-header text-header">ADMINISTRATION</div>
        <div class="card-body">
          <?php
              //ob_start('ob_gzhandler');
              session_start();

              require_once 'bibli_generale.php';

              if (isset($_SESSION['em_id'])){
                  $page = 'index.php';
                  if (isset($_SERVER['HTTP_REFERER'])){
                      $page = $_SERVER['HTTP_REFERER'];
                      $nom_page = url_get_nom_fichier($page);
                      if ($nom_page == 'login.php'){
                          $page = 'index.php'; 
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
                    '<input type="text" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">',
                    '<label for="inputEmail">Identifiant</label>',
                  '</div>',
                '</div>',
                '<div class="form-group">',
                  '<div class="form-label-group">',
                    '<input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" required="required">',
                    '<label for="inputPassword">Mot de Passe</label>',
                  '</div>',
                '</div>',
                '<div class="form-group">',
                  '<!-- Laisser pour le moment au cas ou l on ferait une utilisation des cookies pour se rappeler des id',
                  '<div class="checkbox">',
                    '<label>',
                      '<input type="checkbox" value="remember-me">',
                      'Remember Password',
                    '</label>',
                  '</div>-->',
                '</div>',
                '<input type="submit" class="btn btn-primary btn-block" value="Connexion">',
                '</form>';
              }
              
              function traitement(){
                $bd = bd_connect();
                $code = bd_protect($bd,$_POST['inputEmail']);
                $pass = bd_protect($bd,md5($_POST['inputPassword']));

                $sql = "SELECT em_id FROM EMPLOYE WHERE em_code='$code' AND em_mdp='$pass'";

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

                  $_SESSION['em_id'] = $id;

                  mysqli_free_result($res);
                  mysqli_close($bd);
                  redirige('index.php');
                }
              }

              function redirige($page) {
                header("Location: $page");
                exit();
              }
          ?>
          <div class="text-center">
            <p class="d-block small mt-3">Mot de passe oublié ? <a href="forgot-password.html">Contactez un administrateur</a></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  </body>

</html>
