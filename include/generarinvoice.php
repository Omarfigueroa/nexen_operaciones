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
                                F.Total_General,
                                F.RFC_Importador_Exportador,
                                F.PAIS_ORIGEN
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
                $pais_origen=$facturas['PAIS_ORIGEN'];
		        $rfc=$facturas['RFC_Importador_Exportador'];
        }



        $sql_conceptos= "SELECT	Id_Detalle_Factura,
                                Mark,
                                Numero_Partida,
                                Cantidad,  
                                Unidad_Medida,
                                Moneda,
                                Descripcion_cove_I,
                                CONVERT(float,Precio_Unitario) Precio_Unitario,
                                --Precio_Unitario,
                                CONVERT(float,Total) Total,
                                --Total,
                                Peso_Bruto,
                                Peso_Neto,
                                Mark		
                        FROM Operacion_Facturas_Detalle
                        WHERE Id_Factura=$id_factura
                        UNION
                        SELECT	max(D.Id_Detalle_Factura)+1 as Id_Detalle_Factura,
                                NULL,
                                NULL,
                                SUM(D.Cantidad) Cantidad,  
                                NULL,
                                NULL,
                                NULL,
                                NULL,
                                F.Total_General Total,
                                NULL,
                                NULL,
                                NULL		
                        FROM Operacion_Facturas_Detalle D
                                INNER JOIN Operacion_Facturas F ON D.Id_Factura=F.Id_Factura
                        WHERE F.Id_Factura=$id_factura
                        GROUP BY F.Total_General
                        ORDER BY Id_Detalle_Factura asc";
        
        $consulta_conceptos = $conn_bd->prepare($sql_conceptos);
        $consulta_conceptos -> execute();
        $conceptos = $consulta_conceptos -> fetchAll(PDO::FETCH_ASSOC);


        $sql_moneda  = "SELECT	TOP 1 Moneda
                        FROM Operacion_Facturas_Detalle
                        WHERE Id_Factura=$id_factura";
                        
        $consulta_moneda = $conn_bd->prepare($sql_moneda);
        $consulta_moneda -> execute();
        //$moneda = $consulta_moneda -> fetchAll(PDO::FETCH_ASSOC);
        $moneda = $consulta_moneda->fetch(PDO::FETCH_ASSOC);


        $sql_incot  =  "SELECT	TOP 1 Incoterms
                        FROM Operacion_Facturas_Detalle
                        WHERE Id_Factura=$id_factura";
        
        $consulta_incot = $conn_bd->prepare($sql_incot);
        $consulta_incot -> execute();
        //$moneda = $consulta_moneda -> fetchAll(PDO::FETCH_ASSOC);
        $incot = $consulta_incot->fetch(PDO::FETCH_ASSOC);


        include "invoicePDF.php";
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->render();
        header("Content-type: application/pdf");
        ///header("Content-Disposition: inline; filename=CONTRATO_GENERADO.pdf");
        echo $dompdf->output();
        $dompdf->stream("pdf.pdf", ['Attachment' => false]);
        //$dompdf->stream();
     
?>
<head >
        <title>Descargar Comprobante</title>
</head>