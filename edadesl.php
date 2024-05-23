<?php

/* El primer que faré serà recollir el usuari i pass que hem enviat per POST
 * Confiaré que siguin correctes però hauri de comprobar que ho fossin
 */

//Si no s'ha passat per POST res de res a tomá pol culo!
if( !isset($_POST['usu']) || !isset($_POST['pas']) ){
    header('Location:http://localhost/P55/index.php');
}

$user = $_POST['usu'];
$pass = $_POST['pas'];

//Recullo l'isbn
$isbne = $_POST['isbn'];

//Si ets l'administrador 
if ($user == 'admin'){
    
        //Connexió a mysql
        $mysql = mysqli_connect("localhost", "bookadmin","1111", "webbooks");
        if(!$mysql){
            //echo 'no puc establir la connexió';
            //Ara he de construir a ma la direcció
            $err='Error intern. No es pot establir la connexió. Torni a probar-ho més tard';
            echo $err;        
        }
        //Selecció de la base de dades apropiada
        $selected =  mysqli_select_db($mysql, "webbooks");
        if(!$selected){
            $err='Error intern. No es pot seleccionar la base de dades. Torni a intentar-ho més tard';
            echo $err;
        }
        //Faig una consulta per esborrar el que tingui l'isbn en qüestió
        $query="delete from llibre where isbn like '".$isbne."'";
        $result=  mysqli_query($mysql, $query);
        if(!$result){
            $err='Error intern. No rutlla la query. Torni a intentar-ho més tard';
            echo $err;  
        } else {
            echo $isbne;
        }
        
}else{
    echo 'altres usaris que ha ompliré el codi quan toqui';
}


?>
