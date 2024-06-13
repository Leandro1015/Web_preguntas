<?php
    require_once './modelo/m_pregunta.php';
    require_once '/home/proyectosevg/public_html/2daw01/web_preguntas/fpdf/fpdf.php';

    class Controlador_p {
        public $nombre_vista; // Nombre de la vista a cargar
        private $pregunta; // Instancia del modelo M_pregunta

        /**
         * Constructor de la clase Controlador_p.
         * Inicializa una nueva instancia del modelo M_pregunta.
         */
        public function __construct() {
            $this->pregunta = new M_pregunta(); // Crear una nueva instancia del modelo M_pregunta
        }

        /**
         * Muestra el formulario de preguntas.
         * Asigna la vista correspondiente al formulario de preguntas.
         * @return void
         */
        public function mostrarFP() {
            $this->nombre_vista = 'vista/pregunta'; // Asignar la vista del formulario de preguntas
        }

        /**
         * Guarda las preguntas y respuestas enviadas desde el formulario.
         * Valida que haya al menos una pregunta y una respuesta antes de guardar.
         * @return string|null Mensaje de error o éxito
         */
        public function guardar() {
            $msj = null; // Variable para almacenar mensajes de error o éxito

            // Verificar si se han enviado preguntas y respuestas mediante POST
            if ($this->esPost()) {
                if (!empty($_POST['preguntas']) && !empty($_POST['respuestas'])) {
                    $textoPreguntas = $_POST['preguntas']; // Array de preguntas
                    $respuestas = $_POST['respuestas']; // Array de respuestas

                    // Verificar si hay al menos una pregunta y una respuesta
                    if (count($textoPreguntas) == 0 || count($respuestas) == 0) {
                        $msj = "Por favor, agregue una pregunta y al menos una respuesta.";
                        $this->nombre_vista = 'vista/pregunta';
                    } else {
                        $resultado = true;
                        // Recorrer todas las preguntas
                        foreach ($textoPreguntas as $index => $textoPregunta) {
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

                        // Verificar el resultado de la inserción
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
            } else {
                $msj = "Método de solicitud incorrecto. Por favor, intenta nuevamente.";
                $this->nombre_vista = 'vista/pregunta'; // Redirigir a la vista del formulario de preguntas
            }

            return $msj;
        }

        /**
         * Genera un PDF con las preguntas y respuestas guardadas.
         * @return void
         */
        public function bajarpdf() {
            // Obtenemos todas las preguntas y respuestas desde la base de datos
            $preguntas = $this->pregunta->obtenerTodasLasPreguntas();

            if ($preguntas) {
                $this->crearPDF($preguntas);
            } else {
                echo "No se encontraron preguntas y respuestas.";
            }
        }

        /**
         * Crea y descarga un PDF con las preguntas y respuestas proporcionadas.
         *
         * @param array $preguntas Las preguntas y respuestas.
         * @return void
         */
        private function crearPDF($preguntas) {
            // Creamos el PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);

            // Añadimos un título genérico
            $pdf->Cell(0, 10, 'Preguntas y Respuestas', 0, 1, 'C');

            // Añadimos las preguntas y respuestas al PDF
            $pdf->SetFont('Arial', '', 12);
            foreach ($preguntas as $pregunta) {
                $pdf->Ln(10);
                $pdf->MultiCell(0, 10, utf8_decode($pregunta['textoP']));

                // Ajustamos la posición horizontal de las respuestas (tabulamos)
                $tabWidth = 10;  // Define el ancho de la tabulación
                foreach ($pregunta['respuestas'] as $respuesta) {
                    $pdf->Ln(5);
                    $pdf->SetX($pdf->GetX() + $tabWidth);  // Ajustar la posición horizontal
                    $pdf->MultiCell(0, 10, utf8_decode('- ' . $respuesta['textoR']));
                }
            }

            // Salida del PDF
            $pdf->Output('D', 'preguntas_respuestas.pdf');
            exit;
        }

        /**
         * Guarda un archivo PDF subido por el usuario en la carpeta 'pdfs'.
         * @return string|null Mensaje de éxito o error, o null en caso de no haber archivo subido.
         */
     
         public function guardarArchivos() {
            $mensajeError = null;
    
            // Verificar si se ha enviado un archivo mediante el formulario
            if ($this->esPost() && isset($_FILES['archivo'])) {
                $archivo = $_FILES['archivo'];
                $tipoArchivo = $archivo['type'];
                $esPDF = $tipoArchivo === 'application/pdf';
                $esImagen = in_array($tipoArchivo, ['image/jpeg', 'image/png', 'image/gif']);
    
                // Verificar que el archivo sea un PDF o una imagen
                if ($esPDF || $esImagen) {
                    // Directorio donde se guardarán los archivos subidos
                    $directorioDestino = $esPDF ? 'pdfs/' : 'imagenes/';
    
                    // Verificar si el directorio de destino existe, si no, intenta crearlo
                    if (!file_exists($directorioDestino) && !mkdir($directorioDestino, 0777, true)) {
                        $mensajeError = "Error al crear el directorio de destino.";
                        $this->nombre_vista = 'vista/subidaNoExitosa';
                        return $mensajeError;
                    }
    
                    $nombreArchivo = basename($archivo['name']);
                    $rutaDestino = $directorioDestino . $nombreArchivo;
    
                    // Mover el archivo subido al directorio destino
                    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                        // Guardar información del archivo en la base de datos
                        $nombreOriginal = $archivo['name'];
                        $nombreGuardado = $nombreArchivo;
                        $rutaArchivo = $rutaDestino;
    
                        $resultado = $this->pregunta->guardarArchivo($nombreOriginal, $nombreGuardado, $rutaArchivo);
    
                        if ($resultado === true) {
                            $mensajeError = "El archivo se ha subido y guardado correctamente.";
                            $this->nombre_vista = 'vista/subida';
                        } else {
                            $mensajeError = "Error al guardar el archivo en la base de datos: " . $resultado;
                            $this->nombre_vista = 'vista/subidaNoExitosa';
                        }
                    } else {
                        $mensajeError = "Hubo un error al subir el archivo.";
                        $this->nombre_vista = 'vista/subidaNoExitosa';
                    }
                } else {
                    $mensajeError = "Por favor, suba un archivo PDF o una imagen válida.";
                    $this->nombre_vista = 'vista/subidaNoExitosa';
                }
            } else {
                $mensajeError = "No se ha seleccionado ningún archivo.";
                $this->nombre_vista = 'vista/subidaNoExitosa';
            }
    
            return $mensajeError;
        }

        /**
         * Verifica si la solicitud es de tipo POST.
         * @return bool True si es POST, False si no.
         */
        private function esPost() {
            return $_SERVER['REQUEST_METHOD'] === 'POST';
        }
    }
