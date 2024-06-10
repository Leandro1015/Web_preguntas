<?php
    require_once './modelo/m_pregunta.php';
    class Controlador_p {
        public $nombre_vista;
        private $pregunta;
    
        public function __construct() {
            $this->pregunta = new M_pregunta();
        }
    
        public function mostrarFP() {
            $this->nombre_vista = './vistas/pregunta';
        }
    
        public function guardar() {
            $msj = null;
    
            if (!empty($_POST['textoPregunta']) && !empty($_POST['respuestas'])) {
                $textoPregunta = $_POST['textoPregunta'];
                $respuestas = $_POST['respuestas'];
        
                $resultado = $this->pregunta->insertar($textoPregunta, $respuestas);
            
                if ($resultado === true) {
                    $this->nombre_vista = './vistas/exito';
                } else {
                    $msj = $resultado;
                    $this->nombre_vista = './vistas/pregunta.php';
                }   
            } else { 
                $msj = "Por favor, complete todos los campos.";
                $this->nombre_vista = './vistas/pregunta.php';
            }
    
            return $msj;      
        }  
    }
   