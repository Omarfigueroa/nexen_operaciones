<?php 
    require_once ($_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php');
    require_once (INCLUDE_PATH.'validar_sesiones.php');
    header('Location: '.ruta_relativa().'view/operaciones.php');
