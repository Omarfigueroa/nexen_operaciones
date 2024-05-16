<?php
    
require '../conexion/bd.php';

// $query= "SELECT Via,Denominación FROM Aduanas";
// $consultar=$conn_bd->prepare($query);
// $consultar -> execute();
// $result= $consultar -> fetchAll(PDO::FETCH_ASSOC);
// // print_r($result);
// if( $result === false) {
//     die( print_r( sqlsrv_errors(), true) );
//     // print_r($result);
// }


$query= "SELECT  clave FROM [dbo].[Claves_Pedimento] ORDER BY Clave ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_clv_pedimento= $consultar -> fetchAll(PDO::FETCH_ASSOC);
// print_r($result_clv_pedimento);
if( $result_clv_pedimento === false) {
    die( print_r( sqlsrv_errors(), true) );
    // print_r($result_clv_pedimento);
}


$query= "SELECT DESCRIPCION FROM TIPO_OPERACION ORDER BY DESCRIPCION ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_cat_tipoope= $consultar -> fetchAll(PDO::FETCH_ASSOC);
// print_r($result_cat_tipoope);
if( $result_cat_tipoope === false) {
    die( print_r( sqlsrv_errors(), true) );
    // print_r($result_cat_tipoope);
}

$query= "SELECT [RAZON SOCIAL ] FROM Clientes ORDER BY [RAZON SOCIAL ] ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_cat_razon= $consultar -> fetchAll(PDO::FETCH_ASSOC);
// print_r($result_cat_razon);
if( $result_cat_razon === false) {
    die( print_r( sqlsrv_errors(), true) );
    // print_r($result_cat_razon);
}

$query= "SELECT  medios FROM vias_tranporte ORDER BY medios ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_cat_vias= $consultar -> fetchAll(PDO::FETCH_ASSOC);
// print_r($result_cat_vias);
if( $result_cat_vias === false) {
    die( print_r( sqlsrv_errors(), true) );
    // print_r($result_cat_vias);
}

$query= "SELECT proveedor, codigo, domicilio FROM [provedores] ORDER BY proveedor ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_cat_proveedor= $consultar -> fetchAll(PDO::FETCH_ASSOC);
// print_r($result_cat_proveedor);
if( $result_cat_proveedor === false) {
    die( print_r( sqlsrv_errors(), true) );
     print_r($result_cat_proveedor);
}

$query= "SELECT  id_estatus,Descripcion FROM ESTATUS_OPERACION ORDER BY Descripcion ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_cat_estatus= $consultar -> fetchAll(PDO::FETCH_ASSOC);
// print_r($result_cat_estatus);
if( $result_cat_estatus === false) {
    die( print_r( sqlsrv_errors(), true) );
    // print_r($result_cat_estatus);
}

$query= "SELECT *  FROM [Operacion_nexen] order by FECHOPE DESC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_operacion_nex= $consultar -> fetchAll(PDO::FETCH_ASSOC);

if( $result_operacion_nex === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$query_empresas= "SELECT *  FROM [EMPRESAS] ORDER BY Razon_Social ASC";
$consult_empresas=$conn_bd->prepare($query_empresas);
$consult_empresas -> execute();
$result_empresas= $consult_empresas -> fetchAll(PDO::FETCH_ASSOC);

if( $result_empresas === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$query= "SELECT medios FROM vias_tranporte ORDER BY medios ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_cat_vias= $consultar -> fetchAll(PDO::FETCH_ASSOC);
if( $result_cat_vias === false) {
    die( print_r( sqlsrv_errors(), true) );
}
 
$query= "SELECT * FROM Catalogo_Aduanas ORDER BY Denominación ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_catalogo_aduanas= $consultar -> fetchAll(PDO::FETCH_ASSOC);
if( $result_catalogo_aduanas === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$query= "SELECT * FROM MONEDAS ORDER BY PREFIJO ASC";
$consultar=$conn_bd->prepare($query);
$consultar -> execute();
$result_catalogo_monedas= $consultar -> fetchAll(PDO::FETCH_ASSOC);
if( $result_catalogo_monedas === false) {
    die( print_r( sqlsrv_errors(), true) );
}
$query= "SELECT * FROM TIPO_SOLICITUD_PAGO  WHERE ESTATUS = 'A' ORDER BY DESCRIPCION ASC";
$consultar_tipo=$conn_bd->prepare($query);
$consultar_tipo -> execute();
$result_catalogo_tipo_solicitud_pago= $consultar_tipo -> fetchAll(PDO::FETCH_ASSOC);
if( $result_catalogo_tipo_solicitud_pago === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$query= "SELECT * FROM [dbo].[Cuenta_Destino]  ORDER BY Cuenta_Destino ASC";
$consultar_cuenta_des=$conn_bd->prepare($query);
$consultar_cuenta_des -> execute();
$result_cuenta_destino= $consultar_cuenta_des -> fetchAll(PDO::FETCH_ASSOC);
if( $result_cuenta_destino === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$query= "SELECT * FROM [dbo].[TIPO_CUENTA]  ORDER BY [DESCRIPCION] ASC";
$consultar_tipo_cuenta=$conn_bd->prepare($query);
$consultar_tipo_cuenta -> execute();
$result_tipo_cuenta = $consultar_tipo_cuenta -> fetchAll(PDO::FETCH_ASSOC);
if( $result_tipo_cuenta === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$query= "SELECT * FROM [dbo].[TIPO_SERVICIO]  ORDER BY [DESCRIPCION] ASC";
$consultar_tipo_servicio=$conn_bd->prepare($query);
$consultar_tipo_servicio -> execute();
$result_tipo_servicio = $consultar_tipo_servicio -> fetchAll(PDO::FETCH_ASSOC);
if( $result_tipo_servicio === false) {
    die( print_r( sqlsrv_errors(), true) );
}
?>