<?php

namespace App\Models;

use App\Connection\SQLConnection;
use PDO;
use PDOException;
use InvalidArgumentException;
use Exception;
require __DIR__.'../../../vendor/autoload.php';


class PdfMostrar
{
    protected SQLConnection $conn;

    public function __construct()
    {
        $this->conn = new SQLConnection();
    }


    public function getArchivo($id_pago)
    {
        try {
            // Validar y sanitizar el ID del pago
            $id_pago = filter_var($id_pago, FILTER_VALIDATE_INT);
            if ($id_pago === false) {
                throw new InvalidArgumentException("ID de pago inválido");
            }

            // Consultar el archivo desde la base de datos
            $query = "SELECT * FROM Documentos_Solicitud_Pagos WHERE Id_Pago = :id_pago";
            $stmt = $this->conn->open()->prepare($query);
            $stmt->bindParam(':id_pago', $id_pago, PDO::PARAM_INT);
            $stmt->execute();
            $archivo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($archivo && isset($archivo['Documento']) && !empty($archivo['Documento'])) {
                // Si el documento está en base64
                header("Content-type: application/pdf");
                echo base64_decode($archivo['Documento']);
                exit;
            } elseif ($archivo && isset($archivo['Ruta']) && !empty($archivo['Ruta'])) {
                // Si el documento está guardado en carpeta en el servidor
                $rutaArchivo = $archivo['Ruta'];
                if (file_exists($rutaArchivo)) {
                    // Verificar el tipo de archivo por extensión
                    $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
                    $tipoContenido = mime_content_type($rutaArchivo);
                    header("Content-type: $tipoContenido");
                    header("Content-Disposition: inline; filename=\"" . basename($rutaArchivo) . "\"");
                    readfile($rutaArchivo);
                    exit;
                } else {
                    // El archivo no existe
                    throw new Exception('El archivo no se encuentra disponible para visualizar.');
                }
            } else {
                // El archivo no existe
                throw new Exception('El archivo no existe.');
            }
        } catch (PDOException | InvalidArgumentException | Exception $e) {
            // Manejar errores de manera adecuada
            error_log("Error al obtener archivo: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(array("error" => $e->getMessage()));
        }
    }
}

// Obtener el ID del pago desde la URL
$id_pago = $_GET['id'] ?? null;

// Crear una instancia de SQLConnection
$connection = new SQLConnection();

// Crear una instancia de DetallePagos y obtener el archivo
if ($id_pago !== null) {
    $detallePagos = new PdfMostrar($connection);
    $detallePagos->getArchivo($id_pago);
} else {
    // Manejar el caso en el que no se proporciona el ID del pago
    http_response_code(400);
    echo json_encode(array("error" => "ID de pago no proporcionado"));
}
?>
