<?php
session_start();
include('dbconn.php');

$conn = new dbconnect();
$r = $conn->connect();
$cod_user = $_SESSION['user_idd'];
$cod_anagr = $_SESSION['user_idd'];


if(isset($_POST['validate'])&& $_POST['validate']!= 0) {
    $i=1;
    $tipo="";

    while(isset($_POST['tipo'.$i]))
    {
        $tipo .= $_POST["tipo".$i];
        if(isset($_POST['perm'.$i])&&$_POST['perm'.$i]!="") $tipo .= $_POST["perm".$i];
        $t=$i+1;
        if(isset($_POST['tipo'.$t])) $tipo.= "-";
        $i++;
        
    }
$mo="";
$query= "UPDATE timesheet SET convalidato = 1, motivo='".$mo."',tipo_giorni='".$tipo."' WHERE id_time=".$_POST['validate'];   
    $result=mysql_query($query, $conn->db);
   if (!$result) mysql_error();

}
    
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="css/admin.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
var link=true;
</script>



<script type='text/javascript'>

function modificaLink(parametro, valore){

    var link = window.location.href;
    if(link.indexOf("?")!=-1){

        link = link + "&"+parametro+"="+valore+"";

    }else{

        link = link + "?"+parametro+"="+valore+"";

    }
    
    window.location.href=link;
}
</script>
<script type="text/javascript">
    function removeItem(key) {
   
    var rtn = window.location.href;
        rtn = rtn.replace(key,"");
    window.location.href=rtn;
}

    </script>
<style type="text/css">
 al:link{ color: black;text-decoration: underline;}
al:visited{ color: black;text-decoration: underline; }
al:hover{ color: black;text-decoration: underline;}
al:active{ color: black;text-decoration: underline;}
</style>
        <!-- TemplateBeginEditable name="doctitle" -->
        <title>Amministrazione</title>
        <!-- TemplateEndEditable -->
        <!-- TemplateBeginEditable name="head" -->
        <!-- TemplateEndEditable -->
 <script src="http://code.jquery.com/jquery-1.9.1.js"></script> 
        <script>

$(document).ready(function(){
    $("form").submit(function(){
    $('select').removeAttr('disabled');
});
    });


            $(function() {
 $('.mod').click(function()  {
    var recupero_id = $(this).attr("id");
    var a=".";
    var b= "*";
    var id=a+recupero_id+b;
    $(id).prop( "disabled", false );
    $(id).prop( "readonly", false );
   
});
    $('#flag').change(function(){
        if($('#flag').val() == '2') {
            $('#flag2*').show(); 
            $('#flag3*').hide(); 
            $('#flag2i*').show(); 
            $('#flag3i*').hide(); 
        } else {
            $('#flag2*').hide(); 
            $('#flag3*').show(); 
            $('#flag2i*').hide(); 
            $('#flag3i*').show(); 
        } 
    });
});
          </script>
        <style type="text/css">
            <!--
            /* ~~ Selettori tag/elemento ~~ */
            ul, ol, dl { /* A causa delle differenze tra i browser, è buona norma impostare a zero il margine e la spaziatura interna negli elenchi. Per uniformità, potete specificare qui i valori desiderati, oppure nelle voci di elenco (LI, DT, DD) contenute negli elenchi. Tenete presente che le impostazioni effettuate qui verranno applicate a cascata all'elenco .nav a meno che non scegliate di scrivere un selettore più specifico. */
                padding: 0;
                margin: 0;
            }
            h1, h2, h3, h4, h5, h6, p {
                margin-top: 0;   /* La rimozione del margine superiore permette di aggirare il problema che si crea quando i margini possono fuoriuscire dal div che li contiene. Il margine inferiore che rimane permetterà di distanziare gli elementi che seguono. */
                padding-right: 15px;
                padding-left: 15px; /* L'aggiunta di una spaziatura ai lati degli elementi contenuti nei div, anziché ai div stessi, consente di evitare ogni calcolo matematico relativo ai riquadri. Come metodo alternativo si può anche utilizzare un div nidificato con spaziatura laterale. */
            }
            a img { /* Questo selettore rimuove il bordo blu predefinito visualizzato in alcuni browser intorno a un'immagine quando è circondata da un collegamento.  */
                border: none;
            }
            
            /* ~~ L'applicazione di stili ai collegamenti del sito deve rispettare questo ordine, compreso il gruppo di selettori che creano l'effetto hover. ~~ */
            a:link {
                color:#414958;
                text-decoration: underline; /* A meno che non vogliate personalizzare i collegamenti in un modo molto particolare, è bene applicare la sottolineatura per permetterne una rapida identificazione visiva. */
            }
            a:visited {
                color: #4E5869;
                text-decoration: underline;
            }
            a:hover, a:active, a:focus { /* Questo gruppo di selettori conferisce alla navigazione tramite tastiera gli stessi effetti hover che si producono quando si usa il mouse. */
                text-decoration: none;
            }
            .ris:hover, .ris:active, .ris:focus, .ris:visited{
                text-decoration: underline;
                color: #000;
                
            }
            
          .flag1
          {
              display: block;
          }
           .flag1i
          {
              display: block;
          }
           .flag2
          {
              display: block;
          }
           .flag2i
          {
              display: block;
          }
           .flag3
          {
              display: block;
          }
           .flag3i
          {
              display: block;
          }
            

            /* ~~ Il piè di pagina ~~ */
            .footer {
                padding: 10px 0;
              
            }

            .ric{
                background: #414958;
                border-radius: 5px;
                padding-right: 10px;
                padding-left: 10px;
                color:#fff;
                height: 30px;
                line-height: 0px;
                background-image: url('img/x.png');
                background-size: 12px;
                background-repeat: no-repeat;
                background-position: right center;
                
            }       
            
        </style></head>

        <body>

        <div class="gridContainer clearfix">
    <div id="div1" class="fluid">
       <div id="header" class="fluid "><img src="img/header.png">
        <div id="riepilogo" class="fluid" style="width:200px;float:right; margin-bottom:-2px;">
                <?php if ($is_admin==1) { ?>
                  <input type="image" title="Torna al Riepilogo" onclick="window.location = 'riepilogo.php'" value="Torna al Riepilogo" src="img/riepilogo.png" name="button_search">
                </div>
<div id="logout" class="fluid" style="width:200px;float:right;" ><input type="image" onclick="window.location = 'logout.php'" title="Logout" value="Logout" src="img/logout.png" name="button_search">
</div>
        </div>

<table align="center" cellspacing="5" id="menu">
  <tbody>
    <tr>
 <th scope="col"><input type="image" src="img/cerca_admin.png" value="Cerca" onclick="window.location = 'amministrazione.php?id=cerca'" class="btn2"></th>
<th scope="col"><input type="image" src="img/dipendenti.png" value="Dipendenti" onclick="window.location = 'amministrazione.php?id=dip'" class="btn2"></th>
<th scope="col"><input type="image" src="img/timesheet.png" value="Timesheet" onclick="window.location = 'amministrazione.php?id=time'" class="btn2"></th>
    <th scope="col"><input type="image" src="img/referenti.png" value="Referenti" onclick="window.location = 'amministrazione.php?id=ass'" class="btn2"></th>
    </tr>
  </tbody>
</table>
    <?php } ?>
    
           
                <?php if ($is_admin==1) { 

      if(isset($_GET['id']) && $_GET['id']=="time"){ include 'timesheet_admin.php';}
     if(isset($_GET['id']) && $_GET['id']=="dip"){  include 'dipendenti_admin.php'; }
      if(isset($_GET['id']) && $_GET['id']=="cerca"){ include 'cerca_admin.php'; }
    if(isset($_GET['id']) && $_GET['id']=="ass"){  include 'assegna_admin.php'; }
                } else echo "<p align='center' style='color:red'>Per accedere alla pagina devi avere i privilegi di amministratore</p>";
?>
                                                                    
                                                                </div>
                                                                    <!-- end .container --></div>
                                                                    </body>
                                                                    </html>




