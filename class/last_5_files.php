<h3>ULTIMI 5 FILES</h3>
                                                
                                                  <?php 
                                        $database = new Database();
                                        $conn = $database->dbConnection();
                                        $sql= "SELECT f.nome as nome_file,f.estensione, a.nome,a.cognome,f.id_messaggio,m.id_autore FROM files as f, anagrafica as a,messaggi as m  WHERE f.id_messaggio = m.id_messaggio AND m.id_autore = a.user_id ORDER BY id_messaggio DESC LIMIT 0,5";
                                        if(isset($_GET['view'])&&$_GET['view']=="all_files") $sql= "SELECT f.nome as nome_file,f.estensione, a.nome,a.cognome,f.id_messaggio,m.id_autore FROM files as f, anagrafica as a,messaggi as m  WHERE f.id_messaggio = m.id_messaggio AND m.id_autore = a.user_id ORDER BY id_messaggio DESC";
// echo $sql;
                                        $result = $conn->query($sql);
                                        while($obj_mex= $result->fetch_object()){
                                        $destinatario = $obj_mex-> nome . " " . $obj_mex->cognome;
                                        $time = $obj_mex->data_creazione;    
                   
                     print' <div class="desc">';
                      print '<div class="col-md-10">';
                     print '<div class="thumb">';
                     print '<span class="badge bg-theme"><i class="fa fa-file"></i></span>';
                     print '</div>';
                     print '<div class="details">';
                     print '<p><b>'.$obj_mex->nome_file.'.'.$obj_mex->estensione.'</b><br>';
                     print '<a style="color: #68dff0;" href="bacheca.php?id='.$obj_mex->id_messaggio.'">File inserito da '.$destinatario.'</a>';
                     print '</p>';
                     print '</div>';
                      print '</div>';
                     if($_SESSION['user_idd']==$obj_mex->id_autore&&$_SERVER['PHP_SELF']!='/new_area_riservata/dashboard.php') print '<div class="col-md-2"><h4><a style="color: #68dff0;" target="_blank" href="files/'.$obj_mex->id_messaggio.'/'.$obj_mex->nome_file.'.'.$obj_mex->estensione.'"><span class="glyphicon glyphicon-download"></span></a></h4></div>';
                     print '</div>';
                    
                                        }
                                        ?>
                 