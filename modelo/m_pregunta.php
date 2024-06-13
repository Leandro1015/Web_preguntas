<?php
    require_once 'conexion.php';

    class M_pregunta extends Conectar {
        /**
         * Inserta una pregunta y sus respuestas en la base de datos.
         *
         * @param string $textoPregunta El texto de la pregunta.
         * @param array $respuestas Array de respuestas asociadas a la pregunta.
         * @return bool|string Retorna true si la inserción es exitosa, o un mensaje de error si falla.
         */
        public function insertar($textoPregunta, $respuestas) {
            // Preparar la consulta para insertar la pregunta
            $sqlPregunta = "INSERT INTO pregunta (textoP) VALUES (?)";
            $consultaPregunta = $this->conexion->prepare($sqlPregunta);

            if (!$consultaPregunta) {
                return "Error en la preparación de la consulta: (".$this->conexion->errno.") ".$this->conexion->error;
            }

            // Vincular parámetros
            $consultaPregunta->bind_param("s", $textoPregunta);

            // Ejecutar la consulta para insertar la pregunta
            if ($consultaPregunta->execute()) {
                $idPregunta = $this->conexion->insert_id;

                // Preparar la consulta para insertar cada respuesta
                $sqlRespuesta = "INSERT INTO respuesta (textoR, idPregunta) VALUES (?, ?)";
                $consultaRespuesta = $this->conexion->prepare($sqlRespuesta);

                if (!$consultaRespuesta) {
                    return "Error en la preparación de la consulta: (".$this->conexion->errno.") ".$this->conexion->error;
                }

                // Insertar cada respuesta
                foreach ($respuestas as $respuesta) {
                    // Vincular parámetros para cada respuesta
                    $consultaRespuesta->bind_param("si", $respuesta, $idPregunta);
                    if (!$consultaRespuesta->execute()) {
                        return "Error al insertar respuesta: (".$this->conexion->errno.") ".$this->conexion->error;
                    }
                }
                return true;
            } else {
                return "Error al insertar pregunta: (".$this->conexion->errno.") ".$this->conexion->error;
            }
        }

        /**
         * Guarda la información de un archivo PDF en la base de datos.
         *
         * @param string $nombreOriginal Nombre original del archivo PDF.
         * @param string $nombreGuardado Nombre único generado para el archivo PDF almacenado.
         * @param string $rutaArchivo Ruta donde se guarda el archivo PDF en el servidor.
         * @return bool|string Retorna true si la inserción es exitosa, o un mensaje de error si falla.
         */
        public function guardarArchivoPDF($nombreOriginal, $nombreGuardado, $rutaArchivo) {
            // Preparar la consulta SQL
            $sql = "INSERT INTO archivos_pdf (nombre_original, nombre_guardado, ruta_archivo) VALUES (?, ?, ?)";
            $consulta = $this->conexion->prepare($sql);

            if (!$consulta) {
                return "Error en la preparación de la consulta: (".$this->conexion->errno.") ".$this->conexion->error;
            }

            // Vincular parámetros
            $consulta->bind_param("sss", $nombreOriginal, $nombreGuardado, $rutaArchivo);

            // Ejecutar la consulta para insertar el archivo PDF
            if ($consulta->execute()) {
                // Inserción exitosa
                return true;
            } else {
                // Error al ejecutar la consulta
                return "Error al guardar archivo PDF: (".$consulta->errno.") ".$consulta->error;
            }
        }


        /**
         * Obtiene todas las preguntas y sus respuestas de la base de datos.
         *
         * @return array|bool Array de preguntas con sus respuestas o false en caso de error.
         */
        public function obtenerTodasLasPreguntas() {
            $sql = "SELECT p.idPregunta, p.textoP, r.textoR 
                    FROM pregunta p
                    LEFT JOIN respuesta r ON p.idPregunta = r.idPregunta";
            $resultado = $this->conexion->query($sql);

            if ($resultado) {
                $preguntas = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $idPregunta = $fila['idPregunta'];
                    $preguntas[$idPregunta]['textoP'] = $fila['textoP'];
                    $preguntas[$idPregunta]['respuestas'][] = ['textoR' => $fila['textoR']];
                }
                return $preguntas; // Devuelve las preguntas con sus respuestas
            } else {
                return false;
            }
        }
    }