<?php
if(!isset($_SESSION)){  
    session_start(); 
    $session_id=session_id();
}


if(!isset($_SESSION['usuario_nexen'])){
    header('Location: '.ruta_relativa().'view/login.php');
    die();
}

require_once (CONEXION_PATH.'bd.php'); 

$usuario=$_SESSION['usuario_nexen'];
$sql="SELECT Sesion_Id FROM [Conexión_Usuario] WHERE Usuario = '$usuario' AND Estatus=1";
$buscar_sesion = $conn_bd->prepare($sql);
$buscar_sesion -> execute();
$results_sesion = $buscar_sesion -> fetch(PDO::FETCH_ASSOC);

if($results_sesion['Sesion_Id'] != $session_id ){
    $_SESSION['msj_alerta']="Se inicio otra sesión con este Usuario";
    header('Location: '.ruta_relativa().'view/login.php');
    die();
}