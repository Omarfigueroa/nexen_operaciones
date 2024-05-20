<?php
date_default_timezone_set('America/Mexico_City');
session_start();
require('../../conexion/bd.php');

// Verificar si se recibieron los datos mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el contenido de la solicitud
    $json = file_get_contents('php://input');
    // Decodificar los datos JSON
    $data = json_decode($json, true);

    // Obtener los registros, el footer y el operador del cuerpo de la solicitud
    $registros = $data['registros'];
    $footer = $data['footer'];
    $operador = $data['operador'];

    // Aquí puedes procesar y guardar los datos en la base de datos o en un archivo
    // Ejemplo: Guardar en un archivo JSON
    $dataToSave = [
        'registros' => $registros,
        'footer' => $footer,
        'operador' => $operador
    ];

    try {
        // Primer insert
        $referencia_nexen = $operador['referencia_nexen'];
        $pais_origen = $registros[0]['pais'];
        $nombreOperador = $operador['nombreOperador'];
        $rfcOperador = $operador['rfcOperador'];
        $domOperador = $operador['domOperador'];
        $proveedorFact = $registros[0]['Proveedor'];
        $taxId = $operador['taxId'];
        $numFactura = $registros[0]['num_facturas'];
        $fechaFactura = $registros[0]['fecha'];
        $total = $footer['totalgeneral'];
        $fechope = date('Y-m-d');
        $horaope = date('H:i:s');
        $usuario = $_SESSION['usuario_nexen'];
        $total_peso_bruto = $footer['totalpesobruto'];
        $total_peso_neto = $footer['totalpesoneto'];

        $query = "SELECT Numero_Factura FROM [dbo].[Operacion_Facturas] WHERE [Numero_Factura] = :numFactura";
        $stmt = $conn_bd->prepare($query);
        $stmt->bindParam(':numFactura', $numFactura);
        $stmt->execute();
        $valida_result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($valida_result)) {
            $response = ['success' => false, 'message' => 'Ya existe el numero de factura: ' . $numFactura . ' intenta con otro.'];
        } else {
            $query1 = "INSERT INTO [Operacion_Facturas] ( [Referencia_Nexen], [Proveedor], [Tax_Id], [Numero_Factura], [Fecha_Factura], [Importador_Exportador], [RFC_Importador_Exportador], [Domicilio_Fiscal], [Total_General], [Fechope], [Horaope], [Usuario], [Estatus], [PAIS_ORIGEN], [PESO_BRUTO_TOTAL], [PESO_NETO_TOTAL]) 
                       VALUES (:referencia_nexen, :proveedorFact, :taxId, :numFactura, :fechaFactura, :nombreOperador, :rfcOperador, :domOperador, :total, :fechope, :horaope, :usuario, 'A', :modal_pais_origen, :total_peso_bruto, :total_peso_neto)";

            $conn_bd->beginTransaction();

            $statement1 = $conn_bd->prepare($query1);
            $statement1->bindParam(':referencia_nexen', $referencia_nexen);
            $statement1->bindParam(':modal_pais_origen', $pais_origen);
            $statement1->bindParam(':nombreOperador', $nombreOperador);
            $statement1->bindParam(':proveedorFact', $proveedorFact);
            $statement1->bindParam(':taxId', $taxId);
            $statement1->bindParam(':numFactura', $numFactura);
            $statement1->bindParam(':fechaFactura', $fechaFactura);
            $statement1->bindParam(':rfcOperador', $rfcOperador);
            $statement1->bindParam(':domOperador', $domOperador);
            $statement1->bindParam(':total', $total);
            $statement1->bindParam(':fechope', $fechope);
            $statement1->bindParam(':horaope', $horaope);
            $statement1->bindParam(':usuario', $usuario);
            $statement1->bindParam(':total_peso_bruto', $total_peso_bruto);
            $statement1->bindParam(':total_peso_neto', $total_peso_neto);

            if ($statement1->execute()) {
                $lastId = $conn_bd->lastInsertId();
                $query2 = "INSERT INTO [dbo].[Operacion_Facturas_Detalle] ( [Referencia_Nexen],[Id_Factura], [Numero_Factura], [Numero_Partida], [Descripcion_Cove], [Cantidad],[Unidad_Medida],[Moneda], [Precio_Unitario], [Total], [Estatus], [Fechope], [Horaope],[Usuario], [Peso_Bruto], [Peso_Neto], [Incoterms], [Descripcion_cove_I], [Mark] ) 
                           VALUES (:referencia_nexen, :lastId, :numFactura, :partida, :descripcion, :cantidad, :medida, :moneda, :precioUnitario, :total, 'A', :fechope, :horaope, :usuario, :peso_bruto, :peso_neto, :incoterms, :descripcion_i, :mark)";

                $statement2 = $conn_bd->prepare($query2);
                $partida = 1;

                foreach ($registros as $registro) {
                    $statement2->bindParam(':referencia_nexen', $referencia_nexen);
                    $statement2->bindParam(':lastId', $lastId);
                    $statement2->bindParam(':numFactura', $numFactura);
                    $statement2->bindParam(':partida', $partida);
                    $statement2->bindParam(':descripcion', $registro['coveEspañol']);
                    $statement2->bindParam(':descripcion_i', $registro['coveingles']);
                    $statement2->bindParam(':cantidad', $registro['cantidad']);
                    $statement2->bindParam(':medida', $registro['unidad']);
                    $statement2->bindParam(':precioUnitario', $registro['valorunitario']);
                    $statement2->bindParam(':moneda', $registro['moneda']);
                    $statement2->bindParam(':total', $registro['totalpartida']);
                    $statement2->bindParam(':fechope', $fechope);
                    $statement2->bindParam(':horaope', $horaope);
                    $statement2->bindParam(':usuario', $usuario);
                    $statement2->bindParam(':peso_bruto', $registro['brutro']);
                    $statement2->bindParam(':peso_neto', $registro['neto']);
                    $statement2->bindParam(':incoterms', $registro['icoterm']);
                    $statement2->bindParam(':mark', $registro['mark']);

                    // Ejecutar la consulta
                    $statement2->execute();

                    // Incrementar el número de partida
                    $partida++;
                }

                $conn_bd->commit();
                $response = ['success' => true, 'message' => 'Datos insertados correctamente'];
            } else {
                $conn_bd->rollBack();
                $response = ['success' => false, 'message' => 'Error al insertar en la tabla "Operacion_Facturas"'];
            }
        }
    } catch (PDOException $e) {
        // En caso de error, revertir la transacción
        $conn_bd->rollBack();
        $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }

    echo json_encode($response);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
}
?>
