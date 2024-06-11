document.addEventListener('DOMContentLoaded', function() {
    const divEditor = document.getElementById('divEditor');
    const anadirPreguntaButton = document.getElementById('anadirPreguntaButton');
    const formularioPreguntas = document.getElementById('formularioPreguntas');
    const errorMessage = document.getElementById('error-message');

    anadirPreguntaButton.addEventListener('click', function() {
        añadirPregunta();
    });

    formularioPreguntas.addEventListener('submit', function(event) {
        if (!validarFormulario()) {
            event.preventDefault(); // Evita el envío del formulario si no es válido
        }
    });

    function añadirPregunta() {
        const nuevaPreguntaDiv = document.createElement('div');
        nuevaPreguntaDiv.classList.add('divPregunta');

        const labelPregunta = document.createElement('label');
        labelPregunta.textContent = "Pregunta: ";
        nuevaPreguntaDiv.appendChild(labelPregunta);

        const inputTextoPregunta = document.createElement('input');
        inputTextoPregunta.setAttribute('type', 'text');
        inputTextoPregunta.setAttribute('name', 'preguntas[]');
        nuevaPreguntaDiv.appendChild(inputTextoPregunta);

        const labelRespuestas = document.createElement('label');
        labelRespuestas.textContent = "Respuestas: ";
        nuevaPreguntaDiv.appendChild(labelRespuestas);

        const respuestasContainer = document.createElement('div');
        respuestasContainer.classList.add('respuestasContainer');
        nuevaPreguntaDiv.appendChild(respuestasContainer);

        const anadirRespuestaButton = document.createElement('button');
        anadirRespuestaButton.setAttribute('type', 'button');
        anadirRespuestaButton.textContent = 'Añadir Respuesta';
        anadirRespuestaButton.classList.add('boton');
        nuevaPreguntaDiv.appendChild(anadirRespuestaButton);

        anadirRespuestaButton.addEventListener('click', function() {
            const inputRespuesta = document.createElement('input');
            inputRespuesta.setAttribute('type', 'text');
            inputRespuesta.setAttribute('name', 'respuestas[' + (document.querySelectorAll('.divPregunta').length - 1) + '][]');
            respuestasContainer.appendChild(inputRespuesta);
            respuestasContainer.appendChild(document.createElement('br'));
        });

        const eliminarPreguntaButton = document.createElement('button');
        eliminarPreguntaButton.setAttribute('type', 'button');
        eliminarPreguntaButton.textContent = 'Eliminar Pregunta';
        eliminarPreguntaButton.classList.add('boton');
        nuevaPreguntaDiv.appendChild(eliminarPreguntaButton);

        eliminarPreguntaButton.addEventListener('click', function() {
            nuevaPreguntaDiv.remove();
        });

        divEditor.appendChild(nuevaPreguntaDiv);
    }

    function validarFormulario() {
        const preguntas = document.querySelectorAll('.divPregunta');
        let esValido = true;
        let mensaje = '';

        if (preguntas.length === 0) {
            esValido = false;
            mensaje = 'Por favor, agregue al menos una pregunta.';
        } else {
            preguntas.forEach(pregunta => {
                const inputPregunta = pregunta.querySelector('input[name="preguntas[]"]');
                const respuestas = pregunta.querySelectorAll('input[name^="respuestas"]');

                if (inputPregunta.value.trim() === '') {
                    esValido = false;
                    mensaje = 'Por favor, complete todos los campos de pregunta.';
                } else if (respuestas.length === 0) {
                    esValido = false;
                    mensaje = 'Por favor, agregue al menos una respuesta por pregunta.';
                } else {
                    respuestas.forEach(respuesta => {
                        if (respuesta.value.trim() === '') {
                            esValido = false;
                            mensaje = 'Por favor, complete todos los campos de respuesta.';
                        }
                    });
                }
            });
        }

        errorMessage.textContent = mensaje;
        return esValido;
    }
});
