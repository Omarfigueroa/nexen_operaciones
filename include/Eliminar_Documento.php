<?php
 require('../conexion/bd_sb.php');

try {
 
  // Obtener el ID del archivo desde el parámetro de la URL
  $id = $_GET['id'];
  $Referencia = $_GET['referencia'];
  $Accion = "ELIMINADO";
  $query = "UPDATE [Nexen].[dbo].[FK_DOCUMENTOS_CARPETA] SET Estatus = 0, Accion = :accion WHERE id = :id AND Referencia_Nexen=:referencia";
  $consulta = $conn_sb->prepare($query);
  $consulta->bindParam(':id',$id);
  $consulta->bindParam(':referencia',$Referencia);
  $consulta->bindParam(':accion',$Accion);

  $query_fk_catalogo = "UPDATE [Nexen].[dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION] SET Estatus = 0 WHERE  Referencia_Nexen=:referencia";
  $consulta_fk = $conn_sb->prepare($query_fk_catalogo);
  $consulta_fk->bindParam(':referencia',$Referencia);
  $consulta_fk->execute();

  $query_fk_catalogo = "UPDATE [Nexen_Recuperacion].[dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION] SET Estatus = 0 WHERE  Referencia_Nexen=:referencia";
  $consulta_fk = $conn_sb->prepare($query_fk_catalogo);
  $consulta_fk->bindParam(':referencia',$Referencia);
  $consulta_fk->execute();

  if($consulta->execute()){
    $mensaje = "SE ELIMINO CORRECTAMENTE";
    $tipo = "success";
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