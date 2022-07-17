<?php if($_SESSION['gruppi'] != ""||$_SESSION['is_admin']==1) {?> 
<!-- Inizio Tasto Scrivi Messaggio -->
<div class="section">
          <div class="container">
              <div style="margin-left:5%;margin-bottom:-5%;">
                  <a onclick="javascript:comparsa_messaggio('messaggio')" id="write">
                  <img style="cursor: pointer;" height="28" src="img/write.png"> </a>
              </div>
          </div>
</div>
<!-- Fine Tasto Scrivi Messaggio -->
 <!-- Nuovo Messaggio -->
        <form action="" method="post" enctype="multipart/form-data">
             <!-- Nuovo Messaggio -->
	  <div style="max-width:90%;margin-left:5%;">
        
              <div id="messaggio" class="border" style="margin-left:10%;max-width:80%;<?php if(!isset($_GET['id'])){?>display: none;<?php } ?>">
            <div class="container" style="max-width:90%; margin-left:9%;">
                  <div class="row" style="margin-bottom:1.5%;">
                <div class="col-md-2">
                  
               </div>
                <div class="col-md-2">
                  <span style="font-weight:600;">Titolo:</span>
               </div>
                <div class="col-md-3"><input type="text" name="titolo_messaggio" value="<?php if(isset($_POST['titolo_messaggio'])) echo $_POST['titolo_messaggio']?>">
                </div>
            </div>
            <div class="row" style="margin-bottom:1.5%;">
                <div class="col-md-2">
                  
               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Allegati:</span>
               </div>
                 <div class="col-md-2">
                    <input type="file"  name="fileToUpload" id="fileToUpload" style="width: 150px;">
                    <input type="hidden" name="id_user" value="<?php echo $cod_anagr;?>">
</div>
                 <div class="col-md-1">
                    <input type="submit" title="Allega File" value="Allega File" name="submit" class="myButton" style="height: 25px;border:1px solid #000;font-size:12px;line-height:10px;">
                 </div>
                <div class="col-md-2">
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
echo $file . "<input style='width: 20px;' type='image' name='delete' value='delete' src='img/x.png'><input type='hidden' name='nome_file' value='".$file."'><br/>";
}
//Chiudo la lettura della directory.
closedir($directory_handle);
}
} ?></div> 
            </div>
             <div class="row" style="margin-bottom:1.5%;">
                 <div class="col-md-2">
                  
               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Tipo:</span>
               </div>
               <div class="col-md-3"><select name="tipo">
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="HR"){?> selected="selected" <?php } ?> value="HR">HR</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="Tecnico"){?> selected="selected" <?php } ?>value="Tecnico">Tecnico</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="Amministrazione"){?> selected="selected" <?php } ?>value="Amministrazione">Amministrazione</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="SW&IT Solutions"){?> selected="selected" <?php } ?>value="SW&IT Solutions">SW&IT Solutions</option>
                                <option <?php if(isset($_POST['tipo'])&&$_POST['tipo']=="Commerciali"){?> selected="selected" <?php } ?>value="Commerciali">Commerciali</option>
                    </select>
               </div>
             </div>
                    <div class="row" style="margin-bottom:1.5%;">
                        <div class="col-md-2">
                  
               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Avviso:</span>
               </div>
               <div class="col-md-3">
                    <input type="checkbox" id="email" name="email" value="si" > Invia Email
               </div>
                    </div>
            <div class="row" style="margin-bottom:1.5%;">
                   <div class="col-md-2">
                  
               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Visibile a:</span>
               </div>
               <div class="col-md-6">
                    <input type="checkbox" id="check_all" name="checkbox[]" value="2" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"2")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Ufficio 
                    &nbsp;
                    <input type="checkbox" id="check_all" name="checkbox[]" value="3" <?php if($_SESSION['is_admin']!=1&&strstr($_SESSION['gruppi'],"3")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Esterni 
                    &nbsp;
                    <input type="checkbox" id="check_all" name="checkbox[]" value="4" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"4")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Tecnici 
                    &nbsp;
                    <input type="checkbox" id="check_all" name="checkbox[]" value="5" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"5")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>SW Team 
                    &nbsp;
                    <input type="checkbox" id="check_all" name="checkbox[]" value="6" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"6")&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Responsabili 
                    &nbsp;
                    <input type="checkbox" name="checkbox[]" id="checkbox_check" value="1" <?php if($_SESSION['is_admin']!=1&&!strstr($_SESSION['gruppi'],"1")){?>disabled="disabled"<?php } ?>>Tutti
               </div>
            </div>
            <div class="row" style="margin-bottom:1.5%;">
                 <div class="col-md-2">
                  
               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Data Fine:</span>
               </div>
                <div class="col-md-3">
                    <input type="text" name="data_fine" id="datepicker" value="<?php if(isset($_POST['data_fine'])) echo $_POST['data_fine']?>"/>
                </div>
            </div>
             <div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-2">
                  
               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Testo:</span>
               </div>
               <div class="col-md-2">
                   <textarea style="width: 100%; height: 150px;" name="testo_messaggio"><?php if(isset($_POST['testo_messaggio'])) echo $_POST['testo_messaggio']?></textarea>
               </div>
             </div>
            <div class="row" style="margin-bottom:1.5%;">
               
                 <div class="col-md-8">
                  
               </div>
               <div class="col-md-4">
                   <input type="submit" name="messaggio" value="Invia Messaggio">
               </div>
            </div>
            
        </form>
    </div>
              </div>
          </div>
<?php } ?>
 <!-- Fine Nuovo Messaggio -->
<!-- Tabella Messaggi -->
                  <div>
                  <div class="section">
      <div class="container">
                   <div style="background-color: #333; max-width:100%; padding-top:1%; padding-bottom:1%;">
                     <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:4.5%; text-align: left;"> Ultimi 10 Messaggi </span>
                  </div>
                  <center>
        <div class="row">
          <div class="col-md-12">
            <table class="table">
              <thead>
                <tr style="background-color:#999;color:#fff;">
                  <th></th>
                  <th>Titolo</th>
                  <th>Tipo</th>
                  <th>Testo</th>
                  <th style="width:20%;">Ultima modifica</th>
                  <th>Modifica</th>
                  <th>Elimina</th>
                </tr>
              </thead>
              <tbody> 
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
 
                  <tr>
                      <td></td>
                      <td>
        <?php echo '<a href="bacheca.php?idmex='.$row['id_messaggio'].'">'.$row['titolo']."</a>";?>
                      </td>
                      <td>
                <?php echo $row['tipo'];?>
            </td>
            <td>
                <?php echo substr($row['testo'],0,75)."...";?>
            </td>
                <td>
            <?php echo $row['ultima_modifica'];?>
                </td>
            <?php if($control=="OK"){ ?>
            <td>
                <a class="btn btn-success btn-sm" href="bacheca.php?idmex=<?php echo $row['id_messaggio']?>&id=mod">Modifica</a>
            </td>
            <td><form action="" method="post"><input type="submit" class="btn btn-danger btn-sm" name='delete_mex' value='Elimina'><input type='hidden' name='id_mex_delete' value="<?php echo $row['id_messaggio'];?>"></form>
            </td>
            <?php } else {?>
                  </tr>
         <?php } ?>
                  </tr>
            <?php
         }
    ?>
              </tbody>
            </table>
        </div>
             </div>
                </div>
                <!-- Tabella Messaggi -->
      <!-- Tabella Files -->
                  <div>
                  <div class="section">
      <div class="container">
                   <div style="background-color: #333; max-width:100%; padding-top:1%; padding-bottom:1%;">
                     <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:4.5%; text-align: left;"> Ultimi 10 Files </span>
                  </div>
             <center>
        <div class="row">
          <div class="col-md-12">
            <table class="table">
              <thead>
                <tr style="background-color:#999;color:#fff;">
                                                               <th></th>
                  <th>Nome File</th>
                  <th>Dimensione</th>
                  <th>Estensione</th>
                  <th>Download</th>
                </tr>
              </thead>
              <tbody>

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
 <?php
         while ($row = mysql_fetch_array($sql))
         {
             ?>
                   <tr>
                                                              <td></td>
        <td><?php echo $row['nome'];?></td>
            <td><?php echo $row['dimensione'];?></td>
            <td><?php echo $row['estensione'];?></td>
            <td><a target="_blank" href="./files/<?php echo $row['id_messaggio'].'/'.$row['nome'].'.'.$row['estensione']?>"><img src="img/<?php echo $row['estensione'];?>.png" width="50" alt="download"></a></td>
        </tr>
            <?php
         }
    ?>
    </tbody>
            </table>
          </div>
        </div>
                               </center>
      </div>
    </div>
                </div>
                <!-- Tabella Files -->