<?php

/* El primer que faré serà recollir el usuari i pass que hem enviat per POST
 * Confiaré que siguin correctes però hauri de comprobar que ho fossin
 */



//Si no s'ha passat per POST res de res a tomá pol culo!
header('HTTP/1.1 422');

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    header('HTTP/1.1 200');

    $jsonsRespostaOk = json_encode(["Resposta"=>"Insercio correcta"]);

    $jsons = json_decode($_GET['json'],true);

    $user = $jsons['usu'];
    $pass = $jsons['pas'];

    //Recullo l'isbn si me l'han passat
    $isbne=0;
    if(isset($_POST['is'])){
            $isbne = $_POST['is'];
            }
    //Si ets l'administrador 
    if ($user == 'admin'){
        
            //Connexió a mysql
            $mysql = mysqli_connect("localhost", "bookadmin","1111", "webbooks");
            if(!$mysql){
                header('HTTP/1.1 510');
                //echo 'no puc establir la connexió';
                //Ara he de construir a ma la direcció
                $err='Error intern. No es pot establir la connexió. Torni a probar-ho més tard';
                json_encode(["Error"=>$err]);        
            }
            //Selecció de la base de dades apropiada
            $selected =  mysqli_select_db($mysql, "webbooks");
            if(!$selected){
                header('HTTP/1.1 510');
                $err='Error intern. No es pot seleccionar la base de dades. Torni a intentar-ho més tard';
                json_encode(["Error"=>$err]);
            }
            //Faig una consulta a la base de dades per veure si hi ha una fila que clavi el que m'entren
            $query="select * from llibre";
            $result=  mysqli_query($mysql, $query);
            if(!$result){
                header('HTTP/1.1 510');
                $err='Error intern. No rutlla la query. Torni a intentar-ho més tard';
                json_encode(["Error"=>$err]);  
            }
            
            //Miro quantes files em retornarà
            $numresults=  mysqli_num_rows($result);
            
            //Variable per la sortida
            $sort='';
            
            //Vaig extraient cada resultat i maquetant
            //Tema de reconvertir les referències d'entitat
            for($i=0;$i<$numresults;$i++){
                $row=mysqli_fetch_assoc($result);
                $isbn=html_entity_decode($row['isbn']);
                $autor=html_entity_decode($row['autor']);
                $titol=html_entity_decode($row['titol']);
                $preu=html_entity_decode($row['preu']);
                $sort=$sort.'<tr>';
                //Construeixo la fila tenint en compte si és l'inserit o no
                if($isbn != $isbne){
                    $sort=$sort.'<td><input type="text" name="ide" disabled="disabled" value="'.$isbn.'"></td><td><input type="text" name="nom" value="'.$autor.'"></td><td><input type="text" name="adreca" value="'.$titol.'"></td><td><input type="text" name="ciutat" value="'.$preu.'"></td><td><input type="checkbox"></td></tr>';
                }else{
                    $sort=$sort.'<td><input type="text" name="ide" disabled="disabled" value="'.$isbn.'" class="groc"></td><td><input type="text" name="nom" value="'.$autor.'" class="groc"></td><td><input type="text" name="adreca" value="'.$titol.'" class="groc"></td><td><input type="text" name="ciutat" value="'.$preu.'" class="groc"></td><td><input type="checkbox"></td></tr>';
                }
                
            }
            echo json_encode($sort.'</tr>');
    }else{
        echo 'altres usaris que ha ompliré el codi quan toqui';
    }
    
}



?>
