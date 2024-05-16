<?php
require __DIR__.'../../../vendor/autoload.php';

date_default_timezone_set('America/Mexico_City');
$id = $_POST['Referencia_Nexen'];

$repository = new App\Repositories\DetallePagosRepository();

try {
    $data = $repository->show($id);
    http_response_code(200);
    echo json_encode([
        'data' => $data,
        'message' => 'successful request'
    ]);
}catch(\Exception $e) {
    echo json_encode([
        'data' => '',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}