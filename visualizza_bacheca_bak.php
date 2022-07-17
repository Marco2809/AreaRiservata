<?php
if($_SESSION['gruppi'] != ""||$_SESSION['is_admin']==1) {?> 
<table align="center" width="80%" height="50px"padding="px">
    <tr><td style="font-size:16px; width:10%; font-family:play; font-weight:bold;text-align: left;"><a style="margin-left:0px;" onclick="javascript:comparsa_messaggio('messaggio')" id="write"><img style="cursor: pointer;" height="28" src="img/write.png"></a>
        </td></tr>
</table>

    <div id="messaggio" style="<?php if(!isset($_GET['id'])){?>display: none;<?php } ?>">
        <form action="" method="post" enctype="multipart/form-data">
        <table align="center" class="noborder" width="80%" height="50px"padding="px">
            <tr><td style="font-size:16px; width:10%; font-family:play; font-weight:bold;text-align: left;">Titolo:</td>
                <td style="text-align: left;"><input type="text" name="titolo_messaggio" value="<?php if(isset($_POST['titolo_messaggio'])) echo $_POST['titolo_messaggio']?>"></td></tr>
            <tr><td style="font-size:16px; width:10%; font-family:play; font-weight:bold;text-align: left;">Allegati:</td>
              
                <td style="text-align: left;">
                    <?php //Imposto la directory da leggere
$directory = "/Applications/XAMPP/xamppfiles/htdocs/area_riservata/files/temp/".$_SESSION['user_idd']."/";
// Apriamo una directory e leggiamone il contenuto.
if (is_dir($directory)) {
//Apro l'oggetto directory
if ($directory_handle = opendir($directory)) {
//Scorro l'oggetto fino a quando non è termnato cioè false
while (($file = readdir($directory_handle)) !== false) {
//Se l'elemento trovato è diverso da una directory
//o dagli elementi . e .. lo visualizzo a schermo
if((!is_dir($file))&($file!=".")&($file!=".."))
    if ($file!=".DS_Store")
echo $file . "<input style='width: 50px; margin-left: -10px;' type='image' name='delete' value='delete' src='img/x.png'><input type='hidden' name='nome_file' value='".$file."'><br/>";
}
//Chiudo la lettura della directory.
closedir($directory_handle);
}
} ?>
                    <input type="file"  name="fileToUpload" id="fileToUpload" style="width: 150px;">
                    <input type="hidden" name="id_user" value="<?php echo $cod_anagr;?>">
                    <input type="submit" title="Allega File" value="Allega File" name="submit" class="myButton" style="height: 25px;border:1px solid #000;font-size:12px;line-height:10px;"></td></tr>
            <tr><td style="font-size:16px; width:10%; font-family:play; font-weight:bold;text-align: left;">Tipo:</td>
                        <td style="text-align: left;"><select name="tipo">
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="HR"){?> selected="selected" <?php } ?> value="HR">HR</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="Tecnico"){?> selected="selected" <?php } ?>value="Tecnico">Tecnico</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="Amministrazione"){?> selected="selected" <?php } ?>value="Amministrazione">Amministrazione</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="SW&IT Solutions"){?> selected="selected" <?php } ?>value="SW&IT Solutions">SW&IT Solutions</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="Commerciali"){?> selected="selected" <?php } ?>value="Commerciali">Commerciali</option>
                    </select></td></tr>
                     <tr><td style="font-size:16px; width:10%; font-family:play; font-weight:bold;text-align: left;">Avviso:</td>
                <td style="text-align: left;">
                    <input type="checkbox" id="email" name="email" value="si" > Invia Email
                </td>
            </tr>
            <tr><td style="font-size:16px; width:10%; font-family:play; font-weight:bold;text-align: left;">Visibile a:</td>
                <td style="text-align: left;">
                    <input type="checkbox" id="check_all" name="checkbox[]" value="2" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"2")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Ufficio 
                    <input type="checkbox" id="check_all" name="checkbox[]" value="3" <?php if($_SESSION['is_admin']!=1&&strstr($_SESSION['gruppi'],"3")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Esterni 
                    <input type="checkbox" id="check_all" name="checkbox[]" value="4" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"4")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Tenici 
                    <input type="checkbox" id="check_all" name="checkbox[]" value="5" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"5")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>SW Team 
                    <input type="checkbox" id="check_all" name="checkbox[]" value="6" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"6")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Responsabili 
                    <input type="checkbox" name="checkbox[]" id="checkbox_check" value="1" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Tutti </td></tr>
            <tr><td style="font-size:16px; width:10%; font-family:play; font-weight:bold;text-align: left;">Data Fine:</td>
                <td style="text-align: left;"><input type="text" name="data_fine" id="datepicker" value="<?php if(isset($_POST['data_fine'])) echo $_POST['data_fine']?>"/></td></tr>
            <tr><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">Testo:</td>
                <td style="text-align: left;"><textarea style="width: 600px; height: 150px;" name="testo_messaggio"><?php if(isset($_POST['testo_messaggio'])) echo $_POST['testo_messaggio']?></textarea></td></tr>
            <tr><td colspan="2" style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input type="submit" name="messaggio" value="Invia Messaggio"></td>
               </tr>
        </table>
            
        </form>
    </div>
<?php } ?>
    <center>
    <table  class="noborder" width="80%" height="40px"padding="8px" style="margin-top: 10px;">
        <tbody><tr class="liste_titre">  
                <th class="liste_titre" colspan="2" style="width:80%"><strong style="font-size:18px; color:#fff; font-family:play; font-weight:bold; margin-left:10px;">Ultimi 10 Messaggi</strong>
               </th>
 <th class="liste_titre" colspan="2" style="width:50%; text-align: right;"><strong style="font-size:15px; font-family:play; margin-right:10px;"><a style="color:#6aa84f;" href="http://service-tech.org/servicetech/area_riservata/bacheca.php?mex=all">Visualizza Tutti</a></strong>
                 </th></table></center>  
    <?php 
        $array_gruppi= json_decode($user_group);
        
        $query="SELECT * FROM messaggi WHERE";
        for($i=0;$i<count($array_gruppi);$i++){
            if($array_gruppi[$i]->profilo!=0)
            {
                    $gruppo_da_cercare=$array_gruppi[$i]->gruppo;
                    $query.= " gruppi LIKE '%$gruppo_da_cercare%' OR ";
            }
        
        }
            if(substr($query, -3,3)=="OR ") $query= substr($query,0,-3);
       if(!isset($_GET['mex'])) $query.=" ORDER BY id_messaggio DESC LIMIT 0,10";
       else $query.=" ORDER BY id_messaggio DESC";
        $sql=  mysql_query($query);
        ?>
<table align="center" class="noborder" width="80%" height="50px"padding="px" style="margin-top: -20px;">
    <tr style="font-size:16px; font-family:play; font-weight:bold;text-align: left;  height: 20px; line-height: 50px; background-color: #999999; color: #fff;">
                    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">Titolo</td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">Tipo</td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">Testo</td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Ultima Modifica</td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Edit</td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Elimina</td></tr>
    <?php
         while ($row = mysql_fetch_array($sql))
         {
             $groups=explode(",",$row['gruppi']);
           $control="";
             for($i=0;$i<count($groups);$i++){
                 //echo $groups[$i]."-";
                 $n=$groups[$i];
                 //echo $array_gruppi[$n-1]->profilo. "<br>";
                 if($array_gruppi[$n-1]->profilo=="1"||$array_gruppi[$n-1]->profilo=="2"||$array_gruppi[$n-1]->profilo=="3"||$_SESSION['is_admin']==1) $control="OK";
                 
             }
             ?>

        <tr><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;  height: 50px; line-height: 50px;"><?php echo '<a href="bacheca.php?idmex='.$row['id_messaggio'].'">'.$row['titolo']."</a>";?></td>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left; line-height: 50px;"><?php echo $row['tipo'];?></td>
            <td  style="font-size:16px; font-family:play; font-weight:bold;text-align: left;line-height: 50px;"><?php echo substr($row['testo'],0,75)."...";?></td>
            <td  style="font-size:16px; font-family:play; font-weight:bold;text-align: center;line-height: 50px;"><?php echo $row['ultima_modifica'];?></td>
            <?php if($control=="OK"){ ?>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;line-height: 50px;"><a href="bacheca.php?idmex=<?php echo $row['id_messaggio']?>&id=mod"><img width="37px" alt="Modifica" src="img/mod_mex.png"></a></td>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;"><form action="" method="post"><input type='image' src="img/delete_mex.png" style="width:69px; margin-left: 0px; margin-top: 0px;" name='delete_mex' value='Elimina'><input type='hidden' name='id_mex_delete' value="<?php echo $row['id_messaggio'];?>"></form></td></tr>
            <?php } else {?>
        <td></td><td></td>
         <?php } ?>
        </tr>
            <?php
         }
    ?>
    </table>
    
     <center>
    <table  class="noborder" width="80%" height="40px"padding="8px" style="margin-top: 10px;">
        <tbody><tr class="liste_titre">  
                <th class="liste_titre" colspan="2" style="width:50%"><strong style="font-size:18px; color:#fff; font-family:play; font-weight:bold; margin-left:10px;">Ultimi 10 Files</strong>
                </th>
                <th class="liste_titre" colspan="2" style="width:50%; text-align: right;"><strong style="font-size:15px; font-family:play; margin-right:10px;"><a style="color:#6aa84f;" href="http://service-tech.org/servicetech/area_riservata/bacheca.php?files=all">Visualizza Tutti</a></strong>
                 </th>
    </table></center>  
    <?php 
    
    $array_gruppi= json_decode($user_group);
        $query="SELECT * FROM files WHERE";
        for($i=0;$i<count($array_gruppi);$i++){
            if($array_gruppi[$i]->profilo!=0)
            {
                    $gruppo_da_cercare=$array_gruppi[$i]->gruppo;
                    $query.= " gruppi LIKE '%$gruppo_da_cercare%' OR ";
            }
        
        }
            if(substr($query, -3,3)=="OR ") $query= substr($query,0,-3);
        if(!isset($_GET['files'])) $query.=" ORDER BY id_messaggio DESC LIMIT 0,10";
       else $query.=" ORDER BY id_messaggio DESC";

         $sql=  mysql_query($query);
        ?>
<table align="center" class="noborder" width="80%" height="50px" style="margin-top: -20px;">
         <tr style="text-align: left;  height: 20px; line-height: 50px; background-color: #999999; color: #fff;">
                    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">Nome File</td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">Dimensione</td> <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Estensione</td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;">Download</td></tr>
    <?php
         while ($row = mysql_fetch_array($sql))
         {
             ?>
        <tr><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left; height: 60px; line-height: 60px;"><?php echo $row['nome'];?></td>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;line-height: 60px;"><?php echo $row['dimensione'];?></td>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;line-height: 60px;"><?php echo $row['estensione'];?></td>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: center;"><a target="_blank" href="./files/<?php echo $row['id_messaggio'].'/'.$row['nome'].'.'.$row['estensione']?>"><img src="img/<?php echo $row['estensione'];?>.png" width="50" alt="download"></a></td>
        </tr>
            <?php
         }
    ?>
    </table>