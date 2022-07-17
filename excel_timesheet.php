<?php
session_start();
//ciao
include('dbconn.php');
//include('Mail.php'); 
//include('Mail/mime.php');
$conn = new dbconnect();
$r = $conn->connect();

if(!checkdate($_GET['mese'],28+1,$_GET['anno'])) { $num_giorni = 28;}
			else if(!checkdate($_GET['mese'],29+1,$_GET['anno'])) { $num_giorni = 29;}
			else if(!checkdate($_GET['mese'],30+1,$_GET['anno'])) { $num_giorni = 30;}
			else if(!checkdate($_GET['mese'],31+1,$_GET['anno'])) { $num_giorni = 31;}
   $filename="timesheet_".$_GET['mese']."-".$_GET['anno'].".xls";
   header ("Content-Type: application/vnd.ms-excel");
   header ("Content-Disposition: inline; filename=$filename");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang=it><head>
<title>Excel</title></head>
<body>
 <?php $query_timesheet="SELECT anagrafica.nome,anagrafica.cognome,count(attivita.id_attivita) AS num_giorni, attivita.id_utente, SUM(attivita.ore_lavorate) AS ore_tot,attivita.stato FROM attivita,anagrafica WHERE attivita.id_utente = anagrafica.user_id AND attivita.mese = '".$_GET['mese']."' AND attivita.anno = '".$_GET['anno']."' GROUP BY attivita.id_utente,attivita.stato HAVING attivita.stato <> 'Rifiutato' AND attivita.stato <> 'Non Validato'";
            $result_timesheet= mysql_query($query_timesheet,$conn->db);
            echo mysql_error();
            ?>
    <table align="center" class="noborder" width="80%" height="50px"padding="px" style="margin-top: 20px;">
    <tr style="font-size:16px; font-family:times; font-weight:bold;text-align: center;  height: 20px; line-height: 50px; background-color: #999999; color: #fff;">
                    <td style="font-size:16px; font-family:times; font-weight:bold;text-align: center;">Id Utente</td>
                    <td style="font-size:16px; font-family:times; font-weight:bold;text-align: center;">Numero Giorni</td>
                    <td style="font-size:16px; font-family:times; font-weight:bold;text-align: center;">Ore Totali</td>
                    <td style="font-size:16px; font-family:times; font-weight:bold;text-align: center;">Stato</td>
    <?php
            while($row=  mysql_fetch_array($result_timesheet)){
                
                if($row['num_giorni']==$num_giorni){ $stato="Validato";} else $stato="Non Validato";
                echo 
                '<tr >'
                . '<td style="font-size:16px; font-family:times; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$row['nome'].' '.$row['cognome'].'</td>'
                . '<td style="font-size:16px; font-family:times; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$row['num_giorni'].'</td>'
                . '<td style="font-size:16px; font-family:times; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$row['ore_tot'].'</td>'
                . '<td style="font-size:16px; font-family:times; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$stato.'</td>'
                       . '</tr>';
            }
            ?>
    </table>
</body></html>
