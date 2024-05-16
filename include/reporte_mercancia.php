<?php
date_default_timezone_set('America/Mexico_City');

session_start();
require '../conexion/bd.php';

require '../vendor/autoload.php'; // Ruta a autoload.php de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

try {
    // Realizar la consulta a la base de datos (ajusta la consulta según tus necesidades)
    $stmt = $conn_bd->query("SELECT fecha_factura, NUM_OPERACION, Usuario, Referencia_Cliente, Estatus, REFERENCIA_NEXEN, DETALLE_MERCANCIA FROM Operacion_nexen");

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

    // Definir estilo para las celdas vacías (amarillo con bordes)
    $styleYellow = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'FFFF00']
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
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

        // Aplicar estilo amarillo a las celdas vacías
        foreach (range('A', 'G') as $col) {
            $cellValue = $sheet->getCell($col . $row)->getValue();
            if (empty($cellValue)) {
                $sheet->getStyle($col . $row)->applyFromArray($styleYellow);
            }
        }

        $row++;
    }

    // Configurar el Writer para guardar el archivo en memoria
    $writer = new Xls($spreadsheet);

    // Configurar el tipo de contenido y el nombre del archivo
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="datos_excel_mercancia.xls"');

    // Enviar el archivo al navegador
    ob_clean();
    flush();
    $writer->save('php://output');
    exit;

} catch (PDOException $e) {
    echo "Error en la conexión o consulta a la base de datos: " . $e->getMessage();
}
?>
