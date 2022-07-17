
<?php 
$num=0;
                    if(isset($_GET['id']) && $_GET['id']=="time"){
                        ?>
                      
                <form method="post" action="">
                  <table align="center" width="240" cellspacing="5" style="margin-top:0px; margin-bottom:5px;">
  <tbody>
    <tr>
      <th scope="col">
      <select class="shadows" name="mese" style="margin-top:8px;">
                     <?php
                     for($i=1; $i<=12; $i++)
                     {
                         ?>
                     
   <option value="<?php echo $i;?>" <?php if(isset($_POST['mese'])&&$_POST['mese']==$i) {?> selected="selected"<?php } ?>><?php echo $i ?></option>
      <?php 
                     }
                     ?>
  </select></th>
      
      <th scope="col">
      <select class="shadows" name="anno" style="margin-top:8px;">
                     <?php
                     for($i=date("Y"); $i>=2010; $i--)
                     {
                         ?>
                     
   <option value="<?php echo $i;?>" <?php if(isset($_POST['anno'])&&$_POST['anno']==$i) {?> selected="selected"<?php } ?>><?php echo $i ?></option>
      <?php 
                     }
                     ?>
  </select>
</th>
     
      <th scope="col"><input type="image" title="Cerca" value="Cerca" src="img/cerca2.png" name="Cerca" style="margin-top:15px;"></th>

    <th scope="col"><input type="image" title="Visualizza tutti" value="Visualizza Tutti" src="img/visualizza.png" name="visualizza" style="margin-top:15px;"></th>
    
    <th scope="col">
      <select class="shadows" name="flag" id="flag" style="margin-top:8px; width:80px; height:20px">
  <option></option>
        <option value="3">Verdi</option>
        <option value="2">Gialli</option>
    </select>
      </th>
    <th scope="col"><input type="image" title="Non Compilati" value="Non Compilati" src="img/noncompilati.png" name="visualizza" style="margin-top:15px;">
                    <input type="hidden" name="m" value="<?php if(isset($_POST['mese'])){ echo $_POST['mese']; } else echo date('m') ?>">
                    <input type="hidden" name="a" value="<?php if(isset($_POST['anno'])){ echo $_POST['anno']; } else echo date('Y') ?>">
  </th>
  </tbody>
</table>
                </form>
                <br>
                <div id="contenitore1" class="fluid">
            <?php
                    if((!isset($_POST['mese'])&&!isset($_POST['anno'])&&!isset($_POST['visualizza']))||(isset($_POST['visualizza'])&&$_POST['visualizza']=="Visualizza Tutti"))
                        
        { 
                          
                          $month=date('m');
                          $year=date('Y');
                          if($month != 10 || $month != 11 || $month != 12)
                          {
                             $month = substr($month,1,2);   
                  
                          }
                       
                        $query_time = "select timesheet.flag,timesheet.id_user,timesheet.id_mese_time,anagrafica.referente,anagrafica.user_id, timesheet.id_anno_time, timesheet.id_time,timesheet.tipo_giorni, timesheet.ore_perm from timesheet, anagrafica WHERE timesheet.id_user = anagrafica.user_id AND anagrafica.referente =".$cod_anagr." AND timesheet.convalidato != 1 AND ((timesheet.id_mese_time <= ".$month."  AND timesheet.id_anno_time <=".$year." ) OR (timesheet.id_mese_time = ".$month."  AND timesheet.id_anno_time !=".$year.")OR (timesheet.id_mese_time != ".$month."  AND timesheet.id_anno_time !=".$year.")) ORDER BY timesheet.id_anno_time, timesheet.id_mese_time";  
                                
                        $result_time = mysql_query($query_time, $conn->db);
                  
        } else if(isset($_POST['mese'])&&$_POST['mese']!=""&&$_POST['anno']!=""&&isset($_POST['anno'])&&!isset($_POST['visualizza']))
        {
               
                        $query_time = "select flag, id_user,id_mese_time, id_anno_time, id_time,tipo_giorni, ore_perm from timesheet WHERE convalidato != 1 AND id_mese_time = ". $_POST['mese']." AND id_anno_time = ".$_POST['anno']." ORDER BY id_anno_time, id_mese_time";
                        $result_time = mysql_query($query_time, $conn->db);
            
            
        } 
        
        else if($_POST['visualizza']=="Non Compilati")
                        
        {
                         if(isset($_POST['m']))
                         {
                             $month=$_POST['m'];
                         }
                         else {
                             $month = date('m');
                             if($month != 10 || $month != 11 || $month != 12)
                          {
                             $month = substr($month,1,2);   
                  
                          }
                         }
                          if (isset($_POST['a'])){
                              
                              $year=$_POST['a'];
                          }
                          else {
                              $year=date('Y');
                          }
                          
                          
                          
                          
                         
                        $query_time = "select anagrafica.user_id
                        from anagrafica
                        WHERE  anagrafica.referente =".$cod_anagr." AND anagrafica.user_id NOT IN (SELECT timesheet.id_user FROM timesheet WHERE timesheet.id_user = anagrafica.user_id AND timesheet.id_mese_time =".$month." AND timesheet.id_anno_time = ".$year.")";
                                
                        $result_time = mysql_query($query_time, $conn->db);
                  
        }
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {?>
   
                          <?php 
                          while ($row = mysql_fetch_array($result_time)) {
                        
       
        
       if(isset($_POST['visualizza'])&&$_POST['visualizza']=="Non Compilati") {
           

        if(isset($_POST['m'])) $id_mese_time = $_POST['m'];
        else $id_mese_time = date('m');
        if(isset($_POST['a'])) $id_anno_time = $_POST['a'];
        else $id_anno_time = date('Y');
        $id_time = "";
        $ore_perm = "";
        $tipo_giorni = "";
        $flag= 1;
           
        $id_user=$row["user_id"];
        
       }
       
       else
       {
           
           $id_user = $row['id_user'];
        $id_mese_time = $row['id_mese_time'];
        $id_anno_time = $row['id_anno_time'];
        $id_time = $row['id_time'];
        $ore_perm = $row['ore_perm'];
        $tipo_giorni = $row['tipo_giorni'];
        $flag= $row['flag'];
           
       }
        
        if($flag==1) $imgflag="rosso.png";
        if($flag==2) $imgflag="giallo.png";
        if($flag==3) $imgflag="verde.png";
        
        $num++;
      
            $sql3_anagrafica = "SELECT 
              nome, 
              cognome, 
              codice_fiscale 
                                                        FROM anagrafica WHERE user_id=" . $id_user;


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);

    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        ?><form method="post" action="" id="myform">
                           
                          <?php
       while( $row = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $codice_fiscale = $row['codice_fiscale'];
   
            ?>

  <table id="flag<?php echo $flag;?>" class="flag<?php echo $flag;?>" width="200" border="0" cellspacing="5" style="margin-top:0px; margin-left:-10px;">
  <tbody>
    <tr><th scope="col" style="font-size:20px;padding-bottom:10px;"><?php echo $id_mese_time ."/".$id_anno_time." <br> " ?><?php echo $nome . " " . $cognome; ?> </th>
    </tr>
    <tr>
      <td><img src="img/<?php echo $imgflag;?>"></td>
    </tr>
    <tr>
      <td><input type="image" title="Convalida" id="convalida" name="validate" value="<?php echo $id_time?>" src="img/check.png" style="margin-top:10px;">
        <button type="button" id="text<?php echo $num;?>" class="mod" title="Modifica" value="Modifica" name="button_search" style="margin-top:-60px;padding: 0;
border: none;
background: none;"><img src="img/modifica2.png" style="margin-top:-40px;"></button><button class="mod" type="button" title="Non Convalida" value="Non Convalida"  name="button_search" style="margin-top:-60px;padding: 0;
border: none;
background: none;"><a href="javascript:;" onclick="window.open('motivo.php?id=<?php echo $id_time;?>', 'titolo', 'width=400, height=200, resizable, status, scrollbars=1, location');"><img src="img/annulla.png" style="margin-top:-45px;"></a>
</button></td>
    </tr>
    <tr>
      <td><button type="button" title="Excel" value="Excel" style="
border:none;
background: none;"  onclick="window.location = 'export.php?id=<?php echo $id_time;?>'"><img src="img/downloadxls.png"></button></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
                     <?php
                         }
    }
                    ?>
                        
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
                <?php
        for($i=1; $i<=($num_giorni); $i++){
          
            ?>

                <th width="90" scope="col"<?php if(date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Sunday" || date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Saturday"){?> style="font-size:24px; color:#DF0F0F;"<?php } else { ?> style="font-size:24px; color:#000;"<?php } ?>>
                    <center><b><?php echo $i;?></b></center>
            </th>
            <?php } ?>
                </tr>
                      <tr>
<?php
        for($i=1; $i<=($num_giorni); $i++){
          $ore_lav=8;
          if(date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Sunday" || date("l", mktime(0, 0, 0, $id_mese_time, $i, $id_anno_time))=="Saturday") $ore_lav = "";
            if($tipo_giorni[$i-1]=="F"||$tipo_giorni[$i-1]=="FE") $ore_lav="";
             if(substr($tipo_giorni[$i-1],-1,1)>0) $ore_lav=8-substr($tipo_giorni[$i-1],-1,1);

            ?>
           
      <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px">Ore</strong><br><input name="ore<?php echo $i?>" class="text<?php echo $num;?>" readonly="true" type="text" style="width:40px; height:25px; box-shadow: 5px 5px 5px #666666;
   -webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px; 
font-weight:200; 
font-family:play; 
font-size:12px; 
text-align:center; 
color:#666;" value="<?php echo $ore_lav; ?>"/>
      </td>
    <?php } ?>
    </tr>  
       <tr>  
<?php
        for($i=1; $i<=($num_giorni); $i++){
          $tipo_giorni[$i-1]
            ?>
 
      
      <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px;">Tipologia</strong><br>
        <center><select style="width:50px; height:25px; box-shadow: 5px 5px 5px #666666;
   -webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px; 
font-weight:200; 
font-family:play; 
font-size:12px; 
text-align:center; 
color:#666;" disabled="true" name="tipo<?php echo $i?>" class="text<?php echo $num;?>">
  <option <?php if($tipo_giorni[$i-1]=="P") { ?>selected="selected" value="P"<?php } else {?>value="ND"<?php } ?> ></option>
      <option>P</option>
      <option <?php if($tipo_giorni[$i-1]=="M") { ?>selected="selected" <?php } ?>>M</option>
      <option <?php if($tipo_giorni[$i-1]=="M1") { ?>selected="selected" <?php } ?>>M1</option>
      <option <?php if($tipo_giorni[$i-1]=="F") { ?>selected="selected" <?php } ?>>F</option>
       <option <?php if(substr($tipo_giorni[$i-1],0,2)=="PR") { ?>selected="selected" <?php } ?>>PR</option>
      <option <?php if($tipo_giorni[$i-1]=="FE") { ?>selected="selected" <?php } ?>>FE</option>
      <option <?php if($tipo_giorni[$i-1]=="EX") { ?>selected="selected" <?php } ?>>EX</option>
    </select></center></td>
    <?php } ?>
   </tr>
    <tr>
      <?php
        for($i=1; $i<=($num_giorni); $i++){
            ?>
    <td style="padding-top:10px;padding-bottom:10px;"><strong style="font-size:15px">Permessi</strong><br><input name="perm<?php echo $i?>" class="text<?php echo $num;?>" readonly="true" type="text" style="width:40px; height:25px; box-shadow: 5px 5px 5px #666666;
   -webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px; 
font-weight:200; 
font-family:play; 
font-size:12px; 
text-align:center; 
color:#666; " value="<?php if(substr($tipo_giorni[$i-1],-1,1)>0) { echo substr($tipo_giorni[$i-1],-1,1);} else echo "";  ?>"/></td>

        <?php
                    }
                    ?>
                          </tr>
  </tbody>
</table>
</form>
<br>
              
                <?php
       
                    }
                ?>
               
        <?php
    }
                    }
                    ?>
                      </div>