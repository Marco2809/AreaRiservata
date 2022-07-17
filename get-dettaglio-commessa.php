<?php
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

mysqli_select_db($con, "ajax_demo");
$sql = "SELECT * FROM commesse WHERE id_commessa = " . $id;
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
?>

<form action="" method="POST">
<div class="modal-dialog modal-lg">
   <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title">DETTAGLIO COMMESSA</h4>
      </div>
      <div class="modal-body">
       <form action="" method="POST">
      <table class="table" style="margin-top:1%;">
      <tbody>

      <tr>
      <td class="td-intestazione">Commessa:</td>
      <td>
      <input name="modal-commessa" name="" class="form-control" value="<?php echo $row['commessa']; ?>" type="text">
      </td>
      </tr>

      <tr>
      <td class="td-intestazione">Cliente:</td>
      <td>
      <input name="modal-cliente" class="form-control" value="<?php echo $row['cliente']; ?>" type="text">
      </td>
      </tr>

      <tr>
      <td class="td-intestazione">Alias:</td>
      <td>
      <input name="modal-alias" class="form-control" value="<?php echo $row['nome_commessa']; ?>" type="text">
      </td>
      </tr>

      <tr>
      <td class="td-intestazione">Responsabile/i:</td>
      <td>
          <div id="div-lista-resp">
          <?php 
          $user = new User();
          $responsabili = $user->getResponsabiliByCommessa($_GET['id']);
          $idResponsabili = array();
          foreach($responsabili as $resp) {
              array_push($idResponsabili, $resp["id_user"]);
              echo '<button type="button" value="' . $resp["id_user"] . '" class="btn btn-primary btn-sm" '
                      . 'style="margin-right:5px;" onclick="removeResponsabile(this, this.value)">' . $resp["nome"] . 
                      ' ' . $resp["cognome"] . ' <span class="glyphicon glyphicon-remove"></span></button>';
          }
          ?>
          </div>
          <div class="row">
            <div class="col-md-9">
              <select id="select-responsabili" name="modal-responsabili" style="margin-top:1%;" class="form-control"> 
                <?php
                    $usersNotResp = $user->getUsersExceptRespCommessa($_GET['id']);
                    foreach($usersNotResp as $user) {
                        echo '<option value="' . $user["user_id"] . '">' . 
                        $user["cognome"] . ' ' . $user["nome"] . '</option>';
                    }
                ?>
              </select>
            </div>
            <div class="col-md-3" style="margin-top:4px;">
                <button type="button" class="btn btn-success" onclick="addResponsabile()">
                    <span class="glyphicon glyphicon-plus"></span> Aggiungi
                </button>
            </div>
          </div>
      </td>
      </tr>
      <input type="hidden" name="id-commessa" value="<?php echo $_GET['id']; ?>">
      <input type="hidden" name="responsabili-list" id="responsabili-list"
             value="<?php echo implode("-", $idResponsabili); ?>">
      <input type="hidden" name="responsabili-list-old" id="responsabili-list-old"
             value="<?php echo implode("-", $idResponsabili); ?>">
      </tbody>
      </table>
      </form>
      </div>
      <div class="modal-footer">
         <button name="edit-commessa" type="submit" class="btn btn-success">Modifica</button>
         <button name="delete-commessa" type="submit" class="btn btn-danger">Elimina</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">
            <span class="glyphicon glyphicon-menu-left"></span> Indietro
        </button>
      </div>
   </div>
</div>
</form>

<?php

mysqli_close($con);
?>