<?php

require __DIR__.'/../../vendor/autoload.php';

use App\Repositories\DetallePagosRepository;
$repository = new DetallePagosRepository();
header('Content-Type: application/json');

if(isset($_FILES['file']['name'], $_POST['facturaNexen'], $_POST['Num_Operacion'])) {
    $facturaNexen = $_POST['facturaNexen'];
    $Num_Operacion= $_POST['Num_Operacion'];

    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(["error" => "Error al subir el archivo: " . $_FILES['file']['error']]);
        exit();
    }

    if (mime_content_type($_FILES['file']['tmp_name']) !== 'application/pdf') {
        http_response_code(400);
        echo json_encode(["error" => "El archivo debe ser un PDF."]);
        exit();
    }

    $nombreArchivo = $facturaNexen .'_'.$Num_Operacion. '.pdf';
    try {
        $Mensaje_Update = isset($_POST['Mensaje_Update']) ? $_POST['Mensaje_Update'] : '';
        $resultado = $repository->setFileInvoice($_FILES['file']['tmp_name'], $nombreArchivo, $Num_Operacion, $Mensaje_Update);
        echo json_encode(["message" => $resultado]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error al guardar el archivo: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "No se recibió ningún archivo o referencia Nexen."]);
}

?>
