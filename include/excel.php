<?php
    require '../conexion/bd.php';
    require '../utils/catalogos.php'; 
    //require '../include/validacion_usuarios.php';
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=reporte_excel.xls");
?>
<table id="filtrado" class="table my-3" bgcolor="#EAE8E8">
    <thead class="align-middle">
        <tr class="border"> 
            <th scope="col" class="text-center"># OPERACIÓN</th>
            <th scope="col" class="text-center">REFERENCIA NEXEN</th>
            <th scope="col" class="text-center">CLIENTE</th>
            <th scope="col" class="text-center">BL</th>
            <th scope="col" class="text-center">CONTENEDOR</th>
            <th scope="col" class="text-center">PEDIMENTO</th>
            <th scope="col" class="text-center">FECHA ARRIBO</th>
            <th scope="col" class="text-center">FECHA NOTIFICACIÓN</th>
            <th scope="col" class="text-center">FECHA MODULACIÓN</th> 
            <th scope="col" class="text-center">FECHA PAGO</th>
            <th scope="col" class="text-center">IMPO/EXPO</th>
            <th scope="col" class="text-center">CVE PEDIMENTO</th>
            <th scope="col" class="text-center">DENOMINACIÓN ADUANA</th>
            <th scope="col" class="text-center">TIPO TRAFICO</th>
            <th scope="col" class="text-center"># ECO</th>
        </tr>
    </thead>
    <tbody class="text-center align-middle border">
        <?php
        foreach($result_operacion_nex as $mostrar){    
        ?> 
        <tr>
            <td><?php echo $mostrar['NUM_OPERACION'] ?></td>
            <td><?php echo $mostrar['REFERENCIA_NEXEN']; ?></td>
            <td><?php echo $mostrar['Cliente']; ?></td>
            <td><?php echo $mostrar['BL']; ?></td>
            <td><?php echo $mostrar['Contenedor_1']; ?></td>
            <td><?php echo $mostrar['No_Pedimento']; ?></td>
            <td><?php echo $mostrar['Fecha_Arribo']; ?></td>
            <td><?php echo $mostrar['Fecha_Notificación']; ?></td>
            <td><?php echo $mostrar['Fecha_Modulación']; ?></td>
            <td><?php echo $mostrar['Fecha_Pago_Anticipo']; ?></td>
            <td><?php echo $mostrar['Importador_Exportador']; ?></td>
            <td><?php echo $mostrar['Clave_Pedimento']; ?></td>
            <td><?php echo $mostrar['Estatus']; ?></td>
            <td><?php echo $mostrar['DENOMINACION_ADUANA']; ?></td>
            <td><?php echo $mostrar['tipo_trafico']; ?></td>
            <td><?php echo $mostrar['NUMERO_ECONOMICO']; ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>    
</table>