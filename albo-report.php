<?php

$supplierDoc = new SupplierDocuments();

// Getting expiry states infos
$expiryStates = $supplierDoc->getAllExpiryStates();
$expiryCounts = array();

foreach($expiryStates as $supplier) {
    $statoInRegola = 0;
    $statoInScadenza = 0;
    $statoScaduto = 0;
    $statoMancante = 0;
    for($i = 2; $i < count($supplier); $i++) {
        switch($supplier[$i]) {
            case '0':
                $statoScaduto++;
                break;
            case '1':
                $statoInScadenza++;
                break;
            case '2':
                $statoInRegola++;
                break;
            case '3':
                $statoMancante++;
                break;
        }
    }
    
    if($statoInRegola != count($supplier) - 2) {
        $currentSupplier = array(
            'supplierId' => $supplier[0],
            'supplierName' => $supplier[1],
            'expired' => $statoScaduto,
            'expiring' => $statoInScadenza,
            'valid' => $statoInRegola,
            'missing' => $statoMancante
        );
        array_push($expiryCounts, $currentSupplier);
    }
}

// Getting approval states infos
$approvalStates = $supplierDoc->getAllApprovalStates();
$approvalCounts = array();

foreach($approvalStates as $supplier) {
    $statoAnomalie = 0;
    $statoInLavorazione = 0;
    $statoNoAnomalie = 0;
    for($i = 2; $i < count($supplier); $i++) {
        switch($supplier[$i]) {
            case '0':
                $statoAnomalie++;
                break;
            case '1':
                $statoInLavorazione++;
                break;
            case '2':
                $statoNoAnomalie++;
                break;
        }
    }
    
    if($statoNoAnomalie != count($supplier) - 2) {
        $currentSupplier = array(
            'supplierId' => $supplier[0],
            'supplierName' => $supplier[1],
            'anomaly' => $statoAnomalie,
            'working' => $statoInLavorazione,
            'valid' => $statoNoAnomalie
        );
        array_push($approvalCounts, $currentSupplier);
    }
}

?>

<section class="">

    <div class="col-md-12">
        
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Stato Scadenza Fornitori</span>
                </div>
            </div>

            <div class="panel-body">
                <div id="chartExpiryDiv" style="width:100%; height:500px;"></div>
            </div>
            
        </div>
        
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Stato Approvazione Fornitori</span>
                </div>
            </div>

            <div class="panel-body">
                <div id="chartApprovalDiv" style="width:100%; height:500px;"></div>
            </div>
            
        </div>
        
    </div>
    
</section>

<!-- AMCharts Resources -->
<script src="/new_area_riservata/assets/js/amcharts/amcharts.js"></script>
<script src="/new_area_riservata/assets/js/amcharts/serial.js"></script>
<script src="/new_area_riservata/assets/js/amcharts/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="/new_area_riservata/assets/js/amcharts/plugins/export/export.css" type="text/css" media="all" />

<script>
var chartExpiry = AmCharts.makeChart( "chartExpiryDiv", {
  "type": "serial",
  "colors": [ 
     "#149137",
     "#FFFB30",
     "#FC3232",
     "#B5B7B6"
   ],
  "depth3D": 20,
  "angle": 30,
  "legend": {
    "horizontalGap": 10,
    "useGraphSettings": true,
    "markerSize": 10
  },
  "dataProvider": [ 
  <?php 
    foreach($expiryCounts as $supplier) {
        printExpiryStates($supplier);
        echo ', ';
    }
  ?>
    ],
  "valueAxes": [ {
    "stackType": "regular",
    "axisAlpha": 0,
    "gridAlpha": 0
  } ],
  "graphs": [ {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "In Regola",
    "type": "column",
    "newStack": true,
    "color": "#000000",
    "valueField": "valid"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "In Scadenza",
    "type": "column",
    "newStack": true,
    "color": "#000000",
    "valueField": "expiring"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Scaduti",
    "type": "column",
    "newStack": true,
    "color": "#000000",
    "valueField": "expired"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Mancanti",
    "type": "column",
    "newStack": true,
    "color": "#000000",
    "valueField": "missing"
  } ],
  "categoryField": "supplier",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  },
  "export": {
    "enabled": true
  }

} );

var chartApproval = AmCharts.makeChart( "chartApprovalDiv", {
  "type": "serial",
  "colors": [ 
     "#149137",
     "#FFFB30",
     "#FC3232"
   ],
  "depth3D": 20,
  "angle": 30,
  "legend": {
    "horizontalGap": 10,
    "useGraphSettings": true,
    "markerSize": 10
  },
  "dataProvider": [ 
  <?php 
    foreach($approvalCounts as $supplier) {
        printApprovalStates($supplier);
        echo ', ';
    }
  ?>
    ],
  "valueAxes": [ {
    "stackType": "regular",
    "axisAlpha": 0,
    "gridAlpha": 0
  } ],
  "graphs": [ {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Approvato",
    "type": "column",
    "newStack": true,
    "color": "#000000",
    "valueField": "valid"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "In Lavorazione",
    "type": "column",
    "newStack": true,
    "color": "#000000",
    "valueField": "working"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Anomalie",
    "type": "column",
    "newStack": true,
    "color": "#000000",
    "valueField": "anomaly"
  } ],
  "categoryField": "supplier",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  },
  "export": {
    "enabled": true
  }
} );
</script>