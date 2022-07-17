<!-- GESTIONE RICHIESTE -->
<?php

if(isset($_POST['add-commessa'])) {
    $commessa = new Commessa();
    $commessa->addCommessa($_POST['responsabili'], $_POST['nome-commessa'],
            $_POST['cliente'], $_POST['alias']);
}

if(isset($_POST['edit-commessa'])) {
    $commessa = new Commessa();
    $commessa->updateCommessa($_POST['responsabili-list-old'], $_POST['responsabili-list'],
            $_POST['modal-commessa'], $_POST['modal-cliente'], $_POST['modal-alias'],
            $_POST['id-commessa']);
}

if(isset($_POST['delete-commessa'])) {
    $commessa = new Commessa();
    $commessa->deleteCommessa($_POST['id-commessa']);
}

if(isset($_POST['add-dipendente'])) {
    $userCommessa = new Commessa();
    $userCommessa->createUserCommesse($_POST['id-job'], $_POST['add-dipendente'], '4');
}

if(isset($_POST['remove-dipendente'])) {
    $userGroup = new Commessa();
    $userGroup->deleteUserCommesseById($_POST['id-job'], $_POST['remove-dipendente']);
}

?>
<!--CONTENUTO PRINCIPALE-->
<section class="">

<div class="panel panel-primary">
            <div class="legenda-heading">
      <div style="padding-left:1%;padding-right:2%;">
      <span class="legenda-title">Aggiungi Commessa</span>
      </div>
      </div>

 <div class="panel-body">
   <form action="" method="POST">
        <table class="table" style="margin-top:1%;">
        <tbody>

        <tr>
        <td class="td-intestazione">Nome Commessa:</td>
        <td>
        <input name="nome-commessa" class="form-control" value="" type="text" required>
        </td>
        </tr>

        <tr>
        <td class="td-intestazione">Cliente:</td>
        <td>
        <input name="cliente" class="form-control" value="" type="text" required>
        </td>
        </tr>

        <tr>
        <td class="td-intestazione">Alias:</td>
        <td>
        <input name="alias" class="form-control" value="" type="text" required>
        </td>
        </tr>

        <tr>
        <td class="td-intestazione">Responsabile:</td>
        <td>
        <select name="responsabili" style="margin-top:1%;" class="form-control">
<?php
            $user = new User();
            $allUsers = $user->getAll();

            foreach($allUsers as $responsabile) {
                echo '<option value="' . $responsabile["user_idd"] . '">' .
                     $responsabile["cognome"] . ' ' . $responsabile["nome"] . '</option>';
            }
?>
        </select>
        </td>
        </tr>

        <tr>
        <td>
        <button type="submit" name="add-commessa" class="btn btn-success">Aggiungi</button>
        </td>
        </tr>

        </tbody>
        </table>
        </form>
   </div>
</div>

<form action="" method="POST">
<div class="panel panel-primary" style="margin-bottom:2%;">
    <div class="calendar-heading">
        <div style="padding-left:1%;padding-right:2%;">
            <span class="legenda-title">Commesse</span>
            <a href="#">
                <span class="legenda-title">
                <i class="fa fa-chevron-circle-left fa-2x" style="float:right;margin-top:-0.5%;"></i>
                </span>
            </a>
        </div>
    </div>

 <div class="panel-body">
    <form action="" method="post">
        <table class="table" style="margin-top:1%;">
            <tbody>
                <tr>
                    <td class="td-intestazione">Seleziona Commessa:</td>
                    <td>
                        <select id="ufficio" name="ufficio" style="width:60%;margin-top:1%;"
                            class="form-control" onchange="changeModal(this.value); changeJob(this.value, this);">
                        <option value="0">Seleziona una commessa...</option>
<?php
                        $comm = new Commessa();
                        $allJobs = $comm->getAll();
                        foreach($allJobs as $job) {
                            echo '<option value="' . $job->id_commessa . '"';

                            if(isset($_POST['id-job'])) {
                                if($job->id_commessa == $_POST['id-job']) {
                                    echo ' selected';
                                }
                            }

                            echo '>' . $job->nome_commessa . '</option>';
                        }
?>
                        </select>
                    </td>
                    <td>
                        <a href="#ModalForm" data-toggle="modal" data-target="#ModalForm">
                            <button id="detailJobBtn" type="button" class="btn btn-info" data-toggle="modal" data-target="#ModalEvents">Dettaglio</button>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

</div>

<div class="row" style="margin-bottom:5%;">
<div id="panel-list-employees" class="col-md-6">

<?php

if(isset($_POST['id-job'])) {

?>

<div class="panel panel-primary filterable" style="margin-bottom:5%;">
  <div class="calendar-heading">
              <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Lista Dipendenti</span>
      <div class="pull-right">
        <button class="btn btn-info btn-xs btn-filter button-clear" style="color:#fff;"><span class="fa fa-search fa-2x"></span>
<span class="span-title">&nbsp; Cerca</span>
</button>
      </div>
  </div>
</div>
<form action="" method="POST">
  <table class="table">
      <thead>
          <tr class="filters">
              <th><input type="text" class="form-control" placeholder="Dipendente"></th>
              <th><input type="text" class="form-control" placeholder="Data di Nascita"></th>
              <th><input type="text" class="form-control" placeholder="Aggiungi"></th>
          </tr>
      </thead>

      <tbody>
<?php
            $jobToShow = $_POST['id-job'];

            $userDip = new User();
            $dipendenti = $userDip->getAllExceptJob($jobToShow);
            echo '<input name="id-job" type="hidden" value="' . $jobToShow . '">';
            foreach($dipendenti as $dipendente) {
                echo '<tr>' .
                     '<td>' . $dipendente['nome'] . ' ' . $dipendente['cognome'] . '</td>' .
                     '<td>' . $dipendente['data_nascita'] . '</td>' .
                     '<td><button name="add-dipendente" type="submit" value="' . $dipendente['user_id'] . '"'
                        . 'style="background-color:transparent; border:none; outline:none;">' .
                     '<i class="fa fa-plus-circle fa-2x" style="color:#77c803;"></i></button></td>' .
                     '</tr>';
            }
?>
      </tbody>
  </table>
</form>
</div>

<?php } ?>

</div>
<div id="panel-current-job" class="col-md-6" style="margin-bottom:5%;">

<?php

if(isset($_POST['id-job'])) {

?>

    <div class="panel panel-primary filterable" style="margin-bottom:5%;">
      <div class="calendar-heading">
                  <div style="padding-left:2%;padding-right:2%;">
                          <span class="legenda-title">Commessa <?php echo $allGruppi[$groupToShow-1]['gruppo']; ?></span>
          <div class="pull-right">
            <button class="btn btn-info btn-xs btn-filter button-clear" style="color:#fff;"><span class="fa fa-search fa-2x"></span>
    <span class="span-title">&nbsp; Cerca</span>
    </button>
          </div>
      </div>
                  </div>
    <form action="" method="POST">
      <table class="table">
          <thead>
              <tr class="filters">
                  <th><input type="text" class="form-control" placeholder="Dipendente"></th>
                  <th><input type="text" class="form-control" placeholder="Data di Nascita"></th>
                  <th><input type="text" class="form-control" placeholder="Rimuovi"></th>
              </tr>
          </thead>
          <tbody>
    <?php
                    $jobUser = new Commessa();
                    $firstJobUsers = $jobUser->getMembersByJobId($jobToShow);
                    $resp = $jobUser->getRespByJobId($jobToShow);
                    echo '<input name="id-job" type="hidden" value="' . $jobToShow . '">';
                  foreach($firstJobUsers as $userJob) {

                    $color="";
                    if(in_array($userJob['user_id'],$resp)) $color="style='background-color: green';";

                      echo '<tr' .$color.'>' .
                           '<td>' . $userJob['nome'] . ' ' . $userJob['cognome'] . '</td>' .
                           '<td>' . $userJob['data_nascita'] . '</td>' .
                           '<td><button name="remove-dipendente" type="submit" value="' . $userJob['user_id'] .
                              '" style="background-color:transparent; border:none; outline:none;">'
                              . '<i class="fa fa-minus-circle fa-2x" style="color:#d90e0e;"></i></button></td>' .
                           '</tr>';
                  }
    ?>
          </tbody>
      </table>
    </form>
    </div>

<?php } ?>

</div>

</div>

</section>
<!--FINE CONTENUTO PRINCIPALE-->

<!--MODAL DETTAGLIO COMMESSA-->
<div class="modal fade" id="ModalEvents" role="dialog">
  <form action="" method="POST">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">DETTAGLIO COMMESSA</h4>
          </div>

<?php       if(isset($_POST['id-job'])) {
                $commessa = new Commessa();
                $infoJob = $commessa->getJobById($_POST['id-job']);
?>

          <div class="modal-body">
            <form action="" method="POST">
           <table class="table" style="margin-top:1%;">
           <tbody>

           <tr>
           <td class="td-intestazione">Commessa:</td>
           <td>
           <input name="modal-commessa" name="" class="form-control" value="<?php echo $infoJob['commessa']; ?>" type="text">
           </td>
           </tr>

           <tr>
           <td class="td-intestazione">Cliente:</td>
           <td>
           <input name="modal-cliente" class="form-control" value="<?php echo $infoJob['cliente']; ?>" type="text">
           </td>
           </tr>

           <tr>
           <td class="td-intestazione">Alias:</td>
           <td>
           <input name="modal-alias" class="form-control" value="<?php echo $infoJob['nome_commessa']; ?>" type="text">
           </td>
           </tr>

           <tr>
           <td class="td-intestazione">Responsabile/i:</td>
           <td>
               <div id="div-lista-resp">
               <?php
               $user = new User();
               $responsabili = $user->getResponsabiliByCommessa($_POST['id-job']);
               $idResponsabili = array();
               foreach($responsabili as $resp) {
                   array_push($idResponsabili, $resp["id_user"]);
                   echo '<button type="button" value="' . $resp["id_user"] . '" class="btn btn-primary btn-sm" '
                           . 'style="margin-right:5px;" onclick="removeResponsabile(this, this.value)">' . $resp["nome"] .
                           ' ' . $resp["cognome"] . ' <span class="glyphicon glyphicon-remove"></span></button>';
               }
               ?>
               </div>
               <div class="row">
                 <div class="col-md-9">
                   <select id="select-responsabili" name="modal-responsabili" style="margin-top:1%;" class="form-control">
                     <?php
                         $usersNotResp = $user->getUsersExceptRespCommessa($_POST['id-job']);
                         foreach($usersNotResp as $user) {
                             echo '<option value="' . $user["user_id"] . '">' .
                             $user["cognome"] . ' ' . $user["nome"] . '</option>';
                         }
                     ?>
                   </select>
                 </div>
                 <div class="col-md-3" style="margin-top:4px;">
                     <button type="button" class="btn btn-success" onclick="addResponsabile()">
                         <span class="glyphicon glyphicon-plus"></span> Aggiungi
                     </button>
                 </div>
               </div>
           </td>
           </tr>
           <input type="hidden" name="id-commessa" value="<?php echo $_POST['id-job']; ?>">
           <input type="hidden" name="responsabili-list" id="responsabili-list"
                  value="<?php echo implode("-", $idResponsabili); ?>">
           <input type="hidden" name="responsabili-list-old" id="responsabili-list-old"
                  value="<?php echo implode("-", $idResponsabili); ?>">
           </tbody>
           </table>
           </form>
           </div>
           <div class="modal-footer">
              <button name="edit-commessa" type="submit" class="btn btn-success">Modifica</button>
              <button name="delete-commessa" type="submit" class="btn btn-danger">Elimina</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">
                 <span class="glyphicon glyphicon-menu-left"></span> Indietro
             </button>
           </div>

<?php       } else {
                echo '<div class="modal-body"><center>Seleziona una commessa</center></div>';
                echo '<div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                     </div>';
            }
?>

       </div>
    </div>
  </form>
</div>
<!--MODAL DETTAGLIO COMMESSA-->

<script type="text/javascript">
    function changeModal(str) {
        if (str == "") {
            document.getElementById("ModalEvents").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("ModalEvents").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "get-dettaglio-commessa.php?id=" + str, true);
            xmlhttp.send();
        }
    }

    function addResponsabile() {
        var dipendenteSel = document.getElementById("select-responsabili");
        var idDipendente = dipendenteSel.options[dipendenteSel.selectedIndex].value;
        var nomeDipendente = dipendenteSel.options[dipendenteSel.selectedIndex].text;
        var divListaResp = document.getElementById("div-lista-resp");
        var newResp = document.createElement("button");
        newResp.setAttribute('type', 'button');
        newResp.setAttribute('value', idDipendente);
        newResp.setAttribute('class', 'btn btn-primary btn-sm');
        newResp.setAttribute('style', 'margin-right:5px;');
        newResp.setAttribute('onclick', 'removeResponsabile(this, this.value)');
        newResp.innerHTML = nomeDipendente + " <span class=\"glyphicon glyphicon-remove\"></span>";
        divListaResp.appendChild(newResp);

        for(var i=0; i<dipendenteSel.length; i++){
            if(dipendenteSel.options[i].value == idDipendente) {
               dipendenteSel.remove(i);
           }
        }

        var listaIdResponsabili = document.getElementById("responsabili-list").value;
        if(listaIdResponsabili != "") {
            listaIdResponsabili += "-" + idDipendente;
        } else {
            listaIdResponsabili = idDipendente
        }
        document.getElementById("responsabili-list").value = listaIdResponsabili;
    }

    function removeResponsabile(buttonDipendente, idUser) {
        var nameDipendente = buttonDipendente.textContent;
        var newOption = document.createElement('option');
        newOption.value = idUser;
        newOption.innerHTML = nameDipendente;
        var dipendenteSel = document.getElementById("select-responsabili");
        dipendenteSel.appendChild(newOption);

        buttonDipendente.parentNode.removeChild(buttonDipendente);

        var listaIdResponsabili = document.getElementById("responsabili-list").value;
        var arrayIdResponsabili = listaIdResponsabili.split("-");
        var indexToRemove = arrayIdResponsabili.indexOf(idUser);
        if(indexToRemove > -1) {
            arrayIdResponsabili.splice(indexToRemove, 1);
        }
        listaIdResponsabili = arrayIdResponsabili.join("-");
        document.getElementById("responsabili-list").value = listaIdResponsabili;
    }

    function changeJob(idJob, nameJob) {
        if(idJob == "0") {
            document.getElementById("panel-list-employees").innerHTML = "";
            document.getElementById("panel-current-job").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("panel-current-job").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "get-commessa.php?id=" + idJob + '&name=' +
                    nameJob.options[nameJob.selectedIndex].text, true);
            xmlhttp.send();

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("panel-list-employees").innerHTML = this.responseText;
                    $('script[src="assets/js/filter-table.js"]').remove();
                    var script = document.createElement('script');
                    script.src = 'assets/js/filter-table.js';
                    $('body').append(script);
                }
            };
            xmlhttp.open("GET", "get-lista-dip-commesse.php?id=" + idJob + '&name=' +
                    nameJob.options[nameJob.selectedIndex].text, true);
            xmlhttp.send();

        }
    }

</script>
