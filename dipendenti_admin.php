<?php 
$num=0;?>

<div id="contenitore1" class="fluid" style="padding-left:20px;">

<?php
                    if(isset($_GET['id']) && $_GET['id']=="dip"){
                        ?>
      
               <form method="post" action="">
   
   <table align="center" width="auto" height="50" cellspacing="5" style="margin-bottom:50px; margin-top:10px; overflow:auto;">
  <tbody>
    <tr>
         <th scope="col"><span style="text-align: center"><strong style="font-family:play; font-size:14px;">Nome</strong></span><strong style="font-family:play; font-size:16px;"><br>
          <input id="nome" class="shadows" name="nome" placeholder="nome" type="nome" style="height:15px;"></strong></th>
      <th scope="col"><span style="text-align: center"><strong style="font-family:play; font-size:13px;">Cognome</strong></span><strong style="font-family:play; font-size:13px;"><br>
          <input id="cognome" class="shadows" name="cognome" placeholder="cognome" type="cognome" style="height:15px;"></strong></th>
      <th scope="col"><input class="liste_titre" type="image" title="Cerca" value="Cerca" src="img/cerca2.png" name="cerca" style="margin-bottom:-28px;"></th>
      <th scope="col"><input class="liste_titre" type="image" title="Visualizza tutti" value="Visualizza tutti" src="img/visualizza.png" name="visualizza_dip" style="margin-bottom:-28px;"></th>
    </tr>
  </tbody>
</table>

            <?php
         if(!isset($_POST['nome'])&&!isset($_POST['cognome'])||isset($_POST['visualizza_dip'])&&$_POST['visualizza_dip']=="Visualizza Tutti")
                        
        { 
             
                        $query_time = "select user_id,nome, cognome, luogo_nascita,data_nascita FROM anagrafica ORDER BY cognome, nome";
                        $result_time = mysql_query($query_time, $conn->db);
        } if(isset($_POST['nome'])&&$_POST['cognome']=="")
        {
                         $query_time = "select user_id,nome, cognome, luogo_nascita,data_nascita FROM anagrafica WHERE nome LIKE '%".$_POST['nome']."%' ORDER BY cognome, nome";
                        $result_time = mysql_query($query_time, $conn->db);
  
        }
        if(isset($_POST['cognome'])&&$_POST['nome']=="")
        {
                       
                         $query_time = "select user_id,nome, cognome, luogo_nascita,data_nascita FROM anagrafica WHERE cognome LIKE '%".$_POST['cognome']."%' ORDER BY cognome, nome";
                         $result_time = mysql_query($query_time, $conn->db);
  
        }
if (!$result_time) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {?>
   
                          <?php 
                          while ($row = mysql_fetch_array($result_time)) {

        $user_id = $row['user_id'];
        $nome = $row['nome'];
        $cognome = $row['cognome'];
        $data_nascita = $row['data_nascita'];
        $luogo_nascita = $row['luogo_nascita'];
   
        $data=explode("-",$data_nascita);
        $num++;
        ?>
<table  width="auto" cellspacing="5" style="overflow:auto;">
  <tbody>
    <tr>
                              <th style="text-align: left" scope="col"><img src="img/dipendente.png">&nbsp;<strong style="font-family:play; font-size:18px;">
                                <a style="text-decoration: underline; color: black;" href="riepilogo.php?id=<?php echo $user_id?>"><?php echo $cognome . " " . $nome." - ".$data[2]."/".$data[1]."/".$data[0]." - ".$luogo_nascita; ?></a>
                         </strong><a target="blank" href="/form/tcpdf/examples/PDF_create.php?id=<?php echo $user_id?>"><img width="30px" src="img/pdf2.png"></a></td>
                </th></tr> </tbody>
</table>
<?php
                    }
                ?>
               
        <?php
    }
                    }
                    ?>
                  </div>