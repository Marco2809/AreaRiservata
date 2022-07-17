<?php
// Managing searches
if(isset($_GET['username'])) {
    if($_GET['username'] == '') {
        $error = -2;
    } else {
        $userToSearch = new UserSupplier();
        $userToSearch->setUsername($_GET['username']);
        $searchResults = $userToSearch->searchUsers();

        if(count($searchResults) == 0) {
            $error = -1;
        } else {
            $error = 1;
        }

    }
}

?>

<section class="">

    <div class="col-md-12">
        
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Ricerca Utente</span>
                </div>
            </div>

            <div class="panel-body">

                <form action="albo-fornitori.php" method="GET" name="search_user">
                    
                    <input type="hidden" name="action" value="ricerca-utenti">

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>

                    <button type="submit" class="btn btn-success" name="submit">
                        <span class="glyphicon glyphicon-search"></span> Cerca
                    </button>
                    <a href="javascript:history.back()" class="btn btn-default">
                        <span class="glyphicon glyphicon glyphicon-chevron-left"></span> Indietro
                    </a>

                </form>
                
            </div>
        </div>
        
    <div class="spacing"></div>

    <?php
    if($error == -1) { 
    ?>
        <div class="error-message">
            <div class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-exclamation-sign"></span> 
                <strong>Attenzione!</strong> Nessun utente trovato.
            </div>
        </div>

    <?php   } else if($error == -2) { ?>

        <div class="error-message">
            <div class="alert alert-warning alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-exclamation-sign"></span> 
                <strong>Attenzione!</strong> Nessun dato inserito.
            </div>
        </div>

    <?php   } else if($error == 1) { ?>

        <h4>Risultato Ricerca</h4>

        <table id="search-result" class="tablesorter">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Azioni</th>
                </tr>
            </thead>

            <tbody>
    <?php
                for($i=0; $i<count($searchResults); $i++) {
                    echo '<tr>';
                    echo '<td class="user-profile-user"><a href="user-profile.php?id=' . $searchResults[$i]->getID() . '">' . $searchResults[$i]->getUsername() . '</a></td>';
                    echo '<td class="user-profile-actions">';

                    echo '<a href="registry-supplier.php?id=' . $searchResults[$i]->getID() . '" class="btn btn-info btn-xs">'
                       . '<span class="glyphicon glyphicon-file"></span> Scheda</a> ';

                    echo '<a href="edit-user.php?id=' . $searchResults[$i]->getID() . '" class="btn btn-primary btn-xs">'
                       . '<span class="glyphicon glyphicon-pencil"></span> Modifica</a> '
                       . '<a href="resources/library/php/delete-user.php?id=' . $searchResults[$i]->getID() . '" class="btn btn-danger btn-xs">'
                       . '<span class="glyphicon glyphicon-trash"></span> Elimina</a></td>';
                    echo '</tr>';
                    }
    ?>
            </tbody>
        </table>
    <?php   } 
    ?>
    </div>
    
</section>