<?php
session_start();
include('dbconn.php');
$conn = new dbconnect();
$r = $conn->connect();

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
} else {
    $action = '';
}
$alert = "";
switch ($action) {

    case 'Registrazione':
//$risultato_registrazione = mysql_query("SELECT email FROM login WHERE email = '" . $email . "'", $conn->db);

        /* if (isset($_REQUEST['username']) && $_REQUEST['username'] != '') {
          $username = $_REQUEST['username'];
          } else {
          $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'username!</p>";
          }



          if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
          $email = $_REQUEST['email'];
          } else {
          $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'indirizzo email!</p>";
          } */

        if (isset($_REQUEST['nuova_password']) && $_REQUEST['nuova_password'] != '') {
            $nuova_password = $_REQUEST['nuova_password'];
        } else {
           $alert .= "<p align='center' style='color:red'>Attenzione: inserire la password di conferma!</p>";
        }
        if (isset($_REQUEST['citta_domicilio']) && $_REQUEST['citta_domicilio'] != '') {
            $citta_domicilio = $_REQUEST['citta_domicilio'];
        }
        if (isset($_REQUEST['indirizzo_domicilio']) && $_REQUEST['indirizzo_domicilio'] != '') {
            $indirizzo_domicilio = $_REQUEST['citta_domicilio'];
        }

        if (isset($_REQUEST['vecchia_password']) && $_REQUEST['vecchia_password'] != '') {
            $vecchia_password = $_REQUEST['vecchia_password'];
        } else {
           $alert .= "<p align='center' style='color:red'>Attenzione: inserire la password!</p>";
        } 

        if($vecchia_password != $nuova_password) $alert .= "<p align='center' style='color:red'>Attenzione: le password non corrispondono!</p>";

        if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
            $username = $_REQUEST['email'];
            $email = $_REQUEST['email'];

        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'email!</p>";
        }

        if (isset($_REQUEST['phone']) && $_REQUEST['phone'] != '') {
            $phone = $_REQUEST['phone'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il numero di telefono!</p>";
        }
        if (isset($_REQUEST['nome']) && $_REQUEST['nome'] != '') {
            $nome = $_REQUEST['nome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il numero Nome!</p>";
        }

        if (isset($_REQUEST['cognome']) && $_REQUEST['cognome'] != '') {
            $cognome = $_REQUEST['cognome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il cognome!</p>";
        }
        if (isset($_REQUEST['luogo_nascita']) && $_REQUEST['luogo_nascita'] != '') {
            $luogo_nascita = $_REQUEST['luogo_nascita'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il luogo di nascita!</p>";
        }

        if ((isset($_REQUEST['giorno_anagrafica']) && $_REQUEST['giorno_anagrafica'] != '') && (isset($_REQUEST['mese_anagrafica']) && $_REQUEST['mese_anagrafica'] != '') && (isset($_REQUEST['anno_anagrafica']) && $_REQUEST['anno_anagrafica'] != '')) {
            $data_nascita = $_REQUEST['anno_anagrafica'] . "-" . $_REQUEST['mese_anagrafica'] . "-" . $_REQUEST['giorno_anagrafica'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la data di nascita!</p>";
        }

        if (isset($_REQUEST['citta_residenza']) && $_REQUEST['citta_residenza'] != '') {
            $citta_residenza = $_REQUEST['citta_residenza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la citta di residenza!</p>";
        }
        if (isset($_REQUEST['indirizzo_residenza']) && $_REQUEST['indirizzo_residenza'] != '') {
            $indirizzo_residenza = $_REQUEST['indirizzo_residenza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'indirizzo di residenza!</p>";
        }

        if (isset($_REQUEST['codice_fiscale']) && $_REQUEST['codice_fiscale'] != '') {
            $codice_fiscale = $_REQUEST['codice_fiscale'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il codice fiscale!</p>";
        }
        if (isset($alert) && $alert != "") {
            echo $alert;
            break;
        } else
            $alert = "";

             $controllo_email= mysql_query("SELECT user_idd FROM login WHERE email ="."'".$email."'", $conn->db);
          if(mysql_num_rows($controllo_email) >0)
          {
          $alert = "<p align='center' style='color:red'>Attenzione: email esistente!</p>";
          echo $alert;
          break;
          }    
        /*
          $controllo_user= mysql_query("SELECT user_idd FROM login WHERE username ="."'".$username."'", $conn->db);
          if(mysql_num_rows($controllo_user) >0)
          {
          $alert = "<p align='center' style='color:red'>Attenzione: username esistente!</p>";
          echo $alert;
          break;
          } 

        $controllo_cod = mysql_query("SELECT user_id FROM anagrafica WHERE codice_fiscale =" . "'" . $codice_fiscale . "'", $conn->db);
        if (mysql_num_rows($controllo_cod) > 0) {
            $alert = "<p align='center' style='color:red'>Attenzione: codice fiscale esistente!</p>";
            echo $alert;
            break;
        }

         $controllo_email= mysql_query("SELECT user_idd FROM login WHERE email ="."'".$email."'", $conn->db);
          if(mysql_num_rows($controllo_email) >0)
          {
          $alert = "<p align='center' style='color:red'>Attenzione: email esistente!</p>";
          echo $alert;
          break;
          }
         */
        /*
          if($controllo_user)
          {
          $r=mysql_fetch_object($controllo_user);
          if($r->user_idd > 0)
          {
          $alert = "<p align='center' style='color:red'>Attenzione: username esistente!</p>";
          echo $alert;
          break;
          }

          }

          $controllo_cod= mysql_query("SELECT user_id FROM anagrafica WHERE codice_fiscale ="."'".$codice_fiscale."'", $conn->db);
          if($controllo_cod)
          {
          $r=mysql_fetch_object($controllo_cod);
          if($r->user_id > 0)
          {
          $alert = "<p align='center' style='color:red'>Attenzione: codice fiscale esistente!</p>";
          echo $alert;
          break;
          }

          }

          $controllo_email= mysql_query("SELECT user_idd FROM login WHERE email ="."'".$email."'", $conn->db);
          if($controllo_email)
          {
          $r=mysql_fetch_object($controllo_email);
          if($r->user_id > 0)
          {
          $alert = "<p align='center' style='color:red'>Attenzione: email esistente!</p>";
          echo $alert;
          break;
          }

          }

          $controllo_cod= mysql_query("SELECT user_idd FROM anagarfica WHERE codice_fiscale =".$codice_fiscale);
          if(mysql_num_rows($controllo_cod) > 0)
          {
          $alert = "<p align='center' style='color:red'>Attenzione: codice fiscale gia presente nel DB!</p>";
          break;

          }
          $key="SET FOREIGN_KEY_CHECKS=0";
          mysql_query($key, $conn->db);

          $key="SET FOREIGN_KEY_CHECKS=1";
          mysql_query($key, $conn->db);

         */

        $sql_registrazione = "INSERT INTO login (phone,username,email,password,user_idd) VALUES (
		'" . mysql_real_escape_string($phone) . "',
    '" . mysql_real_escape_string($username) . "',
    '" . mysql_real_escape_string($username) . "',
    '" . mysql_real_escape_string($nuova_password) . "',
    NULL)"; 
		

        $result_registrazione = mysql_query($sql_registrazione, $conn->db);

        //$ultimo_id = mysql_insert_id();
        if (!$result_registrazione) {

            die('Errore di inserimento dati : ' . mysql_error());
            break;
        }

        $sql_anagrafica = "INSERT INTO `anagrafica` (`user_id`,`nome`,`cognome`,`luogo_nascita`,`data_nascita`,`citta_residenza`,`indirizzo_residenza`,`citta_domicilio`,`indirizzo_domicilio`,`codice_fiscale`)
         VALUES ('" .  mysql_insert_id() . "',
	'" . mysql_real_escape_string($nome) . "',
	'" . mysql_real_escape_string($cognome) . "',
	'" . mysql_real_escape_string($luogo_nascita) . "',
	'" . $data_nascita . "',
	'" . mysql_real_escape_string($citta_residenza) . "',
	'" . mysql_real_escape_string($indirizzo_residenza) . "',
	'" . mysql_real_escape_string(empty($citta_domicilio) ? "" : $citta_domicilio) . "',
	'" . mysql_real_escape_string(empty($indirizzo_domicilio) ? "" : $indirizzo_domicilio) . "',
	'" . strtoupper($codice_fiscale) . "')";

        $result_anagrafica = mysql_query($sql_anagrafica, $conn->db);

        if (!$result_registrazione || !$result_anagrafica) {

            die('Errore di inserimento dati : ' . mysql_error());
        } else {

            $alert = "<p align='center' style='color:red'>Registrazione avvenuta con successo!</p>";
        }
        if (isset($alert)) {
            echo $alert;

            header("refresh:5;url=login.htm.php");
            echo "<p align='center'>Se il tuo browser non supporta il redirect clicca <a href=\"login.htm.php\">qui</a></p>";
        }
}
?>
<!doctype html>
<html class="">

    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrazione</title>
                <link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="MODULO CV/css/modulocv.css" rel="stylesheet" type="text/css">
<script src="respond.min.js"></script>
 <link href="css/modulocv.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">

        <script language="javascript" type="text/javascript" src="css/datetimepicker.js"></script>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    </head>
    <body>
    <?php
if (isset($_SESSION['logged'])) { ?>
     <div class="gridContainer clearfix">
    <div id="div1" class="fluid">
  <div id="header" class="fluid"><img src="img/header.png"></div>
<div id="logout" class="fluid" style="width:200px;float:right;" ><input class="liste_titre" type="image" title="Logout" value="Logout" src="img/logout.png" name="button_search" onclick="window.location = 'logout.php'"></div>
        
    
<?php } ?>


<!-- INIZIO ANAGRAFICA ----------------------------------------------------------------------------------------------------------------------------------------------  -->
<?php if($alert!="") echo '<div class="row"><div class="col-md-12">'.$alert.'</div></div>'; ?>
<div id="contenitore1" class="fluid ">
 <p><img src="img/registrazione_icona.png" style="float:left; padding:10px;"></p><br>
   <div id="bordotab">

    <?php
    if(isset($_SESSION['user_idd'])) 
    {    
        $user = $_SESSION['user_idd'];

    $sql_login = "SELECT 
							username, 
							password, 
							email, 
							phone
							FROM login 
							WHERE user_idd=" . $user;


    $result_sql_login = mysql_query($sql_login, $conn->db);
    }
    if (isset($result_sql_login)) {
        $row3 = mysql_fetch_array($result_sql_login, MYSQLI_ASSOC);
        $username = $row3['username'];
        $password = $row3['password'];
        $email = $row3['email'];
        $phone = $row3['phone'];
    } else
    ?>
                <form method="post" action=""> 

                    <table width="100%" cellspacing="5">
  <tbody>
    <tr>

          <th height="62" scope="col" class="span">
                            Email:<br>
                            <input type="nome" name="email" class="shadows" style="margin-top:8px;" 
                                   value="<?php if (isset($_REQUEST['email'])) {
                                   echo $_REQUEST['email'];}?>" >
                        </th>

                        <th height="62" scope="col" class="span">
                             Password:<br>
                            <input type="password" name="vecchia_password" class="shadows"  style="margin-top:8px;" 
                                   value="">
                        </th>

                         <th height="62" scope="col" class="span">
                            Conferma Password:<br>
                            <input type="password" class="shadows" name="nuova_password"  style="margin-top:8px;" 
                                   value="">
                        </th>

                         <th height="62" scope="col" class="span">
                            Telefono:<br>
                            <input type="nome" name="phone" class="shadows" maxlength="40" style="margin-top:8px;" 
                                   value="<?php if (isset($_REQUEST['phone'])) {
                echo $_REQUEST['phone'];
            } ?>" onfocus="if (this.value == 'Telefono')
                                               this.value = ''" >
                        </th>
                                            </tr>
  </tbody>
</table>
<br>
                         <table width="100%" cellspacing="5">
  <tbody>
    <tr>

                           <th height="62" scope="col" class="span">
                                Nome:<br>
                                <input type="nome" name="nome" class="shadows" style="margin-top:8px;" 
                                       value="<?php if (isset($_REQUEST['nome'])) {
                echo $_REQUEST['nome'];
            } else echo 'Nome'; ?>" onfocus="if (this.value == 'Nome')
                                                   this.value = ''">
                            </th>

                            <th height="62" scope="col" class="span">
                                Cognome:<br>
                                <input type="nome" name="cognome" class="shadows"  style="margin-top:8px;" 
                                       value="<?php if (isset($_REQUEST['cognome'])) {
                echo $_REQUEST['cognome'];
            } else echo 'Cognome'; ?>" onfocus="if (this.value == 'Cognome')
                                                   this.value = ''">
                            </th>

                            <th height="62" scope="col" class="span">
                                Data Nascita:<br>
                                <select class="shadows" name="mese_anagrafica" style="margin-top:8px;">

                                    <option value="01">Gennaio</option>
                                    <option value="02">Febbraio</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Aprile</option>
                                    <option value="05">Maggio</option>
                                    <option value="06">Giugno</option>
                                    <option value="07">Luglio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Settembre</option>
                                    <option value="10">Ottobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Dicembre</option>
                                </select>
                                <select class="shadows" name="giorno_anagrafica" style="margin-top:8px;">
                                    <option value="01">1</option>
                                    <option value="02">2</option>
                                    <option value="03">3</option>
                                    <option value="04">4</option>
                                    <option value="05">5</option>
                                    <option value="06">6</option>
                                    <option value="07">7</option>
                                    <option value="08">8</option>
                                    <option value="09">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select>

                                <select class="shadows" name="anno_anagrafica" style="margin-top:8px;">
                                    <option value="1993">1993</option>
                                    <option value="1992">1992</option>
                                    <option value="1991">1991</option>
                                    <option value="1990">1990</option>
                                    <option value="1989">1989</option>
                                    <option value="1988">1988</option>
                                    <option value="1987">1987</option>
                                    <option value="1986">1986</option>
                                    <option value="1985">1985</option>
                                    <option value="1984">1984</option>
                                    <option value="1983">1983</option>
                                    <option value="1982">1982</option>
                                    <option value="1981">1981</option>
                                    <option value="1980">1980</option>
                                    <option value="1979">1979</option>
                                    <option value="1978">1978</option>
                                    <option value="1977">1977</option>
                                    <option value="1976">1976</option>
                                    <option value="1975">1975</option>
                                    <option value="1974">1974</option>
                                    <option value="1973">1973</option>
                                    <option value="1972">1972</option>
                                    <option value="1971">1971</option>
                                    <option value="1970">1970</option>
                                    <option value="1969">1969</option>
                                    <option value="1968">1968</option>
                                    <option value="1967">1967</option>
                                    <option value="1966">1966</option>
                                    <option value="1965">1965</option>
                                    <option value="1964">1964</option>
                                    <option value="1963">1963</option>
                                    <option value="1962">1962</option>
                                    <option value="1961">1961</option>
                                    <option value="1960">1960</option>
                                    <option value="1959">1959</option>
                                    <option value="1958">1958</option>
                                    <option value="1957">1957</option>
                                    <option value="1956">1956</option>
                                    <option value="1955">1955</option>
                                    <option value="1954">1954</option>
                                    <option value="1953">1953</option>
                                    <option value="1952">1952</option>
                                    <option value="1951">1951</option>
                                    <option value="1950">1950</option>
                                    <option value="1949">1949</option>
                                    <option value="1948">1948</option>
                                    <option value="1947">1947</option>
                                </select>
                            </th>
                            <th height="62" scope="col" class="span">
                                Citt?? di residenza:<br>
                                <input type="nome" name="citta_residenza" class="shadows"  style="margin-top:8px;" 
                                       value="<?php if (isset($_REQUEST['citta_residenza'])) {
                echo $_REQUEST['citta_residenza'];
            } else echo 'Citta di Residenza'; ?>" onfocus="if (this.value == 'Citta di Residenza')
                                                   this.value = ''">
                            </th>

                           <th height="62" scope="col" class="span">
                                Indrizzo di residenza:<br>
                                <input type="nome" name="indirizzo_residenza" class="shadows" style="margin-top:8px;" 
                                       value="<?php if (isset($_REQUEST['indirizzo_residenza'])) {
                echo $_REQUEST['indirizzo_residenza'];
            } else echo 'Indirizzo di Residenza'; ?>" onfocus="if (this.value == 'Indirizzo di Residenza')
                                                   this.value = ''">
                            </th>

                       </tr>
                     </tbody>
                   </table>
                        <table width="100%" cellspacing="5">
  <tbody>
    <tr>


                            <th height="62" scope="col" class="span">
                                Luogo di nascita:<br>
                                <input type="nome" name="luogo_nascita" class="shadows" maxlength="30" style="margin-top:8px;"
                                       value="<?php if (isset($_REQUEST['luogo_nascita'])) {
                echo $_REQUEST['luogo_nascita'];
            } else echo 'Luogo di Nascita'; ?>" onfocus="if (this.value == 'Luogo di Nascita')
                                                   this.value = ''">
                            </th>

                            <th height="62" scope="col" class="span">
                                Citt?? domicilio:<br>
                                <input type="nome" name="citta_domicilio" class="shadows" maxlength="40" style="margin-top:8px;" 
                                       value="<?php if (isset($_REQUEST['citta_domicilio'])) {
                echo $_REQUEST['citta_domicilio'];
            } else echo 'Citta di Domicilio'; ?>" onfocus="if (this.value == 'Citta di Domicilio')
                                                   this.value = ''">
                            </th>

                            <th height="62" scope="col" class="span">
                                Indrizzo domicilio:<br>
                                <input type="nome" name="indirizzo_domicilio" class="shadows" maxlength="40" style="margin-top:8px;" 
                                       value="<?php if (isset($_REQUEST['indirizzo_domicilio'])) {
                echo $_REQUEST['indirizzo_domicilio'];
            } else echo 'Indirizzo di Domicilio'; ?>" onfocus="if (this.value == 'Indirizzo di Domicilio')
                                                   this.value = ''">
                            </th>

                            <th height="62" scope="col" class="span">
                                Codice Fiscale<br>
                                <input type="nome" name="codice_fiscale" class="shadows" maxlength="16" style="margin-top:8px;" 
                                       value="<?php if (isset($_REQUEST['codice_fiscale'])) {
                echo $_REQUEST['codice_fiscale'];
            } else echo 'Codice Fiscale'; ?>" onfocus="if (this.value == 'Codice Fiscale')
                                                   this.value = ''">
                            </th>
                          </tr>
                        </tbody>
                      </table>
                     <br>
                     <table align="center" width="100%" cellspacing="5">
  <tbody>
    <tr>
                 <th height="62" scope="col" class="span">
                        <input type="image" src="img/registrazione.png" name="action" value="Registrazione">
                        <button type="button" style="border:0; background:none; " value="Annulla" onclick="window.location.href = 'login.htm.php'"><img style="margin-top:-50px;" src="img/annulla2.png"></button>
                    </th>
                  </tr>
                </tbody>
              </table>
                </form>
       
</div> 
</div>