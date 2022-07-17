
<?php 
    $select="SELECT DISTINCT a.nome, a.cognome, a.user_id, a.codice_fiscale";
     $select1="SELECT DISTINCT a.user_id";
    $from=" FROM anagrafica a";
    $where=" WHERE";
    $quer=0;
    $control_esp=0;
    $control_for=0;
    $control_con=0;
    $control_cer=0;
    $control_cor=0;
    ?>
  <center>
    <div id="contenitore" class="fluid">
    <?php 
                    if(isset($_GET['id']) && $_GET['id']=="cerca"||!isset($_GET['id'])){
                        ?>
<form action="" method="post" name="form">
  
<div>
    <?php 
                   
                    $and=explode("&", $_SERVER["QUERY_STRING"]);
                
                    $p=0;
                    $x=1;
                    for($i=1;$i<count($and);$i++)
                    {
                        $fin=explode("=", $and[$i]);
                        $ar[$p]= $fin[0];
                        $ar[$p+1] = $fin[1];
                        $p=$p+2;
                        ${'where'.$i} ="";
                     if(isset($_POST['ricerca'])&&$_POST['ricerca']=="Cerca")
                     {

                            $fin[1] = str_replace("%20"," ",$fin[1]);
                            
                            if($fin[0]=="ruolo") 
                            {   
                                    
                                    if($control_esp != 1)  $from.=", esperienza e";
                                    if ($i==1)  
                                    {
                                        $where.=" e.user_id = a.user_id"; 
                                        $user_control = "e.user_id";
                                    }
                                    else if($i!=1 && $control_esp!=1) $where.=" AND e.user_id = a.user_id"; 
                                    if($control_esp == 1) ${'where'.$i} .= " e.user_id = a.user_id AND e.ruolo LIKE '%".$fin[1]."%'";
                                    if($control_esp != 1) $where .= " AND e.ruolo LIKE '%".$fin[1]."%'";
                                    $control_esp = 1;
                                    $quer=1;
                           
                            }
                            if($fin[0]=="mansione") 
                            {   
                                    
                                    if($control_esp != 1)  $from.=", esperienza e";
                                    if ($i==1)  
                                    {
                                        $where.=" e.user_id = a.user_id"; 
                                        $user_control = "e.user_id";
                                    }
                                    else if($i!=1 && $control_esp!=1) $where.=" AND e.user_id = a.user_id"; 
                                    if($control_esp == 1) ${'where'.$i} .= " e.user_id = a.user_id AND e.mansione LIKE '%".$fin[1]."%'";
                                    if($control_esp != 1) $where .= " AND e.mansione LIKE '%".$fin[1]."%'";
                                    $control_esp = 1;
                                    $quer=1;
                           
                            }
                            if($fin[0]=="area") 
                            {   
                                    
                                    if($control_esp != 1)  $from.=", esperienza e";
                                    if ($i==1)  
                                    {
                                        $where.=" e.user_id = a.user_id"; 
                                        $user_control = "e.user_id";
                                    }
                                    else if($i!=1 && $control_esp!=1) $where.=" AND e.user_id = a.user_id"; 
                                    if($control_esp == 1) ${'where'.$i} .= " e.user_id = a.user_id AND e.area LIKE '%".$fin[1]."%'";
                                    if($control_esp != 1) $where .= " AND e.area LIKE '%".$fin[1]."%'";
                                    $control_esp = 1;
                                    $quer=1;
                           
                            }
                            if($fin[0]=="sub") 
                            {   
                                    
                                    if($control_esp != 1)  $from.=", esperienza e";
                                    if ($i==1)  
                                    {
                                        $where.=" e.user_id = a.user_id"; 
                                        $user_control = "e.user_id";
                                    }
                                    else if($i!=1 && $control_esp!=1) $where.=" AND e.user_id = a.user_id"; 
                                    if($control_esp == 1) ${'where'.$i} .= " e.user_id = a.user_id AND e.sub_area LIKE '%".$fin[1]."%'";
                                    if($control_esp != 1) $where .= " AND e.sub_area LIKE '%".$fin[1]."%'";
                                    $control_esp = 1;
                                    $quer=1;
                           
                            }
                            if($fin[0]=="studi") 
                            {   
                                
                                    if($control_for != 1)  $from.=", formazione f";
                                    if ($i==1)  
                                    {
                                        $where.=" f.user_id = a.user_id"; 
                                        $user_control = "f.user_id";
                                    }
                                    else if($i!=1 && $control_for!=1) $where.=" AND f.user_id = a.user_id"; 
                                    if($control_for == 1) ${'where'.$i} .= " f.user_id = a.user_id AND f.corso LIKE '%".$fin[1]."%'";
                                    if($control_for != 1) $where .= " AND f.corso LIKE '%".$fin[1]."%'";
                                    
                                    $control_for=1;
                                    $quer=1;
                           
                            }
                            if($fin[0]=="nomeis") 
                            {   
                                
                                    if($control_for != 1)  $from.=", formazione f";
                                    if ($i==1)  
                                    {
                                        $where.=" f.user_id = a.user_id"; 
                                        $user_control = "f.user_id";
                                    }
                                    else if($i!=1 && $control_for!=1) $where.=" AND f.user_id = a.user_id"; 
                                    if($control_for == 1) ${'where'.$i} .= " f.user_id = a.user_id AND f.titolo LIKE '%".$fin[1]."%'";
                                    if($control_for != 1) $where .= " AND f.titolo LIKE '%".$fin[1]."%'";
                                    
                                    $control_for=1;
                                    $quer=1;
                           
                            }
                             if($fin[0]=="corso") 
                            {   
                                
                                    if($control_for != 1)  $from.=", formazione f";
                                    if ($i==1)  
                                    {
                                        $where.=" f.user_id = a.user_id"; 
                                        $user_control = "f.user_id";
                                    }
                                    else if($i!=1 && $control_for!=1) $where.=" AND f.user_id = a.user_id"; 
                                    if($control_for == 1) ${'where'.$i} .= " f.user_id = a.user_id AND f.corso LIKE '%".$fin[1]."%'";
                                    if($control_for != 1) $where .= " AND f.corso LIKE '%".$fin[1]."%'";
                                   
                                    $control_for=1;
                                    $quer=1;
                           
                            }   
                         
                             if($fin[0]=="tipologia") 
                            {   
                                
                                    if($control_con != 1)  $from.=", conoscenze c";
                                    if ($i==1)  
                                    {
                                        $where.=" c.user_id = a.user_id"; 
                                        $user_control = "c.user_id";
                                    }
                                    else if($i!=1 && $control_con!=1) $where.=" AND c.user_id = a.user_id"; 
                                    if($control_con == 1) ${'where'.$i} .= " c.user_id = a.user_id AND c.tipologia LIKE '%".$fin[1]."%'";
                                    if($control_con != 1) $where .= " AND c.tipologia LIKE '%".$fin[1]."%'";
                                    
                                    $control_con=1;
                                    $quer=1;
                           
                            }   
                              if($fin[0]=="des") 
                            {   
                                
                                    if($control_con != 1)  $from.=", conoscenze c";
                                    if ($i==1)  
                                    {
                                        $where.=" c.user_id = a.user_id"; 
                                        $user_control = "c.user_id";
                                    }
                                    else if($i!=1 && $control_con!=1) $where.=" AND c.user_id = a.user_id"; 
                                    if($control_con == 1) ${'where'.$i} .= " c.user_id = a.user_id AND c.descrizione LIKE '%".$fin[1]."%'";
                                    if($control_con != 1) $where .= " AND c.descrizione LIKE '%".$fin[1]."%'";
                                    
                                    $control_con=1;
                                    $quer=1;
                           
                            }   
                            if($fin[0]=="livello") 
                            {   
                                
                                    if($control_con != 1)  $from.=", conoscenze c";
                                    if ($i==1)  
                                    {
                                        $where.=" c.user_id = a.user_id"; 
                                        $user_control = "c.user_id";
                                    }
                                    else if($i!=1 && $control_con!=1) $where.=" AND c.user_id = a.user_id"; 
                                    if($control_con == 1) ${'where'.$i} .= " c.user_id = a.user_id AND c.livello LIKE '%".$fin[1]."%'";
                                    if($control_con != 1) $where .= " AND c.livello LIKE '%".$fin[1]."%'";
                                    
                                    $control_con=1;
                                    $quer=1;
                           
                            }  
                            if($fin[0]=="titolocer") 
                            {   
                                    
                                    if($control_cer != 1)  $from.=", certificazione ce";
                                    if ($i==1)  
                                    {
                                        $where.=" ce.user_id = a.user_id"; 
                                        $user_control = "ce.user_id";
                                    }
                                    else if($i!=1 && $control_cer!=1) $where.=" AND ce.user_id = a.user_id"; 
                                    if($control_cer == 1) ${'where'.$i} .= " ce.user_id = a.user_id AND ce.titolo_certificazione LIKE '%".$fin[1]."%'";
                                    if($control_cer != 1) $where .= " AND ce.titolo_certificazione LIKE '%".$fin[1]."%'";
                                    
                                    $control_cer=1;
                                    $quer=1;
                           
                            } 
                            if($fin[0]=="ente") 
                            {   
                                
                                    if($control_cer != 1)  $from.=", certificazione ce";
                                    if ($i==1)  
                                    {
                                        $where.=" ce.user_id = a.user_id"; 
                                        $user_control = "ce.user_id";
                                    }
                                    else if($i!=1 && $control_cer!=1) $where.=" AND ce.user_id = a.user_id"; 
                                    if($control_cer == 1) ${'where'.$i} .= " ce.user_id = a.user_id AND ce.ente_certificante LIKE '%".$fin[1]."%'";
                                    if($control_cer != 1) $where .= " AND ce.ente_certificante LIKE '%".$fin[1]."%'";
                                    
                                    $control_cer=1;
                                    $quer=1;
                           
                            } 
                            if($fin[0]=="tipo_cor") 
                            {   
                                
                                    if($control_cor != 1)  $from.=", corsi co";
                                    if ($i==1)  
                                    {
                                        $where.=" co.user_id = a.user_id"; 
                                        $user_control = "co.user_id";
                                    }
                                    else if($i!=1 && $control_cor!=1) $where.=" AND co.user_id = a.user_id"; 
                                    if($control_cor == 1) ${'where'.$i} .= " co.user_id = a.user_id AND co.tipo LIKE '%".$fin[1]."%'";
                                    if($control_cor != 1) $where .= " AND co.tipo LIKE '%".$fin[1]."%'";
                                    
                                    $control_cor=1;
                                    $quer=1;
                           
                            } 
                             if($fin[0]=="des_cor") 
                            {   
                                
                                    if($control_cor != 1)  $from.=", corsi co";
                                    if ($i==1)  
                                    {
                                        $where.=" co.user_id = a.user_id"; 
                                        $user_control = "co.user_id";
                                    }
                                    else if($i!=1 && $control_cor!=1) $where.=" AND co.user_id = a.user_id"; 
                                    if($control_cor == 1) ${'where'.$i} .= " co.user_id = a.user_id AND co.descrizione LIKE '%".$fin[1]."%'";
                                    if($control_cor != 1) $where .= " AND co.descrizione LIKE '%".$fin[1]."%'";
                                    
                                    $control_cor=1;
                                    $quer=1;
                           
                            } 
                     }
                        
                        ?>

<input type="button" class="ric" onclick="removeItem('&<?= $and[$i]; ?>')" value="<?php echo str_replace('%20',' ',$ar[$x]);?>  ">

                   <?php
                   $x=$x+2;
                    }
?>
  </div>
  <?php 

                    $query_fin="";
                    $query_int="";
                    $query= $select . $from . $where;
                    $co=0;
                     for($z=1;$z<count($and);$z++)
                     {
                         ${'query'.$z} = "";
                         if(isset(${'where'.$z})&&${'where'.$z}!="") {
                             $co=1;
                         ${'query'.$z} .= $select1 . $from . ' WHERE ' . ${'where'.$z};
                         $query_int.= " AND a.user_id IN( " . ${'query'.$z} .")";
                         }
                     }
                     $query_fin = $query . $query_int;
                     //echo $query_fin;
       if($quer==1){ 
                       
                       $result_query_ric = mysql_query($query_fin, $conn->db);
                       $p= mysql_num_rows($result_query_ric);
                    if (!$result_query_ric) {
    die('Errore di inserimento dati: ' . mysql_error());
} else if($p>0){?>


<table align="center" style="padding: 10px; border: 2px solid black; 
    border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;">
       <!--<caption style="text-align: left; margin-bottom: -10px; margin-top: 10px; color: #000;
	text-shadow: 0px 1px 0px #999, 0px 2px 0px #888;
	font: 30px 'ChunkFiveRegular';"><h3>Risultati</h3></caption>-->
       <tr style="background-color: #ff3333; color: #ffffff;">
     <th style="padding:10px; text-align: center; border: 1px solid black;">Num.</th>
    <th style="padding:10px; text-align: center; border: 1px solid black;">Nome</th> 
    <th style="padding:10px; text-align: center; border: 1px solid black;">Cognome</th>
    <th style="padding:10px; text-align: center; border: 1px solid black;">Codice Fiscale</th>
  </tr>               
           
           <?php 
                          
                          $count=0;
                          while ($row = mysql_fetch_array($result_query_ric)) {
                              $count++;
                              
                              ?>

       <tr onmouseover="this.style.cursor='pointer'" onclick=" window.open('riepilogo.php?id=<?php echo $row['user_id']?>');return false;" style="padding:10px; border: 1px solid black;"><?php echo '<td style="padding:10px; text-align: center; border: 1px solid black;">'.$count. '.</td><td style="padding:10px; text-align: center; border: 1px solid black;">' .$row['nome'] . '</td><td style="padding:10px; text-align: center; border: 1px solid black;">' . $row['cognome'] . '</td><td style="padding:10px; text-align: center; border: 1px solid black;">' . $row['codice_fiscale'].'</td>';?></tr>
    
                              <?php
                          }
                          ?>
        
</table>
        <?php
          } else if($p==0){
              ?>

              <table align="center" style="padding: 10px; border: 2px solid black; 
    border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;">
       <!--<caption style="text-align: left; margin-bottom: -10px; margin-top: 10px; color: #000;
    text-shadow: 0px 1px 0px #999, 0px 2px 0px #888;
    font: 30px 'ChunkFiveRegular';"><h3>Risultati</h3></caption>-->
       <tr style="background-color: #ff3333; color: #ffffff;">
           <th style="padding:10px; text-align: center; border: 1px solid black;">La ricerca non ha prodotto risultati</th></tr></table>
  
              <?php
          }
                   }

            
                    ?>

<div id="professione" class="span" style="margin-top:10px; float:left;"><span style="text-align: left;"></span>
    <p style="float:left; margin-right:450px; margin-bottom:-3px;"><img src="img/esperienza.png"><strong>Esperienza Professionale</strong></p>
  </div>

  <div id="contenitore1" class="fluid ">
  
    <table width="53%" cellspacing="5">
  <tbody>
    <tr>
            <td class="span"><strong>Competenza</strong></td><td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input class="shadows" type="text" id="area" name="area"></td>
     <td width="137">
        <input  type="image" src="img/aggiungi.png" onclick="modificaLink('area',document.getElementById('area').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>
            <td class="span"><strong>Sottocategoria</strong></td><td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input class="shadows" type="text" id="sub" name="sub"></td>
     <td width="137">
        <input  type="image" src="img/aggiungi.png" onclick="modificaLink('sub',document.getElementById('sub').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>
            <td class="span"><strong>Ruolo</strong></td><td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input class="shadows" type="text" id="ruolo" name="ruolo"></td>
     <td width="137">
        <input  type="image" src="img/aggiungi.png" onclick="modificaLink('ruolo',document.getElementById('ruolo').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>
      <td class="span"><strong>Mansione</strong>&nbsp;</td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input type="text" class="shadows" id="mansione" name="mansione"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('mansione',document.getElementById('mansione').value);return false;" value="Aggiungi"></td>
        </tr>
        </tbody>
</table>
</tr></div>

<div id="Istruzione_Formazione" class="span" style="float:left;"><span style="text-align: left;"></span>
    <p style="float:left; margin-right:450px; margin-bottom:-3px;"><img src="img/istruzione.png"><strong>Istruzione e Formazione</strong></p>
  </div>
  
  <div id="contenitore1" class="fluid ">
  <table width="51%" cellspacing="5">
  <tbody>
    <tr>
      <td class="span"><strong>Nome Istituto</strong></td>
        <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input id="nomeis" class="shadows" name="nomeis"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('nomeis',document.getElementById('nomeis').value);return false;" value="Aggiungi"></td>
        </tr>
         <tr>
             <td class="span"><strong>Corso di Laurea</strong>&nbsp;</td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input type="text" class="shadows" id="corso"  name="corso"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('corso',document.getElementById('corso').value);return false;" value="Aggiungi"></td>
        </tr>
         <tr>
      <td class="span"><strong>Corso di Studi</strong>&nbsp;</td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input type="text" class="shadows" id="studi"  name="studi"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('studi',document.getElementById('studi').value);return false;" value="Aggiungi"></td>
        </tr>
  </tbody>
</table>
</div>

        <div id="conoscenze" class="span" style="float:left;"><span style="text-align: left"></span><span style="text-align: left;"></span>
    <p style="float:left; margin-right:450px; margin-bottom:-3px;"><img src="img/competenze.png"><strong>Conoscenze acquisite</strong></p>
  </div>
  
  <div id="contenitore1" class="fluid ">
  <table width="52%" cellspacing="5">
  <tbody>
    <tr>
      <td class="span"><strong>Tipologia</strong></td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input id="tipologia" class="shadows" type="text" name="tipologia"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('tipologia',document.getElementById('tipologia').value);return false;" value="Aggiungi"></td>
        </tr>
         <tr>
      <td class="span"><strong>Descrizione</strong></td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input id="des" class="shadows" type="text" name="des"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('des',document.getElementById('des').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>
             <td class="span"><strong>Livello</strong></td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><select class="shadows" id="livello" name="livello">
                    <option value="Principiante"> Principiante </option>
                    <option value="Intermedio"> Intermedio </option>
                    <option value="Avanzato"> Avanzato </option>
                </select></td><td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('livello',document.getElementById('livello').value);return false;" value="Aggiungi"></td>
        </tr>
         </tbody>
</table>
</div>
         <div id="certificazioni" class="span" style="float:left;"><span style="text-align: left"></span><span style="text-align: left;"></span>
    <p style="float:left; margin-right:450px; margin-bottom:-3px;"><img src="img/certificazione_admin.png"><strong>Certificazioni</strong></p>
  </div>
  
  <div id="contenitore1" class="fluid ">
  <table width="54%" cellspacing="5">
  <tbody>
    <tr>
      <td class="span"><strong>Titolo</strong></td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input id="titolocer" class="shadows" type="text" name="titolocer"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('titolocer',document.getElementById('titolocer').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>    
            <td class="span"><strong>Ente</strong><br></td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input id="ente" class="shadows" type="text" name="ente"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('ente',document.getElementById('ente').value);return false;" value="Aggiungi"></td>
        </tr>
          </tbody>
</table>
</div>
         <div id="corsi" class="span" style="float:left;"><span style="text-align: left"></span><span style="text-align: left;"></span>
  <p style="float:left; margin-right:450px; margin-bottom:-3px;"><img src="img/sicurezza_admin.png"><strong>Corsi di sicurezza</strong></p>
</div>
  
  <div id="contenitore1" class="fluid ">
  <table width="52%" cellspacing="5">
  <tbody>
    <tr>
      <td class="span"><strong>Tipologia</strong></td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"> <select id="tipo_cor" class="shadows" name="tipo_cor">
                    <option value="Corso Base"> Corso Base </option>
                    <option value="Primo Soccorso"> Primo Soccorso </option>
                    <option value="Anti Incendio"> Anti Incendio </option>
                    <option value="Capocantiere"> Capocantiere </option>
                    <option value="RLS"> RLS </option>
                    <option value="RSSP"> RSSP </option>
                </select></td><td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('tipo_cor',document.getElementById('tipo_cor').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>    
           <td class="span"><strong>Descrizione</strong></td>
      <td style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px;color:#000000;"><input id="des_cor" class="shadows" type="text" name="des_cor"></td>
    <td><input  type="image" src="img/aggiungi.png" onclick="modificaLink('des_cor',document.getElementById('des_cor').value);return false;" value="Aggiungi"></td>
        </tr>
         </tbody>
    </table>
    </div>
    <table  width="52%" cellspacing="5">
  <tbody>
    <tr>
            <td><input type="image" src="img/cerca2.png" name="ricerca" value="Cerca"><br><br></td>
        </tr>
         </tbody>
    </table>
</form>


                    <?php } ?>
                </div>