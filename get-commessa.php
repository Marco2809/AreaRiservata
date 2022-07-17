<?php
    require_once('class/dbconn.php');
    require_once('class/commesse.class.php');
?>

<?php
$id = intval($_GET['id']);
$db = new Database();
$con = $db->dbConnection();

if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$userCommessa = new Commessa();
$userCommesseArray = $userCommessa->getMembersByJobId($_GET['id']);
$resp = $userCommessa->getRespByJobId($_GET['id']);

?>

<div class="panel panel-primary filterable" style="margin-bottom:5%;">
  <div class="calendar-heading">
              <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Commessa <?php echo $_GET['name']; ?></span>
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
              <th style="width: 17%;"><input type="text" class="form-control" placeholder="Rimuovi"></th>
          </tr>
      </thead>
      <tbody>
<?php
                echo '<input name="id-job" type="hidden" value="' . $_GET['id'] . '">';
              foreach($userCommesseArray as $userComessa) {

                $color="";
                if(in_array($userComessa['user_id'],$resp[0])) $color="style='background-color: #7FFFD4';";

                  echo '<tr '.$color.'>' .
                       '<td>' . $userComessa['nome'] . ' ' . $userComessa['cognome'] . '</td>' .
                       '<td>' . $userComessa['data_nascita'] . '</td>' .
                       '<td><button name="remove-dipendente" type="submit" value="' . $userComessa['user_id'] . '"'
                          . ' style="background-color:transparent; border:none; outline:none;">'
                          . '<i class="fa fa-minus-circle fa-2x" style="color:#d90e0e;"></i></button></td>' .
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
