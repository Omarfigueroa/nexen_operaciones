<?php
// Establecer la zona horaria predeterminada
date_default_timezone_set('America/Mexico_City');
session_start();

// Incluir archivo de conexión a la base de datos
require '../../conexion/bd.php';

try {
    // Verificar si hay una sesión activa
    if (!isset($_SESSION['usuario_nexen']) || empty($_SESSION['usuario_nexen'])) {
        throw new Exception("No se ha iniciado sesión.");
    }
    // Obtener el nombre de usuario de la sesión
    $usuario = $_SESSION['usuario_nexen'];
    $query_user_name ="SELECT * FROM [dbo].[Usuarios_Login] WHERE Usuario = '$usuario'";
    $select_user_name = $conn_bd->prepare($query_user_name);
    if(!$select_user_name->execute()){
        throw new Exception("Error al obtener el nombre de usuario.");
    }
    // Obtener el nombre de usuario
    $result_name = $select_user_name->fetch(PDO::FETCH_ASSOC); 
    $name_usuario = $result_name['nombre_usuario'];
    // Validar si el nombre de usuario está vacío
    if(empty($name_usuario)){
        throw new Exception("Nombre de usuario no encontrado.");
    }
    // Obtener los datos del formulario
    $referencia_nexen = isset($_GET['referencia_nexen']) ? $_GET['referencia_nexen'] : '';
    // Consultar las facturas en la base de datos
    $stmt = $conn_bd->prepare("SELECT * FROM [dbo].[Operacion_Facturas] WHERE [Referencia_Nexen] = '$referencia_nexen'");

    if(!$stmt->execute()){
        throw new Exception("Error al ejecutar la consulta.");
    }
    // Obtener todas las filas de resultados
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $btnDetalle = '';
    $Invoice='';
    $Packing_List ='';
    $Editar = '';
    $Eliminar = '';
            
    for ($i=0; $i < count($facturas); $i++) {

            $btnDetalle = '<button class="btn btn-primary" onClick="DetalleFactura('.$facturas[$i]['Id_Factura'].')" type="button" >Detalle</button>';
            $facturas[$i]['Detalles'] = '<div class="text-center">' . $btnDetalle .'</div>';

        }
    for ($i=0; $i < count($facturas); $i++) {

        $Invoice = '<a class="btn btn-primary " href="generarinvoice.php?id='.$facturas[$i]['Id_Factura'].'" target="_blank">&nbsp;&nbsp;<i class="bi bi-printer text-light"></i>&nbsp;&nbsp;</a>';
        $facturas[$i]['Invoice'] = '<div class="text-center">' . $Invoice .'</div>';

    }
    for ($i=0; $i < count($facturas); $i++) {

        $Packing_List = '<a class="btn btn-primary btn-detalles" href="generarpacking.php?id=' .$facturas[$i]['Id_Factura'].'" target="_blank">&nbsp;&nbsp;<i class="bi bi-printer text-light"></i>&nbsp;&nbsp;</a></td>';
        $facturas[$i]['Packing_List'] = '<div class="text-center">' . $Packing_List .'</div>';

    }
    for ($i=0; $i < count($facturas); $i++) {

        $Editar = '<button class="btn btn-warning" onClick="EditarFactura('.$facturas[$i]['Id_Factura'].')" type="button" ><i class="bi bi-pencil-square text-primary"></i></button>';
        $facturas[$i]['Editar'] = '<div class="text-center" style="display: inline-block;">' . $Editar . '</div>';

    }
    for ($i=0; $i < count($facturas); $i++) {

        $Eliminar = '<button class="btn btn-danger" onClick="EliminarFactura('.$facturas[$i]['Id_Factura'].')" type="button" ><i class="bi bi-trash text-white"></i></button>';
        $facturas[$i]['Eliminar'] = '<div class="text-center" style="display: inline-block;">' .$Eliminar . '</div>';

    }
    // Retornar los datos en formato JSON
    echo json_encode($facturas);
} catch (Exception $e) {
    // Manejar cualquier excepción capturadas
    http_response_code(400); // Bad Request
    echo json_encode(array('success'=> false, 'error' => $e->getMessage()));
}
?>
