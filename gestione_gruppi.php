<?php
$num=0;
$num1=0;
$alert="";
if(isset($_POST['new_group'])){
   
    $query_new_group="INSERT INTO gruppi (gruppo) VALUES('".$_POST['nome_gruppo']."')";
    $result_query_new_group= mysql_query($query_new_group,$conn->db);
    $ultimo_id = mysql_insert_id();
    if(!$result_query_new_group){
        die('Errore Inserimento Dati: '. mysql_error());
    } else
    {
        $query_gruppi_utente = "SELECT user_id,gruppi_utente FROM anagrafica";
        $result_query_gruppi_utente =  mysql_query($query_gruppi_utente,$conn->db);
        
        while($row_gruppi_utente=  mysql_fetch_array($result_query_gruppi_utente))
        {
                    $gruppi_utente = $row_gruppi_utente['gruppi_utente'];
                    $replace = ',{"gruppo":"'.$ultimo_id.'","profilo":"0"}]';
                    $gruppi_utente = str_replace("]", $replace, $gruppi_utente);
                    $query_update_users_group = "UPDATE anagrafica SET gruppi_utente = '".$gruppi_utente."' WHERE user_id=".$row_gruppi_utente['user_id'];
                    $result_query_update_users_group = mysql_query($query_update_users_group,$conn->db);
                    if(!$result_query_update_users_group){
                        die("ERRORE INSERIMENTO DATI: ".mysql_error());
                    } else {
                         $alert= "<p align='center' style='color:green'>Il gruppo è stato creato con successo.</p>";
                    }
        }
       
       
    }
    
}

if(isset($_POST['aggiungi_utente'])){


    $query_time = "select * FROM anagrafica WHERE user_id = ".$_POST['id_utente'];
                        $result_time = mysql_query($query_time, $conn->db);
        
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {
    
    while ($row = mysql_fetch_array($result_time)) {
        $gruppi=$row['gruppi_utente'];
        $gruppi_app=$row['gruppi_app'];
    }
    
    $ar = json_decode($gruppi);
for($i=0;$i<count($ar);$i++){
    
    if($ar[$i]->gruppo==$_GET['gruppo']) $ar[$i]->profilo="4";
    
}

    $groups_encode =  json_encode($ar);
   $gruppi_app .= ",".$_GET['gruppo'];

    $query_add_utente="UPDATE anagrafica SET gruppi_utente = '".$groups_encode."',gruppi_app='".$gruppi_app."' WHERE user_id = ".$_POST['id_utente'];
    $result_query_add_utente = mysql_query($query_add_utente, $conn->db);

    if (!$result_query_add_utente) {
    die('Errore di inserimento dati: ' . mysql_error());
    }

}
}

if(isset($_POST['rimuovi_utente'])){

    $query_time = "select * FROM anagrafica WHERE user_id = ".$_POST['id_utente'];
                        $result_time = mysql_query($query_time, $conn->db);
        
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {
    
    while ($row = mysql_fetch_array($result_time)) {
        $gruppi=$row['gruppi_utente'];
        $gruppi_app=$row['gruppi_app'];
    }
    
    $ar = json_decode($gruppi);
    for($i=0;$i<count($ar);$i++){
    
    if($ar[$i]->gruppo==$_GET['gruppo']) $ar[$i]->profilo="0";
    echo $ar[$i]->gruppo;
}


    $groups_encode =  json_encode($ar);
   $gruppi_app = str_replace(",".$_GET['gruppo'],'',$gruppi_app);
   $gruppi_app = str_replace($_GET['gruppo'],'',$gruppi_app);

    $query_add_utente="UPDATE anagrafica SET gruppi_utente = '".$groups_encode."',gruppi_app='".$gruppi_app."' WHERE user_id = ".$_POST['id_utente'];
    $result_query_add_utente = mysql_query($query_add_utente, $conn->db);

    if (!$result_query_add_utente) {
    die('Errore di inserimento dati: ' . mysql_error());
    }

}
}


if(!isset($_GET['gruppo'])){ $gruppo = "Service Tech";
                            $_GET['gruppo']=1;
}
else {
    $query="select gruppo from gruppi where id_gruppo=".$_GET['gruppo'];
    $ris_query=mysql_query($query, $conn->db);
    if(!$ris_query){
        die('Errore DB: ' . mysql_error());
} else {
    while ($row = mysql_fetch_array($ris_query)){
        $gruppo=$row['gruppo'];
    }
    }
}
?>
   
<form method="post" action="">        
   <div class="container" style="margin-top:4%;"></div>
      <center>
         <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-3">
          <input id="nome" class="shadows" name="nome" placeholder="nome" type="nome">
          <input id="cognome" name="cognome" placeholder="cognome" type="cognome">
            </div>
       <div class="col-md-1">
           <input type="image" title="Cerca" value="Cerca" src="img/cerca.png" name="cerca" style="width: 40px; margin-left: -10px">
       </div>
          <div class="col-md-1">
              <input type="submit" title="Visualizza tutti" value="Visualizza tutti" class="btn btn-success btn-md" name="visualizza_dip">
          </div>
<div class="col-md-2"></div>
            <div class="col-md-1"></div>
         </div>
      </center>
    <?php if($alert!="") echo '<div class="row"><div class="col-md-12">'.$alert.'</div></div>'; ?>
    <center>
         <div class="section" style="margin-top:3%;">
            <div class="container">
               <div class="row">
                  <div class="col-md-4">
                     <a href="amministrazione.php" style="margin-left: 0px;"><img style="cursor: pointer;" height="25px" src="img/back.png"></a>
                  </div>
                  <div class="col-md-4">
                     <span style="font-size:1em;font-weight:100; font-family:Play; margin-top:12%; margin-left:2%;">Gruppo:</span>
                      <select name="gruppi_change" onchange="newhref(this.value)">
            <?php $query_gruppi ="select * from gruppi";
                  $result_query_gruppi = mysql_query($query_gruppi,$conn->db);
                  if (!$result_query_gruppi) {
    die('Errore DB: ' . mysql_error());
} else {
    while ($row_gruppi = mysql_fetch_array($result_query_gruppi)){
            
     ?>
                                
    <option <?php if($_GET['gruppo']==$row_gruppi['id_gruppo']){?> selected="selected"<?php } ?> value="<?php echo $row_gruppi['id_gruppo']?>"><?php echo $row_gruppi['gruppo']?></option>
                                <?php
    }
}
                  ?>
                            </select>
                  </div>
                  <div class="col-md-4">
                     <a class="btn btn-success btn-md" onclick="javascript:comparsa_messaggio('nuovo_gruppo')"> Nuovo Gruppo </a>
                  </div>
               </div>
            </div>
         </div>
      </center>
      <!-- Fine Tasti e selezione -->
</form>
<center>
 <div id="messaggio" style="display: none;">
        <form action="" method="post">
            <table align="center" class="noborder" width="80%" height="50px"padding="px" style="margin-top:20px;">
                <tr><td style="font-size:16px; width:30%;font-family:play; font-weight:bold;text-align: left;">Nome Gruppo:</td>
                <td style="text-align: left; width: 20%"><input type="text" name="nome_gruppo" value="<?php if(isset($_POST['nome_gruppo'])) echo $_POST['nome_gruppo']?>"></td>
                <td style="text-align: left;"><button type="submit" name="new_group" style="background-color: Transparent;
    background-repeat:no-repeat;
    border: none;
    cursor:pointer;
    overflow: hidden;
    outline:none; margin-top: -8px;"><img src="img/crea.png" width="60px"></button></td></tr>
        </table>
        </form>
 </div>
</center>
            <?php
         if(!isset($_POST['nome'])&&!isset($_POST['cognome'])||isset($_POST['visualizza_dip'])&&$_POST['visualizza_dip']=="Visualizza Tutti")
                        
        { 
             
                        $query_time = "select user_id,nome, img_name,cognome, luogo_nascita,data_nascita FROM anagrafica WHERE gruppi_app NOT LIKE '%".$_GET['gruppo']."%' ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
        } if(isset($_POST['nome'])&&$_POST['cognome']=="")
        {
                         $query_time = "select user_id,nome,img_name, cognome, luogo_nascita,data_nascita FROM anagrafica WHERE gruppi_app NOT LIKE '%".$_GET['gruppo']."%' AND nome LIKE '%".$_POST['nome']."%' ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
  
        }
        if(isset($_POST['cognome'])&&$_POST['nome']=="")
        {
                       
                         $query_time = "select user_id,nome,img_name, cognome, luogo_nascita,data_nascita FROM anagrafica WHERE gruppi_app NOT LIKE '%".$_GET['gruppo']."%' AND cognome LIKE '%".$_POST['cognome']."%' ORDER BY user_id DESC";
                         $result_time = mysql_query($query_time, $conn->db);
  
        }
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {?>
    <div class="section">
         <div class="container">
            <div class="row" style="margin-top:0%;padding-top:1%; padding-bottom:1%;">
               <!-- Tabella sinistra --> 
               <div class="col-md-6">
                  <div style="background-color: #333;">
                     <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%;">Lista Dipendenti</span>
                  </div>
                  <table class="table">
                     <thead>
                        <tr style="background-color:#333;"></tr>
                        <tr>
                           <th></th>
                        </tr>
                        <tr style="background-color:#999;">
                           <th style="width:15%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Num.</span>
                           </th>
                           <th style="width:35%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Nome e Cognome</span>
                           </th>
                           <th style="width:25%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Data di Nascita</span>
                           </th>
                           <th style="width:25%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Aggiungi</span>
                           </th>
                        </tr>
                     </thead>
                  </table>
                       
                        <?php 
                          while ($row = mysql_fetch_array($result_time)) {
if($row['user_id']!=1 && $row['user_id']!=3 ) {
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
               <form method="post" action=""> 
                   <table class="table">
    <tr>
                           <td><?php echo $num; ?></td>
       <!-- <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;height: 50px; line-height: 50px;"><?php if($img_name==0){?><img src="img/dipendente.png"><?php } else {?><img src="img/CV/<?php echo $img_name;?>"><?php }?></td>-->
        <td>
            <span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="amministrazione.php?id_utente=<?php echo $user_id?>"><?php echo $cognome . " " .$nome ?></span></a></td>
        <td>
            <span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="amministrazione.php?id_utente=<?php echo $user_id?>"><?php echo $data[2]."/".$data[1]."/".$data[0]; ?></span></a></td>
        
        <td><button name="aggiungi_utente" type="submit"><img src="img/plus.png" width="20px"></button><input type="hidden" name="id_utente" value="<?php echo $row['user_id']?>"></td>
    </tr></table></form>
<?php
}
}?>
        </tbody></table></form>
        <?php
}
                ?>
   </div>
    <div class="col-md-6">
                  <div style="background-color: #333;">
                      <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%;"><?php echo $gruppo;?></span></div>
    <table class="table">
                     <thead>
                        <tr style="background-color:#333;"></tr>
                        <tr>
                           <th></th>
                        </tr>
                        <tr style="background-color:#999;">
                           <th style="width:15%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Num.</span>
                           </th>
                           <th style="width:35%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Nome e Cognome</span>
                           </th>
                           <th style="width:25%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Data di Nascita</span>
                           </th>
                           <th style="width:25%;">
                              <span style="font-size:1em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Rimuovi</span>
                           </th>
                        </tr>
                     </thead>
    </table>
 <?php 
                          $query_time = "select user_id,nome, img_name,cognome, luogo_nascita,data_nascita FROM anagrafica WHERE gruppi_app LIKE '%".$_GET["gruppo"]."%' ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
                          while ($row = mysql_fetch_array($result_time)) {
if($row['user_id']!=1 && $row['user_id']!=3 ) {
        $user_id = $row['user_id'];
        $nome = $row['nome'];
        $cognome = $row['cognome'];
        $data_nascita = $row['data_nascita'];
        $luogo_nascita = $row['luogo_nascita'];
        $data=explode("-",$data_nascita);
        $num1++;
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
        <form action="" method="post">
   <table class="table">
    <tr>
        <td><?php echo $num1; ?></td>
       <td>
           <span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="amministrazione.php?id_utente=<?php echo $user_id?>"><?php echo $cognome . " " .$nome ?></span></a></td>
        <td>
            <span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="amministrazione.php?id_utente=<?php echo $user_id?>"><?php echo $data[2]."/".$data[1]."/".$data[0]; ?></span></a></td>
         
        <td><button name="rimuovi_utente" type="submit" ><img src="img/minus.png" width="20px"></button><input type="hidden" name="id_utente" value="<?php echo $row['user_id']?>"></td>
        </th></tr></table></form>
<?php
}
}
                ?>
               </tbody>
    </table>
    </div>
                  </div>


