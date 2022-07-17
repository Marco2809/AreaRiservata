<?php
session_start();
require_once('dbconn.php');
class Richiesta
{
    public $db;
    //variabili tabella richieste
    public $id_richiesta;
    public $cliente;
    public $descrizione;
    public $da;
    public $esito;
    public $citta;
    public $start;
    public $end;
    public $revenue;
    public $note;

    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function delRichiesta($id){
         $delete = "DELETE FROM richieste WHERE id_richiesta =". $id;
        $result = $this->db->query($delete);
          return $result;
      }

      public function addRichiesta($richiestaDescrizione, $richiestaCliente, $richiestaDa,$richiestaEsito, $richiestaStart, $richiestaEnd,$richiestaCitta, $richiestaRevenue, $richiestaNote) {
        $sql = "INSERT INTO richieste (descrizione, cliente, da, esito, citta, start, end, revenue, note) VALUES ('" .
                $this->db->escape_string($richiestaDescrizione)."', '" . $this->db->escape_string($richiestaCliente) . "', '" .
                $this->db->escape_string($richiestaDa)."', '" . $this->db->escape_string($richiestaEsito) . "', '" .
                $this->db->escape_string($richiestaCitta) . "', '" . $richiestaStart . "', '" . $richiestaEnd . "','" . $this->db->escape_string($richiestaRevenue) . "','" . $this->db->escape_string($richiestaNote) . "')";


        $result = $this->db->query($sql);
        if ($result) {
            $alert =  "<div class='panel panel-success'><div class='panel-heading'>Messaggio inviato con successo</div></div>";
        }
        else {
            return "<div class='panel panel-danger'><div class='panel-heading'>Problema nell'invio del messaggio. Se il problema persiste contattare l'amministratore.</div></div>";
        }
        return $alert;
      }

      public function editRichiesta($id,$richiestaDescrizione, $richiestaCliente, $richiestaDa,$richiestaEsito, $richiestaStart, $richiestaEnd,$richiestaCitta, $richiestaRevenue, $richiestaNote) {

            $stmtAnagraphic = $this->db->prepare("UPDATE richieste SET descrizione = ?, cliente = ? ,da = ?,esito = ?, citta = ?, start = ? ,end = ?,revenue = ?, note = ? WHERE id_richiesta = ?");
            $stmtAnagraphic->bind_param('sssssssssi', $richiestaDescrizione, $richiestaCliente, $richiestaDa,$richiestaEsito, $richiestaStart, $richiestaEnd,$richiestaCitta, $richiestaRevenue, $richiestaNote, $id);
            $stmtAnagraphic->execute();

            if($stmtAnagraphic) {
                return true;
            } else {
                return false;
            }
        }

      public function getAll() {
          $outputArray = array();
          $result = $this->db->query("SELECT *
                  FROM richieste");
          while($row = $result->fetch_assoc()) {
              array_push($outputArray, $row);
          }
          return $outputArray;
      }

    }

    ?>
