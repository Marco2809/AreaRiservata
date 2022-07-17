<?php 
$contr_el=0;
if(isset($_POST['elimina_dip'])){
        
    $sql_anag_delete="DELETE from anagrafica WHERE user_id=".$_GET['id_utente'];
   $result_sql_anag_delete = mysql_query($sql_anag_delete, $conn->db);
   
     $sql_anag_delete1="DELETE from certificazione WHERE user_id=".$_GET['id_utente'];
   $result_sql_anag_delete1 = mysql_query($sql_anag_delete1, $conn->db);
   
     $sql_anag_delete2="DELETE from corsi WHERE user_id=".$_GET['id_utente'];
   $result_sql_anag_delete2 = mysql_query($sql_anag_delete2, $conn->db);
   
   $sql_anag_delete3="DELETE from esperienza WHERE user_id=".$_GET['id_utente'];
   $result_sql_anag_delete3 = mysql_query($sql_anag_delete3, $conn->db);
   
   $sql_anag_delete4="DELETE from formazione WHERE user_id=".$_GET['id_utente'];
   $result_sql_anag_delete4 = mysql_query($sql_anag_delete4, $conn->db);
  
   $sql_anag_delete5="DELETE from skill WHERE skill_user_id=".$_GET['id_utente'];
   $result_sql_anag_delete5 = mysql_query($sql_anag_delete5, $conn->db);
   
   $sql_anag_delete6="DELETE from login WHERE user_idd=".$_GET['id_utente'];
   $result_sql_anag_delete6 = mysql_query($sql_anag_delete6, $conn->db);
   
   if (!$sql_anag_delete||!$sql_anag_delete1||!$sql_anag_delete2||!$sql_anag_delete3||!$sql_anag_delete4||!$sql_anag_delete5||!$sql_anag_delete6) {
        die('Errore di inserimento dati: ' . mysql_error());
    } else {
            $alert = "<p align='center' style='color:green'>Profilo eliminato con successo!</p>";
            $contr_el=1;
        }
        
        if(isset($alert)&&$alert!=""){
            echo $alert;
        }
   
}

if(isset($_POST['modifica_dip'])){

    $_SESSION['gruppi']="";
    
    $commesse_app="*0*";
    $query_comm="select * from commesse";
    $query_comm_result=mysql_query($query_comm,$conn->db);
    while ($row_com = mysql_fetch_array($query_comm_result)){
        $row_com['nome_commessa'] = str_replace(" ", "", $row_com['nome_commessa']);
        if($_REQUEST[$row_com['nome_commessa']]=="Appartiene") {
            $commesse_app .= $row_com['id_commessa']."*";
            $queri_resp_ok="SELECT id_responsabile,id_commessa from commesse where id_commessa =".$row_com['id_commessa'];
            $result_query_resp_ok = mysql_query($queri_resp_ok,$conn->db);
            echo mysql_error();
            while ($row_com_resp = mysql_fetch_array($result_query_resp_ok)){
                
            if($row_com_resp['id_responsabile']==$_GET['id_utente']){
                
                $queri_resp_delete="UPDATE commesse SET id_responsabile = '0' where id_commessa =".$row_com_resp['id_commessa'];
            $result_query_resp_delete = mysql_query($queri_resp_delete,$conn->db);   
            echo mysql_error();
        }
            }
        }
        else if($_REQUEST[$row_com['nome_commessa']]=="Responsabile"){
            
            $queri_resp="UPDATE commesse SET id_responsabile =".$_GET['id_utente']." where id_commessa = ".$row_com['id_commessa'];
            $result_query_resp = mysql_query($queri_resp,$conn->db);       
            $commesse_app .= $row_com['id_commessa']."*";
            
        } else {
            
            $queri_resp_ok="SELECT id_responsabile,id_commessa from commesse where id_commessa =".$row_com['id_commessa'];
            $result_query_resp_ok = mysql_query($queri_resp_ok,$conn->db);
            echo mysql_error();
            while ($row_com_resp = mysql_fetch_array($result_query_resp_ok)){
                
            if($row_com_resp['id_responsabile']==$_GET['id_utente']){
                
                $queri_resp_delete="UPDATE commesse SET id_responsabile = '0' where id_commessa =".$row_com_resp['id_commessa'];
            $result_query_resp_delete = mysql_query($queri_resp_delete,$conn->db);   
            echo mysql_error();
            } 
            }
        }
    }
    $gruppi_app="";
    $query_gruppi="select * from gruppi";
    $query_gruppi_result=mysql_query($query_gruppi,$conn->db);
    $con=1;
    $gruppi='[';
    while ($row = mysql_fetch_array($query_gruppi_result)){
        if($row['gruppo']=="SW Team") $row['gruppo']= "SWTeam";
        if(isset($_REQUEST[$row['gruppo']]) && $_REQUEST[$row['gruppo']] != '') {
            $group = $_REQUEST[$row['gruppo']];
          if($group!=0&&$group!=4) $_SESSION['gruppi'].="$con,";
            if($group!=0) $gruppi_app.="$con,";
            $gruppi.= '{"gruppo":"'.$con.'","profilo":"'.$group.'"},';
            $con++;
        } 
    }
    
    $gruppi_app = substr($gruppi_app,0,  strlen($gruppi_app)-1);
    $gruppi = substr($gruppi,0,strlen($gruppi)-1);
    $gruppi.=']';
    
        if(isset($_REQUEST['nome']) && $_REQUEST['nome'] != '') {
            $nome = $_REQUEST['nome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il nome del dipendente!</p>";
        }
        
        if(isset($_REQUEST['cognome']) && $_REQUEST['cognome'] != '') {
            $cognome = $_REQUEST['cognome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il cognome del dipendente!</p>";
        }
        
         if(isset($_REQUEST['data_nascita']) && $_REQUEST['data_nascita'] != '') {
            $data_nascita_a = explode("/", $_REQUEST['data_nascita']);
            $data_nascita= $data_nascita_a[2]."-".$data_nascita_a[1]."-".$data_nascita_a[0];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la data di nascita del dipendente!</p>";
        }
        
         if(isset($_REQUEST['citta_residenza']) && $_REQUEST['citta_residenza'] != '') {
            $citta_residenza = $_REQUEST['citta_residenza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la citta di residenza del dipendente!</p>";
        }
        
          if(isset($_REQUEST['indirizzo_residenza']) && $_REQUEST['indirizzo_residenza'] != '') {
            $indirizzo_residenza = $_REQUEST['indirizzo_residenza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'indirizzo di residenza del dipendente!</p>";
        }
        
           if(isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
            $email = $_REQUEST['email'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'email del dipendente!</p>";
        }
        
          if(isset($_REQUEST['codice_fiscale']) && $_REQUEST['codice_fiscale'] != '') {
            $codice_fiscale = $_REQUEST['codice_fiscale'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il codice fiscale del dipendente!</p>";
        }
        
         if(isset($alert)&&$alert!=""){
            echo $alert;
        } else {
        
    $sql_anag_update="UPDATE anagrafica SET nome = '" . $nome . "', cognome='" .$cognome. "', data_nascita='" .$data_nascita. "',citta_residenza='" .$citta_residenza. "', indirizzo_residenza = '" . $indirizzo_residenza. "', gruppi_utente= '" .$gruppi. "', gruppi_app = '".$gruppi_app."', commesse = '".$commesse_app."', codice_fiscale = '".$codice_fiscale."' WHERE user_id=".$_GET['id_utente'];
   $result_sql_anag_update = mysql_query($sql_anag_update, $conn->db);
   echo mysql_error();
   $sql_login_update="UPDATE login SET email = '" . $email . "' WHERE user_idd=".$_GET['id_utente'];
   $result_sql_login_update = mysql_query($sql_login_update, $conn->db);
   
   if (!$result_sql_anag_update||!$result_sql_login_update) {
        die('Errore di inserimento dati: ' . mysql_error());
    } else {
            $alert = "<p align='center' style='color:green'>Profilo modificato con successo!</p>";
        }
        
        if(isset($alert)&&$alert!=""){
            echo $alert;
        }
   
}
}

if(isset($_POST['nuovo_dip'])){
    $gruppi_app="";
    $query_gruppi="select * from gruppi";
    $query_gruppi_result=mysql_query($query_gruppi,$conn->db);
    $con=1;
    $gruppi='[';
    while ($row = mysql_fetch_array($query_gruppi_result)){
        if($row['gruppo']=="SW Team") $row['gruppo']= "SWTeam";
        if(isset($_REQUEST[$row['gruppo']]) && $_REQUEST[$row['gruppo']] != '') {
            $group = $_REQUEST[$row['gruppo']];
            if($group!=0) $gruppi_app.="$con,";
            $gruppi.= '{"gruppo":"'.$con.'","profilo":"'.$group.'"},';
            $con++;
        } 
    }
    $gruppi_app = substr($gruppi_app,0,-1);
    $gruppi = substr($gruppi,0,-1);
    $gruppi.=']';
    //echo $gruppi_app;
    //echo $gruppi;
 
        if(isset($_REQUEST['nome']) && $_REQUEST['nome'] != '') {
            $nome = $_REQUEST['nome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il nome del dipendente!</p>";
        }
        
        if(isset($_REQUEST['cognome']) && $_REQUEST['cognome'] != '') {
            $cognome = $_REQUEST['cognome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il cognome del dipendente!</p>";
        }
        
         if(isset($_REQUEST['data_nascita']) && $_REQUEST['data_nascita'] != '') {
            $data_nascita_a = explode("/", $_REQUEST['data_nascita']);
            $data_nascita= $data_nascita_a[2]."-".$data_nascita_a[1]."-".$data_nascita_a[0];
        } else {
            //$alert .= "<p align='center' style='color:red'>Attenzione: inserire la data di nascita del dipendente!</p>";
            $data_nascita="";
            
        }
        
         if(isset($_REQUEST['citta_residenza']) && $_REQUEST['citta_residenza'] != '') {
            $citta_residenza = $_REQUEST['citta_residenza'];
        } else {
            //$alert .= "<p align='center' style='color:red'>Attenzione: inserire la citta di residenza del dipendente!</p>";
            $citta_residenza="";
            
        }
        
          if(isset($_REQUEST['indirizzo_residenza']) && $_REQUEST['indirizzo_residenza'] != '') {
            $indirizzo_residenza = $_REQUEST['indirizzo_residenza'];
        } else {
            //$alert .= "<p align='center' style='color:red'>Attenzione: inserire l'indirizzo di residenza del dipendente!</p>";
            $indirizzo_residenza="";
            
        }
        
           if(isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
            $email = $_REQUEST['email'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'email del dipendente!</p>";
        }
        
          if(isset($_REQUEST['codice_fiscale']) && $_REQUEST['codice_fiscale'] != '') {
            $codice_fiscale = $_REQUEST['codice_fiscale'];
        } else {
            //$alert .= "<p align='center' style='color:red'>Attenzione: inserire il codice fiscale del dipendente!</p>";
            $codice_fiscale="";
            
        }
        
         if(isset($alert)&&$alert!=""){
            echo $alert;
        } else {
        
  
   
   $sql_login_update="INSERT INTO login (email,username,password) VALUES ('" . $email . "','" . $email . "','Iniziale1$$')";
   $result_sql_login_update = mysql_query($sql_login_update, $conn->db);
   $ultimo_id= mysql_insert_id();
   if (!$result_sql_login_update) {
        die('Errore di inserimento dati: ' . mysql_error());
    } else {
        
          $sql_anag_insert="INSERT INTO anagrafica (user_id,nome,cognome,data_nascita,citta_residenza,indirizzo_residenza,gruppi_utente,gruppi_app,codice_fiscale) 
        VALUES ($ultimo_id,'" . $nome . "','" .$cognome. "','" .$data_nascita. "','" .$citta_residenza. "','" . $indirizzo_residenza. "','" .$gruppi. "','".$gruppi_app."','".$codice_fiscale."')";
   $result_sql_anag_update = mysql_query($sql_anag_insert, $conn->db);
   
   echo mysql_error();
            $alert = "<p align='center' style='color:green'>Profilo creato con successo!</p>";
        }
        
        if(isset($alert)&&$alert!=""){
            echo $alert;
        }
   
}
}
$num=0;
?>
<div class="ficheaddleft">

            <?php
        if($_GET['id_utente']!="new"){
                        $query_time = "select * FROM anagrafica WHERE user_id =".$_GET['id_utente'];
                        $result_time = mysql_query($query_time, $conn->db);
        
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else { if(mysql_num_rows($result_time)==0) $contr_el=1;?>
   <div class="container" style="margin-top:4%;"></div>
   <center>
      <div class="row">
         <div class="col-md-1"></div>
         <div class="col-md-1"><a href="amministrazione.php"><img style="cursor: pointer;" height="25px" src="img/back.png"></a></div>
      </div>
   </center>
<?php if($alert!="") echo '<div class="row"><div class="col-md-12">'.$alert.'</div></div>'; ?>
   <div class="section">
      <div class="container">
         <div class="row" style="margin-top:3%;background-color:#333;padding-top:1%; padding-bottom:1%; max-width:100%;">
            <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%;">Profilo</span>
         </div>
      </div>
   </div>
              <form action="" method="post">
        
                          <?php 
                          while ($row = mysql_fetch_array($result_time)) {
if($row['user_id']!=1 && $row['user_id']!=2 ) {
        $nome = $row['nome'];
            $cognome = $row['cognome'];
            $luogo_nascita = $row['luogo_nascita'];
            $citta_residenza = $row['citta_residenza'];
            $indirizzo_res = $row['indirizzo_residenza'];
            $data_nascita = $row['data_nascita'];
            $citta_domicilio = $row['citta_domicilio'];
            $indirizzo_dom = $row['indirizzo_domicilio'];
            $codice_fiscale = $row['codice_fiscale'];
            $img_name = $row['img_name'];
            $profilo_utente=$row['profilo_utente'];
            $gruppi=$row['gruppi_utente'];

       $sql4_anagrafica = "SELECT 
							phone, 
							email,
                            is_admin
                                                        FROM login WHERE user_idd=" . $_GET['id_utente'];


    $result4_anagrafica = mysql_query($sql4_anagrafica, $conn->db);

    if (!$result4_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row = mysql_fetch_array($result4_anagrafica, MYSQL_ASSOC)) {
            $telefono = $row['phone'];
            $email = $row['email'];
            $is_admin = $row['is_admin'];
}
}
$data_nascita = @explode('-', $data_nascita);
        ?>
   
    <div class="section">
      <div class="container">
         <div class="row" style="margin-top:-2%;">
            <table class="table" style="max-width:100%;">
               <tbody>
               <thead>
                  <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Nome*</span>
                     </th>
                     <th><input type="text" name="nome" value="<?php echo $nome?>">
                     </th></tr>
   <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Cognome*</span>
                     </th>
                     <th><input type="text" name="cognome" value="<?php echo $cognome?>">
                     </th>
                  </tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Data di nascita (dd/mm/yyyy)</span>
                     </th>
                     <th><input type="text" name="data_nascita" value="<?php echo $data_nascita[2] . "/" . $data_nascita[1] . "/" . $data_nascita[0] ?>">
                     </th>
                  </tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Città di Residenza</span>
                     </th>
                     <th><input type="text" name="citta_residenza" value="<?php echo $citta_residenza?>">
                     </th>
                  </tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Indirizzo di Residenza</span>
                     </th>
                     <th><input type="text" name="indirizzo_residenza" value="<?php echo $indirizzo_res?>">
                     </th>
                  </tr>
 <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Email*</span>
                     </th>
                     <th><input type="text" name="email" value="<?php echo $email?>">
                     </th>
                  </tr>
                 <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Codice Fiscale</span>
                     </th>
                     <th><input type="text" name="codice_fiscale" value="<?php echo $codice_fiscale;?>">
                     </th>
                  </tr>
 <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Gruppi e Profili</span>
                     </th>

<?php
$ar = json_decode($gruppi);
// access first element of $ar array
$query="select gruppo from gruppi";
$i=0;
    $ris_query=mysql_query($query, $conn->db);
    if(!$ris_query){
        die('Errore DB: ' . mysql_error());
} else {
    while ($row = mysql_fetch_array($ris_query)){
        $gruppo=$row['gruppo'];
        $gruppo = str_replace(" ", "", $gruppo);
        
   echo '<tr><th></th><th>'.$gruppo.': </th>';
    /*
    if($i==0) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==1) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==2) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==3) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==4) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
     */
    ?> 
    <th>
    <select name="<?php echo $gruppo;?>">
        <option <?php if($ar[$i]->profilo=="0"){?> selected="selected"<?php } ?> value="0">Non Appartiene</option>
        <!--<option <?php if($ar[$i]->profilo=="1"){?> selected="selected"<?php } ?> value="1">Admin</option>-->
        <option <?php if($ar[$i]->profilo=="2"){?> selected="selected"<?php } ?> value="2">Responsabile</option>
        <option <?php if($ar[$i]->profilo=="3"){?> selected="selected"<?php } ?> value="3">Editore</option>
        <option <?php if($ar[$i]->profilo=="4"){?> selected="selected"<?php } ?> value="4">Utente</option>
        <!--<option <?php if($ar[$i]->profilo=="5"){?> selected="selected"<?php } ?> value="5">HR</option>-->
    </select>
    </th>
    <?php
    if($i>0) echo "</tr>";
    $i++;
}?>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Commesse</span>
                     </th>
                  </tr>
<?php
// access first element of $ar array
$query="select * from commesse";
$i=0;
    $ris_query=mysql_query($query, $conn->db);
    if(!$ris_query){
        die('Errore DB: ' . mysql_error());
} else {
    while ($row = mysql_fetch_array($ris_query)){
        $gruppo=$row['nome_commessa'];
         $gruppo1 = str_replace(" ", "", $gruppo);
        $id_commessa=$row['id_commessa'];
        $id_resp=$row['id_responsabile'];
        
   echo '<tr><th></th><th>'.$gruppo.': </th>';
    /*
    if($i==0) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==1) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==2) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==3) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($i==4) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
     */
    ?> 
    
                     <th>
    <select name="<?php echo $gruppo1;?>">
        <?php $query_com="SELECT commesse FROM anagrafica where user_id=".$_GET['id_utente'];
               $ris_query_com=mysql_query($query_com, $conn->db);
               while ($row_com = mysql_fetch_array($ris_query_com)){
                   $commesse=$row_com['commesse'];
                   
                   }
               
        ?>
        <option <?php if(strstr($commesse,"*".$id_commessa."*")&&$id_resp!=$_GET['id_utente']){?> selected="selected"<?php } ?> value="Appartiene">Appartiene</option>
        <option <?php if(!strstr($commesse,"*".$id_commessa."*")){?> selected="selected"<?php } ?> value="Non Appartiene">Non Appartiene</option>
        <option <?php if(strstr($commesse,"*".$id_commessa."*")&&$id_resp==$_GET['id_utente']){?> selected="selected"<?php } ?> value="Responsabile">Responsabile</option>
    </select>
    </th>
    <?php
    if($i>0) echo "</tr>";
    $i++;
}
}
}
}
}
if($contr_el==0) {
                ?>
 <tr>
     <th></th>
     <th><input type="submit" name="modifica_dip" value="Modifica" class="btn btn-success btn-md" style="margin-right: 30px;"><input type="submit" name="elimina_dip" value="Elimina" class="btn btn-danger btn-md"></th>              
 </tr>
</tbody></table>
         </div></div></div></form>
        <?php }
}
        }else {
?>
                    
    <div class="container" style="margin-top:4%;"></div>
   <center>
      <div class="row">
         <div class="col-md-1"></div>
         <div class="col-md-1"><a href="amministrazione.php"><img style="cursor: pointer;" height="25px" src="img/back.png"></a></div>
      </div>
   </center>
   <div class="section">
      <div class="container">
         <div class="row" style="margin-top:3%;background-color:#333;padding-top:1%; padding-bottom:1%; max-width:100%;">
            <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%;">Nuovo Profilo</span>
         </div>
      </div>
   </div>
              <form action="" method="post">
       <div class="section">
      <div class="container">
         <div class="row" style="margin-top:-7%;">
            <table class="table" style="max-width:100%;">
               <tbody>
               <thead>
                  <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Nome*</span>
                     </th>
                     <th><input type="text" name="nome" value="<?php if(isset($_POST['nome']))echo $_POST['nome'];?>">
                     </th></tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Cognome*</span>
                     </th>
                     <th><input type="text" name="cognome" value="<?php if(isset($_POST['cognome']))echo $_POST['cognome'];?>">
                     </th>
                  </tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Data di nascita (dd/mm/yyyy)</span>
                     </th>
                     <th><input type="text" name="data_nascita" value="<?php if(isset($_POST['data_nascita']))echo $_POST['data_nascita'];?>">
                     </th>
                  </tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Città di Residenza</span>
                     </th>
                     <th><input type="text" name="citta_residenza" value="<?php if(isset($_POST['citta_residenza']))echo $_POST['citta_residenza']?>">
                     </th></tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Indirizzo di Residenza</span>
                     </th>
                     <th><input type="text" name="indirizzo_residenza" value="<?php if(isset($_POST['indirizzo_res']))echo $_POST['indirizzo_res']?>">
                     </th></tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Email*</span>
                     </th>
                     <th><input type="text" name="email" value="<?php if(isset($_POST['email']))echo $_POST['email']?>">
                     </th>
                  </tr>
<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Codice Fiscale</span>
                     </th>
                     <th><input type="text" name="codice_fiscale" value="<?php if(isset($_POST['codice_fiscale']))echo $_POST['codice_fiscale'];?>">
                     </th>
                  </tr>
 <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th>
                        <span style="font-size:1em;font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; ">Gruppi e Profili</span>
                     </th>
                     <th>
<?php

// access first element of $ar array
$gruppo="";
for($i=0;$i<6;$i++){
     if($i==0) $gruppo = "ST";
    if($i==1) $gruppo = "Ufficio";
    if($i==2) $gruppo = "Esterni";
    if($i==3) $gruppo = "Tecnici";
    if($i==4) $gruppo = "SWTeam";
    if($i==5) $gruppo = "Responsabili";
    if($i>0) print '<tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                     <th></th>
                     <th>';
    echo '<span style="font-size:1em;font-weight:100; font-family:Play; margin-top:12%; margin-left:2%; ">'.$gruppo.": </span>";
    ?> </th><th>
    <select name="<?php echo $gruppo;?>">
        <option  value="0">Non Appartiene</option>
        <option value="1">Admin</option>
        <option  value="2">Responsabile</option>
        <option  value="3">Editore</option>
        <option  value="4">Utente</option>
        <option  value="5">HR</option>
    </select>
                     </th>             
    <?php
}

                ?>
                  </tr>  
  <tr style="background-color:#333;"></tr>
                  <tr>
                     <th></th>
                  </tr>
                  <tr>
                      <th><input type="submit" name="nuovo_dip" class="btn btn-success btn-md" value="Crea Dipendente">
                     </th>
                  </tr>
  
</tbody></table></center>
</form>
        <?php } ?>
                  </div>
