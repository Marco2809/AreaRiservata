<?php

if(isset($_POST['add-dipendente'])) {
    $user = new User();
    $user->addUserToPermesso104($_POST['add-dipendente']);
}

if(isset($_POST['remove-dipendente'])) {
    $user = new User();
    $user->removeUserFromPermesso104($_POST['remove-dipendente']);
}

?>

<!--CONTENUTO PRINCIPALE-->
<section class="">

<form action="" method="POST">

<div class="row" style="margin-bottom:5%;">
<div id="panel-list-employees" class="col-md-6">
<div class="panel panel-primary filterable" style="margin-bottom:5%;">
    <div class="calendar-heading">
        <div style="padding-left:2%;padding-right:2%;">
            <span class="legenda-title">Lista Dipendenti</span>
        </div>
    </div>
  <table class="table">
      <thead>
          <tr class="filters">
              <th><input type="text" class="form-control" placeholder="Num."></th>
              <th><input type="text" class="form-control" placeholder="Dipendente"></th>
              <th><input type="text" class="form-control" placeholder="Data di Nascita"></th>
              <th><input type="text" class="form-control" placeholder="Aggiungi"></th>
          </tr>
      </thead>
      
      <tbody>
<?php
            $moduleToShow = 0;
            if(isset($_POST['moduli'])) {
                $moduleToShow = $_POST['moduli'];
            } else if(isset($_POST['id-module'])) {
                $moduleToShow = $_POST['id-module'];
            }

            $user = new User();
            $dipendenti = $user->getAllExcept104();
            foreach($dipendenti as $dipendente) {
                echo '<tr>' .
                     '<td>' . $dipendente['user_id'] . '</td>' .
                     '<td>' . $dipendente['nome'] . ' ' . $dipendente['cognome'] . '</td>' .
                     '<td>' . $dipendente['data_nascita'] . '</td>' .
                     '<td><button name="add-dipendente" type="submit" value="' . $dipendente['user_id'] . '"'
                        . 'style="background-color:transparent; border:none; outline:none;">' . 
                     '<i class="fa fa-plus-circle fa-2x" style="color:#77c803;"></i></button></td>' . 
                     '</tr>';
            }
?>
      </tbody>
  </table>
</form>
</div>
</div>
<div id="panel-current-module" class="col-md-6" style="margin-bottom:5%;">
    <div class="panel panel-primary filterable" style="margin-bottom:5%;">
        <div class="calendar-heading">
            <div style="padding-left:2%;padding-right:2%;">
                <span class="legenda-title">Utenti con Permesso per Legge 104</span>
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
                $userPermesso104 = new User();
                $users104 = $userPermesso104->getUsersPermesso104();

                echo '<input name="id-module" type="hidden" value="' . $moduleToShow . '">';
              foreach($users104 as $user104) {
                  echo '<tr>' . 
                       '<td>' . $user104['user_id'] . '</td>' .
                       '<td>' . $user104['nome'] . ' ' . $user104['cognome'] . '</td>' .
                       '<td>' . $user104['data_nascita'] . '</td>' .
                       '<td><button name="remove-dipendente" type="submit" value="' . $user104['user_id'] . 
                          '" style="background-color:transparent; border:none; outline:none;">'
                          . '<i class="fa fa-minus-circle fa-2x" style="color:#d90e0e;"></i></button></td>' . 
                       '</tr>';
                  }
    ?>					
          </tbody>
      </table>
    </form>
    </div>
</div>

</div>
</section>
<!--FINE CONTENUTO PRINCIPALE-->