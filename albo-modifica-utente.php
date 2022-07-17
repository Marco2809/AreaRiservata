<?php

// Getting the user
$user = new UserSupplier();
if(isset($_GET['id'])) {
    $user->getUser($_GET['id']);
}

//Getting the suppliers
$supplier = new Supplier();
$supplier->getSupplierFromUserId($_GET['id']);
$suppliersNotUser = (new Supplier())->getSuppliersNotUser();

// Managing call
if(isset($_POST['username']) || isset($_POST['p']) || isset($_POST['supplier'])) {

    if($_POST['username'] == $user->getUsername() && 
       $_POST['p'] == '9b880ac23e45a106842a9031d5bb62452d555030e755991757263a166f9de3408226cb04da27f2c3469eacc9d172c8836ec1345a5f74ccaa698e45ff3adfcec6' &&
            $_POST['supplier'] == $supplier->getId()) {
        $error = 2;
        $username = $_POST['username'];
    } else {
        $userToUpdate = new UserSupplier();
        $userToUpdate->setID($_GET['id']);
        $userToUpdate->setUsername($_POST['username']);
        $userToUpdate->setPassword($_POST['p']);
        $userToUpdate->setSupplierID($_POST['supplier']);
        $error = $userToUpdate->editUser();

        if($error == 1) {
            $username = $userToUpdate->getUsername();
            $supplier->getSupplierFromUserId($_GET['id']);
            $suppliersNotUser = (new Supplier())->getSuppliersNotUser();
        } else {
            $username = $user->getUsername();
        }
    }

} else {
    $username = $user->getUsername();
}

?>

<section class="">

    <div class="col-md-12">
        
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Modifica Utente</span>
                </div>
            </div>

            <div class="panel-body">
                
                <form action="/albo-fornitori.php?action=modifica-utente&id=<?php echo $_GET['id']; ?>" method="POST" name="edit_form">

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" value="<?php echo $username; ?>"
                               id="username" name="username">
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" value="tochange"
                               name="password">
                    </div>

                    <div class="form-group">
                        <label for="supplier">Fornitore:</label>
                        <select class="form-control" id="supplier" name="supplier">
                            <option value="<?php echo $id;?>" selected>
                                <?php echo $supplier->getRagSociale();?>
                            </option>
            <?php           for($i=0; $i<count($suppliersNotUser); $i++) {
                                $idCurrentSupplier = $suppliersNotUser[$i]->getId();
                                echo '<option value="' . $idCurrentSupplier . '">' 
                                    . $suppliersNotUser[$i]->getRagSociale() . '</option>\n\ ';
                            } ?>
                        </select>
                    </div>

                    <div class="spacing"></div>

                    <input type="button" class="btn btn-info" value="Modifica Dati" name="btnSubmit"
                           onclick="formhash(this.form, this.form.password)">
                    <a href="javascript:history.back()" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left"></span>Indietro
                    </a>

                </form>
                
            </div>
        </div>
    </div>
    
</section>