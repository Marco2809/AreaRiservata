
<div id="contenitore1" class="fluid" style="padding-left:20px;">
<?php 

if(isset($_POST['assegna'])&& $_POST['assegna']=="Assegna")
{

  $query_ref="UPDATE anagrafica SET referente = ".$_POST['referenteid']. " WHERE user_id = ".$_POST['id_us'];
   $result_time = mysql_query($query_ref, $conn->db);
    
}

$num=0;
                    if(isset($_GET['id']) && $_GET['id']=="ass"){
                        ?>
                <form method="post" action="">
                <table align="center" width="auto" height="50" cellspacing="5" style="margin-bottom:50px; margin-top:10px; overflow:auto;">
  <tbody>
    <tr>
      <th scope="col"><span style="text-align: center"><strong style="font-family:play; font-size:14px;">Nome</strong></span><strong style="font-family:play; font-size:16px;"><br>
          <input id="nome" class="shadows" name="nome" placeholder="nome" type="nome" style="height:15px;"></strong></th>
      <th scope="col"><span style="text-align: center"><strong style="font-family:play; font-size:13px;">Cognome</strong></span><strong style="font-family:play; font-size:13px;"><br>
          <input id="cognome" class="shadows" name="cognome" placeholder="cognome" type="cognome" style="height:15px;"></strong></th>
      <th scope="col"><input class="liste_titre" type="image" title="Cerca" value="Cerca" src="img/cerca2.png" name="button_search" style="margin-bottom:-28px;"></th>
      <th scope="col"><input class="liste_titre" type="image" title="Visualizza Tutti" value="Visualizza Tutti" src="img/visualizza.png" name="visualizza" style="margin-bottom:-28px;"></th>
    </tr>
  </tbody>
</table>
</form>
            <?php
                    if(!isset($_POST['nome'])&&!isset($_POST['cognome'])||isset($_POST['visualizza'])&&$_POST['visualizza']=="Visualizza Tutti")
                       
                    {
            $sql3_anagrafica = "SELECT 
							nome, 
							cognome, 
							codice_fiscale ,
                                                        user_id,
                                                        referente
                                                        FROM anagrafica ORDER BY cognome";
                     
                    } if(isset($_POST['nome'])&&$_POST['cognome']=="")
                    {
                         $sql3_anagrafica = "SELECT 
							nome, 
							cognome, 
							codice_fiscale,
                                                        user_id,
                                                        referente
                                                        FROM anagrafica WHERE nome LIKE '%".$_POST['nome']."%' ORDER BY cognome " ;
                        
                    } if(isset($_POST['cognome'])&&$_POST['nome']=="")
                    {
                        
                         $sql3_anagrafica = "SELECT 
							nome, 
							cognome, 
							codice_fiscale,
                                                        user_id,
                                                        referente

                                                        FROM anagrafica WHERE cognome LIKE '%".$_POST['cognome']."%' ORDER BY cognome";
                        
                    }

    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);

    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        ?>
                          
                          <?php
       while( $row = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $codice_fiscale = $row['codice_fiscale'];
            $user_idd= $row['user_id'];
            $referente=$row['referente'];
            $num++;
            ?>
             
                <form method="post" action="">
                    <table align="center" width="75%" cellspacing="5" style="overflow:auto; margin-left:auto;">
  <tbody>
    <tr>
                              <th style="text-align: left" scope="col"><img src="img/dipendente.png">&nbsp;<strong style="font-family:play; font-size:18px;"><?php echo $cognome . " " . $nome." - ".$codice_fiscale; ?>
                         </strong>
                     </th>
                    <th style="text-align: right" scope="col"><strong style="font-family:play; font-size:18px;">Referente:</strong>&nbsp;&nbsp;&nbsp;
                        <select class="shadows" name="referenteid" style="width:125px; margin-bottom:15px;">
                                
                             <?php 
                         
                         $sql_ref = "SELECT 
							user_idd
                                                        FROM login WHERE is_admin = 1 OR is_admin = 2";
                         $result_ref = mysql_query($sql_ref, $conn->db);
                           while( $row2 = mysql_fetch_array($result_ref, MYSQL_ASSOC)) {
                              
                              
                               
                               $sql_ref1 = "SELECT 
							                             user_id,
                                                        nome,
                                                        cognome
                                                        FROM anagrafica WHERE user_id = ". $row2['user_idd'];
                                $result_ref1 = mysql_query($sql_ref1, $conn->db);
                               while( $row1 = mysql_fetch_array($result_ref1, MYSQL_ASSOC)) {
                               $ref= $row1['nome'] ." " .$row1['cognome'];
                                
                               }
                              if($row2['user_idd']==119) $ref="HR";
                              if($row2['user_idd']==156) $ref="Matteo Scarda";
                               ?>
                                 <option value="<?php echo $row2['user_idd']; ?>" <?php if($referente==$row2['user_idd']){?>selected="selected" <?php } ?>><?php echo $ref;?></option>
                      <?php 
                      
                           }
                           
                           ?>
              
                             </select>
                             <input type="hidden" name="id_us" value="<?php echo $user_idd;?>">
                         <input class="liste_titre" type="image" title="Assegna" value="Assegna" src="img/assegna.png" name="assegna" style="margin-bottom:-16px;">
                        </th>
                         </tr>
                   </tbody>  </table>
                    </form>
                     <?php
               
                       
    }
                    }
                    }
 
                    ?>
                        
                        </div>
    