<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Pagos</title>
    <style>
        .contenedor img,
        h1 {
            display: inline-block;
            vertical-align: bottom;
        }

        .contenedor div,
        input,
        label {
            display: inline-block;
            padding-top: 15px;
            vertical-align: middle;
        }

        .contenedor div {
            margin-left: 400px;
        }

        .contenedor input {
            margin-top: 3px;
        }

        .pago h3,
        input {
            display: inline-block;
            vertical-align: middle;

        }

        .pago input {
            margin-top: -8px;
            vertical-align: middle;
            border-style: none;
            width: 500px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .justificacion textarea{
            text-align: center;
            border-style: none;
        }
        .autorizado label{
            margin-left: 35px;
        }
        .autorizado input{
            align-items: center;
            border-style: none;
            margin-top: 5px;
            vertical-align: middle;
        }
        .centrar {
            text-align: center;
        }
        .paddin{
            padding-right: 200px;
        }
        tr.espacio-tr {
            padding-bottom: 15px;
            margin-bottom: 200px;
        }
    </style>


</head>

<body>
    <?php
        require '../conexion/bd.php';
        $id_pago_solicitud = $_GET['id'];

        $query_valida = "SELECT * FROM [dbo].[FK_Solicitud_Pago] WHERE Num_Operacion = :id_pago";
        $valida_pdf = $conn_bd->prepare($query_valida);
        $valida_pdf->bindParam(':id_pago',$id_pago_solicitud);
        $valida_pdf->execute();
        $result_valida = $valida_pdf->fetch(PDO::FETCH_ASSOC);

    
        $valida_tipo = $result_valida['Tipo_Solicitud'];


        if($valida_tipo != 'FINANCIADO'){
            // $query ="SELECT *,D.Contenedor_guia_economico FROM [dbo].[FK_Solicitud_Pago] F  INNER JOIN [dbo].[Documentos_Solicitud_Pagos] D ON F.Num_Operacion = D.Id_Pago  WHERE  F.Num_Operacion = '{$id_pago_solicitud}'";
            $query ="SELECT *,D.Contenedor_guia_economico,O.Contenedor_2 FROM [dbo].[FK_Solicitud_Pago] F  INNER JOIN [dbo].[Documentos_Solicitud_Pagos] D ON F.Num_Operacion = D.Id_Pago INNER JOIN [dbo].[Operacion_nexen] O ON O.REFERENCIA_NEXEN = F.Referencia_Nexen WHERE  F.Num_Operacion = '{$id_pago_solicitud}'";
            $resul = $conn_bd->prepare($query);
            $resul->execute();
            $datos = $resul->fetchAll(PDO::FETCH_ASSOC);
            foreach ($datos as $dato) {
                $referencia_nexen =  $dato['Referencia_Nexen'];
                $nombre_cliente = $dato['Cliente'];
                $operador = $dato['Operador'];
                $fecha = $dato['Fechope'];
                $Contenedor = $dato['Contenedor'];
                $contenedor_2 = $dato['Contenedor_2'];
                $Numero_Economico = $dato['Numero_Economico'];
                $Gia_House = $dato['Guia_House'];
                $Contenedor_guia_economico = $dato['Contenedor_guia_economico'];
                $Razon_social_Receptora = $dato['Razon_Social_Receptora'];
                $RFC = $dato['RFC'];
                $SWT_ABBA = $dato['SWT_ABBA'];
                $Cuenta_Clabe = $dato['Cuenta_Clabe'];
                $cuenta_interbancaria = $dato['CLABE_INTERBANCARIA'];
                $Banco_Destino = $dato['Banco_Destino'];
                $Domicilio_Destino = $dato['Domicilio_Destino'];
                $Domicilio_Razon_Receptora = $dato['Domicilio_Razon_Receptora'];
                $Banco_Receptor = $dato['Banco_Receptor'];
                $Concepto = $dato['Concepto'];
                $Monto = $dato['Monto'];
                $Tipo_Solicitud = $dato['Tipo_Solicitud'];
                $observaciones = $dato['Observaciones'];
                $tipo_pago = $dato['Tipo_Operacion'];
                $Hora = $dato['Hora'];
                $moneda = $dato['Moneda'];
                $referencia_proveedor = $dato['Ref_proveedor'];
                $Numero_pedimento = $dato['Numero_Pedimento'];
                
            }
        }else{
            // $query ="SELECT * FROM [dbo].[FK_Solicitud_Pago]  WHERE  Num_Operacion = '{$id_pago_solicitud}'";
            $query ="SELECT S.*,O.Contenedor_2 FROM [dbo].[FK_Solicitud_Pago] S INNER JOIN [dbo].[Operacion_nexen] O ON O.REFERENCIA_NEXEN = s.Referencia_Nexen  WHERE  S.Num_Operacion = '{$id_pago_solicitud}'";
            $resul = $conn_bd->prepare($query);
            $resul->execute();
            $datos = $resul->fetchAll(PDO::FETCH_ASSOC);
            foreach ($datos as $dato) {
                $referencia_nexen =  $dato['Referencia_Nexen'];
                $nombre_cliente = $dato['Cliente'];
                $operador = $dato['Operador'];
                $fecha = $dato['Fechope'];
                $Contenedor = $dato['Contenedor'];
                $contenedor_2 = $dato['Contenedor_2'];
                $Numero_Economico = $dato['Numero_Economico'];
                $Gia_House = $dato['Guia_House'];
                $Razon_social_Receptora = $dato['Razon_Social_Receptora'];
                $RFC = $dato['RFC'];
                $SWT_ABBA = $dato['SWT_ABBA'];
                $Cuenta_Clabe = $dato['Cuenta_Clabe'];
                $cuenta_interbancaria = $dato['CLABE_INTERBANCARIA'];
                $Banco_Destino = $dato['Banco_Destino'];
                $Domicilio_Destino = $dato['Domicilio_Destino'];
                $Domicilio_Razon_Receptora = $dato['Domicilio_Razon_Receptora'];
                $Banco_Receptor = $dato['Banco_Receptor'];
                $Concepto = $dato['Concepto'];
                $Monto = $dato['Monto'];
                $Tipo_Solicitud = $dato['Tipo_Solicitud'];
                $observaciones = $dato['Observaciones'];
                $tipo_pago = $dato['Tipo_Operacion'];
                $Hora = $dato['Hora'];
                $moneda = $dato['Moneda'];
                $Numero_pedimento = $dato['Numero_Pedimento'];
                $referencia_proveedor = $dato['Ref_proveedor'];
            }
        }

    ?>

    <div class="contenedor">
        <img src="https://nexenelog.mx/nexen_operaciones/img/logoNexen.png" class="img-fluid my-3" alt="profile">
        <h1>Solicitud de Pagos Proveedor</h1>
        <div>
            <label for="Referencia">REFERENCIA: </label>
            <input type="text" style=" height: 20px;  border-style: none; " value="<?php echo $referencia_nexen?>">
        </div>
    </div>
    <div class="pago">
        <div>
            <h3>Pago por:</h3>
            <input id="enaltam" type="text" value="<?php echo $operador?>">
        </div>
        <div>
            <h3>Pagar a: </h3>
            <input id="enaltam" type="text" value="<?php echo $Razon_social_Receptora?>">
        </div>
    </div>
    <div>
        <table >
            <tr class="espacio-tr">
                <td class="paddin">FECHA: </td>
                <td class="centrar"><?php echo $fecha?></td>
            </tr>
            <tr class="espacio-tr">
                <td>CLIENTE: </td>
                <td class="centrar"><?php echo $nombre_cliente?></td>
            </tr>
            <?php if($tipo_pago != 'VIRTUAL'){
                    if($Contenedor != NULL){?>
                <tr class="espacio-tr">
                    <td>CONTENEDOR/GUIA: </td>
                    <td class="centrar"><?php echo $Contenedor?></td>
                </tr>
                <?php }else if($Numero_Economico != NULL){?>
                    <tr class="espacio-tr">
                        <td>CONTENEDOR/GUIA: </td>
                        <td class="centrar"><?php echo $Numero_Economico?></td>
                    </tr>
                <?php } else if($Gia_House != NULL){ ?>
                    <tr class="espacio-tr">
                        <td>CONTENEDOR/GUIA: </td>
                        <td class="centrar"><?php echo $Gia_House?></td>
                    </tr>
                <?php } else if($Contenedor_guia_economico != NULL ){ ?>
                    <tr class="espacio-tr">
                        <td>CONTENEDOR/GUIA: </td>
                        <td class="centrar"><?php echo $Contenedor_guia_economico?></td>
                    </tr>
                <?php } 
                }else {
                    if($Numero_pedimento != NULL){ ?>
                    <tr class="espacio-tr">
                        <td>PEDIMENTO: </td>
                        <td class="centrar"><?php echo $Numero_pedimento?></td>
                    </tr>
            <?php   }
                } 
            ?> 

             <tr class="espacio-tr">
                <td>CONTENEDOR 2: </td>
                <td class="centrar"><?php echo $contenedor_2?></td>
            </tr>
            <tr>
                <td>RFC: </td>
                <td class="centrar"><?php echo $RFC?></td>
            </tr>
            <tr>
                <td>RAZON SOCIAL <br>RECEPTORA: </td>
                <td class="centrar"><?php echo $Razon_social_Receptora?></td>
            </tr>

            <tr>
                <td>SWIFT / ABA: </td>
                <td class="centrar"><?php echo $SWT_ABBA?></td>
            </tr>
            <tr>
                <td>CUENTA: </td>
                <td class="centrar"><?php echo $Cuenta_Clabe?></td>
            </tr>
            <tr>
                <td>CUENTA INTERBANCARIA : </td>
                <td class="centrar"><?php echo $cuenta_interbancaria?></td>
            </tr>
            <tr>
                <td>BANCO DESTINO: </td>
                <td class="centrar"><?php echo $Banco_Destino?></td>
            </tr>
            <tr>
                <td>DOMICILIO <br>COMPLETO: </td>
                <td class="centrar"><?php echo $Domicilio_Destino?></td>
            </tr>
            <tr>
                <td>BANCO<br>INTERMEDIARIO: </td>
                <td class="centrar"><?php echo $Banco_Receptor?></td>
            </tr>
            <tr>
                <td>DOMICILIO<br>COMPLETO: </td>
                <td class="centrar"><?php echo $Domicilio_Razon_Receptora?></td>
            </tr>
            <tr>
                <td>CONCEPTO: </td>
                <td class="centrar"><?php echo $Concepto?></td>
            </tr>
            <tr>
                <td>MONEDA: </td>
                <td class="centrar"><?php echo $moneda?></td>
            </tr>
            <tr>
                <td>MONTO: </td>
                <td class="centrar"><?php echo '$ '. $Monto?></td>
            </tr>
            <tr>
                <td>ANTICIPO/FINANCIADO: </td>
                <td class="centrar"><?php echo $Tipo_Solicitud?></td>
            </tr>
            <tr>
                <td>REFERENCIA PROVEEDOR: </td>
                <td class="centrar"><?php echo $referencia_proveedor?></td>
            </tr>
        </table>
    </div>
    <?php if($Tipo_Solicitud === 'ANTICIPO'){?>
        <div class="justificacion">
            <label for="justificacion">JUSTIFICACION: </label>
            <textarea  cols="50" rows="50"><?php echo $observaciones ?></textarea>
        </div>
        <div class="autorizado">
            <label for="autorizado">Autorizado por: <strong>Rodolfo SanJuan SanJuan</strong></label>
            <label for="Fecha">Fecha: </label>
            <input type="text" value="<?php echo $fecha?>">
        </div>
        <?php }else{?>
            <div class="justificacion">
            <label for="justificacion">JUSTIFICACION: </label>
            <textarea  cols="50" rows="50"><?php echo $observaciones ?></textarea>
            </div>
            <div class="autorizado">
                <label for="autorizado">Autorizado por: <strong>Liliana Rodriguez Barros</strong></label>
                <label for="Fecha">Fecha: </label>
                <input type="text" value="<?php echo $fecha?>">
            </div>
        <?php }?>

</body>

</html>

<?php
$html = ob_get_clean();

require_once '../dompdf/autoload.inc.php';


use Dompdf\Dompdf;

$dompdf = new Dompdf();


$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper("letter");

$dompdf->render();
$pdfContent = $dompdf->output();

// Guardar el archivo PDF en una carpeta
$nombreArchivo = $referencia_nexen .$id_pago_solicitud.'_pago.pdf';
$rutaArchivo = '../reportes/solicitudes_pagos/' . $nombreArchivo;
file_put_contents($rutaArchivo, $pdfContent);

// $dompdf->stream($nombreArchivo, array("Attachment" => true));
// echo "<script>window.open('$rutaArchivo', '_blank').focus();</script>";
$javascriptCode = "<script>
var link = document.createElement('a');
link.href = '$rutaArchivo';
link.target = '_blank';
link.download = '$nombreArchivo';
link.click();
</script>";

// Imprimir el código JavaScript
echo $javascriptCode;

echo '<meta http-equiv="REFRESH" content="0;url=../include/ver_operacion.php?referencia='.$referencia_nexen.'">'; 

?> 