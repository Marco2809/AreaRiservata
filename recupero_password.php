<?php
session_start();
include('dbconn.php');
//include('Mail.php'); 
//include('Mail/mime.php');

require 'PHPMailer/PHPMailerAutoload.php';

//require_once "/usr/share/php/Mail/mail.php";
$conn = new dbconnect();
$r = $conn->connect();
$cod_user = $_SESSION['user_idd'];
$cod_anagr = $_SESSION['user_idd'];

if(isset($_GET['key'])){
$sql3_anagrafica = "SELECT 
                            user_idd
                            FROM login WHERE recupero='" . $_GET['key'] . "'";


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);
    $p= mysql_num_rows($result3_anagrafica);
    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
         while ($row3 = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $user_idd = $row3['user_idd'];
        }
        if($p==0) $control=="no";
        else $control="ok";
    }

} 
else { 
    $control="";
}


if(isset($_POST['ripristino_pass'])&&$_POST['ripristino_pass']=="Richiedi Password")
{

    $salt       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678';
           $len        = strlen($salt);
           $makepass   = '';
           mt_srand(10000000*(double)microtime());
           for ($i = 0; $i < 12; $i++) {
               $makepass .= $salt[mt_rand(0,$len - 1)];
           }

$sql3_anagrafica = "UPDATE login SET recupero = '".$makepass."' WHERE username = '" . $_POST['email'] . "'";
$result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);


// Same as error_reporting(E_ALL);


        // Constructing the email



$nome = 'AREA RISERVATA';
$posta =  'recuperopassword@service-tech.org';

$mail=new PHPMailer;
$mail->CharSet = 'UTF-8';

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.service-tech.org';                // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'recuperopassword@service-tech.org';       // SMTP username
$mail->Password = 'Iniziale1$$';                         // SMTP password
//$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                     // TCP port to connect to
$mail->addAddress($_POST['email']);
$mail->From = 'areariservata@service-tech.org';
$mail->FromName = 'Area Riservata';
$mail->isHTML(true);
$composizione = "test";

/*$to = $_POST['email'];
$subject = 'Recupero Password - Modulo CV';
*/
$incipit=(date('H')<=13)?"Buongiorno":"Buonasera";
$mail->Body =$incipit.',<br><br> Per ripristinare la password cliccare sul seguente link:<br><br>
<a href="http:/service-tech.org/servicetech/area_riservata/recupero_password.php?key='.$makepass.'">http://service-tech.org/servicetech/area_riservata/recupero_password.php?key='.$makepass.'</a>';

$mail->Subject = 'Area Riservata - Ripristino Password';  

if(!$mail->send()) {
    
    $alert = "<p align='center' style='color:red'>Errore. Nessun messaggio inviato</p>";
  } else {
    $alert = "<p align='center' style='color:green'>Messaggio inviato con successo</p>";
}

/*
$host = "mail.service-tech.org";
$username = "recuperopassword@service-tech.org";
$password = "Iniziale1$$";

$headers = array ('MIME-Version' => '1.0rn',
        'Content-Type' => "text/html; charset=ISO-8859-1rn",
        'From' => $from,
        'To' => $to,
        'Subject' => $subject
     );



ini_set('display_errors','on');
error_reporting(E_ALL);

$smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));

$mail = $smtp->send($to, $headers, $body);


 if (PEAR::isError($mail)) {
    $alert = "<p align='center' style='color:red'>Errore. Nessun messaggio inviato</p>";
  } else {
    $alert = "<p align='center' style='color:green'>Messaggio inviato con successo</p>";
}
*/
}


if(isset($_POST['recupera_pass'])&&$_POST['recupera_pass']=="Aggiorna Password")
{
      
        if (isset($_REQUEST['password']) && $_REQUEST['password'] != '') {
            $password = $_REQUEST['password'];
        } 
          if (isset($_REQUEST['c_password']) && $_REQUEST['c_password'] != '') {
            $c_password = $_REQUEST['c_password'];
        } 
    
    if($password==$c_password) {
        $vuoto="";
$sql_esperienza = "UPDATE login
                        
                           SET 
                           
                           password = '" . $password . "',
                           recupero = '" . $vuoto . "'
                           
                           WHERE user_idd ='" . $user_idd . "'";
         
          $result_esperienza = mysql_query($sql_esperienza, $conn->db);
          
          if (!$result_esperienza) {
              
            die('Errore di inserimento dati : ' . mysql_error());
            
        } else {
            
            $alert = "<p align='center' style='color:green'>La password è stata aggiornata, clicca <a href='login.htm.php'><span style='color:black;'>qui</span></a> per tornare al login!</p>";
        }
       }
        else if($password!=$c_password)
        {
                $alert = "<p align='center' style='color:red'>Le password non corrispondono!</p>";
        }   

}
?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="refresh" content="7200" />
    <title>Recupero Password</title>
    <link rel="stylesheet" href="css/recupero_psw.css" type="text/css" />
    <link type="text/css" rel="stylesheet" href="/ticket/css/font-awesome.min.css?a7d44f8"/>
    <meta name="robots" content="noindex" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script type="text/javascript" src="/ticket/js/jquery-1.8.3.min.js?a7d44f8"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("input:not(.dp):visible:enabled:first").focus();
         });
    </script>
</head>
<div id="div1" class="fluid">
 <div class="container">
 
<body id="loginBody">
<?php echo $alert ?>
<div id="loginBox">
    <h1 id="logo">
        <span class="valign-helper"></span>
        <img src="img/logo_password.png" width="262" height="78"/>
    </h1>
    <div class="banner"><small></small></div>
    <?php 
    if(!isset($_GET['key']) && !isset($alert)) { ?>
         <form action="" method="post">
        <input type="hidden" name="user_idd" value="<?php echo $user_idd?>" /> 
        <fieldset>
        <input type="text" name="email" id="email"  placeholder="Email" autocorrect="off" autocapitalize="off">
                        
          <input class="submit" type="submit" name="ripristino_pass" value="Richiedi Password" style="background: #f5f6f6; 
background: -moz-linear-gradient(top, #f5f6f6 0%, #dbdce2 21%, #b8bac6 49%, #dddfe3 80%, #f5f6f6 100%); 
background: -webkit-linear-gradient(top, #f5f6f6 0%,#dbdce2 21%,#b8bac6 49%,#dddfe3 80%,#f5f6f6 100%); 
background: linear-gradient(to bottom, #f5f6f6 0%,#dbdce2 21%,#b8bac6 49%,#dddfe3 80%,#f5f6f6 100%); width:100%; margin-bottom:10px;">
        </fieldset>
    </form>
    <?php
    }
    else if($control=="ok" && $_GET['key']!="" && !isset($alert)){ ?>
    <form action="" method="post">
        <input type="hidden" name="user_idd" value="<?php echo $user_idd?>" /> 
        <fieldset>
        <input type="text" name="password" id="password"  placeholder="Nuova Password" autocorrect="off" autocapitalize="off">
        <input type="Conferma Nuova Password" name="c_password" id="c_password" placeholder="Conferma Nuova Password" autocorrect="off" autocapitalize="off">
                        
          <input class="submit" type="submit" name="recupera_pass" value="Aggiorna Password" style="background: #f5f6f6; 
background: -moz-linear-gradient(top, #f5f6f6 0%, #dbdce2 21%, #b8bac6 49%, #dddfe3 80%, #f5f6f6 100%); 
background: -webkit-linear-gradient(top, #f5f6f6 0%,#dbdce2 21%,#b8bac6 49%,#dddfe3 80%,#f5f6f6 100%); 
background: linear-gradient(to bottom, #f5f6f6 0%,#dbdce2 21%,#b8bac6 49%,#dddfe3 80%,#f5f6f6 100%); width:100%; margin-bottom:10px;">
        </fieldset>
    </form>
    <?php } 
    else if(isset($alert) && $alert!="")
        {
         echo $alert;
        }
         else {?>
            <span style="color: red;">Non disponi dei privilegi necessari</span>
    <?php
    }
        ?>
</body>
</div>

<div id="copyRights">Copyright &copy;
<a href='http://www.service-tech.org' target="_blank">Service Tech s.r.l.</a></div>
</div>
</body>
</html>