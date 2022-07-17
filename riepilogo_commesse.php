<?php
if(!isset($_SESSION['responsabile'])||$_SESSION['responsabile']==0){
    echo 'Non dispone dei provilegi necessari per visualizzare questa pagina.';
    
}
else if(!isset($_GET['id_utente'])){

$num=0;
?>
   
<form method="post" action="">        
   <!-- Barra di ricerca -->
   <div class="container" style="margin-top:4%;"></div>
      <center>
         <div class="row">
<div class="col-md-2"></div>
            <div class="col-md-5">
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
<div class="ficheaddleft">

            <?php
         if(!isset($_POST['nome'])&&!isset($_POST['cognome'])||isset($_POST['visualizza_dip'])&&$_POST['visualizza_dip']=="Visualizza Tutti")
                        
        { 
             
                        $query_time = "select * FROM anagrafica";

                        $query_time .= " ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
        } if(isset($_POST['nome'])&&$_POST['cognome']=="")
        {
            
              $query_time = "select * FROM anagrafica WHERE"; 
                        $query_time .= " nome LIKE '%".$_POST['nome']."%' ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
        }
        if(isset($_POST['cognome'])&&$_POST['nome']=="")
        {
                       
                            $query_time = "select * FROM anagrafica WHERE";
                       
                        $query_time .= " cognome LIKE '%".$_POST['cognome']."%' ORDER BY user_id DESC";
                        $result_time = mysql_query($query_time, $conn->db);
        }
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {?>
      <!-- Lista Dipendenti -->
      <div class="section" style=" min-width:30%;">
         <div class="container">
            <div class="row" style="margin-top:0;">
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
   
    <tr>
                        <td><?php echo $num; ?></td>
                        <td><span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="responsabile.php?id=utenti&id_utente=<?php echo $user_id?>"><?php echo $cognome . " " .$nome ?></a></span></td>
                        <td>  <span style="font-size:1em; color:#333; font-weight:600; font-family:Play; margin-top:12%; margin-left:2%; "><a style="text-decoration: underline; color: black;" href="responsabile.php?id=utenti&id_utente=<?php echo $user_id?>"><?php echo $data[2]."/".$data[1]."/".$data[0]; ?></a></span></td>
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
<?php
    
} else if(isset($_GET['id_utente'])&&$_GET['id_utente']!=""){

        $sql3_anagrafica = "SELECT 
                                                        user_id,
							nome, 
							cognome, 
							luogo_nascita, 
							data_nascita, 
							citta_residenza,
							indirizzo_residenza,
							citta_domicilio,
							indirizzo_domicilio,
                            img_name,
							codice_fiscale FROM anagrafica WHERE user_id=" . $_GET['id_utente'];
      


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);

    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $id = $row['user_id'];
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
        }
    }
    $data_nascita = @explode('-', $data_nascita);
    ?>
    <div id="foto" class="fluid" style="width:20%; float:left; margin-top:30px;">
    <?php if($img_name==0) { ?><img width="150" height="150" style="margin-left:25%; margin-top:50px;" src="img/foto_profilo.png"><?php } else { ?><img style="max-width:150px; max-height:150px; margin-left: 25%; margin-top: 50px; border: 2px solid black;" src="img/CV/<?php echo $id;?>/<?php echo $img_name;?>"><?php } ?>
     
                  </div>

<!-- DIV CONTENITORE -->
<div style="float:right; margin-top:80px;width:80%;">
    <!-- DATI PERSONALI -->
<table width="80%" height="35px"padding="8px"><tbody><tr class="liste_titre">  
<th class="liste_titre" colspan="2" style="width:100%; font-family:play;"><strong style="font-size:18px; font-family:play; font-weight:bold;">&nbsp;DATI PERSONALI</strong></th>
<th class="liste_titre"></th>
<th class="liste_titre"></th>
<th class="liste_titre">&nbsp;</th>
<th class="liste_titre">&nbsp;</th>
<th class="liste_titre">&nbsp;</th>
<th class="liste_titre" width="20">&nbsp;</th></table></center>

<div>
<table class="noborder" width="80%" height="50px"padding="8px">
<tr class="impair"><tr>
<td style="width: 50%;font-size:14px; font-family:play; font-weight:bold;text-align: left;">Nome e Cognome</td>
<td class="nowrap" style="width: 50%;font-size:14px; font-family:play;text-align: left;"><?php echo $nome . " " . $cognome; ?></td></tr>
<tr class="impair"><tr>
<td style="font-size:14px; font-family:play; font-weight:bold;text-align: left;">Data di nascita:</td>
<td class="nowrap" style="font-size:14px; font-family:play;text-align: left;"><?php echo $data_nascita[2] . "/" . $data_nascita[1] . "/" . $data_nascita[0] ?></td></tr>
<tr class="pair"><tr>
<td style="font-size:14px; font-family:play; font-weight:bold;text-align: left;">Residenza</td>
<td class="nowrap" style="font-size:14px; font-family:play;text-align: left;"><?php echo $citta_residenza . ", " . $indirizzo_res; ?></td></tr>
<!--<td style="font-size:14px; font-family:play; font-weight:bold;text-align: left;">Email</td>
<td class="nowrap" style="font-size:14px; font-family:play;text-align: left;"><?php echo $email ?></td></tr>-->
<td style="font-size:14px; font-family:play; font-weight:bold;text-align: left;">Codice Fiscale</td>
<td class="nowrap" style="font-size:14px; font-family:play;text-align: left;"><?php echo $codice_fiscale ?></td></tr>
</tbody></table></center><br></div>

<table width="80%" height="35px"padding="8px"><tbody><tr class="liste_titre">  
<th class="liste_titre" colspan="2" style="width:100%; font-family:play;"><strong style="font-size:18px; font-family:play; font-weight:bold;">&nbsp;COMMESSE</strong></th>
<th class="liste_titre"></th>
<th class="liste_titre"></th>
<th class="liste_titre">&nbsp;</th>
<th class="liste_titre">&nbsp;</th>
<th class="liste_titre">&nbsp;</th>
<th class="liste_titre" width="20">&nbsp;</th></table></center>

<?php
$query="select * from anagrafica where user_id=".$_GET['id_utente'];
$result=  mysql_query($query,$conn->db);

if($result){
    while($row=  mysql_fetch_array($result)){
        $commesse= $row['commesse'];
        $user_id = $row['user_id'];
       
    }
    $com_utente=explode("*",$commesse);
     $query_commesse="";
    for($i=1;$i<count($com_utente)-1;$i++){
                                        if($i==1) $query_commesse.=" id_commessa='".$com_utente[$i]."'";
                                        else $query_commesse.= " OR id_commessa='".$com_utente[$i]."'";
                                    }
                                    $query="SELECT * from commesse WHERE";
                                    $query.= $query_commesse;
                                            $result=  mysql_query($query);
                                            
                                            while($row_commesse= mysql_fetch_array($result)){
                                                 $commessa_id=$row_commesse['id_commessa'];
                                                 $query2="select * from commesse where id_responsabile='".$_SESSION['user_idd']."' AND id_commessa=".$commessa_id;
                                                 $result2=  mysql_query($query2,$conn->db);
                                                 
                                            ?>
<form action="" method="post">
    <input <?php if(mysql_num_rows($result2)==0){?> disabled="disabled" <?php } ?> type="submit" style="   background: #414958;
                border-radius: 5px;
                padding-right: 20px;
                padding-left: 10px;
                color:#fff;
                height: 30px;
                line-height: 0px;
                background-image: url('img/x.png');
                background-size: 16px;
                background-repeat: no-repeat;
                background-position: right;" name="commessa_input" value="<?php echo $row_commesse['nome_commessa']?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id?>">
    <input type="hidden" name="id_della_commessa" value="<?php echo $commessa_id?>">
    <input type='hidden' name="commesse_string" value="<?php echo $commesse?>">
</form>
                                    
                                            <?php } 
}
?>
<form action="" method="post">
    <pre style='color:#000; margin-left: 10px;'>Aggiungi Commessa: 
<select name="commessa_add">
        <?php
        $query="select * from commesse where id_responsabile='".$_SESSION['user_idd']."'";
       for($i=1;$i<count($com_utente)-1;$i++){
           $query.= " AND id_commessa != '".$com_utente[$i]."'";
       }
       $result=  mysql_query($query,$conn->db);
       while($row_result=  mysql_fetch_array($result)){
           ?>
        <option value="<?php echo $row_result['id_commessa']?>"><?php echo $row_result['nome_commessa']?></option>
        <?php
       }
        ?>
    </select> <input type="submit" value="Aggiungi" name="add_commessa">
    <input type='hidden' name="commesse_string" value="<?php echo $commesse?>">
</pre>
</form>
<?php
}
