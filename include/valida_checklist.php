<?php   
require '../conexion/bd.php';

require '../conexion/bd.php';
if(!isset($_SESSION['usuario_nexen'])) 
{ 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }
}

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

    if ($_GET['id_ref'] && $_GET['check']) {
 
        $id_ref = $_GET['id_ref'];
        $checklist = $_GET['check'];
//        $user = $_GET['user'];
        date_default_timezone_set('America/Mexico_City');
        $hora = date('H:i:s');

        $sql_insert= "INSERT INTO [dbo].[Catalogo_Check_list_Detalle]
                                                        ([Id_Catalogo]
                                                        ,[Referencia_Nexen]
                                                        ,[Fechope]
                                                        ,[HoraOpe]
                                                        ,[Usuario])
                                                        VALUES
                                                        ($checklist
                                                        ,'$id_ref'
                                                        ,GETDATE()
                                                        ,'$hora'
                                                        ,'$name_usuario')";
        
        $resultado_insert_check = $conn_bd->prepare($sql_insert);
        $result_insert_check = $resultado_insert_check->execute();
        if( $result_insert_check==TRUE){
            echo'<script type="text/javascript">
            alert("SE VALIDO CORRECTAMENTE EL DOCUMENTO");
            </script>';
             echo '<meta http-equiv="Refresh" content="0;url=ver_operacion.php?referencia='.$id_ref.'">';
        }else{
            echo'No se pudo validar el documento, vuelve a intentarlo';
        }

    }
?>