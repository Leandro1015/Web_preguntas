<?php
    require_once 'conexion.php';

    class M_pregunta extends Conectar {
        public function insertar($textoPregunta, $respuestas) {
            $sqlPregunta = "INSERT INTO pregunta (textoP) VALUES ('$textoPregunta')";
            if ($this->conexion->query($sqlPregunta)) {
                $pregunta_id = $this->conexion->insert_id;

                foreach ($respuestas as $respuesta) {
                    $sqlRespuesta = "INSERT INTO respuesta (textoR, idPregunta) VALUES ('$respuesta', $pregunta_id)";
                    if (!$this->conexion->query($sqlRespuesta)) {
                        return "Error al insertar respuesta: ".$this->conexion->error;
                    }
                }
                return true;
            } else {
                return "Error al insertar pregunta: ".$this->conexion->error;
            }
        }
    }