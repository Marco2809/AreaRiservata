<?php
    if(isset($_GET['id'])) {
        $userClass = new User();
        $user = $userClass->getUserById($_GET['id']);
    }

    if(isset($_POST['assign-job-submit'])) {
        $jobAssign = new Commessa();
        $code = $jobAssign->createUserCommesse($_POST['id-job-assign'], $_GET['id'], 4);
    }

    if(isset($_POST['delete-job-submit'])) {
        $jobDelete = new Commessa();
        $code = $jobDelete->deleteUserCommesse($_POST['delete-job-submit']);
    }
?>

<section class="">

    <!--FOTO DIPENDENTE-->
    <div class="col-lg-2">
        <center>
            <div class="row">
                <div id="foto">
                    <img src="assets/img/avatar/profilo.png">
                </div>
            </div>
        </center>
    </div>
    <!--FINE FOTO DIPENDENTE-->



    <!-- DATI PERSONALI -->
    <div id="" class="col-lg-10">
    <div id="dati-personali" class="col-lg-12 ds">
    <div class="panel panel-primary" style="margin-bottom:2%;">
    <div class="legenda-heading" style="padding-left:2%;">
    <span class="legenda-title">Dati Personali</span>
    </div>
    <div class="panel-body">
    <table class="table" style="margin-top:1%;">
    <tbody>
        <tr>
            <td class="td-intestazione">NOME</td>
            <td><?php echo $user['nome']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">COGNOME</td>
            <td><?php echo $user['cognome']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">DATA di NASCITA</td>
            <td><?php echo $user['data_nascita']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">LUOGO di NASCITA</td>
            <td><?php echo $user['luogo_nascita']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">NAZIONALITA'</td>
            <td><?php echo $user['nazionalita']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">TELEFONO</td>
            <td><?php echo $user['phone']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">INDIRIZZO di RESIDENZA</td>
            <td><?php echo $user['indirizzo_residenza']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">CITTA' di RESIDENZA</td>
            <td><?php echo $user['citta_residenza']; ?></td>
        </tr>

        <tr>
            <td class="td-intestazione">CODICE FISCALE</td>
            <td><?php echo $user['codice_fiscale']; ?></td>
        </tr>
    </tbody>
    </table>
    </div>
    </div>

    <!-- SEZIONE COMMESSE -->
    <div class="panel panel-primary" style="margin-bottom: <?= (count($allJobs) > 0) ? 2 : 5; ?>%;">
                <div class="calendar-heading">
          <div style="padding-left:1%; padding-right:2%;">
          <span class="legenda-title">Lista Commesse Assegnate</span>
          </div>
          </div>

     <div class="panel-body">
       <form action="" method="POST">
          <table class="table" style="margin-bottom: 5px;">
            <thead>
              <th width="60%">Nome</th>
              <th width="30%">Ruolo</th>
              <th width="10%">Azioni</th>
            </thead>
             <tbody>
<?php
                $commessa = new Commessa();
                $jobsAssigned = $commessa->getAssignedJobs($_GET['id']);

                foreach($jobsAssigned as $job) {
                    echo '<tr>
                            <td>' . $job[0] . '</td>
                            <td>' . (($job[2] == 2) ? 'Responsabile' : 'Membro') . '</td>
                           <td>
                            <button type="submit" class="btn btn-danger btn-sm" style="float:right;" value="' . $job[1] . '" name="delete-job-submit">
                                <i class="fa fa-minus-circle"></i> Elimina
                            </button>
                           </td>
                         </tr>';
                }
?>
             </tbody>
          </table>
       </form>
       </div>
    </div>


<?php
    $allJobs = $commessa->getCommesseByResponsabile($_SESSION['user_idd']);

    if (count($allJobs) > 0) { ?>

    <div class="panel panel-primary" style="margin-bottom: 5%;">
      <div class="legenda-heading">
        <div style="padding-left: 1%; padding-right: 2%;">
          <span class="legenda-title">Assegna Nuova Commessa</span>
        </div>
      </div>

      <div class="panel-body">
        <form action="" method="POST">
          <table class="table" style="margin-top: 1%;">
            <tbody>
              <tr>
                <td>
                  <select name="id-job-assign" style="width: 60%; margin-top: 1%;" class="form-control">
<?php
                  foreach($allJobs as $job) {
                      echo '<option value="' . $job[2] . '">' .
                           $job[2] . '</option>';
                  }
?>
                  </select>
                </td>
                <td>
                  <button name="assign-job-submit" type="submit" class="btn btn-sm btn-success" style="float: right;">
                      <i class="fa fa-plus-circle"></i> Assegna
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
       </div>
    </div>
    <?php } ?>
    <!-- FINE SEZIONE COMMESSE -->

    </div>
    </div>
    <!-- FINE DATI PERSONALI -->

</section>
