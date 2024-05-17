<?php

date_default_timezone_set('America/Mexico_City');

class OperacionFacturaHandler {
    private $conn_bd;

    public function __construct($conexion) {
        $this->conn_bd = $conexion;
    }

    public function obtenerNombreUsuario() {
        session_start();
        if (!isset($_SESSION['usuario_nexen'])) {
            header('Location: login.php');
            exit();
        }

        $usuario = $_SESSION['usuario_nexen'];
        $query = "SELECT nombre_usuario FROM [dbo].[Usuarios_Login] WHERE Usuario = :usuario";
        $stmt = $this->conn_bd->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nombre_usuario'];
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
        $usuario = $_SESSION['usuario_nexen'];
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
            $query = "INSERT INTO [Operacion_Facturas] ( [Referencia_Nexen], [Proveedor], [Tax_Id], [Numero_Factura], [Fecha_Factura], [Importador_Exportador], [RFC_Importador_Exportador], [Domicilio_Fiscal], [Total_General], [Fechope], [HoraoPe], [Usuario], [Estatus], [PAIS_ORIGEN], [PESO_BRUTO_TOTAL], [PESO_NETO_TOTAL]) 
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
            $stmt->bindParam(':usuario', $usuario);
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
}

// Uso de la clase
require '../../conexion/bd.php';
$handler = new OperacionFacturaHandler($conn_bd);

$opcion = $_POST['opcion'];
$nombreOperador = isset($_POST['nombreOperador']) ? $_POST['nombreOperador'] : '';

if ($opcion === 'leerEmpresas') {
    $empresas = $handler->leerEmpresas($nombreOperador);
    header('Content-Type: application/json');
    echo json_encode($empresas);
} else if ($opcion === 'insertarOperacionFactura') {
    $response = $handler->insertarOperacionFactura($_POST);
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
