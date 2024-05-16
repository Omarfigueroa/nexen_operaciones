<?php

namespace App\Models;

use App\Connection\SQLConnection;
use Exception;

class ReporteCarpetas
{
    private SQLConnection $db;

    public function __construct()
    {
        $this->db = new SQLConnection();
    }

    /**
     * Otiene toda la informacion necesaria para hacer el reporte.
     *
     * @return array
     */
    public function index(): array
    {
        try {
            $sql = "SELECT Referencia_Nexen,
                            ISNULL([GUIA], 'No') AS GUIA,
                            ISNULL([PAKING LIST ORIGINAL], 'No') AS PAKING_LIST_ORIGINAL,
                            ISNULL([FACTURA ORIGINAL], 'No') AS FACTURA_ORIGINAL,
                            ISNULL([BL ORIGEN O HOUSE], 'No') AS BL_ORIGEN_O_HOUSE,
                            ISNULL([EXPEDIENTE DIGITAL], 'No') AS EXPEDIENTE_DIGITAL,
                            ISNULL([CUENTA DE GASTOS], 'No') AS CUENTA_DE_GASTOS,
                            ISNULL([OTROS], 'No') AS OTROS,
                            ISNULL([OTROS_1], 'No') AS OTROS_1,
                            ISNULL([OTROS_2], 'No') AS OTROS_2,
                            ISNULL([OTROS_3], 'No') AS OTROS_3,
                            ISNULL([OTROS_4], 'No') AS OTROS_4,
                            ISNULL([OTROS_5], 'No') AS OTROS_5,
                            ISNULL([OTROS_6], 'No') AS OTROS_6,
                            ISNULL([OTROS_7], 'No') AS OTROS_7,
                            ISNULL([OTROS_8], 'No') AS OTROS_8
                    FROM (
                        SELECT cdo.TIPO_OPE,
                                fd.Nombre_Documento,
                                fd.Referencia_Nexen,
                                CASE WHEN fd.Nombre_Documento IS NOT NULL THEN 'Si' ELSE NULL END AS Uploaded
                        FROM CATALOGO_DOCUMENTOS_OPERERACION cdo
                        LEFT JOIN FK_DOCUMENTOS_CARPETA fd ON cdo.ID_CATALOGO_DOCUMENTOS = fd.id_catalogo_documentos
                        WHERE fd.Estatus = 1
                    ) AS SourceTable
                    PIVOT (
                        MAX(Uploaded) FOR Nombre_Documento IN (
                            [GUIA], 
                            [PAKING LIST ORIGINAL], 
                            [FACTURA ORIGINAL], 
                            [BL ORIGEN O HOUSE],
                            [EXPEDIENTE DIGITAL],
                            [CUENTA DE GASTOS],
                            [OTROS],
                            [OTROS_1],
                            [OTROS_2],
                            [OTROS_3],
                            [OTROS_4],
                            [OTROS_5],
                            [OTROS_6],
                            [OTROS_7],
                            [OTROS_8]
                        )
                    ) AS PivotTable";

            $result = $this->db->open()->query($sql);
            
            return $result->fetchAll();
        } catch (Exception $e) {
            throw $e;
        }
    }
}