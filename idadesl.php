<?php
if( !isset($_POST['usu']) || !isset($_POST['pas']) ){
    //header('Location:http://localhost/P55-Javier Ruiz Nieto/index.php');
}
$jsons = json_decode($_GET['json'],true);

$user = $jsons['usu'];
$pass = $jsons['pas'];
$isbn = $jsons['isbn'];
$autor = $jsons['autor'];
$titol = $jsons['titol'];
$preu = $jsons['preu'];


$jsonsRespostaOk = json_encode(["Resposta"=>"Insercio correcta"]);
$jsonsRespostaErr = json_encode(["Resposta"=>"Err de Insercio"]);

//Tema de guardar les referències d'entitats

$isbn=htmlentities(addslashes($isbn));
$autor=htmlentities(addslashes($autor));
$titol=htmlentities(addslashes($titol));
$preu=htmlentities(addslashes($preu));

//Si ets l'administrador 
if ($user == 'admin'){
    
        //Connexió a mysql
        $mysql = mysqli_connect("localhost", "bookadmin","1111", "webbooks");
        if(!$mysql){
            //echo 'no puc establir la connexió';
            //Ara he de construir a ma la direcció
            $err='Error intern. No es pot establir la connexió. Torni a probar-ho més tard';
            echo $jsonsRespostaErr;       
        }
        //Selecció de la base de dades apropiada
        $selected =  mysqli_select_db($mysql, "webbooks");
        if(!$selected){
            $err='Error intern. No es pot seleccionar la base de dades. Torni a intentar-ho més tard';
            echo $jsonsRespostaErr;
        }
        
        //Faig la inserció de les dades
        $query="insert into llibre values ('".$isbn."','".$autor."','".$titol."','".$preu."')";
        $result=  mysqli_query($mysql, $query);
        if(!$result){
            $err='Error intern. No rutlla la query. Torni a intentar-ho més tard';
            echo $jsonsRespostaErr; 
        }
        
        
        echo $jsonsRespostaOk;
}else{
    echo $jsonsRespostaErr;
}

?>