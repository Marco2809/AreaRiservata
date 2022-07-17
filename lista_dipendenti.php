<?php 
$num=0;
?>
   
<form method="post" action="">        
   <!-- Barra di ricerca -->
   <div class="container" style="margin-top:4%;"></div>
      <center>
         <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-3">
          <input id="nome" class="shadows" name="nome" placeholder="nome" type="nome">
          <input id="cognome" name="cognome" placeholder="cognome" type="cognome">
     </div>
      <div class="col-md-1"><input type="image" title="Cerca" value="Cerca" src="img/cerca.png" name="cerca" style="width: 40px; margin-left: -10px">
      </div>
      <div class="col-md-1">
          <input type="submit" title="Visualizza tutti" value="Visualizza tutti" class="btn btn-success btn-sm" name="visualizza_dip" >
      </div>
         </div>
      </center>
</form>
 <!-- Fine Barra di ricerca -->
      <!-- Barra pulsanti -->
      <div class="section">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <center></center>
               </div>
            </div>
         </div>
      </div>
      <center>
         <div class="container">
            <div class="row">
               <div class="col-md-2" style="margin-top:1%;">
        <a href="amministrazione.php?id_utente=new" class="btn btn-success btn-md">Nuovo Utente</a>
               </div>
            <div class="col-md-3" style="margin-top:1%;">
                <a href="amministrazione.php?id_gruppo=adm&gruppo=1"class="btn btn-success btn-md">Aree di Competenza</a>
            </div>    
                <div class="col-md-2" style="margin-top:1%;">
                    <a href="amministrazione.php?id=ric" class="btn btn-success btn-md">Ricerca CV</a>
                             </div>
               <div class="col-md-2" style="margin-top:1%;">
                   <a href="amministrazione.php?id=timesheet" class="btn btn-success btn-md">Timesheet</a>
                                     </div>
               <div class="col-md-2" style="margin-top:1%;">
                   <a href="amministrazione.php?id=commesse" class="btn btn-success btn-md">Commesse</a>
               </div>
            </div>
         </div>
      </center>

            <?php
         if(!isset($_POST['nome'])&&!isset($_POST['cognome'])||isset($_POST['visualizza_dip'])&&$_POST['visualizza_dip']=="Visualizza Tutti")
                        
        { 
             
                        $query_time = "select * FROM anagrafica WHERE";
                        for($r=0;$r<count($gruppi_admin);$r++){
                            
                            if($r==0) $query_time .= " (gruppi_app LIKE '%".$gruppi_admin[$r]."%'";
                            else $query_time .= " OR gruppi_app LIKE '%".$gruppi_admin[$r]."%'";
                              
                        }
                        $query_time .= ")"; 
                        $query_time .= " ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
        } if(isset($_POST['nome'])&&$_POST['cognome']=="")
        {
            
              $query_time = "select * FROM anagrafica WHERE";
                        for($r1=0;$r1<count($gruppi_admin);$r1++){
                            
                            if($r1==0) $query_time .= " (gruppi_app LIKE '%".$gruppi_admin[$r1]."%'";
                            else $query_time .= " OR gruppi_app LIKE '%".$gruppi_admin[$r1]."%'";
                            
                        }
                        $query_time .= ")"; 
                        $query_time .= " AND nome LIKE '%".$_POST['nome']."%' ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
        }
        if(isset($_POST['cognome'])&&$_POST['nome']=="")
        {
                       
                            $query_time = "select * FROM anagrafica WHERE";
                        for($r1=0;$r1<count($gruppi_admin);$r1++){
                            
                            if($r1==0) $query_time .= " (gruppi_app LIKE '%".$gruppi_admin[$r1]."%'";
                            else $query_time .= " OR gruppi_app LIKE '%".$gruppi_admin[$r1]."%'";
                            
                        }
                        $query_time .= ")"; 
                        $query_time .= " AND cognome LIKE '%".$_POST['cognome']."%' ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
        }
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {?>
    <!-- Fine Barra pulsanti -->
      <!--Intestazione -->
      <div class="section">
         <div class="container">
            <div class="row" style="max-width:98%;margin-left:1%;margin-top:3%;background-color:#333;padding-top:1%; padding-bottom:1%;">
               <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Lista Dipendenti</span>
            </div>
         </div>
      </div>
      <!-- Fine Intestazione -->
      <!-- Lista Dipendenti -->
      <div class="section" style=" min-width:30%;">
         <div class="container">
            <div class="row" style="margin-top:-8%;">
               <table class="table border">
                  <thead>
                     <tr style="background-color:#333;"></tr>
                     <tr>
                        <th></th>
                     </tr>
                     <tr style="background-color:#999;">
                        <th>
                           <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Num.</span>
                        </th>
                        <th>
                           <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Nome e Cognome</span>
                        </th>
                        <th>
                           <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Data di Nascita</span>
                        </th>
                        <th>
                           <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">CV</span>
                        </th>
                     </tr>
                  </thead>
                 <tbody>
                          <?php 
                          while ($row = mysql_fetch_array($result_time)) {
if($row['user_id']!=1 && $row['user_id']!=2 ) {
        $user_id = $row['user_id'];
        $nome = $row['nome'];
        $cognome = $row['cognome'];
        $data_nascita = $row['data_nascita'];
        $luogo_nascita = $row['luogo_nascita'];
        $data=explode("-",$data_nascita);
        $num++;
        $img_name = $row['img_name'];
        
        $query_time1 = "select * FROM esperienza WHERE user_id =".$user_id." ORDER BY id_esp DESC LIMIT 1";
                         $result_time1 = mysql_query($query_time1, $conn->db);
                  
       
if (!$result_time1) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {
    $row1 = mysql_fetch_array($result_time1);
        $area = $row1['area'];
}


        ?>
   
    <tr>
                        <td><?php echo $num; ?></td>
                        <td><span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="amministrazione.php?id_utente=<?php echo $user_id?>"><?php echo $cognome . " " .$nome ?></a></span></td>
                        <td>  <span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="amministrazione.php?id_utente=<?php echo $user_id?>"><?php echo $data[2]."/".$data[1]."/".$data[0]; ?></a></span></td>
    <td><a target="blank" href="http://service-tech.org/servicetech/area_riservata/tcpdf/examples/PDF_create.php?id=<?php echo $user_id?>"><img width="30px" src="img/pdf2.png"></a></td>
        </tr>
<?php
}
}
}
                ?>
               </tbody>
               </table>
                  </div>
         </div>
      </div>
      <!-- Fine Lista Dipendenti -->
