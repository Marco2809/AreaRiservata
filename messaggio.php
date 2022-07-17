<?php
        $array_gruppi= explode(",",$user_group);

        $query="SELECT * FROM messaggi WHERE id_messaggio =".$_GET['idmex'];
        $sql=  mysql_query($query);
        ?>

    <?php
         while ($row = mysql_fetch_array($sql))
         {

             ?>
<a href="bacheca.php" style="margin-left: 110px;"><img style="cursor: pointer; margin-top: 20px;" height="25px" src="img/back.png"></a>
    <form action="" method="post" enctype="multipart/form-data">
        <div style="max-width:90%;margin-left:5%;">

              <div id="messaggio" class="border" style="margin-left:10%;max-width:80%;<?php if(!isset($_GET['id'])){?>display: none;<?php } ?>">
            <div class="container" style="max-width:90%; margin-left:9%;">
                <div class="row" style="margin-bottom:1.5%;">
                <div class="col-md-2">

               </div>
            <div class="col-md-2">
                  <span style="font-weight:600;">Titolo:</span>
               </div>
                <div class="col-md-3"><input type="text" name="titolo_messaggio" <?php if(!isset($_GET['id'])){ ?> disabled="disabled" style="background:#fff;"<?php } ?> value="<?php echo $row['titolo']?>">
                </div>
                </div>
            <div class="row" style="margin-bottom:1.5%;">
                <div class="col-md-2">

               </div>
            <div class="col-md-2">
                  <span style="font-weight:600;">Allegati:</span>
               </div>

                <div class="col-md-3">
                    <?php //Imposto la directory da leggere
$directory = "/Applications/XAMPP/xamppfiles/htdocs/area_riservata/files/".$row['id_messaggio']."/";
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
    {
echo $file;
   if(isset($_GET['id'])) { echo "<input style='width: 20px;' type='image' name='delete_notemp' value='delete' src='img/x.png'><input type='hidden' name='nome_file' value='".$file."'><br/>"; }
}
}
//Chiudo la lettura della directory.
closedir($directory_handle);
}
} ?>
                </div>
            </div>
              <div class="row" style="margin-bottom:1.5%;">
                <div class="col-md-2">

               </div>
            <div class="col-md-2">
                  <span style="font-weight:600;">Allegati da Aggiungere:</span>
               </div>


                     <div class="col-md-2"><?php if(isset($_GET['id'])) { ?><input type="file"  name="fileToUpload" id="fileToUpload" style="width: 150px;">
                    <input type="hidden" name="id_user" value="<?php echo $cod_anagr;?>">
                     </div>
                   <div class="col-md-2">
                    <input type="submit" title="Allega File" value="Allega File" name="submit" class="myButton" style="height: 25px;border:1px solid #000;font-size:12px;line-height:10px;"><?php } ?></td></tr>
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
    {
echo $file;
   if(isset($_GET['id'])) { echo "<input style='width: 50px; margin-left: -10px;' type='image' name='delete' value='delete' src='img/x.png'><input type='hidden' name='nome_file' value='".$file."'><br/>"; }
}
}
//Chiudo la lettura della directory.
closedir($directory_handle);
}
} ?></div></div>

             <div class="row" style="margin-bottom:1.5%;">
                 <div class="col-md-2">

               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Visibile a:</span>
               </div>
             <div class="col-md-6"><input <?php if(strpos($row['gruppi'], "2")!==false||strpos($row['gruppi'], "true")) { ?> checked="checked"<?php } ?> type="checkbox" id="check_all" name="checkbox[]" <?php if(!isset($_GET['id'])) { ?>disabled="disabled" <?php } ?> value="2">Ufficio
                    <input <?php if(strpos($row['gruppi'], "3")!==false||strpos($row['gruppi'], "true")) { ?> checked="checked"<?php } ?> type="checkbox" id="check_all" name="checkbox[]" <?php if(!isset($_GET['id'])) { ?>disabled="disabled" <?php } ?> value="3">Esterni
                    <input <?php if(strpos($row['gruppi'], "4")!==false||strpos($row['gruppi'], "true")) { ?> checked="checked"<?php } ?> type="checkbox" id="check_all" name="checkbox[]" <?php if(!isset($_GET['id'])) { ?>disabled="disabled" <?php } ?> value="4">Tenici
                    <input <?php if(strpos($row['gruppi'], "5")!==false||strpos($row['gruppi'], "true")) {?> checked="checked"<?php } ?>type="checkbox" id="check_all" name="checkbox[]" <?php if(!isset($_GET['id'])) { ?>disabled="disabled" <?php } ?> value="5">SW Team
                    <input <?php if(strpos($row['gruppi'], "6")!==false||strpos($row['gruppi'], "true")) {?> checked="checked"<?php } ?> type="checkbox" id="check_all" name="checkbox[]" <?php if(!isset($_GET['id'])) { ?>disabled="disabled" <?php } ?> value="6">Responsabili
                    <input <?php if(strpos($row['gruppi'], "true")) {?> checked="checked"<?php } ?>type="checkbox" name="checkbox[]" id="checkbox_check" value="1">Tutti
             </div>
             </div>

                  <div class="row" style="margin-bottom:1.5%;">
                 <div class="col-md-2">

               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Data Fine:</span>
               </div>
             <div class="col-md-3"><input type="text" name="data_fine" <?php if(!isset($_GET['id'])){ ?> disabled="disabled" style="background:#fff;"<?php } ?> id="datepicker" value="<?php echo $row['data_fine']?>"/>
             </div>
                  </div>
            <div class="row" style="margin-bottom:1.5%;">
                 <div class="col-md-2">

               </div>
               <div class="col-md-2">
                  <span style="font-weight:600;">Testo:</span>
               </div>
                <div class="col-md-3"><textarea style="width: 500px; height: 150px;background-color: white;" name="testo_messaggio" <?php if(!isset($_GET['id'])){ ?> disabled="disabled"<?php } ?>><?php echo $row['testo']?></textarea></div></div>
            <?php if(isset($_GET['id'])) {?><div class="row" style="margin-bottom:1.5%;">
                 <div class="col-md-8">

               </div>
                <div class="col-md-4"><input type="submit" class="btn btn-default btn-sm" name="messaggio" value="Modifica Messaggio"></div></div><?php } ?>
            <input type="hidden" name="id_messaggio" value="<?php echo $row['id_messaggio'];?>">
             </div>
                  </div>


            </div>
   </form>

<?php
             }
         ?>
