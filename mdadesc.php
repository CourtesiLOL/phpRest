<?php

/* El primer que faré serà recollir el usuari i pass que hem enviat per POST
 * Confiaré que siguin correctes però hauri de comprobar que ho fossin
 */



//Si no s'ha passat per POST res de res a tomá pol culo!

header('HTTP/1.1 422');

if ($_SERVER['REQUEST_METHOD'] == 'GET'){


    $jsonsRespostaOk = json_encode(["Resposta"=>"Insercio correcta"]);

    $jsons = json_decode($_GET['json'],true);

    $user = $jsons['usu'];
    $pass = $jsons['pas'];


    //Si ets l'administrador 
    if ($user == 'admin'){
            
            //Connexió a mysql
            $link = mysqli_connect("localhost", "bookadmin","1111", "webbooks");
            if(!$link){
                //echo 'no puc establir la connexió';
                //Ara he de construir a ma la direcció
                $err='Error intern. No es pot establir la connexió. Torni a probar-ho més tard';
                echo json_encode(["Error"=>$err]);        
            }
            
            //Faig una consulta a la base de dades 
            $result =  mysqli_query($link,'select * from client');
            
            //Incialitzo la sortida
            $sort='';
            
            /* store results sets en cas que hi hagi resultats */
            if ($result){
                while( $row = mysqli_fetch_array($result) ){
                {
                        /*Extrec cada una de les columnes de cada set*/
                        $id=html_entity_decode($row[0]);
                        $nom=html_entity_decode($row[1]);
                        $adreca=html_entity_decode($row[2]);
                        $ciutat=html_entity_decode($row[3]);
                        $sort=$sort.'<tr>';
                        //M'asseguro de no poder esborrar el admin
                        if(!strcmp($nom,'Administrador')){
                            $sort=$sort.'<td><input type="text" name="ide" value="'.$id.'"></td><td><input type="text" name="nom" value="'.$nom.'"></td><td><input type="text" name="adreca" value="'.$adreca.'"></td><td><input type="text" name="ciutat" value="'.$ciutat.'"></td><td><input type="checkbox" disabled="true"></td></tr>';
                        }else{
                            $sort=$sort.'<td><input type="text" name="ide" value="'.$id.'"></td><td><input type="text" name="nom" value="'.$nom.'"></td><td><input type="text" name="adreca" value="'.$adreca.'"></td><td><input type="text" name="ciutat" value="'.$ciutat.'"></td><td><input type="checkbox"></td></tr>';
                        }
                }
                }
                echo $sort;
                
            /*Allibero memòria*/
            $result->free();

            /*Close connection*/
            $link->close();

            }else {json_encode(["Error"=>"error"]);}
            
    }else{
    //INTERFICIE CLIENTS ******************************************************
    //Connexió a mysql
            $link = mysqli_connect("localhost", "bookclient","1111", "webbooks");
            if(!$link){
                //echo 'no puc establir la connexió';
                //Ara he de construir a ma la direcció
                $err='Error intern. No es pot establir la connexió. Torni a probar-ho més tard';
                echo json_encode(["Error"=>$err]);        
            }
            
            //Faig una consulta a la base de dades per obtindre l'id de l'usuari
            $result =  mysqli_query($link,'select id from credencials where usuari like "'.$user.'"');
            
            /* store results sets en cas que hi hagi resultats */
            if ($result){
                $row = mysqli_fetch_array($result);
                $id=$row[0];
                }
                
            //Faig una consulta a la base de dades per obtindre les dades de client de l'usuari
            $result =  mysqli_query($link,'select * from client where id like "'.$id.'"');
            
            //Incialitzo la sortida
            $sort='';
            
            //Començo a codificar la taula
            $sort=$sort.'<!--Dades dels clients-->
            <div id="client">
                        <table>
                            <caption>Client</caption>
                            <thead>
                                <tr>
                                <th>Id</th><th>Nom</th><th>Adre&#231;a</th><th>Ciutat</th>
                                </tr>
                            </thead>
                            <tbody id="clienttb">';
            
            /* store results sets en cas que hi hagi resultats */
            if ($result){
                while( $row = mysqli_fetch_array($result) ){
                        /*Extrec cada una de les columnes de cada set*/
                        $id=html_entity_decode($row[0]);
                        $nom=html_entity_decode($row[1]);
                        $adreca=html_entity_decode($row[2]);
                        $ciutat=html_entity_decode($row[3]);
                        $sort=$sort.'<tr>';

                        $sort=$sort.'<td><input type="text" name="ide" value="'.$id.'" disabled="true"></td><td><input type="text" name="nom" value="'.$nom.'"></td><td><input type="text" name="adreca" value="'.$adreca.'"></td><td><input type="text" name="ciutat" value="'.$ciutat.'"></td></tr>';
                }
                }
            
            //Tanco la taula dades dels clients
            $sort=$sort.'</tbody></table></div>';
                
            //Hi afegeixo la capçalera de la taula CREDENCIALS
            $sort=$sort.'<div id="credencials"><table><caption>Credencials</caption><thead><tr><th>Id</th><th>Usuari</th><th>Password</th></tr></thead><tbody id="credencialstb">';

            //Faig una consulta a la base de dades per obtindre ara les credencials
            $result =  mysqli_query($link,'select * from credencials where id like "'.$id.'"');
            
            /* store results sets en cas que hi hagi resultats */
            if ($result){
                while( $row = mysqli_fetch_array($result) ){
                    $usuari=html_entity_decode($row[0]);
                    $contrassenya=html_entity_decode($row[1]);
                    $id=html_entity_decode($row[2]);
                    $sort=$sort.'<tr>';

                    $sort=$sort.'<td><input type="text" name="ide2" value="'.$id.'" disabled="true"></td><td><input type="text" name="usuari" value="'.$usuari.'"></td><td><input type="text" name="contrassenya" value="'.$contrassenya.'"></td></tr>';
                
                }
                }     
            
            //I ara la part final de la taula credencials que em falta
            $sort=$sort.'</tbody></table></div><hr/>';
            
            //Imprimeixo la sortida
            echo json_encode($sort);
                
            /*Allibero memòria*/
            $result->free();

            /*Close connection*/
            $link->close();

    }
    
}


?>
