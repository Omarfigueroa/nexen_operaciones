<?php
include_once "../dompdf/autoload.inc.php";
require '../conexion/bd.php';

use Dompdf\Dompdf;

        $dompdf = new Dompdf();
        ob_start();

        if( isset($_GET['id']) && !empty($_GET['id'])){
        $id_factura = $_GET['id'];
        }else{
        $id_factura = $_GET['id_factura'];
        }

        $sql_facturas = "SELECT F.Referencia_Nexen,
                                F.Proveedor,
                                P.domicilio,
                                F.Tax_Id,
                                F.Numero_Factura,
                                F.Fecha_Factura,
                                F.Importador_Exportador,
                                F.RFC_Importador_Exportador,
                                F.Domicilio_Fiscal,
                                F.Total_General
                        FROM Operacion_Facturas F 
                                inner join provedores P 
                                        ON F.Proveedor=P.Proveedor 
                        WHERE F.id_factura=$id_factura";

       
        $consulta_facturas = $conn_bd->prepare($sql_facturas);
        $consulta_facturas -> execute();
        $facturas = $consulta_facturas -> fetch(PDO::FETCH_ASSOC);

        if ($facturas) {
                $proveedor=$facturas['Proveedor'];
                $domicilio=$facturas['domicilio'];
                $tax_id=$facturas['Tax_Id'];
                $no_factura=$facturas['Numero_Factura'];
                $fecha_factura=$facturas['Fecha_Factura'];
                $importador_exportador=$facturas['Importador_Exportador'];
                $domicilio_fiscal=$facturas['Domicilio_Fiscal'];
                $total_general=$facturas['Total_General'];
        }



        $sql_conceptos= "SELECT Cantidad,  
                                Unidad_Medida,
                                Moneda,
                                Descripcion_Cove,
                                Precio_Unitario,
                                Total		
                        FROM Operacion_Facturas_Detalle
                        WHERE Id_Factura=$id_factura";
        
        $consulta_conceptos = $conn_bd->prepare($sql_conceptos);
        $consulta_conceptos -> execute();
        $conceptos = $consulta_conceptos -> fetchAll(PDO::FETCH_ASSOC);
        

        include "facturaPDF.php";
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->render();
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=CONTRATO_GENERADO.pdf");
        echo $dompdf->output();
        $dompdf->stream();
     
?>
<head >
        <title>Descargar Comprobante</title>
</head>