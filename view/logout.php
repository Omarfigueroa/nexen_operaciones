<?php
session_start();
require '../conexion/bd.php';

// $_SESSION['usuarioidrh']="";
// unset($_SESSION['usuarioidrh']);
$select_exis_sesion = "UPDATE [dbo].[Conexión_Usuario] SET Estatus = 0 WHERE Usuario = '".$_SESSION['usuario_nexen']."' AND Estatus=1 AND Sesion_Id='".session_id()."'";
                            
$buscar_sesion = $conn_bd->prepare($select_exis_sesion);
$buscar_sesion -> execute();
//$results_sesion = $buscar_sesion -> fetchAll(PDO::FETCH_ASSOC);

$_SESSION['usuario_nexen']="";
unset($_SESSION['usuario_nexen']);

/*
$_SESSION['abierta']="";
unset($_SESSION['abierta']);
*/
session_destroy();  
header("Location: ../index.php");//use for the redirection to some page  
?>