<?php
date_default_timezone_set('America/Mexico_City');


session_start();
require '../conexion/bd.php';



// Obtener el valor de la opción y el nombre de operador enviados por AJAX
$opcion = $_POST['opcion'];
//$nombreOperador = $_POST['nombreOperador'];
$nombreOperador = isset($_POST['nombreOperador']) ? $_POST['nombreOperador'] : '';

$usuario = $_SESSION['usuario_nexen'];

$query_user_name ="SELECT * FROM [dbo].[Usuarios_Login] WHERE Usuario = '$usuario'";
$select_user_name = $conn_bd->prepare($query_user_name);

if($select_user_name -> execute()){
    $result_name = $select_user_name -> fetch(PDO::FETCH_ASSOC); 
    $name_usuario = $result_name['nombre_usuario'];
}

//Validar si nombre viene vacio
if(!$name_usuario){
    header('Location: login.php');
}

 
// Verificar la opción seleccionada
if ($opcion === 'leerEmpresas') {
    $nombreOperador = $_POST['nombreOperador'];
    // Realizar la consulta para obtener los datos de la tabla 'EMPRESAS'
    // Aquí debes incluir tu código para consultar la tabla 'EMPRESAS'
    // Utiliza la variable $conn_bd para realizar la conexión y ejecutar la consulta

    // Ejemplo de consulta utilizando PDO
    $query = "SELECT * FROM [dbo].[EMPRESAS] WHERE [Razon_Social] = '$nombreOperador'";
    $statement = $conn_bd->query($query);

    // Obtener los resultados de la consulta
    $empresas = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos como respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($empresas);

}else if($opcion === 'insertarOperacionFactura'){
    
   
        //Primer insert
        $referencia_nexen = $_POST['referencia_nexen'];
        $modal_pais_origen = $_POST['modal_pais_origen'];
        $nombreOperador = $_POST['nombreOperador'];
        $rfcOperador = $_POST['rfcOperador'];
        $domOperador = $_POST['domOperador'];
        $proveedorFact = $_POST['proveedorFact'];
        $taxId = $_POST['taxId'];
        $numFactura = $_POST['numFactura'];
        $fechaFactura = $_POST['fechaFactura'];
        $total = $_POST['total'];
        date_default_timezone_set('America/Mexico_City');
        $fechope = date('Y-m-d');
        $horaope = date('H:i:s');
        $usuario = $_SESSION['usuario_nexen'];
        
        $total_peso_bruto = $_POST['total_peso_bruto'];
        $total_peso_neto = $_POST['total_peso_neto'];

        //Primero se verifica si $numFactura ya existe
        $query = "SELECT COUNT(*) FROM [dbo].[Operacion_Facturas] WHERE [Numero_Factura] = :numFactura";
        $stmt = $conn_bd->prepare($query);
        $stmt->bindParam(':numFactura', $numFactura);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Se encontraron uno o más registros que cumplen con la condición
            $response = ['success' => false, 'message' => 'Ya existe el numero de factura: '.$numFactura.' intenta con otro.'];
        } else {
            $query1 = "INSERT INTO [Operacion_Facturas] ( [Referencia_Nexen], [Proveedor], [Tax_Id], [Numero_Factura], [Fecha_Factura], [Importador_Exportador], [RFC_Importador_Exportador], [Domicilio_Fiscal], [Total_General], [Fechope], [HoraoPe], [Usuario], [Estatus], [PAIS_ORIGEN], [PESO_BRUTO_TOTAL], [PESO_NETO_TOTAL]) 
            VALUES ( :referencia_nexen, :proveedorFact, :taxId, :numFactura, :fechaFactura, :nombreOperador, :rfcOperador, :domOperador, :total, :fechope, :horaope, :usuario, 'A', :modal_pais_origen, :total_peso_bruto, :total_peso_neto)";

            $statement1 = $conn_bd->prepare($query1);
            $statement1->bindParam(':referencia_nexen', $referencia_nexen);
            $statement1->bindParam(':modal_pais_origen', $modal_pais_origen);
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
            
     
            //$statement1->execute();


            if ($statement1->execute()) {
                    // Devolver una respuesta de éxito
                    $lastId = $conn_bd->lastInsertId();
                    $response = ['success' => true, 'message' => 'Primer insert fue correcto', 'lastId' => $lastId, 'referencia_nexen' => $referencia_nexen];
                    
                
            } else {
                    $response = ['success' => false, 'message' => 'Error al insertar en la tabla "Operacion_Facturas"'];
            }
        }
           
        
    
        // Devolver los datos como respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        
        
    
    

}else if ($opcion === 'insertarFacturaDetalle') {
    
    // Obtener los datos enviados por AJAX
    $lastId = $_POST['lastId'];
    $referencia_nexen = $_POST['referencia_nexen'];
    $numFactura = $_POST['numFactura'];
    $incoterms = $_POST['incoterms'];
    $partida = $_POST['partida'];
    $descripcion = $_POST['descripcion'];
    $descripcion_i = $_POST['descripcion_i'];
    $cantidad = $_POST['cantidad'];
    $medida = $_POST['medida'];
    $precioUnitario = $_POST['precioUnitario'];
    $moneda = $_POST['moneda'];
    $total_partida = $_POST['total_partida'];
    $fechope = date('Y-m-d');
    $horaope = date('H:i:s');
    //$usuario = $_SESSION['usuario_nexen'];

    $peso_bruto = filter_var($_POST['peso_bruto'], FILTER_VALIDATE_FLOAT);
    $peso_neto = filter_var($_POST['peso_neto'], FILTER_VALIDATE_FLOAT);

    $mark = $_POST['mark'];

    
   

    $query = "INSERT INTO [dbo].[Operacion_Facturas_Detalle] ( [Referencia_Nexen],[Id_Factura], [Numero_Factura], [Numero_Partida], [Descripcion_Cove], [Cantidad],[Unidad_Medida],[Moneda], [Precio_Unitario], [Total], [Estatus], [Fechope], [Horaope],[Usuario], [Peso_Bruto], [Peso_Neto], [Incoterms], [Descripcion_cove_I], [Mark] ) 
    VALUES (:referencia_nexen, :lastId, :numFactura, :partida, :descripcion, :cantidad,:medida,:moneda, :precioUnitario, :total,'A', :fechope, :horaope, :usuario, :peso_bruto, :peso_neto, :incoterms, :descripcion_i, :mark)";


    $statement = $conn_bd->prepare($query);
    $statement->bindParam(':referencia_nexen', $referencia_nexen);
    $statement->bindParam(':lastId', $lastId);
    $statement->bindParam(':numFactura', $numFactura);
    $statement->bindParam(':partida', $partida);
    $statement->bindParam(':descripcion', $descripcion);
    $statement->bindParam(':descripcion_i', $descripcion_i);
    $statement->bindParam(':cantidad', $cantidad);
    $statement->bindParam(':medida', $medida);
    $statement->bindParam(':precioUnitario', $precioUnitario);
    $statement->bindParam(':moneda', $moneda);
    $statement->bindParam(':total', $total_partida);
    $statement->bindParam(':fechope', $fechope);
    $statement->bindParam(':horaope', $horaope);
    $statement->bindParam(':usuario', $name_usuario);

    $statement->bindParam(':peso_bruto', $peso_bruto);
    
    $statement->bindParam(':peso_neto', $peso_neto);

    $statement->bindParam(':incoterms', $incoterms);

    $statement->bindParam(':mark', $mark);
  

    if ($statement->execute()) {
        // Devolver una respuesta de éxito
        $response = ['success' => true, 'message' => 'Factura y partidas guardadas correctamente'];
    } else {

        // Devolver una respuesta con el mensaje de error
        //$response = ['success' => false, 'message' => 'Error al insertar el detalle de la factura'];
        $errorInfo = $statement->errorInfo();

        // Devolver una respuesta con el mensaje de error de SQL Server
        $response = ['success' => false, 'message' => 'Error al insertar el detalle de la factura', 'error' => $errorInfo[2]];
    }

    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

}else if ($_POST['opcion'] === 'obtenerFacturas') {
    // Realiza la consulta para obtener los datos de las facturas
    // y almacena los resultados en un array $facturas}

    $referencia_nexen = $_POST['referencia_nexen'];
    $nombreOperador = $_POST['nombreOperador'];

    // Ejemplo de cómo podrías obtener los datos de la base de datos
    $stmt = $conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas] WHERE [Referencia_Nexen] = '$referencia_nexen' AND [Importador_Exportador] = '$nombreOperador'");

    

    $stmt->execute();
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
    // Retorna los datos en formato JSON
    echo json_encode(array('success'=> true,'data' => $facturas));

  }else if ($_POST['opcion'] === 'obtenerDetalleFacturas') {
    // Realiza la consulta para obtener los datos de las facturas
    // y almacena los resultados en un array $facturas}

    $Id_Factura = $_POST['idFactura'];
    

    // Ejemplo de cómo podrías obtener los datos de la base de datos
    $stmt = $conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Id_Factura] = '$Id_Factura'");

    

    $stmt->execute();
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
    // Retorna los datos en formato JSON
    echo json_encode(array('success'=> true,'data' => $facturas));

  }else if ($_POST['opcion'] === 'borrarPartidas') {


    $partida = $_POST['partida'];
    $factura = $_POST['factura'];

    $stmt = $conn_bd->prepare("DELETE FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Partida] = :partida AND [Numero_Factura] = :factura");
    $stmt->bindParam(':partida', $partida);
    $stmt->bindParam(':factura', $factura);

    if ($stmt->execute()) {
        echo json_encode(array('success' => true, 'message' => 'Registro eliminado correctamente'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error al eliminar el registro'));
    }

  }else if ($_POST['opcion'] === 'obtenerEditarFacturas') {


    $numero_factura = $_POST['numero_factura'];

    // Ejemplo de cómo podrías obtener los datos de la base de datos
    $stmt = $conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas] o
    INNER JOIN [dbo].[Operacion_Facturas_Detalle] F ON o.Referencia_Nexen = F.Referencia_Nexen
    INNER JOIN [dbo].[provedores] AS p ON o.Proveedor = p.Proveedor
    WHERE o.Numero_Factura = :numero_factura");


    $stmt->bindParam(':numero_factura', $numero_factura);
    $stmt->execute();
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Obtener partidas
    // Ejemplo de cómo podrías obtener los datos de la base de datos
    $stmt2 = $conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Factura] = :numero_factura");

    $stmt2->bindParam(':numero_factura', $numero_factura);
    $stmt2->execute();
    $partidas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Retorna los datos en formato JSON
    echo json_encode(array('success'=> true,'data' => $facturas, 'partidas' => $partidas));


  }else if ($_POST['opcion'] === 'UpdateOperacionFactura') {


    $proveedor_fact_edit = $_POST['proveedor_fact_edit'];
    $modal_pais_origen_edit = $_POST['modal_pais_origen_edit'];
    $modal_domicilio_proveedor_edit = $_POST['modal_domicilio_proveedor_edit'];
    $modal_rfc_operador_edit = $_POST['modal_rfc_operador_edit'];
    $modal_num_factura_edit = $_POST['modal_num_factura_edit'];
    $modal_fecha_factura_edit = $_POST['modal_fecha_factura_edit'];
    $tax_id_edit = $_POST['tax_id_edit'];
    $incoterms_edit = $_POST['incoterms_edit'];
    $modal_nombre_operador_edit = $_POST['modal_nombre_operador_edit'];
    $modal_domicilio_operador_edit = $_POST['modal_domicilio_operador_edit'];
    $total_edit = $_POST['precio_total'];
    $fechope = date('Y-m-d'); // Fecha actual en formato 'YYYY-MM-DD'
    $horaoPe = date('H:i:s'); // Hora actual en formato 'HH:MM:SS'
    

    // Preparar la consulta de actualización
    $stmt = $conn_bd->prepare("UPDATE [dbo].[Operacion_Facturas] 
    SET [Proveedor] = :proveedor,
    [Tax_Id] = :tax_id,
    [Fecha_Factura] = :fecha_factura,
    [Importador_Exportador] = :importador_exportador,
    [RFC_Importador_Exportador] = :rfc_importador_exportador,
    [Domicilio_Fiscal] = :domicilio_fiscal,
    [Total_General] = :total_general,
    [Fechope] = :fechope,
    [HoraoPe] = :horaoPe,
    [PAIS_ORIGEN] = :pais_origen

    WHERE [Numero_Factura] = :numero_factura");
   
    // Asignar los valores de los parámetros
    $stmt->bindParam(':numero_factura', $modal_num_factura_edit);
    $stmt->bindParam(':proveedor', $proveedor_fact_edit);
    $stmt->bindParam(':tax_id', $tax_id_edit);
    $stmt->bindParam(':fecha_factura', $modal_fecha_factura_edit);
    $stmt->bindParam(':importador_exportador', $modal_nombre_operador_edit);
    $stmt->bindParam(':rfc_importador_exportador', $modal_rfc_operador_edit);
    $stmt->bindParam(':domicilio_fiscal', $modal_domicilio_operador_edit);
    $stmt->bindParam(':total_general', $total_edit);
    $stmt->bindParam(':fechope', $fechope);
    $stmt->bindParam(':horaoPe', $horaoPe);
    $stmt->bindParam(':pais_origen', $modal_pais_origen_edit);

   
    
    // Ejecutar la consulta
    if($stmt->execute()){

        //Para obtener id_factura
        // Obtener el Id_Factura después de la actualización
            $stmtSelect = $conn_bd->prepare("SELECT Id_Factura, Referencia_Nexen FROM [dbo].[Operacion_Facturas] WHERE [Numero_Factura] = :numero_factura");
            $stmtSelect->bindParam(':numero_factura', $modal_num_factura_edit);
            $stmtSelect->execute();

            // Verificar si se obtuvo el Id_Factura correctamente
            $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            $idFactura = $row['Id_Factura'];
            $Referencia_Nexen = $row['Referencia_Nexen'];

        //Se borran todas los detalles de factura para volverse a insertar
        // Preparar la sentencia SQL
        $sql = "DELETE FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Factura] = :numero_factura";

        // Preparar y ejecutar la consulta
        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':numero_factura', $modal_num_factura_edit);
        $stmt->execute();

        // Verificar si se eliminó correctamente
        if ($stmt->rowCount() > 0) {
            // Ejecutar la consulta de actualización

            // Retorna los datos en formato JSON
            echo json_encode(array('success'=> true,'message' => 'La actualizacion de la factura fue correcta', 'messageDeletePartidas' => 'Partidas borradas correctamente, procediendo a insertar...', 'idFactura' => $idFactura, 'Referencia_Nexen' => $Referencia_Nexen));
        } else {
            // Retorna los datos en formato JSON
            echo json_encode(array('success'=> true,'message' => 'La actualizacion de la factura fue correcta', 'messageDeletePartidas' => 'Falló al borrar partidas', 'idFactura' => $idFactura, 'Referencia_Nexen' => $Referencia_Nexen));
        }

          
        
        
    }else{
        echo json_encode(array('success'=> true,'message' => 'La actualizacion de la factura fue incorrecta'));
    }
    
  }else if ($_POST['opcion'] === 'borrarFacturayDetalles') {


    $id_factura = $_POST['id_factura'];
    $referencia_nexen = $_POST['referencia_nexen'];
    $numero_factura = $_POST['numero_factura'];
    $fecha_factura = $_POST['fecha_factura'];
    $tax_id = $_POST['tax_id'];
    $fechope = date('Y-m-d');
    $horaope = date('h:i:s');

    // Eliminar registros de la tabla Operacion_Facturas_Detalle
    $stmtDetalle = $conn_bd->prepare("DELETE FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Factura] = :numero_factura");
    $stmtDetalle->bindParam(':numero_factura', $numero_factura);

    // Eliminar registros de la tabla Operacion_Facturas
    $stmtFactura = $conn_bd->prepare("DELETE FROM [dbo].[Operacion_Facturas] WHERE [Id_Factura] = :id_factura");
    $stmtFactura->bindParam(':id_factura', $id_factura);

    //insert a log borrado facturas
    $stmtLogBorrado = $conn_bd->prepare("INSERT INTO [dbo].[Log_Borrado_Factura] (Id_Factura, Referencia_Nexen, Tax_Id, Numero_Factura, Fecha_Factura, Fechope, Horaope, Usuario) 
                                        VALUES (:id_factura, :referencia_nexen, :tax_id, :numero_factura, :fecha_factura, :fechope, :horaope, :usuario)");
    $stmtLogBorrado->bindParam(':id_factura', $id_factura);
    $stmtLogBorrado->bindParam(':referencia_nexen', $referencia_nexen);
    $stmtLogBorrado->bindParam(':tax_id', $tax_id);
    $stmtLogBorrado->bindParam(':numero_factura', $numero_factura);
    $stmtLogBorrado->bindParam(':fecha_factura', $id_factura);
    $stmtLogBorrado->bindParam(':fechope', $fechope);
    $stmtLogBorrado->bindParam(':horaope', $horaope);
    $stmtLogBorrado->bindParam(':usuario', $usuario);

    // Iniciar una transacción
    $conn_bd->beginTransaction();

    try {
        // Ejecutar la eliminación en la tabla Operacion_Facturas_Detalle
        $stmtDetalle->execute();

        // Ejecutar la eliminación en la tabla Operacion_Facturas
        $stmtFactura->execute();

        //ejecutar query de insert a log borrado
        $stmtLogBorrado->execute();

        // Confirmar la transacción
        $conn_bd->commit();

        echo json_encode(array('success' => true, 'message' => 'Registros eliminados correctamente'));
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn_bd->rollBack();

        echo json_encode(array('success' => false, 'message' => 'Error al eliminar los registros '. $e->getMessage()));
    }


  }else if ($opcion === 'leerProveedor') {
    $taxID = $_POST['taxID']; // Corregir el nombre del parámetro a taxID

    // Realizar la consulta para obtener los datos del proveedor
    // Aquí debes incluir tu código para consultar los datos del proveedor
    // Utiliza la variable $conn_bd para realizar la conexión y ejecutar la consulta
    // ...

    // Ejemplo de consulta utilizando PDO
    $query = "SELECT * FROM [dbo].[provedores] WHERE [codigo] = :taxID";
    $statement = $conn_bd->prepare($query);
    $statement->bindParam(':taxID', $taxID);



    // Devolver los datos como respuesta JSON
    header('Content-Type: application/json');
    if($statement->execute()){
        $proveedor = $statement->fetch(PDO::FETCH_ASSOC);
        echo json_encode(array('success' => true, 'proveedor' => $proveedor));
    }else{
        echo json_encode(array('success' => false, 'message' => 'Error en la consulta de proveedores'));
    }

}else if ($opcion === 'updateProveedor') {

    $editar_tax_id = $_POST['editar_tax_id'];
    $new_tax_id = $_POST['new_tax_id'];
    $editar_proveedor = $_POST['editar_proveedor'];
    $editar_domicilio = $_POST['editar_domicilio'];
    $editar_email = $_POST['editar_email'];
    $editar_whatsapp = $_POST['editar_whatsapp'];

    // Realizar el update del proveedor
    $query = "UPDATE [dbo].[provedores] SET codigo = :newTaxID, [Proveedor] = :proveedor, [domicilio] = :domicilio, [correo] = :email, [whatsapp] = :whatsapp WHERE [codigo] = :taxID";
    $statement = $conn_bd->prepare($query);
    $statement->bindParam(':proveedor', $editar_proveedor);
    $statement->bindParam(':domicilio', $editar_domicilio);
    $statement->bindParam(':email', $editar_email);
    $statement->bindParam(':whatsapp', $editar_whatsapp);
    $statement->bindParam(':taxID', $editar_tax_id);
    $statement->bindParam(':newTaxID', $new_tax_id);

    // Ejecutar el update
    if ($statement->execute()) {
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'message' => 'Proveedor actualizado correctamente'));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Error al actualizar el proveedor'));
    }

}else if ($opcion === 'deleteProveedor') {

    $editar_tax_id = $_POST['editar_tax_id'];


    // Realizar el update del proveedor
    $query = "DELETE FROM [dbo].[provedores] WHERE [codigo] = :taxID";
    $statement = $conn_bd->prepare($query);
    $statement->bindParam(':taxID', $editar_tax_id);

    // Ejecutar el update
    if ($statement->execute()) {
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'message' => 'Proveedor borrado correctamente'));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Error al borrar el proveedor'));
    }

}else if ($opcion === 'leerCliente') {
    $nombre_cliente = trim($_POST['nombre_cliente']);


    // Ejemplo de consulta utilizando PDO
    $query = "SELECT * FROM [dbo].[Clientes] WHERE [RAZON SOCIAL ] = :nombre_cliente";
    $statement = $conn_bd->prepare($query);
    $statement->bindParam(':nombre_cliente', $nombre_cliente);


    // Realizar la consulta para rellenar la DataTable
    $queryDataTable = "SELECT id, [RAZON SOCIAL ] AS RazonSocial FROM [dbo].[Clientes]";
    $statementDataTable = $conn_bd->prepare($queryDataTable);
    $statementDataTable->execute();
    $data = $statementDataTable->fetchAll(PDO::FETCH_ASSOC);


    // Devolver los datos como respuesta JSON
    header('Content-Type: application/json');
    if($statement->execute()){
        $cliente = $statement->fetch(PDO::FETCH_ASSOC);
        echo json_encode(array('success' => true, 'cliente' => $cliente, 'data' => $data));
    }else{
        echo json_encode(array('success' => false, 'message' => 'Error en la consulta de cliente'));
    }

}else if ($opcion === 'updateCliente') {

    $razon_social_cliente_edit = $_POST['razon_social_cliente_edit'];
    $rfc_cliente_edit = $_POST['rfc_cliente_edit'];
    $telefono_cliente_edit = $_POST['telefono_cliente_edit'];
    $movil_cliente_edit = $_POST['movil_cliente_edit'];
    $nombre_contacto_edit = $_POST['nombre_contacto_edit'];
    $email_cliente_1_edit = $_POST['email_cliente_1_edit'];
    $email_cliente_2_edit = $_POST['email_cliente_2_edit'];
    $dom_cliente_edit = $_POST['dom_cliente_edit'];

    // Realizar el update del cliente
    $query = "UPDATE [Nexen].[dbo].[Clientes] SET [RFC ] = :rfc, [TELEFONO] = :telefono, [MOVIL ] = :movil, [CONTACTO] = :contacto, [EMAIL 1] = :email1, [EMAIL 2] = :email2, [Domilio_Fisico] = :domicilio WHERE [RAZON SOCIAL ] = :razon_social";
    $statement = $conn_bd->prepare($query);
    $statement->bindParam(':rfc', $rfc_cliente_edit);
    $statement->bindParam(':telefono', $telefono_cliente_edit);
    $statement->bindParam(':movil', $movil_cliente_edit);
    $statement->bindParam(':contacto', $nombre_contacto_edit);
    $statement->bindParam(':email1', $email_cliente_1_edit);
    $statement->bindParam(':email2', $email_cliente_2_edit);
    $statement->bindParam(':domicilio', $dom_cliente_edit);
    $statement->bindParam(':razon_social', $razon_social_cliente_edit);



    // Ejecutar el update
    if ($statement->execute()) {
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'message' => 'Cliente actualizado correctamente'));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Error al actualizar el cliente'));
    }

}else if ($opcion === 'deleteOperacion') {

    $password_sup = $_POST['password_sup'];
    $referencia_nexen = $_POST['referencia_nexen'];

    try {

        // Iniciar una transacción
        $conn_bd->beginTransaction();

        // Realizar los deletes
        $tabla1 = 'Operacion_nexen';
        $tabla2 = '[dbo].[Operacion_Facturas]';
        $tabla3 = '[dbo].[Operacion_Facturas_Detalle]';
        $tabla4 = '[dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION]';
        $tabla5 = '[dbo].[Catalogo_Check_list_Detalle]';


        $delete1 = "DELETE FROM $tabla1 WHERE [Referencia_Nexen] = :referencia";
        $delete2 = "DELETE FROM $tabla2 WHERE [Referencia_Nexen] = :referencia";
        $delete3 = "DELETE FROM $tabla3 WHERE [Referencia_Nexen] = :referencia";
        $delete4 = "DELETE FROM $tabla4 WHERE [Referencia_Nexen] = :referencia";
        $delete5 = "DELETE FROM $tabla5 WHERE [Referencia_Nexen] = :referencia";


        $stmt1 = $conn_bd->prepare($delete1);
        $stmt1->bindValue(':referencia', $referencia_nexen);
        $stmt1->execute();

        $stmt2 = $conn_bd->prepare($delete2);
        $stmt2->bindValue(':referencia', $referencia_nexen);
        $stmt2->execute();

        $stmt3 = $conn_bd->prepare($delete3);
        $stmt3->bindValue(':referencia', $referencia_nexen);
        $stmt3->execute();

        $stmt4 = $conn_bd->prepare($delete4);
        $stmt4->bindValue(':referencia', $referencia_nexen);
        $stmt4->execute();

        $stmt5 = $conn_bd->prepare($delete5);
        $stmt5->bindValue(':referencia', $referencia_nexen);
        $stmt5->execute();


        // Confirmar la transacción
        $conn_bd->commit();

        // Consultar el usuario de la tabla Contraseña_Sup
        $selectUsuario = "SELECT [Usuario] FROM [dbo].[Contraseña_Sup] WHERE [Contraseña] = :password";
        $stmtUsuario = $conn_bd->prepare($selectUsuario);
        $stmtUsuario->execute(array(':password' => $password_sup));
        $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC)['Usuario'];

        // Obtener la fecha y hora actual
        $fechaActual = date('Y-m-d');
        $horaActual = date('H:i:s');

        // Insertar un nuevo registro en la tabla Log_Borrado
        $insertLog = "INSERT INTO [dbo].[Log_Borrado] ([Usuario], [Contraseña], [Fechope], [Horaope], [Referencia_Nexen])
              VALUES (:usuario, :password, :fecha, :hora, :Referencia_Nexen)";
        $stmtLog = $conn_bd->prepare($insertLog);
        $stmtLog->execute(array(
            ':usuario' => $usuario,
            ':password' => $password_sup,
            ':fecha' => $fechaActual,
            ':hora' => $horaActual,
            ':Referencia_Nexen' => $referencia_nexen
        ));

        //Insertar en Fk_Log_Detalle_Ope_Nexen el borrado de operacion
        $insertLogOperacion = "INSERT INTO [dbo].[Fk_Log_Detalle_Ope_Nexen] ([Usuario],[HORA_OPE],[FECHOPE],[REFERENCIA_NEXEN],[Tipo_OPE])
                                                    VALUES(:usuario, :hora , :fecha ,:Referencia_Nexen,'DELETE')";
        
        $stmtLogOpe = $conn_bd->prepare($insertLogOperacion);
        $stmtLogOpe->execute(array(
            ':usuario' => $name_usuario,
            ':fecha' => $fechaActual,
            ':hora' => $horaActual,
            ':Referencia_Nexen' => $referencia_nexen
        ));


        // Mostrar mensaje de éxito
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'message' => 'Operación borrada correctamente'));
    } catch (PDOException $e) {
        // Mostrar mensaje de error
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Error al ejecutar el borrado de operaciones: ' . $e->getMessage()));

        // Revertir la transacción
        $conn_bd->rollBack();
    }

}else if ($opcion === 'VerificarPasswordSupervisor') {

    if (isset($_POST['password'])) {
        $user_sup = $_POST['user_sup'];
        $password = $_POST['password'];

        $consulta = $conn_bd->prepare("SELECT * FROM Contraseña_Sup WHERE Usuario = :user_sup AND Contraseña = :password");
        $consulta->bindParam(':user_sup', $user_sup);
        $consulta->bindParam(':password', $password);

        try {
            $consulta->execute();
            $fila = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                // La contraseña existe
                header('Content-Type: application/json');
                echo json_encode(array('success' => true));
            } else {
                // La contraseña no existe
                header('Content-Type: application/json');
                echo json_encode(array('success' => false));
            }
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'message' => 'Error al ejecutar el borrado de operaciones: ' . $e->getMessage()));
        }
    }
}else if ($opcion === 'GuardarDetalleRetenidos') {
    // Obtener los datos enviados por AJAX
    $Referencia_Nexen = $_POST['Referencia_Nexen'];
    $Fecha_Retenido = $_POST['Fecha_Retenido'];
    $Fecha_Liberacion = $_POST['Fecha_Liberacion'];
    $MSA = $_POST['MSA'];
    $Incidencia = $_POST['Incidencia'];
    $Observacion = $_POST['Observacion'];
    $Estatus = $_POST['Estatus'];
    $fechope = date('Y-m-d');
    $hora = date('H:i:s');


    //PRIMERO SE COMPRUEBA SI EXISTE YA REGISTRO, SE HACE UN UPDATE, SI NO EXISTE SE HACE UN INSERT.
    $select_query = "SELECT * FROM [dbo].[OPERACIONES_RETENIDAS] WHERE [Referencia_Nexen] = '$Referencia_Nexen'";
    $buscar_query = $conn_bd->prepare($select_query);
    $buscar_query -> execute();
    $result_query = $buscar_query -> fetchAll(PDO::FETCH_ASSOC);

    //Si ya existe se hace update
    if(count($result_query) > 0){
        $query = "UPDATE [dbo].[OPERACIONES_RETENIDAS] SET
            [Fecha_Retenido] = :Fecha_Retenido,
            [Fecha_Liberacion] = :Fecha_Liberacion,
            [MSA] = :MSA,
            [INCIDENCIA] = :Incidencia,
            [OBSERVACION] = :Observacion,
            [FECHOPE] = :fechope,
            [HORA] = :hora,
            [ESTATUS] = :Estatus
          WHERE [Referencia_Nexen] = :referencia_nexen";

        $statement = $conn_bd->prepare($query);
        $statement->bindParam(':referencia_nexen', $Referencia_Nexen);
        $statement->bindParam(':Fecha_Retenido', $Fecha_Retenido);
        $statement->bindParam(':Fecha_Liberacion', $Fecha_Liberacion);
        $statement->bindParam(':MSA', $MSA);
        $statement->bindParam(':Incidencia', $Incidencia);
        $statement->bindParam(':Observacion', $Observacion);
        $statement->bindParam(':fechope', $fechope);
        $statement->bindParam(':hora', $hora);
        $statement->bindParam(':Estatus', $Estatus);

        if ($statement->execute()) {
            // Devolver una respuesta de éxito
            $response = ['success' => true, 'message' => 'Operación retenida actualizada con exito'];
        } else {
            // Devolver una respuesta con el mensaje de error
            //$response = ['success' => false, 'message' => 'Error al insertar el detalle de la factura'];
            $errorInfo = $statement->errorInfo();

            // Devolver una respuesta con el mensaje de error de SQL Server
            $response = ['success' => false, 'message' => 'Error en actualizar de operación retenida', 'error' => $errorInfo[2]];
            $response = ['success' => false, 'message' => 'Error en actualizar de operación retenida', 'error' => $errorInfo[2]];
        }
    }else{ //No existe, se hace insert
        $query = "INSERT INTO [dbo].[OPERACIONES_RETENIDAS] ( [Referencia_Nexen],[Fecha_Retenido], [Fecha_Liberacion], [MSA], [INCIDENCIA], [OBSERVACION],[FECHOPE],[HORA], [ESTATUS]) 
        VALUES (:referencia_nexen, :Fecha_Retenido, :Fecha_Liberacion, :MSA, :Incidencia , :Observacion, :fechope, :hora, :Estatus)";


    $statement = $conn_bd->prepare($query);
    $statement->bindParam(':referencia_nexen', $Referencia_Nexen);
    $statement->bindParam(':Fecha_Retenido', $Fecha_Retenido);
    $statement->bindParam(':Fecha_Liberacion', $Fecha_Liberacion);
    $statement->bindParam(':MSA', $MSA);
    $statement->bindParam(':Incidencia', $Incidencia);
    $statement->bindParam(':Observacion', $Observacion);
    $statement->bindParam(':fechope', $fechope);
    $statement->bindParam(':hora', $hora);
    $statement->bindParam(':Estatus', $Estatus);

    if ($statement->execute()) {
        // Devolver una respuesta de éxito
        $response = ['success' => true, 'message' => 'Operación retenida guardada con exito'];
    } else {

        // Devolver una respuesta con el mensaje de error
        //$response = ['success' => false, 'message' => 'Error al insertar el detalle de la factura'];
        $errorInfo = $statement->errorInfo();

            // Devolver una respuesta con el mensaje de error de SQL Server
            $response = ['success' => false, 'message' => 'Error en el guardado de operación retenida', 'error' => $errorInfo[2]];
        }
    }

    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

}else if ($opcion === 'updateOperador') {

    $nombre_operador = $_POST['nombre_operador'];
    $referencia_nexen = $_POST['referencia_nexen'];
    $rfc_empresa_input = $_POST['rfc_empresa_input'];
    $dir_empresa_input = $_POST['dir_empresa_input'];
    
    

    // Obtener la fecha y hora actual
    $fechope = date('Y-m-d');
    $horaope = date('H:i:s');

    try {
        // Iniciar una transacción
        $conn_bd->beginTransaction();
    
        // Eliminar la tabla temporal si existe
        $dropTempTable = "IF OBJECT_ID('tempdb..##DatosTemporales') IS NOT NULL DROP TABLE #DatosTemporales";
        $stmtDropTempTable = $conn_bd->prepare($dropTempTable);
        $stmtDropTempTable->execute();

        // Crear la tabla temporal
        $createTempTable = "CREATE TABLE ##DatosTemporales (
            Usuario varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Referencia_Cliente varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Cliente varchar(400) COLLATE Modern_Spanish_CI_AS NULL,
            BL varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Contenedor_1 varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Pto_LLegada varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Fecha_Arribo varchar(10) COLLATE Modern_Spanish_CI_AS NULL,
            Fecha_Notificación varchar(10) COLLATE Modern_Spanish_CI_AS NULL,
            Fecha_Pago_Anticipo varchar(10) COLLATE Modern_Spanish_CI_AS NULL,
            Fecha_Modulación varchar(10) COLLATE Modern_Spanish_CI_AS NULL,
            No_Pedimento varchar(25) COLLATE Modern_Spanish_CI_AS NULL,
            Importador_Exportador varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Clave_Pedimento varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            No_Factura varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Valor_Factura varchar(25) COLLATE Modern_Spanish_CI_AS NULL,
            Descripcion_Cove varchar(500) COLLATE Modern_Spanish_CI_AS NULL,
            Factura_Anexo24 varchar(50) COLLATE Modern_Spanish_CI_AS NULL,
            tipo_cambio varchar(25) COLLATE Modern_Spanish_CI_AS NULL,
            Fecha_Factura24 varchar(10) COLLATE Modern_Spanish_CI_AS NULL,
            WMS varchar(25) COLLATE Modern_Spanish_CI_AS NULL,
            Estatus varchar(25) COLLATE Modern_Spanish_CI_AS NULL,
            HORA_OPE varchar(10) COLLATE Modern_Spanish_CI_AS NULL,
            FECHOPE date NULL,
            Patente varchar(4) COLLATE Modern_Spanish_CI_AS NULL,
            Moneda varchar(25) COLLATE Modern_Spanish_CI_AS NULL,
            DENOMINACION_ADUANA varchar(400) COLLATE Modern_Spanish_CI_AS NULL,
            Guia_House varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Tipo_Operacion varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            proveedor varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            BULTOS varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            peso_bruto varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            tipo_trafico varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            GUIA_HOUSE1 varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            GUIA_HOUSE2 varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            GUIA_HOUSE3 varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            valida_fecha varchar(35) COLLATE Modern_Spanish_CI_AS NULL,
            fecha_factura varchar(10) COLLATE Modern_Spanish_CI_AS NULL,
            FACTURA_SALIDA_ANEXO24 varchar(50) COLLATE Modern_Spanish_CI_AS NULL,
            NUM_SALIDA_WMS varchar(50) COLLATE Modern_Spanish_CI_AS NULL,
            NUM_RECTIFICACION varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            FECHA_LIBERACION date NULL,
            OBSERVACIONES varchar(200) COLLATE Modern_Spanish_CI_AS NULL,
            ID_CLIENTE int NULL,
            NUMERO_ECONOMICO varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            REFERENCIA_NEXEN varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            Contenedor_2 varchar(250) COLLATE Modern_Spanish_CI_AS NULL,
            DETALLE_MERCANCIA varchar(MAX) COLLATE Modern_Spanish_CI_AS NULL
        )";
        $stmtCreateTempTable = $conn_bd->prepare($createTempTable);
        $stmtCreateTempTable->execute();

    
        // Realizar el SELECT desde Operacion_nexen y la inserción en la tabla temporal
        $selectOperacionNexen = "SELECT Usuario, Referencia_Cliente, Cliente, BL, Contenedor_1, Pto_LLegada, Fecha_Arribo, Fecha_Notificación, Fecha_Pago_Anticipo, Fecha_Modulación, No_Pedimento, Importador_Exportador, Clave_Pedimento, No_Factura, Valor_Factura, Descripcion_Cove, Factura_Anexo24, tipo_cambio, Fecha_Factura24, WMS, Estatus, HORA_OPE, FECHOPE, Patente, Moneda, DENOMINACION_ADUANA, Guia_House, Tipo_Operacion, proveedor, BULTOS, peso_bruto, tipo_trafico, GUIA_HOUSE1, GUIA_HOUSE2, GUIA_HOUSE3, valida_fecha, fecha_factura, FACTURA_SALIDA_ANEXO24, NUM_SALIDA_WMS, NUM_RECTIFICACION, FECHA_LIBERACION, OBSERVACIONES, ID_CLIENTE, NUMERO_ECONOMICO, REFERENCIA_NEXEN, Contenedor_2, DETALLE_MERCANCIA
        FROM Operacion_nexen
        WHERE REFERENCIA_NEXEN = :referencia_nexen";
        $stmtSelectOperacionNexen = $conn_bd->prepare($selectOperacionNexen);
        $stmtSelectOperacionNexen->bindValue(':referencia_nexen', $referencia_nexen);
        $stmtSelectOperacionNexen->execute();

    
        $insertTempTable = "INSERT INTO ##DatosTemporales (Usuario, Referencia_Cliente, Cliente, BL, Contenedor_1, Pto_LLegada, Fecha_Arribo, Fecha_Notificación, Fecha_Pago_Anticipo, Fecha_Modulación, No_Pedimento, Importador_Exportador, Clave_Pedimento, No_Factura, Valor_Factura, Descripcion_Cove, Factura_Anexo24, tipo_cambio, Fecha_Factura24, WMS, Estatus, HORA_OPE, FECHOPE, Patente, Moneda, DENOMINACION_ADUANA, Guia_House, Tipo_Operacion, proveedor, BULTOS, peso_bruto, tipo_trafico, GUIA_HOUSE1, GUIA_HOUSE2, GUIA_HOUSE3, valida_fecha, fecha_factura, FACTURA_SALIDA_ANEXO24, NUM_SALIDA_WMS, NUM_RECTIFICACION, FECHA_LIBERACION, OBSERVACIONES, ID_CLIENTE, NUMERO_ECONOMICO, REFERENCIA_NEXEN, Contenedor_2, DETALLE_MERCANCIA)
        VALUES (:usuario, :referencia_cliente, :cliente, :bl, :contenedor_1, :pto_llegada, :fecha_arribo, :fecha_notificacion, :fecha_pago_anticipo, :fecha_modulacion, :no_pedimento, :importador_exportador, :clave_pedimento, :no_factura, :valor_factura, :descripcion_cove, :factura_anexo24, :tipo_cambio, :fecha_factura24, :wms, :estatus, :hora_ope, :fechope, :patente, :moneda, :denominacion_aduana, :guia_house, :tipo_operacion, :proveedor, :bultos, :peso_bruto, :tipo_trafico, :guia_house1, :guia_house2, :guia_house3, :valida_fecha, :fecha_factura, :factura_salida_anexo24, :num_salida_wms, :num_rectificacion, :fecha_liberacion, :observaciones, :id_cliente, :numero_economico, :referencia_nexen, :contenedor_2, :detalle_mercancia)";
        $stmtInsertTempTable = $conn_bd->prepare($insertTempTable);
        while ($row = $stmtSelectOperacionNexen->fetch(PDO::FETCH_ASSOC)) {
            $stmtInsertTempTable->bindValue(':usuario', $row['Usuario']);
            $stmtInsertTempTable->bindValue(':referencia_cliente', $row['Referencia_Cliente']);
            $stmtInsertTempTable->bindValue(':cliente', $row['Cliente']);
            $stmtInsertTempTable->bindValue(':bl', $row['BL']);
            $stmtInsertTempTable->bindValue(':contenedor_1', $row['Contenedor_1']);
            $stmtInsertTempTable->bindValue(':pto_llegada', $row['Pto_LLegada']);
            $stmtInsertTempTable->bindValue(':fecha_arribo', $row['Fecha_Arribo']);
            $stmtInsertTempTable->bindValue(':fecha_notificacion', $row['Fecha_Notificación']);
            $stmtInsertTempTable->bindValue(':fecha_pago_anticipo', $row['Fecha_Pago_Anticipo']);
            $stmtInsertTempTable->bindValue(':fecha_modulacion', $row['Fecha_Modulación']);
            $stmtInsertTempTable->bindValue(':no_pedimento', $row['No_Pedimento']);
            $stmtInsertTempTable->bindValue(':importador_exportador', $row['Importador_Exportador']);
            $stmtInsertTempTable->bindValue(':clave_pedimento', $row['Clave_Pedimento']);
            $stmtInsertTempTable->bindValue(':no_factura', $row['No_Factura']);
            $stmtInsertTempTable->bindValue(':valor_factura', $row['Valor_Factura']);
            $stmtInsertTempTable->bindValue(':descripcion_cove', $row['Descripcion_Cove']);
            $stmtInsertTempTable->bindValue(':factura_anexo24', $row['Factura_Anexo24']);
            $stmtInsertTempTable->bindValue(':tipo_cambio', $row['tipo_cambio']);
            $stmtInsertTempTable->bindValue(':fecha_factura24', $row['Fecha_Factura24']);
            $stmtInsertTempTable->bindValue(':wms', $row['WMS']);
            $stmtInsertTempTable->bindValue(':estatus', $row['Estatus']);
            $stmtInsertTempTable->bindValue(':hora_ope', $row['HORA_OPE']);
            $stmtInsertTempTable->bindValue(':fechope', $row['FECHOPE']);
            $stmtInsertTempTable->bindValue(':patente', $row['Patente']);
            $stmtInsertTempTable->bindValue(':moneda', $row['Moneda']);
            $stmtInsertTempTable->bindValue(':denominacion_aduana', $row['DENOMINACION_ADUANA']);
            $stmtInsertTempTable->bindValue(':guia_house', $row['Guia_House']);
            $stmtInsertTempTable->bindValue(':tipo_operacion', $row['Tipo_Operacion']);
            $stmtInsertTempTable->bindValue(':proveedor', $row['proveedor']);
            $stmtInsertTempTable->bindValue(':bultos', $row['BULTOS']);
            $stmtInsertTempTable->bindValue(':peso_bruto', $row['peso_bruto']);
            $stmtInsertTempTable->bindValue(':tipo_trafico', $row['tipo_trafico']);
            $stmtInsertTempTable->bindValue(':guia_house1', $row['GUIA_HOUSE1']);
            $stmtInsertTempTable->bindValue(':guia_house2', $row['GUIA_HOUSE2']);
            $stmtInsertTempTable->bindValue(':guia_house3', $row['GUIA_HOUSE3']);
            $stmtInsertTempTable->bindValue(':valida_fecha', $row['valida_fecha']);
            $stmtInsertTempTable->bindValue(':fecha_factura', $row['fecha_factura']);
            $stmtInsertTempTable->bindValue(':factura_salida_anexo24', $row['FACTURA_SALIDA_ANEXO24']);
            $stmtInsertTempTable->bindValue(':num_salida_wms', $row['NUM_SALIDA_WMS']);
            $stmtInsertTempTable->bindValue(':num_rectificacion', $row['NUM_RECTIFICACION']);
            $stmtInsertTempTable->bindValue(':fecha_liberacion', $row['FECHA_LIBERACION']);
            $stmtInsertTempTable->bindValue(':observaciones', $row['OBSERVACIONES']);
            $stmtInsertTempTable->bindValue(':id_cliente', $row['ID_CLIENTE']);
            $stmtInsertTempTable->bindValue(':numero_economico', $row['NUMERO_ECONOMICO']);
            $stmtInsertTempTable->bindValue(':referencia_nexen', $row['REFERENCIA_NEXEN']);
            $stmtInsertTempTable->bindValue(':contenedor_2', $row['Contenedor_2']);
            $stmtInsertTempTable->bindValue(':detalle_mercancia', $row['DETALLE_MERCANCIA']);
            $stmtInsertTempTable->execute();
        }


        // Realizar el INSERT en la tabla Fk_Log_Detalle_Ope_Nexen usando los datos temporales
        $insertLogTable = "INSERT INTO Fk_Log_Detalle_Ope_Nexen (Usuario, Referencia_Cliente, Cliente, BL, Contenedor_1, Pto_LLegada, Fecha_Arribo, Fecha_Notificacion, Fecha_Pago_Anticipo, Fecha_Modulacion, No_Pedimento, Importador_Exportador, Clave_Pedimento, No_Factura, Valor_Factura, Descripcion_Cove, Factura_Anexo24, tipo_cambio, Fecha_Factura24, WMS, Estatus, HORA_OPE, FECHOPE, Patente, Moneda, DENOMINACION_ADUANA, Guia_House, Tipo_Operacion, proveedor, BULTOS, peso_bruto, tipo_trafico, GUIA_HOUSE1, GUIA_HOUSE2, GUIA_HOUSE3, valida_fecha, fecha_factura, FACTURA_SALIDA_ANEXO24, NUM_SALIDA_WMS, NUM_RECTIFICACION, FECHA_LIBERACION, OBSERVACIONES, ID_CLIENTE, NUMERO_ECONOMICO, REFERENCIA_NEXEN, Contenedor_2, Tipo_OPE, DETALLE_MERCANCIA)
        SELECT Usuario, Referencia_Cliente, Cliente, BL, Contenedor_1, Pto_LLegada, Fecha_Arribo, Fecha_Notificación, Fecha_Pago_Anticipo, Fecha_Modulación, No_Pedimento, Importador_Exportador, Clave_Pedimento, No_Factura, Valor_Factura, Descripcion_Cove, Factura_Anexo24, tipo_cambio, Fecha_Factura24, WMS, Estatus, HORA_OPE, FECHOPE, Patente, Moneda, DENOMINACION_ADUANA, Guia_House, Tipo_Operacion, proveedor, BULTOS, peso_bruto, tipo_trafico, GUIA_HOUSE1, GUIA_HOUSE2, GUIA_HOUSE3, valida_fecha, fecha_factura, FACTURA_SALIDA_ANEXO24, NUM_SALIDA_WMS, NUM_RECTIFICACION, FECHA_LIBERACION, OBSERVACIONES, ID_CLIENTE, NUMERO_ECONOMICO, REFERENCIA_NEXEN, Contenedor_2, 'UPDATE', DETALLE_MERCANCIA
        FROM ##DatosTemporales";
        $stmtInsertLogTable = $conn_bd->prepare($insertLogTable);
        $stmtInsertLogTable->execute();

        
        // Confirmar la transacción
        $conn_bd->commit();
    } catch (PDOException $e) {
        // Si hay un error, hacer un rollback de la transacción
        $conn_bd->rollBack();
        echo "Error: " . $e->getMessage();
    }
    
    try {

        // Iniciar una transacción
        $conn_bd->beginTransaction();

        // Realizar los deletes
        $tabla1 = '[dbo].[Operacion_nexen]';
        $tabla2 = '[dbo].[Operacion_Facturas]';
        $tabla3 = '[dbo].[FK_Solicitud_Pago]';
        $tabla4 = '[dbo].[Log_Facturas]';
        $tabla5 = '[dbo].[Log_Solicitud_Pagos]';


        $update1 = "UPDATE [dbo].[Operacion_nexen] SET Importador_Exportador = :nombre_operador WHERE REFERENCIA_NEXEN = :referencia_nexen";
        $update2 = "UPDATE [dbo].[Operacion_Facturas] SET Importador_Exportador = :nombre_operador, RFC_Importador_Exportador = :rfc_empresa_input, Domicilio_fiscal = :dir_empresa_input WHERE Referencia_Nexen = :referencia_nexen";
        $update3 = "UPDATE [dbo].[FK_Solicitud_Pago] SET Operador = :nombre_operador WHERE Referencia_Nexen = :referencia_nexen";
        $insert1 = "INSERT INTO [dbo].[Log_Facturas] ([Referencia_Nexen], [Numero_Factura], [Fecha_Factura], [Fechope], [Horaope], [Usuario], [Estatus]) VALUES (:referencia_nexen, :numero_factura, :fecha_factura, :fechope, :horaope, :usuario, :estatus)";
    
        $stmt1 = $conn_bd->prepare($update1);
        $stmt1->bindValue(':nombre_operador', $nombre_operador);
        $stmt1->bindValue(':referencia_nexen', $referencia_nexen);
        $stmt1->execute();

        $stmt2 = $conn_bd->prepare($update2);
        $stmt2->bindValue(':nombre_operador', $nombre_operador);
        $stmt2->bindValue(':referencia_nexen', $referencia_nexen);
        $stmt2->bindValue(':rfc_empresa_input', $rfc_empresa_input);
        $stmt2->bindValue(':dir_empresa_input',$dir_empresa_input);
        $stmt2->execute();

        $stmt3 = $conn_bd->prepare($update3);
        $stmt3->bindValue(':nombre_operador', $nombre_operador);
        $stmt3->bindValue(':referencia_nexen', $referencia_nexen);
        $stmt3->execute();

        // Obtener los valores de Numero_Factura y Fecha_Factura de la tabla Operacion_Facturas
        $selectFactura = "SELECT Numero_Factura, Fecha_Factura FROM [dbo].[Operacion_Facturas] WHERE Referencia_Nexen = :referencia_nexen";
        $stmtFactura = $conn_bd->prepare($selectFactura);
        $stmtFactura->bindValue(':referencia_nexen', $referencia_nexen);
        $stmtFactura->execute();

        $rowFactura = $stmtFactura->fetch(PDO::FETCH_ASSOC);

        $numeroFactura = $rowFactura['Numero_Factura'];
        $fechaFactura = $rowFactura['Fecha_Factura'];

        $stmtLogFactura = $conn_bd->prepare($insert1);
        $stmtLogFactura->bindValue(':referencia_nexen', $referencia_nexen);
        $stmtLogFactura->bindValue(':numero_factura', $numeroFactura);
        $stmtLogFactura->bindValue(':fecha_factura', $fechaFactura);
        $stmtLogFactura->bindValue(':fechope', $fechope);
        $stmtLogFactura->bindValue(':horaope', $horaope);
        $stmtLogFactura->bindValue(':usuario', $name_usuario);
        $stmtLogFactura->bindValue(':estatus', 'A');
        $stmtLogFactura->execute();


        // Confirmar la transacción
        $conn_bd->commit();

        
        // Mostrar mensaje de éxito
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'message' => 'Operación actualizada correctamente'));
    } catch (PDOException $e) {
        // Mostrar mensaje de error
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Error al ejecutar el update de operaciones: ' . $e->getMessage()));

        // Revertir la transacción
        $conn_bd->rollBack();
    }

}  else {
    // Opción no válida
    echo 'Opción no válida';
}
