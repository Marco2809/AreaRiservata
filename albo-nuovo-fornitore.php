<section class="">

    <div class="col-md-12">
        
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Creazione Nuovo Fornitore</span>
                </div>
            </div>

            <div class="panel-body">

                <form action="albo-fornitori.php?action=fornitori" method="POST" name="create_supplier">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rag_sociale">Ragione sociale:</label>
                            <input type="text" class="form-control" id="rag_sociale" name="rag_sociale" required>
                        </div>
                        <div class="form-group">
                            <label for="p_iva">Partita IVA:</label>
                            <input type="text" class="form-control" id="iva" name="iva" required>
                        </div>
                        <div class="form-group">
                            <label for="indirizzo">Indirizzo:</label>
                            <input type="text" class="form-control" id="indirizzo" name="indirizzo" required>
                        </div>
                        <div class="form-group">
                            <label for="citta">Città:</label>
                            <input type="text" class="form-control" id="citta" name="citta" required>
                        </div>
                        <div class="form-group">
                            <label for="pec">PEC:</label>
                            <input type="text" class="form-control" id="pec" name="pec" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="cf">Codice Fiscale:</label>
                            <input type="text" class="form-control" id="cf" name="cf" required>
                        </div>
                        <div class="form-group">
                            <label for="cap">CAP:</label>
                            <input type="text" class="form-control" id="cap" name="cap" required>
                        </div>
                        <div class="form-group">
                            <label for="nazione">Nazione:</label>
                            <input type="text" class="form-control" id="nazione" name="nazione" required>
                        </div>
                    </div>

                    <div class="spacing"></div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success" name="submit">
                            <span class="glyphicon glyphicon-ok"></span> Crea Operatore
                        </button>
                        <a href="javascript:history.back()" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left"></span> Indietro
                    </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
</section>