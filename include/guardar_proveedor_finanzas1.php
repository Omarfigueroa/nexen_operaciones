<?php
require '../conexion/bd.php';
if(!isset($_SESSION['usuario_nexen'])) { 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }else{
        require '../utils/catalogos.php'; 
        require '../utils/utils.php'; 

        $user_nexen = $_SESSION['usuario_nexen'];

        date_default_timezone_set('America/Mexico_City');

        $fechope = date('Y-m-d');
        $horaope = date('H:i:s');

       

        
         $opcion = isset($_POST['opcion']) ? $_POST['opcion'] : '';

         switch ($opcion) {
            case 'guardarOperador':
                // Declarando variables
                $razon_social = $_POST['u_razon_social'];
                $rfc = $_POST['u_rfc'];
                $dom_fiscal = $_POST['u_dom_fiscal'];
                $rep_legal = $_POST['u_rep_legal'];
                

                $query = "SELECT * FROM EMPRESAS WHERE Razon_Social = :razon_social OR RFC = :rfc";
                $consulta = $conn_bd->prepare($query);
                $consulta->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
                $consulta->bindParam(':rfc', $rfc, PDO::PARAM_STR);
                $consulta->execute();

                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    // Ya existe una empresa con la misma Razon Social o RFC
                    // Puedes mostrar un mensaje de error o realizar otra acción
                    echo json_encode(array("success" => false, "message" => "Ya existe una empresa con la misma Razon Social o RFC"));
                    exit;
                } else {
                    // No existe una empresa con la misma Razon Social o RFC, puedes continuar con la inserción
                    $query_insert = "INSERT INTO EMPRESAS (Razon_Social, RFC, Domicilio_Fiscal, Representante_Legal, ESTATUS) 
                    VALUES (:razon_social, :rfc, :dom_fiscal, :rep_legal, 'A')";
                    $insercion = $conn_bd->prepare($query_insert);
                    $insercion->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
                    $insercion->bindParam(':rfc', $rfc, PDO::PARAM_STR);
                    $insercion->bindParam(':dom_fiscal', $dom_fiscal, PDO::PARAM_STR);
                    $insercion->bindParam(':rep_legal', $rep_legal, PDO::PARAM_STR);

                    if ($insercion->execute()) {
                    echo json_encode(array("success" => true, "message" => "Empresa guardada exitosamente"));
                    } else {
                    echo json_encode(array("success" => false, "message" => "Error al insertar la empresa"));
                    }
                }
                break;

            case 'guardarTipoCuenta':
                    // Declarando variables
                    $alias = $_POST['c_alias'];
                    $razon_social = $_POST['c_razon_social'];
                    $tipo_persona = $_POST['c_tipo_persona'];
                    $tipo_servicio = $_POST['c_tipo_servicio'];
                    $curp = $_POST['c_curp'];
                    $rfc = $_POST['c_rfc'];
                    $ref_proveedor = $_POST['c_ref_proveedor'];
                    
    
                    $query = "SELECT * FROM Proveedores_Cuentas WHERE Alias = :alias OR Razon_Social = :razon_social OR RFC = :rfc";
                    $consulta = $conn_bd->prepare($query);
                    $consulta->bindParam(':alias', $alias, PDO::PARAM_STR);
                    $consulta->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
                    $consulta->bindParam(':rfc', $rfc, PDO::PARAM_STR);
                    $consulta->execute();
    
                    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    
                    if ($resultado) {
                        // Ya existe una empresa con la misma Razon Social o RFC
                        // Puedes mostrar un mensaje de error o realizar otra acción
                        echo json_encode(array("success" => false, "message" => "Ya existe una empresa con la misma Alias, Razon Social o RFC"));
                        exit;
                    } else {
                        
                        // No existe una empresa con la misma Razon Social o RFC, puedes continuar con la inserción
                        $query_insert = "INSERT INTO Proveedores_Cuentas (Alias, Razon_Social, Tipo_Persona, Tipo_Servicio, CURP, RFC, Fechope, horaope, Usuario,  ESTATUS, Ref_Proveedor) 
                        VALUES (:alias, :razon_social, :tipo_persona, :tipo_servicio, :curp, :rfc, :fechope, :horaope, :usuario, 'A', :ref_proveedor)";
                        $insercion = $conn_bd->prepare($query_insert);
                        $insercion->bindParam(':alias', $razon_social, PDO::PARAM_STR);
                        $insercion->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
                        $insercion->bindParam(':tipo_persona', $tipo_persona, PDO::PARAM_STR);
                        $insercion->bindParam(':tipo_servicio', $tipo_servicio, PDO::PARAM_STR);
                        $insercion->bindParam(':curp', $curp, PDO::PARAM_STR);
                        $insercion->bindParam(':rfc', $rfc, PDO::PARAM_STR);
                        $insercion->bindParam(':fechope', $fechope, PDO::PARAM_STR);
                        $insercion->bindParam(':horaope', $horaope, PDO::PARAM_STR);
                        $insercion->bindParam(':usuario', $user_nexen, PDO::PARAM_STR);
                        $insercion->bindParam(':ref_proveedor', $ref_proveedor, PDO::PARAM_STR);
    
                        if ($insercion->execute()) {
                        echo json_encode(array("success" => true, "message" => "Cuenta guardada exitosamente"));
                        } else {
                        echo json_encode(array("success" => false, "message" => "Error al insertar la empresa"));
                        }
                        
                    }
                    break;
            case 'guardarCuenta':
                    // Declarando variables
                    $fk_idRazon = $_POST['fk_idRazon'];
                    $fk_razon_social = empty($_POST['fk_razon_social']) ? "N/A" : $_POST['fk_razon_social'];
                    $fk_referencia_proveedor = empty($_POST['fk_referencia_proveedor']) ? "N/A" : $_POST['fk_referencia_proveedor'];
                    $fk_curp = empty($_POST['fk_curp']) ? "N/A" : $_POST['fk_curp'];
                    $fk_tipo_servicio = empty($_POST['fk_tipo_servicio']) ? "N/A" : $_POST['fk_tipo_servicio'];
                    $fk_tipo_persona = empty($_POST['fk_tipo_persona']) ? "N/A" : $_POST['fk_tipo_persona'];
                    $fk_rfc = empty($_POST['fk_rfc']) ? "N/A" : $_POST['fk_rfc'];
                    $fk_tipo_cuenta = empty($_POST['fk_tipo_cuenta']) ? "N/A" : $_POST['fk_tipo_cuenta'];
                    $fk_idBanco = empty($_POST['fk_banco']) ? "N/A" : $_POST['fk_banco'];
                    $fk_cuenta = empty($_POST['fk_cuenta']) ? "N/A" : $_POST['fk_cuenta'];
                    $fk_abba = empty($_POST['fk_abba']) ? "N/A" : $_POST['fk_abba'];
                    $fk_clabe = empty($_POST['fk_clabe']) ? "N/A" : $_POST['fk_clabe'];
                    $fk_banco_inter = empty($_POST['fk_banco_inter']) ? "N/A" : $_POST['fk_banco_inter'];
                    $fk_domicilio = empty($_POST['fk_domicilio']) ? "N/A" : $_POST['fk_domicilio'];
                    $operadores = $_POST['operadores'];

                    $transactionSuccess = true;
                    $erroresOperadores = array();
                    $operadoresExitosos = array();


                    try {
                        // Iniciar transacción
                        $conn_bd->beginTransaction();

                        // Comprobación para evitar duplicados en la tabla Razon_Bancos
                        if ($fk_tipo_cuenta === 'NACIONAL') {
                            if ($fk_cuenta !== 'N/A' || $fk_clabe !== 'N/A') {
                                $query_check = "SELECT COUNT(*) FROM Razon_Bancos WHERE (Cuenta = :fk_cuenta OR Clabe = :fk_clabe) AND Estatus = 'A'";
                                $check_existence = $conn_bd->prepare($query_check);
                                $check_existence->bindParam(':fk_cuenta', $fk_cuenta, PDO::PARAM_STR);
                                $check_existence->bindParam(':fk_clabe', $fk_clabe, PDO::PARAM_STR);
                                $check_existence->execute();

                                $existingCount = $check_existence->fetchColumn();

                                if ($existingCount > 0) {
                                    echo json_encode(array("success" => false, "message" => "Ya existe una cuenta con estos datos: Cuenta/Clabe"));
                                    exit;
                                }
                            }
                        } elseif ($fk_tipo_cuenta === 'INTERNACIONAL') {
                            if ($fk_abba !== 'N/A') {
                                $query_check = "SELECT COUNT(*) FROM Razon_Bancos WHERE SWT_ABBA = :fk_abba AND Estatus = 'A'";
                                $check_existence = $conn_bd->prepare($query_check);
                                $check_existence->bindParam(':fk_abba', $fk_abba, PDO::PARAM_STR);
                                $check_existence->execute();

                                $existingCount = $check_existence->fetchColumn();

                                if ($existingCount > 0) {
                                    echo json_encode(array("success" => false, "message" => "Ya existe una cuenta con este dato: SWT_ABBA"));
                                    exit;
                                }
                            }
                        }

                        //INSERT A RAZON BANCO
                        $query_banco = "INSERT INTO Razon_Bancos (Id_banco, id_razon_social, Tipo_Cuenta, Cuenta, Clabe, SWT_ABBA, Banco_Intermediario, Domicilio_Completo, Fechope, horaope, Usuario, Estatus) 
                        VALUES (:fk_idBanco, :fk_idRazon, :fk_tipo_cuenta, :fk_cuenta, :fk_clabe, :fk_abba, :fk_banco_inter, :fk_domicilio, :fechope, :horaope, :Usuario, 'A')";
                        $insercion_banco = $conn_bd->prepare($query_banco);
                        $insercion_banco->bindParam(':fk_idBanco', $fk_idBanco, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fk_idRazon', $fk_idRazon, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fk_tipo_cuenta', $fk_tipo_cuenta, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fk_cuenta', $fk_cuenta, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fk_clabe', $fk_clabe, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fk_abba', $fk_abba, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fk_banco_inter', $fk_banco_inter, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fk_domicilio', $fk_domicilio, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':fechope', $fechope, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':horaope', $horaope, PDO::PARAM_STR);
                        $insercion_banco->bindParam(':Usuario', $user_nexen, PDO::PARAM_STR);

                        $check_query_operador_razon = "SELECT COUNT(*) FROM Fk_Operador_Razon WHERE Id_Operador = :operador AND Id_Razon_Social = :fk_idRazon";
                        $check_existence_operador_razon = $conn_bd->prepare($check_query_operador_razon);


                        foreach ($operadores as $operador) {
                            $successMessage = "";
                            //INSERT A FK OPERADOR RAZON
                            $query_insert = "INSERT INTO Fk_Operador_Razon (Id_Operador, Id_Razon_Social, Fechope, horaope, Usuario, Estatus) 
                            VALUES ( :operador, :fk_idRazon, :fechope, :horaope, :usuario, 'A')";
                            $insercion = $conn_bd->prepare($query_insert);
                            $insercion->bindParam(':fk_idRazon', $fk_idRazon, PDO::PARAM_STR);
                            $insercion->bindParam(':operador', $operador, PDO::PARAM_STR);
                            $insercion->bindParam(':fechope', $fechope, PDO::PARAM_STR);
                            $insercion->bindParam(':horaope', $horaope, PDO::PARAM_STR);
                            $insercion->bindParam(':usuario', $user_nexen, PDO::PARAM_STR);

                            // Ejecutar la comprobación
                            $check_existence_operador_razon->bindParam(':operador', $operador, PDO::PARAM_STR);
                            $check_existence_operador_razon->bindParam(':fk_idRazon', $fk_idRazon, PDO::PARAM_STR);
                            $check_existence_operador_razon->execute();

                            $existingCountOperadorRazon = $check_existence_operador_razon->fetchColumn();

                            if ($existingCountOperadorRazon > 0) {
                                echo json_encode(array("success" => false, "message" => "Ya existe una combinación Id_Operador / Id_Razon_Social"));
                                exit;
                            }


                            if (!$insercion->execute()) {
                                $transactionSuccess = false;
                                $erroresOperadores[] = $operador;
                            } else {
                                $operadoresExitosos[] = $operador;
                            }
                        }

                        // Ejecutar la inserción en Razon_Bancos una sola vez
                        if (!$insercion_banco->execute()) {
                            $transactionSuccess = false;
                        }
                            
                        if ($transactionSuccess) {
                            // Si todas las inserciones fueron exitosas, confirmar la transacción
                            $conn_bd->commit();
                            echo json_encode(array("success" => true, "message" => "Operadores insertados exitosamente."));
                        } else {
                            // Si hubo errores, deshacer la transacción
                            $conn_bd->rollBack();
                            echo json_encode(array("success" => false, "message" => "Error al insertar operadores: " . implode(', ', $erroresOperadores)));
                        }
                    } catch (PDOException $e) {
                        // Si ocurre una excepción, deshacer la transacción y mostrar mensaje de error
                        $conn_bd->rollBack();
                        echo json_encode(array("success" => false, "message" => "Error en la transacción: " . $e->getMessage()));
                    }
                                                                        
                    break;
            case 'guardarBanco':
                    // Declarando variables
                    $operacion = $_POST['Operacion'];

                    if ($operacion == 'Insertar') {
                        $banco = $_POST['e_banco'];
                    
                        // Consulta para verificar si ya existe un banco con el mismo nombre
                        $query_check_duplicate = "SELECT COUNT(*) FROM Catalogo_Bancos WHERE NOMBRE_BANCO = :banco";
                        $check_duplicate = $conn_bd->prepare($query_check_duplicate);
                        $check_duplicate->bindParam(':banco', $banco, PDO::PARAM_STR);
                        $check_duplicate->execute();
                    
                        $duplicateCount = $check_duplicate->fetchColumn();
                    
                        if ($duplicateCount > 0) {
                            echo json_encode(array("success" => false, "message" => "Ya existe un banco con el mismo nombre"));
                        } else {
                            // Si no existe, procede con la inserción
                            $query_insert = "INSERT INTO Catalogo_Bancos (NOMBRE_BANCO, ESTATUS) 
                                            VALUES (:banco, 'A')";
                            $insercion = $conn_bd->prepare($query_insert);
                            $insercion->bindParam(':banco', $banco, PDO::PARAM_STR);
                    
                            if ($insercion->execute()) {
                                echo json_encode(array("success" => true, "message" => "Banco guardado exitosamente"));
                            } else {
                                echo json_encode(array("success" => false, "message" => "Error al insertar el banco"));
                            }
                        }
                    } else if ($operacion == 'Update') {
                        $idBanco = $_POST['idBanco'];
                        $nuevoBanco = $_POST['e_banco'];

                        $query_update = "UPDATE Catalogo_Bancos SET NOMBRE_BANCO = :nuevoBanco WHERE ID_BANCO = :idBanco";
                        $actualizacion = $conn_bd->prepare($query_update);
                        $actualizacion->bindParam(':nuevoBanco', $nuevoBanco, PDO::PARAM_STR);
                        $actualizacion->bindParam(':idBanco', $idBanco, PDO::PARAM_INT);
                        
                        if ($actualizacion->execute()) {
                            echo json_encode(array("success" => true, "message" => "Banco actualizado exitosamente"));
                        } else {
                            echo json_encode(array("success" => false, "message" => "Error al actualizar el banco"));
                        }
                    }
                    

                break;
                case 'actualizarCuenta':
                    $fk_idRazon = $_POST['fk_idRazon'];
                    $operadores = $_POST['operadores'];

                    $transactionSuccess = true;
                    $erroresOperadores = array();
                    $operadoresExitosos = array();

                    try {
                        // Iniciar transacción
                        $conn_bd->beginTransaction();

                        foreach ($operadores as $operador) {
                            // INSERT A FK OPERADOR RAZON
                            $query_insert = "INSERT INTO Fk_Operador_Razon (Id_Operador, Id_Razon_Social, Fechope, horaope, Usuario, Estatus) 
                            VALUES ( :operador, :fk_idRazon, :fechope, :horaope, :usuario, 'A')";
                            $insercion = $conn_bd->prepare($query_insert);
                            $insercion->bindParam(':fk_idRazon', $fk_idRazon, PDO::PARAM_STR);
                            $insercion->bindParam(':operador', $operador, PDO::PARAM_STR);
                            $insercion->bindParam(':fechope', $fechope, PDO::PARAM_STR);
                            $insercion->bindParam(':horaope', $horaope, PDO::PARAM_STR);
                            $insercion->bindParam(':usuario', $user_nexen, PDO::PARAM_STR);

                            if (!$insercion->execute()) {
                                $transactionSuccess = false;
                                $erroresOperadores[] = $operador;
                            } else {
                                $operadoresExitosos[] = $operador;
                            }
                        }

                        if ($transactionSuccess) {
                            // Si todas las inserciones fueron exitosas, confirmar la transacción
                            $conn_bd->commit();
                            echo json_encode(array("success" => true, "message" => "Operadores insertados exitosamente."));
                        } else {
                            // Si hubo errores, deshacer la transacción
                            $conn_bd->rollBack();
                            echo json_encode(array("success" => false, "message" => "Error al insertar operadores: " . implode(', ', $erroresOperadores)));
                        }
                    } catch (PDOException $e) {
                        // Si ocurre una excepción, deshacer la transacción y mostrar mensaje de error
                        $conn_bd->rollBack();
                        echo json_encode(array("success" => false, "message" => "Error en la transacción: " . $e->getMessage()));
                    }
                break;
                case 'guardarBancoACuenta':
                    $bc_idBanco = $_POST['bc_idBanco'];
                    $bc_idRazon = $_POST['bc_idRazon'];
                    $bc_tipo_cuenta = $_POST['bc_tipo_cuenta'];
                    $bc_banco = $_POST['bc_banco'];
                    $bc_cuenta = $_POST['bc_cuenta'];
                    $bc_abba = $_POST['bc_abba'];
                    $bc_clabe = $_POST['bc_clabe'];
                    $bc_banco_inter = $_POST['bc_banco_inter'];
                    $bc_domicilio = $_POST['bc_domicilio'];

                    // Comprobación para evitar duplicados
                    $existingCount = 0;

                    if ($bc_tipo_cuenta === 'INTERNACIONAL') {
                        // Verificar si ya existe un registro similar con abba y banco intermediario
                        $query_check = "SELECT COUNT(*) FROM Razon_Bancos WHERE (SWT_ABBA = :bc_abba AND Banco_Intermediario = :bc_banco_inter) AND (Id_Banco != :bc_idBanco OR id_razon_social != :bc_idRazon) AND Estatus = 'A'";
                        $check_existence = $conn_bd->prepare($query_check);
                        $check_existence->bindParam(':bc_abba', $bc_abba, PDO::PARAM_STR);
                        $check_existence->bindParam(':bc_banco_inter', $bc_banco_inter, PDO::PARAM_STR);
                        $check_existence->bindParam(':bc_idBanco', $bc_idBanco, PDO::PARAM_STR);
                        $check_existence->bindParam(':bc_idRazon', $bc_idRazon, PDO::PARAM_STR);
                        $check_existence->execute();
                    
                        $existingCount = $check_existence->fetchColumn();
                    } elseif ($bc_tipo_cuenta === 'NACIONAL') {
                        // Verificar si ya existe un registro similar con cuenta y clabe
                        $query_check = "SELECT COUNT(*) FROM Razon_Bancos WHERE ((Cuenta = :bc_cuenta OR Clabe = :bc_clabe) AND (Id_Banco != :bc_idBanco OR id_razon_social != :bc_idRazon)) AND Estatus = 'A'";
                        $check_existence = $conn_bd->prepare($query_check);
                        $check_existence->bindParam(':bc_cuenta', $bc_cuenta, PDO::PARAM_STR);
                        $check_existence->bindParam(':bc_clabe', $bc_clabe, PDO::PARAM_STR);
                        $check_existence->bindParam(':bc_idBanco', $bc_idBanco, PDO::PARAM_STR);
                        $check_existence->bindParam(':bc_idRazon', $bc_idRazon, PDO::PARAM_STR);
                        $check_existence->execute();
                    
                        $existingCount = $check_existence->fetchColumn();
                    }
                    
                    if ($existingCount > 0) {
                        echo json_encode(array("success" => false, "message" => "Ya existe un registro similar."));
                        exit;
                    }

                    // Insertar en la base de datos
                    $query_insert = "INSERT INTO Razon_Bancos (Id_banco, id_razon_social, Tipo_Cuenta, Cuenta, Clabe, SWT_ABBA, Banco_Intermediario, Domicilio_Completo, Fechope, horaope, Usuario, Estatus) 
                    VALUES (:bc_idBanco, :bc_idRazon, :bc_tipo_cuenta, :bc_cuenta, :bc_clabe, :bc_abba, :bc_banco_inter, :bc_domicilio, :fechope, :horaope, :Usuario, 'A')";

                    $insercion = $conn_bd->prepare($query_insert);
                    $insercion->bindParam(':bc_idBanco', $bc_idBanco, PDO::PARAM_STR);
                    $insercion->bindParam(':bc_idRazon', $bc_idRazon, PDO::PARAM_STR);
                    $insercion->bindParam(':bc_tipo_cuenta', $bc_tipo_cuenta, PDO::PARAM_STR);
                    $insercion->bindParam(':bc_cuenta', $bc_cuenta, PDO::PARAM_STR);
                    $insercion->bindParam(':bc_clabe', $bc_clabe, PDO::PARAM_STR);
                    $insercion->bindParam(':bc_abba', $bc_abba, PDO::PARAM_STR);
                    $insercion->bindParam(':bc_banco_inter', $bc_banco_inter, PDO::PARAM_STR);
                    $insercion->bindParam(':bc_domicilio', $bc_domicilio, PDO::PARAM_STR);
                    $insercion->bindParam(':fechope', $fechope, PDO::PARAM_STR);
                    $insercion->bindParam(':horaope', $horaope, PDO::PARAM_STR);
                    $insercion->bindParam(':Usuario', $user_nexen, PDO::PARAM_STR);

                    if ($insercion->execute()) {
                        echo json_encode(array("success" => true, "message" => "Registro insertado exitosamente."));
                    } else {
                        echo json_encode(array("success" => false, "message" => "Error al insertar el registro."));
                    }
                break;
 
             // Agrega otros casos para diferentes opciones si es necesario
 
             default:
                 $response = array();
                 $response['success'] = false;
                 $response['message'] = 'Opción no válida';
                 echo json_encode($response);
                 break;
         }
        
        
/*  VIEJO
// Obtener los valores enviados por POST
        $c_cuenta_destino = $_POST['c_cuenta_destino'];
        $c_razon_social = $_POST['c_razon_social'];
        $c_rfc = $_POST['c_rfc'];
        $c_cuenta = $_POST['c_cuenta'];
        $c_abba = $_POST['c_abba'];
        $c_banco = $_POST['c_banco'];
        $c_clabe = $_POST['c_clabe'];
        $c_banco_inter = $_POST['c_banco_inter'];
        $c_domicilio = $_POST['c_domicilio'];
        $c_tipo_cuenta = $_POST['c_tipo_cuenta'];
        $c_referencia_proveedor = $_POST['c_referencia_proveedor'];
        $c_curp = $_POST['c_curp'];
        $c_tipo_servicio = $_POST['c_tipo_servicio'];
        $c_tipo_persona = $_POST['c_tipo_persona'];
        $operadores = $_POST['operadores'];

        // Crear un array para almacenar las respuestas de cada operador
        $responses = array();
        // Comprobar si ya existe una cuenta con los mismos detalles para algún operador
        foreach ($operadores as $operador) {
            $query_select_cuenta = "SELECT * FROM [dbo].[Cuenta_Destino] WHERE [Operador]='$operador' AND [Cuenta]='$c_cuenta' AND ([Clabe]='$c_clabe' OR [SWT_ABBA]='$c_abba')";
            $existe_cuenta = $conn_bd->prepare($query_select_cuenta);

            if ($existe_cuenta->execute()) {
                $si_existe = $existe_cuenta->fetch(PDO::FETCH_ASSOC);
                if ($si_existe > 0) {
                    $responses[] = array("success" => false, "message" => "Ya existe una cuenta con los mismos detalles para el operador: $operador.");
                } else {
                    // Realizar el INSERT para este operador
                    $query_insert_provee_finanzas = "INSERT INTO [dbo].[Cuenta_Destino]
                    ([Cuenta_Destino], [Razon_social], [RFC], [Banco], [Cuenta], [Clabe], [SWT_ABBA], [Banco_Intermediario], [Domicilio_Completo], [fechope], [horaope], [Usuario], [Estatus], [Operador], [tipo_cuenta], [Ref_proveedor], [Tipo_servicio], [CURP], [Tipo_Persona])
                    VALUES
                    ('$c_cuenta_destino', '$c_razon_social', '$c_rfc', '$c_banco', '$c_cuenta', '$c_clabe', '$c_abba', '$c_banco_inter', '$c_domicilio', '$fechaOpe', '$hora', '$user_nexen', 'A', '$operador', '$c_tipo_cuenta', '$c_referencia_proveedor', '$c_tipo_servicio', '$c_curp', '$c_tipo_persona')";
                    
                    $insertar_provee_finanzas = $conn_bd->prepare($query_insert_provee_finanzas);

                    if ($insertar_provee_finanzas->execute()) {
                        $responses[] = array("success" => true, "message" => "Registros insertados exitosamente para el operador: $operador");
                    } else {
                        $responses[] = array("success" => false, "message" => "Error en el INSERT para el operador: $operador");
                    }
                }
            } else {
                $responses[] = array("success" => false, "message" => "Error en la consulta de la base de datos para el operador: $operador");
            }
        }
*/
/*
        // Imprimir el JSON con todas las respuestas
        header('Content-Type: application/json');
        echo json_encode($responses);
*/

        /* jari
        $c_operador = $_POST['c_operador'];
        $c_cuenta_destino = $_POST['c_cuenta_destino'];
        $c_razon_social = $_POST['c_razon_social']; 
        $c_rfc = $_POST['c_rfc']; 
        $c_cuenta = $_POST['c_cuenta']; 
        $c_abba = $_POST['c_abba'];
        $c_banco = $_POST['c_banco']; 
        $c_clabe = $_POST['c_clabe']; 
        $c_banco_inter = $_POST['c_banco_inter']; 
        $c_domicilio = $_POST['c_domicilio'];
        $c_tipo_cuenta = $_POST['c_tipo_cuenta'];
        $c_referencia_proveedor = $_POST['c_referencia_proveedor'];
        $c_curp = $_POST['c_curp'];
        $c_tipo_servicio = $_POST['c_tipo_servicio'];
        $c_tipo_persona = $_POST['c_tipo_persona'];

        $query_select_cuenta= "SELECT * FROM [dbo].[Cuenta_Destino] WHERE [Operador]='$c_operador' AND [Cuenta]='$c_cuenta' AND ([Clabe]='$c_clabe' OR [SWT_ABBA]='$c_abba')";
        $existe_cuenta= $conn_bd->prepare($query_select_cuenta);

        if($existe_cuenta->execute()){
            $si_existe = $existe_cuenta -> fetch(PDO::FETCH_ASSOC); 
                if($si_existe>0){
                    echo'<script type="text/javascript">
                    alert("YA EXISTE LA CUENTA, POR FAVOR ASEGURATE QUE SEAN LOS DATOS CORRECTOS");
                    </script>';

                }else{
                    $query_insert_provee_finanzas ="INSERT INTO [dbo].[Cuenta_Destino]
                    ([Cuenta_Destino]
                    ,[Razon_social]
                    ,[RFC]
                    ,[Banco]
                    ,[Cuenta]
                    ,[Clabe]
                    ,[SWT_ABBA]
                    ,[Banco_Intermediario]
                    ,[Domicilio_Completo]
                    ,[fechope]
                    ,[horaope]
                    ,[Usuario]
                    ,[Estatus]
                    ,[Operador]
                    ,[tipo_cuenta]
                    ,[Ref_proveedor]
                    ,[Tipo_servicio]
                    ,[CURP]
                    ,[Tipo_Persona])
                VALUES
                    ('$c_cuenta_destino'
                    ,'$c_razon_social'
                    ,'$c_rfc'
                    ,'$c_banco'
                    ,'$c_cuenta'
                    ,'$c_clabe'
                    ,'$c_abba'
                    ,'$c_banco_inter'
                    ,'$c_domicilio'
                    ,'$fechaOpe'
                    ,'$hora'
                    ,'$user_nexen'
                    ,'A'
                    ,'$c_operador'
                    ,'$c_tipo_cuenta'
                    ,'$c_referencia_proveedor'
                    ,'$c_tipo_servicio'
                    ,'$c_curp'
                    ,'$c_tipo_persona')";

                    $insertar_provee_finanzas= $conn_bd->prepare($query_insert_provee_finanzas);

                    if($insertar_provee_finanzas->execute()){
                        echo'<script type="text/javascript">
                        alert("SE INSERTO CORRECTAMENTE EL PROVEEDOR");
                        </script>';
                        echo '<meta http-equiv="REFRESH" content="0;url=../view/cargar_proveedor_finanzas.php">'; 
                    }else{
                        echo'<script type="text/javascript">
                        alert("NO SE PUDO INSERTAR EL PROVEEDOR, POR FAVOR INTENTATO NUEVAMENTE");
                        </script>';

                        echo'<script type="text/javascript">
                        window..back();
                        </script>';
                    }
                    
                }
        
            echo '<meta http-equiv="REFRESH" content="0;url=../view/cargar_proveedor_finanzas.php">'; 
            
        }else{
            echo'<script type="text/javascript">
            alert("OCURRIO UN ERROR AL REVISAR SI EXISTE LA CUENTA, POR FAVOR INTENTATO NUEVAMENTE");
            </script>';
        }
        */
    }

}//Si existe Usuario

?>




