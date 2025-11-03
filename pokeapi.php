<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Pokémon</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Buscador de Pokémon</h1>
    </header>
    <main>
        <section>
            <form id="pokemonForm" onsubmit="return false;">
                <input type="text" id="nombre" placeholder="Ingresa el nombre del Pokémon">
                <button type="button" id="buscar">Buscar</button>
            </form>
            <div id="resultado"></div>
        </section>
    </main>
    <script src="script.js"></script>
    <?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';

    if (!empty($nombre)) {
            // 1. Obtener la lista de todos los Pokémon
            $url_lista = "https://pokeapi.co/api/v2/pokemon?limit=100000&offset=0";
            $respuesta_lista = @file_get_contents($url_lista);
            if ($respuesta_lista === FALSE) {
                echo json_encode([
                    'error' => true,
                    'mensaje' => "No se pudo obtener la lista de Pokémon."
                ]);
                exit;
            }
            $datos_lista = json_decode($respuesta_lista, true);
            $nombre_buscar = strtolower($nombre);
            $pokemon_url = null;
            foreach ($datos_lista['results'] as $poke) {
                if (strtolower($poke['name']) === $nombre_buscar) {
                    $pokemon_url = $poke['url'];
                    break;
                }
            }
            if (!$pokemon_url) {
                echo json_encode([
                    'error' => true,
                    'mensaje' => "Busca bien " . htmlspecialchars($nombre)
                ]);
                exit;
            }
            
            // 2. Obtener los datos del Pokémon específico
            $respuesta = @file_get_contents($pokemon_url);
            if ($respuesta === FALSE) {
                echo json_encode([
                    'error' => true,
                    'mensaje' => "No se pudo obtener la información de " . htmlspecialchars($nombre)
                ]);
                exit;
            }
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
    ?>
</body>
</html>

