<?php

function loginForm(){
	echo'
	<div id="loginform">
	<form action="index.php" method="post">
		<p><strong>Introdueix usuari i contrassenya per continuar:</strong></p>
		<label for="name">User: </label>
		<input type="text" name="user" id="user" /> 
                <label for="name">Pass: </label>
                <input type="text" name="pass" id="pass" /> 
		<input type="submit" name="enter" id="enter" value="Enter" />
	</form>
	</div>
	';
}

if(isset($_POST['user'])){
	if($_POST['user'] != "" && $_POST['pass'] != ""){
		$_POST['user'] = stripslashes(htmlspecialchars($_POST['user']));
                $_POST['pass'] = stripslashes(htmlspecialchars($_POST['pass']));
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Chat</title>

<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript">
    
        //Definire les dues variables globals que usaré per saber quin usuari és el que fa AJAX 
        //i que redefiniré cada vegada que es logui un usuari
        var user="admin";
        var pass="1111";

        $(document).ready(function(){
                
                //INTERFICIE CLIENTS - En logar-se si usuari no és l'administrador o cap 
                //mostra les dades de la taula client i credencials
                if (user != 'admin' && user !="") {
                    mostraDadesClientCredencials();
                }
                console.log(user)
                console.log(pass)

                //Si l'usuari vol tancar la sessió
                $("#exit").click(function(){
                        var exit = confirm("Estas segur que vols abandonar la sessio?");
                        if(exit==true){
                          //Em carrego les variables globals en deslogar-me
                          user="";
                          pass="";
                          window.location = 'index.php';
                        }		
                });
                
                //INTERFICIE CLIENTS - Funció que
                //mostra les dades de la taula client i credencials
                function mostraDadesClientCredencials(){
                        var jsonInfo = JSON.parse('{"user":"' + user + '","pass":"' + pass + '"}');
                        alert(user);
                        $.ajax({
                                        type: 'POST',
                                        url:  'mdadesc.php',
                                        dataType: 'json',
                                        data: JSON.strinstringify(jsonInfo),
                                        success: function(suc) {
                                           jQuery('#clientMostra').html(suc);
                                        },
                                        error: function() {alert('An error occurred!');}
                       });
                       //Les seves credencials
                }
                

                //Event onclic esborra dades llibres ticats
                $("#edadesc").click(function(){
                         //Primer poso en un array cada un dels que tinc que esborrar
                            var arr = new Array();
                            var c=0;
                            var n = jQuery('#clienttb input:checkbox').length;
                            alert(n);
                            for(var i=0;i<n;i++){
                                if(jQuery('#clienttb input:checkbox:eq('+i+')').is(':checked')){
                                    //Vaig posant a dins d'un array les posicions ticades'
                                    //Els isbns dels llibres a esborrar
                                    //alert("ticat: "+i+" poscion array: "+c);
                                    var is=jQuery('#clienttb tr:eq('+i+') input:eq(0)').val();
                                    arr[c]=is;
                                    alert(is);
                                    c=c+1;
                                }
                            }
                            
                            //Fent Ajax aconsegueixo que me'ls esborri un a un'
                            //passant dins de l'array l'Id de cada un dels clients a esborrar
                            for(var c=0;c<arr.length;c++){
                            var id = arr[c];
                            $.ajax({
                                        type: 'POST',
                                        url:  'edadesc.php',
                                        dataType: 'html',
                                        data: {
                                            usu: user,
                                            pas: pass,
                                            idp: id
                                        },
                                        success: function(suc) {
                                           //Trec l'id del client esborrat
                                           alert(suc);
                                           //Actualitzo la llista dels clients forçant l'event onclick 
                                           //en mostra dades clients'
                                           $("#mdadesc").click();
                                        },
                                        error: function() {alert('An error occurred!');}
                            });
                               
                            }
                          
                });


                //Event onclic actualitza dades clients
                $("#adadesc").click(function(){          
                        alert('actualitzadades client');
                         //Primer poso en un array cada un dels que tinc que actualitzar
                         var arr = new Array();
                            var c=0;
                            var n = jQuery('#clienttb input:checkbox').length;

                            for(var i=0;i<n;i++){
                                if(jQuery('#clienttb input:checkbox:eq('+i+')').is(':checked')){
                                    //Vaig posant a dins d'un array les el numero de fila ticada'
                                    alert("ticat: "+i+" poscion array: "+c);
                                    arr[c]=i;
                                    c=c+1;
                                }
                            }
                            
                            //Fent Ajax aconsegueixo que me'ls actualitzi un a un
                            //a partir de la posició de fila ticada multiplicada per 4 que són els td's'
                            for(var i=0;i<arr.length;i++){
                                var mult = arr[i]*4;
                                alert(mult);
                                alert(arr.length);
                                var a = 0+parseInt(mult);
                                var b = 1+parseInt(mult);
                                var c = 2+parseInt(mult);
                                var d = 3+parseInt(mult);
                                //Recullo per jQuery el que he introduit en cada un dels camps del formulari d'inserció'
                                var id=jQuery(':text:eq('+a+')','#clienttb').val();
                                var nom=jQuery(':text:eq('+b+')','#clienttb').val();
                                var adreca=jQuery(':text:eq('+c+')','#clienttb').val();
                                var ciutat=jQuery(':text:eq('+d+')','#clienttb').val();
                                alert(id +" "+nom +" "+ adreca +" "+ ciutat);
                            
                            //Li passo les 4 dades perque no puc saber quina es la que tinc que actualitzar.
                            $.ajax({
                                        type: 'POST',
                                        url:  'adadesc.php',
                                        dataType: 'html',
                                        data: {
                                            usu: user,
                                            pas: pass,
                                            idp: id,
                                            nomp: nom,
                                            adrecap: adreca,
                                            ciutatp: ciutat
                                        },
                                        success: function(suc) {
                                           //Trec l'isbn del llibre esborrat
                                           alert(suc);
                                           //Actualitzo la llista dels llibres usant la funció
                                           //mostradadesllibres(0);
                                        },
                                        error: function() {alert('An error occurred!');}
                            });
                            
                            }
                           
                });
                
                //Event onclic mostra dades clients
                $("#mdadesc").click(function(){
                        //alert('user: '+user);	
                        //alert('pass: '+pass);
                        
                         $.ajax({
                                        type: 'POST',
                                        url:  'mdadesc.php',
                                        dataType: 'html',
                                        data: {
                                            usu: user,
                                            pas: pass
                                        },
                                        success: function(suc) {
                                           jQuery('#clienttb').html(suc);
                                        },
                                        error: function() {alert('An error occurred!');}
                                        });
                        
                });
                
                //Event onclic mostra dades dels llibres
                $("#mdadesl").click(function(){          
                        mostradadesllibres(0);
                });
                
                //Funció que mostra les dades dels llibres el parametre passa 0 si s'invoca sense inserir 
                ////cap llibre i l'isbn si s'invoca despres de la inserció d'un llibre'
                function mostradadesllibres(isbn){

                    var jsonInfo = JSON.parse('{"user":"' + user + '","pass":"' + pass + '"}');
                    $.ajax({
                                    type: 'GET',
                                    url:  'rest.php',
                                    dataType: 'json',
                                    data: {json:jsonInfo},
                                    success: function(suc) {
                                        console.log(suc["response"])
                                        //alert(suc["response"])
                                        //jQuery('#llibretb').html(suc);
                                    },
                                    error: function(suc) {
                                        console.log(suc["Error"]);
                                    }
                    });
                }


                
                //Event onclic inserir nous llibres
                $("#idadesl").click(function(){ 
                    //Primer de tot comprovo que l'isbn no estigui duplicat'
                    //sempre que l'hagin introduit
                    
                    var isbn=jQuery(':text:eq(0)','#inllibretb').val();
                    var ce = '0';
                    if(isbn != ''){
                        //Si l'atribut és vermell vol dir que l'isbn és incorrecte!!'
                        //Ho comprovo a partir de l'atribu'
                        //Mirava d'usar la funció comprovaISBN però crec que anava per un fil a part i fallava!!!!!!!
                        var atr = jQuery("#isbn").attr('class');
                        if(atr == 'none'){
                            ce=1;
                        };
                    }
                    //El segon pas serà comprovar que tant autor com títol no s'entren com a nulls'
                    var a =jQuery('#autor').val();
                    var t =jQuery('#titol').val();
                    jQuery("#autor").attr('class','none');
                    jQuery("#titol").attr('class','none');
                    jQuery("#preu").attr('class','none');
                    if(a == '') {
                        ce='0'; //Condició per no entrar a introduir
                        jQuery("#autor").attr('class','roig');
                    }
                    if(t =='') {
                        ce='0'; //Condició per no entrar a introduir
                        jQuery("#titol").attr('class','roig');
                    }
                    //Tercer comprovaré que el preu sigui un nombre
                    var p =jQuery('#preu').val();
                    if(isNaN(p)){
                        ce='0'; //Condició per no entrar a introduir
                        jQuery("#preu").attr('class','roig');
                    }
                    //En cas que em retorni exit
                    if (ce == '1') {
                        //Recullo per jQuery el que he introduit en cada un dels camps del formulari d'inserció'
                        var isbnj=jQuery(':text:eq(0)','#inllibretb').val();
                        var autorj=jQuery(':text:eq(1)','#inllibretb').val();
                        var titolj=jQuery(':text:eq(2)','#inllibretb').val();
                        var preuj=jQuery(':text:eq(3)','#inllibretb').val();
                        
                         $.ajax({
                                        type: 'POST',
                                        url:  'idadesl.php',
                                        dataType: 'html',
                                        data: {
                                            usu: user,
                                            pas: pass,
                                            isbn: isbnj,
                                            autor: autorj,
                                            titol: titolj,
                                            preu: preuj
                                        },
                                        success: function(suc) {
                                           alert(suc);
                                        },
                                        error: function() {alert('An error occurred!');}
                                        });
                        //Ara ja mostro les dades dels llibres amb el nou llibre inserit 1 
                        //segon més tard esborrant primer els que hi havia
                        jQuery('#llibretb').html('');
                        var t=setTimeout(function(){mostradadesllibres(isbnj);},2000);
                    }
                });
                
                
                //Event per a que quan es perdi el focus de l'isbn aquest comprobi que aquest no està duplicat'
                $("#isbn").keyup(function(){ 
                    comprovaISBN();
                });
                
                //Funció que comprova la no duplicitat de l'isbn d'un llibre que anem a introduir'
                function comprovaISBN(){
                    var isbn=jQuery("#isbn").val();
                         $.ajax({
                                        type: 'POST',
                                        url: 'comprovaISBN.php',
                                        dataType: 'html',
                                        data: {
                                            usu: user,
                                            pas: pass,
                                            isbnp: isbn
                                        },
                                        success: function(suc) {
                                           if(suc == 0){
                                               //Cas dolent
                                               jQuery("#isbn").attr('class','roig');
                                               return 0;
                                           }else{
                                               //Cas bo
                                               jQuery("#isbn").attr('class','none');
                                               return 1;
                                           }
                                        },
                                        error: function() {
                                            alert('An error occurred!');}
                                        });
                }
                
                //Event onclic esborra dades llibres ticats
                $("#edadesl").click(function(){
                         //Primer poso en un array cada un dels que tinc que esborrar
                            var arr = new Array();
                            var c=0;
                            var n = jQuery('#llibretb input:checkbox').length;
                            
                            for(var i=0;i<n;i++){
                                if(jQuery('#llibretb input:checkbox:eq('+i+')').is(':checked')){
                                    //Vaig posant a dins d'un array les posicions ticades'
                                    //Els isbns dels llibres a esborrar
                                    //alert("ticat: "+i+" poscion array: "+c);
                                    var is=jQuery('#llibretb tr:eq('+i+') input:eq(0)').val();
                                    arr[c]=is;
                                    c=c+1;
                                }
                            }
                            
                            //Fent Ajax aconsegueixo que me'ls esborri un a un'
                            //passant dins de l'array l'ISBN de cada un dels llibres a esborrar
                            for(var c=0;c<arr.length;c++){
                            var isb = arr[c];
                            $.ajax({
                                        type: 'POST',
                                        url:  'edadesl.php',
                                        dataType: 'html',
                                        data: {
                                            usu: user,
                                            pas: pass,
                                            isbn: isb
                                        },
                                        success: function(suc) {
                                           //Trec l'isbn del llibre esborrat
                                           alert(suc);
                                           //Actualitzo la llista dels llibres usant la funció
                                           mostradadesllibres(0);
                                        },
                                        error: function() {alert('An error occurred!');}
                            });
                               
                            }
                          
                });
                
                
                //Event onclic mostra dades dels llibres
                $("#adadesl").click(function(){          
                        alert('actualitzadades');
                         //Primer poso en un array cada un dels que tinc que actualitzar
                            var arr = new Array();
                            var c=0;
                            var n = jQuery('#llibretb input:checkbox').length;
                            
                            for(var i=0;i<n;i++){
                                if(jQuery('#llibretb input:checkbox:eq('+i+')').is(':checked')){
                                    //Vaig posant a dins d'un array les el numero de fila ticada'
                                    //alert("ticat: "+i+" poscion array: "+c);
                                    arr[c]=i;
                                    c=c+1;
                                }
                            }
                            
                            //Fent Ajax aconsegueixo que me'ls actualitzi un a un
                            //a partir de la posició de fila ticada multiplicada per 4 que són els td's'
                            for(var i=0;i<arr.length;i++){
                                var mult = arr[i]*4;
                                alert(mult);
                                alert(arr.length);
                                var a = 0+parseInt(mult);
                                var b = 1+parseInt(mult);
                                var c = 2+parseInt(mult);
                                var d = 3+parseInt(mult);
                                //Recullo per jQuery el que he introduit en cada un dels camps del formulari d'inserció'
                                var isbnj=jQuery(':text:eq('+a+')','#llibretb').val();
                                var autorj=jQuery(':text:eq('+b+')','#llibretb').val();
                                var titolj=jQuery(':text:eq('+c+')','#llibretb').val();
                                var preuj=jQuery(':text:eq('+d+')','#llibretb').val();
                                
                            
                            //Li passo les 4 dades perque no puc saber quina es la que tinc que actualitzar.
                            $.ajax({
                                        type: 'POST',
                                        url:  'adadesl.php',
                                        dataType: 'html',
                                        data: {
                                            usu: user,
                                            pas: pass,
                                            isbn: isbnj,
                                            autor: autorj,
                                            titol: titolj,
                                            preu: preuj
                                        },
                                        success: function(suc) {
                                           //Trec l'isbn del llibre esborrat
                                           alert(suc);
                                           //Actualitzo la llista dels llibres usant la funció
                                           //mostradadesllibres(0);
                                        },
                                        error: function() {alert('An error occurred!');}
                            });
                               
                            }
                           
                });


        }); 
        
</script>

<style type="text/css">
    /* CSS Document */
    body {
            font:12px arial;
            color: #222;
            text-align:center;
            padding:35px; }

    form, p, span {
            margin:0;
            padding:0; }

    input { 
            font:12px arial; }

    #wrapper {
            margin:0 auto;
            margin-top: 10px;
            padding-bottom:25px;
            background:#EBF4FB;
            width:704px;
            height: 440px;
            border:10px ridge cadetblue; 
            box-shadow: 3px 3px 7px grey;
            border-radius: 9px;
            opacity: 0.7;
    }
    
    #loginform {
            margin:0 auto;
            margin-top: 60px;
            padding-bottom:25px;
            background:#EBF4FB;
            width:504px;
            border:10px ridge cadetblue; 
            box-shadow: 3px 3px 7px grey;
            border-radius: 9px;
            opacity: 0.7;
    }
    
    body{
        background-image: url(fons2.png);
        background-size: 100%;
    }
    
    strong{
        color: grey;
        letter-spacing: 2px;
    }
    
    #nom{
        text-align: left;
        font-weight: bold;
    }

    #loginform { 
            padding-top:18px; }

    #loginform p { 
            margin: 5px; }

    #submitmsg { 
            box-shadow: 3px 3px 7px grey;
    }
    
    #submit { 
            width: 60px; 
    }

    .error { 
            color: #ff0000; }

    #menu { 
            padding:12.5px 25px 12.5px 25px; 
    }
    
    #name, #pass {
            border-radius: 4px;
            box-shadow: 3px 3px 7px grey;
            border:1px solid aqua; 
            padding-left: 4px;
            color: grey;
    }
    
    #name, #pass {
            margin-right: 9px;
    }
    /*Estil dels botons*/
    #enter, #exit, #mdadesc, #edadesc, #adadesc, #mdadesl, #edadesl, #adadesl, #idadesl{
            border-radius: 4px;
            box-shadow: 3px 3px 7px grey;
            border:1px solid aqua; 
            text-align: center;
            font-weight: bold;
            color: darkslategrey;
    }
    
                    /*Estils per a les presentacions de dades*/
                    table{
                        border: 2px solid;
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 5px;
                    }
                    thead{
                        
                        border-bottom: 2px solid;
                        background-color: red;
                    }
                    th{
                        padding: 5px;
                        align: center;
                        border: 1px solid;
                    }
                    td{
                        padding: 5px;
                        align: center;
                        border: 1px solid;
                    }
                    caption{
                        background-color: oldlace;
                        border-top: 1px solid;
                        padding: 5px;
                    }
                    #client, #libre {
                        margin-bottom: 7px;
                        height: 120px;
                        overflow: auto;
                    }
                    #inllibre{
                        margin-bottom: 7px;
                        overflow: auto;
                    }
                    /*Que els inputs de tipus text de la presentació encaixin en les celes de la taula*/
                    #clienttb input[type="text"], #llibretb input[type="text"], #inllibretb input[type="text"]
                    {
                    width:95%;
                    }
                    /*Classe per que quedin grocs el inputs del darrer llibre*/
                    .groc{
                        background-color: yellow;
                    }
                    /*Classe per que quedin vermells els isbns duplicats*/
                    .roig{
                        background-color: red;
                    }
</style>
</head>

<?php
if(!isset($_POST['user']) || !isset($_POST['pass']) || isset($_GET['error']) || ($_POST['user'] == "" || $_POST['pass'] == "")) {
            if (isset($_GET['error'])){ 
                echo '<span class="error">'.$_GET['error'].'</span>';
            }else{
                echo '<span class="error">Siusplau introdueix usuari i contrassenya</span>';
            }
            
            loginForm();
}
else{
    //Aquest és el cas en que ha posat usuari i passord ---> Això fora de l'IF || (!isset($_POST['pass']) || $_POST['pass'] != "pass")!!!
    //Connexió a mysql
    $mysql = mysqli_connect("localhost", "bookusers","bookusers", "webbooks");
    if(!$mysql){
        //echo 'no puc establir la connexió';
        //Ara he de construir a ma la direcció
        $err='Error intern. No es pot establir la connexió. Torni a probar-ho més tard';
        $direccio='http://localhost/P55/index.php?error='.$err;        
        header('Location:'.$direccio);

    }
    //Selecció de la base de dades apropiada
    $selected =  mysqli_select_db($mysql, "webbooks");
    if(!$selected){
        $err='Error intern. No es pot seleccionar la base de dades. Torni a intentar-ho més tard';
        $direccio='http://localhost/P55/index.php?error='.$err;        
        header('Location:'.$direccio);
    }
    //Faig una consulta a la base de dades per veure si hi ha una fila que clavi el que m'entren
    $query="select count(*) from credencials where usuari = '".$_POST['user']."' and contrassenya = '".$_POST['pass']."'";
    $result=  mysqli_query($mysql, $query);
    if(!$result){
        $err='Error intern. No rutlla la query. Torni a intentar-ho més tard';
        $direccio='http://localhost/P55/index.php?error='.$err;        
        header('Location:'.$direccio);
    }
    $row=  mysqli_fetch_row($result);
    $count = $row[0];
    
    if($count > 0){
        echo '<p class="error">Benvingut '.$_POST['user'].'</p>';
        
        //Canvio l'usuari i la pass de la variable global per poder consultar-los cada vegada que faci AJAX
        echo '<script type="text/javascript">';
        echo 'var pass="'.$_POST['pass'].'";';
        echo 'var user="'.$_POST['user'].'";';
        echo '</script>';
        
    } else {
        $err='Usuari o contrassenya incorrectes';
        $direccio='http://localhost/P55/index.php?error='.$err;        
        header('Location:'.$direccio);
    }  
?>

<!--Aquí hauré de posar una cosa o una altra depenent del client i de l'administrador-->
<!--Administrador-->
<?php
if ($_POST['user']=='admin'){
?>
<div id="wrapper">
	<div id="menu">
		
                
                <!--Dades dels clients-->
		<div id="client">
                    <table>
                        <caption>Clients</caption>
                        <thead>
                            <tr>
                            <th>Id</th><th>Nom</th><th>Adre&#231;a</th><th>Ciutat</th><th>Selecci&#242;</th>
                            </tr>
                        </thead>
                        <tbody id="clienttb">
                            
                        </tbody>
                    </table>
                </div>
                    <input name="mdadesc" type="button" id="mdadesc" value="Mostra dades"/>
                    <input name="edadesc" type="button" id="edadesc" value="Esborra ticats"/>
                    <input name="adadesc" type="button" id="adadesc" value="Actualitza ticats"/>
                    <hr/>
                
                <!--Dades dels llibres-->
		<div id="libre">
                    <table>
                        <caption>Llibres</caption>
                        <thead>
                            <tr>
                            <th>ISBN</th><th>Autor</th><th>T&#236;tol</th><th>Preu</th><th>Selecci&#242;</th>
                            </tr>
                        </thead>
                        <tbody id="llibretb">
                            
                        </tbody>
                    </table>
                </div>
                <!--Dades inserció dels llibres-->
                <div id="inllibre">
                    <table>
                        <caption>Insereix llibres</caption>
                        <thead>
                            <tr>
                            <th>ISBN</th><th>Autor</th><th>T&#236;tol</th><th>Preu</th>
                            </tr>
                        </thead>
                        <tbody id="inllibretb">
                            <tr><td><input type="text" name="ide" id="isbn"></td><td><input type="text" name="autor" id="autor"></td><td><input type="text" name="titol" id="titol"></td><td><input type="text" name="preu" id="preu"></td></tr>
                        </tbody>
                    </table>
                </div>
                    <input name="mdadesl" type="button" id="mdadesl" value="Mostra dades"/>
                    <input name="edadesl" type="button" id="edadesl" value="Esborra ticats"/>
                    <input name="adadesl" type="button" id="adadesl" value="Actualitza ticats"/>
                    <input name="idadesl" type="button" id="idadesl" value="Insereix llibre"/>
                    <hr/>
                
                <p class="logout"><input name="sortir" type="button" id="exit" value="SORTIR"/></p>
	</div>	

</div>

<!--Client-->
<?php
}else{
?>

<div id="wrapper">
	<div id="menu">
		
            <div id="clientMostra">
                
            </div>     
                <p class="logout"><input name="sortir" type="button" id="exit" value="SORTIR"/></p>
	</div>	

</div>

<?php
}}
?>
</body>
</html>
