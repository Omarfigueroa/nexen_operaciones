<?php
require '../conexion/bd.php';
if(!isset($_SESSION['usuario_nexen'])) { 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }else{
        require '../utils/catalogos.php'; 
        require '../utils/utils.php';

        $user_nexen = $_SESSION['usuario_nexen'];
        $query_user_name ="SELECT * FROM [dbo].[Usuarios_Login] WHERE Usuario = '$user_nexen'";
        $select_user_name = $conn_bd->prepare($query_user_name);

        if($select_user_name -> execute()){
            $result_name = $select_user_name -> fetch(PDO::FETCH_ASSOC); 
            $name_usuario = $result_name['nombre_usuario'];
        }

        //Validar si nombre viene vacio
        if(!$name_usuario){
            header('Location: login.php');
        }


        $opcion = $_POST['opcion'];

        if ($opcion === 'VerificarPassword') {

            if (isset($_POST['password'])) {
                $password = $_POST['password'];
        
                $consulta = $conn_bd->prepare("SELECT * FROM Contraseña_Sup WHERE Contraseña = :password");
                $consulta->bindParam(':password', $password);
        
                try {
                    $consulta->execute();
                    $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        
                    if ($fila) {
                        // La contraseña existe
                        header('Content-Type: application/json');
                        echo json_encode(array('success' => true));
                    } else {
                        // La contraseña no existe
                        header('Content-Type: application/json');
                        echo json_encode(array('success' => false));
                    }
                } catch (PDOException $e) {
                    header('Content-Type: application/json');
                    echo json_encode(array('success' => false, 'message' => 'Error : ' . $e->getMessage()));
                }
            }
        } elseif ($opcion === 'actualizarEstatusFinanciamiento') {
            $solicitud_pago = $_POST['solicitud_pago'];

            $actualziarEstatus =  $conn_bd->prepare("UPDATE [dbo].[FK_Solicitud_Pago] SET Estatus = 'AUTORIZADO' WHERE Num_Operacion = :solicitud_pago");
            $update->bindParam(':solicitud_pago', $solicitud_pago);
            $update->execute();
            echo '<meta http-equiv="REFRESH" content="0;url=../include/enviar_correo_gerentes.php">'; 
                                    
        } else {
            // Opción no válida
            echo 'Opción no válida';
        }


        

    }//Si existe Usuario

}


?>
