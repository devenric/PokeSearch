<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';

    if (!empty($nombre)) {
        // Llamamos a la API de Pokémon usando file_get_contents
        $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($nombre);

        $respuesta = @file_get_contents($url);

        if ($respuesta === FALSE) {
            echo json_encode([
                'error' => true,
                'mensaje' => "Busca bien " . htmlspecialchars($nombre)
            ]);
        } else {
            // Decodificamos el JSON para poder usarlo en PHP
            $datos = json_decode($respuesta, true);
            $altura = $datos['height'] / 10; // Convertir a metros
            $peso = $datos['weight'] / 10; // Convertir a kilogramos
            $imagen = $datos['sprites']['front_default'];
            $tipos = array_map(function($tipo) {
                return $tipo['type']['name'];
            }, $datos['types']);

            echo json_encode([
                'error' => false,
                'nombre' => ucfirst($nombre),
                'altura' => $altura,
                'peso' => $peso,
                'imagen' => $imagen,
                'tipos' => $tipos
            ]);
        }
    } else {
        echo json_encode([
            'error' => true,
            'mensaje' => "No se envió ningún nombre."
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'mensaje' => "Método no permitido."
    ]);
}


