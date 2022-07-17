<?php
require_once('class/moduli.class.php');

if(!isset($_SESSION['user_idd'])){
  header("location: https://www.service-tech.it/new_area_riservata/login.htm.php");
}
?>

<!-- **********************************************************************************************************************************************************
     BARRA IN ALTO E NOTIFICHE
      *********************************************************************************************************************************************************** -->
      <!--INIZIO HEADER-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Mostra/Nascondi Menù"></div>
              </div>
            <!--INIZIO LOGO-->
            <a href="dashboard.php" class="logo">
			<b><img src="assets/img/logo.png" style="width:100%;"></b></a>
            <!--FINE LOGO-->

<?php



if(!$_SESSION['is_supplier']) { ?>
    <div class="nav notify-row" id="top_menu">
        <!--  INIZIO NOTIFICHE -->
        <ul class="nav top-menu">
            <!-- FINE NOTIFICHE  -->
            <!-- IMPOSTAZIONI FINE -->
            <!-- INIZIO UNREAD MESSAGGI-->
<?php
            $database = new Database();
            $conn = $database->dbConnection();
            $sql = "SELECT messaggi.id_messaggio, titolo, testo, id_autore, data_creazione, anagrafica.nome, anagrafica.cognome
                    FROM messaggi
                    INNER JOIN anagrafica ON anagrafica.user_id = messaggi.id_autore
                    INNER JOIN message_groups ON message_groups.id_messaggio = messaggi.id_messaggio
                    INNER JOIN read_messages ON read_messages.id_messaggio = messaggi.id_messaggio
                    WHERE messaggi.id_destinatario = " . $_SESSION['user_idd']." AND read_messages.stato = 0
                    ORDER BY id_messaggio DESC LIMIT 0, 5";

            $result = $conn->query($sql);
            $num = $result->num_rows;
?>
            <li id="header_inbox_bar" class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="dashboard.php">
                    <i class="fa fa-envelope-o"></i>
                    <?php if($num > 0) { ?><span class="badge notify"><?php echo $num;?></span><?php } ?>
                </a>
                <ul class="dropdown-menu extended inbox">
                    <div class="notify-arrow notify-arrow-green"></div>

                    <li>
                        <p class="green">

                            Ci sono <?php echo $num;?> nuovi messaggi</p>
                    </li>

<?php

    function delta_tempo ($data_iniziale, $data_finale, $unita) {
        $data1 = strtotime($data_iniziale);
        $data2 = strtotime($data_finale);

        switch ($unita) {
            case "m": $unita = 1/60; break; 	//MINUTI
            case "h": $unita = 1; break;		//ORE
            case "g": $unita = 24; break;		//GIORNI
            case "a": $unita = 8760; break;     //ANNI
        }

        $differenza = (($data2 - $data1) / 3600) / $unita;
        return $differenza;
    }

    while ($obj_read = $result->fetch_object()) {
        $time = delta_tempo($obj_read->data_creazione, date("Y-m-d H:i:s"), "m");
        $type = "min";
        if ($time >= 1) {
            $time = (int) $time;
        } else {
            $time = $time * 60;
            $type="sec";
        }

        if ($time > 60 && $type == "min"){
            $time = $time / 60;
            $type= "ore";
            $time = (int) $time;
        }
        if ($time > 24 && $type == "ore") {
            $time = $time / 24;
            $type = "giorni";
            $time = (int) $time;
        }
        if ($time > 30 && $type == "giorni") {
            $time = $time / 30;
            $type = "mesi";
            $time = (int) $time;
        }
?>
            <li>
                <a href="dashboard.php?id=<?php echo $obj_read->id_mex; ?>">
                    <span class="photo">
                        <?php

                        if (file_exists("assets/img/avatar/" . $obj_read->id_autore . ".png")) {
                            echo '<img alt="avatar" style="max-width: 80px;" src="assets/img/avatar/' . $obj_read->id_autore . '.png">';
                        } else {
                            echo '<img alt="avatar" style="max-width: 80px;" src="assets/img/avatar/avatar_empty.jpg">';
                        }

                        ?>
                    </span>
                    <span class="subject">
                    <span class="from"><?php echo $obj_read->titolo; ?></span><br>
                    <span class="time"><?php echo $time . " " . $type . " fa"?></span>
                    </span>
                    <span class="message">
                       <?php echo "da ". $obj_read->nome . " " . $obj_read->cognome; ?>
                    </span>
                </a>
            </li>
<?php } ?>
            <li>
                <a href="dashboard.php?view=all_messages">Vedi tutti i messaggi</a>
            </li>
        </ul>
    </li>
    <!-- FINE DROPDOWN MESSAGGI -->
</ul>
<!--  FINE NOTIFICHE -->
</div>
<?php } ?>

            <div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="logout.php" >Logout</a></li>
            	</ul>
            </div>
        </header>
      <!--FINE HEADER-->

      <!-- **********************************************************************************************************************************************************
      SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--INIZIO SIDEBAR-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- INIZIO SIDEBAR MENU-->
              <ul class="sidebar-menu" id="nav-accordion">

              	  <p class="centered">
                      <?php if(!$_SESSION['is_supplier']) {
                          echo '<a href="riepilogo.php?id=' . $_SESSION['user_idd'] .'">';
                      }

                        if (file_exists("assets/img/avatar/" . $_SESSION['user_idd'] . ".png")) {
                            echo '<img src="assets/img/avatar/' . $_SESSION["user_idd"] . '.png" class="img-circle" width="60">';
                        } else {
                            echo '<img src="assets/img/avatar/avatar_empty.jpg" class="img-circle" width="60">';
                        }
                        ?>
                      <?php if(!$_SESSION['is_supplier']) { echo '</a>'; } ?>
                  </p>

              	  <h5 class="centered">
                      <?php if(!$_SESSION['is_supplier']) {
                          echo $_SESSION['name'] . " " . $_SESSION['surname'];
                      } else {
                          echo $_SESSION['supplier_rag_sociale'];
                      } ?>
                  </h5>

<?php
                    $modulo = new Modulo();
                    $permessiArray = $modulo->getModulesByUserId($_SESSION['user_idd']);
?>

                   <li class="mt">
                       <a <?php if($_SERVER['PHP_SELF']=="/new_area_riservata/dashboard.php") { ?> class="active"<?php } ?> href="dashboard.php">
                          <i class="fa fa-home"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>

                  <!--<li class="sub-menu">

                      <a <?php if($_SERVER['PHP_SELF']=="/new_area_riservata/dashboard.php") { ?> class="active"<?php } ?> href="bacheca.php">
                          <i class="fa fa-dashboard"></i>
                          <span>Bacheca</span>
                      </a>
                  </li>-->

<?php   if ($_SESSION['user_idd'] !== 366) { ?>
                  <li class="sub-menu">
                      <a <?php if($_SERVER['PHP_SELF']=="/new_area_riservata/riepilogo.php") { ?> class="active"<?php } ?> href="javascript:;" >
                          <i class="fa fa-user"></i>
                          <span>Profilo</span>
                      </a>
                      <ul class="sub">
                            <li><a href="riepilogo.php?id=<?php echo $_SESSION['user_idd'];?>">Riepilogo</a></li>
                            <li><a href="anagrafica.php?id=<?php echo $_SESSION['user_idd'];?>">Anagrafica</a></li>
                            <li><a href="questionario.php?id=<?php echo $_SESSION['user_idd'];?>">Questionario</a></li>
                            <li><a href="istruzione.php?id=<?php echo $_SESSION['user_idd'];?>">Istruzione</a></li>
                            <li><a href="esperienza.php?id=<?php echo $_SESSION['user_idd'];?>">Esperienza</a></li>
                            <li><a href="certificazioni.php?id=<?php echo $_SESSION['user_idd'];?>">Certificazioni</a></li>
                            <li><a href="corsi.php?id=<?php echo $_SESSION['user_idd'];?>">Corsi</a></li>
                            <li><a href="lingue_straniere.php?id=<?php echo $_SESSION['user_idd'];?>">Lingue Straniere</a></li>
                            <li><a href="riepilogo_competenze.php?id=<?php echo $_SESSION['user_idd'];?>">Competenze</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a <?php if($_SERVER['PHP_SELF']=="/new_area_riservata/timesheet.php") { ?> class="active"<?php } ?> href="timesheet.php?id=<?php echo $_SESSION['user_idd'];?>" >
                          <i class="fa fa-calendar"></i>
                          <span>Timesheet</span>
                      </a>
                  </li>
<?php       }
            /*if(in_array(1, $permessiArray)) { ?>
                  <li class="sub-menu">
                      <a <?php if($_SERVER['PHP_SELF']=="/new_area_riservata/amministrazione.php") { ?> class="active"<?php } ?> href="amministrazione.php?action=new" >
                          <i class="fa fa-lock"></i>
                          <span>Amministrazione</span>
                      </a>
                      <ul class="sub">
                            <li><a href="amministrazione.php?action=new">Nuovo Utente</a></li>
                            <li><a href="amministrazione.php?action=permessi">Permessi</a></li>
                      </ul>
                  </li>
<?php   }*/ if(in_array(2, $permessiArray)) {?>
                  <li class="sub-menu">
                      <a <?php if($_SERVER['PHP_SELF']=="/new_area_riservata/responsabile.php") { ?> class="active"<?php } ?> href="responsabile.php?action=convalida" >
                          <i class="fa fa-rocket"></i>
                          <span>Responsabile</span>
                      </a>
                      <ul class="sub">
                            <li><a href="responsabile.php?action=convalida">Convalida Attività</a></li>
                            <li><a href="responsabile.php?action=timesheet">Timesheet</a></li>
                            <li><a href="responsabile.php?action=approva-ferie">Approva Ferie</a></li>
                            <li><a href="responsabile.php?action=abilita">Abilita Utente</a></li>
                      </ul>
                  </li>
<?php   } if(in_array(3, $permessiArray)) {?>
                  <li class="sub-menu">
                      <a <?php if(substr($_SERVER['PHP_SELF'], 0, 22) == "/new_area_riservata/hr") { ?> class="active"<?php } ?> href="hr-gestione-personale.php?action=dipendenti" >
                          <i class="fa fa-address-card"></i>
                          <span>HR</span>
                      </a>
                      <ul class="sub">
                          <!--<li><a href="hr-gestione-personale.php?action=new">Nuovo Utente</a></li>-->
                          <li><a href="hr-gestione-personale.php?action=dipendenti">Gestione Personale</a></li>
                          <!--<li><a href="hr-costi-lavoro.php?action=costi">Costi del Lavoro</a></li>-->
                          <li><a href="hr-recruitment.php?action=richieste">Recruitment</a></li>
                          <li><a href="hr-ricerca-statistiche.php?action=ricerca-cv">Ricerca & Statistiche</a></li>
                      </ul>
                  </li>

<?php } if(in_array(5, $permessiArray)) { ?>
                  <li class="sub-menu">
                      <a <?php if($_SERVER['PHP_SELF']=="/new_area_riservata/costi_aziendali.php") { ?> class="active"<?php } ?> href="costi_aziendali.php?action=costi_aziendali" >
                          <i class="fa fa-eur"></i>
                          <span>Costi Aziendali</span>
                      </a>
                  </li>

<?php } ?>

              </ul>
              <!-- FINE SIDEBAR MENU-->
          </div>
      </aside>
      <!--FINE SIDEBAR-->
