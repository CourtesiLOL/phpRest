<?php

$peticion = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($peticion) {
    case "GET":
        getllibres();
        break;
    case "POST":
        echo json_encode(["response" => "El número es 2"]);
        break;
    case "PUT":
        echo json_encode(["response" => "El número es 3"]);
        break;
    case "DELETE":
        echo json_encode(["response" => "El número no es 1, 2 o 3"]);
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Error" => "Método no permitido"]);
        break;
}

function getllibres() {
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

    // Recullo l'isbn si me l'han passat
    $isbne = isset($_GET['is']) ? $_GET['is'] : 0;

    if ($user == 'admin') {
        // Connexió a mysql
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
?>
