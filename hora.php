<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer desde el servidor</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
    // Obtener la hora del servidor en PHP
    date_default_timezone_set('America/El_Salvador'); // Ajusta la zona horaria
    $hora_servidor = date('H:i:s');
    ?>

    <h1>Hora del servidor: <span id="horaServidor"><?php echo $hora_servidor; ?></span></h1>
    <h2>Timer: <span id="timer"></span></h2>

    <script>
        $(document).ready(function() {
            // Función para agregar ceros si el valor es menor a 10
            function padZero(value) {
                return value < 10 ? '0' + value : value;
            }

            // Obtener la hora del servidor desde el HTML generado por PHP
            let horaServidor = $('#horaServidor').text();
            
            // Función para iniciar el timer
            function iniciarTimer(horaServidor) {
                let partesHora = horaServidor.split(':');
                let horas = parseInt(partesHora[0]);
                let minutos = parseInt(partesHora[1]);
                let segundos = parseInt(partesHora[2]);

                // Función que actualiza el timer cada segundo
                setInterval(function() {
                    segundos++;

                    if (segundos >= 60) {
                        segundos = 0;
                        minutos++;
                    }

                    if (minutos >= 60) {
                        minutos = 0;
                        horas++;
                    }

                    if (horas >= 24) {
                        horas = 0;
                    }

                    // Actualizar el DOM
                    $('#timer').text(padZero(horas) + ':' + padZero(minutos) + ':' + padZero(segundos));
                }, 1000);
            }

            // Iniciar el timer con la hora obtenida
            iniciarTimer(horaServidor);
        });
    </script>
</body>
</html>
