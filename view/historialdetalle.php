<?php 
 if(!isset($_SESSION['usuario_nexen'])) 
 { 
     session_start();

     if(!isset($_SESSION['usuario_nexen'])){
         header('Location: login.php');
     }
     
 }

require '../utils/catalogos.php'; 
require '../utils/utils.php';


if (isset($_GET['referencia']) && !empty($_GET['referencia'])) {
    $ref=$_GET['referencia'];
}else{
    $ref="";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones</title>
    <link href="../css/estilos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
    <script src="../utils/functions_historial.js"></script>
    <style>
    .check {
        width: 20px;
        height: 20px;
    }

    .titulos {
        height: 37px;
        font: 20px;
    }
    </style>
</head>

<body>
    <?php include('../plantilla/menu.php'); ?>
    <br><br><br>

        <h1>Historial Operaciones</h1>
        
            <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <div class="tile-body">
                            <div class="table-responsive">
                                <table class="table table-hover cell-border table-bordered" id="tableHistorial">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Num_Operacion</th>
                                            <th>Referencia_Cliente</th>
                                            <th>Cliente</th>
                                            <th>BL</th>
                                            <th>Contenedor_1</th>
                                            <th>Pto_llegada</th>
                                            <th>Fecha_Arribo</th>
                                            <th>Fecha_Notificacion</th>
                                            <th>Fecha_pago_Anticipo</ht>
                                            <th>Fecha_Modulacion</th>
                                            <th>No_Pedimento</th>
                                            <th>Imp/Exp</th>
                                            <th>Clave_Pedimento</th>
                                            <th>No_Factura</th>
                                            <th>Valor_Factura</th>
                                            <th>Descripcion_cove</th>
                                            <th>Factura_anexo24</th>
                                            <th>tipo_cambio</th>
                                            <th>Fecha_Factura24</th>
                                            <th>WMS</th>
                                            <th>Estatus</th>
                                            <th>Hora_Ope</th>
                                            <th>FechOpe</th>
                                            <th>Patente</th>
                                            <th>Moneda</th>
                                            <th>Denominacion_Aduana</th>
                                            <th>Guia_House</th>
                                            <th>Tipo_Operacion</th>
                                            <th>Proveedor</th>
                                            <th>Bultos</th>
                                            <th>Peso_Bruto</th>
                                            <th>Tipo_Trafico</th>
                                            <th>Guia_House1</th>
                                            <th>Valida_Fecha</th>
                                            <th>Fecha_Factura</th>
                                            <th>Factura_Salida_Anexo24</th>
                                            <th>Num_Salida_WMS</th>
                                            <th>Num_Rectificacion</th>
                                            <th>Fecha_Liberacion</th>
                                            <th>Observaciones</th>
                                            <th>Id_Cliente</th>
                                            <th>Numero_Economico</th>
                                            <th>Referencia_Nexen</th>
                                            <th>Contenedor_2</th>
                                            <th>Tipo_Ope</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Carlos</td>
                                            <td>Henández</td>
                                            <td>carlos@info.com</td>
                                            <td>78542155</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>


</body>

</html>