<?php

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
                <td colspan="2" style="text-align:center;"><FONT SIZE=2><?php echo $tax_id;?></FONT></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;"><FONT SIZE=5>PACKING LIST</FONT></td>
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
                <td style="height: 150px;">
                    <h6>
                    <?php echo  $importador_exportador;?><br>
                    <?php echo  $domicilio_fiscal;?>
                    </h6>
                </td>
                <td></td>
            </tr>
            <tr>
                <td  colspan="2">
                    <table id="conceptos" style="width: 100%;" align="center">
                        <tr>
                            <th style="text-align:center;"><FONT SIZE=2>Cantidad</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>Unidad de Medida</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>Moneda</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>Descripcion</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>Precio Unitario</FONT></th>
                            <th style="text-align:center;"><FONT SIZE=2>Total</FONT></th>
                        </tr>
                        <?php 
                            foreach ($conceptos as $conc) {
                        ?>
                        <tr>
                            <td style="width: 12%;text-align:center;"><FONT SIZE=1><?php echo $conc['Cantidad']; ?></FONT></td>
                            <td style="width: 12%;text-align:center;"><FONT SIZE=1><?php echo $conc['Unidad_Medida']; ?></FONT></td>
                            <td style="width: 12%;text-align:center;"><FONT SIZE=1><?php echo $conc['Moneda']; ?></FONT></td>
                            <td style="width: 40%;;text-align:center;"><FONT SIZE=1><?php echo $conc['Descripcion_Cove']; ?></FONT></td>
                            <td style="width: 12%;text-align:right;"><FONT SIZE=1><?php echo $conc['Precio_Unitario']; ?></FONT></td>
                            <td style="width: 12%;text-align:right;"><FONT SIZE=1><?php echo $conc['Total']; ?></FONT></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>