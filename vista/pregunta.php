<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilo.css">
        <title>Formulario de Preguntas</title>
    </head>
    <body>
        <div class="contenedor">
            <h2>Agregar Pregunta</h2>
            <form action="index.php?c=Controlador_p&m=guardar" method="post">
                <label for="textoPregunta">Texto de la Pregunta:</label><br>
                <input type="text" name="textoPregunta"><br><br>

                <label>Respuestas:</label><br>
                <input type="text" name="respuestas[]"><br>
                <input type="text" name="respuestas[]"><br>
                <input type="text" name="respuestas[]"><br>
                <input type="text" name="respuestas[]"><br><br>

                <input type="submit" value="Guardar">
            </form>
            <?php 
                if (isset($datos_vista)) { 
                    echo "<p class='error-message'>" . $datos_vista . "</p>";
                } 
            ?>
        </div>
    </body>
</html>
