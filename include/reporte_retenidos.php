<?php
date_default_timezone_set('America/Mexico_City');

session_start();
require '../conexion/bd.php';

require '../vendor/autoload.php'; // Ruta a autoload.php de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

try {
    // Establecer la codificación de caracteres UTF-8 para evitar problemas con acentos y caracteres especiales
    header('Content-Type: text/html; charset=utf-8');

    $stmt = $conn_bd->query("SELECT o.fecha_factura, o.NUM_OPERACION, o.Usuario, o.Referencia_Cliente, o.Estatus, o.REFERENCIA_NEXEN, o.DETALLE_MERCANCIA, r.MSA 
                            FROM Operacion_nexen AS o
                            LEFT JOIN Operaciones_retenidas AS r ON o.REFERENCIA_NEXEN = r.REFERENCIA_NEXEN
                            WHERE o.Estatus = 'RETENIDO'");

    // Crear un nuevo objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Escribir los encabezados en la hoja de cálculo
    $sheet->setCellValue('A1', 'Fecha Factura');
    $sheet->setCellValue('B1', 'NUM OPERACION');
    $sheet->setCellValue('C1', 'Usuario');
    $sheet->setCellValue('D1', 'Referencia Cliente');
    $sheet->setCellValue('E1', 'Estatus');
    $sheet->setCellValue('F1', 'Referencia Nexen');
    $sheet->setCellValue('G1', 'DETALLE MERCANCIA');
    $sheet->setCellValue('H1', 'MSA');

    // Definir estilos para las celdas con colores
    $styleGreen = [
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '00FF00']
        ]
    ];
    $styleRed = [
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'FF0000']
        ]
    ];

    // Escribir los datos de la consulta en la hoja de cálculo
    $row = 2;
    while ($row_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $row, $row_data['fecha_factura']);
        $sheet->setCellValue('B' . $row, $row_data['NUM_OPERACION']);
        $sheet->setCellValue('C' . $row, $row_data['Usuario']);
        $sheet->setCellValue('D' . $row, $row_data['Referencia_Cliente']);
        $sheet->setCellValue('E' . $row, $row_data['Estatus']);
        $sheet->setCellValue('F' . $row, $row_data['REFERENCIA_NEXEN']);
        $sheet->setCellValue('G' . $row, $row_data['DETALLE_MERCANCIA']);
        $sheet->setCellValue('H' . $row, $row_data['MSA']);

        // Aplicar estilo de fondo verde o rojo según el valor de MSA
        if ($row_data['MSA'] === 'A') {
            $sheet->getStyle('H' . $row)->applyFromArray($styleGreen);
        } elseif ($row_data['MSA'] === 'I') {
            $sheet->getStyle('H' . $row)->applyFromArray($styleRed);
        }

        $row++;
    }

    // Recorrer todas las celdas para aplicar el estilo amarillo a las celdas vacías
    foreach ($sheet->getRowIterator() as $row) {
        foreach ($row->getCellIterator() as $cell) {
            if (empty($cell->getValue())) {
                $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
            }
            // Agregar bordes a todas las celdas
            $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }
    }

    // Configurar el Writer para guardar el archivo en memoria
    $writer = new Xls($spreadsheet);

    // Configurar el tipo de contenido y el nombre del archivo
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="datos_excel_retenidos.xls"');

    // Enviar el archivo al navegador
    ob_clean();
    flush();
    $writer->save('php://output');
    exit;

} catch (PDOException $e) {
    echo "Error en la conexión o consulta a la base de datos: " . $e->getMessage();
}
?>
