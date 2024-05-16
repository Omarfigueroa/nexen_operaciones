<?php
require('../conexion/bd_sb.php');


if (isset($_POST['referencia_nexen']) && isset($_POST['tipo_trafico'])) {
    $capas=$_POST['capas'];
    $cargo = isset($_POST['cargo']) ? $_POST['cargo'] : '';
    $mospuntos = ($capas == '1') ? '../' : '../../';
    $rutadir = $mospuntos . "include/";
    
    $referencia_nexen = $_POST['referencia_nexen'];
	$tipo_trafico = $_POST['tipo_trafico'];
    $Accion = "RECUPERADO";
    $query_carpetas = "SELECT Nombre_Documento,Documento_ruta,Tipo_Documento,Referencia_Nexen,Nombre,id_catalogo_documentos,Estatus,id FROM [Nexen].[dbo].[FK_DOCUMENTOS_CARPETA] WHERE Referencia_Nexen = :referencia AND Estatus != 0";
    $carpetas = $conn_sb->prepare($query_carpetas);
    $carpetas->bindParam(':referencia',$referencia_nexen); 
    $carpetas->execute();
    $result_carpetas = $carpetas->fetchAll(PDO::FETCH_ASSOC);
    
    if(!empty($result_carpetas)){
        $catalogo = "SELECT  TIPO_OPE,DOCUMENTO,ID_CATALOGO_DOCUMENTOS FROM [Nexen].[dbo].[CATALOGO_DOCUMENTOS_OPERERACION] WHERE TIPO_OPE = :tipo_trafico";
        $consulta_catalogo = $conn_sb->prepare($catalogo);
        $consulta_catalogo->bindParam(':tipo_trafico',$tipo_trafico);
        $consulta_catalogo->execute();
        $result_consulta_catalago = $consulta_catalogo->fetchAll(PDO::FETCH_ASSOC);
        
         // Arrays para almacenar coincidencias y no coincidencias
        $coincidencias = array();
        $noCoincidencias = array();

        // Comparaci�n y clasificaci�n
        foreach ($result_consulta_catalago as $item1) {
            $encontrado = false;
            foreach ($result_carpetas as $item2) {
                if ($item1['DOCUMENTO'] === $item2['Nombre_Documento']) {
                    $coincidencias[] = array('array1' => $item1, 'array2' => $item2);
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $noCoincidencias[] = $item1;
            }
        }

            // Recorrer array de coincidencias
        for ($i = 0; $i < count($coincidencias); $i++) {
            $btnUpdate = '';
            $btnView = '';
            $btnDownload = '';
            $btnDelete = '';

            if ($coincidencias[$i]['array2']['Estatus'] == "") {
                $coincidencias[$i]['array2']['Estatus'] = '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle-fill"></i> Sin Archivo</span>';
                $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate(' . $coincidencias[$i]['array2']['ID_CATALOGO_DOCUMENTOS'] . ', \'' . $referencia_nexen . '\',\'' . $tipo_trafico . '\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';
            } else {
                
                $coincidencias[$i]['array2']['Estatus'] = '<span class="badge rounded-pill bg-success"><i class="bi bi-check-circle-fill"></i> Verificado</span>';
                $btnDownload = '<a class="btn btn-info btn-sm" style="margin:2px;" title="Descargar Archivo" href="'.$rutadir.'descargar_archivo.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen .'" target="_blank"><i class="bi bi-download"></i></a>';
                $btnView = '<a class="btn btn-primary  btn-sm " target="_blank" style="margin:2px;" title="Ver Archivo" href="'.$rutadir.'ver_Documento.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-eye"></i></a>';
                if($cargo == ''){$btnDelete = '<a class="btn btn-danger  btn-sm " style="margin:2px;" title="Eliminar Archivo" href="'.$rutadir.'Eliminar_Documento.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-trash"></i></a>';}else{$btnDelete = '';}
            }

            $coincidencias[$i]['array2']['OPTIONS'] = '<div class="text-center">' . $btnUpdate . ' ' . $btnDownload . '' . $btnView . '' . $btnDelete . '</div>';
        }

        // Recorrer array de no coincidencias
        for ($i = 0; $i < count($noCoincidencias); $i++) {
            $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate(' . $noCoincidencias[$i]['ID_CATALOGO_DOCUMENTOS'] . ', \'' . $referencia_nexen . '\',\'' . $tipo_trafico . '\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';

            $noCoincidencias[$i]['Estatus'] = '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle-fill"></i> Sin Archivo</span>';
            $noCoincidencias[$i]['OPTIONS'] = '<div class="text-center">' . $btnUpdate . '</div>';
        }
        if($cargo == ''){ echo json_encode(array_merge($coincidencias, $noCoincidencias), JSON_UNESCAPED_UNICODE); } else{ echo json_encode(array_merge($coincidencias), JSON_UNESCAPED_UNICODE); }
        die;
    }else{
        $sql_recuperacion = "SELECT * FROM [Nexen_Recuperacion].[dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION] WHERE Referencia_Nexen = :referencia ";
        $consulta_recuperacion = $conn_sb->prepare($sql_recuperacion);
        $consulta_recuperacion->bindParam(':referencia',$referencia_nexen);
        $consulta_recuperacion->execute();
        $result_consulta_recuperacion = $consulta_recuperacion->fetchAll(PDO::FETCH_ASSOC);
    
        $sql = "SELECT * FROM [Nexen].[dbo].[FK_DETALLE_CATALOGO_DOCUMENTOS_OPERERACION] WHERE Referencia_Nexen = :referencia ";
        $consulta = $conn_sb->prepare($sql);
        $consulta->bindParam(':referencia',$referencia_nexen);
        $consulta->execute();
        $result_consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);


        $documentos = array_merge($result_consulta_recuperacion, $result_consulta);
        $documentosUnicos = array();
        foreach ($documentos as $documento) {
            $claveUnica = $documento['Nombre_Documento'] . $documento['nombre'];
            if (!array_key_exists($claveUnica, $documentosUnicos)) {
                $documentosUnicos[$claveUnica] = $documento;
            }
        }
        $documentosUnicos = array_values($documentosUnicos);
    

       
        if(empty($documentosUnicos)){
            $query_catalogo = "SELECT * FROM  [Nexen].[dbo].[CATALOGO_DOCUMENTOS_OPERERACION] WHERE TIPO_OPE = '{$tipo_trafico}'";
			$consulta_catalogo = $conn_sb->prepare($query_catalogo);
			$consulta_catalogo -> execute();
			$result_catalogo = $consulta_catalogo -> fetchAll(PDO::FETCH_ASSOC);
			for ($i=0; $i < count($result_catalogo); $i++){
				
				$btnUpdate = '';
				$result_catalogo[$i]['Estatus'] = '<span class="badge badge-danger" style="color:red;">Sin Archivo</span>';
				$btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate('.$result_catalogo[$i]['ID_CATALOGO_DOCUMENTOS'].', \''.$referencia_nexen.'\',\''.$tipo_trafico.'\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';	
				$result_catalogo[$i]['OPTIONS'] = '<div class="text-center">'.$btnUpdate.'</div>';	
				
			} 
			echo json_encode($result_catalogo,JSON_UNESCAPED_UNICODE);
        }else{
        


        // print_r($documentosUnicos);
            
        // echo "resolrver";
        // die;
    
            $carpetaDestino = '../Documentos/'.$referencia_nexen.'/';
        
            if (!file_exists($carpetaDestino)) {
                if (mkdir($carpetaDestino, 0777, true)) {




                    foreach ($documentosUnicos as $documento) {
                        $nombre_archivo = $documento['Nombre_Documento'];
                        $tipo_Documento = $documento['Tipo_Documento'];
                        $Fechope = $documento['Fechope'];
                        $Hora = $documento['Horaope'];
                        $Usuario = $documento['Usuario'];
                        $Estatus = $documento['Estatus'];
                        $Refernecia_Nexen = $documento['Referencia_Nexen'];
                        $id_catalogo_Documento = $documento['id_catalogo_Documentos'];
                        $Nombre = $documento['nombre'];
                        $tipo = $documento['type_file'];
                        
                        if($Estatus != 0){
                            $rutaCompleta = $carpetaDestino . '/' . $Nombre;
                            $documentoBase64 = $documento['Documento']; // Aquí asumimos que el documento está en base64
                            $documentoBinario = base64_decode($documentoBase64);
                            file_put_contents($rutaCompleta, $documentoBinario);
                            
                            $stmt = $conn_sb->prepare("INSERT INTO [Nexen].[dbo].[FK_DOCUMENTOS_CARPETA] (Nombre_Documento,Documento_ruta,Tipo_Documento,Fechope,Horaope,Usuario,Estatus,Referencia_Nexen,id_catalogo_documentos,Nombre,Type_File,Accion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                            $stmt->bindParam(1, $nombre_archivo);
                            $stmt->bindParam(2, $carpetaDestino);
                            $stmt->bindParam(3, $tipo_Documento);
                            $stmt->bindParam(4, $Fechope);
                            $stmt->bindParam(5, $Hora);
                            $stmt->bindParam(6,$Usuario);
                            $stmt->bindParam(7, $Estatus);
                            $stmt->bindParam(8, $Refernecia_Nexen);
                            $stmt->bindParam(9, $id_catalogo_Documento);
                            $stmt->bindParam(10, $Nombre);
                            $stmt->bindParam(11, $tipo);
                            $stmt->bindParam(12, $Accion);
                            $stmt->execute();  
                        }
                    }
                    
                        $query_carpetas = "SELECT Nombre_Documento,Documento_ruta,Tipo_Documento,Referencia_Nexen,Nombre,id_catalogo_documentos,Estatus,id FROM [Nexen].[dbo].[FK_DOCUMENTOS_CARPETA] WHERE Referencia_Nexen = :referencia AND Estatus != 0";
                        $carpetas = $conn_sb->prepare($query_carpetas);
                        $carpetas->bindParam(':referencia',$referencia_nexen); 
                        $carpetas->execute();
                        $result_carpetas = $carpetas->fetchAll(PDO::FETCH_ASSOC);

                        if(!empty($result_carpetas)){
                            $catalogo = "SELECT  TIPO_OPE,DOCUMENTO,ID_CATALOGO_DOCUMENTOS FROM [Nexen].[dbo].[CATALOGO_DOCUMENTOS_OPERERACION] WHERE TIPO_OPE = :tipo_trafico";
                            $consulta_catalogo = $conn_sb->prepare($catalogo);
                            $consulta_catalogo->bindParam(':tipo_trafico',$tipo_trafico);
                            $consulta_catalogo->execute();
                            $result_consulta_catalago = $consulta_catalogo->fetchAll(PDO::FETCH_ASSOC);

                                // Arrays para almacenar coincidencias y no coincidencias
                            $coincidencias = array();
                            $noCoincidencias = array();

                            // Comparación y clasificación
                            foreach ($result_consulta_catalago as $item1) {
                                $encontrado = false;
                                foreach ($result_carpetas as $item2) {
                                    if ($item1['DOCUMENTO'] === $item2['Nombre_Documento']) {
                                        $coincidencias[] = array('array1' => $item1, 'array2' => $item2);
                                        $encontrado = true;
                                        break;
                                    }
                                }
                                if($cargo == ''){ if (!$encontrado) {
                                    $noCoincidencias[] = $item1;
                                } }
                            }

                                // Recorrer array de coincidencias
                            for ($i = 0; $i < count($coincidencias); $i++) {
                                $btnUpdate = '';
                                $btnView = '';
                                $btnDownload = '';
                                $btnDelete = '';

                                if ($coincidencias[$i]['array2']['Estatus'] == "") {
                                    $coincidencias[$i]['array2']['Estatus'] = '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle-fill"></i> Sin Archivo</span>';
                                    $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate(' . $coincidencias[$i]['array2']['ID_CATALOGO_DOCUMENTOS'] . ', \'' . $referencia_nexen . '\',\'' . $tipo_trafico . '\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';
                                } else {
                                    $coincidencias[$i]['array2']['Estatus'] = '<span class="badge rounded-pill bg-success"><i class="bi bi-check-circle-fill"></i> Verificado</span>';
                                    $btnDownload = '<a class="btn btn-info btn-sm" style="margin:2px;" title="Descargar Archivo" href="'.$rutadir.'descargar_archivo.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-download"></i></a>';
                                    $btnView = '<a class="btn btn-primary  btn-sm " target="_blank" style="margin:2px;" title="Ver Archivo" href="'.$rutadir.'ver_Documento.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-eye"></i></a>';
                                    $btnDelete = '<a class="btn btn-danger  btn-sm " style="margin:2px;" title="Eliminar Archivo" href="'.$rutadir.'Eliminar_Documento.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-trash"></i></a>';
                                }

                                $coincidencias[$i]['array2']['OPTIONS'] = '<div class="text-center">' . $btnUpdate . ' ' . $btnDownload . '' . $btnView . '' . $btnDelete . '</div>';
                            }

                            // Recorrer array de no coincidencias
                            for ($i = 0; $i < count($noCoincidencias); $i++) {
                                $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate(' . $noCoincidencias[$i]['ID_CATALOGO_DOCUMENTOS'] . ', \'' . $referencia_nexen . '\',\'' . $tipo_trafico . '\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';

                                $noCoincidencias[$i]['Estatus'] = '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle-fill"></i> Sin Archivo</span>';
                                $noCoincidencias[$i]['OPTIONS'] = '<div class="text-center">' . $btnUpdate . '</div>';
                            }
                            if($cargo == ''){
                            echo json_encode(array_merge($coincidencias, $noCoincidencias), JSON_UNESCAPED_UNICODE);
                            }else{
                                echo json_encode(array_merge($coincidencias), JSON_UNESCAPED_UNICODE);
                            }
                            
                            die;

                        }else{
                            $query_catalogo = "SELECT * FROM  [Nexen].[dbo].[CATALOGO_DOCUMENTOS_OPERERACION] WHERE TIPO_OPE = '{$tipo_trafico}'";
                            $consulta_catalogo = $conn_sb->prepare($query_catalogo);
                            $consulta_catalogo -> execute();
                            $result_catalogo = $consulta_catalogo -> fetchAll(PDO::FETCH_ASSOC);
                            for ($i=0; $i < count($result_catalogo); $i++){
                                
                                $btnUpdate = '';
                                $result_catalogo[$i]['Estatus'] = '<span class="badge badge-danger" style="color:red;">Sin Archivo</span>';
                                $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate('.$result_catalogo[$i]['ID_CATALOGO_DOCUMENTOS'].', \''.$referencia_nexen.'\',\''.$tipo_trafico.'\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';	
                                $result_catalogo[$i]['OPTIONS'] = '<div class="text-center">'.$btnUpdate.'</div>';	
                                
                            } 
                            echo json_encode($result_catalogo,JSON_UNESCAPED_UNICODE);
                        }

                } else {
                    $mensaje = array('status' => false,'msg'=>'PROBLEMAS AL EXTRAER LOS DOCUMENTOS');
                    echo json_encode($mensaje,JSON_UNESCAPED_UNICODE);
                    die;
                }
            } else {
                foreach ($documentosUnicos as $documento) {
                    $nombre_archivo = $documento['Nombre_Documento'];
                    $tipo_Documento = $documento['Tipo_Documento'];
                    $Fechope = $documento['Fechope'];
                    $Hora = $documento['Horaope'];
                    $Usuario = $documento['Usuario'];
                    $Estatus = $documento['Estatus'];
                    $Refernecia_Nexen = $documento['Referencia_Nexen'];
                    $id_catalogo_Documento = $documento['id_catalogo_Documentos'];
                    $Nombre = $documento['nombre'];
                    $tipo = $documento['type_file'];
        
                    if($Estatus != 0){
                        $rutaCompleta = $carpetaDestino . '/' . $Nombre;
            
                        $documentoBase64 = $documento['Documento']; // Aquí asumimos que el documento está en base64
                        $documentoBinario = base64_decode($documentoBase64);
                        file_put_contents($rutaCompleta, $documentoBinario);
                        $stmt = $conn_sb->prepare("INSERT INTO [Nexen].[dbo].[FK_DOCUMENTOS_CARPETA] (Nombre_Documento,Documento_ruta,Tipo_Documento,Fechope,Horaope,Usuario,Estatus,Referencia_Nexen,id_catalogo_documentos,Nombre,Type_File,Accion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                        $stmt->bindParam(1, $nombre_archivo);
                        $stmt->bindParam(2, $carpetaDestino);
                        $stmt->bindParam(3, $tipo_Documento);
                        $stmt->bindParam(4, $Fechope);
                        $stmt->bindParam(5, $Hora);
                        $stmt->bindParam(6,$Usuario);
                        $stmt->bindParam(7, $Estatus);
                        $stmt->bindParam(8, $Refernecia_Nexen);
                        $stmt->bindParam(9, $id_catalogo_Documento);
                        $stmt->bindParam(10, $Nombre);
                        $stmt->bindParam(11, $tipo);
                        $stmt->bindParam(12, $Accion);
                        $stmt->execute();
                    }
                  
                }
                $query_carpetas = "SELECT Nombre_Documento,Documento_ruta,Tipo_Documento,Referencia_Nexen,Nombre,id_catalogo_documentos,Estatus,id FROM [Nexen].[dbo].[FK_DOCUMENTOS_CARPETA] WHERE Referencia_Nexen = :referencia AND Estatus != 0";
                $carpetas = $conn_sb->prepare($query_carpetas);
                $carpetas->bindParam(':referencia',$referencia_nexen); 
                $carpetas->execute();
                $result_carpetas = $carpetas->fetchAll(PDO::FETCH_ASSOC);

                if(!empty($result_carpetas)){
                    $catalogo = "SELECT  TIPO_OPE,DOCUMENTO,ID_CATALOGO_DOCUMENTOS FROM [Nexen].[dbo].[CATALOGO_DOCUMENTOS_OPERERACION] WHERE TIPO_OPE = :tipo_trafico";
                    $consulta_catalogo = $conn_sb->prepare($catalogo);
                    $consulta_catalogo->bindParam(':tipo_trafico',$tipo_trafico);
                    $consulta_catalogo->execute();
                    $result_consulta_catalago = $consulta_catalogo->fetchAll(PDO::FETCH_ASSOC);

                        // Arrays para almacenar coincidencias y no coincidencias
                    $coincidencias = array();
                    $noCoincidencias = array();

                    // Comparación y clasificación
                    foreach ($result_consulta_catalago as $item1) {
                        $encontrado = false;
                        foreach ($result_carpetas as $item2) {
                            if ($item1['DOCUMENTO'] === $item2['Nombre_Documento']) {
                                $coincidencias[] = array('array1' => $item1, 'array2' => $item2);
                                $encontrado = true;
                                break;
                            }
                        }
                        if (!$encontrado) {
                            $noCoincidencias[] = $item1;
                        }
                    }

                        // Recorrer array de coincidencias
                    for ($i = 0; $i < count($coincidencias); $i++) {
                        $btnUpdate = '';
                        $btnView = '';
                        $btnDownload = '';
                        $btnDelete = '';

                        if ($coincidencias[$i]['array2']['Estatus'] == "") {
                            $coincidencias[$i]['array2']['Estatus'] = '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle-fill"></i> Sin Archivo</span>';
                            $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate(' . $coincidencias[$i]['array2']['ID_CATALOGO_DOCUMENTOS'] . ', \'' . $referencia_nexen . '\',\'' . $tipo_trafico . '\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';
                        } else {
                            $coincidencias[$i]['array2']['Estatus'] = '<span class="badge rounded-pill bg-success"><i class="bi bi-check-circle-fill"></i> Verificado</span>';
                            $btnDownload = '<a class="btn btn-info btn-sm" style="margin:2px;" title="Descargar Archivo" href="'.$rutadir.'descargar_archivo.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-download"></i></a>';
                            $btnView = '<a class="btn btn-primary  btn-sm " target="_blank" style="margin:2px;" title="Ver Archivo" href="'.$rutadir.'ver_Documento.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-eye"></i></a>';
                            if($cargo == ''){ $btnDelete = '<a class="btn btn-danger  btn-sm " style="margin:2px;" title="Eliminar Archivo" href="'.$rutadir.'Eliminar_Documento.php?id=' . $coincidencias[$i]['array2']['id'] . '&referencia=' . $referencia_nexen . '"><i class="bi bi-trash"></i></a>'; }else{ $btnDelete = '';}
                        }

                        $coincidencias[$i]['array2']['OPTIONS'] = '<div class="text-center">' . $btnUpdate . ' ' . $btnDownload . '' . $btnView . '' . $btnDelete . '</div>';
                    }

                    // Recorrer array de no coincidencias
                    for ($i = 0; $i < count($noCoincidencias); $i++) {
                        $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate(' . $noCoincidencias[$i]['ID_CATALOGO_DOCUMENTOS'] . ', \'' . $referencia_nexen . '\',\'' . $tipo_trafico . '\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';

                        $noCoincidencias[$i]['Estatus'] = '<span class="badge rounded-pill bg-danger"><i class="bi bi-x-circle-fill"></i> Sin Archivo</span>';
                        $noCoincidencias[$i]['OPTIONS'] = '<div class="text-center">' . $btnUpdate . '</div>';
                    }
                    echo json_encode(array_merge($coincidencias, $noCoincidencias), JSON_UNESCAPED_UNICODE);
                    die;

                }
                else{
                    $query_catalogo = "SELECT * FROM  [Nexen].[dbo].[CATALOGO_DOCUMENTOS_OPERERACION] WHERE TIPO_OPE = '{$tipo_trafico}'";
                    $consulta_catalogo = $conn_sb->prepare($query_catalogo);
                    $consulta_catalogo -> execute();
                    $result_catalogo = $consulta_catalogo -> fetchAll(PDO::FETCH_ASSOC);
                    for ($i=0; $i < count($result_catalogo); $i++){
                        
                        $btnUpdate = '';
                        $result_catalogo[$i]['Estatus'] = '<span class="badge badge-danger" style="color:red;">Sin Archivo</span>';
                        $btnUpdate = '<button class="btn btn-success btn-sm " onClick="fntUpdate('.$result_catalogo[$i]['ID_CATALOGO_DOCUMENTOS'].', \''.$referencia_nexen.'\',\''.$tipo_trafico.'\')"  title="Subir Archivo"><i class="bi bi-upload"></i></button>';	
                        $result_catalogo[$i]['OPTIONS'] = '<div class="text-center">'.$btnUpdate.'</div>';	
                        
                    } 
                    echo json_encode($result_catalogo,JSON_UNESCAPED_UNICODE);
                }
            }
        
        }
    }
}


?>