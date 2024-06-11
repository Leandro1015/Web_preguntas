<?php
require_once './modelo/m_pregunta.php';

class Controlador_p {
    public $nombre_vista;
    private $pregunta;

    public function __construct() {
        $this->pregunta = new M_pregunta();
    }

    public function mostrarFP() {
        $this->nombre_vista = 'vista/pregunta';
    }

    public function guardar() {
        $msj = null;

        if (!empty($_POST['preguntas']) && !empty($_POST['respuestas'])) {
            $textoPreguntas = $_POST['preguntas'];
            $respuestas = $_POST['respuestas'];

            if (count($textoPreguntas) == 0 || count($respuestas) == 0) {
                $msj = "Por favor, agregue una pregunta y al menos una respuesta.";
                $this->nombre_vista = 'vista/pregunta';
            } else {
                $resultado = true;
                // Recorrer todas las preguntas
                foreach ($textoPreguntas as $index => $textoPregunta) { //$index, variable que indica la posición de cada pregunta y su respuesta dentro de los arrays $_POST['preguntas'] y $_POST['respuestas'].
                    // Verificar que la pregunta y sus respuestas no estén vacías
                    if (!empty($textoPregunta) && !empty($respuestas[$index])) {
                        // Insertar la pregunta y sus respuestas en la base de datos
                        $resultado = $this->pregunta->insertar($textoPregunta, $respuestas[$index]);
                        if ($resultado !== true) {
                            break;
                        }
                    } else {
                        $resultado = "Por favor, complete todos los campos.";
                        break;
                    }
                }

                if ($resultado === true) {
                    $this->nombre_vista = 'vista/exito';
                } else {
                    $msj = $resultado;
                    $this->nombre_vista = 'vista/pregunta';
                }
            }
        } else {
            $msj = "Por favor, agregue una pregunta.";
            $this->nombre_vista = 'vista/pregunta';
        }

        return $msj;
    }
}
