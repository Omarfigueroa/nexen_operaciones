<?php
function transformarFecha($fecha) {
    // Convertir la fecha en formato "dd/mm/aaaa" a una fecha en formato UNIX timestamp
    $timestamp = strtotime($fecha);

    // Convertir la fecha en formato UNIX timestamp a una fecha en formato "aaaa/mm/dd"
    $fecha_transformada = date('Y/m/d', $timestamp);

    return $fecha_transformada;
}

// Ejemplo de uso
//echo transformarFecha('31/12/2022'); // Salida: 2022/12/31
?>

