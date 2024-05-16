<?php

namespace App\Models;

use App\Connection\SQLConnection;

class DetallePagos
{
    protected SQLConnection $conn;

    public function __construct()
    {
        $this->conn = new SQLConnection();
    }

    public function index(): array
    {
        try {
            $query = "WITH CTE_Solicitud_Pago AS (
                SELECT
                    Referencia_Nexen AS REFERENCIA,
                    Tipo_Operacion AS OPERACION,
                    COUNT(*) AS CANTIDAD_PAGOS,
                    SUM(CASE WHEN Estatus = 'PENDIENTE' THEN 1 ELSE 0 END) AS CANTIDAD_PENDIENTES
                FROM FK_Solicitud_Pago
                GROUP BY Referencia_Nexen, Tipo_Operacion
            ),
            CTE_Carpetas AS (
                SELECT 
                    Referencia_Nexen,
                    COUNT(*) AS CARPETAS
                FROM FK_DOCUMENTOS_CARPETA 
                GROUP BY Referencia_Nexen
            )
            SELECT 
                sp.REFERENCIA,
                sp.OPERACION,
                sp.CANTIDAD_PAGOS,
                sp.CANTIDAD_PENDIENTES,
                COALESCE(c.CARPETAS, 0) AS CARPETAS
            FROM CTE_Solicitud_Pago sp
            LEFT JOIN CTE_Carpetas c ON sp.REFERENCIA = c.Referencia_Nexen";
            $stmt = $this->conn->open()->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $stmt->closeCursor();
            return $result;
        } catch (\PDOException $th) {
            throw $th;
        }
    }

    public function show($reference): array
    {
        try {
            $query = "SELECT SP.*, OP.Contenedor_2, D.Id_Pago
            FROM [dbo].[FK_Solicitud_Pago] AS SP
            INNER JOIN operacion_nexen AS OP ON SP.referencia_nexen = OP.referencia_nexen          
            LEFT JOIN Documentos_Solicitud_Pagos AS D ON SP.Num_Operacion = D.Id_Pago   
            WHERE SP.Referencia_Nexen = :reference
            ORDER BY SP.Fechope, SP.Hora DESC";
            $stmt = $this->conn->open()->prepare($query);
            $stmt->bindParam(':reference', $reference);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $stmt->closeCursor();
            return $result;
        } catch (\PDOException $th) {
            throw $th;
        }
    }

    public function getMovementsAll(): array
    {
        try {
            $query = "SELECT * FROM [dbo].[Log_Solicitud_Pagos] ORDER BY Id_Operacion DESC";
            $stmt = $this->conn->open()->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $stmt->closeCursor();
            return $result;
        } catch (\PDOException $th) {
            throw $th;
        }
    }

    public function getDetailsPaymentsByReference($reference): array
    {
        try {
            $query = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE REFERENCIA_NEXEN = :reference";
            $stmt = $this->conn->open()->prepare($query);
            $stmt->bindParam(':reference', $reference);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $stmt->closeCursor();
            return $result;
        } catch (\PDOException $th) {
            throw $th;
        }
    }
    public function setFileInvoice($archivoTemporal, $nombreArchivoDestino, $Num_Operacion, $Mensaje_Update) {
        $referencia = pathinfo($nombreArchivoDestino, PATHINFO_FILENAME);
        $referenciaNexen = strstr($referencia, '_', true);
        $idOperacion = ltrim(strstr($nombreArchivoDestino, '_'), '_');
        $carpetaDestino = "../../reportes/facturas/".$referenciaNexen."/";
    
        if (!file_exists($carpetaDestino)) {
            if (!mkdir($carpetaDestino, 0777, true)) {
                throw new \Exception("Error al crear la carpeta de destino.");
            }
        }
    
        if (!is_dir($carpetaDestino)) {
            throw new \Exception("La carpeta de destino no se pudo crear correctamente.");
        }
    
        if (move_uploaded_file($archivoTemporal, $carpetaDestino . $idOperacion)) {
            $ruta= $carpetaDestino . $idOperacion;
    
            $query = ($Mensaje_Update !== '') ?
            "UPDATE FK_Solicitud_Pago SET Ruta_Factura = ?, Detalles_Correcion = ? WHERE Referencia_Nexen = ? AND Num_Operacion = ?" :
            "UPDATE FK_Solicitud_Pago SET Ruta_Factura = ? WHERE Referencia_Nexen = ? AND Num_Operacion = ?";
        
            $stmt = $this->conn->open()->prepare($query);
            $stmt->bindParam(1, $ruta);
            
            if ($Mensaje_Update !== '') {
                $stmt->bindParam(2, $Mensaje_Update);
                $stmt->bindParam(3, $referenciaNexen);
                $stmt->bindParam(4, $Num_Operacion);
            } else {
                $stmt->bindParam(2, $referenciaNexen);
                $stmt->bindParam(3, $Num_Operacion);
            }
        
            $stmt->execute();
            return "Archivo guardado correctamente";
        } else {
            throw new \Exception("Error al guardar el archivo.");
        }
    }
}    