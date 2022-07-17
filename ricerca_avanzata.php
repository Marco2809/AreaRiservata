<style type="text/css">
             .ric{
                background: #414958;
                border-radius: 5px;
                padding-right: 10px;
                padding-left: 10px;
                color:#fff;
                height: 30px;
                line-height: 0px;
                background-image: url('img/x.png');
                background-size: 16px;
                background-repeat: no-repeat;
                background-position: right top;

            }

        </style>
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
                    if(isset($_GET['id']) && $_GET['id']=="ric"||!isset($_GET['id'])){
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
                                    if($control_for == 1) ${'where'.$i} .= " f.user_id = a.user_id AND f.laurea LIKE '%".$fin[1]."%'";
                                    if($control_for != 1) $where .= " AND f.laurea LIKE '%".$fin[1]."%'";

                                    $control_for=1;
                                    $quer=1;

                            }

                             if($fin[0]=="tipologia")
                            {

                                    if($control_con != 1)  $from.=", skill c";
                                    if ($i==1)
                                    {
                                        $where.=" c.skill_user_id = a.user_id";
                                        $user_control = "c.skill_user_id";
                                    }
                                    else if($i!=1 && $control_con!=1) $where.=" AND c.skill_user_id = a.user_id";
                                    if($control_con == 1) ${'where'.$i} .= " c.skill_user_id = a.user_id AND c.skill LIKE '%".$fin[1]."%'";
                                    if($control_con != 1) $where .= " AND c.skill LIKE '%".$fin[1]."%'";

                                    $control_con=1;
                                    $quer=1;

                            }
                              /*if($fin[0]=="des")
                            {

                                    if($control_con != 1)  $from.=", skill c";
                                    if ($i==1)
                                    {
                                        $where.=" c.skill_user_id = a.user_id";
                                        $user_control = "c.skill_user_id";
                                    }
                                    else if($i!=1 && $control_con!=1) $where.=" AND c.skill_user_id = a.user_id";
                                    if($control_con == 1) ${'where'.$i} .= " c.skill_user_id = a.user_id AND c.descrizione LIKE '%".$fin[1]."%'";
                                    if($control_con != 1) $where .= " AND c.descrizione LIKE '%".$fin[1]."%'";

                                    $control_con=1;
                                    $quer=1;

                            } */
                            if($fin[0]=="livello")
                            {

                                    if($control_con != 1)  $from.=", skill c";
                                    if ($i==1)
                                    {
                                        $where.=" c.skill_user_id = a.user_id";
                                        $user_control = "c.skill_user_id";
                                    }
                                    else if($i!=1 && $control_con!=1) $where.=" AND c.skill_user_id = a.user_id";
                                    if($control_con == 1) ${'where'.$i} .= " c.skill_user_id = a.user_id AND c.livello_skill LIKE '%".$fin[1]."%'";
                                    if($control_con != 1) $where .= " AND c.livello_skill LIKE '%".$fin[1]."%'";

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

    <input type="button" class='ric' onclick="removeItem('&<?= $and[$i]; ?>')" value="<?php echo str_replace('%20',' ',$ar[$x]);?>  ">

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

       <tr style ="cursor:pointer;" onclick=" window.open('riepilogo.php?id=<?php echo $row['user_id']?>');return false;" style="padding:10px; border: 1px solid black;"><?php echo '<td style="padding:10px; cursor:pointer; color:#000; text-align: center; border: 1px solid black;">'.$count. '.</td><td style="padding:10px; cursor:pointer; color:#000;text-align: center; border: 1px solid black;">' .$row['nome'] . '</td><td style="padding:10px; cursor:pointer; color:#000;text-align: center; border: 1px solid black;">' . $row['cognome'] . '</td><td style="padding:10px;cursor:pointer; color:#000; text-align: center; border: 1px solid black;">' . $row['codice_fiscale'].'</td>';?></tr>

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


  <div id="contenitore1" class="fluid ">

         <table  class="noborder" width="50%" height="40px"padding="8px" style="margin-top: 10px;margin-right: 50px;">
  <tbody>
    <tr>
        <td style="text-align: left; width: 35%"><a href="amministrazione.php" style="margin-left: 0px;"><img style="cursor: pointer; margin-top: 10px;" height="25px" src="img/back.png"></a></td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input type="submit" class="btn btn-success btn-md" name="ricerca" value="Cerca"><br><br></td>
    </tr>
         </tbody>
    </table>

     <table  class="noborder" width="50%" height="40px"padding="8px" style="margin-top: -30px;margin-right: 50px;">
  <tbody>
      <tr class="liste_titre">
                <th class="liste_titre" colspan="4" style="width:80%; padding-bottom: 10px; padding-top: 10px;"><strong style="font-size:18px; color:#fff; font-family:play; font-weight:bold; margin-left:10px;">Competenze Acquisite</strong>
      <tr style="font-size:16px; font-family:play; font-weight:bold;text-align: left;  height: 20px; line-height: 50px; color: #000;"></tr>
    <tr>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left; width: 20%;"><strong>Area di Competenza</strong></td>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><select name="area" id="area">
    <option <?php if(isset($_POST['area'])&&$_POST['area']=="Sys Admin" ) { ?>selected="selected"<?php } ?> value="Sys Admin" >Sys Admin</option>
        <option <?php if(isset($_POST['area'])&&$_POST['area']=="Tecnico Hardware" ) { ?>selected="selected"<?php } ?> value="Tecnico Hardware" >Tecnico Hardware</option>
        <option <?php if(isset($_POST['area'])&&$_POST['area']=="Phisical Network Developer" ) { ?>selected="selected"<?php } ?>value="Phisical Network Developer" >Phisical Network Developer</option>
        <option <?php if(isset($_POST['area'])&&$_POST['area']=="Network Admin" ) { ?>selected="selected"<?php } ?>value="Network Admin" >Network Admin</option>
        <option <?php if(isset($_POST['area'])&&$_POST['area']=="Consulente Direzionale" ) { ?>selected="selected"<?php } ?>value="Consulente Direzionale" >Consulente Direzionale</option>
        <option <?php if(isset($_POST['area'])&&$_POST['area']=="Developer - IT Solutions" ) { ?>selected="selected"<?php } ?>value="Developer - IT Solutions" >Developer - IT Solutions</option>
         <option <?php if(isset($_POST['area'])&&$_POST['area']=="Web Design - Grafica 3D" ) { ?>selected="selected"<?php } ?>value="Web Design - Grafica 3D" >Web Design - Grafica 3D</option>
          <option <?php if(isset($_POST['area'])&&$_POST['area']=="Altro" ) { ?>selected="selected"<?php } ?>value="Altro">Altro</option>
    </select></td>
     <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">
        <input  type="image" src="img/aggiungi1.png" onclick="modificaLink('area',document.getElementById('area').value);return false;" value="Aggiungi"></td>
        </tr>
        <!--<tr>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Sottocategoria</strong></td>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input class="shadows" type="text" id="sub" name="sub"></td>
     <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">
        <input  type="image" src="img/aggiungi1.png" onclick="modificaLink('sub',document.getElementById('sub').value);return false;" value="Aggiungi"></td>
        </tr>-->
        <tr>
           <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Ruolo</strong></td>
           <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input class="shadows" type="text" id="ruolo" name="ruolo"></td>
     <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;">
        <input  type="image" src="img/aggiungi1.png" onclick="modificaLink('ruolo',document.getElementById('ruolo').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Mansione</strong>&nbsp;</td>
 <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input type="text" class="shadows" id="mansione" name="mansione"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('mansione',document.getElementById('mansione').value);return false;" value="Aggiungi"></td>
        </tr>
        </tbody>
</table>
</tr></div>

  <div id="contenitore1" class="fluid ">
   <table  class="noborder" width="50%" height="40px"padding="8px" style="margin-top: 10px;margin-right: 50px;">
  <tbody>
      <tr class="liste_titre">
                <th class="liste_titre" colspan="4" style="width:80%; padding-bottom: 10px; padding-top: 10px;"><strong style="font-size:18px; color:#fff; font-family:play; font-weight:bold; margin-left:10px;">Istruzione</strong>
      <tr style="font-size:16px; font-family:play; font-weight:bold;text-align: left;  height: 20px; line-height: 50px; color: #000;"></tr>
    <tr>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left; width: 20%"><strong>Nome Istituto</strong></td>
        <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input id="nomeis" class="shadows" name="nomeis"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('nomeis',document.getElementById('nomeis').value);return false;" value="Aggiungi"></td>
        </tr>
         <tr>
             <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Corso di Laurea</strong>&nbsp;</td>
   <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input type="text" class="shadows" id="corso"  name="corso"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('corso',document.getElementById('corso').value);return false;" value="Aggiungi"></td>
        </tr>
         <tr>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Corso di Studi</strong>&nbsp;</td>
 <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input type="text" class="shadows" id="studi"  name="studi"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('studi',document.getElementById('studi').value);return false;" value="Aggiungi"></td>
        </tr>
  </tbody>
</table>
</div>


  <div id="contenitore1" class="fluid ">
   <table  class="noborder" width="50%" height="40px"padding="8px" style="margin-top: 10px;margin-right: 50px;">
  <tbody>
      <tr class="liste_titre">
                <th class="liste_titre" colspan="4" style="width:80%; padding-bottom: 10px; padding-top: 10px;"><strong style="font-size:18px; color:#fff; font-family:play; font-weight:bold; margin-left:10px;">Skill</strong>
      <tr style="font-size:16px; font-family:play; font-weight:bold;text-align: left;  height: 20px; line-height: 50px; color: #000;"></tr>
    <tr>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;width: 20%;"><strong>Tipologia</strong></td>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input id="tipologia" class="shadows" type="text" name="tipologia"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('tipologia',document.getElementById('tipologia').value);return false;" value="Aggiungi"></td>
        </tr>
         <!--<tr>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Descrizione</strong></td>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input id="des" class="shadows" type="text" name="des"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('des',document.getElementById('des').value);return false;" value="Aggiungi"></td>
        </tr>-->
        <tr>
             <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Livello</strong></td>
   <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><select class="shadows" id="livello" name="livello">
                    <option value="Principiante"> Principiante </option>
                    <option value="Intermedio"> Intermedio </option>
                    <option value="Avanzato"> Avanzato </option>
                </select></td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('livello',document.getElementById('livello').value);return false;" value="Aggiungi"></td>
        </tr>
         </tbody>
</table>
</div>

  <div id="contenitore1" class="fluid ">
  <table  class="noborder" width="50%" height="40px"padding="8px" style="margin-top: 10px;margin-right: 50px;">
  <tbody>
      <tr class="liste_titre">
                <th class="liste_titre" colspan="4" style="width:80%; padding-bottom: 10px; padding-top: 10px; background-color:yellow;"><strong style="font-size:18px; color:#fff; font-family:play; font-weight:bold; margin-left:10px;">Certificazioni</strong>
      <tr style="font-size:16px; font-family:play; font-weight:bold;text-align: left;  height: 20px; line-height: 50px; color: #000;"></tr>
    <tr>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;width: 20%;"><strong>Titolo</strong></td>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input id="titolocer" class="shadows" type="text" name="titolocer"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('titolocer',document.getElementById('titolocer').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>
            <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Ente</strong><br></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input id="ente" class="shadows" type="text" name="ente"></td>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('ente',document.getElementById('ente').value);return false;" value="Aggiungi"></td>
        </tr>
          </tbody>
</table>
</div>

  <div id="contenitore1" class="fluid ">
  <table  class="noborder" width="50%" height="40px"padding="8px" style="margin-top: 10px;margin-right: 50px;">
  <tbody>
      <tr class="liste_titre">
                <th class="liste_titre" colspan="4" style="width:80%; padding-bottom: 10px; padding-top: 10px;"><strong style="font-size:18px; color:#fff; font-family:play; font-weight:bold; margin-left:10px;">Corsi di Sicurezza</strong>
      <tr style="font-size:16px; font-family:play; font-weight:bold;text-align: left;  height: 20px; line-height: 50px; color: #000;"></tr>
    <tr>
      <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;width: 20%;"><strong>Tipologia</strong></td>
     <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><select id="tipo_cor" class="shadows" name="tipo_cor">
                    <option value="Corso Base"> Corso Base </option>
                    <option value="Primo Soccorso"> Primo Soccorso </option>
                    <option value="Anti Incendio"> Anti Incendio </option>
                    <option value="Capocantiere"> Capocantiere </option>
                    <option value="RLS"> RLS </option>
                    <option value="RSSP"> RSSP </option>
                </select></td><td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('tipo_cor',document.getElementById('tipo_cor').value);return false;" value="Aggiungi"></td>
        </tr>
        <tr>
           <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><strong>Descrizione</strong></td>
       <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input id="des_cor" class="shadows" type="text" name="des_cor"></td>
    <td style="font-size:16px; font-family:play; font-weight:bold;text-align: left;"><input  type="image" src="img/aggiungi1.png" onclick="modificaLink('des_cor',document.getElementById('des_cor').value);return false;" value="Aggiungi"></td>
        </tr>
         </tbody>
    </table>
    </div>

</form>


                    <?php } ?>
                </div>
