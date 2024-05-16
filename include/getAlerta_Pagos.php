<?php
date_default_timezone_set('America/Mexico_City');

session_start();
$usuario = $_SESSION['usuario_nexen'];

require '../conexion/bd.php';

// Consulta SQL para obtener el nombre del usuario a partir del valor de $usuario
$sql = "SELECT RTRIM(LTRIM(nombre_usuario)) as nombre_usuario FROM Usuarios_Login WHERE RTRIM(LTRIM(Usuario)) = :usuario";

try {
    $stmt = $conn_bd->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // El usuario fue encontrado en la tabla, el nombre se almacena en la variable $nombreUsuario
        $nombreUsuario = $result['nombre_usuario'];
    } else {
        // El usuario no fue encontrado en la tabla, asignar un valor predeterminado o mostrar un mensaje de error
        $nombreUsuario = 'Usuario Desconocido';
        // O puedes mostrar un mensaje de error:
        // echo "Error: Usuario no encontrado en la base de datos.";
    }
} catch (PDOException $e) {
    echo "Error al consultar la base de datos: " . $e->getMessage();
}

// Ejemplo de cómo podrías obtener los datos de la base de datos
$stmt = $conn_bd->prepare("SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Usuario = '$nombreUsuario' AND [Estatus_Alerta_Pago] = 1");

$stmt->execute();

// Obtener los registros que cumplen con la condición
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Realizar el conteo de registros obtenidos
$totalRegistros = count($registros);

// Verificar si hay registros para enviar una respuesta con datos o no
if ($totalRegistros > 0) {
    // Hay registros, retorna los datos en formato JSON junto con el nombre del usuario
    echo json_encode(array('success'=> true, 'data' => $registros, 'totalRegistros' => $totalRegistros, 'nombreUsuario' => $nombreUsuario));
} else {
    // No hay registros, retorna una respuesta indicando que no se encontraron datos
    echo json_encode(array('success' => false, 'message' => 'No se encontraron registros.' . $usuario));
}
?>
