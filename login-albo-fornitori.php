<?php
session_start();
require_once('class/dbconn.php');
require_once('assets/php/functions.php');
require_once('class/user.class.php');
require_once('class/supplier.class.php');
require_once('class/supplier_documents.class.php');
require_once('class/user_supplier.class.php');

$db = (new Database())->dbConnection();

if(isset($_POST['login'])){
    $user = new User();
    $login = $user->login($_POST['username'],$_POST['password']);
}

if($_GET['error'] == 1) {
    $login = "User o Password non valide!";
} else if($_GET['error'] == 2) {
    $login = "Richiesta non valida!";
}

// Gestione registrazione nuovo fornitore
$registerCode = 0;

if(isset($_POST['rag_sociale'])) {
    
    // Creazione utente
    $userCreatedId = 0;
    
    $idDuplicate = null;
    $stmtSelect = $db->prepare("SELECT user_idd FROM login WHERE username = BINARY ? LIMIT 1");
    $stmtSelect->bind_param('s', $_POST['email']);
    $stmtSelect->execute();
    $stmtSelect->store_result();
    $stmtSelect->bind_result($idDuplicate);
    $stmtSelect->fetch();

    if($stmtSelect->num_rows == 0) {
        $username = $_POST['email'];
        $password = hash('sha512', $_POST['p']);
        $stmtInsert = $db->prepare("INSERT INTO login (username, password, email) "
                . "VALUES (?, ?, ?)");
        $stmtInsert->bind_param("sss", $username, $password, $username);
        if(!$stmtInsert->execute()) {
            trigger_error("Errore! " . $db->error, E_USER_WARNING);
        }
        $stmtInsert->close();
        
        $stmtSelectId = $db->prepare("SELECT LAST_INSERT_ID()");
        $stmtSelectId->execute();
        $stmtSelectId->store_result();
        $stmtSelectId->bind_result($userCreatedId);
        $stmtSelectId->fetch();
        
        $registerCode = 1;
    } else {
        $registerCode = -1;
    }
    
    // Creazione fornitore
    if($registerCode == 1) {
        $supplier = new Supplier();
        $supplierDocuments = new SupplierDocuments();

        $currentDate = date('d/m/Y');
        $currentYear = substr($currentDate, 6);
        $dateExpiryYear = substr($currentDate, 0, 6) . ($currentYear + 1);

        $supplier->setRagSociale($_POST['rag_sociale']);
        $supplier->setIva($_POST['iva']);
        $supplier->setCf($_POST['cf']);
        $supplier->setDataInsOE(time());
        $supplier->setIndirizzo($_POST['indirizzo']);
        $supplier->setCap($_POST['cap']);
        $supplier->setCittaProv($_POST['citta']);
        $supplier->setNazione($_POST['nazione']);
        $supplier->setEmail($_POST['email']);
        $supplier->setPEC($_POST['pec']);
        $supplier->setStato(1);
        $supplier->setStatoArt80(1);
        $supplier->setStatoDocScad(3);
        $supplier->setScadAnn(getTimestampFromDate($dateExpiryYear));

        $supplierCreatedId = $code = $supplier->createSupplier();
        $supplierDocuments->createSupplierDocuments($code);

        // Creation of files' directory
        $path = $_SERVER["DOCUMENT_ROOT"] . '/new_area_riservata/documents/' . $supplierCreatedId . '/';
        if(!file_exists($path)) {
            $oldmask = umask(0);
            for($i=0; $i<11; $i++) {
                mkdir($path.($i+1).'/richieste', 0777, true);
                mkdir($path.($i+1).'/documenti', 0777, true);
            }
            umask($oldmask);
        }
    }
    
    // Creazione relazione utente <--> fornitore
    if($registerCode == 1) {
        $userSupplier = new UserSupplier();
        $userSupplier->setID($userCreatedId);
        $userSupplier->setSupplierID($supplierCreatedId);
        $userSupplier->insertRelation();
    }
    
    if($registerCode == -1) {
        $login = "Registrazione non completata!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>ALBO FORNITORI ST - Login</title>

    
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
	  	
                    <form class="form-login" action="./assets/php/process_supplier_login.php" method="post" name="loginForm">
		        <h2 class="form-login-heading">LOGIN</h2>
		        <div class="login-wrap">
                            <input type="text" class="form-control" name="username" id="loginUsername" placeholder="Email" required autofocus>
		            <br>
                            <input type="password" class="form-control" name="password" id="loginPassword" placeholder="Password" required>
		            <label class="checkbox">
		                <!--<span class="pull-right">
		                    <a data-toggle="modal" href="login.php#myModal"> Password dimenticata?</a>
		
		                </span>-->
		            </label>
                            <input class="btn btn-theme btn-block" name="login" id="login"
                                   type="button" value="ENTRA" onclick="formhash(this.form, this.form.loginPassword)">

                            <a href="registrazione-albo-fornitori.php" class="btn btn-theme btn-block">
                                REGISTRATI
                            </a>
                <?php if(isset($login)){ ?>
                            <center>
                                <span style="color: #c91a1a;"><?php echo '<br>'.$login; ?></span>
                            </center>
                            <?php } ?>
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
        $.backstretch("assets/img/login-albo-fornitori-bg.jpg", {speed: 500});
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