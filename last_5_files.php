<h3>ULTIMI 5 FILES</h3>

<?php
$database = new Database();
$conn = $database->dbConnection();
$sql = "SELECT DISTINCT files.nome AS nome_file, files.estensione, anagrafica.nome, anagrafica.cognome, files.id_messaggio, messaggi.id_autore
        FROM files
        INNER JOIN file_groups ON file_groups.id_messaggio = files.id_messaggio
        INNER JOIN messaggi ON messaggi.id_messaggio = files.id_messaggio
        INNER JOIN message_groups ON message_groups.id_messaggio = files.id_messaggio
        INNER JOIN anagrafica ON anagrafica.user_id = messaggi.id_autore
        WHERE message_groups.id_gruppo IS NOT NULL OR file_groups.id_destinatario = " . $_SESSION['user_idd'] . "
            OR messaggi.id_autore = " . $_SESSION['user_idd'] . "
        ORDER BY id_messaggio DESC LIMIT 0, 5";

if ( isset($_GET['view']) && $_GET['view'] == "all_files" ) {
    $sql = "SELECT DISTINCT files.nome AS nome_file, files.estensione, anagrafica.nome, anagrafica.cognome, files.id_messaggio, messaggi.id_autore
        FROM files
        INNER JOIN file_groups ON file_groups.id_messaggio = files.id_messaggio
        INNER JOIN messaggi ON messaggi.id_messaggio = files.id_messaggio
        INNER JOIN message_groups ON message_groups.id_messaggio = files.id_messaggio
        INNER JOIN anagrafica ON anagrafica.user_id = messaggi.id_autore
        WHERE message_groups.id_gruppo IS NOT NULL OR file_groups.id_destinatario = " . $_SESSION['user_idd'] . "
            OR messaggi.id_autore = " . $_SESSION['user_idd'];
}

$result = $conn->query($sql);
$num = 0;

while ($obj_mex= $result->fetch_object()) {
    $num++;
    $destinatario = $obj_mex->nome . " " . $obj_mex->cognome;
    $time = $obj_mex->data_creazione;

    print '<div class="desc">';
    print '<div class="col-md-10">';
    print '<div class="thumb">';
    print '<span class="badge bg-theme"><i class="fa fa-file"></i></span>';
    print '</div>';
    print '<div class="details" style="width: 85%;">';
    print '<p><b>' . $obj_mex->nome_file . '.' . $obj_mex->estensione . '</b><br>';
    print '<a style="color: #68dff0;" target="_blank" href="files/' . $obj_mex->id_messaggio . '/' . $obj_mex->nome_file . '.' .
        $obj_mex->estensione . '">File inserito da ' . $destinatario . '</a>';
    print '</p>';
    print '</div>';
    print '</div>';

    if($_SERVER['PHP_SELF']!='/dashboard.php') {
        print '<div class="col-md-2"><h4><a style="color: #68dff0;" target="_blank" href="files/' . $obj_mex->id_messaggio .
            '/' . $obj_mex->nome_file . '.' . $obj_mex->estensione . '"><span class="glyphicon glyphicon-download"></span></a></h4></div>';
    }

    print '</div>';
}

if ($num == 0) {
    print' <div class="desc" >';
    print '<div class="col-md-12" >';
    print '<div class="thumb">';
    print '<span class="badge bg-theme" ><i class="fa fa-file"></i></span>';
    print '</div>';
    print '<div class="details" style="width: 85%;">';
    print '<p><b>Non è presente nessun File</b><br>';
    print '</p>';
    print '</div>';
    print '</div>';
    print '</div>';
}
?>
