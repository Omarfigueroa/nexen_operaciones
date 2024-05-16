<?php
$server = '158.69.113.62';
$database = 'Nexen';
$username = 'sa';
$password = '#Nexen_2023*10/21.#';

try {
  $conn = new PDO("sqlsrv:server=$server;database=$database", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Obtener el ID del archivo desde el parámetro de la URL
  $id = $_GET['id'];
  $referencia_nexen=$_GET['referencia'];
  $query = "SELECT * FROM [dbo].[FK_DOCUMENTOS_CARPETA] WHERE id=:id AND Referencia_Nexen =:referencia ";
  $consulta = $conn->prepare($query);
  $consulta->bindParam(':id',$id);
  $consulta->bindParam(':referencia',$referencia_nexen);
  $consulta->execute();
  $result_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
  $nombre = $result_consulta['Nombre'];
  $ruta = $result_consulta['Documento_ruta'].$nombre;
  


  // print_r($result_consulta);
  // die;

// Verificar si el archivo existe
if (file_exists($ruta)) {
  // Configurar las cabeceras HTTP para la descarga
  header('Content-Type: ' . mime_content_type($ruta));

  // Leer el archivo y mostrarlo en la ventana/pestaña actual
  readfile($ruta);
  
} else {
  $mensaje = "EL ARCHIVO NO EXISTE.";
  $tipo = "error";
}
  
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
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