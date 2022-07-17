<?php
session_start();
include('dbconn.php');

$conn = new dbconnect();
$r = $conn->connect();
$cod_user = $_SESSION['user_idd'];

 $sql4_anagrafica = "SELECT 
                                                        is_admin
                                                        FROM login WHERE user_idd=" . $cod_user;


    $result4_anagrafica = mysql_query($sql4_anagrafica, $conn->db);

    if (!$result4_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row = mysql_fetch_array($result4_anagrafica, MYSQL_ASSOC)) {
            $is_admin = $row['is_admin'];
        }
    }

    if(isset($_POST['submit_motivo'])&&$_POST['submit_motivo']=="Invia") {


$query= "UPDATE timesheet SET motivo = '".$_POST['mot']."' WHERE id_time=".$_POST['id_motivo'];   
    $result=mysql_query($query, $conn->db);
   if (!$result) mysql_error();

   echo '<p style="color: red;">Aggiornamento avvenuto con successo</p>';

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Non Convalida</title>
<link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="css/noncovalida.css" rel="stylesheet" type="text/css">
<!-- 
To learn more about the conditional comments around the html tags at the top of the file:
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/

Do the following if you're using your customized build of modernizr (http://www.modernizr.com/):
* insert the link to your js here
* remove the link below to the html5shiv
* add the "no-js" class to the html tags at the top
* you can also remove the link to respond.min.js if you included the MQ Polyfill in your modernizr build 
-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="respond.min.js"></script>
</head>
<body>
<div class="gridContainer clearfix">
  <div id="div1" class="fluid">
  <center>
    
  <div id="contenitore" style="width:300px;
  height:110px;
  margin-right:0px;
  margin-left:0;
  margin-top:15px;
  margin-bottom:100px;
  padding-right:20px;
  padding-left:20px;
  padding-bottom:20px;
  padding-top:20px;
  alignment-adjust:central;
  text-align:center;
  border: 0px solid #CCC;
  border-radius: 12px;
      -moz-box-shadow:    inset 0 0 20px #000000;
   -webkit-box-shadow: inset 0 0 20px #000000;
   box-shadow:         inset 0 0 20px #000000;">
<?php if($is_admin==1) { ?>

   Inserire il motivo della non convalida:
  <th scope="col" style="margin-top:20px;"><br> 
    <form action="" method="post">
<textarea rows="2" cols="20" style="resize:none;" name="mot">
</textarea></th><br>
<input type="image" title="Invia" value="Invia" src="img/invian.png" name="submit_motivo">
  <input type="hidden" name="id_motivo" value="<?php echo $_GET['id'];?>"> 
  </form>
  <?php } else {
    ?>
    Non hai i privilegi necessari per visualizzare la pagina
    <?php } ?>
  </div>
  </center>
  
  </div>
</div>
</body>
</html>
