<?php
if(!isset($_SESSION)){  
    session_start();
}



if(isset($_SESSION['usuario_nexen'])){
    require (CONEXION_PATH.'bd.php');
    $select_exis_sesion = "UPDATE [dbo].[Conexión_Usuario] SET Estatus = 0 WHERE Usuario = '".$_SESSION['usuario_nexen']."' AND Estatus=1 AND Sesion_Id='".session_id()."'";
    $buscar_sesion = $conn_bd->prepare($select_exis_sesion);
    $buscar_sesion -> execute();
    
    
    $_SESSION['usuario_nexen']="";
    unset($_SESSION['usuario_nexen']);
    
    session_destroy();  

}



?>