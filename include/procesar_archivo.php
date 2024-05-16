<?php

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
    

  // Verificar si se envió un archivo
   if(isset($_FILES['Archivo']) && $_FILES['Archivo']['error'] === UPLOAD_ERR_OK) {
        //variables globales
        date_default_timezone_set('America/Mexico_City');
        $Referencia = $_POST['Referencia'];
        $Tipo_Ope = $_POST['Tipo_Ope'];
        $id_catalogo = $_POST['id_catalogo'];
        $Nombre_Documento = $_POST['nombre'];
        $fechaOpe  = date('Y/m/d');
        $hora = date('H:i:s');
        $Estatus = 1;
        $Accion = "INSERTA";
        //variables del archivo
        $file = $_FILES['Archivo']['tmp_name'];
        $name = $_FILES['Archivo']['name'];
        $tipe_file = $_FILES['Archivo']['type'];
        // ||$tipoArchivo == ".rar"||$tipoArchivo == ".zip"
        //validacion de archivo correcto
        $tipoArchivo = '.' . pathinfo($_FILES["Archivo"]["name"], PATHINFO_EXTENSION);
        if ($tipoArchivo == ".pdf" || $tipoArchivo == ".PDF" || $tipoArchivo == ".xlsx" || $tipoArchivo == ".xls"|| $tipoArchivo == ".XLS"|| $tipoArchivo == ".XLSX" || $tipoArchivo == ".jpg" || $tipoArchivo == ".JPG" 
            ||$tipoArchivo == ".png" || $tipoArchivo == ".PNG" || $tipoArchivo == ".rar" || $tipoArchivo == ".RAR" || $tipoArchivo == ".zip" || $tipoArchivo == ".ZIP"){

            
                $ruta = '../Documentos/';
                $ruta_Destino = $ruta . $Referencia . '/' . $name;

                if (is_dir($ruta)) {
                    if (!is_dir($ruta . $Referencia)) {
                        // Si la carpeta no existe, la creamos con permisos adecuados.
                        if (mkdir($ruta . $Referencia, 0777, true)) {
                            if (move_uploaded_file($file, $ruta_Destino)) {
                                $ruta_carpeta = $ruta. $Referencia.'/';
                                $stmt = $conn_bd->prepare("INSERT INTO [dbo].[FK_DOCUMENTOS_CARPETA] (Nombre_Documento,Documento_ruta,Tipo_Documento,Fechope,Horaope,Usuario,Estatus,Referencia_Nexen,id_catalogo_documentos,Nombre,Type_File,Accion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                                $stmt->bindParam(1, $Nombre_Documento);
                                $stmt->bindParam(2, $ruta_carpeta);
                                $stmt->bindParam(3, $Tipo_Ope);
                                $stmt->bindParam(4, $fechaOpe);
                                $stmt->bindParam(5, $hora);
                                $stmt->bindParam(6,$name_usuario);
                                $stmt->bindParam(7, $Estatus);
                                $stmt->bindParam(8, $Referencia);
                                $stmt->bindParam(9, $id_catalogo);
                                $stmt->bindParam(10, $name);
                                $stmt->bindParam(11, $tipe_file);
                                $stmt->bindParam(12, $Accion);

                                $stmt->execute();
                                $mensaje = "El archivo se ha subido y guardado exitosamente.";
                                $tipo = "success";
                            } else {
                                $mensaje = "No se pudo subir o guardar el archivo.";
                                $tipo = "error";
                            }
                        } else {
                            $mensaje = "No se pudo crear la carpeta.";
                            $tipo = "error";
                        }
                    } else {
                        if (move_uploaded_file($file, $ruta_Destino)) {
                            $ruta_carpeta = $ruta. $Referencia.'/';
                            $stmt = $conn_bd->prepare("INSERT INTO [dbo].[FK_DOCUMENTOS_CARPETA] (Nombre_Documento,Documento_ruta,Tipo_Documento,Fechope,Horaope,Usuario,Estatus,Referencia_Nexen,id_catalogo_documentos,Nombre,Type_File,Accion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                            $stmt->bindParam(1, $Nombre_Documento);
                            $stmt->bindParam(2, $ruta_carpeta);
                            $stmt->bindParam(3, $Tipo_Ope);
                            $stmt->bindParam(4, $fechaOpe);
                            $stmt->bindParam(5, $hora);
                            $stmt->bindParam(6,$name_usuario);
                            $stmt->bindParam(7, $Estatus);
                            $stmt->bindParam(8, $Referencia);
                            $stmt->bindParam(9, $id_catalogo);
                            $stmt->bindParam(10, $name);
                            $stmt->bindParam(11, $tipe_file);
                            $stmt->bindParam(12, $Accion);
                            $stmt->execute();
                            $mensaje = "El archivo se ha subido y guardado exitosamente.";
                            $tipo = "success";
                        } else {
                            $mensaje = "No se pudo subir o guardar el archivo.";
                            $tipo = "error";
                        }
                    }
                } else {
                    $mensaje = "No se encontró la ruta.";
                    $tipo = "error";
                }
            
        }else{
            $mensaje = "No se pudo subir o guardar el archivo.";
            $tipo = "error";
        }
   }else{
        $mensaje = "No se pudo subir o guardar el archivo.";
        $tipo = "error";
   }

?>
<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Aquí puedes incluir el contenido de tu página -->

    <script>
    // Script JavaScript para mostrar la alerta con Swal
    Swal.fire({
        icon: '<?php echo $tipo; ?>',
        title: '<?php echo ($tipo === "success") ? "Éxito" : "Error"; ?>',
        text: '<?php echo $mensaje; ?>'
    }).then(function() {
        // Redirección después de que el usuario interactúa con la alerta
        window.location.href = 'ver_operacion.php?referencia=<?php echo $Referencia; ?>';
    });
    </script>
</body>

</html>