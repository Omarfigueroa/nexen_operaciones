<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Repositories\ReporteCarpetasRepository;

try {
    $reporteCarpetasRepository = new ReporteCarpetasRepository;
    $reporteCarpetasRepository->generar();

    http_response_code(200);
} catch (Exception $e) {
    header('Content-Type: application/json');

    echo json_encode([
        'data' => '',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}