<h3>ULTIMI 5 MESSAGGI</h3>
<?php

$database = new Database();
$conn = $database->dbConnection();

if (isset($_POST['action']) && $_POST['action']=="update") { ?>

<script type="text/javascript">
    $(function () {
        $('#ModalModifica<?= $_POST['update_id_mex'];?>').modal();
    });
</script>

<?php }
    if (isset($_GET['id']) && $_GET['id'] != "") {
        $mex = new Message();
        $permitted = $mex->havePermission($_GET['id'], $_SESSION['user_idd']);
        if ($permitted == "ok") {
            $mex->setRead($_GET['id'], $_SESSION['user_idd']); ?>

<script type="text/javascript">
    $(function() {
        $('#ModalDettaglio<?= $_GET['id'];?>').modal();
    });
</script>

<?php
        } else echo $permitted;
    }

$num = 0;
$sql = "SELECT m.id_messaggio as id_mex, m.titolo, m.testo, m.id_autore, m.data_creazione, a.nome, a.cognome
        FROM messaggi as m, anagrafica as a, message_groups as mg, user_groups as ug, user_commesse as uc
        WHERE (((ug.id_user = '" . $_SESSION['user_idd'] . "' AND ug.id_gruppo = mg.id_gruppo AND mg.id_messaggio = m.id_messaggio)
            OR (uc.id_user = 118 AND uc.id_commessa = mg.id_commessa AND mg.id_messaggio = m.id_messaggio)
            OR (mg.id_destinatario = '" . $_SESSION['user_idd'] . "' AND mg.id_messaggio = m.id_messaggio))) AND m.id_autore = a.user_id
        GROUP BY id_mex
        ORDER BY id_mex DESC LIMIT 0, 5";

if (isset($_GET['view']) && $_GET['view'] == "all_messages") {
    $sql = "SELECT m.id_messaggio as id_mex, m.titolo, m.testo, m.id_autore, m.data_creazione, a.nome, a.cognome
            FROM messaggi as m, anagrafica as a, message_groups as mg, user_groups as ug, user_commesse as uc
            WHERE (((ug.id_user = '" . $_SESSION['user_idd'] . "' AND ug.id_gruppo = mg.id_gruppo AND mg.id_messaggio = m.id_messaggio)
                OR (uc.id_user = 118 AND uc.id_commessa = mg.id_commessa AND mg.id_messaggio = m.id_messaggio)
                OR (mg.id_destinatario = '" . $_SESSION['user_idd'] . "' AND mg.id_messaggio = m.id_messaggio))) AND m.id_autore = a.user_id
            GROUP BY id_mex
            ORDER BY id_mex DESC";
}

$result = $conn->query($sql);

while ($obj_mex = $result->fetch_object()) {
    $num++;
    $destinatario = $obj_mex-> nome . " " . $obj_mex->cognome;
    $time = $obj_mex->data_creazione;

                     print' <div class="desc" >';
                     print '<div class="col-md-12" >';
                     print '<div class="thumb">';
                     print '<span class="badge bg-theme" ><i class="fa fa-clock-o"></i></span>';
                     print '</div>';
                     print '<div class="details" style="width: 85%;">';
                     print '<p><b>'.$obj_mex->titolo.'</b><br>';
                     print '<muted>'.$time.'</muted><br/>';
                     print '<a style="color: #68dff0;" data-toggle="modal" href="dashboard.php?id='.$obj_mex->id_mex.'">Messaggio da '.$destinatario.'</a>';
                     print '</p>';
                     print '</div>';
                     print '</div>';
                     if($_SESSION['user_idd']==$obj_mex->id_autore&&$_SERVER['PHP_SELF']!='/new_area_riservata/dashboard.php') print '<div class="col-md-2"><form action"" method="post"><h4><a style="color: #68dff0;" data-toggle="modal" data-backdrop="static" href=""  data-target="#ModalModifica'.$obj_mex->id_mex.'"><span class="glyphicon glyphicon-edit"></span></a><input type="hidden" name="id_mex" value="'.$obj_mex->id_mex.'"><button type="submit" name="delete_message" style="color: #68dff0; background-color:transparent; border:none;"><span style="margin-left:25%;" class="glyphicon glyphicon-trash"></span></button></h4></form></div>';
                     print '</div>';

                     print '	<!-- Modal Dettaglio -->
						<div class="modal fade" id="ModalDettaglio'.$obj_mex->id_mex.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">'.strtoupper($obj_mex->titolo).'</h4>
						        <h5 class="modal-title" id="myModalLabel">Messaggio scritto da <strong>'.$destinatario.'</strong> il '.$time.'</h5>
						      </div>
						      <div class="modal-body">';
                                                 $directory = "./files/".$obj_mex->id_mex."/";
// Apriamo una directory e leggiamone il contenuto.
if (is_dir($directory)) {
//Apro l'oggetto directory
if ($directory_handle = opendir($directory)) {
    $con=0;
//Scorro l'oggetto fino a quando non è terminato cioè false
while (($file = readdir($directory_handle)) !== false) {
    $con++;
    if($con==3) print'<strong>Allegati</strong>: ';
//Se l'elemento trovato è diverso da una directory
//o dagli elementi . e .. lo visualizzo a schermo
if((!is_dir($file))&($file!=".")&($file!=".."))
    if ($file!=".DS_Store")
print "<div class='panel panel-default'><div class='panel-heading'>".$file . "<a target='_blank' href='./files/".$obj_mex->id_mex.'/'.$file."' class='btn btn-xs btn-info' style='margin-left: 1%; color: white;'>Visualizza</a></div></div>";
}
//Chiudo la lettura della directory.
closedir($directory_handle);
}
}
						       print '<strong>Testo</strong>:<br> '.$obj_mex->testo.'
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
						      </div>
						    </div>
						  </div>
						</div>
      				<!--Fine Modal Dettaglio -->';

                     print '<!-- Modal Modifica -->
                                                <form action="" method="post" enctype="multipart/form-data">
						<div class="modal fade" id="ModalModifica'.$obj_mex->id_mex.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">';
                                                    if(isset($res_update)) print $res_update;
                                                    if(isset($alert_allegato)) print $alert_allegato;
						      print '<div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						        <h4 class="modal-title">Titolo:</h4> <input type text name="titolo_messaggio" value="'.$obj_mex->titolo.'">

						      </div>
						      <div class="modal-body">
                                                             <div class="col-md-5">
                     <input type="file"   name="fileToUpload" id="fileToUpload" style="width: 150px;">

</div>
                 <div class="col-md-5">
                    <input type="submit" class="btn btn-xs btn-primary" title="Allega File" value="Allega File" name="add_allegato" style="margin-bottom:10px;">
                 </div><br><br>
							  ';
                                                                                                $directory = "/htdocs/new_area_riservata/files/".$obj_mex->id_mex."/";
// Apriamo una directory e leggiamone il contenuto.
if (is_dir($directory)) {
//Apro l'oggetto directory
if ($directory_handle = opendir($directory)) {
    $con=0;
//Scorro l'oggetto fino a quando non è terminato cioè false
while (($file = readdir($directory_handle)) !== false) {
    $con++;
    if($con==3) print'<strong>Allegati</strong>: ';
//Se l'elemento trovato è diverso da una directory
//o dagli elementi . e .. lo visualizzo a schermo
if((!is_dir($file))&($file!=".")&($file!=".."))
    if ($file!=".DS_Store")
print "<div class='panel panel-default'><div class='panel-heading'>".$file . "<input type='submit' style='margin-left:1%;' value='Elimina' name='delete_allegato' class='btn btn-xs btn-danger'><input type='hidden' name='nome_file' value='".$file."'></div></div>";
}
//Chiudo la lettura della directory.
closedir($directory_handle);
}
}
							 print  '<center><textarea name="testo_messaggio" style="width:100%;;max-width:100%;" cols="50" rows="10">'.$obj_mex->testo.'</textarea>
								</center>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
								<button type="submit" name="update_mex" class="btn btn-primary">Salva Modifica</button>
                                                                <input type="hidden" name="update_id_mex" value="'.$obj_mex->id_mex.'">
                                                                <input type="hidden" name="action" value="update">
						      </div>
						    </div>
						  </div>
						</div>
</form>
      				<!--Fine Modal Modifica -->';

                                        }

                                        if($num==0){

                                              print' <div class="desc" >';
                     print '<div class="col-md-12" >';
                     print '<div class="thumb">';
                     print '<span class="badge bg-theme" ><i class="fa fa-clock-o"></i></span>';
                     print '</div>';
                     print '<div class="details" style="width: 85%;">';
                     print '<p><b>Non è presente nessun Messaggio</b><br>';


                     print '</p>';
                     print '</div>';
                     print '</div>';
                     //if($_SESSION['user_idd']==$obj_mex->id_autore&&$_SERVER['PHP_SELF']!='/new_area_riservata/dashboard.php') print '<div class="col-md-2"><form action"" method="post"><h4><a style="color: #68dff0;" data-toggle="modal" data-backdrop="static" href=""  data-target="#ModalModifica'.$obj_mex->id_mex.'"><span class="glyphicon glyphicon-edit"></span></a><input type="hidden" name="id_mex" value="'.$obj_mex->id_mex.'"><button type="submit" name="delete_message" style="color: #68dff0; background-color:transparent; border:none;"><span style="margin-left:25%;" class="glyphicon glyphicon-trash"></span></button></h4></form></div>';
                     print '</div>';


                                }

                        ?>
