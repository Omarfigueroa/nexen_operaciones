<?php
function truncateFloat($number, $digitos)
{
    $raiz = 10;
    $multiplicador = pow ($raiz,$digitos);
    $resultado = ((int)($number * $multiplicador)) / $multiplicador;
    return number_format($resultado, $digitos);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #conceptos {
            border-collapse: collapse; 
            border:1px solid #69899F;
        } 
        #conceptos td{
            border:1px dotted #000000;
            padding:5px;
        }
        table td:first-child{
            border-left:0px solid #000000;
        }
        table th{
        border:2px solid #69899F;
        padding:5px;
        }
    </style>

</head>
<body> 
    <table style="width: 100%;" align="center">
        <tbody>
            <tr>
                <td colspan="2" style="text-align:center;"><FONT SIZE=5><?php echo  $proveedor;?></FONT></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;height: 30px;"><FONT SIZE=1><?php echo $domicilio;?></FONT></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;"><FONT SIZE=2>TAX ID: <?php echo $tax_id;?></FONT></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;"><FONT SIZE=5>INVOICE</FONT></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align:right;"><FONT SIZE=2>INVOICE NO: <?php echo $no_factura;?></FONT></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align:right;"><FONT SIZE=2>DATE 	<?php echo $fecha_factura;?></FONT></td>
            </tr>
            <tr>
                <td style="width: 10%;text-align:left;"><FONT SIZE=1>SHIP TO</FONT></td>
                <td style="height: 150px; width: 90%;">
                    <h6>
                    <?php echo  $importador_exportador;?><br><br>
                    <?php echo  $domicilio_fiscal;?><br><br>
                    RFC. <?php echo  $rfc;?>
                    </h6>
                </td>

            </tr>
            <tr> 
                <td  colspan="2">
                    <table id="conceptos" style="width: 100%;" align="center">
                        <tr>
                            <th style="text-align:center;"><FONT SIZE=2></FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2></FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2></FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2></FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>INCOTERM</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2><?php echo $incot['Incoterms']; ?></FONT></th>
                        </tr>
                        <tr>
                            <th style="text-align:center;"><FONT SIZE=2>MARKS</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>DESCRIPTION OF GOODS</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>QUANTITY</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>UNIT</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>UNIT PRICE (<?php echo $moneda['Moneda']; ?>)</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>AMOUNT (<?php echo $moneda['Moneda']; ?>)</FONT></th>
                        </tr>
                        <?php 
                            foreach ($conceptos as $conc) {
                        ?>
                        <tr>
                            <td style="width: 12%;text-align:center;"><FONT SIZE=1><?php echo $conc['Mark']; ?></FONT></td>
                            <td style="width: 40%;text-align:center;"><FONT SIZE=1><?php echo $conc['Descripcion_cove_I']; ?></FONT></td>
                            <td style="width: 12%;text-align:center;"><FONT SIZE=1><?php echo $conc['Cantidad']; ?></FONT></td>
                            <td style="width: 12%;;text-align:center;"><FONT SIZE=1><?php echo $conc['Unidad_Medida']; ?></FONT></td>
                            <td style="width: 12%;text-align:right;"><FONT SIZE=1><?php echo ( is_null($conc['Precio_Unitario']) ? '' : truncateFloat($conc['Precio_Unitario'],5)); ?></FONT></td>
                            <td style="width: 12%;text-align:right;"><FONT SIZE=1><?php echo truncateFloat($conc['Total'],5); ?></FONT></td>
                        </tr>
                        <?php
                            }
                        ?>

                    </table>

                    <table style="width: 100%;" align="center">
                        <tr>
                            <td colspan="6" style="text-align:right;"><FONT SIZE=1>COUNTRY OF ORIGIN: <?php echo $pais_origen; ?></FONT></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>