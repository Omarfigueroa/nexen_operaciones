<?php

namespace App\Models;

use App\Connection\SQLConnection;

class ReporteDocumentosFaltantes
{
    protected SQLConnection $conn;
    public string $mes;
    public string $anio;

    public function __construct($mes, $anio)
    {
        $this->conn = new SQLConnection();
        $this->mes = $mes;
        $this->anio = $anio;
    }

    public function index()
    {
        try {
            $sql = "SELECT
                        fksp.Referencia_Nexen,
                        fksp.Concepto,
                        fksp.Tipo_Solicitud,
                    CASE
                        WHEN dsp.Documento IS NOT NULL OR dsp.Ruta IS NOT NULL THEN 'Si'
                        ELSE 'No'
                    END AS Solicitud_Pagos,
                    CASE
                        WHEN dsp.Documento IS NOT NULL OR dsp.Ruta IS NOT NULL THEN 'Si'
                        ELSE 'No'
                    END AS Anticipo_Cliente,
                    CASE
                        WHEN fksp.Ruta_Archivo IS NOT NULL THEN 'Si'
                        ELSE 'No'
                    END AS Pago_Proveedor,
                    CASE
                        WHEN fksp.Ruta_Factura IS NOT NULL THEN 'Si'
                        ELSE 'No'
                    END AS Factura_Proveedor
                    FROM FK_Solicitud_Pago fksp
                    LEFT JOIN Documentos_Solicitud_Pagos dsp
                    ON dsp.Id_Pago = fksp.Num_Operacion
                    WHERE YEAR(fksp.Fechope) = :anio
                    AND MONTH(fksp.Fechope) = :mes";

            $stmt = $this->conn->open()->prepare($sql);
            $stmt->bindParam(':anio', $this->anio);
            $stmt->bindParam(':mes', $this->mes);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if(empty($results)) {
                throw new \PDOException('No hay registros para la fecha seleccionada');
            }

            return $results;
        } catch (\PDOException $th) {
            throw $th;
        }
    }
}