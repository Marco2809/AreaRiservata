<?php

if(isset($_POST['add-dipendente'])) {
    $userModule = new Modulo();
    $userModule->createUserModule($_POST['add-dipendente'], $_POST['moduli']);
}

if(isset($_POST['remove-dipendente'])) {
    $userModule = new Modulo();
    $userModule->deleteUserModule($_POST['remove-dipendente'], $_POST['id-module']);
}

?>

<!--CONTENUTO PRINCIPALE-->
<section class="">

<form action="" method="POST">
<div class="row">
    
    <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Selezione Modulo</span>
                </div>
            </div>

            <select id="moduli" name="moduli" style="margin:2%; width:96%;" 
                    class="form-control" onchange="changeModule(this.value, this)">
    <?php
                $moduli = new Modulo();
                $allModules = $moduli->getAll();

                foreach($allModules as $modulo) {
                    echo '<option value="' . $modulo['id_modulo'], '"';
                    
                    if(isset($_POST['moduli'])) {
                        if($modulo['id_modulo'] == $_POST['moduli']) {
                            echo ' selected';
                        }
                    } else if(isset($_POST['id-module'])) {
                        if($modulo['id_modulo'] == $_POST['id-module']) {
                            echo ' selected';
                        }
                    }
                    
                    echo '>' . $modulo['nome'] . '</option>';
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
            $dipendenti = $user->getAllExceptModule($moduleToShow);
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
                <span class="legenda-title">Permessi Modulo <?php echo $allModules[$moduleToShow-1]['nome']; ?></span>
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
                $moduloUser = new Modulo();
                $firstModuleUsers = $moduloUser->getUsersByModuleId($moduleToShow);

                echo '<input name="id-module" type="hidden" value="' . $moduleToShow . '">';
              foreach($firstModuleUsers as $userModule) {
                  echo '<tr>' . 
                       '<td>' . $userModule['user_id'] . '</td>' .
                       '<td>' . $userModule['nome'] . ' ' . $userModule['cognome'] . '</td>' .
                       '<td>' . $userModule['data_nascita'] . '</td>' .
                       '<td><button name="remove-dipendente" type="submit" value="' . $userModule['user_id'] . 
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
    function changeModule(idModule, nameModule) {
        if(idModule == "0") {
            document.getElementById("panel-list-employees").innerHTML = "";
            document.getElementById("panel-current-module").innerHTML = "";
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
                    document.getElementById("panel-current-module").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "get-modulo.php?id=" + idModule + '&name=' + 
                    nameModule.options[nameModule.selectedIndex].text, true);
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
            xmlhttp.open("GET", "get-lista-dip-moduli.php?id=" + idModule + '&name=' + 
                    nameModule.options[nameModule.selectedIndex].text, true);
            xmlhttp.send();
            
        }
    }
</script>