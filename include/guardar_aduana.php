<?php
require '../conexion/bd.php';


if(isset($_POST['numero_aduana']) && !empty($_POST['numero_aduana'])&&
    isset($_POST['denominacion_aduana']) && !empty($_POST['denominacion_aduana'])){

    $numero_aduana = $_POST['numero_aduana'];
    $denominacion_aduana = $_POST['denominacion_aduana'];

    // $select_estatus =$conn_bd->prepare("SELECT * FROM [dbo].[Catalogo_Aduanas] WHERE Codigo = '{$numero_aduana}'");
    $select_estatus =$conn_bd->prepare("SELECT * FROM [dbo].[Aduanas] WHERE Aduana = '{$numero_aduana}'");

    if($select_estatus->execute()){
    //  $update_estatus = $conn_bd->prepare("UPDATE [dbo].[Catalogo_Aduanas] SET [Codigo] = :valor1, [Denominación] = :valor2 WHERE Codigo = '{$numero_aduana}'");
        $update_estatus = $conn_bd->prepare("UPDATE [dbo].[Aduanas] SET [Aduana] = :valor1, [Denominación] = :valor2 WHERE Aduana = '{$numero_aduana}'");
        $update_estatus->bindParam(":valor1",$numero_aduana);
        $update_estatus->bindParam(":valor2",$denominacion_aduana);

        if($update_estatus->execute()){
            echo'<script type="text/javascript">
            alert("SE INSERTO CORRECTAMENTE LA ADUANA");
            window.history.back();
        </script>';
        }else{
            echo'<script type="text/javascript">
            alert("NO SE PUDO INSERTAR LA ADUANA");
            window.history.back();
            </script>';
        }
    }
}else{
    echo'<script type="text/javascript">
    alert("POR FAVOR LLENA TODOS LOS CAMPOS SOLICITADOS");
    window.history.back();
</script>';
}

?>