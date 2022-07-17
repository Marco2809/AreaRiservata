<?php
    require_once('class/dbconn.php');
    require_once('class/moduli.class.php');
?>

<?php
$id = intval($_GET['id']);
$db = new Database();
$con = $db->dbConnection();

if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$userModulo = new Modulo();
$userModulesArray = $userModulo->getUsersByModuleId($_GET['id']);

?>

<div class="panel panel-primary filterable" style="margin-bottom:5%;">
    <div class="calendar-heading">
        <div style="padding-left:2%;padding-right:2%;">
            <span class="legenda-title">Permessi Modulo <?php echo $_GET['name']; ?></span>
        </div>
    </div>
<form action="" method="POST">
  <table class="table">
      <thead>
          <tr class="filters">
              <th><input type="text" class="form-control" placeholder="Num."></th>
              <th><input type="text" class="form-control" placeholder="Dipendente"></th>
              <th><input type="text" class="form-control" placeholder="Data di Nascita"></th>
              <th><input type="text" class="form-control" placeholder="Rimuovi"></th>
          </tr>
      </thead>
      <tbody>
<?php
                echo '<input name="id-module" type="hidden" value="' . $_GET['id'] . '">';
              foreach($userModulesArray as $userModule) {
                  echo '<tr>' . 
                       '<td>' . $userModule['user_id'] . '</td>' .
                       '<td>' . $userModule['nome'] . ' ' . $userModule['cognome'] . '</td>' .
                       '<td>' . $userModule['data_nascita'] . '</td>' .
                       '<td><button name="remove-dipendente" type="submit" value="' . $userModule['user_id'] . '"'
                          . ' style="background-color:transparent; border:none; outline:none;">'
                          . '<i class="fa fa-minus-circle fa-2x" style="color:#d90e0e;"></i></button></td>' . 
                       '</tr>';
              }
?>					
      </tbody>
  </table>
</form>
</div>

<script src="assets/js/filter-table.js"></script>

<?php

mysqli_close($con);
?>