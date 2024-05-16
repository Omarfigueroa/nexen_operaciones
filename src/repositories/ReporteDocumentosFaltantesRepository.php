<?php

namespace App\Repositories;

use App\Models\ReporteDocumentosFaltantes;
use Exception;

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReporteDocumentosFaltantesRepository
{
    protected ReporteDocumentosFaltantes $model;
    protected Spreadsheet $spreadsheet;
    protected $activeWorksheet;

    public function __construct($mes, $anio)
    {
        $this->model = new ReporteDocumentosFaltantes($mes, $anio);
        $this->spreadsheet = new Spreadsheet();
        $this->activeWorksheet = $this->spreadsheet->getActiveSheet();

        $this->applyStyles();
    }

    public function generateXlsx()
    {
        try {
            $results = $this->model->index();

            // Agregar encabezados de columna
            $this->activeWorksheet->setCellValue('A1', 'Referencia Nexen');
            $this->activeWorksheet->setCellValue('B1', 'Concepto');
            $this->activeWorksheet->setCellValue('C1', 'Tipo de Solicitud');
            $this->activeWorksheet->setCellValue('D1', 'Solicitud de Pago');
            $this->activeWorksheet->setCellValue('E1', 'Anticipo de Cliente');
            $this->activeWorksheet->setCellValue('F1', 'Pago a Proveedor');
            $this->activeWorksheet->setCellValue('G1', 'Factura de Proveedor');
    
            $row = 2;
    
            foreach($results as $registro) {
                $this->activeWorksheet->setCellValue('A' . $row, $registro['Referencia_Nexen']);
                $this->activeWorksheet->setCellValue('B' . $row, $registro['Concepto']);
                $this->activeWorksheet->setCellValue('C' . $row, $registro['Tipo_Solicitud']);
                $this->activeWorksheet->setCellValue('D' . $row, $registro['Solicitud_Pagos']);
                $this->activeWorksheet->setCellValue('E' . $row, $registro['Anticipo_Cliente']);
                $this->activeWorksheet->setCellValue('F' . $row, $registro['Pago_Proveedor']);
                $this->activeWorksheet->setCellValue('G' . $row, $registro['Factura_Proveedor']);
    
                $color_verde = 'C6EFCE';
                $color_rojo = 'FFC7CE';
    
                $this->setCellColor(
                    'D',
                    $row,
                    $registro['Solicitud_Pagos'] === 'Si' ? $color_verde : $color_rojo
                );
    
                $this->setCellColor(
                    'E',
                    $row,
                    $registro['Anticipo_Cliente'] === 'Si' ? $color_verde : $color_rojo
                );
    
                $this->setCellColor(
                    'F',
                    $row,
                    $registro['Pago_Proveedor'] === 'Si' ? $color_verde : $color_rojo
                );
    
                $this->setCellColor(
                    'G',
                    $row,
                    $registro['Factura_Proveedor'] === 'Si' ? $color_verde : $color_rojo
                );
    
                $row++;
            }
    
            $writer = new Xlsx($this->spreadsheet);
    
            // redirect output to client browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . urlencode('reporte-df-ref-nexen.xlsx') . '"');
            header('Cache-Control: max-age=0');
    
            ob_clean();
            $writer->save('php://output');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Cambia el color a una celda del documento de Excel.
     *
     * @param string                        $columna Columa. Por ej.: 'A'.
     * @param int                           $row Fila.
     * @param string                        $color Color en hexadecimal (sin #).
     */
    protected function setCellColor($columna, $row, $color)
    {
        $this->activeWorksheet->getStyle($columna . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($color);
    }

    /**
     * Aplica estilos a todo el documento de Excel.
     *
     * @return void
     */
    protected function applyStyles()
    {
        $estilosGenerales = [
            'font' => [
                'name' => 'Arial',
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Color negro
                ],
            ],
        ];

        /**
         * Estilos para la primera fila (nombre de las columnas).
         */
        $estilosNombreColumnas = [
            'font' => [
                'name'  => 'Arial',
                'size'  => 12,
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'], // Color de las letras en blanco
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '000000'], // Color de fondo negro
            ],

        ];

        // Aplicar estilo a la primera fila
        $this->activeWorksheet->getStyle('1')->applyFromArray($estilosNombreColumnas);

        /**
         * Ajustar el tamaño de las celdas al tamaño del texto.
         */
        foreach (range('A', 'Y') as $col) {
            $this->activeWorksheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Aplicar el estilo a todas las celdas
        $this->activeWorksheet->getStyle('A1:Z1000')->applyFromArray($estilosGenerales);
    }
}