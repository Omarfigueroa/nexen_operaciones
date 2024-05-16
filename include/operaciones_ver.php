<?php
        require_once('../conexion/bd.php');

        $boton="<button class='badge text-bg-success' style='background-color: green;'>REFERENCIA</button>";

        $query= "SELECT 
        O.Tipo_Trafico,
        O.NUM_OPERACION,
        O.Usuario,
        O.FECHOPE,
        O.Patente,
        O.REFERENCIA_NEXEN,
        O.Referencia_Cliente,
        O.Cliente,
        O.BL,
        O.Contenedor_1,
        O.Contenedor_2,
        O.No_Pedimento, 
        O.Fecha_Arribo, 
        O.Fecha_Notificación, 
        O.Fecha_Modulación,
        O.Fecha_Pago_Anticipo,
        O.Importador_Exportador,
        O.Clave_Pedimento,
        O.Estatus,
        O.DENOMINACION_ADUANA,
        O.tipo_trafico,
        O.NUMERO_ECONOMICO,
        O.DETALLE_MERCANCIA,
        (SELECT COUNT(*) FROM FK_DOCUMENTOS_CARPETA FK WHERE FK.Referencia_Nexen = O.REFERENCIA_NEXEN) AS CONTEO_ARCHIVOS
    FROM 
        Operacion_nexen O
    LEFT JOIN 
        Usuarios_Login U ON O.Usuario = U.Usuario
    ORDER BY 
        O.NUM_OPERACION DESC;";
        $consultar=$conn_bd->prepare($query);
        $consultar -> execute();
        $row= $consultar -> fetchAll(PDO::FETCH_ASSOC);
                
        for ($i = 0; $i < count($row); $i++) {
                $btnView = '<a class="btn btn-sm btnViewUsuario eyewait text-white"  href="../include/ver_operacion.php?referencia='.$row[$i]['REFERENCIA_NEXEN'].'" role="button"><i class="bi bi-eye-fill" style="font-size:18px;"></i></a>';
               if(($row[$i]['Tipo_Trafico'] != '' && $row[$i]['Tipo_Trafico'] != null) && $row[$i]['CONTEO_ARCHIVOS'] > 0 ){
                $prueba = '<div class="custom-icon file text-white" onclick="openCarpetas(\'' . $row[$i]['REFERENCIA_NEXEN'] . '\', \'' . $row[$i]['Tipo_Trafico'] . '\');"><i class="bi bi-folder-fill"></i></div>';
               }else{
                $prueba='';
               }
                $row[$i]['NUM_OPERACION'] = '<div class="text-center">'.$btnView.$prueba.'</div>';
            }
            

        echo json_encode($row); 
        die;