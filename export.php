<?php
    session_start();
include('dbconn.php');

$conn = new dbconnect();
$r = $conn->connect();

    $query_time = "select timesheet.id_user,timesheet.id_mese_time, timesheet.id_anno_time, timesheet.tipo_giorni, anagrafica.nome, anagrafica.cognome from timesheet, anagrafica WHERE timesheet.id_user = anagrafica.user_id AND id_time = ". $_GET['id'];
                        $result_time = mysql_query($query_time, $conn->db);

                          while ($row = mysql_fetch_array($result_time)) {
                              
                              $id_user = $row['id_user'];
        $id_mese_time = $row['id_mese_time'];
        $id_anno_time = $row['id_anno_time'];
        $tipo_giorni = $row['tipo_giorni'];
        $nome=$row['nome'];
        $cognome=$row['cognome'];
                          }
                        
                        
   $filename=$cognome.$nome.$id_mese_time.$id_anno_time.".xls";
   header ("Content-Type: application/vnd.ms-excel");
   header ("Content-Disposition: inline; filename=$filename");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang=it><head>
<title>Timesheet Excel</title></head>
<body>
 <table  width="200" border="0" cellspacing="5" style="margin-top:0px; margin-left:-10px;">
  <tbody>
    <tr>
      <th scope="col" colaspan="5" style="font-size:20px;padding-bottom:10px;"><?php echo $nome . " " . $cognome. " - " . $id_mese_time ."/".$id_anno_time; ?> </th>
    </tr>
  </tbody>
</table>
    <?php
 $tipo_giorni = @explode('-', $tipo_giorni);

if(count($tipo_giorni)==1) {
    
    for($r=0;$r<=31;$r++)
    {
        $tipo_giorni[$r] = "";
    }
    
}
if(!checkdate($id_mese_time,28+1,$id_anno_time)) { $num_giorni = 28;}
      else if(!checkdate($id_mese_time,29+1,$id_anno_time)) { $num_giorni = 29;}
      else if(!checkdate($id_mese_time,30+1,$id_anno_time)) { $num_giorni = 30;}
      else if(!checkdate($id_mese_time,31+1,$id_anno_time)) { $num_giorni = 31;}
   ?>
<table width="100%" height="auto" border="1" cellspacing="5px" id="flag<?php echo $flag."i";?>" class="flag<?php echo $flag."i";?>" style="margin-top:-230px; margin-left:160px; margin-bottom:50px;">
  <tbody>
      <tr>
         <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px">Giorni
      </td>
                <?php
        for($i=1; $i<=($num_giorni); $i++){
          
            ?>

                <th width="90" scope="col"<?php if(date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Sunday" || date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Saturday"){?> style="font-size:24px; color:#DF0F0F;"<?php } else { ?> style="font-size:24px; color:#000;"<?php } ?>>
                    <center><b><?php echo $i;?></b></center>
            </th>
            <?php } ?>
                </tr>
                      <tr>
                        <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px">Ore
      </td>
<?php
        for($i=1; $i<=($num_giorni); $i++){
          $ore_lav=8;
          if(date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Sunday" || date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Saturday") $ore_lav = "";
            if($tipo_giorni[$i-1]=="F"||$tipo_giorni[$i-1]=="FE") $ore_lav="";

             if(substr($tipo_giorni[$i-1],-1,1)>0) $ore_lav=8-substr($tipo_giorni[$i-1],-1,1);

            ?>
           
      <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px"><center><?php echo $ore_lav; ?></center>
      </td>
    <?php } ?>
    </tr>  
       <tr>  
        <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px">Tipologia
      </td>
<?php
        for($i=1; $i<=($num_giorni); $i++){
       
            ?>
 
      
      <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px;">
        <center><?php if($tipo_giorni[$i-1]=="ND"||$tipo_giorni[$i-1]=="P") echo "&nbsp;"; else if(substr($tipo_giorni[$i-1],0,2)=="PR") {echo substr($tipo_giorni[$i-1],0,2);} else echo $tipo_giorni[$i-1]; ?></center></td>
    <?php } ?>
   </tr>
    <tr>
       <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px">Permessi
      </td>
      <?php
        for($i=1; $i<=($num_giorni); $i++){
            ?>
    <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px"><center><?php if(substr($tipo_giorni[$i-1],-1,1)>0) { echo substr($tipo_giorni[$i-1],-1,1);} else echo "";?></center></td>

        <?php
                    }
                    ?>
                          </tr>
  </tbody>
</table>
</body>
</html>

