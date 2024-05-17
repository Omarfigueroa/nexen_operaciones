<?php

class GetSelectsData {
    private $conn_bd;

    public function __construct($connection) {
        $this->conn_bd = $connection;
    }

    /**
     * Obtains data from the Provider table.
     *
     * @return array Data from the Provider table.
     */
    public function getProvider() {
        try {
            $stmt = $this->conn_bd->prepare("SELECT proveedor, codigo, domicilio FROM [provedores] ORDER BY proveedor ASC");
            $stmt->execute();
            $provider = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $provider;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Obtains data from the Catalogo_Factura_Incoterms table.
     *
     * @return array Data from the Catalogo_Factura_Incoterms table.
     */
    public function getIncoterms() {
        try {
            $stmt = $this->conn_bd->prepare("SELECT * FROM [dbo].[Catalogo_Factura_Incoterms] ORDER BY [Descripcion] ASC");
            $stmt->execute();
            $incoterms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $incoterms;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Obtains data from the Extent table.
     *
     * @return array Data from the Extent table.
     */
    public function getExtent() {
        try {
            $stmt = $this->conn_bd->prepare("SELECT [Id_medida], [Medida], [Estatus] FROM [dbo].[medidas]");
            $stmt->execute();
            $extent = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $extent;
        } catch (PDOException $e) {
            return [];
        }
    }
    /**
     * Obtains data from the currency table.
     *
     * @return array Data from the currency table.
     */
    public function getCurrency() {
        try {
            $stmt = $this->conn_bd->prepare("SELECT * FROM [dbo].[MONEDAS]");
            $stmt->execute();
            $currency = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $currency;
        } catch (PDOException $e) {
            return [];
        }
    }
}

require '../../conexion/bd.php';

$action = $_GET['action']; // Assuming action parameter is sent via GET

$getSelectsData = new GetSelectsData($conn_bd);

switch ($action) {
    case 'incoterms':
        $data = $getSelectsData->getIncoterms();
        break;
    case 'extent':
        $data = $getSelectsData->getExtent();
        break;
    case 'currency':
        $data = $getSelectsData->getCurrency();
        break;
    case 'provider':
        $data = $getSelectsData->getProvider();
        break;
    default:
        $data = [];
        break;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
