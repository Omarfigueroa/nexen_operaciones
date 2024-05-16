<?php

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/');
define('INCLUDE_PATH', ROOT_PATH.'include/');
define('VIEW_PATH', ROOT_PATH.'view/');
define('UTILS_PATH', ROOT_PATH.'utils/');
define('CONEXION_PATH', ROOT_PATH.'conexion/');

function ruta_relativa(){

    $porciones = explode("/", $_SERVER['PHP_SELF']);
    $ruta="";
    $ruta_raiz="";

    for($i=1; $i<count($porciones)-2; $i++ ){
        $ruta=$ruta.'/'.$porciones[$i];
        $ruta_raiz=$ruta_raiz.'../';
    }

    $ruta=$ruta.'/';
    //return $ruta;
    return $ruta_raiz;
}