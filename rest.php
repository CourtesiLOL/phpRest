<?php

$peticion = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($peticion) {
    case "GET":
        getllibres();
        break;
    case "POST":
        postllibres();
        break;
    case "PUT":
        putllibres();
        break;
    case "DELETE":
        echo json_encode(["response" => "El número no es 1, 2 o 3"]);
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Error" => "Método no permitido"]);
        break;
}

function getllibres()
{
    if (!isset($_GET['json'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["Error" => "No se ha proporcionado el parámetro 'json'"]);
        return;
    }

    $jsons = json_decode($_GET['json'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["Error" => "JSON inválido"]);
        return;
    }

    if (!isset($jsons['user']) || !isset($jsons['pass'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["Error" => "Faltan parámetros 'user' o 'pass'"]);
        return;
    }

    $user = $jsons['user'];
    $pass = $jsons['pass'];

    $isbne = isset($_GET['is']) ? $_GET['is'] : 0;

    if ($user == 'admin') {
        $mysql = mysqli_connect("localhost", "bookadmin", "1111", "webbooks");
        if (!$mysql) {
            header('HTTP/1.1 510 Not Extended');
            echo json_encode(["Error" => "No se puede establecer la conexión a la base de datos"]);
            return;
        }

        $query = "SELECT * FROM llibre";
        $result = mysqli_query($mysql, $query);
        if (!$result) {
            header('HTTP/1.1 510 Not Extended');
            echo json_encode(["Error" => "No se puede ejecutar la consulta"]);
            return;
        }

        $sort = '';

        while ($row = mysqli_fetch_assoc($result)) {
            $isbn = isset($row['isbn']) ? html_entity_decode($row['isbn']) : '';
            $autor = isset($row['autor']) ? html_entity_decode($row['autor']) : '';
            $titol = isset($row['titol']) ? html_entity_decode($row['titol']) : '';
            $preu = isset($row['preu']) ? html_entity_decode($row['preu']) : '';
            $sort .= '<tr>';
            if ($isbn != $isbne) {
                $sort .= '<td><input type="text" name="ide" disabled="disabled" value="' . $isbn . '"></td><td><input type="text" name="nom" value="' . $autor . '"></td><td><input type="text" name="adreca" value="' . $titol . '"></td><td><input type="text" name="ciutat" value="' . $preu . '"></td><td><input type="checkbox"></td></tr>';
            } else {
                $sort .= '<td><input type="text" name="ide" disabled="disabled" value="' . $isbn . '" class="groc"></td><td><input type="text" name="nom" value="' . $autor . '" class="groc"></td><td><input type="text" name="adreca" value="' . $titol . '" class="groc"></td><td><input type="text" name="ciutat" value="' . $preu . '" class="groc"></td><td><input type="checkbox"></td></tr>';
            }
            $sort .= '</tr>';
        }

        echo json_encode(["response" => $sort]);
    } else {
        echo json_encode(["response" => $user]);
    }
}

function postllibres()
{
    // Lee el cuerpo de la solicitud
    $json = file_get_contents('php://input');
    $jsons = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["Error" => "JSON inválido"]);
        return;
    }

    // Verifica si se recibieron todos los parámetros necesarios
    if (!isset($jsons['user']) || !isset($jsons['pass']) || !isset($jsons['isbnViejo']) || !isset($jsons['isbnNuevo']) || !isset($jsons['autor']) || !isset($jsons['titol']) || !isset($jsons['preu'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["Error" => "Faltan parámetros 'user', 'pass', 'isbnjViejo', 'isbnjNuevo', 'autorj', 'titolj' o 'preuj'"]);
        return;
    }

    // Asigna y sanitiza los datos recibidos
    $user = htmlspecialchars($jsons['user']);
    $pass = htmlspecialchars($jsons['pass']);
    $isbnViejo = htmlspecialchars($jsons['isbnjViejo']);
    $isbnNuevo = htmlspecialchars($jsons['isbnjNuevo']);
    $autor = htmlspecialchars($jsons['autorj']);
    $titol = htmlspecialchars($jsons['titolj']);
    $preu = htmlspecialchars($jsons['preuj']);

    // Aquí puedes continuar con el procesamiento de los datos
    // Ejemplo de respuesta
    header('HTTP/1.1 200 OK');

    if ($user == 'admin') {
        // Conecta a la base de datos
        $mysql = mysqli_connect("127.0.0.1", "bookadmin", "1111", "webbooks");
        if (!$mysql) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["Error" => "No se puede establecer la conexión a la base de datos"]);
            return;
        }

        // Prepara la consulta UPDATE
        $query = "UPDATE llibre SET isbn = '$isbnNuevo', autor = '$autor', titol = '$titol', preu = '$preu' WHERE llibre.isbn = '$isbnViejo'";
        $stmt = mysqli_prepare($mysql, $query);
        if (!$stmt) {   
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["Error" => "Fallo al preparar la consulta"]);
            mysqli_close($mysql);
            return;
        }

        // Ejecuta la consulta
        if (!mysqli_stmt_execute($stmt)) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["Error" => "No se puede ejecutar la consulta"]);
            mysqli_stmt_close($stmt);
            mysqli_close($mysql);
            return;
        }

        echo json_encode(["response" => "Libro actualizado correctamente"]);
        mysqli_stmt_close($stmt);
        mysqli_close($mysql);
    } else {
        echo json_encode(["response" => "Funcionalidad para otros usuarios será implementada próximamente"]);
    }
}


function putllibres()
{
    // Obtiene el cuerpo de la solicitud
    $input = file_get_contents('php://input');
    $jsons = json_decode($input, true);

    // Verifica si el JSON es válido
    if (json_last_error() !== JSON_ERROR_NONE) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["Error" => "JSON inválido"]);
        return;
    }

    // Verifica si se recibieron todos los parámetros necesarios
    if (!isset($jsons['usu']) || !isset($jsons['pas']) || !isset($jsons['isbn']) || !isset($jsons['autor']) || !isset($jsons['titol']) || !isset($jsons['preu'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["Error" => "Faltan parámetros 'usu', 'pas', 'isbn', 'autor', 'titol' o 'preu'"]);
        return;
    }

    // Sanitiza los datos recibidos
    $user = htmlentities(addslashes($jsons['usu']));
    $pass = htmlentities(addslashes($jsons['pas']));
    $isbn = htmlentities(addslashes($jsons['isbn']));
    $autor = htmlentities(addslashes($jsons['autor']));
    $titol = htmlentities(addslashes($jsons['titol']));
    $preu = htmlentities(addslashes($jsons['preu']));

    $jsonsRespostaOk = json_encode(["Resposta" => "Inserción correcta"]);
    $jsonsRespostaErr = json_encode(["Resposta" => "Err de Inserción"]);

    // Verifica las credenciales
    if ($user == 'admin') {
        // Conecta a la base de datos
        $mysql = mysqli_connect("localhost", "bookadmin", "1111", "webbooks");
        if (!$mysql) {
            header('HTTP/1.1 510 Not Extended');
            echo $jsonsRespostaErr;
            return;
        }

        $selected = mysqli_select_db($mysql, "webbooks");
        if (!$selected) {
            header('HTTP/1.1 510 Not Extended');
            echo $jsonsRespostaErr;
            return;
        }

        // Inserta los datos en la base de datos
        $query = "INSERT INTO llibre (isbn, autor, titol, preu) VALUES ('$isbn', '$autor', '$titol', '$preu')";
        $result = mysqli_query($mysql, $query);
        if (!$result) {
            header('HTTP/1.1 510 Not Extended');
            echo $jsonsRespostaErr;
            return;
        }

        echo $jsonsRespostaOk;
    } else {
        echo $jsonsRespostaErr;
    }
}

?>