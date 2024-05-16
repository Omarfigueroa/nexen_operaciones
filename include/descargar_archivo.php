<?php

namespace App\Models;

use App\Connection\SQLConnection;
use PDO;
use PDOException;
use InvalidArgumentException;
use Exception;
require __DIR__.'/../vendor/autoload.php';


class PdfGenerar
{
    protected SQLConnection $conn;

    public function __construct()
    {
        $this->conn = new SQLConnection();
    }
    public function descargarArchivo($id, $referencia_nexen)
    {
        try {
            // Preparar la consulta SQL
            $query = "SELECT * FROM [dbo].[FK_DOCUMENTOS_CARPETA] WHERE id=:id AND Referencia_Nexen=:referencia";
            $consulta = $this->conn->open()->prepare($query);
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':referencia', $referencia_nexen, PDO::PARAM_STR);

            // Ejecutar la consulta
            $consulta->execute();
            $result_consulta = $consulta->fetch(PDO::FETCH_ASSOC);

            // Obtener el nombre y la ruta del archivo
            $nombre = $result_consulta['Nombre'];
            $ruta = $result_consulta['Documento_ruta'] . $nombre;

            // Verificar si el archivo existe
            if (file_exists($ruta)) {
                // Preparar la descarga del archivo
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($ruta));
                header('Content-Length: ' . filesize($ruta));

                // Leer y enviar el archivo al cliente
                readfile($ruta);

                // Mensaje de éxito
                $mensaje = "SE DESCARGÓ CORRECTAMENTE.";
                $tipo = "success";
            } else {
                // Mensaje de error si el archivo no existe
                $mensaje = "EL ARCHIVO NO EXISTE.";
                $tipo = "error";
            }
        } catch (PDOException $e) {
            // Mensaje de error en caso de excepción
            $mensaje = "Error: " . $e->getMessage();
            $tipo = "error";
        }

        // Mostrar la alerta con SweetAlert
        echo '<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
    Swal.fire({
        icon: "' . $tipo . '",
        title: "' . ($tipo === "success" ? "Éxito" : "Error") . '",
        text: "' . $mensaje . '"
    }).then(function() {
        // Redireccionar después de cerrar la alerta
        window.location.href = "ver_operacion.php?referencia=' . $referencia_nexen . '";
    });
    </script>
</body>

</html>';
    }
}

// Obtener los parámetros de la URL
$id = $_GET['id'] ?? null;
$referencia_nexen = $_GET['referencia'] ?? null;

// Crear una instancia de PdfMostrar y descargar el archivo
$pdfMostrar = new PdfGenerar();
$pdfMostrar->descargarArchivo($id, $referencia_nexen);
