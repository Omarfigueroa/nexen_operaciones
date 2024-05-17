<?php

date_default_timezone_set('America/Mexico_City');

class OperacionFacturaHandler {
    private $conn_bd;
    private $usuario;
    private $nombre_usuario;

    public function __construct($conexion) {
        $this->conn_bd = $conexion;
    }

    public function obtenerSesion() {
        session_start();
        if (!isset($_SESSION['usuario_nexen'])) {
            header('Location: login.php');
            exit();
        }
        $this->usuario = $_SESSION['usuario_nexen'];
    }

    public function obtenerNombreUsuario() {
        $this->obtenerSesion();

        $query = "SELECT nombre_usuario FROM [dbo].[Usuarios_Login] WHERE Usuario = :usuario";
        $stmt = $this->conn_bd->prepare($query);
        $stmt->bindParam(':usuario', $this->usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->nombre_usuario = $result['nombre_usuario'];
        return $this->nombre_usuario;
    }

    public function leerEmpresas($nombreOperador) {
        $query = "SELECT * FROM [dbo].[EMPRESAS] WHERE [Razon_Social] = :nombreOperador";
        $stmt = $this->conn_bd->prepare($query);
        $stmt->bindParam(':nombreOperador', $nombreOperador);
        $stmt->execute();
        $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $empresas;
    }

    public function insertarOperacionFactura($data) {
        $this->obtenerSesion();
        
        $referencia_nexen = $data['referencia_nexen'];
        $modal_pais_origen = $data['modal_pais_origen'];
        $nombreOperador = $data['nombreOperador'];
        $rfcOperador = $data['rfcOperador'];
        $domOperador = $data['domOperador'];
        $proveedorFact = $data['proveedorFact'];
        $taxId = $data['taxId'];
        $numFactura = $data['numFactura'];
        $fechaFactura = $data['fechaFactura'];
        $total = $data['total'];
        $fechope = date('Y-m-d');
        $horaope = date('H:i:s');
        $total_peso_bruto = $data['total_peso_bruto'];
        $total_peso_neto = $data['total_peso_neto'];

        $query = "SELECT COUNT(*) FROM [dbo].[Operacion_Facturas] WHERE [Numero_Factura] = :numFactura";
        $stmt = $this->conn_bd->prepare($query);
        $stmt->bindParam(':numFactura', $numFactura);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return ['success' => false, 'message' => 'Ya existe el numero de factura: '.$numFactura.' intenta con otro.'];
        } else {
            $query = "INSERT INTO [Operacion_Facturas] ([Referencia_Nexen], [Proveedor], [Tax_Id], [Numero_Factura], [Fecha_Factura], [Importador_Exportador], [RFC_Importador_Exportador], [Domicilio_Fiscal], [Total_General], [Fechope], [HoraoPe], [Usuario], [Estatus], [PAIS_ORIGEN], [PESO_BRUTO_TOTAL], [PESO_NETO_TOTAL]) 
                      VALUES (:referencia_nexen, :proveedorFact, :taxId, :numFactura, :fechaFactura, :nombreOperador, :rfcOperador, :domOperador, :total, :fechope, :horaope, :usuario, 'A', :modal_pais_origen, :total_peso_bruto, :total_peso_neto)";
            $stmt = $this->conn_bd->prepare($query);
            $stmt->bindParam(':referencia_nexen', $referencia_nexen);
            $stmt->bindParam(':modal_pais_origen', $modal_pais_origen);
            $stmt->bindParam(':nombreOperador', $nombreOperador);
            $stmt->bindParam(':proveedorFact', $proveedorFact);
            $stmt->bindParam(':taxId', $taxId);
            $stmt->bindParam(':numFactura', $numFactura);
            $stmt->bindParam(':fechaFactura', $fechaFactura);
            $stmt->bindParam(':rfcOperador', $rfcOperador);
            $stmt->bindParam(':domOperador', $domOperador);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':fechope', $fechope);
            $stmt->bindParam(':horaope', $horaope);
            $stmt->bindParam(':usuario', $this->usuario);
            $stmt->bindParam(':total_peso_bruto', $total_peso_bruto);
            $stmt->bindParam(':total_peso_neto', $total_peso_neto);

            if ($stmt->execute()) {
                $lastId = $this->conn_bd->lastInsertId();
                return ['success' => true, 'message' => 'Primer insert fue correcto', 'lastId' => $lastId, 'referencia_nexen' => $referencia_nexen];
            } else {
                return ['success' => false, 'message' => 'Error al insertar en la tabla "Operacion_Facturas"'];
            }
        }
    }
    
    public function obtenerFacturas($referencia_nexen, $nombreOperador) {
        $stmt = $this->conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas] WHERE [Referencia_Nexen] = :referencia_nexen AND [Importador_Exportador] = :nombreOperador");
        $stmt->bindParam(':referencia_nexen', $referencia_nexen);
        $stmt->bindParam(':nombreOperador', $nombreOperador);
        
        $stmt->execute();
        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode(array('success'=> true,'data' => $facturas));
    }

    public function obtenerDetalleFacturas($Id_Factura) {
        $stmt = $this->conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Id_Factura] = :Id_Factura");
        $stmt->bindParam(':Id_Factura', $Id_Factura);
        $stmt->execute();
        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode(array('success'=> true,'data' => $facturas));
    }
    

    public function borrarPartidas($partida, $factura) {
        $stmt = $this->conn_bd->prepare("DELETE FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Partida] = :partida AND [Numero_Factura] = :factura");
        $stmt->bindParam(':partida', $partida);
        $stmt->bindParam(':factura', $factura);
    
        if ($stmt->execute()) {
            return json_encode(array('success' => true, 'message' => 'Registro eliminado correctamente'));
        } else {
            return json_encode(array('success' => false, 'message' => 'Error al eliminar el registro'));
        }
    }


    public function obtenerEditarFacturas($numero_factura) {
        $stmt = $this->conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas] o
        INNER JOIN [dbo].[Operacion_Facturas_Detalle] F ON o.Referencia_Nexen = F.Referencia_Nexen
        INNER JOIN [dbo].[provedores] AS p ON o.Proveedor = p.Proveedor
        WHERE o.Numero_Factura = :numero_factura");
    
        $stmt->bindParam(':numero_factura', $numero_factura);
        $stmt->execute();
        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $this->conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Factura] = :numero_factura");
        $stmt2->bindParam(':numero_factura', $numero_factura);
        $stmt2->execute();
        $partidas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(array('success'=> true,'data' => $facturas, 'partidas' => $partidas));
    }
    

    public function updateOperacionFactura($proveedor_fact_edit, $modal_pais_origen_edit, $modal_domicilio_proveedor_edit, $modal_rfc_operador_edit, $modal_num_factura_edit, $modal_fecha_factura_edit, $tax_id_edit, $incoterms_edit, $modal_nombre_operador_edit, $modal_domicilio_operador_edit, $total_edit) {
        $fechope = date('Y-m-d');
        $horaoPe = date('H:i:s');
    
        $stmt = $this->conn_bd->prepare("UPDATE [dbo].[Operacion_Facturas] 
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
        $stmt->bindParam(':numero_factura', $modal_num_factura_edit);
    
        if($stmt->execute()) {
            $stmtSelect = $this->conn_bd->prepare("SELECT Id_Factura, Referencia_Nexen FROM [dbo].[Operacion_Facturas] WHERE [Numero_Factura] = :numero_factura");
            $stmtSelect->bindParam(':numero_factura', $modal_num_factura_edit);
            $stmtSelect->execute();
    
            $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            $idFactura = $row['Id_Factura'];
            $Referencia_Nexen = $row['Referencia_Nexen'];
    
            $sql = "DELETE FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Factura] = :numero_factura";
            $stmt = $this->conn_bd->prepare($sql);
            $stmt->bindParam(':numero_factura', $modal_num_factura_edit);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return json_encode(array('success'=> true,'message' => 'La actualizacion de la factura fue correcta', 'messageDeletePartidas' => 'Partidas borradas correctamente, procediendo a insertar...', 'idFactura' => $idFactura, 'Referencia_Nexen' => $Referencia_Nexen));
            } else {
                return json_encode(array('success'=> true,'message' => 'La actualizacion de la factura fue correcta', 'messageDeletePartidas' => 'Falló al borrar partidas', 'idFactura' => $idFactura, 'Referencia_Nexen' => $Referencia_Nexen));
            }
        } else {
            return json_encode(array('success'=> false,'message' => 'La actualizacion de la factura fue incorrecta'));
        }
    }

    
    public function borrarFacturayDetalles($id_factura, $referencia_nexen, $numero_factura, $fecha_factura, $tax_id, $fechope, $horaope, $usuario) {
        $stmtDetalle = $this->conn_bd->prepare("DELETE FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Numero_Factura] = :numero_factura");
        $stmtDetalle->bindParam(':numero_factura', $numero_factura);
    
        $stmtFactura = $this->conn_bd->prepare("DELETE FROM [dbo].[Operacion_Facturas] WHERE [Id_Factura] = :id_factura");
        $stmtFactura->bindParam(':id_factura', $id_factura);
    
        $stmtLogBorrado = $this->conn_bd->prepare("INSERT INTO [dbo].[Log_Borrado_Factura] (Id_Factura, Referencia_Nexen, Tax_Id, Numero_Factura, Fecha_Factura, Fechope, Horaope, Usuario) 
                                            VALUES (:id_factura, :referencia_nexen, :tax_id, :numero_factura, :fecha_factura, :fechope, :horaope, :usuario)");
        $stmtLogBorrado->bindParam(':id_factura', $id_factura);
        $stmtLogBorrado->bindParam(':referencia_nexen', $referencia_nexen);
        $stmtLogBorrado->bindParam(':tax_id', $tax_id);
        $stmtLogBorrado->bindParam(':numero_factura', $numero_factura);
        $stmtLogBorrado->bindParam(':fecha_factura', $id_factura);
        $stmtLogBorrado->bindParam(':fechope', $fechope);
        $stmtLogBorrado->bindParam(':horaope', $horaope);
        $stmtLogBorrado->bindParam(':usuario', $usuario);
    
        $this->conn_bd->beginTransaction();
    
        try {
            $stmtDetalle->execute();
            $stmtFactura->execute();
            $stmtLogBorrado->execute();
    
            $this->conn_bd->commit();
    
            echo json_encode(array('success' => true, 'message' => 'Registros eliminados correctamente'));
        } catch (Exception $e) {
            $this->conn_bd->rollBack();
    
            echo json_encode(array('success' => false, 'message' => 'Error al eliminar los registros '. $e->getMessage()));
        }
    }
    
    public function leerProveedor($taxID) {
        $query = "SELECT * FROM [dbo].[provedores] WHERE [codigo] = :taxID";
        $statement = $this->conn_bd->prepare($query);
        $statement->bindParam(':taxID', $taxID);

        if ($statement->execute()) {
            $proveedor = $statement->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array('success' => true, 'proveedor' => $proveedor));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error en la consulta de proveedores'));
        }
    }
    
    public function updateProveedor($editar_tax_id, $new_tax_id, $editar_proveedor, $editar_domicilio, $editar_email, $editar_whatsapp) {
        $query = "UPDATE [dbo].[provedores] SET codigo = :newTaxID, [Proveedor] = :proveedor, [domicilio] = :domicilio, [correo] = :email, [whatsapp] = :whatsapp WHERE [codigo] = :taxID";
        $statement = $this->conn_bd->prepare($query);
        $statement->bindParam(':proveedor', $editar_proveedor);
        $statement->bindParam(':domicilio', $editar_domicilio);
        $statement->bindParam(':email', $editar_email);
        $statement->bindParam(':whatsapp', $editar_whatsapp);
        $statement->bindParam(':taxID', $editar_tax_id);
        $statement->bindParam(':newTaxID', $new_tax_id);

        if ($statement->execute()) {
            echo json_encode(array('success' => true, 'message' => 'Proveedor actualizado correctamente'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al actualizar el proveedor'));
        }
    }
    
    public function deleteProveedor($editar_tax_id) {
        $query = "DELETE FROM [dbo].[provedores] WHERE [codigo] = :taxID";
        $statement = $this->conn_bd->prepare($query);
        $statement->bindParam(':taxID', $editar_tax_id);

        if ($statement->execute()) {
            echo json_encode(array('success' => true, 'message' => 'Proveedor borrado correctamente'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al borrar el proveedor'));
        }
    }

    public function leerCliente($nombre_cliente) {
        $query = "SELECT * FROM [dbo].[Clientes] WHERE [RAZON SOCIAL ] = :nombre_cliente";
        $statement = $this->conn_bd->prepare($query);
        $statement->bindParam(':nombre_cliente', $nombre_cliente);
    
        $queryDataTable = "SELECT id, [RAZON SOCIAL ] AS RazonSocial FROM [dbo].[Clientes]";
        $statementDataTable = $this->conn_bd->prepare($queryDataTable);
        $statementDataTable->execute();
        $data = $statementDataTable->fetchAll(PDO::FETCH_ASSOC);
    
        if ($statement->execute()) {
            $cliente = $statement->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array('success' => true, 'cliente' => $cliente, 'data' => $data));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error en la consulta de cliente'));
        }
    }
    
    public function updateCliente($razon_social_cliente_edit, $rfc_cliente_edit, $telefono_cliente_edit, $movil_cliente_edit, $nombre_contacto_edit, $email_cliente_1_edit, $email_cliente_2_edit, $dom_cliente_edit) {
        $query = "UPDATE [Nexen].[dbo].[Clientes] SET [RFC ] = :rfc, [TELEFONO] = :telefono, [MOVIL ] = :movil, [CONTACTO] = :contacto, [EMAIL 1] = :email1, [EMAIL 2] = :email2, [Domilio_Fisico] = :domicilio WHERE [RAZON SOCIAL ] = :razon_social";
        $statement = $this->conn_bd->prepare($query);
        $statement->bindParam(':rfc', $rfc_cliente_edit);
        $statement->bindParam(':telefono', $telefono_cliente_edit);
        $statement->bindParam(':movil', $movil_cliente_edit);
        $statement->bindParam(':contacto', $nombre_contacto_edit);
        $statement->bindParam(':email1', $email_cliente_1_edit);
        $statement->bindParam(':email2', $email_cliente_2_edit);
        $statement->bindParam(':domicilio', $dom_cliente_edit);
        $statement->bindParam(':razon_social', $razon_social_cliente_edit);
    
        if ($statement->execute()) {
            echo json_encode(array('success' => true, 'message' => 'Cliente actualizado correctamente'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al actualizar el cliente'));
        }
    }


    public function deleteOperacion($password_sup, $referencia_nexen, $name_usuario) {
        try {
            $this->conn_bd->beginTransaction();
    
            $deleteQueries = array(
                "DELETE FROM Operacion_nexen WHERE Referencia_Nexen = :referencia",
                "DELETE FROM [dbo].[Operacion_Facturas] WHERE [Referencia_Nexen] = :referencia",
                "DELETE FROM [dbo].[Operacion_Facturas_Detalle] WHERE [Referencia_Nexen] = :referencia",
                "DELETE FROM [dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION] WHERE [Referencia_Nexen] = :referencia",
                "DELETE FROM [dbo].[Catalogo_Check_list_Detalle] WHERE [Referencia_Nexen] = :referencia"
            );
    
            foreach ($deleteQueries as $deleteQuery) {
                $stmt = $this->conn_bd->prepare($deleteQuery);
                $stmt->bindValue(':referencia', $referencia_nexen);
                $stmt->execute();
            }
            $this->conn_bd->commit();
    
            $selectUsuario = "SELECT [Usuario] FROM [dbo].[Contraseña_Sup] WHERE [Contraseña] = :password";
            $stmtUsuario = $this->conn_bd->prepare($selectUsuario);
            $stmtUsuario->execute(array(':password' => $password_sup));
            $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC)['Usuario'];
            $fechaActual = date('Y-m-d');
            $horaActual = date('H:i:s');

            $insertLog = "INSERT INTO [dbo].[Log_Borrado] ([Usuario], [Contraseña], [Fechope], [Horaope], [Referencia_Nexen])
                VALUES (:usuario, :password, :fecha, :hora, :Referencia_Nexen)";
            $stmtLog = $this->conn_bd->prepare($insertLog);
            $stmtLog->execute(array(
                ':usuario' => $usuario,
                ':password' => $password_sup,
                ':fecha' => $fechaActual,
                ':hora' => $horaActual,
                ':Referencia_Nexen' => $referencia_nexen
            ));
    
            $insertLogOperacion = "INSERT INTO [dbo].[Fk_Log_Detalle_Ope_Nexen] ([Usuario],[HORA_OPE],[FECHOPE],[REFERENCIA_NEXEN],[Tipo_OPE])
                VALUES(:usuario, :hora , :fecha ,:Referencia_Nexen,'DELETE')";
    
            $stmtLogOpe = $this->conn_bd->prepare($insertLogOperacion);
            $stmtLogOpe->execute(array(
                ':usuario' => $name_usuario,
                ':fecha' => $fechaActual,
                ':hora' => $horaActual,
                ':Referencia_Nexen' => $referencia_nexen
            ));
    
            header('Content-Type: application/json');
            echo json_encode(array('success' => true, 'message' => 'Operación borrada correctamente'));
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'message' => 'Error al ejecutar el borrado de operaciones: ' . $e->getMessage()));
    
            $this->conn_bd->rollBack();
        }
    }
    
    public function verificarPasswordSupervisor($user_sup, $password) {
        if (isset($password)) {
            $consulta = $this->conn_bd->prepare("SELECT * FROM Contraseña_Sup WHERE Usuario = :user_sup AND Contraseña = :password");
            $consulta->bindParam(':user_sup', $user_sup);
            $consulta->bindParam(':password', $password);
    
            try {
                $consulta->execute();
                $fila = $consulta->fetch(PDO::FETCH_ASSOC);
    
                if ($fila) {
                    echo json_encode(array('success' => true));
                } else {
                    echo json_encode(array('success' => false));
                }
            } catch (PDOException $e) {
                echo json_encode(array('success' => false, 'message' => 'Error al ejecutar el borrado de operaciones: ' . $e->getMessage()));
            }
        }
    }
    
    
    
}


require '../../conexion/bd.php';
$handler = new OperacionFacturaHandler($conn_bd);

$opcion = $_POST['opcion'];
$nombreOperador = isset($_POST['nombreOperador']) ? $_POST['nombreOperador'] : '';

switch ($opcion) {
    case 'leerEmpresas':
        $empresas = $handler->leerEmpresas($nombreOperador);
        break;
    case 'insertarOperacionFactura':
        $response = $handler->insertarOperacionFactura($_POST);
        break;
    default:
        break;
}


header('Content-Type: application/json');

if (isset($empresas)) {
    echo json_encode($empresas);
} elseif (isset($response)) {
    echo json_encode($response);
}

?>
