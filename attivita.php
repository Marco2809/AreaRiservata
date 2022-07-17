<?php 

if(isset($_POST['commessa'])&&$_POST['commessa']!="") {
    $commessa_search=$_POST['commessa'];
} else $commessa_search="Nessuna";

if(!isset($_SESSION['responsabile'])||$_SESSION['responsabile']==0){
    echo 'Non dispone dei provilegi necessari per visualizzare questa pagina.';
    
} else { ?>
<div id="contenitore1" class="fluid ">
 <table align="center" width="100%" height="auto" padding="0" border="1" class="cal" style="margin-top:0px;margin-left:px;">
    <tr>
  <col class="weekday" span="5">
  <col class="weekend">
  <col class="weekend">
  <thead>
      <tr><td colspan="8" style="color:#fff; font-size: 35px; height: 100%; background-color: #960a19;">
              <center>ATTIVITA' DA VALIDARE</center></td></tr></table><br>
              <?php 
              
              if($commessa_search!="Nessuna") {
                  $query_attivita="SELECT cliente, nome_commessa from commesse WHERE nome_commessa= ".$commessa_search." AND id_responsabile = ".$_SESSION['user_idd'];
              $result_attivita = mysql_query($query_attivita, $conn->db); }
              else {
                  $query_attivita="SELECT cliente, nome_commessa from commesse WHERE id_responsabile = ".$_SESSION['user_idd'];
              $result_attivita = mysql_query($query_attivita, $conn->db);
              }
              
              while ($row_com = mysql_fetch_array($result_attivita, MYSQL_ASSOC)){
                  $commessa= $row_com['nome_commessa'];
     
             
              $query_attivita="SELECT * from attivita,anagrafica WHERE anagrafica.user_id = attivita.id_utente AND attivita.stato='Non Validato' AND attivita.commessa='".$commessa."' ORDER BY giorno DESC, mese DESC, anno DESC";
              $result_attivita = mysql_query($query_attivita, $conn->db);
              echo mysql_error();
              $control_attivita=1;
                while ($row_attivita = mysql_fetch_array($result_attivita, MYSQL_ASSOC)) {?>
     <form action="" method="post">
             <table class="table table-bordered">
                   <?php  if($control_attivita==1){
                        ?>

<thead>
      <tr>
         <th><center><span style="color:#8ec25a;">Nome Cognome</span></center></th>
      <th><center><span style="color:#8ec25a;">Data</span></center></th>
      <th><center><span style="color:#8ec25a;">Ora Inizio</span></center></th>
      <th><center><span style="color:#8ec25a;">Ora Fine</span></center></th>
      <th><center><span style="color:#8ec25a;">Durata</span></center></th>
      <th><center><span style="color:#8ec25a;">Descrizione</span></center></th>
      <th><center><span style="color:#8ec25a;">Commessa</span></center></th>
      <th><center><span style="color:#8ec25a;">Tipo</span></center></th>
      <th><center><span style="color:#8ec25a;">Convalida</span></center></th>
      <th><center><span style="color:#8ec25a;">Rifiuta</span></center></th>
      </tr>
    </thead>

          <?php
                    }
                    $control_attivita++;
      
                    ?>
     <tr>
         <td align="center" style="background-color: #fff; width: 120px; line-height: 15px"><?php echo $row_attivita['nome'].' '.$row_attivita['cognome']?></td>
        
         <td align="center" style="background-color: #fff;"><?php echo $row_attivita['giorno'].'/'.$row_attivita['mese'].'/'.$row_attivita['anno']?></td>
         
         <td align="center" style="background-color: #fff; width: 100px;padding-left: 0;padding-right: 0; margin:0;">
                    <?php echo substr($row_attivita['ora_inizio'],0,2) .":".substr($row_attivita['ora_inizio'],3,2);?>

        <td align="center" style="background-color: #fff;width: 100px;padding-left: 0;padding-right: 0; margin:0;">
             <?php echo substr($row_attivita['ora_fine'],0,2) .":".substr($row_attivita['ora_fine'],3,2);?></td>
            <td align="center" style="background-color: #fff;"><?php echo $row_attivita['ore_lavorate'];?></td>
            <td align="center" style="background-color: #fff;"><?php echo $row_attivita['descrizione'];?>
                    </td>
                            <td align="center" style="background-color: #fff;">
                                   <?php echo $row_attivita['commessa']?></td>
        <td align="center" style="background-color: #fff;"><?php echo $row_attivita['tipo']?></td>
        <td align="center" style="background-color: #fff;padding: 0;padding-top: 10px;">
            <input type="image" name="convalida" style="width: 85px;padding: 0; margin:0;" src="img/convalida.png"
      onmouseover="this.src='img/convalida2.png';"
      onmouseout="this.src='img/convalida.png';"/>
        </td>
           <td align="center" style="background-color: #fff;padding: 0;padding-top: 10px;">
               <img class="rifiuta" id="<?php echo $row_attivita['id_attivita']?>" name="prova" style="width: 85px; padding: 0; margin:0;" src="img/rifiuta.png"
      onmouseover="this.src='img/rifiuta2.png';"
      onmouseout="this.src='img/rifiuta.png';"/>
            <input type="hidden" name="id_attivita" value="<?php echo $row_attivita['id_attivita']?>">
           </td>
     </tr><tr id="motivo<?php echo $row_attivita['id_attivita']?>" style="display:none;" ><td colspan="10">Motivo: <br><textarea name="motivo_rifiuto" cols="2" rows="4" style="width: 45%;"></textarea><br><input style="background-color: #960a19;color: #fff;" type="submit" name="ok_rifiuto" value="Rifiuta"></td></tr></table></form><?php
}
}
}
              ?>
    <script src="jquery-3.0.0.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
           $(".rifiuta*").click(function() {
                       var id_attivita = $(this).attr("id"); 
                       if($('#motivo'+id_attivita).css("display")=="none") {
                          
                       $('#motivo'+id_attivita).css("display","");
                   } else {$('#motivo'+id_attivita).css("display","none");
         
        }
                      
           });
        });
                </script>