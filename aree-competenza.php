<?php

if(isset($_POST['add-submit'])) {
    $group = new Gruppo();
    $group->createGroup($_POST['group-name-add']);
}

if(isset($_POST['add-dipendente'])) {
    $infoArray = [$_POST['gruppi'] . '-4'];

    $userGroup = new Gruppo();
    $userGroup->createUserGruppi($_POST['add-dipendente'], $infoArray);
}

if(isset($_POST['remove-dipendente'])) {
    $userGroup = new Gruppo();
    $userGroup->deleteUserGruppi($_POST['remove-dipendente'], $_POST['id-group']);
}

?>

<!--CONTENUTO PRINCIPALE-->
<section class="">


<div class="row">

    <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:2%; height:150px;">
            <div class="legenda-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Aggiungi Nuovo Gruppo</span>
                </div>
            </div>
            <form action="" method="POST">
                <div class="col-md-12" style="padding:2%;">
                    <div class="row">
                        <div class="col-md-2" style="line-height: 3;">
                            <strong>Nome Gruppo:</strong>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="group-name-add">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success" name="add-submit" type="submit">
                                <span class="glyphicon glyphicon-plus"></span> Aggiungi
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<form action="" method="POST">
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Selezione Gruppo</span>
                </div>
            </div>

            <select id="gruppi" name="gruppi" style="margin:2%; width:96%;"
                    class="form-control" onchange="changeGroup(this.value, this)">
    <?php
                $gruppi = new Gruppo();
                $allGruppi = $gruppi->getAll();

                foreach($allGruppi as $gruppo) {
                    echo '<option value="' . $gruppo['id_gruppo'] . '"';

                    if(isset($_POST['gruppi'])) {
                        if($gruppo['id_gruppo'] == $_POST['gruppi']) {
                            echo ' selected';
                        }
                    } else if(isset($_POST['id-group'])) {
                        if($gruppo['id_gruppo'] == $_POST['id-group']) {
                            echo ' selected';
                        }
                    }

                    echo '>' . $gruppo['gruppo'] . '</option>';
                }

    ?>
            </select>
        </div>
    </div>
</div>
<div class="row" style="margin-bottom:5%;">
<div id="panel-list-employees" class="col-md-6">
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
            $groupToShow = 0;
            if(isset($_POST['gruppi'])) {
                $groupToShow = $_POST['gruppi'];
            } else if(isset($_POST['id-group'])) {
                $groupToShow = $_POST['id-group'];
            }

            $user = new User();
            $dipendenti = $user->getAllExceptGroup($groupToShow);
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
<div id="panel-current-group" class="col-md-6" style="margin-bottom:5%;">
    <div class="panel panel-primary filterable" style="margin-bottom:5%;">
      <div class="calendar-heading">
                  <div style="padding-left:2%;padding-right:2%;">
                          <span class="legenda-title">Gruppo <?php echo $allGruppi[$groupToShow-1]['gruppo']; ?></span>
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
                  <th><input type="text" class="form-control" placeholder="Num."></th>
                  <th><input type="text" class="form-control" placeholder="Dipendente"></th>
                  <th><input type="text" class="form-control" placeholder="Data di Nascita"></th>
                  <th><input type="text" class="form-control" placeholder="Rimuovi"></th>
              </tr>
          </thead>
          <tbody>
    <?php
                    $gruppoUser = new Gruppo();
                    $firstGroupUsers = $gruppoUser->getUserByGroupId($groupToShow);

                    echo '<input name="id-group" type="hidden" value="' . $groupToShow . '">';
                  foreach($firstGroupUsers as $userGroup) {
                      echo '<tr>' .
                           '<td>' . $userGroup['user_id'] . '</td>' .
                           '<td>' . $userGroup['nome'] . ' ' . $userGroup['cognome'] . '</td>' .
                           '<td>' . $userGroup['data_nascita'] . '</td>' .
                           '<td><button name="remove-dipendente" type="submit" value="' . $userGroup['user_id'] .
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

<script type="text/javascript">
    function changeGroup(idGroup, nameGroup) {
        if(idGroup == "0") {
            document.getElementById("panel-list-employees").innerHTML = "";
            document.getElementById("panel-current-group").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("panel-current-group").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "get-gruppo.php?id=" + idGroup + '&name=' +
                    nameGroup.options[nameGroup.selectedIndex].text, true);
            xmlhttp.send();

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("panel-list-employees").innerHTML = this.responseText;
                    $('script[src="assets/js/filter-table.js"]').remove();
                    var script = document.createElement('script');
                    script.src = 'assets/js/filter-table.js';
                    $('body').append(script);
                }
            };
            xmlhttp.open("GET", "get-lista-dipendenti.php?id=" + idGroup + '&name=' +
                    nameGroup.options[nameGroup.selectedIndex].text, true);
            xmlhttp.send();

        }
    }
</script>
