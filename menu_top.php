<?php


if(isset($_SESSION['user_idd'])) {

    $sql3_anagrafica = "SELECT
                           *
                            FROM anagrafica WHERE user_id=" . $_SESSION['user_idd'];


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);

    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row3 = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $gruppi_utente  = $row3['gruppi_utente'];

        }
    }
    $_SESSION['gruppo_admin']="";
    $_SESSION['gruppo_writer'] = "";
    $_SESSION['gruppo_resp'] = "";
    $ar_groups = json_decode($gruppi_utente);
// access first element of $ar array
    for($i=0;$i<count($ar_groups);$i++){

                 //echo $array_gruppi[$n-1]->profilo. "<br>";
                 if($ar_groups[$i]->profilo=="1") {$_SESSION['is_admin']="1";
                 $_SESSION['gruppo_admin'].=$ar_groups[$i]->gruppo.",";
                 }

                  if($ar_groups[$i]->profilo=="2") {$_SESSION['is_resp']="1";
                 $_SESSION['gruppo_resp'].=$ar_groups[$i]->gruppo.",";
                 }

                  if($ar_groups[$i]->profilo=="3") {$_SESSION['is_writer']="1";
                  $_SESSION['gruppo_writer'].=$ar_groups[$i]->gruppo.",";
                 }
             }
}
$_SESSION['gruppo_admin'] = substr($_SESSION['gruppo_admin'],0,-1);
$_SESSION['gruppo_resp'] = substr($_SESSION['gruppo_resp'],0,-1);
$_SESSION['gruppo_writer'] = substr($_SESSION['gruppo_writer'],0,-1);

?>
      <!-- Menu principale -->
      <div class="bs-example" style="margin-top:-3.5%;">
         <nav role="navigation" class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header"style="background-color:#333;padding-top:1%;padding-bottom:1%;">
               <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               </button>
               <a href="http://www.service-tech.org/servicetech/" class="navbar-brand">
               <img src="img/logo1.png" style="width:95%;margin-top:-3%;">
               </a>
            </div>
            <!-- Collection of nav links and other content for toggling -->
            <div id="navbarCollapse" class="collapse navbar-collapse" style="background-color:#333;">
               <ul class="nav navbar-nav navbar-right" style="background-color:#333;padding-bottom:1%;padding-top:1%;">
                  <li><a href="logout.php">
                     <img src="img/logout.png" style="margin-top:-8%;">
                     </a>
                  </li>
               </ul>
               <ul class="nav navbar-nav">
                  <li><a href="http://www.service-tech.org/servicetech/">
                     <img src="img/home.png" style="margin-top:12%;" onmouseover="this.src='img/home2.png';" onmouseout="this.src='img/home.png';">
                     </a>
                  </li>
                  <li class="dropdown">
                     <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                     <img src="img/servizi.png" style="margin-top:12%;" onmouseover="this.src='img/servizi2.png';" onmouseout="this.src='img/servizi.png';" style="margin-top:-2%;">
                     <b class="caret" style="color:#fff;margin-top:12%;"></b></a>
                     <ul role="menu" class="dropdown-menu">
                        <li>
                           <a href="http://service-tech.org/servicetech/index.php/servizi/software-it-solutions">Software &amp; IT Solutions</a>
                           </a>
                        </li>
                        <li>
                           <a href="http://service-tech.org/servicetech/index.php/servizi/system-network-management">System &amp; Network Management</a>
                           </a>
                        </li>
                        <li>
                           <a href="http://service-tech.org/servicetech/index.php/servizi/sw-hw-solutions">Soluzioni Software &amp; Hardware</a>
                           </a>
                        </li>
                        <li>
                           <a href="http://service-tech.org/servicetech/index.php/servizi/cablaggio">Cabling</a>
                           </a>
                        </li>
                        <li>
                           <a href="http://service-tech.org/servicetech/index.php/servizi/servicedesk">Service Desk</a>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li><a href="http://www.service-tech.org/servicetech/index.php/lavora-con-noi">
                     <img src="img/lavora-con-noi.png" style="margin-top:8%;" onmouseover="this.src='img/lavora-con-noi2.png';" onmouseout="this.src='img/lavora-con-noi.png';">
                     </a>
                  </li>
                  <li><a href="http://www.service-tech.org/servicetech/index.php/contatti">
                     <img src="img/contatti.menu.png" style="margin-top:11%;" onmouseover="this.src='img/contatti.menu2.png';" onmouseout="this.src='img/contatti.menu.png';" style="margin-top:-2%;">
                     </a>
                  </li>
                  <li><a href="http://www.service-tech.org/servicetech/index.php/download">
                     <img src="img/download.png"  style="margin-top:8%;" onmouseover="this.src='img/download2.png';" onmouseout="this.src='img/download.png';" style="margin-top:-2.5%;">
                     </a>
                  </li>
                  <li><a href="#">
                     <img src="img/area.png" width="180" height="60" style="margin-top:-6%; padding-right:5px;">
                     </a>
                  </li>
               </ul>
            </div>
         </nav>
      </div>

<!-- SOTTOMENU -->
<center>
         <div class="section" style="padding-bottom: 1%; margin-top: 1%; background-color: rgb(51, 51, 51); width:100%;">
            <div class="container">
               <div class="row" style="margin-top:0.3%;padding-left:5%;width:100%;">
                  <div class="col-md-2">
                      <a href="./dashboard.php"><img src="img/bacheca2.png" onmouseover="this.src='img/bacheca.png';" onmouseout="this.src='img/bacheca2.png';"></a>
                  </div>
                  <div class="col-md-2">
                      <a href="./riepilogo.php"><img src="img/profilo2.png" onmouseover="this.src='img/profilo.png';" onmouseout="this.src='img/profilo2.png';"></a>
                  </div>
                  <div class="col-md-2">
                      <a href="./calendario.php"><img src="img/timesheet2.png" onmouseover="this.src='img/timesheet.png';" onmouseout="this.src='img/timesheet2.png';"></a>
                  </div>
                   <?php if(isset($_SESSION['is_admin'])&&$_SESSION['is_admin']==1) {?>
                  <div class="col-md-2">
                      <a href="./amministrazione.php"><img src="img/amministrazione2.png" onmouseover="this.src='img/amministrazione.png';" onmouseout="this.src='img/amministrazione2.png';"></a>
                  </div>
                   <?php } ?>
                   <?php if(isset($_SESSION['responsabile'])&&$_SESSION['responsabile']==1) {?>
                  <div class="col-md-2">
                      <a href="./responsabile.php"><img src="img/responsabile.png" onmouseover="this.src='img/responsabile2.png';" onmouseout="this.src='img/responsabile.png';"></a>
                  </div>
                    <?php } ?>
               </div>
            </div>
         </div>
      </center>
      <!--Fine Menu secondario -->
