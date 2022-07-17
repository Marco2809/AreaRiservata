<?php
session_start();
include('dbconn.php');

$conn = new dbconnect();
$r = $conn->connect();
/*
$fd= fopen ("./files/CV.csv", "r");
$x=0;

while (!feof ($fd))
{
   $riga=fgets($fd, 4096);
   if($riga!="")
   {
       $riga = str_replace("'","",$riga);
      $arr=split(';', $riga);
      for($i=1;$i<count($arr);$i++){
          $email=split(" ",$arr[$i]);
          for($t=0;$t<3;$t++){
              $con[$i]="";
              if(trim($email[0])=="D"||trim($email[0])=="DI"||trim($email[0])=="DEL") $con[$i] .= trim($email[2]).".".trim($email[0]).trim($email[1]);
              else if(isset($email[2])&&$email[2]!=""){
                  $con[$i] .= trim($email[2]).".".trim($email[0]);
                                
              } else {
                        $con[$i] .= trim($email[1]).".".trim($email[0]);
                                if(isset($email[2])) $con[$i].= trim($email[2]);
              }

                      
              $con[$i] .= "@service-tech.org";
          }
          //print $arr[$i]."<br>";
      }
      $x++;
   }
}
for($f=0;$f<count($con);$f++){
    print strtolower($con[$f])."<br>";
}
print "";
fclose($fd);

*/

if(isset($_POST['modifica'])){
    
    if(isset($_REQUEST['ora_inizio'])&&$_REQUEST['ora_inizio']!=""&&isset($_REQUEST['minuti_inizio'])&&$_REQUEST['minuti_inizio']!=""){
        $ora_inizio = $_REQUEST['ora_inizio'].":".$_REQUEST['minuti_inizio'];
    }
    if(isset($_REQUEST['ora_fine'])&&$_REQUEST['ora_fine']!=""&&isset($_REQUEST['minuti_fine'])&&$_REQUEST['minuti_fine']!=""){
        $ora_fine = $_REQUEST['ora_fine'].":".$_REQUEST['minuti_fine'];
    }
   if(isset($_REQUEST['durata'])&&$_REQUEST['durata']!=""){
        $durata = $_REQUEST['durata'];
    }
    if(isset($_REQUEST['descrizione_attivita'])){
        $descrizione_attivita = $_REQUEST['descrizione_attivita'];
    }
     if(isset($_REQUEST['commessa'])){
        $commessa = $_REQUEST['commessa'];
    }
      if(isset($_REQUEST['tipo'])){
        $tipo = $_REQUEST['tipo'];
    }
    $stato="Non Validato";
    $query="UPDATE attivita SET ora_inizio='".$ora_inizio."',ora_fine='".$ora_fine."',ore_lavorate='".$durata."',commessa='".$commessa."',descrizione='".$descrizione_attivita."',tipo='".$tipo."',stato='".$stato."' WHERE id_attivita=". $_POST['id_attivita'];
    $query_result=  mysql_query($query,$conn->db);
    //echo mysql_error();
   if (!$query_result) {
        die('Errore di aggiornamento dati: ' . mysql_error());
    } else {
            $alert = "<p align='center' style='color:green'>Attività modificata con successo!</p>";
        }
        
        if(isset($alert)&&$alert!=""){
            echo $alert;
        }
}



if(isset($_POST['elimina'])){
    
    $query="DELETE FROM attivita WHERE id_attivita=". $_POST['id_attivita'];
    $query_result=  mysql_query($query,$conn->db);
    //echo mysql_error();
   if (!$query_result) {
        die('Errore di aggiornamento dati: ' . mysql_error());
    } else {
            $alert = "<p align='center' style='color:green'>Attività rimossa con successo!</p>";
        }
        
        if(isset($alert)&&$alert!=""){
            echo $alert;
        }
}

if(isset($_POST['aggiungi'])){
    
    if(isset($_REQUEST['ora_inizio'])&&$_REQUEST['ora_inizio']!=""&&isset($_REQUEST['minuti_inizio'])&&$_REQUEST['minuti_inizio']!=""){
        $ora_inizio = $_REQUEST['ora_inizio'].":".$_REQUEST['minuti_inizio'];
    }
    if(isset($_REQUEST['ora_fine'])&&$_REQUEST['ora_fine']!=""&&isset($_REQUEST['minuti_fine'])&&$_REQUEST['minuti_fine']!=""){
        $ora_fine = $_REQUEST['ora_fine'].":".$_REQUEST['minuti_fine'];
    }
   if(isset($_REQUEST['durata'])&&$_REQUEST['durata']!=""){
        $durata = $_REQUEST['durata'];
    }
    if(isset($_REQUEST['descrizione_attivita'])){
        $descrizione_attivita = $_REQUEST['descrizione_attivita'];
    }
     if(isset($_REQUEST['commessa'])){
        $commessa = $_REQUEST['commessa'];
    }
      if(isset($_REQUEST['tipo'])){
        $tipo = $_REQUEST['tipo'];
    }
    
    $query="INSERT INTO attivita (id_utente,ora_inizio,ora_fine,ore_lavorate,commessa,descrizione,giorno,mese,anno,tipo) 
        VALUES ('".$_SESSION['user_idd']."','".$ora_inizio."','".$ora_fine."','".$durata."','".$commessa."','".$descrizione_attivita."','".$_GET['day']."','".$_GET['month']."','".$_GET['year']."','".$tipo."')";
    $query_result=  mysql_query($query,$conn->db);
    //echo mysql_error();
   if (!$query_result) {
        die('Errore di aggiornamento dati: ' . mysql_error());
    } else {
            $alert = "<p align='center' style='color:green'>Attività aggiunta con successo!</p>";
        }
        
        if(isset($alert)&&$alert!=""){
            echo $alert;
        }
}

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
} else {
    $action = '';
}

switch ($action) {
		
case 'Vai':

if (isset($_REQUEST['mese'])&&$_REQUEST['mese']!="") {
            $id_mese = $_REQUEST['mese'];
        } 
		
if (isset($_REQUEST['anno'])&&$_REQUEST['anno']!="") {
            $id_anno = $_REQUEST['anno'];
        } 

break;
	
}

if (!isset($id_mese)) {
            $id_mese = date("m");
        } 
		
if (!isset($id_anno)) {
            $id_anno = date("Y");
        }

if (!isset($_REQUEST['num_giorni'])) {
			if(!checkdate($id_mese,28+1,$id_anno)) { $num_giorni = 28;}
			else if(!checkdate($id_mese,29+1,$id_anno)) { $num_giorni = 29;}
			else if(!checkdate($id_mese,30+1,$id_anno)) { $num_giorni = 30;}
			else if(!checkdate($id_mese,31+1,$id_anno)) { $num_giorni = 31;}
}

if(isset($_SESSION['logged'])) {

	if($id_mese==1) {$mese="Gennaio"; $id_mese="01";}
	if($id_mese==2) {$mese="Febbraio"; $id_mese="02";}
	if($id_mese==3) {$mese="Marzo"; $id_mese="03";}
	if($id_mese==4) {$mese="Aprile"; $id_mese="04";}
	if($id_mese==5) {$mese="Maggio"; $id_mese="05";}
	if($id_mese==6) {$mese="Giugno"; $id_mese="06";}
	if($id_mese==7) {$mese="Luglio"; $id_mese="07";}
	if($id_mese==8) {$mese="Agosto"; $id_mese="08";}
	if($id_mese==9) {$mese="Settembre"; $id_mese="09";}
	if($id_mese==10) $mese="Ottobre"; 
	if($id_mese==11) $mese="Novembre"; 
	if($id_mese==12) $mese="Dicembre"; 
	
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Timesheet</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css\bootstrap.css" rel="stylesheet" type="text/css">
	<link href="css/styles.css" rel="stylesheet">
	<link href="css/calendario.css" rel="stylesheet">
	<link href="css/calendario.scss" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script language="javascript"> 
    function toggleMe(obj, a){ 
      var e=document.getElementById(a); 
      if(obj=="PR"){ 
        e.style.display="block"; 
      }else{ 
    e.style.display="none"; 
} 
    } 
</script> 
        <script type="text/javascript">
        
function durata_tot(id){ 
    if(id=="0") id = "";
       var id1 = "minuti_fine"+id;
       var id2 = "ora_fine"+id;
       var id3 = "ora_inizio"+id;
       var id4 = "minuti_inizio"+id;
       var id5 = "durata"+id;
       

      if(document.getElementById(id1).value=="Minuti") var mf=0;
      else mf=(document.getElementById(id1).value)-0;

      if(document.getElementById(id3).value=="Ora") var hi=0;
      else hi=(document.getElementById(id3).value)-0; 
      if(document.getElementById(id2).value=="Ora") hf=0;
      else hf=(document.getElementById(id2).value)-0;
      if(document.getElementById(id4).value=="Minuti") mi=0;
      else mi=(document.getElementById(id4).value)-0;
      
      
      var ore = hf-hi;
      var minuti = mf - mi;
       if(hi<=13&&hf>=14) ore = ore - 1;
      if(minuti<0) var minuti = minuti + 60;
      
      if(ore<0||(hf==hi&&mi>mf)) var somma = "Errore";
      else var somma = ore + "h" + minuti;

     

      document.getElementById(id5).value = somma;
      }  
     
        
        </script>
    <script type="text/javascript">
        $("#day").click(function()
{
    alert('ciao');
    document.location.href = "amministrazione.php";
});
        </script>
    <script type="text/javascript">
function comparsa1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display=""; document.getElementById(a).scrollIntoView();} else {document.getElementById(a).style.display="none";} }
</script>
</head>
<body style="background-color:#f1f1f1;">

<?php include('menu_top.php');

if (isset($_SESSION['logged'])&&!isset($_GET['day'])&&!isset($_GET['id'])) {
    ?>

<br>
<center>

<form action="" method="post">
  <!-- Tasti e selezione -->
	<center>
    <div class="section">
      <div class="container">
        <div class="row" style="max-width:30%;">
          <div class="col-md-4">
<select class="shadows" name="mese" class="margin-top:8px;">
                                                <?php for($month=1;$month<=12;$month++)
 {
	 if($month==1) $mese_cal="Gennaio"; 
	if($month==2) $mese_cal="Febbraio"; 
	if($month==3) $mese_cal="Marzo"; 
	if($month==4) $mese_cal="Aprile"; 
	if($month==5) $mese_cal="Maggio"; 
	if($month==6) $mese_cal="Giugno"; 
	if($month==7) $mese_cal="Luglio"; 
	if($month==8) $mese_cal="Agosto"; 
	if($month==9) $mese_cal="Settembre"; 
	if($month==10) $mese_cal="Ottobre"; 
	if($month==11) $mese_cal="Novembre"; 
	if($month==12) $mese_cal="Dicembre"; 
	 ?>
                            <option <?php if($id_mese==$month) { ?>selected="selected"<?php } ?> value="<?php echo $month; ?>"><?php echo $mese_cal; ?></option>
                            <?php 
 }
 
 ?>
                            </select>
                            </div>
          <div class="col-md-4">
<select class="shadows" name="anno" class="margin-top:8px;">
                                                <?php for($year=date('Y');$year>=2010;$year--)
 {
	 ?>
                            <option <?php if($id_anno==$year) { ?>selected="selected"<?php } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                            <?php 
 }
 
 ?>
                            </select>
          </div>
       
          <div class="col-md-4">
                            <input type="image" name="action" src="img/vai2.png" value="Vai" style="margin-top:-4%; max-width:100%;">
                               
          </div>
    </div>
    </div>
        </form>
        <?php /*$query="SELECT * from commesse where id_responsabile=".$_SESSION['user_idd'];
                $result = mysql_query($query,$conn->db);*/
                if(isset($_SESSION['responsabile'])&&$_SESSION['responsabile']==1){
              
                ?>
         <!--<th scope="col">
                            
             <input type="button" name="admin_attivita" value="Convalida Attività" onclick="location.href='calendario.php?id=attivita'">
                            </th>
          <th scope="col">
                            
             <input type="button" name="admin_commessa" value="Abilita Utente" onclick="location.href='calendario.php?id=utenti'">
                            </th>-->
                <?php } ?>
   
</center>
 </form>
<center>

<div class="container">
<?php if($alert!="") echo '<div class="row"><div class="col-md-12">'.$alert.'</div></div>'; ?>
<?php if(isset($motivo)&&$motivo!="") {?> <h2 id="motivo" class="fluid " style="font-family:play; font-size:20px; margin-top:-20px; color:red;">Il Timesheet di questo mese non è stato convalidato<br><?php echo $motivo;?></h2><?php }?>
<?php if(isset($convalidato)&&$convalidato==1) {?> <h2 id="motivo" class="fluid " style="font-family:play; font-size:20px; margin-top:-20px; color:green;">Il Timesheet di questo mese è stato convalidato</h2><?php }?>

	<center>
	<div class="mese">
	<span class="mese"><?php echo $mese ." ".$id_anno?></span>
	</div>
	</center>
 <form action="" method="post">
     <div class="grid-calendar">
		<div class="row calendar-week-header">
			<div class="col-xs-1 giornisettimana"><div><div><span class="days">LUN</span></div></div></div>
			<div class="col-xs-1 giornisettimana"><div><div><span class="days">MAR</span></div></div></div>
			<div class="col-xs-1 giornisettimana"><div><div><span class="days">MER</span></div></div></div>
			<div class="col-xs-1 giornisettimana"><div><div><span class="days">GIO</span></div></div></div>
			<div class="col-xs-1 giornisettimana"><div><div><span class="days">VEN</span></div></div></div>
			<div class="col-xs-1 giornisettimana"><div><div><span class="days">SAB</span></div></div></div>
			<div class="col-xs-1 giornisettimana"><div><div><span class="days">DOM</span></div></div></div>
		</div>
		<?php

		$inizio = date("l", mktime(0, 0, 0, $id_mese, 1, $id_anno));

                
		if($inizio=="Monday")
		{
			?>
            <?php
			
		for($i=1;$i<=$num_giorni;$i++)
		{
                    
                    $timesheet = "SELECT  
				ore_lavorate,
                                stato,
                                tipo
                                                        
                                FROM attivita 
                                    
                                                   WHERE id_utente='" . $_SESSION['user_idd']."'"
                                                            . "AND giorno = '". $i."'"
                                                         . "AND mese = '". $id_mese . "' "
                                                         . "AND anno = '". $id_anno."'";

    $result_timesheet = mysql_query($timesheet, $conn->db);
	$somma=0;
        $stato="";
	if (mysql_num_rows($result_timesheet) > 0) {
	$control=0;
while ($row = mysql_fetch_array($result_timesheet, MYSQL_ASSOC)) {
    
 if($row['stato']=="Non Validato") { $stato = "Non Validato"; $control = 1;}
 if($row['stato']=="Rifiutato"&&$control!=1) {$stato = "<span style='color:red;'>Rifiutato</span>"; $control = 1;}
 if($row['stato']=="Validato"&&$control!=1&&$control!=2) {$stato = "<span style='color:green;'>Validato</span>";}
 if($row['tipo']=="P" ) $somma=$somma + $row['ore_lavorate'];
    
}
  
        }
			$festa="";
			if($id_mese==1 && $i==1) $festa="Capodanno";
			if($id_mese==1 && $i==6) $festa="Befana";
			if($id_mese==4 && $i==25) $festa="Liberazione";
			if($id_mese==5 && $i==1) $festa="Lavoratori";
			if($id_mese==6 && $i==2) $festa="Repubblica";
			if($id_mese==8 && $i==15) $festa="Ferragosto";
			if($id_mese==11 && $i==1) $festa="Ognissanti";
			if($id_mese==12 && $i==8) $festa="Immacolata";
			if($id_mese==12 && $i==25) $festa="Natale";
			if($id_mese==12 && $i==26) $festa="Stefano";
		   if($i==1||$i==8||$i==15||$i==22||$i==29)
		   {
                       ?>
      
      <div class="row calendar-week">
          
         <?php
		   }
		   ?>
          <div <?php if($festa==""){?> class="col-xs-1 grid-cell" <?php } ?>
        <?php if($festa!="") { ?> class="col-xs-1 grid-cell festivo" <?php } ?> onclick="location.href='calendario.php?day=<?php echo $i;?>&month=<?php echo $id_mese?>&year=<?php echo $id_anno?>';" style="cursor:pointer;">
			<center><span><?php echo $i;?></span></center><br/><a href="#"><span><?php echo $somma ?> su 8<br>
                     <?php echo $stato?><br></span></a>     
          </div>
		<?php
			if($i==7||$i==14||$i==21||$i==28)
			{
		?> 
          
         	</div>
         <?php
		 	}
		}
		}
		if($inizio=="Tuesday")
		{
			?>
          
            <?php
		for($i=2;$i<=$num_giorni+1;$i++)
		{
                    
                    $t=$i-1;
                                  $timesheet = "SELECT  
				ore_lavorate,
                                stato,
                                tipo
                                                        
                                FROM attivita 
                                    
                                                   WHERE id_utente='" . $_SESSION['user_idd']."'"
                                                            . "AND giorno = '". $t."'"
                                                         . "AND mese = '". $id_mese . "' "
                                                         . "AND anno = '". $id_anno."'";

    $result_timesheet = mysql_query($timesheet, $conn->db);
	$somma=0;
        $stato="";
        if (mysql_num_rows($result_timesheet) > 0) {
	$control=0;
while ($row = mysql_fetch_array($result_timesheet, MYSQL_ASSOC)) {
    
if($row['stato']=="Non Validato") { $stato = "Non Validato"; $control = 1;}
 if($row['stato']=="Rifiutato"&&$control!=1) {$stato = "<span style='color:red;'>Rifiutato</span>"; $control = 1;}
 if($row['stato']=="Validato"&&$control!=1&&$control!=2) {$stato = "<span style='color:green;'>Validato</span>";}
 if($row['tipo']=="P" ) $somma=$somma + $row['ore_lavorate'];
    
}
  
        }
			
			$festa="";
			if($id_mese==1 && $i==2) $festa="Capodanno";
			if($id_mese==1 && $i==7) $festa="Befana";
			if($id_mese==4 && $i==26) $festa="Liberazione";
			if($id_mese==5 && $i==2) $festa="Lavoratori";
			if($id_mese==6 && $i==3) $festa="Repubblica";
			if($id_mese==8 && $i==16) $festa="Ferragosto";
			if($id_mese==11 && $i==2) $festa="Ognissanti";
			if($id_mese==12 && $i==9) $festa="Immacolata";
			if($id_mese==12 && $i==26) $festa="Natale";
			if($id_mese==12 && $i==27) $festa="Stefano";
		   if($t==1||$t==7||$t==14||$t==21||$t==28)
		   { 
		?> 
          <div class="row calendar-week">
              <?php if($t==1){?><div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div><?php } ?>
         <?php
		   }
		   ?>
          <div <?php if($festa==""){?> class="col-xs-1 grid-cell" <?php } ?>
        <?php if($festa!="") { ?> class="col-xs-1 grid-cell festivo" <?php } ?> onclick="location.href='calendario.php?day=<?php echo $i-1;?>&month=<?php echo $id_mese?>&year=<?php echo $id_anno?>';" style="cursor:pointer;">
       
			<center><span><?php echo $i-1;?></span></center><br/><a href="#"><span><?php echo $somma ?> su 8<br>
                     <?php echo $stato?><br></span></a>

          </div>
		<?php
			if($t==6||$t==13||$t==20||$t==27)
			{
		?> 
          
         	</div>
         <?php
		 	}
		}
		}
		if($inizio=="Wednesday")
		{
			?>
            
            <?php
		for($i=3;$i<=$num_giorni+2;$i++)
		{
                    $t=$i-2;
                                  $timesheet = "SELECT  
				ore_lavorate,
                                stato,
                                tipo
                                                        
                                FROM attivita 
                                    
                                                   WHERE id_utente='" . $_SESSION['user_idd']."'"
                                                            . "AND giorno = '". $t."'"
                                                         . "AND mese = '". $id_mese . "' "
                                                         . "AND anno = '". $id_anno."'";

    $result_timesheet = mysql_query($timesheet, $conn->db);
	$somma=0;
        $stato="";
	if (mysql_num_rows($result_timesheet) > 0) {
	$control=0;
while ($row = mysql_fetch_array($result_timesheet, MYSQL_ASSOC)) {
    
 if($row['stato']=="Non Validato") { $stato = "Non Validato"; $control = 1;}
 if($row['stato']=="Rifiutato"&&$control!=1) {$stato = "<span style='color:red;'>Rifiutato</span>"; $control = 1;}
 if($row['stato']=="Validato"&&$control!=1&&$control!=2) {$stato = "<span style='color:green;'>Validato</span>";}
 if($row['tipo']=="P" ) $somma=$somma + $row['ore_lavorate'];
    
}
  
        }
			
			$festa="";
			if($id_mese==1 && $i==3) $festa="Capodanno";
			if($id_mese==1 && $i==8) $festa="Befana";
			if($id_mese==4 && $i==27) $festa="Liberazione";
			if($id_mese==5 && $i==3) $festa="Lavoratori";
			if($id_mese==6 && $i==4) $festa="Repubblica";
			if($id_mese==8 && $i==17) $festa="Ferragosto";
			if($id_mese==11 && $i==3) $festa="Ognissanti";
			if($id_mese==12 && $i==10) $festa="Immacolata";
			if($id_mese==12 && $i==27) $festa="Natale";
			if($id_mese==12 && $i==28) $festa="Stefano";
		   if($t==1||$t==6||$t==13||$t==20||$t==27)
		   {
		?> 
         <div class="row calendar-week" style="width:100%">
              <?php if($t==1){?><div class="col-xs-1 grid-cell" >
               <div><div>
			
			</div></div>  
              </div>
                  <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <?php } ?>
         <?php
		   }
		   ?>
               <div <?php if($festa==""){?> class="col-xs-1 grid-cell" <?php } ?>
        <?php if($festa!="") { ?> class="col-xs-1 grid-cell festivo" <?php } ?> onclick="location.href='calendario.php?day=<?php echo $i-2;?>&month=<?php echo $id_mese?>&year=<?php echo $id_anno?>';" style="cursor:pointer;">
         
			<center><span><?php echo $i-2;?></span></center><br/><a href="#"><span><?php echo $somma ?> su 8<br>
                     <?php echo $stato?><br></span></a>
	
          </div>
		<?php
			if($t==5||$t==12||$t==19||$t==26)
			{
		?> 
          
         	</div>
         <?php
		 	}
		}
	}
	if($inizio=="Thursday")
		{
			?>
            <tr><td><div class="day"></div></td>
            <td><div class="day"></div></td>
            <td><div class="day"></div></td>
            <?php
		for($i=4;$i<=$num_giorni+3;$i++)
		{            
                    $t=$i-3;
                    $timesheet = "SELECT  
				ore_lavorate,
                                stato,
                                tipo
                                                        
                                FROM attivita 
                                    
                                                   WHERE id_utente='" . $_SESSION['user_idd']."'"
                                                            . "AND giorno = '". $t."'"
                                                         . "AND mese = '". $id_mese . "' "
                                                         . "AND anno = '". $id_anno."'";

    $result_timesheet = mysql_query($timesheet, $conn->db);
	$somma=0;
        $stato="";
	if (mysql_num_rows($result_timesheet) > 0) {
	$control=0;
while ($row = mysql_fetch_array($result_timesheet, MYSQL_ASSOC)) {
    
 if($row['stato']=="Non Validato") { $stato = "Non Validato"; $control = 1;}
 if($row['stato']=="Rifiutato"&&$control!=1) {$stato = "<span style='color:red;'>Rifiutato</span>"; $control = 1;}
 if($row['stato']=="Validato"&&$control!=1&&$control!=2) {$stato = "<span style='color:green;'>Validato</span>";}
 if($row['tipo']=="P" ) $somma=$somma + $row['ore_lavorate'];
    
    
}
  
        }
			$festa="";
			if($id_mese==1 && $i==4) $festa="Capodanno";
			if($id_mese==1 && $i==9) $festa="Befana";
			if($id_mese==4 && $i==28) $festa="Liberazione";
			if($id_mese==5 && $i==4) $festa="Lavoratori";
			if($id_mese==6 && $i==5) $festa="Repubblica";
			if($id_mese==8 && $i==18) $festa="Ferragosto";
			if($id_mese==11 && $i==4) $festa="Ognissanti";
			if($id_mese==12 && $i==11) $festa="Immacolata";
			if($id_mese==12 && $i==28) $festa="Natale";
			if($id_mese==12 && $i==29) $festa="Stefano";
		   if($t==1||$t==8||$t==15||$t==22||$t==29)
		   {
		?> 
         <div class="row calendar-week">
              <?php if($t==1){?><div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
             <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <?php } ?>
         <?php
		   }
		   ?>
               <div <?php if($festa==""){?> class="col-xs-1 grid-cell" <?php } ?>
        <?php if($festa!="") { ?> class="col-xs-1 grid-cell festivo" <?php } ?> onclick="location.href='calendario.php?day=<?php echo $i-3;?>&month=<?php echo $id_mese?>&year=<?php echo $id_anno?>';" style="cursor:pointer;">
       
			<center><span><?php echo $i-3;?></span></center><br/><a href="#"><span><?php echo $somma ?> su 8<br>
                     <?php echo $stato?><br></span></a>
	     
          </div>
		<?php
			if($i==4||$i==11||$i==18||$i==25)
			{
		?> 
         	</tr>
         <?php
		 	}
		}
	}
	if($inizio=="Friday")
		{
			?>

            <?php
		for($i=5;$i<=$num_giorni+4;$i++)
		{
                    
                    $t=$i-4;
                    $timesheet = "SELECT  
				ore_lavorate,
                                stato,
                                tipo
                                                        
                                FROM attivita 
                                    
                                                   WHERE id_utente='" . $_SESSION['user_idd']."'"
                                                            . "AND giorno = '". $t."'"
                                                         . "AND mese = '". $id_mese . "' "
                                                         . "AND anno = '". $id_anno."'";

    $result_timesheet = mysql_query($timesheet, $conn->db);
	$somma=0;
        $stato="";
	if (mysql_num_rows($result_timesheet) > 0) {
	$control=0;
while ($row = mysql_fetch_array($result_timesheet, MYSQL_ASSOC)) {
    
 if($row['stato']=="Non Validato") { $stato = "Non Validato"; $control = 1;}
 if($row['stato']=="Rifiutato"&&$control!=1) {$stato = "<span style='color:red;'>Rifiutato</span>"; $control = 1;}
 if($row['stato']=="Validato"&&$control!=1&&$control!=2) {$stato = "<span style='color:green;'>Validato</span>";}
 if($row['tipo']=="P" ) $somma=$somma + $row['ore_lavorate'];
    
    
}
  
        }
			$festa="";
			if($id_mese==1 && $i==5) {$festa="Capodanno";}
			if($id_mese==1 && $i==10){ $festa="Befana";}
			if($id_mese==4 && $i==29) $festa="Liberazione";
			if($id_mese==5 && $i==5) $festa="Lavoratori";
			if($id_mese==6 && $i==6) $festa="Repubblica";
			if($id_mese==8 && $i==19) $festa="Ferragosto";
			if($id_mese==11 && $i==5) $festa="Ognissanti";
			if($id_mese==12 && $i==12) $festa="Immacolata";
			if($id_mese==12 && $i==29) $festa="Natale";
			if($id_mese==12 && $i==30) $festa="Stefano";
		   if($t==1||$t==4||$t==11||$t==18||$t==25)
		   {
		?> 
         <div class="row calendar-week">
              <?php if($t==1){?><div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
             <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
             <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <?php } ?>
         <?php
		   }
                   
		   ?>
               <div <?php if($festa==""){?> class="col-xs-1 grid-cell" <?php } ?>
        <?php if($festa!="") { ?> class="col-xs-1 grid-cell festivo" <?php } ?> onclick="location.href='calendario.php?day=<?php echo $i-4;?>&month=<?php echo $id_mese?>&year=<?php echo $id_anno?>';" style="cursor:pointer;">
          
			<center><span><?php echo $i-4;?></span></center><br/><a href="#"><span><?php echo $somma ?> su 8<br>
                     <?php echo $stato?><br></span></a>
 
          </div>
		<?php
			if($t==3||$t==10||$t==17||$t==24)
			{
		?> 
         </div>
         <?php
		 	}
		}
	}
	if($inizio=="Saturday")
		{
			?>
            
            <?php
		for($i=6;$i<=$num_giorni+5;$i++)
		{
                                 $t=$i-5;
                    $timesheet = "SELECT  
				ore_lavorate,
                                stato,
                                tipo
                                                        
                                FROM attivita 
                                    
                                                   WHERE id_utente='" . $_SESSION['user_idd']."'"
                                                            . "AND giorno = '". $t."'"
                                                         . "AND mese = '". $id_mese . "' "
                                                         . "AND anno = '". $id_anno."'";

    $result_timesheet = mysql_query($timesheet, $conn->db);
	$somma=0;
        $stato="";
	if (mysql_num_rows($result_timesheet) > 0) {
		$control=0;
while ($row = mysql_fetch_array($result_timesheet, MYSQL_ASSOC)) {
    
 if($row['stato']=="Non Validato") { $stato = "Non Validato"; $control = 1;}
 if($row['stato']=="Rifiutato"&&$control!=1) {$stato = "<span style='color:red;'>Rifiutato</span>"; $control = 1;}
 if($row['stato']=="Validato"&&$control!=1&&$control!=2) {$stato = "<span style='color:green;'>Validato</span>";}
 if($row['tipo']=="P" ) $somma=$somma + $row['ore_lavorate'];
}
  
        }
			$festa="";
			if($id_mese==1 && $i==6) {$festa="Capodanno";}
			if($id_mese==1 && $i==11){ $festa="Befana";}
			if($id_mese==4 && $i==30) $festa="Liberazione";
			if($id_mese==5 && $i==6) $festa="Lavoratori";
			if($id_mese==6 && $i==7) $festa="Repubblica";
			if($id_mese==8 && $i==20) $festa="Ferragosto";
			if($id_mese==11 && $i==6) $festa="Ognissanti";
			if($id_mese==12 && $i==13) $festa="Immacolata";
			if($id_mese==12 && $i==30) $festa="Natale";
			if($id_mese==12 && $i==31) $festa="Stefano";
		   if($t==1||$t==3||$t==10||$t==17||$t==24)
		   {
		?> 
                 <div class="row calendar-week">
              <?php if($t==1){?><div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
             <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
             <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                      <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <?php } ?>
         <?php
		   }
                   
		   ?>
               <div <?php if($festa==""){?> class="col-xs-1 grid-cell" <?php } ?>
        <?php if($festa!="") { ?> class="col-xs-1 grid-cell festivo" <?php } ?> onclick="location.href='calendario.php?day=<?php echo $i-5;?>&month=<?php echo $id_mese?>&year=<?php echo $id_anno?>';" style="cursor:pointer;">
			<center><span><?php echo $i-5;?></span></center><br/><a href="#"><span><?php echo $somma ?> su 8<br>
                     <?php echo $stato?><br></span></a>   
          </div>
		<?php
			if($t==2||$t==9||$t==16||$t==23||$t==30)
			{
		?> 
         </div>
		
         <?php
		 	}
		}
	}
	if($inizio=="Sunday")
		{
			?>
            
            <?php
		for($i=7;$i<=$num_giorni+6;$i++)
		{
                                 $t=$i-6;
                    $timesheet = "SELECT  
				ore_lavorate,
                                stato,
                                tipo
                                                        
                                FROM attivita 
                                    
                                                   WHERE id_utente='" . $_SESSION['user_idd']."'"
                                                            . "AND giorno = '". $t."'"
                                                         . "AND mese = '". $id_mese . "' "
                                                         . "AND anno = '". $id_anno."'";

    $result_timesheet = mysql_query($timesheet, $conn->db);
	$somma=0;
        $stato="";
	if (mysql_num_rows($result_timesheet) > 0) {
		$control=0;
while ($row = mysql_fetch_array($result_timesheet, MYSQL_ASSOC)) {
    
 if($row['stato']=="Non Validato") { $stato = "Non Validato"; $control = 1;}
 if($row['stato']=="Rifiutato"&&$control!=1) {$stato = "<span style='color:red;'>Rifiutato</span>"; $control = 1;}
 if($row['stato']=="Validato"&&$control!=1&&$control!=2) {$stato = "<span style='color:green;'>Validato</span>";}
 if($row['tipo']=="P" ) $somma=$somma + $row['ore_lavorate'];
    
}
  
        }
			$festa="";
			if($id_mese==1 && $i==7) {$festa="Capodanno";}
			if($id_mese==1 && $i==12){ $festa="Befana";}
			if($id_mese==4 && $i==31) $festa="Liberazione";
			if($id_mese==5 && $i==7) $festa="Lavoratori";
			if($id_mese==6 && $i==8) $festa="Repubblica";
			if($id_mese==8 && $i==21) $festa="Ferragosto";
			if($id_mese==11 && $i==7) $festa="Ognissanti";
			if($id_mese==12 && $i==14) $festa="Immacolata";
			if($id_mese==12 && $i==31) $festa="Natale";
			if($id_mese==12 && $i==32) $festa="Stefano";
		   if($t==1||$t==2||$t==9||$t==16||$t==23||$t==30)
		   {
		?> 
         <div class="row calendar-week">
              <?php if($t==1){?><div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
             <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
             <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                      <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                   <div class="col-xs-1 grid-cell">
               <div><div>
			
			</div></div>  
              </div>
                  <?php } ?>
         <?php
		   }
                   
		   ?>
               <div <?php if($festa==""){?> class="col-xs-1 grid-cell" <?php } ?>
        <?php if($festa!="") { ?> class="col-xs-1 grid-cell festivo" <?php } ?> onclick="location.href='calendario.php?day=<?php echo $i-6;?>&month=<?php echo $id_mese?>&year=<?php echo $id_anno?>';" style="cursor:pointer;">
			<center><span><?php echo $i-6;?></span></center><br/><a href="#"><span><?php echo $somma ?> su 8<br>
                     <?php echo $stato?><br></span></a>   
          </div>
		<?php
			if($t==1||$t==8||$t==15||$t==22||$t==29)
			{
		?> 
         </div>
         <?php
		 	}
		}
	
     
}

	?>
  </tbody>
</table>
<!--<table width="50%" cellspacing="5" id="bordotab" style="margin-top:50px;">
  <tbody>
    <tr>
      <th scope="col" class="span"><strong>Legenda</strong><br></th>
    </tr>
 
    
    <tr>
      <td>P=Presente&nbsp;&nbsp;&nbsp;&nbsp;M=Malattia&nbsp;&nbsp;&nbsp;&nbsp;M1=Maternità</td></td>
    </tr>
    <tr>
      <td>F=Ferie&nbsp;&nbsp;&nbsp;&nbsp;PR=Permessi&nbsp;&nbsp;&nbsp;&nbsp;EX=Ex Festività</td>
      <tr>
      <td>FE=Festività</td>
    </tr>
  </tbody>
</table>
  <input type="image" value="Salva Timesheet" name="action" src="img/salva.png"/><input type="image" src="img/invia.png" value="Invia Timesheet" name="action"/></div>
<input type="hidden" name="numero_giorni" value="<?php echo $num_giorni?>" />
<input type="hidden" name="mese_time" value="<?php echo $id_mese?>" />
<input type="hidden" name="anno_time" value="<?php echo $id_anno?>" />-->
</form>
</div>
</div>
</div>
</div>
 </div>
</div>
<?php 
} else if(isset($_SESSION['logged'])&&isset($_GET['day'])) {
    
    if(!checkdate($_GET['month']-1,28+1,$_GET['year'])) { $num_giorni_prec = 28;}
			else if(!checkdate($_GET['month']-1,29+1,$_GET['year'])) { $num_giorni_prec = 29;}
			else if(!checkdate($_GET['month']-1,30+1,$_GET['year'])) { $num_giorni_prec = 30;}
			else if(!checkdate($_GET['month']-1,31+1,$_GET['year'])) { $num_giorni_prec = 31;}
                        
                            if(!checkdate($_GET['month'],28+1,$_GET['year'])) { $num_giorni_ora = 28;}
			else if(!checkdate($_GET['month'],29+1,$_GET['year'])) { $num_giorni_ora = 29;}
			else if(!checkdate($_GET['month'],30+1,$_GET['year'])) { $num_giorni_ora = 30;}
			else if(!checkdate($_GET['month'],31+1,$_GET['year'])) { $num_giorni_ora = 31;}
    
          if($_GET['day']<10) $realday = "0".$_GET['day'];
        else $realday = $_GET['day'];
    if($_GET['day']>1) { 
        $sx= $_GET['day'] - 1;
        $sx_month = $_GET['month'];
        $sx_year = $_GET['year'];
  
    }
    else { 
        
        if($_GET['day']=="1"&&$_GET['month']=="01"){
            $sx_month = 12;
            $sx_year = $_GET['year'] -1;
            $sx= 31;
        }
        else {

        $sx = $num_giorni_prec;
        if($_GET['month']<=10) {
            $sx_month = $_GET['month'] - 1;
            $sx_month = "0".$sx_month;
        } else {
            $sx_month = $_GET['month'] - 1;
        }
        $sx_year = $_GET['year'];
        }
    }
    
    if($_GET['day']<10) $realday = "0".$_GET['day'];
        else $realday = $_GET['day'];
        
    if($_GET['day']<$num_giorni_ora) { 
        $dx= $_GET['day'] + 1;
        $dx_month = $_GET['month'];
        $dx_year = $_GET['year'];
  
    }
    else { 
        
        if($_GET['day']==31&&$_GET['month']==12){
            $dx_month = "01";
            $dx_year = $_GET['year'] +1;
            $dx= 1;
        }
        else {

        $dx = 1;

        if($_GET['month']<9) {
       
            $dx_month = $_GET['month'] + 1;
            $dx_month = "0".$dx_month;
        } else {
            $dx_month = $_GET['month'] + 1;
        }
        $dx_year = $_GET['year'];
        }
    }   

    ?>
 
<!-- Intestazione Giorno -->
<div class="section" style="margin-top:-3%;">
    <div class="container"> 
	<center>
	<div style="margin-top:1.5%;margin-bottom:1%;">
	<a href="./calendario.php?day=<?php echo $sx?>&month=<?php echo $sx_month?>&year=<?php echo $sx_year?>"><img src="img/sx.png" style="margin-right:25px;"></a>
        <span style="color:#9a2633;font-weight:bold;font-size:30px;">    <?php echo $realday. " - ".$_GET['month'] . " - " . $_GET['year']; ?></span>
              <a href="./calendario.php?day=<?php echo $dx?>&month=<?php echo $dx_month?>&year=<?php echo $dx_year?>"><img src="img/dx.png" style="margin-left:25px;"></a>
	</center>
</div>
</div>
	<!-- Fine Intestazione Giorno -->

              <?php 
              $query_attivita="SELECT * from attivita WHERE giorno=".$_GET['day']." AND mese=".$_GET['month']." AND anno=".$_GET['year']." AND id_utente=".$_SESSION['user_idd'];
              $result_attivita = mysql_query($query_attivita, $conn->db);
              
              $control_attivita=1;
              $c=0;
                while ($row_attivita = mysql_fetch_array($result_attivita, MYSQL_ASSOC)) {
                    $c++;
                    ?>
     <form action="" method="post">
              <!-- Nuova Attività -->
          <!-- Titolo 1 -->
		  
    
                        
          <div class="section" style="margin-top:-5%;">
          <div class="container">
          <div class="titolo-sezione">
		  <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; text-align: left;padding-left:2%;"><?php echo $row_attivita['commessa'];?></span> 
                  <a id="link-esperienza1" onClick="javascript:comparsa1('attivita<?php echo $c; ?>')"><img src="img/apri.png"></img></a>
		  </div>
		  <!-- Fine Titolo 1 -->
   
    <div class="border" id="attivita<?php echo $c;?>"style="display:none;">
<form action="" method="post">
		  <div class="section">
              <div class="container" style="max-width:90%; margin-left:7%;">
            <div class="row" style="padding-bottom:2%;padding-top:0.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600; font-size:16px;">Ora Inizio:</span>
               </div>
               <div class="col-md-3">
                    <select onchange="javascript:durata_tot(<?php echo $control_attivita?>);" id="ora_inizio<?php echo $control_attivita?>" name="ora_inizio">
                        <option>Ora</option>
                        <?php for($i=8;$i<21;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if(substr($row_attivita['ora_inizio'],0,2)==$t){ ?> selected="selected" <?php }?> value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
            <select name="minuti_inizio" onchange="javascript:durata_tot(<?php echo $control_attivita?>);" id="minuti_inizio<?php echo $control_attivita?>">
            <option>Minuti</option>
                        <?php for($i=0;$i<60;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if(substr($row_attivita['ora_inizio'],3,2)==$t){ ?> selected="selected" <?php }?> value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
               </div>
            </div>
        <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;padding-bottom:4%;font-size:16px;">Ora Fine:</span>
               </div>
               <div class="col-md-3">
            <select onchange="javascript:durata_tot(<?php echo $control_attivita?>);" name="ora_fine" id="ora_fine<?php echo $control_attivita?>">
                        <option>Ora</option>
                        <?php for($i=8;$i<21;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if(substr($row_attivita['ora_fine'],0,2)==$t){ ?> selected="selected" <?php }?> value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
            <select onchange="javascript:durata_tot(<?php echo $control_attivita?>);" name="minuti_fine" id="minuti_fine<?php echo $control_attivita?>">
            <option>Minuti</option>
                        <?php for($i=0;$i<60;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if(substr($row_attivita['ora_fine'],3,2)==$t){ ?> selected="selected" <?php }?> value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
               </div>
        </div>
                  
            <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Descrizione:</span>
               </div>
               <div class="col-md-3">
                   <input type="text" name="descrizione_attivita" value="<?php echo $row_attivita['descrizione'];?>">
               </div>
            </div>
                           <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Commessa:</span>
               </div>
               <div class="col-md-3">
                   <select name="commessa">
                                    <?php 
                                    
                                    $query="SELECT commesse from anagrafica WHERE user_id=".$_SESSION['user_idd'];
                                            $result=  mysql_query($query);
                                            
                                            while($row_commesse= mysql_fetch_array($result)){
                                                $commesse_utente=$row_commesse['commesse'];
                                               
                                            }
                                    $com_utente = explode("*", $commesse_utente);
                                    $query_commesse="";
                                    for($i=1;$i<count($com_utente)-1;$i++){
                                        if($i==1) $query_commesse.=" id_commessa='".$com_utente[$i]."'";
                                        else $query_commesse.= " OR id_commessa='".$com_utente[$i]."'";
                                    }
                                    $query="SELECT * from commesse WHERE";
                                    $query.= $query_commesse;
                                            $result=  mysql_query($query);
                                           
                                            while($row_commesse= mysql_fetch_array($result)){
                                            ?>
                                    <option value="<?php echo $row_commesse['nome_commessa']?>" 
                                        <?php if($row_attivita['commessa']==$row_commesse['nome_commessa']){ ?>selected="selected"<?php } ?> ><?php echo $row_commesse['nome_commessa']?></option>
                                    
                                            <?php } ?>
                        </select>
               </div>
                           </div>
        <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Tipo:</span>
               </div>
               <div class="col-md-3"><select name="tipo">
                                    <option <?php if($row_attivita['tipo']=="P") {?>selected="selected"<?php }?> value="P">Presente</option>
                                    <option <?php if($row_attivita['tipo']=="PE") {?>selected="selected"<?php }?>value="PE">Permessi</option>
                                    <option <?php if($row_attivita['tipo']=="F") {?>selected="selected"<?php }?>value="F">Ferie</option>
                                    <option <?php if($row_attivita['tipo']=="FE") {?>selected="selected"<?php }?>value="FE">Festività</option>
                                    <option <?php if($row_attivita['tipo']=="M") {?>selected="selected"<?php }?>value="M">Maternità</option>
                        </select>
               </div>
        </div>
            <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Stato:</span>
               </div>
                   <div class="col-md-3"><?php echo $row_attivita['stato'];?>
            </div>
            </div>
              </div>
                      <?php if($row_attivita['stato']=="Rifiutato"){?>
                   <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Motivo:</span>
               </div>
               <div class="col-md-3"><textarea disabled="disabled" name="motivo_rifiuto" cols="2" rows="4" style="width: 45%;"><?php echo $row_attivita['motivo']?></textarea>
               </div>
                   </div>
      <?php } ?>
        <div class="row" style="margin-bottom:1.5%;">
            <div class="col-md-5"></div>
               <div class="col-md-3">
                   <input type="submit" name="modifica" value="Modifica" class="btn btn-success btn-sm">
 
                <input type="submit" name="elimina" value="Elimina" class="btn btn-danger btn-sm">
            <input type="hidden" name="id_attivita" value="<?php echo $row_attivita['id_attivita']?>">
         </div>
        </div>
              
                  </div></div></div></div></form><?php
} 
                                         
              ?>
        
         <!-- Tasto aggiungi -->
		  <div class="section">
          <div class="container">
          <div style="margin-left:2%;margin-top: -50px;">
		  <a class="btn btn-success btn-md" id="link-lingua2" onclick="mostranascondi('attivita');"> Aggiungi Attività</a>
  		  </a>
		  </div>
		  </div>
                  </div>
		  <!-- Fine Tasto aggiungi -->
        
     
     <form action="" method="post">
        <!-- Nuova Attività -->
          <!-- Titolo 1 -->
          <div class="section" style="margin-top:-5%; display: none;" id="attivita">
          <div class="container">
          <div class="titolo-sezione">
		  <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; text-align: left;padding-left:2%;"> Nuova Attività</span> 
		  </div>
		  <!-- Fine Titolo 1 -->

		  <div class="border" id="istruzione1"style="display:block;">
		  <div class="section">
              <div class="container" style="max-width:90%; margin-left:7%;">
            <div class="row" style="padding-bottom:2%;padding-top:0.5%;">
                <div class="col-md-3">
                  <span style="font-weight:600; font-size:16px;">Ora Inizio:</span>
               </div>
               <div class="col-md-3">
                    <select name="ora_inizio" onchange="javascript:durata_tot('0');" id="ora_inizio">
                        <option>Ora</option>
                        <?php for($i=8;$i<21;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if($t=="09") {?> selected="selected" <?php } ?> value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
            <select name="minuti_inizio" onchange="javascript:durata_tot('0');" id="minuti_inizio">
            <option>Minuti</option>
                        <?php for($i=0;$i<60;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if($t=="00") {?> selected="selected" <?php } ?>  value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
         </div>
            </div>
            <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;padding-bottom:4%;font-size:16px;">Ora Fine:</span>
               </div>
               <div class="col-md-3">
              <select name="ora_fine" onchange="javascript:durata_tot('0');" id="ora_fine">
                        <option>Ora</option>
                        <?php for($i=8;$i<21;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if($t=="18") {?> selected="selected" <?php } ?>  value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
        <select name="minuti_fine" onchange="javascript:durata_tot('0');" id="minuti_fine">
            <option>Minuti</option>
                        <?php for($i=0;$i<60;$i++){
                            if($i<10) $t="0".$i;
                            else $t= $i;
                           ?>
                        <option <?php if($t=="00") {?> selected="selected" <?php } ?>  value="<?php echo $t?>"><?php echo $t?></option>
                        <?php
                        }?>
            </select>
               </div></div>
         <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Durata:</span>
               </div>
               <div class="col-md-3"><input type="text" name="durata" id="durata" value="8h0" style="width: 40px;">
               </div>
         </div>
                   <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Descrizione:</span>
               </div>
               <div class="col-md-3">
                   <input type="text" name="descrizione_attivita" value="">
               </div>
                   </div>
                            <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Commessa:</span>
               </div>
               <div class="col-md-3"><select name="commessa">
                                       <?php 
                                    
                                    $query="SELECT commesse from anagrafica WHERE user_id=".$_SESSION['user_idd'];
                                            $result=  mysql_query($query);
                                            
                                            while($row_commesse= mysql_fetch_array($result)){
                                                $commesse_utente=$row_commesse['commesse'];
                                               
                                            }
                                    $com_utente = explode("*", $commesse_utente);
                                    $query_commesse="";
                                    for($i=1;$i<count($com_utente)-1;$i++){
                                        if($i==1) $query_commesse.=" id_commessa='".$com_utente[$i]."'";
                                        else $query_commesse.= " OR id_commessa='".$com_utente[$i]."'";
                                    }
                                    $query="SELECT * from commesse WHERE";
                                    $query.= $query_commesse;
                                            $result=  mysql_query($query);
                                           
                                            while($row_commesse= mysql_fetch_array($result)){
                                            ?>
                                    <option value="<?php echo $row_commesse['nome_commessa']?>" ><?php echo $row_commesse['nome_commessa']?></option>
                                    
                                            <?php } ?>
                        </select>
               </div>
                            </div>
        <div class="row" style="margin-bottom:1.5%;">
               <div class="col-md-3">
                  <span style="font-weight:600;font-size:16px;">Tipo:</span>
               </div>
               <div class="col-md-3">
                   <select name="tipo">
                                    <option value="P">Presente</option>
                                    <option value="PE">Permessi</option>
                                    <option value="F">Ferie</option>
                                    <option value="FE">Festività</option>
                                    <option value="M">Maternità</option>
                        </select>
               </div>
        </div>
         <div class="row" style="margin-bottom:1.5%;margin-top:1.5%;">
             <div class="col-md-3" style="margin-top:1%;"></div>
               <div class="col-md-3" style="margin-top:1%;">
                   <input type="submit" name="aggiungi" value="Aggiungi" class="btn btn-success btn-sm" >
               </div>
           <div class="col-md-3" style="margin-top:1%;">
               <input type="submit" name="annulla" value="Annulla" class="btn btn-danger btn-sm" onClick="window.location.reload()">
           </div>
         </div>
              </div>
                  </div>
                  </div>
          </div>
          </div></form>
   <?php  
} else if(isset($_GET['id'])&&$_GET['id']=="attivita"){
    include('attivita.php');
} else if(isset($_GET['id'])&&$_GET['id']=="utenti"){
    include('riepilogo_commesse.php');
}
else {
?>
    <div style="margin-top:100px; margin-left: -35px;">
    <?php
    echo "<p align='center' style='color:red'>Per accedere alla pagina devi effettuare il <a href='/form/login.htm.php'>login</a></p>";
?>
    </div>
    <?php
}

?>

</body>
</html>

<script>
function mostranascondi(div, switchImgTag) {
        var ele = document.getElementById(div);
        var imageEle = document.getElementById(switchImgTag);
        if(ele.style.display == "inline") {
                ele.style.display = "none";
		imageEle.innerHTML = '<img src="../img/apri.png">';
        }
        else {
                ele.style.display = "inline";
                imageEle.innerHTML = '<img src="../img/apri.png">';
        }
}
</script>