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
            <form id="formularioPreguntas" action="index.php?c=controlador_p&m=guardar" method="post">
                <div id="divEditor">
                    <!-- Las preguntas y respuestas dinámicas se añadirán aquí -->
                </div>
                <button type="button" id="anadirPreguntaButton">Añadir pregunta</button><br><br>
                <input type="submit" value="Guardar">
                <button type="button" id="descargarPDFButton" class="boton-descargar">Descargar PDF</button>
            </form>
            
            <h2>Subir Archivo PDF</h2>
            <form id="formularioSubirArchivo" action="index.php?c=controlador_p&m=guardarArchivos" method="post" enctype="multipart/form-data">
                <input type="file" name="archivo" accept=".pdf,image/*">
                <button type="submit">Subir archivo</button>
            </form>

            <p id="error-message" class="error-message"></p>
            <?php 
                if (isset($datos_vista)) { 
                    echo "<p class='error-message'>".$datos_vista."</p>";
                } 
            ?>
        </div>
        <script src="./js/preguntas.js"></script>
    </body>
</html>
