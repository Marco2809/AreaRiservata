
<?php
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/product/stock/class/entrepot.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT . '/fourn/class/fournisseur.product.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/myclass/myFamily.php';
require_once DOL_DOCUMENT_ROOT . '/product/myclass/myAsset.php';
require_once DOL_DOCUMENT_ROOT . '/product/myclass/myMagazzino.php';
require_once DOL_DOCUMENT_ROOT . '/product/myclass/myTracking.php';
require_once DOL_DOCUMENT_ROOT . '/product/myclass/myUtilizzati.php';
require_once DOL_DOCUMENT_ROOT . '/product/myclass/myPostinotelematico.php';
require_once DOL_DOCUMENT_ROOT . '/custom/ultimateqrcode/lib/ultimateqrcode.lib.php';
require_once DOL_DOCUMENT_ROOT . '/custom/ultimateqrcode/includes/phpqrcode/qrlib.php';



$langs->load("products");
$langs->load("stocks");
$langs->load("suppliers");

$action = GETPOST('action');
$action_crea = GETPOST('action_crea');
$action_agg_2 = GETPOST('action_crea');

$sref = GETPOST("sref");
$sbarcode = GETPOST("sbarcode");
$snom = GETPOST("snom");
$sall = GETPOST("sall");
$type = GETPOST("type", "int");
$search_sale = GETPOST("search_sale");
$search_categ = GETPOST("search_categ", 'int');
$tosell = GETPOST("tosell");
$tobuy = GETPOST("tobuy");
$fourn_id = GETPOST("fourn_id", 'int');
$catid = GETPOST('catid', 'int');

$sortfield = GETPOST("sortfield", 'alpha');
$sortorder = GETPOST("sortorder", 'alpha');
$page = GETPOST("page", 'int');


$title = "Inventario";

llxHeader('', $title, $helpurl, '');

//valori da input 
$codice_famiglia = !empty($_GET['cod_famiglia']) ? $_GET['cod_famiglia'] : null;

$n_asset_censire = !empty($_GET['dati']['numero_asset']) ? $_GET['dati']['numero_asset'] : null;
$ddt = !empty($_GET['dati']['num_ddt1']) ? $_GET['dati']['num_ddt1'] : null; // separato
$data = !empty($_GET['dati']['data_ddt1']) ? $_GET['dati']['data_ddt1'] : null;
$fattura = !empty($_GET['dati']['fattura']) ? $_GET['dati']['fattura'] : null;
$data_fattura = !empty($_GET['dati']['data_fattura']) ? $_GET['dati']['data_fattura'] : null;
$id_magazzino = !empty($_GET['id_magazzino']) ? $_GET['id_magazzino'] : null;
$id_regione = !empty($_GET['id_regione']) ? $_GET['id_regione'] : null;


$root = DOL_URL_ROOT;
print '<div class="fiche">';
print '<div class="tabs" data-type="horizontal" data-role="controlgroup">';
print '<a class="tabTitle">
            
<img border="0" title="" alt="" src="' . $root . '"/theme/eldy/img/object_product.png">
Movimentazione asset
</a>';

print '<div class="inline-block tabsElem">
<a id="card" class="tab inline-block" href="' . $root . '/product/inventario_collocazione.php?mainmenu=products&leftmenu=product&type=5" data-role="button">Inventario</a>
</div>';

print '<div class="inline-block tabsElem">
<a id="price" class="tabactive tab inline-block" href="' . $root . '/product/inventario_censimento.php?mainmenu=products&leftmenu=product&type=6&id=3" data-role="button">Censimento</a>
</div>';

print '</div>';

if ($action == "Aggiorna")
{
    $codice_asset = !empty($_GET['dati_asset']['cod_asset']) ? $_GET['dati_asset']['cod_asset'] : null;
    $codice_famiglia = !empty($_GET['dati_asset']['cod_famiglia']) ? $_GET['dati_asset']['cod_famiglia'] : null;

    $corridoio = !empty($_GET['dati_asset']['corridoio']) ? $_GET['dati_asset']['corridoio'] : null;
    $scaffali = !empty($_GET['dati_asset']['scaffali']) ? $_GET['dati_asset']['scaffali'] : null;
    $ripiano = !empty($_GET['dati_asset']['ripiano']) ? $_GET['dati_asset']['ripiano'] : null;
    $pt_number = !empty($_GET['dati_asset']['pt_number']) ? $_GET['dati_asset']['pt_number'] : null;
    $serial_number = !empty($_GET['dati_asset']['serial_number']) ? $_GET['dati_asset']['serial_number'] : null;
    $imei_number = !empty($_GET['dati_asset']['imei_number']) ? $_GET['dati_asset']['imei_number'] : null;
    $n_asset_censire = !empty($_GET['dati_asset']['n_asset_censire']) ? $_GET['dati_asset']['n_asset_censire'] : null;


    $ddt = !empty($_GET['dati_asset']['num_ddt1']) ? $_GET['dati_asset']['num_ddt1'] : null; // separato
    $data = !empty($_GET['dati_asset']['data_ddt1']) ? $_GET['dati_asset']['data_ddt1'] : null;
    $fattura = !empty($_GET['dati_asset']['fattura']) ? $_GET['dati_asset']['fattura'] : null;
    $data_fattura = !empty($_GET['dati_asset']['data_fattura']) ? $_GET['dati_asset']['data_fattura'] : null;


    $sql = "UPDATE " . MAIN_DB_PREFIX . "asset SET "
            . "corridoio =" . "'" . mysql_real_escape_string($corridoio) . "'"
            . ",scaffali=" . "'" . mysql_real_escape_string($scaffali) . "'"
            . ",ripiano=" . "'" . mysql_real_escape_string($ripiano) . "'"
            . ",pt_number=" . "'" . mysql_real_escape_string($pt_number) . "'"
            . ",serial_number=" . "'" . mysql_real_escape_string($serial_number) . "'"
            . ",imei_number=" . "'" . ($imei_number) . "'"
            . " WHERE  cod_asset LIKE " . "'" . mysql_real_escape_string($codice_asset) . "'";
    $res = $db->query($sql);
    //$action = "Crea";
}


//form
print '<form method="get" action="' . $_SERVER["PHP_SELF"] . '">';
print '<table class="border" width="100%">';
$sql = "SELECT DISTINCT * FROM llx_product ORDER BY ref ASC";
$result = $db->query($sql);
$select_prod = "<select name='cod_famiglia'>";
$select_prod.="<option value=''></option>";
$selected = " ";
while ($obj_prod = $db->fetch_object($result))
{
    if ($codice_famiglia == $obj_prod->ref)
        $selected = " selected";
    else
        $selected = " ";
    $select_prod.= '<option value=' . $obj_prod->ref . $selected . '>' . $obj_prod->ref . '</option>';
}
$select_prod .= "</select>";
print '<tr><td class="fieldrequired"><strong>Cod Famiglia</strong></td><td>' . $select_prod . '</td></tr>';


print '<tr><td class="fieldrequired" width="20%">' . "Numero asset da censire" . '</td>';
print '<td><input name="dati[numero_asset]" size="10" maxlength="8" value="' . $n_asset_censire . '">';
print '</td></tr>';


print '<tr><td class="fieldrequired" width="20%">' . "DDT" . '</td>';
print '<td> <input type="text" name="dati[num_ddt1]"  value="' . $ddt . '" ></td>';
print '</tr>';

print'<script>
        $(function() {
        $( "#datepicker" ).datepicker();
         });
       </script>';
print '<tr><td class="fieldrequired" width="20%">' . "Data" . '</td>';
print '<td><input  name="dati[data_ddt1]" id="datepicker" size="40" value="' . $data . '">';
print '</td></tr>';

print '<tr><td class="fieldrequired" width="20%">' . "Fattura" . '</td>';
print '<td><input name="dati[fattura]"  value="' . $fattura . '">';
print '</td></tr>';

print'<script>
      $(function() {
      $( "#datepicker2" ).datepicker();
      });
      </script>';
print '<tr><td class="fieldrequired">' . "Data fattura" . '</td><td colspan="3">';
print '<input type="text" name="dati[data_fattura]" id="datepicker2" value="' . $data_fattura . '" ></td>';



$sql = "SELECT DISTINCT rowid,label FROM llx_entrepot ORDER BY label ASC";
$result = $db->query($sql);
$select_mag = "<select name='id_magazzino'>";
$select_mag.="<option value=''></option>";
$selected = " ";
while ($obj_prod = $db->fetch_object($result))
{
    if ($obj_prod->rowid == 3)
        $selected = " selected";
    else
        $selected = " ";

    $select_mag.= '<option value=' . $obj_prod->rowid . $selected . '>' . $obj_prod->label . '</option>';
}
$select_mag .= "</select>";
print '<tr><td class="fieldrequired"><strong>Magazzino</strong></td><td>' . $select_mag . '</td></tr>';

$select_regione = "<select name='id_regione'>";
$select_regione .="<option value=''></option>";
$sql = "SELECT  rowid,nom FROM llx_c_regions";
$sql .=" WHERE (rowid>=301 and rowid<=320) ";
$res = $db->query($sql);
$selected = " ";
while ($obj_reg = $db->fetch_object($res))
{
    if ($obj_reg->rowid == 307)
        $selected = " selected";
    else
        $selected = " ";

    $select_regione.= '<option value=' . $obj_reg->rowid . $selected . '>' . $obj_reg->nom . '</option>';
}
$select_regione .= "</select>";
print '<tr><td class="fieldrequired"><strong>Regione</strong></td><td>' . $select_regione . '</td></tr>';

print '</table>';
print '<br>';
if ($action != "Aggiorna")
{
    print '<center>';
    if ($action == "Crea") // se ha inserito il pulsante crea, non mostrare più il bottone
    {
        print '<input type="submit" class="button" name="action"  value="' . "Termina Procedura" . '"> '; //mostra solo gli asset da creare
    } else //se invece non ha premuto il pulsante crea
    {
        print '<input type="submit" class="button" name="action"  value="' . "Crea" . '"> '; //mostra il pulsante per consetire di creare.
    }
    print '</center>';
} else
{
    print '<center><input type="submit" class="button" name="action"  value="' . "Termina Procedura" . '"> </center>';
}
print '</form>';

if ($action == "Aggiorna")
{
    visualizza_asset_creati($codice_famiglia);
}

if ($action == "Crea")
{

    if (empty($codice_famiglia))
    {
        print '<script>alert("Selezionare la famiglia");</script>';
        $path = DOL_URL_ROOT . '/product/inventario_censimento.php?mainmenu=products&dati[num_ddt1]=' . $ddt;
        print '<META HTTP-EQUIV="refresh" CONTENT="1; URL=' . $path . '">';
        return;
    }
    if (empty($n_asset_censire))
    {
        print '<script>alert("Inserire la quantità di asset da inserire");</script>';
        $path = DOL_URL_ROOT . '/product/inventario_censimento.php?mainmenu=products&dati[num_ddt1]=' . $ddt;
        print '<META HTTP-EQUIV="refresh" CONTENT="1; URL=' . $path . '">';
        return;
    }
    $asset_creati = censisci($codice_famiglia, $n_asset_censire, $ddt, $data, $fattura, $data_fattura, $id_magazzino, $id_regione);
    if ($asset_creati)
    {

        visualizza_asset_creati($codice_famiglia);
    }
}
if ($action == "Termina Procedura")
{
    $codice_famiglia = !empty($_GET['cod_famiglia']) ? $_GET['cod_famiglia'] : null;
    global $user;
    $user_id = $user->id;
    $sql = "DELETE FROM tmp_inventario_censimento WHERE codice_famiglia LIKE " . "'" . $codice_famiglia . "'"
            . " AND id_user = " . $user_id;
    $eliminato = $db->query($sql);
    if ($eliminato)
    {
        $path = DOL_URL_ROOT . '/product/inventario_censimento.php?mainmenu=products';
        print '<META HTTP-EQUIV="Refresh" CONTENT="0; url=' . $path . '">';
    }
}

function visualizza_asset_creati($codice_famiglia)
{
    global $db, $user;
    $user_id = $user->id;
    $sql = "SELECT * FROM llx_asset as a "
            . " inner JOIN tmp_inventario_censimento AS tmp "
            . " ON a.cod_asset = tmp.codice_asset "
            . " WHERE tmp.id_user like  '" . $user_id . "'"
            . "   and codice_famiglia like " . "'" . $codice_famiglia . "'";
    $res = $db->query($sql);
    if ($res)
    {
        while ($obj_asset = $db->fetch_object($res))
        {
            $n_asset_censire = $db->num_rows($res);
            print '<br>';
            print '<form method="get" action="' . $_SERVER["PHP_SELF"] . '">';
            print '<div class="tabBar">';
            print '<table class="border" width="100%">';

            // $img_path = "http://localhost/dolibarr/htdocs/custom/ultimateqrcode/temp/5f0f0d7712f60306acc11f88943bdf0f.png";
            // $img_path = DOL_URL_ROOT . "/custom/ultimateqrcode/temp/5f0f0d7712f60306acc11f88943bdf0f.png";
            $img_path = DOL_URL_ROOT . '/custom/ultimateqrcode/temp/' . md5($obj_asset->cod_asset) . ".png";

            /*
            $htmlqrcode = '';
            $htmlqrcode.='<tr>';
            $htmlqrcode.='<center><img src="' . $img_path . '" /></center>';
            $htmlqrcode.='</td>';
            $htmlqrcode.='</tr>';
            print $htmlqrcode;
            print '</center>';
             
             */
           $url = DOL_URL_ROOT.'/custom/ultimateqrcode/asset_qrcode.php?qrcode_codice='.$obj_asset->cod_asset;
            
            $link = '<center> <a target="_blank" href="' . $url . '">' . "Stampa Qrcode" . '</a></td> </center>';
            print $link;
            print '<br>';




            print '<tr>';
            print '<td width="15%">Codice asset</td>';
            print '<td class="nobordernopadding">' . $obj_asset->cod_asset . '</td>';
            print '</tr>';
            /*
              print '<tr>';
              print '<td width="15%">DDT</td>';
              print '<td class="nobordernopadding">' . $obj_asset->num_ddt1 . '</td>';
              print '</tr>';

              print '<tr>';
              print '<td width="15%">Data</td>';
              print '<td class="nobordernopadding">' . $obj_asset->data_ddt1 . '</td>';
              print '</tr>';

              print '<tr>';
              print '<td width="15%">Fattura</td>';
              print '<td class="nobordernopadding">' . $obj_asset->fattura . '</td>';
              print '</tr>';

              print '<tr>';
              print '<td width="15%">Data fattura</td>';
              print '<td class="nobordernopadding">' . $obj_asset->data_fattura . '</td>';
              print '</tr>';
             */
            $obj_magazzino = new magazzino($db);
            $magazzino_nome = $obj_magazzino->getMagazzino($obj_asset->id_magazzino);
            $magazzino_nome = $magazzino_nome[0]['label'];
            print '<tr>';
            print '<td width="15%">Magazzino</td>';
            print '<td class="nobordernopadding">' . $magazzino_nome . '</td>';
            print '</tr>';

            $statutarray_fisico = array('1' => "Giacenza Magazzino", '2' => "In uso", '3' => "In transito", '4' => "Laboratorio riparazione", "5" => "Dismesso", "6" => "Presso Cliente", '7' => "Giacenza Tecnico");
            $statutarray_fisico = array_flip($statutarray_fisico);
            $str_stato_fisico = array_search($obj_asset->stato_fisico, $statutarray_fisico);

            $statutarray_tecnico = array('1' => "Nuovo", '2' => "Ricondizionato", '3' => "Guasto", '4' => "Sconosciuto", '5' => "Utilizzato per Intervento", '6' => "Dismesso", '7' => "Venduto");
            $statutarray_tecnico = array_flip($statutarray_tecnico);
            $str_stato_tecnico = array_search($obj_asset->stato_tecnico, $statutarray_tecnico);
            print '<tr>';
            print '<td width="15%">Stato fisico</td>';
            print '<td class="nobordernopadding">' . $str_stato_fisico . '</td>';
            print '</tr>';

            print '<tr>';
            print '<td width="15%">Stato tecnico</td>';
            print '<td class="nobordernopadding">' . $str_stato_tecnico . '</td>';
            print '</tr>';

            $corridoio = $obj_asset->corridoio;
            $scaffali = $obj_asset->scaffali;
            $ripiano = $obj_asset->ripiano;

            print '<tr>';
            print '<td width="15%">Corridoio</td>';
            print '<td><input name="dati_asset[corridoio]" size="20" value="' . $corridoio . '">';
            print '</td>';
            print '</tr>';

            print '<tr>';
            print '<td width="15%">Scaffali</td>';
            print '<td><input name="dati_asset[scaffali]" size="20" value="' . $scaffali . '">';
            print '</td>';
            print '</tr>';

            print '<tr>';
            print '<td width="15%">Ripiano</td>';
            print '<td><input name="dati_asset[ripiano]" size="20" value="' . $ripiano . '">';
            print '</td>';
            print '</tr>';

            $pt_number = $obj_asset->pt_number;
            print '<tr>';
            print '<td width="15%">PT number</td>';
            print '<td><input name="dati_asset[pt_number]"  onkeyup="showHint(this.value)" size="20" value="' . $pt_number . '">';
            print '</td>';
            print '<td><span id="txtHint"></span></td>';
            print '</tr>';

            $serial_number = $obj_asset->serial_number;
            print '<tr>';
            print '<td width="15%">Serial number</td>';
            print '<td><input name="dati_asset[serial_number]" size="20" value="' . $serial_number . '">';
            print '</td>';
            print '</tr>';

            $imei_number = $obj_asset->imei_number;
            print '<tr>';
            print '<td width="15%">Imei_number</td>';
            print '<td><input name="dati_asset[imei_number]" size="20" value="' . $imei_number . '">';
            print '</td>';
            print '</tr>';


            print ' <input type="hidden" name="dati_asset[id_magazzino]" value="' . $obj_asset->id_magazzino . '"> ';
            print ' <input type="hidden" name="dati_asset[cod_famiglia]" value="' . $obj_asset->cod_famiglia . '"> ';
            print ' <input type="hidden" name="dati_asset[n_asset_censire]" value="' . $n_asset_censire . '"> ';
            print ' <input type="hidden" name="dati_asset[cod_asset]" value="' . $obj_asset->cod_asset . '"> ';
            print ' <input type="hidden" name="dati_asset[id_regione]" value="' . $obj_asset->id_regione . '"> ';
            print ' <input type="hidden" name="dati_asset[stato_tecnico]" value="' . $obj_asset->stato_tecnico . '"> ';
            print ' <input type="hidden" name="dati_asset[stato_fisico]" value="' . $obj_asset->stato_fisico . '"> ';

            print ' <input type="hidden" name="dati_asset[num_ddt1]" value="' . $obj_asset->num_ddt1 . '"> ';
            print ' <input type="hidden" name="dati_asset[data_ddt1]" value="' . $obj_asset->data_ddt1 . '"> ';
            print ' <input type="hidden" name="dati_asset[fattura]" value="' . $obj_asset->fattura . '"> ';
            print ' <input type="hidden" name="dati_asset[data_fattura]" value="' . $obj_asset->data_fattura . '"> ';


            print '</table>';

            print '</div>';
            print '<center><input type="submit" class="button" name="action"  value="' . "Aggiorna" . '"> </center>';
            print '</form>';
        }
    }
}


function createQRcode($codice_asset)
{

    $tempDir = DOL_DOCUMENT_ROOT . '/custom/ultimateqrcode/temp/';
    // generating
    // we building raw data
    $codeContents = $codice_asset;

    $qrcode_codice = md5($codeContents);

    $filename = $tempDir . md5($codeContents) . '.png';
    // generating
    QRcode::png($codeContents, $filename, QR_ECLEVEL_L, 2);
}

function censisci($cod_famiglia, $n_asset_censire, $ddt, $data, $fattura, $data_fattura, $id_magazzino, $id_regione)
{

    if (empty($n_asset_censire))
    {
        return false;
    }
    if (empty($cod_famiglia))
    {
        return false;
    }
    global $db, $user;

    $user_id = $user->id;
    $sql = "CREATE TABLE  tmp_inventario_censimento(id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, codice_asset VARCHAR(250),codice_famiglia VARCHAR(250),  id_user VARCHAR(100))";
    $result = $db->query($sql);

    $query = "SELECT label FROM llx_product WHERE ref = '" . $cod_famiglia . "'";
    $result = $db->query($query);
    $etichetta = "";
    if ($result)
    {
        $obj_label = $db->fetch_object($result);
        $etichetta = $obj_label->label;
        $etichetta = str_replace(array('"', '\''), '', $etichetta);
    }

    for ($a = 0; $a < $n_asset_censire; $a++)
    {
        $obj_assets = new asset($db);
        $assets_famiglia = $obj_assets->getAssetFromFamily($cod_famiglia);
        $numerico = 0;
        if (!empty($assets_famiglia))
        {
            $n = count($assets_famiglia);
            $max = 0;
            for ($i = 0; $i < $n; $i++)
            {
                $my_codAsset = $assets_famiglia[$i]['cod_asset'];
                $numerico = substr($my_codAsset, -6);
                $numerico = (int) $numerico;
                if ($max == 0)
                {
                    $max = $numerico;
                }
                if ($numerico > $max)
                {
                    $max = $numerico;
                }
            }
        }
        $max++;
        $prog_num = str_pad($max, 6, "0", STR_PAD_LEFT);
        $anno_corrente = date("Y");
        $newCodAsset = $cod_famiglia . "-" . $anno_corrente . $prog_num;

        $stato_fisico = 1;
        $stato_tecnico = 1;

        $magazzino = $id_magazzino;
        $regione = $id_regione;
        $data_creazione = date("d-m-y");
        // verificare che il record non esiste già
        //CONTROLLO SE l'asset gia esiste
        $sql = "INSERT INTO " . MAIN_DB_PREFIX . "asset (";
        $sql.= "cod_famiglia";
        $sql.= ", cod_asset";
        $sql.= ", label";
        $sql.= ", stato_fisico";
        $sql.= ", stato_tecnico";
        $sql.= ", id_regione";
        $sql.= ", id_magazzino";
        $sql.= ", data_creazione";
        $sql.= ", num_ddt1";
        $sql.= ", data_ddt1";
        $sql.= ", fattura";
        $sql.= ", data_fattura";

        $sql.= ") VALUES (";
        $sql.= "'" . $cod_famiglia . "'";
        $sql.= ", '" . $newCodAsset . "'";
        $sql.= ", '" . $etichetta . "'";
        $sql.= ", '" . $stato_fisico . "'";
        $sql.= ", '" . $stato_tecnico . "'";
        $sql.= ", '" . $regione . "'";

        $sql.= ", '" . $magazzino . "'";
        $sql.= ", '" . $data_creazione . "'";
        $sql.= ", '" . $ddt . "'";
        $sql.= ", '" . $data . "'";
        $sql.= ", '" . $fattura . "'";
        $sql.= ", '" . $data_fattura . "'";

        $sql.= ")";
        $result = $db->query($sql);
        if ($result)
        { // se è stato salvato il record// occorre aggiornare il campo cod_asset(facendo la concatenazione cod_faiglia-l'ultimo id
            //$lastIdAsset = $result->lastId;
            // aggiorno il contatore della famiglia (incremento o decremento)
            //ricavo il valore della scorta (attuale)
            $query = "SELECT LAST_INSERT_ID() as last_id;";
            $res = $db->query($query);
            $lastIdAsset = $db->fetch_object($res);
            $lastIdAsset = $lastIdAsset->last_id;

            $query = "SELECT f.stock as scorta ";
            $query .= " FROM " . MAIN_DB_PREFIX . "product as f ";
            $query .= "WHERE f.ref = " . "'" . $cod_famiglia . "'";
            $result = $db->query($query);
            if ($result)
            {
                $obj = $db->fetch_object($result);
                $tot_scorte = (int) $obj->scorta;
            }
            $tot_scorte++; // incremento poiché ho aggiunto un asset
            if ($stato_fisico == 2 || $stato_fisico == 5)
            { // se lo stato fisico è in uso o dismesso
                $tot_scorte = (int) ($tot_scorte - 1); // decremento poiche lo stato fisico è in uso o dismesso
            }
            $query = "UPDATE " . MAIN_DB_PREFIX . "product ";
            $query .= "SET stock = " . $tot_scorte;
            $query .= " WHERE ref = " . "'" . $cod_famiglia . "'";
            $result = $db->query($query);
            if ($result != false)
            { // l'asset non è stato creato allora ricarica la pagina
                $sql = "SELECT * ";
                $sql .= " FROM " . MAIN_DB_PREFIX . "asset as a ";
                $sql .= " WHERE id = " . $lastIdAsset;
                $res = $db->query($sql);
                if ($res)
                {
                    $obj_asset = $db->fetch_object($res);
                    $code_newAsset = $obj_asset->cod_asset;
                    $obj_track = new myTracking($db);
                    $array_tracking = array();
                    $array_tracking['azione'] = "creato";
                    $array_tracking['user'] = $user->id;
                    $array_tracking['riferimento'] = $num_ticket_interno;
                    $array_tracking['codice_asset'] = $code_newAsset;
                    $array_tracking['codice_famiglia'] = $cod_famiglia;
                    $array_tracking['etichetta'] = $etichetta;
                    $obj_track->nuovo_tracking($array_tracking); // insertimento tracking massivamente

                    if ($stato_fisico == 2) // solo se lo stato fisico dell'asset è in uso
                    {
                        $obj_utilizzati = new myUtilizzati($db);
                        $obj_utilizzati->nuovo_utilizzati($array_tracking);
                    }
                }
            }
            //occorre aggiungere nella tabella tmp_inventario_censimento
            $query = "INSERT INTO  tmp_inventario_censimento (";
            $query.= "codice_asset";
            $query.= ", codice_famiglia";
            $query.= ", id_user";
            $query.= ") VALUES (";
            $query.= "'" . $newCodAsset . "'";
            $query.= ", '" . $cod_famiglia . "'";
            $query.= ", '" . $user_id . "'";
            $query.= ")";
            $ris = $db->query($query);

            createQRcode($newCodAsset);
        } else
        { //altrimenti
            //altrimenti, se l'asset è stato creato, ridirezione nella pagina elenco asset
        }
    }
    return true; // gli asset sono stati creati
}
?>
<script>
    function showHint(str) {
        if (str.length == 0) {
            document.getElementById("txtHint").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "ric_ptnumber.php?q=" + str, true);
            xmlhttp.send();
        }
    }
</script>


