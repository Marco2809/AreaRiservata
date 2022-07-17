<?php
/* Copyright (C) 2002-2006 Rodolphe Quiedeville  <rodolphe@quiedeville.org>
 * Copyright (C) 2004      Eric Seigne           <eric.seigne@ryxeo.com>
 * Copyright (C) 2004-2012 Laurent Destailleur   <eldy@users.sourceforge.net>
 * Copyright (C) 2005      Marc Barilley / Ocebo <marc@ocebo.com>
 * Copyright (C) 2005-2013 Regis Houssin         <regis.houssin@capnetworks.com>
 * Copyright (C) 2006      Andre Cianfarani      <acianfa@free.fr>
 * Copyright (C) 2010-2012 Juanjo Menent         <jmenent@2byte.es>
 * Copyright (C) 2012      Christophe Battarel   <christophe.battarel@altairis.fr>
 * Copyright (C) 2013      Florian Henry		  	<florian.henry@open-concept.pro>
 * Copyright (C) 2013      Cédric Salvador       <csalvador@gpcsolutions.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file       htdocs/compta/facture/list.php
 *	\ingroup    facture
 *	\brief      Page to create/see an invoice
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/modules/facture/modules_facture.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/discount.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/invoice.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
if (! empty($conf->commande->enabled)) require_once DOL_DOCUMENT_ROOT.'/commande/class/commande.class.php';
if (! empty($conf->projet->enabled))
{
	require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
}

$langs->load('bills');
$langs->load('companies');
$langs->load('products');
$langs->load('main');

$sall=trim(GETPOST('sall'));
$projectid=(GETPOST('projectid')?GETPOST('projectid','int'):0);

$id=(GETPOST('id','int')?GETPOST('id','int'):GETPOST('facid','int'));  // For backward compatibility
$ref=GETPOST('ref','alpha');
$socid=GETPOST('socid','int');
$action=GETPOST('action','alpha');
$confirm=GETPOST('confirm','alpha');
$lineid=GETPOST('lineid','int');
$userid=GETPOST('userid','int');

if (! $sortorder) $sortorder='DESC';
if (! $sortfield) $sortfield='f.datef';
$limit = $conf->liste_limit;


$search_user = GETPOST('search_user','int');
$search_sale = GETPOST('search_sale','int');
$day	= GETPOST('day','int');
$month	= GETPOST('month','int');
$year	= GETPOST('year','int');
$day_i	= GETPOST('dayi','int');
$month_i	= GETPOST('monthi','int');
$year_i	= GETPOST('yeari','int');

$data_inizio=$_POST['data_inizio'];

if($data_inizio!=""){
    $datei=explode("/",$data_inizio);
    $day_i = $datei[0];
    $month_i = $datei[1];
    $year_i = $datei[2];
   
} else {
    $day_i = "01";
    $month_i = "01";
    $year_i = "2015";
}

$data_fine=$_POST['data_fine'];
if($data_fine!=""){
    $datef=explode("/",$data_fine);
    $day = $datef[0];
    $month = $datef[1];
    $year = $datef[2];
} else {
    $day= date("d");
    $month= date("m");
    $year= date("Y");
}

$filtre	= GETPOST('filtre');


$object=new Facture($db);

// Initialize technical object to manage hooks of thirdparties. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('invoicelist'));

$now=dol_now();


/*
 * Actions
 */

$parameters=array('socid'=>$socid);
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks

/*
 * View
 */


llxHeader('',$langs->trans('Bill'),'EN:Customers_Invoices|FR:Factures_Clients|ES:Facturas_a_clientes');

$form = new Form($db);
$formother = new FormOther($db);
$formfile = new FormFile($db);
$bankaccountstatic=new Account($db);
$facturestatic=new Facture($db);

    $sql = 'SELECT * FROM  '.MAIN_DB_PREFIX.'commesse ORDER BY nome ASC';
$resql = $db->query($sql);
if ($resql)
{
    $commesse="<option value=''></option>";
    while($objp = @@$db->fetch_object($resql)){
        $commesse.="<option value=".$objp->rowid.">".$objp->codice."</option>";
    }
}

  $sql = 'SELECT * FROM  '.MAIN_DB_PREFIX.'centri_ricavo ORDER BY nome ASC';
$resql = $db->query($sql);
if ($resql)
{
    $centri_ricavo="<option value=''></option>";
    while($objp = @$db->fetch_object($resql)){
        $nome_ricavo= str_replace(" ", "€$", $objp->nome);
        $centri_ricavo.="<option value=".$nome_ricavo.">".$objp->nome."</option>";
    }
}

  $sql = 'SELECT * FROM  '.MAIN_DB_PREFIX.'centri_costo ORDER BY nome ASC';
$resql = $db->query($sql);
if ($resql)
{
    $centri_costo="<option value=''></option>";
    while($objp = @$db->fetch_object($resql)){
        $nome_costo= str_replace(" ", "€$", $objp->nome);
        $centri_costo.="<option value=".$nome_costo.">".$objp->nome."</option>";
       
    }
}
    
if($_POST['cliente']!=""&&$_POST['fornitore']!="") $_POST['fornitore']="";

$sql = 'SELECT * FROM  '.MAIN_DB_PREFIX.'societe ORDER BY nom ASC';
$resql = $db->query($sql);
if ($resql)
{
    
    $clienti="<option value=''></option>";
    $fornitori="<option value=''></option>";
    while($objp = @$db->fetch_object($resql)){
        if($objp->rowid=="277") $objp->nom.=" - X15OD7";
        if($objp->rowid=="224") $objp->nom.=" - 1FI4LS";
        if($objp->rowid=="7") $objp->nom.=" - KALHJG";
        if($objp->rowid=="194") $objp->nom.=" - 5UM9ZR";
        if($objp->client==1 || $objp->client==3) $clienti.="<option value=".$objp->rowid.">".$objp->nom."</option>";
        if($objp->fournisseur==1) $fornitori.="<option value=".$objp->rowid.">".$objp->nom."</option>";
    }
}
if(isset($_POST['cliente'])||isset($_POST['fornitore'])){
$sql = 'SELECT * FROM  '.MAIN_DB_PREFIX.'societe where rowid ='.$_POST['cliente'].$_POST['fornitore'];
$resql = $db->query($sql);
if ($resql)
{
    while($objp = @$db->fetch_object($resql)){
        if($objp->client==1 || $objp->client==3) $nome_cliente=$objp->nom;
        if($objp->fournisseur==1) $nome_fornitore=$objp->nom;
    }
}
}

if(isset($_POST['attive'])&&!isset($_POST['passive'])){
    
$sql = 'SELECT f.rowid,f.facnumber, f.fk_soc, f.datef, f.commessa, f.paye, f.date_lim_reglement,s.nom,s.client,s.fournisseur,f.note_public,fe.num_ordine ';
$sql.= ' ,fd.fk_facture,fd.description, fd.tva_tx, fd.total_ht, fd.total_tva, fd.total_ttc ';
$sql.= ' FROM '.MAIN_DB_PREFIX.'facture as f,'.MAIN_DB_PREFIX.'societe as s,'.MAIN_DB_PREFIX.'facturedet as fd,'.MAIN_DB_PREFIX.'facture_extrafields as fe ';
$sql.= ' WHERE';
$sql.= ' f.fk_soc=s.rowid ';
$sql.= ' AND fd.fk_facture=f.rowid ';
$sql.= ' AND fe.fk_object=f.rowid ';

if(isset($_POST['centro_ricavo'])) $sql.= ' AND f.centro_ricavo="'.str_replace("€$"," ",$_POST['centro_ricavo']).'"';
if (isset($_POST['cliente'])&&$_POST['cliente']!="") $sql.= ' AND f.fk_soc="'.$_POST['cliente'].'"';
if ($month > 0)
{
    $sql.= " AND f.datef BETWEEN '". $year_i. $month_i. $day_i."' AND '".$year. $month. $day."'";

}
if ($_POST['scaduta'] =="si")
{
    $sql.= " AND f.paye='0' AND f.date_lim_reglement BETWEEN f.date_lim_reglement AND '". date("Y"). date("m"). date("d")."' ";

} else if ($_POST['scaduta'] =="no")
{
    $sql.= " AND f.date_lim_reglement < '".date("Y")."-". date("m")."-". date("d")."'";

} 

if ($_POST['pagato'] =="si")
{
    $sql.= " AND f.paye='1' ";

} else if ($_POST['pagato'] =="no"&&$_POST['scaduta']!="si")
{
    $sql.= " AND f.paye='0' ";

} 

if (isset($_POST['commessa'])&&$_POST['commessa'] !="")
{
    $sql.= " AND f.commessa='".$_POST['commessa']."' ";

} 

$sql.= " ORDER BY f.datef ASC";
//print $sql;
} else if(!isset($_POST['attive'])&&isset($_POST['passive'])){
    $sql = 'SELECT ff.rowid, ff.ref, ff.ref_supplier, ff.fk_soc, ff.datef, ff.commessa, ff.paye, ff.date_lim_reglement,s.nom,s.client,s.fournisseur,ffe.fornitore_nordine as num_ordine ';
$sql.= ' ,fd.fk_facture_fourn,fd.description, fd.total_ht, fd.tva as total_tva, fd.total_ttc ';
$sql.= ' FROM '.MAIN_DB_PREFIX.'facture_fourn as ff,'.MAIN_DB_PREFIX.'societe as s,'.MAIN_DB_PREFIX.'facture_fourn_det as fd, '.MAIN_DB_PREFIX.'facture_fourn_extrafields as ffe ';
$sql.= ' WHERE';
$sql.= ' ff.fk_soc=s.rowid';
$sql.= ' AND fd.fk_facture_fourn=ff.rowid ';
$sql.= ' AND ffe.fk_object=ff.rowid ';
if(isset($_POST['centro_costo'])&&$_POST['centro_costo']!="") $sql.= ' AND ff.centro_costo="'.str_replace("€$"," ",$_POST['centro_costo']).'"';
if (isset($_POST['fornitore'])&&$_POST['fornitore']!="") $sql.= ' AND ff.fk_soc="'.$_POST['fornitore'].'"';
if ($month > 0)
{
    $sql.= " AND ff.datef BETWEEN '". $year_i. $month_i. $day_i."' AND '".$year. $month. $day."'";

}
if ($_POST['scaduta'] =="si")
{
    $sql.= " AND ff.paye='0' AND ff.date_lim_reglement BETWEEN ff.date_lim_reglement AND '". date("Y"). date("m"). date("d")."' ";

} else if ($_POST['scaduta'] =="no")
{
    $sql.= " AND ff.date_lim_reglement < '".date("Y")."-". date("m")."-". date("d")."'";

} 

if ($_POST['pagato'] =="si")
{
    $sql.= " AND ff.paye='1' ";

} else if ($_POST['pagato'] =="no"&&$_POST['scaduta']!="si")
{
    $sql.= " AND ff.paye='0' ";

} 

if (isset($_POST['commessa'])&&$_POST['commessa'] !="")
{
    $sql.= " AND ff.commessa='".$_POST['commessa']."' ";

} 

$sql.= " ORDER BY ff.datef ASC";

//print $sql;
} else {
    $sql = '(SELECT f.rowid,f.facnumber, f.fk_soc, f.datef, f.commessa, f.paye, f.date_lim_reglement,f.note_public,fe.num_ordine ';
//$sql.= ' a.bank';
$sql.=  ', fsoc.nom, fsoc.rowid as socrowid, fsoc.client,fsoc.fournisseur ';
$sql.= ' ,fd.fk_facture,fd.description, fd.total_ht, fd.total_tva, fd.total_ttc ';
$sql.= ' FROM '.MAIN_DB_PREFIX.'facture as f, '.MAIN_DB_PREFIX.'societe as fsoc, '.MAIN_DB_PREFIX.'facturedet as fd, '.MAIN_DB_PREFIX.'facture_extrafields as fe ';
$sql.= ' WHERE ';
$sql.= ' f.fk_soc=fsoc.rowid ';
$sql.= ' AND fd.fk_facture=f.rowid ';
$sql.= ' AND fe.fk_object=f.rowid ';
if(isset($_POST['centro_ricavo'])) $sql.= ' AND f.centro_ricavo="'.str_replace("€$"," ",$_POST['centro_ricavo']).'"';
if ($month > 0)
{
    $sql.= " AND f.datef BETWEEN '". $year_i. $month_i. $day_i."' AND '".$year. $month. $day."' ";

}
if ($_POST['scaduta'] =="si")
{
    $sql.= " AND f.paye='0' AND ff.paye='0' AND f.date_lim_reglement BETWEEN f.date_lim_reglement AND '". date("Y"). date("m"). date("d")."' ";

} else if ($_POST['scaduta'] =="no")
{
    $sql.= " AND f.date_lim_reglement < '".date("Y")."-". date("m")."-". date("d")."'";

} 

if ($_POST['pagato'] =="si")
{
    $sql.= " AND f.paye='1'";

} else if ($_POST['pagato'] =="no"&&$_POST['scaduta']!="si")
{
    $sql.= " AND f.paye='0'";

} 

if (isset($_POST['commessa'])&&$_POST['commessa'] !="")
{
    $sql.= " AND f.commessa='".$_POST['commessa']."' ";

} 
$sql.=")";

$sql.= " UNION ";

$sql .= '(SELECT ff.rowid,ff.ref,ff.ref_supplier,ff.fk_soc, ff.datef, ff.commessa, ff.paye, ff.date_lim_reglement,ff.note_public, ffe.fornitore_nordine as num_ordine ';
$sql.=  ', ffsoc.nom, ffsoc.rowid as socrowid,ffsoc.client,ffsoc.fournisseur ';
$sql.= ' ,fd.fk_facture_fourn,fd.description, fd.total_ht, fd.tva as total_tva, fd.total_ttc ';
$sql.= ' FROM '.MAIN_DB_PREFIX.'facture_fourn as ff, '.MAIN_DB_PREFIX.'societe as ffsoc, '.MAIN_DB_PREFIX.'facture_fourn_det as fd,'.MAIN_DB_PREFIX.'facture_fourn_extrafields as ffe ';
$sql.= ' WHERE ';
$sql.= ' ff.fk_soc=ffsoc.rowid ';
$sql.= ' AND fd.fk_facture_fourn=ff.rowid ';
$sql.= ' AND ffe.fk_object=ff.rowid ';
if(isset($_POST['centro_costo'])) $sql.= ' AND ff.centro_costo="'.str_replace("€$"," ",$_POST['centro_costo']).'"';
//$sql.= ' AND f.fk_soc="'.$_POST['cliente'].'"';
if ($month > 0)
{
    $sql.= " AND ff.datef BETWEEN '". $year_i. $month_i. $day_i."' AND '".$year. $month. $day."' ";

}
if ($_POST['scaduta'] =="si")
{
    $sql.= " AND ff.paye='0' AND ff.date_lim_reglement BETWEEN ff.date_lim_reglement AND '". date("Y"). date("m"). date("d")."' ";

} else if ($_POST['scaduta'] =="no")
{
    $sql.= " AND ff.date_lim_reglement < '".date("Y")."-". date("m")."-". date("d")."'";

} 

if ($_POST['pagato'] =="si")
{
    $sql.= " AND ff.paye='1'";

} else if ($_POST['pagato'] =="no"&&$_POST['scaduta']!="si")
{
    $sql.= " AND ff.paye='0'";

} 



if (isset($_POST['commessa'])&&$_POST['commessa'] !="")
{
    $sql.= " AND ff.commessa='".$_POST['commessa']."' ";

} 
$sql.=")";
 
$sql.= " ORDER BY datef ASC";

}

$nbtotalofrecords = 0;
if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST))
{
	$result = $db->query($sql);
	$nbtotalofrecords = $db->num_rows($result);
}
//print $sql;
$resql = $db->query($sql);
//dol_print_error($db, $object->error);

if ($resql)
{
    
    $num = $db->num_rows($resql);

    $param='&socid='.$socid;
    $param.='&mainmenu=accountancy';
    if ($month)              $param.='&month='.$month;
    if ($year)               $param.='&year=' .$year;
    //print_barre_liste($langs->trans('BillsCustomers').' '.($socid?' '.$soc->nom:''),$page,$_SERVER["PHP_SELF"],$param,$sortfield,$sortorder,'',$num,$nbtotalofrecords);

    $i = 0;
 
    
    
   
    print '<form method="post" action="'.$_SERVER["PHP_SELF"]."?mainmenu=accountancy".'">'."\n";
    print '<table>';
    /*print '<tr><td>Data Inizio:</td><td><input id="data_inizio" name="data_inizio" type="text" size="15" maxlength="11" value="" onchange="dpChangeDay(\'data_inizio\',\'dd/MM/yyyy\'); ">';
    print '<button id="reButton" type="button" class="dpInvisibleButtons" onclick="showDP(\'/dolibarr/htdocs/core/\',\'data_inizio\',\'dd/MM/yyyy\',\'it_IT\');"><img src="/dolibarr/htdocs/theme/eldy/img/object_calendarday.png" border="0" alt="Seleziona una data" title="Seleziona una data" class="datecallink"></button>';
    print '<input type="hidden" id="reday" name="reday_inizio" value="">';
    print '<input type="hidden" id="remonth" name="remonth_inizio" value="">';
    print '<input type="hidden" id="reyear" name="reyear_inizio" value="">';
    print '<button class="dpInvisibleButtons datenowlink" id="reButtonNow" type="button" name="_useless" value="Now" onclick="resetDP(\'/dolibarr/htdocs/core/\',\'data_inizio\',\'dd/MM/yyyy\',\'it_IT\');">Adesso</button>';
    print '</td></tr>';
    print '<tr><td>Data Fine:</td><td><input id="data_fine" name="data_fine" type="text" size="15" maxlength="11" value="" onchange="dpChangeDay(\'data_fine\',\'dd/MM/yyyy\'); ">';
    print '<button id="reButton" type="button" class="dpInvisibleButtons" onclick="showDP(\'/dolibarr/htdocs/core/\',\'data_fine\',\'dd/MM/yyyy\',\'it_IT\');"><img src="/dolibarr/htdocs/theme/eldy/img/object_calendarday.png" border="0" alt="Seleziona una data" title="Seleziona una data" class="datecallink"></button>';
    print '<input type="hidden" id="reday_fine" name="reday_fine" value="">';
    print '<input type="hidden" id="remonth_fine" name="remonth_fine" value="">';
    print '<input type="hidden" id="reyear_fine" name="reyear_fine" value="">';
    print '<button class="dpInvisibleButtons datenowlink" id="reButtonNow1" type="button" name="_useless1" value="Now" onclick="resetDP(\'/dolibarr/htdocs/core/\',\'data_fine\',\'dd/MM/yyyy\',\'it_IT\');">Adesso</button>';
    print '</td></tr>';

    */
    print '<tr><td class="fieldrequired">' . $langs->trans('Data Inizio') . '</td><td colspan="2">';
    $datefacture = dol_mktime(12, 0, 0, $month_i, $day_i, $year_i);
    $form->select_date($datefacture ? $datefacture : $data_inizio, 'data_inizio', '', '', '', "add", 1, 1);
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans('Data Fine') . '</td><td colspan="2">';
    $datefacturef = dol_mktime(12, 0, 0, $month, $day, $year);
    $form->select_date($datefacturef ? $datefacturef : $data_fine, 'data_fine', '', '', '', "add", 1, 1);
    print '</td></tr>';
    
    print '<tr><td class="">' . $langs->trans('Fatture Attive') . '</td><td colspan="2">';
    print '<input type="checkbox" name="attive" id="attive" onclick="func_attive()">';
    print '</td></tr>';
    
    print '<tr><td class="">' . $langs->trans('Fatture Passive') . '</td><td colspan="2">';
    print '<input type="checkbox" name="passive" id="passive" onclick="func_passive()">';
    print '</td></tr>';
    
     print '<tr><td class="">' . $langs->trans('Cliente') . '</td><td colspan="2">';
    print '<select name="cliente" id="tr_cliente" disabled="disabled">'.$clienti.'</select>';
    print '</td></tr>';
    
    
     print '<tr ><td class="">' . $langs->trans('Fornitore') . '</td><td colspan="2">';
    print '<select name="fornitore" id="tr_fornitore" disabled="disabled">'.$fornitori.'</select>';
    print '</td></tr>';
    
    print '<tr><td class="">' . $langs->trans('Commessa') . '</td><td colspan="2">';
    print '<select name="commessa" >'.$commesse.'</select>';
    print '</td></tr>';
    
     print '<tr><td class="">' . $langs->trans('Centri di Ricavo') . '</td><td colspan="2">';
    print '<select name="centri_ricavo" id="tr_ricavo" disabled="disabled">'.$centri_ricavo.'</select>';
    print '</td></tr>';
    
     print '<tr><td class="">' . $langs->trans('Centri di Costo') . '</td><td colspan="2">';
    print '<select name="centro_costo" id="tr_costo" disabled="disabled">'.$centri_costo.'</select>';
    print '</td></tr>';
    
     /*print '<tr><td class="">' . $langs->trans('Pagata') . '</td><td colspan="2">';
    print '<select name="pagato" ><option value=""></option><option value="no">No</option><option value="si">Si</option></select>';
    print '</td></tr>';
    
     print '<tr><td class="">' . $langs->trans('Scaduta') . '</td><td colspan="2">';
    print '<select name="scaduta" ><option value=""></option><option value="no">No</option><option value="si">Si</option></select>';
    print '</td></tr>';
    */
        print '<tr><td><input type="submit" name="genera" value="Genera"></td></tr>';
    print '</table>';
    print '</form>';
    if(isset($_POST['genera'])){
    print'<form action="./report_xml_export.php" method="post">';
    print '<table><tr><td align="right"><input type="submit" value="Esporta">'
    . '<input type="hidden" name="nome_fornitore" value="'.$nome_fornitore.'">'
            . '<input type="hidden" name="nome_cliente" value="'.$nome_cliente.'">'
            . '<input type="hidden" name="data_inizio" value="'.$data_inizio.'">'
            . '<input type="hidden" name="cliente" value="'.$_POST['cliente'].'">'
            . '<input type="hidden" name="fornitore" value="'.$_POST['fornitore'].'">'
            . '<input type="hidden" name="data_fine" value="'.$data_fine.'">'
            . '<input type="hidden" name="scaduta" value="'.$_POST['scaduta'].'">'
            . '<input type="hidden" name="pagato" value="'.$_POST['pagato'].'">'
            . '<input type="hidden" name="commessa" value="'.$_POST['commessa'].'">'
            . '<input type="hidden" name="centro_ricavo" value="'.$_POST['centro_ricavo'].'">'
            . '<input type="hidden" name="centro_costo" value="'.$_POST['centro_costo'].'">';
            if(isset($_POST['attive'])) print '<input type="hidden" name="attive" value="true">';
            if(isset($_POST['passive'])) print '<input type="hidden" name="passive" value="true">';
            print '</td></tr></table>';
    print '</form>';
    
    print '<table class="liste" border="1" width="100%">';

    print '<tr class="liste_titre"><td colspan="11" align="center">Estratto Conto '.$nome_cliente.$nome_fornitore." - da ".$_POST['data_inizio']." a ".$_POST['data_fine'].'</td></tr>';
    
  print '<tr class="liste_titre">'
            . '<td class="nowrap" align="center" colspan="1">Numero</td>'
            . '<td class="nowrap" align="center" colspan="1">Data</td>'
            . '<td class="nowrap" align="center" colspan="1">Scadenza</td>'
            . '<td class="nowrap" align="center" colspan="1">Imponibile</td>'
            . '<td class="nowrap" align="center" colspan="1">IVA</td>'
          . '<td class="nowrap" align="center" colspan="1">Descrizione</td>'
            . '<td class="nowrap" align="center" colspan="1">Cliente</td>'
            . '<td class="nowrap" align="center">Commessa</td>'
          . '<td class="nowrap" align="center">Num Ordine</td>'
            . '<td class="nowrap" align="center">Pagato</td>'
            . '<td class="nowrap" align="center">Data Pagamento</td>'
            . '</tr>';
     /*print '<tr class="liste_titre"><td align="center">DATA</td>'
            . '<td class="nowrap" align="center">Entrate<br>Cassa</td>'
            . '<td class="nowrap" align="center">Uscita<br>Cassa</td>'
            . '<td class="nowrap" align="center">Saldo<br>Cassa</td>'
            . '<td class="nowrap" align="center">Entrate<br>Cassa</td>'
            . '<td class="nowrap" align="center">Uscita<br>Cassa</td>'
            . '<td class="nowrap" align="center">Saldo<br>Cassa</td>'
              . '<td class="nowrap" align="center">Saldo<br>Cassa</td>'
            . '</tr>';
*/
  //$saldo_generale=$saldo_cassa+$saldo_sella+$saldo_unicredit+$saldo_bnl+$saldo_ricaricabile;
        while($objp = @$db->fetch_object($resql))
        {
            

    $sql_cod_commessa = 'SELECT codice FROM llx_commesse WHERE nome ="'.$objp->commessa.'"';
    $res_sql_cod_commessa = $db->query($sql_cod_commessa);
$objcom = $db->fetch_object($res_sql_cod_commessa);
        $codice_commessa=$objcom->codice;

            $totale+=$objp->total_ht;
            if($objp->paye=="1"&&($objp->client=="1"||$objp->client=="3")){
       
        $sqlpay = 'SELECT * ';
$sqlpay.= ' FROM '.MAIN_DB_PREFIX.'paiement_facture as pf,'.MAIN_DB_PREFIX.'paiement as p';
$sqlpay.= ' WHERE ';
$sqlpay.= ' pf.fk_paiement=p.rowid AND pf.fk_facture='.$objp->rowid;
        $sqlpay.= ' ORDER BY p.datep ASC LIMIT 1';
        $resqlpay = $db->query($sqlpay);
        //print $sqlpay;
        
//dol_print_error($db, $object->error);
if ($resqlpay){
        $objpay = @$db->fetch_object($resqlpay);
        $data_pagamento=$objpay->datep;
} 
    } else if($objp->paye=="1"&&$objp->fournisseur=="1"){
       
        $sqlpay = 'SELECT * ';
$sqlpay.= ' FROM '.MAIN_DB_PREFIX.'paiementfourn_facturefourn as pf,'.MAIN_DB_PREFIX.'paiement as p';
$sqlpay.= ' WHERE ';
$sqlpay.= ' pf.fk_paiementfourn=p.rowid AND pf.fk_facturefourn='.$objp->rowid;
        $sqlpay.= ' ORDER BY p.datep ASC LIMIT 1';
        $resqlpay = $db->query($sqlpay);
        //print $sqlpay;
//dol_print_error($db, $object->error);
if ($resqlpay){
        $objpay = @$db->fetch_object($resqlpay);
        $data_pagamento=$objpay->datep;
} 
    } 
    
    else {
        
        $data_pagamento="";
        
    }
            
           if(isset($_POST['cliente'])&&$_POST['cliente']!=""){
               
            $sql_acconto = 'SELECT SUM(amount) as totale FROM  '.MAIN_DB_PREFIX.'paiement_facture WHERE fk_facture='.$objp->rowid;
$resql_acconto = $db->query($sql_acconto);
if ($resql_acconto)
{
    while($obj_acconto = @$db->fetch_object($resql_acconto)){
       $acconto=$obj_acconto->totale;
       $saldo=$objp->total_ttc-$obj_acconto->totale;
       $totale_estratto+=$saldo;
       $totale_pagamento+=$objp->total_ttc;
    }
}
           } else if (isset($_POST['fornitore'])&&$_POST['fornitore']!=""){
               $sql_acconto = 'SELECT SUM(amount) as totale FROM  '.MAIN_DB_PREFIX.'paiementfourn_facturefourn WHERE fk_facturefourn='.$objp->rowid;
$resql_acconto = $db->query($sql_acconto);
if ($resql_acconto)
{
    while($obj_acconto = @$db->fetch_object($resql_acconto)){
       $acconto=$obj_acconto->totale;
       $saldo=$objp->total_ttc-$obj_acconto->totale;
       $totale_estratto+=$saldo;
       $totale_pagamento+=$objp->total_ttc;
    }
           }
           }
            $var=!$var;
            //$saldo_generale+=$objp->amount;

            $datelimit=$db->jdate($objp->datelimite);

            print '<tr '.$bc[$var].'>';


			// Numero Documento
			print '<td class="nowrap">';
			print $objp->facnumber.$objp->ref_supplier;
			print '</td>';
                        
                        // Data Documento
			print '<td class="nowrap">';
			print date("d-m-Y", strtotime($objp->datef) );
			print '</td>';
                        
                          // Scadenza Documento
			print '<td class="nowrap">';
			print date("d-m-Y", strtotime($objp->date_lim_reglement) );
			print '</td>';
                        
                        // Imponibile Documento
			print '<td class="nowrap">';
			print price($objp->total_ht,0,$langs);
			print '</td>';
                        
                        // IVA Documento
                        if($objp->paye==1) $acconto=0;
			print '<td class="nowrap">';
			print price($objp->total_tva,0,$langs);
			print '</td>';
                        
                         // Descrizione
                     
			print '<td style="max-width:200px;">';
			print $objp->description;
			print '</td>';
                        
                         // Cliente 
			print '<td class="nowrap">';
			print $objp->nom;
			print '</td>';
                        
                        // Commessa Documento
                        if($objp->paye==1) $saldo=0;
			print '<td class="nowrap">';
			print $codice_commessa;
			print '</td>';
                        
                        // Ordine Documento
			print '<td style="max-width:150px;">';
			print $objp->num_ordine;
			print '</td>';
                        
                        // Pagamento
                        if($objp->paye==1) $pagato="Si";
                        else $pagato="No";
                        
			print '<td class="nowrap">';
			print $pagato;
			print '</td>';
                        
                         // Data Pagamento
			print '<td class="nowrap">';
			if($data_pagamento!="") print date("d-m-Y", strtotime($data_pagamento) );
			print '</td>';

 print '</tr>';
        
        //print '<td align="right">'.price($saldo_generale,0,$langs).'</td>';
        //print '<td align="center">'.$objp->note.'</td>';

        
    }
                           /*
                          // Totale
			print "<tr '.$bc[$var].'>";
			print '<td colspan="5" class="nowrap" align="right" style="color:black; font-weight:bold; ">Totale:</td>';
                        print '<td colspan="3" align="left" class="nowrap" style="color:black;">'.price($totale,0,$langs).'</td>';
                        
			print '</tr>';
                            
                            */
    print "</table>\n";
    print "</form>\n";
    }
    $db->free($resql);
print'<script>
function func_attive() {
if(document.getElementById("attive").checked&&document.getElementById("passive").checked){
    document.getElementById("tr_cliente").disabled = false;
    document.getElementById("tr_ricavo").disabled = false;
    
    } else if(document.getElementById("attive").checked){
document.getElementById("tr_cliente").disabled = false;
document.getElementById("tr_fornitore").disabled = true;
document.getElementById("tr_ricavo").disabled = false;
} else {
document.getElementById("tr_cliente").disabled = true;
document.getElementById("tr_ricavo").disabled = true;
}
};
function func_passive() {
if(document.getElementById("passive").checked&&document.getElementById("attive").checked){
    document.getElementById("tr_fornitore").disabled = false;
    document.getElementById("tr_costo").disabled = false;
    
} else if(document.getElementById("passive").checked){
document.getElementById("tr_cliente").disabled = true;
document.getElementById("tr_fornitore").disabled = false;
document.getElementById("tr_costo").disabled = false;
} else {
document.getElementById("tr_fornitore").disabled = true;
document.getElementById("tr_costo").disabled = true;
}
};
</script>';
    $db->free($resql);


llxFooter();
$db->close();
}