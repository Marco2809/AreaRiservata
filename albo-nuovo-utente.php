<?php

$suppliersNotUser = (new Supplier())->getSuppliersNotUser();

?>

<section class="">

    <div class="row">

        <div class="col-md-12">
            
            <div class="panel panel-primary filterable" style="margin-bottom:2%;">
                <div class="calendar-heading">
                    <div style="padding-left:1%;padding-right:1%;">
                        <span class="legenda-title">Creazione Nuovo Utente</span>
                    </div>
                </div>
                
                <div class="panel-body">

                    <form action="albo-fornitori.php?action=utenti" method="POST" name="create_user">
                        <div class="col-md-12" style="padding:2%;">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="supplier">Fornitore:</label>
                                <select class="form-control" id="supplier" name="supplier">
<?php                               for($i=0; $i<count($suppliersNotUser); $i++) {
                                        echo '<option value="' . $suppliersNotUser[$i]->getId() . '">' . 
                                                $suppliersNotUser[$i]->getRagSociale() . '</option>';
                                    }
?>
                                </select>
                            </div>

                            <input type="button" class="btn btn-success" name="btnSubmit" value="Crea Utente"
                                   onclick="formhash(this.form, this.form.password)">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="spacing"></div>

    <?php
    if(isset($_GET['error'])) { ?>
    <div class="error-message">
        <div class="alert alert-danger alert-dismissable fade in">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <span class="glyphicon-exclamation-sign"></span> 
            <strong>Attenzione!</strong> Username già presente.
        </div>
    </div>
    <?php } ?>

</section>