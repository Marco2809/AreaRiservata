<?php

if(isset($_REQUEST['id_mese'])) {
    $id_mese = $_REQUEST['id_mese'];
    if(substr($id_mese, 0, 1) == '0') {
        $id_mese = substr($id_mese, 1, 1);
    }
}

if(isset($_REQUEST['id_anno'])) {
    $id_anno = $_REQUEST['id_anno'];
}

if(isset($_POST['next'])) {
    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }
		
    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }
            
    if($id_mese=="01") $id_mese = 1;
    if($id_mese=="02") $id_mese = 2;
    if($id_mese=="03") $id_mese = 3;
    if($id_mese=="04") $id_mese = 4;
    if($id_mese=="05") $id_mese = 5;
    if($id_mese=="06") $id_mese = 6;
    if($id_mese=="07") $id_mese = 7;
    if($id_mese=="08") $id_mese = 8;
    if($id_mese=="09") $id_mese = 9;
       
    if($id_mese == 12){
        $id_anno++;
        $id_mese = 1;
    }
    else {
        $id_mese= $id_mese + 1;
    }
   
 }
 
 if(isset($_POST['prev'])) {
    
    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }
		
    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if($id_mese == 1) {
        $id_mese = 12;
        $id_anno = $id_anno - 1;
    } else {
        $id_mese = $id_mese - 1;
    }

 }
         
if(!isset($id_mese)) {
	$_POST['id_mese'] = date("m");
    $id_mese = date("m");
} 

if(!isset($id_anno)) {
	$_POST['id_anno'] = date("Y");
    $id_anno = date("Y");
}
        
if($id_mese == 1) {
    $mese_cal="Gennaio";
} else if($id_mese == 2) {
    $mese_cal="Febbraio";
} else if($id_mese == 3) {
    $mese_cal="Marzo";
} else if($id_mese == 4) {
    $mese_cal="Aprile";
} else if($id_mese == 5) {
    $mese_cal="Maggio";
} else if($id_mese == 6) {
    $mese_cal="Giugno";
} else if($id_mese == 7) {
    $mese_cal="Luglio";
} else if($id_mese == 8) {
    $mese_cal="Agosto";
} else if($id_mese == 9) {
    $mese_cal="Settembre";
} else if($id_mese == 10) {
    $mese_cal="Ottobre";
} else if($id_mese == 11) {
    $mese_cal="Novembre";
} else if($id_mese == 12) {
    $mese_cal="Dicembre";
}


?>

<section class="">
    <center>
        <div class="panel panel-primary">
            <div class="calendar-heading">
                <form action="costi_aziendali.php?action=costi_aziendali" method="POST">
                    <button type="submit" name="prev" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-left fa-2x" aria-hidden="true"></i></button>

                    <span class="mese-title"><?php echo $mese_cal . " " . $id_anno; ?></span>

                    <button type="submit" name="next" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-right fa-2x" aria-hidden="true"></i></button>
 					<input type="hidden" name="action" value="costi_aziendali">
                    <input type="hidden" name="id_mese" value="<?php echo $id_mese;?>">
                    <input type="hidden" name="id_anno" value="<?php echo $id_anno;?>">
                </form>
            </div>
        </div>
    </center>
</section>

<section class="">

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="calendar-heading">
			<div style="padding-left:2%;padding-right:2%;">
				<span class="legenda-title">Lista Commesse</span>
                <!--<div class="pull-right">
                 <button class="btn btn-info btn-xs btn-filter button-clear" style="color:#fff;"><span class="fa fa-search fa-2x"></span>
        <span class="span-title">&nbsp; Cerca</span>
        </button>
                </div>-->
				<div class="pull-right">
      <a class="btn btn-success btn-xs btn-filter button-clear" 
         href="<?php echo 'export-costi-aziendali.php?mese=' . $id_mese . '&anno=' . $id_anno; ?>"
         style="color:#fff;"><span class="fa fa-file-excel-o fa-2x"></span>
      <span class="span-title">&nbsp; Download Excel</span>
      </a>
      </div>
            </div>
			</div>
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th><input type="text" class="form-control" placeholder="Commessa"></th>
                        <th><input type="text" class="form-control" placeholder="Ore Totali"></th>
						<th><input type="text" class="form-control" placeholder="Costo Totale"></th>
                    </tr>
                </thead>
                <tbody>
                    
<?php
                    $infoHRClass = new InfoHR();
                    $infoList = $infoHRClass->getAllCostiAziendaliByMonth($id_mese,$id_anno);
					//print_r($infoList);
                    for($i=0;$i<count($infoList);$i+=3){
						
						$commessa = new Commessa();
						$id_commessa = $commessa->getIdCommessaByName($infoList[$i]);
						
                        echo '<tr>
							<td><a href="./costi_aziendali.php?action=dettaglio&id='.$id_commessa.'&id_mese='.$id_mese.'&id_anno='.$id_anno.'">'.$infoList[$i].'</td>
                                <td>' . number_format($infoList[$i+1],2,".",""). '</td>
								<td>' . number_format($infoList[$i+2],2,".",""). '€</td>
                            </tr>';
                    }
?>
					
                </tbody>
            </table>
        </div>
    </div>
	</div>
</section>
			
<!--FINE CONTENUTO PRINCIPALE-->
