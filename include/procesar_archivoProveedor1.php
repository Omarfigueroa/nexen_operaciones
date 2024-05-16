<?php
date_default_timezone_set('America/Mexico_City');
require('../conexion/bd.php');
if(!isset($_SESSION['usuario_nexen']))
{
    session_start();
    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php'); 
    }
}

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

// Verificar la opción seleccionada
if ($opcion === 'InsertarDocumento') {

    $mes = $_POST['mes'];
    $anio = date('Y');
    $btn_doc = $_POST['btn_doc'];
    $idProveedor = $_POST['idProveedor'];

    //documento
    $archivo = $_FILES['documento']['tmp_name'];
    $nombreArchivo = $_FILES['documento']['name'];
    $tipoArchivo = $_FILES['documento']['type'];
    $tamanioArchivo = $_FILES['documento']['size'];

    
    //$documento = base64_encode('0x54686973206973206120746573742076616C7565');
    //$documento = base64_encode(file_get_contents($archivo));

    $fechope = date('Y-m-d');
    $horaope = date('H:i:s');

    $doc = '';

    if($btn_doc == 'OF_Up'){
        $doc = 'Doc_obligaciones';
    }
    if($btn_doc == 'CIF_Up'){
        $doc = 'Doc_CIF';
    }

    //comprobacion back end que solo el archivo pueda ser PDF
    if ($tipoArchivo === 'application/pdf') {
        // Ejemplo de consulta utilizando PDO
        $query = "SELECT * FROM [dbo].[Proveedor_Documentos] WHERE [Mes] = '$mes' AND [Anio] = '$anio' AND [Tipo_Documento] = '$doc' AND [Id_Proveedor] = '$idProveedor'";
        $statement = $conn_bd->query($query);

        // Obtener los resultados de la consulta
        $res_documento = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($res_documento) > 0) {
            // Existe algo en la columna $doc
            $response = ['success' => false, 'message' => 'Ya existe un documento ' . $doc . ' Tienes que borrarlo primero.'];
            // Devolver los datos como respuesta JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {

            $nombreArchivot = explode(".", $nombreArchivo);
            $extensionArchivo = strtolower(end($nombreArchivot));

            $hora_carga = date('His');
            $nombre_documento = $doc. '_'.$idProveedor.'_' .$hora_carga. '.' .$extensionArchivo;
            $uploadFileDir = '../documentos_proveedores/';
            $dest_path = $uploadFileDir . $nombre_documento;

            if(move_uploaded_file($archivo, $dest_path)){

                $queryInsert = "INSERT INTO [dbo].[Proveedor_Documentos] (Id_Proveedor, Mes, Anio, Tipo_Documento, Fechope, Horaope, Usuario, Ruta_Archivo, Nombre_Archivo) 
                    VALUES (:idProveedor, :mes, :anio, :doc, :fechope, :horaope, :name_usuario, :uploadFileDir, :nombre_documento)";
                $statementInsert = $conn_bd->prepare($queryInsert);
                $statementInsert->bindParam(':idProveedor', $idProveedor);
                $statementInsert->bindParam(':mes', $mes);
                $statementInsert->bindParam(':anio', $anio);
                $statementInsert->bindParam(':doc', $doc);
                //$statementInsert->bindParam(':documento', $documento);
                $statementInsert->bindParam(':fechope', $fechope);
                $statementInsert->bindParam(':horaope', $horaope);
                $statementInsert->bindParam(':name_usuario', $name_usuario);
                $statementInsert->bindParam(':uploadFileDir', $uploadFileDir);
                $statementInsert->bindParam(':nombre_documento', $nombre_documento);

                $statementInsert->execute();

                if ($statementInsert) {
                    $response = ['success' => true, 'message' => 'El documento ha sido insertado correctamente'];
                } else {
                    $response = ['success' => false, 'message' => 'Error al insertar el documento'];
                }
                // Devolver los datos como respuesta JSON
                header('Content-Type: application/json');
                echo json_encode($response);
            }else{
                $response = ['success' => false, 'message' => 'Error al intentar guardar el archivo en directorio'];
                // Devolver los datos como respuesta JSON
                header('Content-Type: application/json');
                echo json_encode($response);
            }

        }
    } else {
        // El archivo no es un PDF, muestra un mensaje de error o realiza alguna acción adecuada
        $response = ['success' => false, 'message' => 'Solo se permiten archivos PDF'];
        // Devolver los datos como respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}else if($opcion === 'DescargarDocumento'){

    //Primero se comprueba que exista el archivo
    //Se traen las variables
    $mes = $_POST['mes'];
    $btn_doc = $_POST['btn_doc'];
    $anio = date('Y');
    $idProveedor = $_POST['idProveedor'];
    $id_doc = $_POST['id_doc'];

    $doc = '';

    if($btn_doc == 'OF_Down'){
        $doc = 'Doc_obligaciones';
    }
    if($btn_doc == 'CIF_Down'){
        $doc = 'Doc_CIF';
    }

    //$querySelect = "SELECT * FROM [dbo].[Proveedor_Documentos] WHERE [Mes] = '$mes' AND [Anio] = '$anio' AND [Tipo_Documento] = '$doc'";
    $querySelect = "SELECT * FROM [dbo].[Proveedor_Documentos] WHERE id=".$id_doc;

    $statement = $conn_bd->query($querySelect);

    // Obtener los resultados de la consulta
    $res_documento = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (count($res_documento) <= 0) {
        // Existe algo en la columna $doc
        //$response = ['success' => false, 'message' => 'No existe un documento para ' . $doc ."-". $mes ."-". $anio ."-". $idProveedor];
        $response = ['success' => false, 'message' => 'No existe un documento para id ' .$id_doc];
        header('Content-Type: application/json');
        echo json_encode($response);

    } else {

        // Consulta para obtener el archivo desde la base de datos
            //$query = "SELECT * FROM [dbo].[Proveedor_Documentos] WHERE [Id_Proveedor] = '$idProveedor' AND [Tipo_Documento] = '$doc' AND [Mes] = '$mes'  AND [Anio] = '$anio'";
            $query = "SELECT * FROM [dbo].[Proveedor_Documentos] WHERE id=".$id_doc;
            $stmt = $conn_bd->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            /*
            //Definimos el nombre del archivo
            $nombre_archivo = $archivo['Tipo_Documento'] . "_" . $archivo['Mes'] . "_" . $archivo['Anio'];

            // Obtener el contenido del archivo decodificándolo desde base64
            $documento = base64_decode($archivo['Documento']);
            */

            $dest_path=$res['Ruta_Archivo'].$res['Nombre_Archivo'];

            $archivo=file_get_contents($dest_path);
            // Crear una respuesta JSON con el contenido del archivo en base64
            $response = ['success' => true, 'pdfData' => base64_encode($archivo), 'nombre_archivo' => $res['Nombre_Archivo']];

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
    }

}else if($opcion === 'BorrarDocumento'){

    //Primero se comprueba que exista el archivo
    //Se traen las variables
    $mes = $_POST['mes'];
    $btn_doc = $_POST['btn_doc'];
    $anio = date('Y');
    $idProveedor = $_POST['idProveedor'];
    $id_doc = $_POST['id_doc'];

    $doc = '';

    if($btn_doc == 'OF_Delete'){
        $doc = 'Doc_obligaciones';
    }
    if($btn_doc == 'CIF_Delete'){
        $doc = 'Doc_CIF';
    }

    //$querySelect = "SELECT * FROM [dbo].[Proveedor_Documentos] WHERE [Mes] = '$mes' AND [Anio] = '$anio' AND [Tipo_Documento] = '$doc' AND [Documento] IS NOT NULL";
    $querySelect = "SELECT * FROM [dbo].[Proveedor_Documentos] WHERE id=".$id_doc;
    $statement = $conn_bd->query($querySelect);

    // Obtener los resultados de la consulta
    $res_documento = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (count($res_documento) <= 0) {
        // Existe algo en la columna $doc
        $response = ['success' => false, 'message' => 'No existe un documento para id' . $id_doc];
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $queryDelete = "DELETE FROM [dbo].[Proveedor_Documentos] WHERE id=".$id_doc;
        $statement = $conn_bd->query($queryDelete);

        // Existe algo en la columna $doc
        $response = ['success' => true, 'message' => 'Documento borrado con exito!'];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

}

?>
