<?php
    require_once('assets/php/functions.php');
    require_once('class/dbconn.php');
    require_once('class/user.class.php');
?>

<?php
$id = intval($_GET['id']);
$db = new Database();
$con = $db->dbConnection();

if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$user = new User();
$userArray = $user->getAllExceptGroup($_GET['id']);

?>

<div class="panel panel-primary filterable" style="margin-bottom:5%;">
  <div class="calendar-heading">
              <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Lista Dipendenti</span>
      <div class="pull-right">
        <button class="btn btn-info btn-xs btn-filter button-clear" style="color:#fff;"><span class="fa fa-search fa-2x"></span>
<span class="span-title">&nbsp; Cerca</span>
</button>
      </div>
  </div>
              </div>
<form action="" method="POST">
  <table class="table">
      <thead>
          <tr class="filters">
              <th style="width: 55%;"><input type="text" class="form-control" placeholder="Dipendente"></th>
              <th style="width: 28%;"><input type="text" class="form-control" placeholder="Data di Nascita"></th>
              <th style="width: 17%;"><input type="text" class="form-control" placeholder="Aggiungi"></th>
          </tr>
      </thead>
      <tbody>
<?php
                echo '<input name="id-group" type="hidden" value="' . $_GET['id'] . '">';
              foreach($userArray as $user) {
                  echo '<tr>' .
                       '<td>' . $user['nome'] . ' ' . $user['cognome'] . '</td>' .
                       '<td>' . $user['data_nascita'] . '</td>' .
                       '<td><button name="add-dipendente" type="submit" value="' . $user['user_id'] . '"'
                          . ' style="background-color:transparent; border:none; outline:none;">'
                          . '<i class="fa fa-plus-circle fa-2x" style="color:#77c803;"></i></button></td>' .
                       '</tr>';
              }
?>
      </tbody>
  </table>
</form>
</div>

<?php

mysqli_close($con);
?>
</body>
</html>
