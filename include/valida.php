<?php   
    require('../conexion/bd.php');

    if ($_POST) {
        
        $id_catalogo = $_POST['id_catalogo'];

        $sql = "SELECT * From [dbo].[CATALOGO_DOCUMENTOS_OPERERACION] WHERE ID_CATALOGO_DOCUMENTOS ='{$id_catalogo}' " ; 
        $resultado = $conn_bd->prepare($sql);
        $resultado->execute();
        $row = $resultado->fetchAll( PDO::FETCH_ASSOC );
        echo json_encode($row);
    }
?>