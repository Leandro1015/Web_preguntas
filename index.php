<?php
    require_once './config/config.php';

    // Verificar si el controlador y el método están definidos en la URL
    if (!isset($_GET["c"]) || !isset($_GET["m"])) {
        $nombre_controlador = CONTROLADOR_POR_DEFECTO;
        $nombre_metodo = METODO_POR_DEFECTO;
    } else {
        $nombre_controlador = $_GET["c"];
        $nombre_metodo = $_GET["m"];
    }

    $ruta_controlador = 'controlador/' . $nombre_controlador . '.php';

    if (file_exists($ruta_controlador)) {
        require_once $ruta_controlador;
    } else {
        echo "Error: El controlador " . $nombre_controlador . " no existe.";
    }

    // Crear instancia del controlador y llamar al método correspondiente
    $objetoContr = new $nombre_controlador();

    $datos_vista = $objetoContr->{$nombre_metodo}();

    // Obtener el nombre de la vista desde el controlador
    $vista = $objetoContr->nombre_vista . '.php';

    if (file_exists($vista)) {
        require_once $vista;
    } else {
        echo "Error: La vista " . $vista . " no existe.";
    }