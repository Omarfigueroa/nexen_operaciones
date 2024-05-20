<?php
// Verificar si se recibieron los datos mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los registros y el footer del cuerpo de la solicitud
    $registros = $_POST['registros'];
    $footer = $_POST['footer'];

    // Convertir los datos JSON en un array de PHP
    $registros = json_decode(json_encode($registros), true);
    $footer = json_decode(json_encode($footer), true);

    print_r($registros);
    die;

    // Aquí puedes procesar y guardar los datos en la base de datos o en un archivo
    // Ejemplo: Guardar en un archivo JSON
    // $data = [
    //     'registros' => $registros,
    //     'footer' => $footer
    // ];

    // Guardar los datos en un archivo JSON
   // file_put_contents('facturas.json', json_encode($data, JSON_PRETTY_PRINT));

    // Responder al cliente
    //echo json_encode(['status' => 'success', 'message' => 'Datos guardados exitosamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
}
?>
