<?php

require __DIR__ . '../../../vendor/autoload.php';

try {
    $repository = new App\Repositories\ReporteDocumentosFaltantesRepository($_GET['mes'], $_GET['anio']);
    $repository->generateXlsx();
    http_response_code(200);
} catch (\Exception $e) {
    header('Content-Type: application/json'); 
    echo json_encode([
        'data' => '',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    }