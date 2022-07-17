<?php

if(isset($_GET['type'])) {
    $attivita = new Attivita();
    switch($_GET['type']) {
        case 'approve':
            consoleLog("Entrato");
            $resultValidation = array('approve',
                $attivita->approveAllByUser($_GET['user'], $_GET['month'], $_GET['year']));
            break;

        case 'disapprove':
            $resultValidation = array('disapprove',
                $attivita->disapproveAllByUser($_GET['user'], $_GET['month'],
                    $_GET['year'], $_GET['cause']));
            if($resultValidation[1] == 1) {
                $user = new User();
                $email = $user->getEmailByUserId($_GET['user']);
                $mail = new PHPMailer;
                $mail->setFrom('activity.notifier@service-tech.org', 'Activity Notifier');
                $mail->addAddress($email);
                $mail->Subject = 'Notifica rifiuto attività timesheet';
                $mail->isHTML(true);

                $bodyMail = '';
                if(date('G') > 15) {
                    $bodyMail = 'Buonasera,<br><br>';
                } else {
                    $bodyMail = 'Buongiorno,<br><br>';
                }
                $bodyMail .= 'in data odierna non sono state approvate le richieste di ferie del timesheet relative '
                        . 'al mese ' . $_GET['month'] . '/' . $_GET['year'] . ' per il seguente '
                        . 'motivo: "' . $_GET['cause'] . '".<br><br>Per verificare è possibile accedere all\'area riservata: <a href="http://www.service-tech.org/new_area_riservata/login.php">Login Area Riservata Service-Tech</a>';
                $mail->Body = $bodyMail;
                $mail->send();
            }
            break;
    }
}

// Calendar management

if(isset($_GET['id-month'])) {
    $id_mese = $_GET['id-month'];
    if(substr($id_mese, 0, 1) == '0') {
        $id_mese = substr($id_mese, 1, 1);
    }
}

if(isset($_GET['id-year'])) {
    $id_anno = $_GET['id-year'];
}

if(isset($_POST['next'])) {
    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if($id_mese=="01") $id_mese = 1;
    if($id_mese=="02") $id_mese = 2;
    if($id_mese=="03") $id_mese = 3;
    if($id_mese=="04") $id_mese = 4;
    if($id_mese=="05") $id_mese = 5;
    if($id_mese=="06") $id_mese = 6;
    if($id_mese=="07") $id_mese = 7;
    if($id_mese=="08") $id_mese = 8;
    if($id_mese=="09") $id_mese = 9;

    if($id_mese == 12){
        $id_anno++;
        $id_mese = 1;
    }
    else {
        $id_mese= $id_mese + 1;
    }

 }

 if(isset($_POST['prev'])) {

    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if($id_mese == 1) {
        $id_mese = 12;
        $id_anno = $id_anno - 1;
    } else {
        $id_mese = $id_mese - 1;
    }

 }

if(!isset($id_mese)) {
    $id_mese = date("m");
    $id_mese1 = date("m");
} else if($id_mese <= 9) {
    $id_mese = "0" . $id_mese;
}

if(!isset($id_anno)) {
    $id_anno = date("Y");
}

if($id_mese == 1) {
    $mese_cal="Gennaio";
} else if($id_mese == 2) {
    $mese_cal="Febbraio";
} else if($id_mese == 3) {
    $mese_cal="Marzo";
} else if($id_mese == 4) {
    $mese_cal="Aprile";
} else if($id_mese == 5) {
    $mese_cal="Maggio";
} else if($id_mese == 6) {
    $mese_cal="Giugno";
} else if($id_mese == 7) {
    $mese_cal="Luglio";
} else if($id_mese == 8) {
    $mese_cal="Agosto";
} else if($id_mese == 9) {
    $mese_cal="Settembre";
} else if($id_mese == 10) {
    $mese_cal="Ottobre";
} else if($id_mese == 11) {
    $mese_cal="Novembre";
} else if($id_mese == 12) {
    $mese_cal="Dicembre";
}

if(!isset($_REQUEST['num_giorni'])) {
    if(!checkdate($id_mese, 28+1, $id_anno)) {
        $num_giorni = 28;
    } else if(!checkdate($id_mese, 29+1, $id_anno)) {
        $num_giorni = 29;
    } else if(!checkdate($id_mese, 30+1, $id_anno)) {
        $num_giorni = 30;
    } else if(!checkdate($id_mese, 31+1, $id_anno)) {
        $num_giorni = 31;
    }
}

?>

<section class="">
    <center>
        <div class="panel panel-primary">
            <div class="calendar-heading">
                <form action="responsabile.php?action=approva-ferie" method="POST">
                    <button type="submit" name="prev" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-left fa-2x" aria-hidden="true"></i></button>

                    <span class="mese-title"><?php echo $mese_cal . " " . $id_anno; ?></span>

                    <button type="submit" name="next" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-right fa-2x" aria-hidden="true"></i></button>

                    <input type="hidden" name="id_mese" value="<?php echo $id_mese;?>">
                    <input type="hidden" name="id_anno" value="<?php echo $id_anno;?>">
                </form>
            </div>
        </div>
    </center>

<?php
    if(isset($resultValidation)) {
        switch($resultValidation[0]) {
            case 'approve':
                if($resultValidation[1] == 1) {
                    echo '<div class="alert alert-success alert-dismissable fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Approvazione effettuata!</strong> Hai approvato con successo le richieste selezionate.
                          </div>';
                } else if($resultValidation[1] == -1) {
                    echo '<div class="alert alert-danger alert-dismissable fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Approvazione non completata!</strong> Non è stato possibile completare l\'operazione.
                          </div>';
                }
                break;

            case 'disapprove':
                if($resultValidation[1] == 1) {
                    echo '<div class="alert alert-success alert-dismissable fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Approvazione effettuata!</strong> Hai rifiutato con successo le richieste selezionate.
                          </div>';
                } else if($resultValidation[1] == -1) {
                    echo '<div class="alert alert-danger alert-dismissable fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Approvazione non completata!</strong> Non è stato possibile completare l\'operazione.
                          </div>';
                }
                break;
        }
    }
?>

    <div class="row">
    <div class="col-md-12">
    <div class="panel panel-primary filterable" style="margin-bottom:5%;">
        <div class="calendar-heading">
            <div style="padding-left:2%;padding-right:2%;">
                <span class="legenda-title">Richieste di Ferie da approvare</span>
            </div>
        </div>
        <div class="panel-body" style="padding-left:2%;padding-right:2%;">
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th style="width:65%"><input type="text" class="form-control" placeholder="Dipendente"></th>
                        <th style="width:20%"><input type="text" class="form-control" placeholder="Giorni Richiesti di Ferie"></th>
                        <th style="width:15%"><input type="text" class="form-control" placeholder="Azioni"></th>
                    </tr>
                </thead>
                <tbody>
<?php
                        $commesse = new Commessa();
                        $listaCommesse = $commesse->getCommesseByResponsabile($_SESSION['user_idd']);
                        $attivitaClass = new Attivita();
                        $listaAttivita = $attivitaClass->getVacationToApproveByJobs($listaCommesse, $id_mese, $id_anno);
                        $listaUtenti = array();
                        $listaAttivitaPerUtenti = array();

                        foreach($listaAttivita as $attivita) {
                            $posToInsert = null;
                            if(in_array($attivita[1], $listaUtenti)) {
                                $posToInsert = array_search($attivita[1], $listaUtenti);
                            } else {
                                $posToInsert = array_push($listaUtenti, $attivita[1]) - 1;
                                $name = $attivita[2] . ' ' . $attivita[3];
                                array_push($listaAttivitaPerUtenti,
                                    array($listaUtenti[$posToInsert], $name, 0));
                            }

                            $listaAttivitaPerUtenti[$posToInsert][2]++;

                        }

                        foreach($listaAttivitaPerUtenti as $utente) {
                            echo '<tr>
                                    <td style="width:180px;"><a href="responsabile.php?action=approva-dettaglio&id=' .
                                        $utente[0] . '&id-month=' . $id_mese . '&id-year=' . $id_anno . '">' . $utente[1] . '</a></td>' .
                                    '<td>' . $utente[2] . '</td>' .
                                    '<td style="width:140px;">
                                        <center>
                                        <a class="btn btn-success button-clear" 
                                            href="responsabile.php?action=approva-ferie&type=approve&user=' .
                                            $utente[0] . '&month=' . $id_mese . '&year=' . $id_anno . '
                                            &id-month=' . $id_mese . '&id-year=' . $id_anno . '">
                                            <i class="fa fa-check-circle fa-2x" aria-hidden="true"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger
                                            button-refuse" data-toggle="modal" data-target="#disapproveVacationModal" 
                                            data-user-id="' . $utente[0] . '" data-month="' . $id_mese .
                                            '" data-year="' . $id_anno . '">
                                            <i class="fa fa-times-circle fa-2x" aria-hidden="true"></i>
                                        </button>
                                        </center>
                                    </td>' .
                                 '</tr>';
                        }
?>

                </tbody>
            </table>
            </div>
        </div>
    </div>
	</div>
</section>

<div id="disapproveVacationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Approvazione Richiesta di Ferie</h4>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <input type="hidden" value="approva-ferie" name="action" id="action">
                    <input type="hidden" value="disapprove" name="type" id="type">
                    <input type="hidden" value="" name="user" id="user-id">
                    <input type="hidden" value="" name="month" id="month">
                    <input type="hidden" value="" name="year" id="year">
                    <div class="form-group">
                        <label for="comment"><strong>Inserisci il motivo della non approvazione:</strong></label>
                        <textarea class="form-control" rows="5" name="cause"></textarea>
                    </div>
                    <input type="hidden" value="<?php echo $id_mese; ?>" name="id-month" id="id-month">
                    <input type="hidden" value="<?php echo $id_anno; ?>" name="id-year" id="id-year">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="refuse-button">
                        <span class="glyphicon glyphicon-remove"></span> Rifiuta
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <span class="glyphicon glyphicon-chevron-left"></span> Annulla
                    </button>
                </div>
            </form>
          </div>
    </div>
  </div>

<script type="text/javascript" src='./assets/js/responsabile-rifiuto.js'></script>
