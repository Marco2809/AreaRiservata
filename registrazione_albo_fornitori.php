<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
if(isset($_POST['login'])){
    $user = new User();
    $login = $user->login($_POST['username'],$_POST['password']);
    //echo $_POST['username']."-".$_POST['password'];
}

if($_GET['error']==1) $login = "User o Password non valide!";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>ALBO FORNITORI ST - Registrazione</title>

    
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    
    <script type="text/javascript" src="./assets/js/forms.js"></script>
    <script type="text/javascript" src="./assets/js/sha512.js"></script>
    
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      CONTENUTO
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
	  	<div class="container">
	  	
                    <form class="form-login-albo" action="./assets/php/process_login.php" method="post" name="loginForm" style="margin-top:5%;">
		        <div class="panel panel-primary filterable">
            <div class="calendar-heading" style="background-color:#66cc99; height:50px;">
                <div style="padding-left:1%;padding-right:1%;padding-top:1%;">
                    <span class="legenda-title" style="color:#fff; font-size:18px; font-weight:600;">REGISTRAZIONE - ALBO FORNITORI</span>
                </div>
            </div>

            <div class="panel-body">

                <form action="albo-fornitori.php?action=fornitori" method="POST" name="create_supplier">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rag_sociale">Ragione sociale:</label>
                            <input type="text" class="form-control" id="rag_sociale" name="rag_sociale" required>
                        </div>
                        <div class="form-group">
                            <label for="p_iva">Partita IVA:</label>
                            <input type="text" class="form-control" id="iva" name="iva" required>
                        </div>
                        <div class="form-group">
                            <label for="indirizzo">Indirizzo:</label>
                            <input type="text" class="form-control" id="indirizzo" name="indirizzo" required>
                        </div>
                        <div class="form-group">
                            <label for="citta">Città:</label>
                            <input type="text" class="form-control" id="citta" name="citta" required>
                        </div>
                        <div class="form-group">
                            <label for="pec">PEC:</label>
                            <input type="text" class="form-control" id="pec" name="pec" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                         <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="cf">Codice Fiscale:</label>
                            <input type="text" class="form-control" id="cf" name="cf" required>
                        </div>
                        <div class="form-group">
                            <label for="cap">CAP:</label>
                            <input type="text" class="form-control" id="cap" name="cap" required>
                        </div>
                        <div class="form-group">
                            <label for="nazione">Nazione:</label>
                            <input type="text" class="form-control" id="nazione" name="nazione" required>
                        </div>
                    </div>

                    <div class="spacing"></div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success" name="submit">
                            <span class="glyphicon glyphicon-ok"></span> Registrati
                        </button>
                        <a href="javascript:history.back()" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left"></span> Indietro
                    </a>
                    </div>

                </form>

            </div>
                </form>
                </center>

		        </div>
		
		          <!-- Modal Password Dimenticata-->
		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Password Dimenticata</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>Inserisci il Tuo indirizzo email per richiedere una nuova password</p>
		                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
		
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Annulla</button>
		                          <button class="btn btn-theme" type="button">Invia</button>
		                      </div>
		                  </div>
		              </div>
		          </div>
		          <!-- modal -->
		
		      </form>	  	
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("assets/img/registrazione-albo-fornitori-bg.jpg", {speed: 500});
    </script>
    <script type="text/javascript">
        $('#loginUsername').keypress(function (e) {
            if (e.which == 13) {
              $('#login').click();
              return false;
            }
        });
        $('#loginPassword').keypress(function (e) {
            if (e.which == 13) {
              $('#login').click();
              return false;
            }
        });
    </script>

  </body>
</html>
