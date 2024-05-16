<?php

namespace App\Repositories;

use App\Models\ReporteCarpetas;
use Exception;

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReporteCarpetasRepository
{
    private ReporteCarpetas $reporteCarpetas;
    private Spreadsheet $spreadsheet;
    private $activeWorksheet;

    public function __construct()
    {
        $this->reporteCarpetas = new ReporteCarpetas;
        $this->spreadsheet = new Spreadsheet();
        $this->activeWorksheet = $this->spreadsheet->getActiveSheet();

        $this->applyStyles();
    }

    /**
     * Genera el documento XLSX.
     *
     * @return void
     */
    public function generar()
    {
        try {
            $results = $this->reporteCarpetas->index();

            /**
             * Agregar encabezados a las columnas del documento.
             */
            $this->activeWorksheet->setCellValue('A1', 'REFERENCIA NEXEN');
            $this->activeWorksheet->setCellValue('B1', 'GUIA');
            $this->activeWorksheet->setCellValue('C1', 'PAKING LIST ORIGINAL');
            $this->activeWorksheet->setCellValue('D1', 'FACTURA ORIGINAL');
            $this->activeWorksheet->setCellValue('E1', 'BL_ORIGEN_O_HOUSE ORIGINAL');
            $this->activeWorksheet->setCellValue('F1', 'EXPEDIENTE DIGITAL');
            $this->activeWorksheet->setCellValue('G1', 'CUENTA DE GASTOS');
            $this->activeWorksheet->setCellValue('H1', 'OTROS');
            $this->activeWorksheet->setCellValue('I1', 'OTROS_1');
            $this->activeWorksheet->setCellValue('J1', 'OTROS_2');
            $this->activeWorksheet->setCellValue('K1', 'OTROS_3');
            $this->activeWorksheet->setCellValue('L1', 'OTROS_4');
            $this->activeWorksheet->setCellValue('M1', 'OTROS_5');
            $this->activeWorksheet->setCellValue('N1', 'OTROS_6');
            $this->activeWorksheet->setCellValue('O1', 'OTROS_7');
            $this->activeWorksheet->setCellValue('P1', 'OTROS_8');

            $row = 2;

            /**
             * Insertar los registros en las celdas.
             */
            foreach($results as $registro) {
                $this->activeWorksheet->setCellValue('A' . $row, $registro['Referencia_Nexen']);
                $this->activeWorksheet->setCellValue('B' . $row, $registro['GUIA']);
                $this->activeWorksheet->setCellValue('C' . $row, $registro['PAKING_LIST_ORIGINAL']);
                $this->activeWorksheet->setCellValue('D' . $row, $registro['FACTURA_ORIGINAL']);
                $this->activeWorksheet->setCellValue('E' . $row, $registro['BL_ORIGEN_O_HOUSE']);
                $this->activeWorksheet->setCellValue('F' . $row, $registro['EXPEDIENTE_DIGITAL']);
                $this->activeWorksheet->setCellValue('G' . $row, $registro['CUENTA_DE_GASTOS']);
                $this->activeWorksheet->setCellValue('H' . $row, $registro['OTROS']);
                $this->activeWorksheet->setCellValue('I' . $row, $registro['OTROS_1']);
                $this->activeWorksheet->setCellValue('J' . $row, $registro['OTROS_2']);
                $this->activeWorksheet->setCellValue('K' . $row, $registro['OTROS_3']);
                $this->activeWorksheet->setCellValue('L' . $row, $registro['OTROS_4']);
                $this->activeWorksheet->setCellValue('M' . $row, $registro['OTROS_5']);
                $this->activeWorksheet->setCellValue('N' . $row, $registro['OTROS_6']);
                $this->activeWorksheet->setCellValue('O' . $row, $registro['OTROS_7']);
                $this->activeWorksheet->setCellValue('P' . $row, $registro['OTROS_8']);

                $color_verde = 'C6EFCE';
                $color_rojo = 'FFC7CE';

                $this->setCellColor(
                    'B',
                    $row,
                    $registro['GUIA'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'C',
                    $row,
                    $registro['PAKING_LIST_ORIGINAL'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'D',
                    $row,
                    $registro['FACTURA_ORIGINAL'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'E',
                    $row,
                    $registro['BL_ORIGEN_O_HOUSE'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'F',
                    $row,
                    $registro['EXPEDIENTE_DIGITAL'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'G',
                    $row,
                    $registro['CUENTA_DE_GASTOS'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'H',
                    $row,
                    $registro['OTROS'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'I',
                    $row,
                    $registro['OTROS_1'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'J',
                    $row,
                    $registro['OTROS_2'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'K',
                    $row,
                    $registro['OTROS_3'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'L',
                    $row,
                    $registro['OTROS_4'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'M',
                    $row,
                    $registro['OTROS_5'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'N',
                    $row,
                    $registro['OTROS_6'] === 'Si' ? $color_verde : $color_rojo
                );

                $this->setCellColor(
                    'O',
                    $row,
                    $registro['OTROS_7'] === 'Si' ? $color_verde : $color_rojo
                );
                
                $this->setCellColor(
                    'P',
                    $row,
                    $registro['OTROS_8'] === 'Si' ? $color_verde : $color_rojo
                );

                $row++;
            }

            $writer = new Xlsx($this->spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . urlencode('reporte-carpetas.xlsx') . '"');
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
     * @param string $columna Columa. Por ej.: 'A'.
     * @param int    $row Fila.
     * @param string $color Color en hexadecimal (sin #).
     */
    protected function setCellColor(string $columna, int $row, string $color)
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
    private function applyStyles()
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
        foreach (range('A', 'P') as $col) {
            $this->activeWorksheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Aplicar el estilo a todas las celdas
        $this->activeWorksheet->getStyle('A1:P2600')->applyFromArray($estilosGenerales);
    }
}