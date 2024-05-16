<?php
require __DIR__.'../../../vendor/autoload.php';

$repository = new App\Repositories\DetallePagosRepository();

try {
    $data = $repository->all();
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