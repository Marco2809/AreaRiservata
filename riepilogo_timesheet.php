<?php
if(isset($_POST['mese'])&&isset($_POST['anno'])){
    $anno=$_POST['anno'];
    $mese=$_POST['mese'];
} else {
    $anno=date('Y');
    $mese=date('m');
} 
if(!checkdate($mese,28+1,$anno)) { $num_giorni = 28;}
			else if(!checkdate($mese,29+1,$anno)) { $num_giorni = 29;}
			else if(!checkdate($mese,30+1,$anno)) { $num_giorni = 30;}
			else if(!checkdate($mese,31+1,$anno)) { $num_giorni = 31;}

?>
<div class="ficheaddleft">
    <center>

<form action="" method="post">
  <table width="240" cellspacing="5" style="margin-top:50px;">
  <tbody>
    <tr>
      <th scope="col">
<select class="shadows" name="mese" class="margin-top:8px;">
                                                <?php for($month=1;$month<=12;$month++)
 {
         if($month==1){ $mese_cal="Gennaio"; $me="01";}
	if($month==2){ $mese_cal="Febbraio"; $me="02";}
	if($month==3) {$mese_cal="Marzo"; $me="03";}
	if($month==4) {$mese_cal="Aprile"; $me="04";}
	if($month==5) {$mese_cal="Maggio"; $me="05";}
	if($month==6) {$mese_cal="Giugno"; $me="06";}
	if($month==7) {$mese_cal="Luglio"; $me="07";}
	if($month==8) {$mese_cal="Agosto"; $me="08";}
	if($month==9) {$mese_cal="Settembre"; $me="09";}
	if($month==10) {$mese_cal="Ottobre"; $me="10";}
	if($month==11) {$mese_cal="Novembre"; $me="11";}
	if($month==12) {$mese_cal="Dicembre"; $me="12";}
	 ?>
                            <option <?php if($mese==$me) { ?>selected="selected"<?php } ?> value="<?php echo $me; ?>"><?php echo $mese_cal; ?></option>
                            <?php 
 }
 
 ?>
                            </select>
                             </th>
      
      <th scope="col">
<select class="shadows" name="anno" class="margin-top:8px;">
                                                <?php for($year=date("Y");$year>=2010;$year--)
 {
	 ?>
                            <option <?php if($anno==$year) { ?>selected="selected"<?php } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                            <?php 
 }
 
 ?>
                            </select>
  </th>
      
      
      <th scope="col">
                            <input type="image" name="action" src="img/vai2.png" value="Vai" style="margin-top:3px; width: 100px;">
                               
                            </th>
                             <th scope="col">
                                 <a href="excel_timesheet.php?mese=<?php echo $mese?>&anno=<?php echo $anno?>" target="_blank">Download Excel</a>
                               
                            </th>
    </tr>
  </tbody>
</table>
 </form>
        </center>
    <?php $query_timesheet="SELECT anagrafica.nome,anagrafica.cognome,count(attivita.id_attivita) AS num_giorni, attivita.id_utente, SUM(attivita.ore_lavorate) AS ore_tot,attivita.stato FROM attivita,anagrafica WHERE attivita.id_utente = anagrafica.user_id AND attivita.mese = '".$mese."' AND attivita.anno = '".$anno."' GROUP BY attivita.id_utente,attivita.stato HAVING attivita.stato <> 'Rifiutato' AND attivita.stato <> 'Non Validato'";
            $result_timesheet= mysql_query($query_timesheet,$conn->db);
            echo mysql_error();
            ?>
    <table align="center" class="noborder" width="80%" height="50px"padding="px" style="margin-top: 20px;">
    <tr style="font-size:16px; font-family:play; font-weight:bold;text-align: center;  height: 20px; line-height: 50px; background-color: #999999; color: #fff;">
                    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Nome e Cognome</td>
                    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Numero Giorni</td>
                    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Ore Totali</td>
                    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Stato</td>
    <?php
            while($row=  mysql_fetch_array($result_timesheet)){
                
                if($row['num_giorni']==$num_giorni){ $stato="Validato";} else $stato="Non Validato";
                echo 
                '<tr >'
                . '<td style="font-size:16px; font-family:play; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$row['nome'].' '.$row['cognome'].'</td>'
                . '<td style="font-size:16px; font-family:play; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$row['num_giorni'].'</td>'
                . '<td style="font-size:16px; font-family:play; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$row['ore_tot'].'</td>'
                . '<td style="font-size:16px; font-family:play; font-weight:bold;text-align: center; height: 50px; line-height: 50px;">'.$stato.'</td>'
                       . '</tr>';
            }
            ?>
    </table>
</div>

