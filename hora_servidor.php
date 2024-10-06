<?php
// script hora_servidor.php
date_default_timezone_set('America/El_Salvador'); // Ajusta a tu zona horaria
    echo json_encode([
        'hora_servidor' => date('H:i:s'),
        //Prueba
        //Prueba 2
    ]);
?>