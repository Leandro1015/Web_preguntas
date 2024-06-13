<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilo.css">
        <title>Error al subir el archivo</title>
    </head>
    <body>
        <div class="contenedor">
            <h1>Error al subir el archivo</h1>
            <?php
                // Comprueba si se han recibido avisos de error y muestra el mensaje correspondiente.
                if(isset($mensajeError)) {
                    echo "<p class='error-message'>$mensajeError</p>";
                }
            ?>
            <br>
            <a href="index.php?c=controlador_p&m=mostrarFP">Volver al Formulario de Preguntas</a>
        </div>
    </body>
</html>
