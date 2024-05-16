<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
//require_once (INCLUDE_PATH.'validar_sesiones.php');
require_once (CONEXION_PATH.'bd.php'); 

// Declaramos la librería
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$periodo=$_GET['periodo'];

$anmes=explode("-", $_GET['periodo']);

# Encabezado de los productos
//$encabezado = array("AWB","EXTERNAL TRACKING","SHIPPER","ADDRESS SHIPPER","CITY","ZIP CODE","CITY","CITY","CONSIGNEE","ADRESS","CITY","STATE","STATE","STATE","WEIGHT","WEIGHT","VALUE","CURRENCY","DESCRIPTION","QUANTITY","NIVEL","ENCONTRADO");
$encabezado = array("RAZON SOCIAL","CURP","RFC","TIPO SERVICIO","DOMICILIO","DOC DIF","DOC OBLIGACIONES");

$documento = new Spreadsheet();
$hojaDeProductos = $documento->getActiveSheet();
$hojaDeProductos->setTitle("Hoja1");

# El último argumento es por defecto A1
$hojaDeProductos->fromArray([$encabezado], null, 'A1');

$query = "SELECT	Razon_social,
                    CURP,
                    RFC,
                    Tipo_Servicio,
                    Domicilio_Completo,
                    Doc_CIF=CASE Doc_CIF
                                WHEN 0 THEN 'NO'
                                WHEN 1 THEN 'SI'
                            END,
                    Doc_obligaciones=CASE Doc_obligaciones
                                WHEN 0 THEN 'NO'
                                WHEN 1 THEN 'SI'
                            END 
                    FROM (
                    SELECT * FROM (
                    SELECT DISTINCT D.Razon_social, D.CURP, D.RFC, D.Tipo_Servicio, D.Domicilio_Completo, C.Tipo_Documento
                    FROM Cuenta_Destino D
                    LEFT JOIN Cuenta_Destino_Documentos C
                        ON D.Id_Cuenta=C.Id_Cuenta AND Mes='".$anmes[0]."' AND Anio=".$anmes[1]."
                    WHERE RTRIM(LTRIM(D.RFC))!='INTERNACIONAL'
                    ) C
                    PIVOT(
                        COUNT(Tipo_Documento)
                        FOR Tipo_Documento IN (
                            Doc_CIF,
                            Doc_obligaciones
                        ) 
                    ) AS pivot_table
                    ) T 
                    WHERE Doc_CIF=0 OR Doc_obligaciones=0";

$consultar=$conn_bd->prepare($query);

if($consultar){

    $consultar -> execute();
    $result_manifiesto= $consultar -> fetchAll(PDO::FETCH_ASSOC);

    if( $result_manifiesto) {

        $numeroDeFila = 2;

        foreach($result_manifiesto as $manifiest){ 

            # Escribir registros en el documento
            $hojaDeProductos->setCellValue("A".$numeroDeFila."", $manifiest['Razon_social']);
            $hojaDeProductos->setCellValue("B".$numeroDeFila."", $manifiest['CURP']);
            $hojaDeProductos->setCellValue("C".$numeroDeFila."", $manifiest['RFC']);
            $hojaDeProductos->setCellValue("D".$numeroDeFila."", $manifiest['Tipo_Servicio']);
            $hojaDeProductos->setCellValue("E".$numeroDeFila."", $manifiest['Domicilio_Completo']);
            $hojaDeProductos->setCellValue("F".$numeroDeFila."", $manifiest['Doc_CIF']);
            $hojaDeProductos->setCellValue("G".$numeroDeFila."", $manifiest['Doc_obligaciones']);

            switch ($manifiest['Doc_CIF']) {
                case 'NO':
                    $documento->getActiveSheet()->getStyle('F'.$numeroDeFila.':F'.$numeroDeFila.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
                    break;
                case 'SI':
                    $documento->getActiveSheet()->getStyle('F'.$numeroDeFila.':F'.$numeroDeFila.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    break;
            }

            switch ($manifiest['Doc_obligaciones']) {
                case 'NO':
                    $documento->getActiveSheet()->getStyle('G'.$numeroDeFila.':G'.$numeroDeFila.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
                    break;
                case 'SI':
                    $documento->getActiveSheet()->getStyle('G'.$numeroDeFila.':G'.$numeroDeFila.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    break;
            }
            

            $numeroDeFila++;

        }

    }else{

    }

}


    $fileName="Cuentas_Documentos.xlsx";


$writer = new Xlsx($documento);
//$writer->save($fileName);

ob_clean();
//flush();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save("php://output");

//echo json_encode(["success" => true, "message" => "Archivo descargado"]);

?>