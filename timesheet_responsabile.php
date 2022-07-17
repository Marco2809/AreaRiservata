<?php

if (isset($_POST['next'])) {
    if (isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }

    if (isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    switch($id_mese) {
        case "01":
            $id_mese = 1;
            break;
        case "02":
            $id_mese = 2;
            break;
        case "03":
            $id_mese = 3;
            break;
        case "04":
            $id_mese = 4;
            break;
        case "05":
            $id_mese = 5;
            break;
        case "06":
            $id_mese = 6;
            break;
        case "07":
            $id_mese = 7;
            break;
        case "08":
            $id_mese = 8;
            break;
        case "09":
            $id_mese = 9;
            break;
    }

    if ($id_mese == 12) {
        $id_anno++;
        $id_mese = 1;
    }
    else {
        $id_mese = $id_mese + 1;
    }

    if ($id_mese < 10) {
        $id_mese_original = '0' . $id_mese;
    } else {
        $id_mese_original = $id_mese;
    }

 }

if (isset($_POST['prev'])) {
    if (isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese_original = $id_mese = $_REQUEST['id_mese'];
    }

    if (isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if ($id_mese == 1) {
        $id_mese = 12;
        $id_anno = $id_anno - 1;
    }
    else $id_mese = $id_mese - 1;

    if ($id_mese < 10) {
        $id_mese_original = '0' . $id_mese;
    } else {
        $id_mese_original = $id_mese;
    }
 }


if (!isset($id_mese)) {
    $id_mese_original = $id_mese = date("m");
    $id_mese1 = date("m");
} else {
    if (!isset($id_mese1)) {
        if ($id_mese <= 9) $id_mese1 = "0" . $id_mese;
        else $id_mese1 = $id_mese;
    }
}

if (!isset($id_anno)) {
    $id_anno = date("Y");
}

switch($id_mese) {
    case 1:
        $mese_cal = "Gennaio";
        break;
    case 2:
        $mese_cal = "Febbraio";
        break;
    case 3:
        $mese_cal = "Marzo";
        break;
    case 4:
        $mese_cal = "Aprile";
        break;
    case 5:
        $mese_cal = "Maggio";
        break;
    case 6:
        $mese_cal = "Giugno";
        break;
    case 7:
        $mese_cal = "Luglio";
        break;
    case 8:
        $mese_cal = "Agosto";
        break;
    case 9:
        $mese_cal = "Settembre";
        break;
    case 10:
        $mese_cal = "Ottobre";
        break;
    case 11:
        $mese_cal = "Novembre";
        break;
    case 12:
        $mese_cal = "Dicembre";
        break;
}

if (!isset($_REQUEST['num_giorni'])) {
    if (!checkdate($id_mese,28 + 1, $id_anno)) {
        $num_giorni = 28;
    } else if (!checkdate($id_mese,29 + 1, $id_anno)) {
        $num_giorni = 29;
    } else if(!checkdate($id_mese, 30 + 1, $id_anno)) {
        $num_giorni = 30;
    } else if(!checkdate($id_mese,31 + 1, $id_anno)) {
        $num_giorni = 31;
    }
}
?>

<!-- CONTENUTO PRINCIPALE -->

<section class="">
    <div class="panel panel-primary" style="margin-bottom:1%;">
        <center>
            <div class="calendar-heading">
                <form action="" method="post">
                    <button type="submit" name="prev" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-left fa-2x" aria-hidden="true"></i></button>

               <span class="mese-title"><?php echo $mese_cal . " " . $id_anno; ?></span>

               <button type="submit" name="next" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-right fa-2x" aria-hidden="true"></i></button>

               <input type="hidden" name="id_mese" value="<?php echo $id_mese;?>">
               <input type="hidden" name="id_anno" value="<?php echo $id_anno;?>">
                </form>
            </div>
        </center>
    </div>

    <div class="panel panel-primary filterable" style="margin-bottom:5%;">
      <?php
        $commesse = new Commessa();
        $listaCommesse = $commesse->getIDCommesseByResponsabile($_SESSION['user_idd']);
      ?>
      <form action="export-timesheet-dipendenti.php" method="get">
        <input type="hidden" name="mese" value="<?= $id_mese ?>">
        <input type="hidden" name="anno" value="<?= $id_anno ?>">
        <input type="hidden" name="id_commesse" value="<?php echo implode(",", $listaCommesse); ?>">
        <div class="legenda-heading">
            <div style="padding:1%;">
                <button class="btn btn-success btn-xs btn-filter button-clear" style="color:#fff;">
                    <span class="fa fa-search fa-2x"></span>
                    <span class="span-title">&nbsp; Cerca</span>
                </button>
                <div class="pull-right">
                    <button class="btn btn-success btn-xs" type="submit" style="color:#fff;">
                        <span class="fa fa-file-excel-o fa-2x"></span>
                        <span class="span-title">&nbsp; Download Excel</span>
                    </button>
                </div>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr class="filters">
                    <th width="25%"><input type="text" class="form-control" name="dipendente" placeholder="Dipendente"></th>
                    <th width="35%"><input type="text" class="form-control" name="commessa" placeholder="Commesse"></th>
                    <th width="15%">Num. Giorni</th>
                    <th width="15%">Ore Totali</th>
                    <th width="10%">Stato</th>
                </tr>
            </thead>
            <tbody>
<?php
            $attivitaClass = new Attivita();
            $usersArray = $attivitaClass->selectHoursByMonthAndYearForResponsabile($id_mese_original, $id_anno, $listaCommesse);
            foreach($usersArray as $user) {
                if($user['valid'] != '') {
                    $user['valid'] = '<span class="label label-danger">Non Validato</span>';
                } else {
                    $user['valid'] = '<span class="label label-success">Validato</span>';
                }

                echo '<tr>' .
                        '<td>' . $user['surname'] . ' ' . $user['name'] . '</td>' .
                        '<td>' . $user['jobs'] . '</td>' .
                        '<td>' . $user['totDays'] . '</td>' .
                        '<td>' . $user['totHours'] . '</td>' .
                        '<td>' . $user['valid'] . '</td>' .
                     '</tr>';
            }
?>
            </tbody>
        </table>
    </div>
</section>

<!-- FINE CONTENUTO PRINCIPALE -->
