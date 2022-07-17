<?php

if(isset($_GET['code'])) {
    $code = $_GET['code'];
}

// Gestione eventuale inserimento nuovo utente
if(isset($_POST['username'], $_POST['p'])) {

    $idDuplicate = null;
    $stmtSelect = $conn->prepare("SELECT user_supplier.user_id FROM user_supplier INNER JOIN login "
            . "ON user_supplier.user_id = login.user_idd WHERE login.username = ? LIMIT 1");
    $stmtSelect->bind_param('s', $_POST['username']);
    $stmtSelect->execute();
    $stmtSelect->store_result();
    $stmtSelect->bind_result($idDuplicate);
    $stmtSelect->fetch();

    if($stmtSelect->num_rows == 0) {
        $username = $_POST['username'];
        $password = hash('sha512', $_POST['p']);
        $supplierId = $_POST['supplier'];
        
        $stmtInsert = $conn->prepare("INSERT INTO login (username, password, email) "
                . "VALUES (?, ?, ?)");
        $stmtInsert->bind_param("sss", $username, $password, $username);
        if(!$stmtInsert->execute()) {
            trigger_error("Errore! " . $db->error, E_USER_WARNING);
        }
        $stmtInsert->close();
        
        $stmtSelectId = $conn->prepare("SELECT LAST_INSERT_ID()");
        $stmtSelectId->execute();
        $stmtSelectId->store_result();
        $stmtSelectId->bind_result($userId);
        $stmtSelectId->fetch();
        
        $userSupplier = new UserSupplier();
        $userSupplier->setID($userId);
        $userSupplier->setSupplierID($supplierId);
        $userSupplier->insertRelation();
        
        $code = 1;
    } else {
        header('Location: /albo-fornitori.php?action=nuovo-utente&error=1');
    }

}

// Ricavo user presenti nel DB
$idResult = null;
$supplierIdResult = null;
$usernameResult = null;

$idArray = array();
$supplierIdArray = array();
$usernameArray = array();

$stmt = $conn->prepare("SELECT user_supplier.user_id, user_supplier.supplier_id, "
        . "login.username FROM user_supplier INNER JOIN login ON user_supplier.user_id = login.user_idd "
        . "ORDER BY login.username DESC");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($idResult, $supplierIdResult, $usernameResult);

while($stmt->fetch()) {
    array_push($idArray, $idResult);
    array_push($supplierIdArray, $supplierIdResult);
    array_push($usernameArray, $usernameResult);
}
        
?>

<div class="container">
    
    <div class="col-md-10">
        
    <?php
    if($code == 1) { ?>
        <div class="error-message">
            <div class="alert alert-success alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-ok"></span> <strong>Creazione effettuata!</strong> Utente creato correttamente.
            </div>
        </div>
    <?php } ?>
    
    <div class="spacing"></div>
    
    <table id="suppliers-list" class="tablesorter">
        
        <thead style="margin-bottom: 2%;">
            <tr>
                <th style="padding-top:1%;padding-bottom:1%;"><span class="legenda-title">Username</span></th>
                <th style="padding-top:1%;padding-bottom:1%;""><span class="legenda-title">Azioni</span></th>
            </tr>
        </thead>
        
        <tfoot class="supplier-table-foot">
            <tr>
                <td colspan="10">
                    <div class="pager">
                        <div class="pager-center">
                            <span class="prev">
                                <img src="assets/img/prev.png" />Precedente
                            </span>
                            <span class="pagecount"></span>
                            &nbsp;<span class="next">Successivo
                                <img src="assets/img/next.png">
                            </span>
                            <div class="pager-right pull-right">
                                # per pagina:
                                <a href="#" class="current">25</a> |
                                <a href="#">50</a> |
                                <a href="#">100</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
        
        <tbody style="margin-top:8%;">
                <?php
                for($i=0; $i<count($usernameArray); $i++) {
                    echo '<tr>';
                    echo '<td class="user-profile-user">' . $usernameArray[$i] . '</td>';
                    echo '<td class="user-profile-actions">';
                    
                    if($roleArray[$i] == 'Fornitore') {
                        echo '<a href="supplier-profile.php?id=' . $supplierIdArray[$i] . '" class="btn btn-info btn-xs">'
                           . '<span class="glyphicon glyphicon-file"></span> Scheda</a> ';
                    }
                    
                    echo '<a href="albo-fornitori.php?action=modifica-utente&id=' . $idArray[$i] . '" class="btn btn-primary btn-xs">'
                       . '<span class="glyphicon glyphicon-pencil"></span> Modifica</a> '
                       . '<a href="assets/php/albo-elimina-utente.php?id=' . $idArray[$i] . '" class="btn btn-danger btn-xs">'
                       . '<span class="glyphicon glyphicon-trash"></span> Elimina</a></td>';
                    echo '</tr>';
                }
                ?>
        </tbody>
        
    </table>
    
    </div>
    
</div>

<script type="text/javascript">
    $(document).ready(function() { 
        $("#users-list").tablesorter(); 
    }); 
</script>