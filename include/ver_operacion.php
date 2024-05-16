<?php
date_default_timezone_set('America/Mexico_City');

require_once($_SERVER['DOCUMENT_ROOT'] . '/nexen_operaciones/include/config.php');
require_once(INCLUDE_PATH . 'validar_sesiones.php');
require_once(UTILS_PATH . 'catalogos.php');
require_once(UTILS_PATH . 'utils.php');


if (isset($_GET['contenedor']) && !empty($_GET['contenedor']) || isset($_GET['referencia'])  && !empty($_GET['referencia']) || isset($_GET['num_operacion'])  && !empty($_GET['num_operacion'])) {

    if (isset($_GET['contenedor']) && !empty($_GET['contenedor'])) {
        $contenedor_selecionado = $_GET['contenedor'];
        $select_exis_contenedor = "SELECT * FROM [dbo].[Operacion_nexen]WHERE Contenedor_1 = '$contenedor_selecionado'";
        //print_r($select_exis_contenedor);
    } elseif (isset($_GET['referencia'])  && !empty($_GET['referencia'])) {
        $referencia_selecionada = $_GET['referencia'];
        $select_exis_contenedor = "SELECT * FROM [dbo].[Operacion_nexen]WHERE REFERENCIA_NEXEN = '$referencia_selecionada'";
        //print_r($select_exis_contenedor);
    } elseif (isset($_GET['num_operacion'])  && !empty($_GET['num_operacion'])) {
        $num_operacion = $_GET['num_operacion'];
        $select_exis_contenedor = "SELECT * FROM [dbo].[Operacion_nexen] WHERE NUM_OPERACION = '$num_operacion'";
    }

    //echo $select_exis_contenedor;

    $buscar_contenedor = $conn_bd->prepare($select_exis_contenedor);
    $buscar_contenedor->execute();
    $results_contgenedor = $buscar_contenedor->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results_contgenedor as $operacion) {
        $referencia_nexen =  $operacion['REFERENCIA_NEXEN'];
        $nombre_cliente = $operacion['Cliente'];
        $referecia_cliente = $operacion['Referencia_Cliente'];
        $estatus = $operacion['Estatus'];
        // $saldo = $operacion['Valor_Factura'];
        $impor_export = $operacion['Importador_Exportador'];
        $tipo_operacion = $operacion['Tipo_Operacion'];
        $clave_pedimento = $operacion['Clave_Pedimento'];
        $tipo_trafico = $operacion['tipo_trafico'];
        $denominacion_aduana = $operacion['DENOMINACION_ADUANA'];
        $cve_aduana = $operacion['Pto_LLegada'];
        // $master = $operacion['Guia_House'];
        $house = $operacion['GUIA_HOUSE1'];
        $contenedor1 = $operacion['Contenedor_1'];
        $contenedor2 = $operacion['Contenedor_2'];
        $numero_economico = $operacion['NUMERO_ECONOMICO'];
        $bultos = $operacion['BULTOS'];
        // $proveedor = $operacion['proveedor'];
        $valor_factura = $operacion['Valor_Factura'];
        $moneda = $operacion['Moneda'];
        $tipo_cambio = $operacion['tipo_cambio'];
        $peso_bruto = $operacion['peso_bruto'];
        $date_notificacion = $operacion['Fecha_Notificación'];
        $date_arribo = $operacion['Fecha_Arribo'];
        $date_fecha_pedimento = $operacion['Fecha_Pago_Anticipo'];
        $date_modulacion = $operacion['Fecha_Modulación'];
        $patente = $operacion['Patente'];
        $num_pedimento = $operacion['No_Pedimento'];
        $recibo_wms = $operacion['WMS'];
        $num_anexo_24 = $operacion['FACTURA_SALIDA_ANEXO24'];
        $date_fact_anexo24 = $operacion['Fecha_Factura24'];
        $desc_cove = $operacion['Descripcion_Cove'];
        $usuario_ope = $operacion['Usuario'];
        $hora = $operacion['HORA_OPE'];
        $bl = $operacion['BL'];
        $det_mercancia  = $operacion['DETALLE_MERCANCIA'];
    }

    //Logica para traer las fechas de retenidos
    if (isset($referencia_nexen) && !empty($referencia_nexen)) {
        $select_operacion_retenida = "SELECT *  FROM [dbo].[OPERACIONES_RETENIDAS] WHERE [Referencia_Nexen] = '$referencia_nexen'";
        $buscar_operacion_retenida = $conn_bd->prepare($select_operacion_retenida);
        $buscar_operacion_retenida->execute();
        $results_operacion_retenida = $buscar_operacion_retenida->fetchAll(PDO::FETCH_ASSOC);

        if (count($results_operacion_retenida) > 0) {
            foreach ($results_operacion_retenida as $operacion_retenida_onload) {
                //Valores de ver operacion, formulario principal
                $fecha_retenido = $operacion_retenida_onload['Fecha_Retenido'];
                $fecha_liberacion = $operacion_retenida_onload['Fecha_Liberacion'];

                //Datos asignados en modal operaciones retenidas
                $msa_estado = $operacion_retenida_onload['MSA'];
                $incidencia_estado = $operacion_retenida_onload['INCIDENCIA'];
                $observacion = $operacion_retenida_onload['OBSERVACION'];
            }
        } else {
            $fecha_retenido = '';
            $fecha_liberacion = '';
            $msa_estado = '';
            $incidencia_estado = '';
            $observacion = '';
        }
    }


?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ver Operación</title>
        <link href="../css/estilos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.css" />
        <style>
            .check {
                width: 25px;
                height: 25px;
            }

            .titulos {
                height: 37px;
                font: 20px;
            }
        </style>
    </head>

    <body>
        <?php include('../plantilla/menu.php'); ?>
        <div class="container mt-3" style="max-width: 99%;">
            <div class="row ">
                <div class="row my-1 contenedor-registro-operaciones">

                    <h1>Registro de Operaciones</h1>
                    <div class="col-10">
                        <div class="mh-100"><br>
                            <form action="../include/actualizar_operacion.php?id_referencia=<?php echo $referencia_nexen ?>" method="POST">
                                <div class="form-control ">
                                    <span class="title">CLIENTE</span>
                                    <div class="row my-1">
                                        <div class="col">
                                            <label for="" class="titulos"> Referencia Nexen</label>
                                            <input class="form-control" id="referencia_nexen" name="referencia_nexen" type="text" placeholder="Referencia Nexen" value="<?php echo isset($referencia_nexen) && !empty($referencia_nexen) ?  $referencia_nexen : "" ?>" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="titulos">Cliente</label>
                                            <div class="input-group">
                                                <select class="form-control" id="razon_social" name="razon_social" disabled required>
                                                    <?php
                                                    if ($nombre_cliente) {
                                                        echo '<option value="' . $nombre_cliente . '">' . $nombre_cliente . '</option>';
                                                    } else {
                                                        echo '<option value="">Selecciona una opción</option>';
                                                    }
                                                    foreach ($result_cat_razon as $valores) {
                                                        $selec = $valores["RAZON SOCIAL "] == $nombre_cliente ? 'selected' : '';
                                                        echo '<option value="' . $valores["RAZON SOCIAL "] . '" ' . $selec . '>' . $valores["RAZON SOCIAL "] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <button type="button" class="btn btn-warning" nombre_cliente="<?php echo $nombre_cliente; ?>" id="btnEditarCliente"><i class="bi bi-pencil-square"></i></button>
                                            </div>
                                            <input type="hidden" id="razon_social_o" name="razon_social_o" value="<?php echo isset($nombre_cliente) && !empty($nombre_cliente) ?  $nombre_cliente : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label class="titulos">Referencia Cliente</label>
                                            <input type="text" id="referencia_cliente" name="referencia_cliente" oninput="quitarComillas(this)" placeholder="Introduce Referencia Cliente" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) OR (event.charCode >= 65 && event.charCode <= 90)" min="1" value="<?php echo isset($referecia_cliente) && !empty($referecia_cliente) ?  $referecia_cliente : "" ?>">
                                            <input type="hidden" id="referencia_cliente_o" name="referencia_cliente_o" value="<?php echo isset($referecia_cliente) && !empty($referecia_cliente) ?  $referecia_cliente : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label for="btn-modal1" class="titulos"> Estatus </label>
                                            <select class="form-control" id="estatus" name="estatus" required>
                                                <?php
                                                // if($nombre_cliente){
                                                //     echo '<option value="'.$estatus.'">'.$estatus.'</option>';
                                                // }else{
                                                echo '<option value="">Selecciona una opción</option>';
                                                // }
                                                foreach ($result_cat_estatus as $valores) :

                                                    $sele = $valores["Descripcion"] == $estatus ? 'selected' : '';

                                                    //echo '<option value="'.$valores["Descripcion"].'">'.$valores["Descripcion"].'</option>';
                                                    echo '<option value="' . $valores["Descripcion"] . '" ' . $sele . '>' . $valores["Descripcion"] . '</option>';

                                                endforeach; ?>
                                            </select>

                                        </div>
                                        <div class="col">
                                            <label class="titulos">Saldo Cliente</label>
                                            <input class="form-control" id="saldo_cliente" name="saldo_cliente" type="text" placeholder="Saldo Actual" disabled>
                                            <input type="hidden" id="saldo_cliente_o" name="saldo_cliente_o" value="">
                                        </div>
                                    </div>
                                    <div class="row my-1">
                                        <div class="col">
                                            <label for="btn-modal3" class="titulos">Importador/Exportador </label>
                                            <div class="input-group">
                                                <select class="align-text-bottom form-control" id="nombre_operador" name="nombre_operador" disabled required>
                                                    <?php
                                                    /*
                                        if($impor_export){
                                            echo '<option value="'.$impor_export.'">'.$impor_export.'</option>';
                                        }else{
                                            */
                                                    echo '<option value="">Selecciona una opción</option>';
                                                    //}
                                                    foreach ($result_empresas as $valores) :
                                                        $selei = $valores["Razon_Social"] == $impor_export ? 'selected' : '';
                                                        echo '<option rfc_empresa="' . $valores["RFC"] . '" dir_empresa="' . $valores['DOMICILIO_FISCAL'] . '" value="' . $valores["Razon_Social"] . '" ' . $selei . '>' . $valores["Razon_Social"] . '</option>';
                                                    endforeach; ?>
                                                </select>
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" nombre_operador="<?php echo $impor_export; ?>" id="btnEditarOperador"><i class="bi bi-pencil-square"></i></button>
                                            </div>
                                            <input type="hidden" id="nombre_operador_o" name="nombre_operador_o" value="<?php echo isset($impor_export) && !empty($impor_export) ?  $impor_export : "" ?>">
                                            <input type="hidden" id="rfc_empresa" name="rfc_empresa" value="<?php echo isset($rfc_empresa) ?  $rfc_empresa : "" ?>">
                                            <input type="hidden" id="dir_empresa" name="dir_empresa" value="<?php echo isset($dir_empresa) ?  $dir_empresa : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label for="btn-modal3" class="titulos">Operación </label>
                                            <select class="form-control" id="tipo_operacion" name="tipo_operacion" disabled required>
                                                <?php
                                                /*
                                        if($tipo_operacion){
                                            echo '<option value="'.$tipo_operacion.'">'.$tipo_operacion.'</option>';
                                        }else{
                                        */
                                                echo '<option value="">Selecciona una opción</option>';
                                                //}

                                                foreach ($result_cat_tipoope as $valores) :
                                                    $seleo = $valores["DESCRIPCION"] == $tipo_operacion ? 'selected' : '';
                                                    echo '<option value="' . $valores["DESCRIPCION"] . '" ' . $seleo . '>' . $valores["DESCRIPCION"] . '</option>';
                                                endforeach;
                                                ?>
                                            </select>
                                            <input type="hidden" id="tipo_operacion_o" name="tipo_operacion_o" value="<?php echo isset($tipo_operacion) && !empty($tipo_operacion) ?  $tipo_operacion : "" ?>">
                                        </div>
                                        <div class="col">
                                            <div class="boton-modal4">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tipo-operacion">
                                                    <label for="btn-modal4">Clave Pedimento
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                    </label>
                                                </button>
                                            </div>
                                            <select class="form-control" id="cve_pedimento" name="cve_pedimento">
                                                <?php
                                                /*
                                        if($clave_pedimento){
                                            echo '<option value="'.$clave_pedimento.'">'.$clave_pedimento.'</option>';
                                        }else{
                                            */
                                                echo '<option value="">Selecciona una opción</option>';
                                                // }
                                                foreach ($result_clv_pedimento as $valores) :
                                                    $selep = $valores["clave"] == $clave_pedimento ? 'selected' : '';
                                                    echo '<option value="' . $valores["clave"] . '" ' . $selep . '>' . $valores["clave"] . '</option>';
                                                endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="btn-modal5" class="titulos"> Tipo Tráfico </label>
                                            <select class="form-control" id="tipo_trafico" name="tipo_trafico" onchange="tipoTransporte(this.value)" required>
                                                <!-- <option value="">Selecciona una opción</option> -->
                                                <?php
                                                if ($tipo_trafico) {
                                                    //$select_cat_via  = "SELECT Medios FROM [dbo].[vias_tranporte] WHERE Medios != '$tipo_trafico'";
                                                    $select_cat_via  = "SELECT Medios FROM [dbo].[vias_tranporte]";

                                                    $cat_via = $conn_bd->prepare($select_cat_via);
                                                    $cat_via->execute();
                                                    $results_via = $cat_via->fetchAll(PDO::FETCH_ASSOC);

                                                    //echo '<option value="'.$tipo_trafico.'">'.$tipo_trafico.'</option>';

                                                    echo '<option value="">Selecciona una opción</option>';

                                                    foreach ($results_via as $valor) :
                                                        $seler = $valor["Medios"] == $tipo_trafico ? 'selected' : '';

                                                        echo '<option value="' . $valor["Medios"] . '" ' . $seler . '>' . $valor["Medios"] . '</option>';
                                                    endforeach;
                                                } else {
                                                    echo '<option value="">Selecciona una opción</option>';
                                                }

                                                ?>
                                            </select>
                                            <input type="hidden" id="tipo_trafico_o" name="tipo_trafico_o" value="<?php echo isset($tipo_trafico) && !empty($tipo_trafico) ?  $tipo_trafico : "" ?>">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-control ">
                                    <span class="title">EMPRESAS</span>
                                    <div class="row my-1">
                                        <div class="col">
                                            <div class="boton-modal">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-aduana">
                                                    <label for="btn-modal"> Aduana
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                    </label>
                                                </button>
                                            </div>
                                            <select class="form-control" id="denominacion_aduana" name="denominacion_aduana" onchange="mostrar_codigo_aduana(this.value)">
                                                <?php
                                                if ($tipo_trafico) {
                                                    echo '<option value="' . $denominacion_aduana . '">' . $denominacion_aduana . '</option>';
                                                } else {
                                                    echo '<option value="">Selecciona una opción</option>';
                                                }
                                                foreach ($result_catalogo_aduanas as $valores) :
                                                    echo '<option value="' . $valores["Denominación"] . '">' . $valores["Denominación"] . '</option>';
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="titulos">Clave Aduana</label>
                                            <input class="form-control" type="text" id="cve_aduana" name="cve_aduana" placeholder="Clave Aduana" disabled value="<?php echo isset($cve_aduana) && !empty($cve_aduana) ?  $cve_aduana : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label class="titulos">Master</label>
                                            <?php // if($tipo_trafico=="AEREO"){
                                            ?>
                                            <input class="form-control" type="text" id="bl" name="bl" oninput="quitarComillas(this)" placeholder="Introduce BL" value="<?php echo isset($bl) && !empty($bl) ?  $bl : ""  ?>">
                                            <?php   // }else{
                                            ?>
                                            <!-- <input class="form-control" type="text" id="bl" name="bl" oninput="quitarComillas(this)" placeholder="Introduce BL" value="<?php echo isset($bl) && !empty($bl) ?  $bl : ""  ?>" disabled > -->
                                            <?php   // }
                                            ?>

                                        </div>
                                        <div class="col">
                                            <label for="btn-modal" class="titulos"> House </label>
                                            <?php //  if($tipo_trafico=="AEREO"){
                                            ?>
                                            <input class="form-control" type="text" id="house" name="house" oninput="quitarComillas(this)" placeholder="Introduce House" value="<?php echo isset($house) && !empty($house) ? $house : ""  ?>">
                                            <?php  //  }else{
                                            ?>
                                            <!-- <input class="form-control" type="text" id="house" name="house" oninput="quitarComillas(this)"  placeholder="Introduce House" value="<?php echo isset($house) && !empty($house) ? $house : ""  ?>" disabled> -->
                                            <?php  //  }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row my-1">
                                        <div class="col-1">
                                            <br>
                                            <label for="">Contenedores</label>
                                        </div>
                                        <div class="col-2">
                                            <?php //  if($tipo_trafico=="CARRETERO"){
                                            ?>
                                            <!-- <input class="form-control" name="contenedor1" id="contenedor1" type="text" maxlength="12 " oninput="quitarComillas(this)" placeholder="Contenedor 1" value="<?php echo isset($contenedor1) && !empty($contenedor1) ?  $contenedor1 : ""  ?>" disabled> -->
                                            <!-- <input class="form-control" name="contenedor2" id="contenedor2" type="text" maxlength="12 " oninput="quitarComillas(this)" placeholder="Contenedor 2" value="<?php echo isset($contenedor2) && !empty($contenedor2) ?  $contenedor2 : ""  ?>" disabled> -->
                                            <?php //   }else{
                                            ?>
                                            <input class="form-control" name="contenedor1" id="contenedor1" type="text" maxlength="12 " oninput="quitarComillas(this)" placeholder="Contenedor 1" value="<?php echo isset($contenedor1) && !empty($contenedor1) ?  $contenedor1 : ""  ?>">
                                            <input class="form-control" name="contenedor2" id="contenedor2" type="text" maxlength="12 " oninput="quitarComillas(this)" placeholder="Contenedor 2" value="<?php echo isset($contenedor2) && !empty($contenedor2) ?  $contenedor2 : ""  ?>">
                                            <?php   // }
                                            ?>
                                        </div>
                                        <div class="col-3">
                                            <div class="boton-modal">
                                                <label for="btn-modal" style="height: 100px;" class="titulos"> Buscar Número Ecónomico
                                                    <i class="bi bi-search"></i>
                                                </label>
                                                </button>
                                            </div>
                                            <?php if ($tipo_trafico == "CARRETERO-FERROVIARIO" || $tipo_trafico == "CARRETERO") { ?>
                                                <input class="form-control" type="text" placeholder="Introduce Numero Economico" oninput="quitarComillas(this)" name="num_eco" id="num_eco" value="<?php echo isset($numero_economico) && !empty($numero_economico) ? $numero_economico : ""  ?>">
                                            <?php    } else { ?>
                                                <input class="form-control" type="text" placeholder="Introduce Numero Economico" oninput="quitarComillas(this)" name="num_eco" id="num_eco" value="<?php echo isset($numero_economico) && !empty($numero_economico) ? $numero_economico : ""  ?>" disabled>
                                            <?php    } ?>

                                        </div>
                                        <div class="col-3">
                                            <label class="titulos">Bultos</label>
                                            <input type="text" name="bultos" id="bultos" oninput="quitarComillas(this)" placeholder="Introduce Bultos 00.00" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)" min="1" value="<?php echo isset($bultos) && !empty($bultos) ? $bultos : ""  ?>">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-control ">
                                    <span class="title">ESTATUS </span>
                                    <div class="container py-3" id="containerDetalleRetenidos">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="6">
                                                        <strong>MSA</strong>
                                                    </div>
                                                    <div class="6">
                                                        <div class="btn-group-vertical" role="group" aria-label="Basic mixed styles example">
                                                            <div class="form-check">
                                                                <input class="form-check-input bg-success" type="radio" name="msa_estado" id="msa_activo" value="A" <?php echo $msa_estado == 'A' ? 'checked' : "" ?>>
                                                                <label class="form-check-label text-success" for="msa_activo">
                                                                    Verde
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input bg-danger" type="radio" name="msa_estado" id="msa_inactivo" value="I" <?php echo $msa_estado == 'I' ? 'checked' : "" ?>>
                                                                <label class="form-check-label text-danger" for="msa_inactivo">
                                                                    Rojo
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="6">
                                                        <strong>Incidencia</strong>
                                                    </div>
                                                    <div class="6">
                                                        <div class="btn-group-vertical" role="group" aria-label="Basic mixed styles example">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="incidencia_estado" id="incidencia_si" value="S" <?php echo $incidencia_estado == 'S' ? 'checked' : "" ?>>
                                                                <label class="form-check-label" for="incidencia_si">
                                                                    Si
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="incidencia_estado" id="incidencia_no" value="N" <?php echo $incidencia_estado == 'N' ? 'checked' : "" ?>>
                                                                <label class="form-check-label" for="incidencia_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label for="fecha_retenido"> <strong>Fecha de retencion:</strong> </label>
                                                <div class="input-group mb-3">
                                                    <input type="date" class="form-control" id="modal_fecha_retenido" name="modal_fecha_retenido" value="<?php echo $fecha_retenido !== '1900-01-01' ? $fecha_retenido : ''; ?>">
                                                    <button class="btn btn-warning btn-outline-primary" type="button" id="btnDeleteRetenido"><span clasS="bi bi-trash"></span></button>
                                                </div>
                                                <br>
                                                <label for="fecha_retenido"><strong>Fecha de liberación:</strong></label>
                                                <div class="input-group mb-3">
                                                    <input type="date" class="form-control" id="modal_fecha_liberacion" name="modal_fecha_liberacion" value="<?php echo $fecha_liberacion !== '1900-01-01' ? $fecha_liberacion : ''; ?>">
                                                    <button class="btn btn-warning btn-outline-primary" type="button" id="btnDeleteLiberacion" onclick="limpiarFechaEstatus(this)"><span clasS="bi bi-trash"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row py-3">
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <textarea class="form-control" placeholder="Escribe una observación aquí" id="observacion"><?php echo $observacion; ?></textarea>
                                                    <label for="observacion">Observación:</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center">
                                            <button type="button" class="btn btn-primary btn-block" id="btnGuardarEstatus" onclick="GuardarEstatus()">
                                                <span id="spinner_guardarEstatus" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-control ">
                                    <span class="title">FACTURAS</span>
                                    <div class="row my-1">
                                        <div class="col-3">
                                            <div class="boton-modal text-center">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVerFacturas" onclick="modalVerFacturas()">
                                                    <label for="btn-modal">Ver facturas
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="boton-modal text-center">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCargarFacturas" onclick="modalCargarFactura()">
                                                    <label for="btn-modal">Cargar Factura
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="red" A stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                        </div>
                                        <div class="col-3 text-center">
                                            <label class="titulos">Valor Factura</label>
                                            <input class="form-control text-center" id="valor_factura" name="valor_factura" type="text" oninput="quitarComillas(this)" placeholder="Introduce Valor 00.00" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)" min="1" value="<?php echo isset($saldo) && !empty($saldo) ?  '$' . $saldo : "" ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="row my-1">
                                        <div class="col">
                                            <label class="titulos">Tipo Cambio</label>
                                            <input class="form-control" id="tipo_cambio" name="tipo_cambio" oninput="quitarComillas(this)" type="text" placeholder="Introduce Tipo Cambio 00.00" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)" min="1" value="<?php echo isset($tipo_cambio) && !empty($tipo_cambio) ? $tipo_cambio : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label class="titulos">Peso Bruto</label>
                                            <input class="form-control" type="text" id="peso_bruto" name="peso_bruto" oninput="quitarComillas(this)" placeholder="Peso Bruto 00.00" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)" min="1" value="<?php echo isset($peso_bruto) && !empty($peso_bruto) ?  $peso_bruto : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label>Fecha Notificacion</label><button type="button" class="btn btn-danger" id="btn_Notificaion"><i class="bi bi-trash"></i></button>
                                            <input class="form-control" type="date" id="fechNotifi" name="fechNotifi" value="<?php echo isset($date_notificacion) && !empty($date_notificacion) ? $date_notificacion : "" ?>">

                                        </div>
                                        <div class="col">
                                            <label>Fecha Arribo</label><button type="button" class="btn btn-danger" id="btn_Arribo"><i class="bi bi-trash"></i></button>
                                            <input class="form-control" type="date" id="fecharribo" name="fecharribo" value="<?php echo isset($date_arribo) && !empty($date_arribo) ? $date_arribo : "" ?>">
                                        </div>
                                    </div>
                                    <div class="row my-1">
                                        <div class="col">
                                            <label>Fecha Pago Pedimento</label><button type="button" class="btn btn-danger" id="btn_pedimento"><i class="bi bi-trash"></i></button>
                                            <input class="form-control" type="date" id="fechpedimento" name="fechpedimento" value="<?php echo isset($date_fecha_pedimento) && !empty($date_fecha_pedimento) ? $date_fecha_pedimento : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label>Fecha Modulación</label><button type="button" class="btn btn-danger" id="btnmodu"><i class="bi bi-trash"></i></button>
                                            <input class="form-control" type="date" id="fechamodulacion" name="fechamodulacion" value="<?php echo isset($date_modulacion) && !empty($date_modulacion) ? $date_modulacion : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label class="titulos">Patente</label>
                                            <input class="form-control" type="text" id="patente" name="patente" oninput="quitarComillas(this)" maxlength="4" placeholder="Patente  4 Digitos" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1" value="<?php echo isset($patente) && !empty($patente) ?  $patente : "" ?>">
                                        </div>
                                        <div class="col">
                                            <div class="boton-modal">
                                                <?php
                                                $verifica = "SELECT Horaope, Fechope, Numero_pedimento FROM [dbo].[FK_RECTIFICADO] WHERE Referencia_Nexen = '{$referencia_nexen}'";
                                                $resul_verifica = $conn_bd->prepare($verifica);
                                                $resul_verifica->execute();
                                                $verificado_Fk = $resul_verifica->fetch(PDO::FETCH_ASSOC);
                                                for ($i = 0; $i < count($result_cat_estatus); $i++) {
                                                    if ($result_cat_estatus[$i]['Descripcion'] == $estatus) {

                                                        if (!empty($verificado_Fk)) {
                                                            echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Rectificacion">
                                                    <label for="btn-modal">Rectificación</label>
                                                    </button>';
                                                            echo ' <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Historial_Rectificacion"><i class="bi bi-search"></i></button>';
                                                        } else if (!empty($num_pedimento)) {

                                                            echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Rectificacion">
                                                    <label for="btn-modal">Rectificación</label>
                                                    </button>';
                                                        }
                                                    } else if ($result_cat_estatus[$i]['Descripcion'] ==  $estatus) {
                                                        if (!empty($verificado_Fk)) {
                                                            echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Rectificacion">
                                                    <label for="btn-modal">Rectificación</label>
                                                    </button>';
                                                            echo ' <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Historial_Rectificacion"><i class="bi bi-search"></i></button>';
                                                        } else if (!empty($num_pedimento)) {
                                                            echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Rectificacion">
                                                    <label for="btn-modal">Rectificación</label>
                                                    </button>';
                                                        }
                                                    } else if ($result_cat_estatus[$i]['Descripcion'] ==  $estatus) {
                                                        if (!empty($verificado_Fk)) {
                                                            echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Rectificacion">
                                                    <label for="btn-modal">Rectificación</label>
                                                    </button>';
                                                            echo ' <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Historial_Rectificacion"><i class="bi bi-search"></i></button>';
                                                        } else if (!empty($num_pedimento)) {
                                                            echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal_Rectificacion">
                                                    <label for="btn-modal">Rectificación</label>
                                                    </button>';
                                                        }
                                                    }
                                                }

                                                if (!empty($verificado_Fk)) {
                                                    $datos = "SELECT TOP 1 Fechope AS fecha_mas_reciente, Horaope AS hora_mas_reciente, Numero_pedimento AS numero
                                                        FROM [dbo].[FK_RECTIFICADO]
                                                        WHERE Referencia_Nexen = '{$referencia_nexen}'
                                                        ORDER BY Fechope DESC, Horaope DESC";
                                                    $result_ultimo  = $conn_bd->prepare($datos);
                                                    $result_ultimo->execute();
                                                    $result_Fk = $result_ultimo->fetch(PDO::FETCH_ASSOC);

                                                ?>
                                                    <input class="form-control" style="margin-top: 5px;" type="text" id="num_pedimento" name="num_pedimento" oninput="quitarComillas(this)" maxlength="15" placeholder="Pedimento 7 Digitos" class="form-control" autocomplete="off" disabled onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1" value="<?php echo $result_Fk['numero']; ?>">
                                                    <label for="btn-modal" style="color: red;" class="titulos">Rectificado</label>
                                                <?php
                                                }
                                                ?>

                                                <label for="btn-modal" class="titulos">Buscar Número Pedimento
                                                    <i class="bi bi-search"></i>
                                                </label>
                                            </div>
                                            <input class="form-control" type="text" id="num_pedimento" name="num_pedimento" oninput="quitarComillas(this)" maxlength="15" placeholder="Pedimento 7 Digitos" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1" value="<?php echo isset($num_pedimento) && !empty($num_pedimento) ?  $num_pedimento : "" ?>">
                                        </div>
                                    </div>
                                    <div class="row my-1">
                                        <div class="col">
                                            <label>Detalle de Mercancía</label>
                                            <input class="form-control" id="det_mercancia" name="det_mercancia" oninput="quitarComillas(this)" placeholder="Detalle de Mercancía" value="<?php echo isset($det_mercancia) && !empty($det_mercancia) ?  $det_mercancia : "" ?>">
                                        </div>
                                    </div>
                                    <div class="row my-1">
                                        <div class="col">
                                            <label class="titulos">Número Recibo WMS</label>
                                            <input class="form-control" type="text" id="wms" name="wms" oninput="quitarComillas(this)" placeholder="Introduce WMS" value="<?php echo isset($recibo_wms) && !empty($recibo_wms) ?  $recibo_wms : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label class="titulos">Número Anexo #24</label>
                                            <input class="form-control" type="text" id="anexo_24" name="anexo_24" oninput="quitarComillas(this)" placeholder="Introduce Anexo #24" value="<?php echo isset($num_anexo_24) && !empty($num_anexo_24) ? $num_anexo_24 : "" ?>">
                                        </div>
                                        <div class="col">
                                            <label>Fecha Factura Anexo #24</label><button type="button" class="btn btn-danger" id="btnNex24"><i class="bi bi-trash"></i></button>
                                            <input class="form-control" type="date" id="opeNex" name="opeNex" value="<?php echo isset($date_fact_anexo24) && !empty($date_fact_anexo24) ?  $date_fact_anexo24 : "" ?>">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-control">
                                    <span class="title">CONTROLES</span>
                                    <div class="row my-1">
                                        <div class="col-sm-6">

                                        </div>
                                        <div class="col-sm-3 ">
                                            <label for="">Usuario</label>
                                            <input type="text" name="user" id="user" oninput="quitarComillas(this)" class="form-control" value="<?php echo isset($usuario_ope) && !empty($usuario_ope) ?  $usuario_ope : "" ?>" disabled>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="">Hora</label>
                                            <input type="datetime" name="hora" id="hora" class="form-control" value="<?php echo isset($hora) && !empty($hora) ?  $hora : "" ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary btn-lg"> <i class="bi bi-arrow-repeat"></i>ACTUALIZAR</button>
                            </div>
                            </form>
                            <div class="col text-center">
                                <button class="btn btn-danger btn-lg" onclick="modalborrarOperacion()"><i class="bi bi-trash"></i>BORRAR</button>
                            </div>
                        </div>
                        <br>
                    </div>
                    <!-- </div> -->

                    <!-- DIV DOCUMENTOS -->
                    <div class="col">
                        <div class="col-12">
                            <div id="data"></div>
                            <div class="tile">
                                <div class="tile-body">
                                    <?php if (isset($referencia_nexen) && !empty($referencia_nexen))
                                        $referencia_nexen = $referencia_nexen; {
                                    ?>
                                        <button type="button" class="btn btn-success" id="verDocumentos" onclick="openCarpetas('<?php echo $referencia_nexen; ?>','<?php echo $tipo_trafico; ?>')"><i class="bi bi-eye-fill"></i>
                                            Carpeta Digital</button>
                                    <?php } ?>
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>DOCUMENTO</th>
                                                    <th>ACCIONES</th>
                                                    <th>ESTADO</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableDocumentos">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-12">
                            <div class="form-control">
                                <h5>CHECK LIST</h5>
                                <form method="POST">
                                    <?php
                                    $select_cat_checklist = "SELECT * FROM [dbo].[Catalogo_Check_list]";
                                    $cat_checklist = $conn_bd->prepare($select_cat_checklist);
                                    $cat_checklist->execute();
                                    $results_cat_checklist = $cat_checklist->fetchAll(PDO::FETCH_ASSOC);
                                    // print_r($results_cat_checklist);
                                    foreach ($results_cat_checklist as $checklist) :
                                        $id_catalogo = $checklist['Id_Catalogo'];

                                        $select_check = "SELECT * FROM [dbo].[Catalogo_Check_list_Detalle] WHERE Id_Catalogo = $id_catalogo AND Referencia_Nexen = '$referencia_nexen'";
                                        $exist_checklist = $conn_bd->prepare($select_check);
                                        $exist_checklist->execute();
                                        $results_checklist = $exist_checklist->fetchAll(PDO::FETCH_ASSOC);
                                    ?>

                                        <?php
                                        if (empty($results_checklist)) { ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="this.form.action ='valida_checklist.php?id_ref=<?php echo $referencia_nexen ?>&check=<?php echo $id_catalogo ?>&user=<?php echo $usuario_ope ?>'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16" style="color:red">

                                                </svg>
                                            </button>
                                            <label for="check_tp_<?php echo $checklist['Id_Catalogo']; ?>"><?php echo $checklist['Descripción']; ?></label> <br>
                                        <?php } else { ?>
                                            <button type="submit" class="btn btn-success btn-sm" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16" style="color:white">
                                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                                </svg>


                                            </button>
                                            <label for="check_tp_<?php echo $checklist['Id_Catalogo']; ?>"><?php echo $checklist['Descripción']; ?></label><br>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </form>
                            </div>
                        </div>
                        <br>
                        <div class="col-12">
                            <div class="form-control text-center">
                                <h5>SOLICITUD DE PAGOS</h5>
                                <div class="boton-modal text-center">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_solicitud_pagos">
                                        <label for="btn-modal"> Solicitud de Pagos
                                            <i class="bi bi-credit-card"></i>
                                        </label>
                                    </button>
                                </div><br>
                                <div class="boton-modal text-center">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" onclick="abrirmodaldet();">
                                        <label for="btn-modal"> Detalle de Pagos
                                            <i class="bi bi-wallet2"></i>
                                        </label>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DIV DOCUMENTOS -->

                    <!-- TABLA CONTENEDORES -->
                    <div class="container mt-3 text-center" style="max-width: 100%;">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="text-center nft">HISTORIAL DE MOVIMIENTOS</h3>
                                        <div class="section-table container-table">
                                            <table id="data_table" class="table table-hover table-striped" style="font-size: 12px; margin-right:5px !important;">
                                                <thead>
                                                    <tr>
                                                        <th>USUARIO</th>
                                                        <th>REFERENCIA CLIENTE</th>
                                                        <th>CLIENTE</th>
                                                        <th>BL</th>
                                                        <th>CONTENEDOR 1</th>
                                                        <th>FECHA ARRIBO</th>
                                                        <th>FECHA NOTIFICACIÓN</th>
                                                        <th>FECHA PAGO ANTICIPO</th>
                                                        <th>FECHA MODULACIÓN</th>
                                                        <th># PEDIMENTO</th>
                                                        <th>IMPORTADOR EXPORTADOR</th>
                                                        <th>CVE PEDIMENTO</th>
                                                        <th>VALOR FACTURA</th>
                                                        <th>DESCRIPCIÓN COVE</th>
                                                        <th>TIPO CAMBIO</th>
                                                        <th>FECHA FACTURA 24</th>
                                                        <th>WMS</th>
                                                        <th>ESTATUS</th>
                                                        <th>HORA OPE</th>
                                                        <th>FECHA OPE</th>
                                                        <th>PATENTE</th>
                                                        <th>MONEDA</th>
                                                        <th>DENOMINACIÓN ADUANA</th>
                                                        <th>GUIA HOUSE</th>
                                                        <th>TIPO OPERACIÓN</th>
                                                        <th>PROVEEDOR</th>
                                                        <th>BULTOS</th>
                                                        <th>PESO BRUTO</th>
                                                        <th>TIPO TRÁFICO</th>
                                                        <th>GUIA HOUSE 1</th>
                                                        <th>FACTURA SALIDA ANEXO24</th>
                                                        <th>NÚMERO ECONÓMICO</th>
                                                        <th>REFERENCIA NEXEN</th>
                                                        <th>CONTENEDOR 2</th>
                                                        <th>ACCIÓN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $select_hist_operacion  = "SELECT * FROM [Fk_Log_Detalle_Ope_Nexen]  WHERE REFERENCIA_NEXEN = '$referencia_nexen '";
                                                    $histo_ope = $conn_bd->prepare($select_hist_operacion);
                                                    $histo_ope->execute();
                                                    $results_hist_ope = $histo_ope->fetchAll(PDO::FETCH_ASSOC);

                                                    foreach ($results_hist_ope as $operacion) : ?>
                                                        <tr>
                                                            <td class=""><?php echo $operacion['Usuario']; ?></td>
                                                            <td class=""><?php echo $operacion['Referencia_Cliente']; ?></td>
                                                            <td class=""><?php echo $operacion['Cliente']; ?></td>
                                                            <td class=""><?php echo $operacion['BL']; ?></td>
                                                            <td class=""><?php echo $operacion['Contenedor_1']; ?></td>
                                                            <td class=""><?php echo $operacion['Fecha_Arribo']; ?></td>
                                                            <td class=""><?php echo $operacion['Fecha_Notificacion']; ?></td>
                                                            <td class=""><?php echo $operacion['Fecha_Pago_Anticipo']; ?></td>
                                                            <td class=""><?php echo $operacion['Fecha_Modulacion']; ?></td>
                                                            <td class=""><?php echo $operacion['No_Pedimento']; ?></td>
                                                            <td class=""><?php echo $operacion['Importador_Exportador']; ?></td>
                                                            <td class=""><?php echo $operacion['Clave_Pedimento']; ?></td>
                                                            <td class=""><?php echo $operacion['Valor_Factura']; ?></td>
                                                            <td class=""><?php echo $operacion['Descripcion_Cove']; ?></td>
                                                            <td class=""><?php echo $operacion['tipo_cambio']; ?></td>
                                                            <td class=""><?php echo $operacion['Fecha_Factura24']; ?></td>
                                                            <td class=""><?php echo $operacion['WMS']; ?></td>
                                                            <td class=""><?php echo $operacion['Estatus']; ?></td>
                                                            <td class=""><?php echo $operacion['HORA_OPE']; ?></td>
                                                            <td class=""><?php echo $operacion['FECHOPE']; ?></td>
                                                            <td class=""><?php echo $operacion['Patente']; ?></td>
                                                            <td class=""><?php echo $operacion['Moneda']; ?></td>
                                                            <td class=""><?php echo $operacion['DENOMINACION_ADUANA']; ?></td>
                                                            <td class=""><?php echo $operacion['Guia_House']; ?></td>
                                                            <td class=""><?php echo $operacion['Tipo_Operacion']; ?></td>
                                                            <td class=""><?php echo $operacion['proveedor']; ?></td>
                                                            <td class=""><?php echo $operacion['BULTOS']; ?></td>
                                                            <td class=""><?php echo $operacion['peso_bruto']; ?></td>
                                                            <td class=""><?php echo $operacion['tipo_trafico']; ?></td>
                                                            <td class=""><?php echo $operacion['GUIA_HOUSE1']; ?></td>
                                                            <td class=""><?php echo $operacion['FACTURA_SALIDA_ANEXO24']; ?></td>
                                                            <td class=""><?php echo $operacion['NUMERO_ECONOMICO']; ?></td>
                                                            <td class=""><?php echo $operacion['REFERENCIA_NEXEN']; ?></td>
                                                            <td class=""><?php echo $operacion['Contenedor_2']; ?></td>
                                                            <td class=""><?php echo $operacion['Tipo_OPE']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN TABLA CONTENEDORES -->

                        <!-- modal para subir archivos -->
                        <div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header headerRegister">
                                        <h5 class="modal-title" id="titleModal">Subir Archivo</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formArchivo" method="POST" action="procesar_archivo.php" enctype="multipart/form-data" class="form-control">
                                            <div class="row" style="padding: 10px;">
                                                <div class="col-md-12" style="padding: 10px;">
                                                    <input type="hidden" id="Tipo_Ope" name="Tipo_Ope" value="">
                                                    <input type="hidden" id="id_catalogo" name="id_catalogo" value="">
                                                    <input type="hidden" id="Referencia" name="Referencia" value="">
                                                    <input type="hidden" id="nombre" name="nombre" value="">
                                                </div>
                                                <div class="col-md-12" style="padding: 10px;">
                                                    <input type="file" name="Archivo">
                                                    <input class="btn btn-success" type="submit" value="Guardar Archivo">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  modal para subir archivos -->


                        <!-- MODAL CLIENTES -->
                        <div class="modal fade" id="modal-cliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class=" modal-dialog ">
                                <div class="modal-content text-center">
                                    <div class="modal-header   bg-primary   text-white  text-center ">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">CLIENTES</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body modal-dialog-scrollable">
                                        <form action="../include/guardar_cliente.php" method="POST">
                                            <label class="display-5">Clientes</label><br><br>
                                            <label class="align-text-bottom form-label">Razón Social</label>
                                            <input type="text" class="align-text-bottom form-control" name="razon_social_cliente" id="razon_social_cliente" placeholder="Introduce Razón Social" required>
                                            <label class="align-text-bottom form-label">RFC</label>
                                            <input type="text" class="align-text-bottom form-control" name="rfc_cliente" id="rfc_cliente" placeholder="Introduce RFC Cliente" required>
                                            <label class="align-text-bottom form-label">Teléfono</label>
                                            <input type="text" class="align-text-bottom form-control" name="telefono_cliente" id="telefono_cliente" placeholder=" Introduce Telefono Cliente" required>
                                            <label class="align-text-bottom form-label">Movíl</label>
                                            <input type="text" class="align-text-bottom form-control" name="movil_cliente" id="movil_cliente" placeholder="Introduce Movil Cliente">
                                            <label class="align-text-bottom form-label"> Nombre Contacto</label>
                                            <input type="text" class="align-text-bottom form-control" name="nombre_contacto" id="nombre_contacto" placeholder="Introduce nombre contacto" required>
                                            <label class="align-text-bottom form-label"> Email # 1</label>
                                            <input type="email" class="align-text-bottom form-control" name="email_cliente_1" id="email_cliente_1" placeholder="Introduce email cliente" required>
                                            <label class="align-text-bottom form-label"> Email # 2</label>
                                            <input type="email" class="align-text-bottom form-control" name="email_cliente_2" id="email_cliente_2" placeholder="Introduce segundo email">
                                            <label class="align-text-bottom form-label"> Domicilio</label>
                                            <input type="text" class="align-text-bottom form-control" name="domicilio_cliente" id="domicilio_cliente" placeholder="Introduce domicilio cliente" required>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="embed-responsive">
                                        <div class="container-fluid">
                                            <table class="table w-100 p-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"># REGISTRO</th>
                                                        <th scope="col">CLIENTE</th>
                                                        <th scope="col">RFC</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>MarkDSADSAD</td>
                                                        <td>MarkDSADSAD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN MODAL CLIENTES -->

                        <!--INICIA MODAL EDITAR/BORRAR CLIENTES-->
                        <div class="modal fade" id="modal_editar_cliente" tabindex="-1" aria-labelledby="editmodalcliente" aria-hidden="true">
                            <div class=" modal-dialog ">
                                <div class="modal-content text-center">
                                    <div class="modal-header   bg-primary   text-white  text-center ">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="editmodalcliente">EDITAR CLIENTES</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body modal-dialog-scrollable">
                                        <label class="display-5">Clientes</label><br><br>
                                        <label class="align-text-bottom form-label">Razón Social</label>
                                        <input type="text" class="align-text-bottom form-control" disabled name="razon_social_cliente_edit" id="razon_social_cliente_edit" placeholder="Introduce Razón Social" required>
                                        <label class="align-text-bottom form-label">RFC</label>
                                        <input type="text" class="align-text-bottom form-control" name="rfc_cliente_edit" id="rfc_cliente_edit" placeholder="Introduce RFC Cliente" required>
                                        <label class="align-text-bottom form-label">Teléfono</label>
                                        <input type="text" class="align-text-bottom form-control" name="telefono_cliente_edit" id="telefono_cliente_edit" placeholder=" Introduce Telefono Cliente" required>
                                        <label class="align-text-bottom form-label">Movíl</label>
                                        <input type="text" class="align-text-bottom form-control" name="movil_cliente_edit" id="movil_cliente_edit" placeholder="Introduce Movil Cliente">
                                        <label class="align-text-bottom form-label"> Nombre Contacto</label>
                                        <input type="text" class="align-text-bottom form-control" name="nombre_contacto_edit" id="nombre_contacto_edit" placeholder="Introduce nombre contacto" required>
                                        <label class="align-text-bottom form-label"> Email # 1</label>
                                        <input type="email" class="align-text-bottom form-control" name="email_cliente_1_edit" id="email_cliente_1_edit" placeholder="Introduce email cliente" required>
                                        <label class="align-text-bottom form-label"> Email # 2</label>
                                        <input type="email" class="align-text-bottom form-control" name="email_cliente_2_edit" id="email_cliente_2_edit" placeholder="Introduce segundo email">
                                        <label class="align-text-bottom form-label"> Domicilio</label>
                                        <input type="text" class="align-text-bottom form-control" name="dom_cliente_edit" id="dom_cliente_edit" placeholder="Introduce domicilio cliente" required>
                                        <label class="align-text-bottom form-label">Contraseña</label>
                                        <input type="password" class="align-text-bottom form-control" name="pw_cliente_edit" id="pw_cliente_edit" placeholder="Introduce la contraseña del supervisor" required>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Bloqueado hasta proxima actualización">
                                                <button type="button" class="btn btn-danger" disabled>Borrar Cliente</button>
                                            </span>
                                            <!--
                                <button type="button" class="btn btn-danger" disabled
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Estará disponible hasta la proxima actualización."
                                >Borrar Cliente</button>
                                -->
                                            <button type="button" class="btn btn-warning" onclick="updateCliente()">Editar Cliente</button>
                                        </div>
                                    </div>
                                    <div class="embed-responsive">
                                        <div class="container-fluid">
                                            <table id="dataTableClientes" class="table w-100 p-3">
                                                <thead>
                                                    <tr>
                                                        <th># REGISTRO</th>
                                                        <th>CLIENTE</th>
                                                        <th>ACCIÓN</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN MODAL BORRAR/EDITAR CLIENTES -->

                        <!-- INICIA MODAL OPERADORES -->
                        <div class="modal fade" id="modalOperador" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Editar Operador (Importador/Exportador)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center py-2">
                                            <h5>Para poder editar al operador necesitas el usuario y contraseña de algún supervisor de area.</h5>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="user_operador_edit" placeholder="Usuario">
                                            <label for="user_operador_edit">Usuario</label>
                                        </div>
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="pw_operador_edit" placeholder="Contraseña">
                                            <label for="pw_operador_edit">Contraseña</label>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger d-none" role="alert" id="alerta_edit_operador">
                                        Usuario/Contraseña incorrectos!
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" id="comprobarContraSupervisor">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="modal_spinner_operador"></span>
                                            Verificar contraseña
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- TERMINA MODAL OPERADORES -->


                        <!-- MODAL ESTATUS -->
                        <div class="modal fade" id="modal-estatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class=" modal-dialog ">
                                <div class="modal-content text-center">
                                    <div class="modal-header bg-primary text-white text-center ">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">CATALOGO ESTATUS</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body modal-dialog-scrollable">
                                        <form action="../include/guardar_estatus.php" method="POST">
                                            <label class="display-5">ESTATUS</label><br><br>
                                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+" onblur="validarText(this)" name="estatus" id="estatus" placeholder="Introduce ESTATUS" required>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-success" id="guardar_estatus">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="embed-responsive">
                                        <div class="container-fluid">
                                            <table class="table w-100 p-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"># ESTATUS</th>
                                                        <th scope="col">ESTATUS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>MarkDSADSAD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN MODAL ESTATUS-->

                        <!-- MODAL IMPORTADOR/EXPORTADOR -->
                        <div class="modal fade" id="modal-impo-expo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class=" modal-dialog ">
                                <div class="modal-content text-center">
                                    <div class="modal-header   bg-primary   text-white  text-center ">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">IMPORTADOR/EXPORTADOR</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body modal-dialog-scrollable">
                                        <form action="../include/guardar_importador_exportador.php" method="POST">
                                            <label class="display-5">Empresas</label><br><br>
                                            <label class="align-text-bottom form-label">Razon Social</label>
                                            <input type="text" class="align-text-bottom form-control" name="razon_social_impo_expo" id="razon_social_impo_expo" placeholder="Introduce Razón Social">
                                            <label class="align-text-bottom form-label">RFC</label>
                                            <input type="text" class="align-text-bottom form-control" name="rfc_impo_expo" id="rfc_impo_expo" placeholder="Introduce RFC">
                                            <label class="align-text-bottom form-label">Domicilio Fiscal</label>
                                            <input type="text" class="align-text-bottom form-control" name="domicilio_fiscal" id="domicilio_fiscal" placeholder="Domicilio Fiscal">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="embed-responsive">
                                        <div class="container-fluid">
                                            <table class="table w-100 p-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"># FACTURA</th>
                                                        <th scope="col">CONCEPTO</th>
                                                        <th scope="col">VALOR</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>MarkDSADSAD</td>
                                                        <td>MarkDSADSAD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN MODAL IMPORTADOR/EXPORTADOR -->

                        <!-- MODAL TIPO OPERACION -->
                        <div class="modal fade" id="modal-tipo-operacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class=" modal-dialog ">
                                <div class="modal-content text-center">
                                    <div class="modal-header   bg-primary   text-white  text-center ">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">CATALOGO TIPO OPERACION</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body modal-dialog-scrollable">
                                        <form action="../include/guardar_tipo_operacion.php" method="POST">
                                            <label class="display-6">TIPO OPERACIÓN</label><br><br>
                                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+" onblur="validarText(this)" id="tipo_operacion" name="tipo_operacion" placeholder="Introduce Tipo Operación">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="sumbit" class="btn btn-success" id="guardar_ope">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="embed-responsive">
                                        <div class="container-fluid">
                                            <table class="table w-100 p-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"># TIPO OPERACIÓN</th>
                                                        <th scope="col">DESCRIPCIÓN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>MarkDSADSAD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FIN MODAL TIPO OPERACION -->

                        <!-- MODAL ADUANA -->
                        <div class="modal fade" id="modal-aduana" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class=" modal-dialog ">
                                <div class="modal-content text-center">
                                    <div class="modal-header bg-primary text-white  text-center ">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel"> Catalago Aduanas</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body modal-dialog-scrollable">
                                        <form action="../include/guardar_aduana.php" method="POST">
                                            <label class="display-5">Aduanas</label><br><br>

                                            <label class="align-text-bottom form-label">Numero Aduana</label>
                                            <input type="number" class="align-text-bottom  form-control" onblur="validarNumber(this)" id="numero_aduana" name="numero_aduana" require placeholder="Introduce  Numero Aduana">

                                            <label class="align-text-bottom form-label">Denominación Aduana</label>
                                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+" onblur="validarText(this)" id="denominacion_aduana" name="denominacion_aduana" placeholder="Introduce  Denominación Aduana">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-success" id="guardar_aduana">Guardar Cambios</button>

                                            </div>
                                        </form>
                                    </div>
                                    <div class="embed-responsive">
                                        <div class="container-fluid">
                                            <table class="table w-100 p-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"># ADUANA</th>
                                                        <th scope="col">DENOMINACION ADUANA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>MarkDSADSAD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- MODAL FIN ADUANA -->

                        <!-- MODAL CONTENEDORES-->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog  text-center">
                                <div class="modal-content text-center">
                                    <div class="modal-header   bg-primary   text-white  text-center">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">ASOCIAR CONTENEDOR</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="#">
                                        <div class="modal-body">
                                            <label class="display-5">Contenedor Principal</label><br><br>
                                            <label class="blockquote-footer text-center"># Pedimento</label> <br><br>
                                            <label class="blockquote-footer text-center"># BL</label> <br><br>
                                            <label class="align-text-bottom form-label">Contenedor Asociado</label>
                                            <input type="text" class="align-text-bottom  form-control" placeholder="Introduce # Contenedor Asociado">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">First</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Mark</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- FIN MODAL CONTENEDORES -->

                        <!-- MODAL DETALLE RETENIDOS-->
                        <div class="modal fade" id="modalDetalleRetenidos" tabindex="-1" aria-labelledby="modalDetalleRetenidos" aria-hidden="true">
                            <div class="modal-dialog modal-xl  text-center">
                                <div class="modal-content text-center">
                                    <div class="modal-header   bg-primary   text-white  text-center">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">Detalle Retenidos</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="6">
                                                        <strong>MSA</strong>
                                                    </div>
                                                    <div class="6">
                                                        <div class="btn-group-vertical" role="group" aria-label="Basic mixed styles example">
                                                            <div class="form-check">
                                                                <input class="form-check-input bg-success" type="radio" name="msa_estado" id="msa_activo" value="A" <?php echo $msa_estado == 'A' ? 'checked' : "" ?>>
                                                                <label class="form-check-label" for="msa_activo">
                                                                    Activo
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input bg-danger" type="radio" name="msa_estado" id="msa_inactivo" value="I" <?php echo $msa_estado == 'I' ? 'checked' : "" ?>>
                                                                <label class="form-check-label" for="msa_inactivo">
                                                                    Inactivo
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="6">
                                                        <strong>Incidencia</strong>
                                                    </div>
                                                    <div class="6">
                                                        <div class="btn-group-vertical" role="group" aria-label="Basic mixed styles example">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="incidencia_estado" id="incidencia_si" value="S" <?php echo $incidencia_estado == 'S' ? 'checked' : "" ?>>
                                                                <label class="form-check-label" for="incidencia_si">
                                                                    Si
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="incidencia_estado" id="incidencia_no" value="N" <?php echo $incidencia_estado == 'N' ? 'checked' : "" ?>>
                                                                <label class="form-check-label" for="incidencia_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="">
                                                    <label for="fecha_retenido"> <strong>Fecha de retencion:</strong> </label>
                                                    <input type="date" class="form-control" id="modal_fecha_retenido" name="modal_fecha_retenido" value="<?php echo $fecha_retenido ?>">
                                                </div>
                                                <br>
                                                <div class="">
                                                    <label for="fecha_retenido"><strong>Fecha de liberación:</strong></label>
                                                    <input type="date" class="form-control" id="modal_fecha_liberacion" name="modal_fecha_liberacion" value="<?php echo $fecha_retenido ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row py-3">
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <textarea class="form-control" placeholder="Escribe una observación aquí" id="observacion"><?php echo $observacion; ?></textarea>
                                                    <label for="observacion">Observación:</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" id="btnGuardarEstatus" onclick="GuardarEstatus()">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN MODAL DETALLE RETENIDOS -->

                        <!-- MODAL FACTURAS -->
                        <div class="modal fade" id="modalFacturas" tabindex="-1" z-index="2" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class=" modal-dialog ">
                                <div class="modal-content text-center">
                                    <div class="modal-header   bg-primary   text-white  text-center ">
                                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">ASOCIAR FACTURAS</label>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body modal-dialog-scrollable">
                                        <form action="../include/guardar_factura.php">
                                            <label class="display-5">Facturas</label><br><br>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="blockquote-footer text-center"># Pedimento</label><br>
                                                    <label class="blockquote-footer text-center"># Contenedor</label><br>
                                                </div>
                                                <div class="col">
                                                    <label class="blockquote-footer text-center"># Económico</label><br>
                                                    <label class="blockquote-footer text-center"># BL</label><br>
                                                </div>
                                            </div>
                                            <label class="align-text-bottom form-label">Buscar Factura</label>
                                            <input type="text" class="align-text-bottom form-control" name="num_factura" id="num_factura" placeholder="Introduce # factura">
                                            <label class="align-text-bottom form-label">Descripción de Factura</label>
                                            <input type="text" class="align-text-bottom form-control" name="concepto" id="concepto" placeholder="Introduce Concepto" disabled>
                                            <label class="align-text-bottom form-label">Fecha de Factura</label>
                                            <input type="date" class="align-text-bottom form-control" name="fecha_factura" id="fecha_factura" disabled>
                                            <label class="align-text-bottom form-label">Proveedor</label><br>
                                            <select class="align-text-bottom form-control" name="proveedor_factura" id="proveedor_factura" disabled>
                                                <option value="">Selecciona una opción</option>
                                                <?php
                                                foreach ($result_cat_proveedor as $valores) :
                                                    echo '<option value="' . $valores["proveedor"] . '">' . $valores["proveedor"] . '</option>';
                                                endforeach; ?>
                                            </select>
                                            <label class="align-text-bottom form-label">Valor de Factura</label>
                                            <input type="text" class="align-text-bottom form-control" name="val_factura" id="val_factura" placeholder="Introduce Valor 00.00" disabled>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="align-text-bottom form-label">Asociar Contenedor</label>
                                                    <input type="text" class="align-text-bottom form-control" name="contenedor_asociado" id="contenedor_asociado" placeholder="">
                                                </div>
                                                <div class="col">
                                                    <label class="align-text-bottom form-label">Asociar BL</label>
                                                    <input type="text" class="align-text-bottom form-control" name="bl_asociado" id="bl_asociado" placeholder="">
                                                </div>
                                            </div>
                                            <label class="align-text-bottom form-label">Referencia Nexen</label>
                                            <input type="text" class="align-text-bottom form-control" placeholder="">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Imprimir</button>
                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Buscar</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="button" class="btn btn-success">Asociar Factura</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="embed-responsive">
                                        <div class="container-fluid">
                                            <table class="table w-100 p-3">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"># FACTURA</th>
                                                        <th scope="col">CONCEPTO</th>
                                                        <th scope="col">VALOR</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>MarkDSADSAD</td>
                                                        <td>MarkDSADSAD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN MODAL FACTURAS -->

                        <!-- EJEMPLO MODAL CARGAR FACTURA Y ASOCIAR (MODAL DOBLE) -->
                        <div class="modal fade" id="modalCargarFacturas" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-xl text-center">
                                <div class="modal-content text-center">
                                    <div class="modal-body modal-dialog-scrollable">
                                        <div class="form-table-consulta">
                                            <h1>CARGAR FACTURAS</h1>
                                        </div>
                                        <!--<form action="../include/guardar_factura.php" method="POST">-->
                                        <!-- <div class="form-tabla-contenedor">  -->
                                        <div class="row my-1">
                                            <div class="col-3">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_proveedor">
                                                    Agregar Proveedor +
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row my-1">
                                            <div class="col-sm-6">
                                                <h6 class="text-center">Proveedor</h6>
                                                <div class="selectprueba">
                                                    <select class="align-text-bottom form-control selectpicker validSelect" data-show-subtext="true" data-live-search="true" id="proveedor_fact" name="proveedor_fact" onchange="cambiarProveedor(this);">
                                                        <option value="" selected disabled>Selecciona una opción</option>
                                                        <?php
                                                        foreach ($result_cat_proveedor as $valores) :
                                                            echo '<option domicilio="' . $valores['domicilio'] . '" taxid="' . $valores["codigo"] . '" value="' . $valores["proveedor"] . '">' . $valores["proveedor"] . '</option>';
                                                            $taxid = $valores["codigo"];
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <h6 class="text-center">Editar proveedor</h6>
                                                <button id="btnEditarProveedor" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modal_editar_proveedor" disabled><i class="bi bi-pen"></i></button>
                                            </div>
                                            <div class="col-sm-4 ">
                                                <h6 class="text-center">País de origen</h6>
                                                <input type="text" class="align-text-bottom form-control validText" id="modal_pais_origen" oninput="quitarAcentosYComillas(this)" name="num_factura" placeholder="Introduce país de origen" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="text-center">Domicilio proveedor</h6>
                                                <input type="text" class="align-text-bottom form-control" id="modal_domicilio_proveedor" name="modal_domicilio_proveedor" placeholder="Introduce proveedor" required disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row my-1">
                                            <div class="col-sm-4">
                                                <h6 class="text-center">Número de Factura</h6>
                                                <input type="text" class="align-text-bottom form-control validFactura" id="modal_num_factura" oninput="quitarAcentosYComillas(this)" name="num_factura" placeholder="Introduce # factura" required>
                                            </div>
                                            <div class="col-sm-4 validando">
                                                <h6 class="text-center">Fecha de Factura</h6>
                                                <input type="date" class="align-text-bottom form-control validDate" id="modal_fecha_factura" name="fecha_factura" required value="">
                                            </div>
                                            <div class="col-sm-2 ">
                                                <h6 class="text-center">TAX-ID</h6>
                                                <input type="text" class="align-text-bottom form-control" name="tax_id" id="tax_id" value="" required disabled placeholder="Escoger proveedor">
                                            </div>
                                            <div class="col-sm-2">
                                                <h6 class="text-center">Incoterms</h6>
                                                <select class="form-control" id="incoterms" name="incoterms">
                                                    <?php
                                                    try {
                                                        // Consulta los datos de la tabla medidas
                                                        $stmt = $conn_bd->prepare("SELECT TOP (1000) * FROM [dbo].[Catalogo_Factura_Incoterms] ORDER BY [Descripcion] ASC;");
                                                        $stmt->execute();

                                                        // Muestra las opciones del select con los valores obtenidos
                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value='" . $row['Descripcion'] . "'>" . $row['Descripcion'] . "</option>";
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo "Error: " . $e->getMessage();
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row my-1">
                                            <div class="col-sm-8">
                                                <h6 class="text-center">Operador</h6>
                                                <input type="text" class="form-control" value="" id="modal_nombre_operador" name="modal_nombre_operador" disabled>
                                            </div>
                                            <div class="col-sm-4">
                                                <h6 class="text-center">RFC</h6>
                                                <input type="text" class="align-text-bottom form-control" id="modal_rfc_operador" name="modal_rfc_operador" placeholder="" disabled value="">
                                            </div>
                                        </div>
                                        <div class="row my-1">
                                            <div class="col">
                                                <h6 class="text-center">Domicilio Fiscal Operador</h6>
                                                <input type="text" class="align-text-bottom form-control" id="modal_domicilio_operador" name="modal_domicilio_operador" placeholder="" disabled value="">
                                            </div>
                                        </div>
                                        <div class="partidas">
                                            <div class="row my-1">
                                                <div class="col-sm-6 validando">
                                                    <h6 class="text-center">Descripción Cove (Español)</h6>
                                                    <textarea rows="" class="align-text-bottom form-control validDescription" id="desc_factura" oninput="quitarAcentosYComillas(this)" name="desc_factura" cols="" required></textarea>
                                                    <!-- <input type="tex"  class="align-text-bottom form-control"> -->
                                                </div>
                                                <div class="col-sm-6 validando">
                                                    <h6 class="text-center">Cove Description (English)</h6>
                                                    <textarea rows="" class="align-text-bottom form-control validDescription" id="desc_factura_i" oninput="quitarAcentosYComillas(this)" name="desc_factura_i" cols="" required></textarea>
                                                    <!-- <input type="tex"  class="align-text-bottom form-control"> -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3 validando">
                                                    <h6 class="text-center">Cantidad</h6>
                                                    <input type="text" class="align-text-bottom form-control validNumber" id="modal_cantidad" name="modal_valor_factura" placeholder="Introduce cantidad" pattern="^[0-9]+">
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Medida</h6>
                                                    <select class="form-control" id="medida" name="medida">
                                                        <?php
                                                        try {
                                                            // Consulta los datos de la tabla medidas
                                                            $stmt = $conn_bd->prepare("SELECT TOP (1000) [Id_medida], [Medida], [Estatus] FROM [dbo].[medidas]");
                                                            $stmt->execute();

                                                            // Muestra las opciones del select con los valores obtenidos
                                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='" . $row['Medida'] . "'>" . $row['Medida'] . "</option>";
                                                            }
                                                        } catch (PDOException $e) {
                                                            echo "Error: " . $e->getMessage();
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <h6 class="text-center">Precio Unitario</h6>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control validNumber" id="precio_unitario" name="precio_unitario" placeholder="Introduce el Precio Unitario 00.00" disabled>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary decrease-btn" type="button">-</button>
                                                            <button class="btn btn-outline-secondary increase-btn" type="button">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Moneda</h6>
                                                    <select class="form-control" id="modal_moneda" name="modal_moneda">
                                                        <?php
                                                        try {
                                                            // Consulta los datos de la tabla medidas
                                                            $stmt = $conn_bd->prepare("SELECT TOP (1000) * FROM [dbo].[MONEDAS]");
                                                            $stmt->execute();

                                                            // Muestra las opciones del select con los valores obtenidos
                                                            while ($row2 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='" . $row2['PREFIJO'] . "'>" . $row2['PREFIJO'] . "</option>";
                                                            }
                                                        } catch (PDOException $e) {
                                                            echo "Error: " . $e->getMessage();
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Precio Total</h6>
                                                    <input type="text" class="align-text-bottom form-control validNumber" id="precio_total" name="precio_total" placeholder="Total">
                                                </div>
                                            </div>
                                            <div class="row pt-2 pb-2 justify-content-end">
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Mark</h6>
                                                    <input type="text" class="align-text-bottom form-control validMark" id="modal_mark" oninput="quitarAcentosYComillas(this)" name="modal_mark" placeholder="Mark N/M">
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Peso Bruto</h6>
                                                    <input type="text" min="0" class="align-text-bottom form-control validNumber" id="modal_peso_bruto" name="peso_bruto" placeholder="Bruto">
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Peso Neto</h6>
                                                    <input type="text" min="0" class="align-text-bottom form-control validNumber" id="modal_peso_neto" name="peso_neto" placeholder="Neto">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- </div> -->
                                        <div class="row my-1">
                                            <div class="col-sm-3 "></div>
                                            <div class="col-sm-6 text-center">
                                                <button type="button" class="btn btn-primary" id="btnAgregar">Agregar</button>
                                            </div>
                                            <div class="col-sm-3 "></div>
                                        </div>
                                        <br>
                                        <div class="embed-responsive card">
                                            <div class="container table-responsive card-body">
                                                <table class="table w-100 p-3 table-responsive table-scrollable table-striped table-hover" id="tablaFacturas">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"># FACTURA</th>
                                                            <th scope="col">DESCRIPCIÓN COVE</th>
                                                            <th scope="col">COVE DESCRIPTION</th>
                                                            <th scope="col">CANTIDAD</th>
                                                            <th scope="col">UNIDAD</th>
                                                            <th scope="col">VALOR UNITARIO</th>
                                                            <th scope="col">MONEDA</th>
                                                            <th scope="col">TOTAL PARTIDA</th>
                                                            <th scope="col">PESO BRUTO</th>
                                                            <th scope="col">PESO NETO</th>
                                                            <th scope="col">MARK</th>
                                                            <th scope="col">ACCION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Aquí se agregarán las filas dinámicamente -->
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>

                                                            <th scope="col">Total:</th>
                                                            <th scope="row"></th>
                                                            <th scope="col">
                                                                <span id="total">0</span>
                                                            </th>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>
                                                            <th scope="col" id="total_peso_bruto" value="0">0</th>
                                                            <th scope="col" id="total_peso_neto" value="0">0</th>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div><br><br>
                                    <div class="modal-footer ">
                                        <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                        <button id="btnGuardarFacturas" type="button" class="btn btn-lg btn-success" onclick="enviarSolicitudAjax()">
                                            <span id="spinner_insert" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                                <!--</form>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                <div class=" modal-dialog ">
                    <div class="modal-content text-center">
                        <div class="modal-header   bg-primary   text-white  text-center ">
                            <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">ASOCIAR FACTURAS</label>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-dialog-scrollable">
                            <form action="include/guardar_factura.php">
                                <label class="display-5">Facturas</label><br><br>
                                <div class="row">
                                    <div class="col">
                                        <label class="blockquote-footer text-center"># Pedimento</label> <br>
                                        <label class="blockquote-footer text-center"># Contenedor</label> <br>
                                    </div>
                                    <div class="col">
                                        <label class="blockquote-footer text-center"># Económico</label> <br>
                                        <label class="blockquote-footer text-center"># BL</label> <br>
                                    </div>
                                </div>
                                <label class="align-text-bottom form-label">Buscar Factura</label>
                                <input type="text" class="align-text-bottom form-control" id="num_factura" name="num_factura" placeholder="Introduce # factura">
                                <label class="align-text-bottom form-label">Descripción de Factura</label>
                                <input type="text" class="align-text-bottom form-control" id="concepto" name="concepto" placeholder="Introduce Concepto" disabled>
                                <label class="align-text-bottom form-label">Fecha de Factura</label>
                                <input type="date" class="align-text-bottom form-control" id="fecha_factura" name="fecha_factura" disabled>
                                <label class="align-text-bottom form-label">Proveedor</label><br>
                                <select class="align-text-bottom form-control" id="proveedor_factura" name="proveedor_factura" disabled>
                                    <option value="">Selecciona una opción</option>
                                    <?php
                                    foreach ($result_cat_proveedor as $valores) :
                                        echo '<option value="' . $valores["proveedor"] . '">' . $valores["proveedor"] . '</option>';
                                    endforeach; ?>
                                </select>
                                <label class="align-text-bottom form-label">Valor de Factura</label>
                                <input type="text" class="align-text-bottom form-control" id="valor_factura" name="valor_factura" placeholder="Introduce Valor 00.00" disabled>
                                <label class="align-text-bottom form-label">Asociar Contenedor</label>
                                <input type="text" class="align-text-bottom form-control" id="contenedor_asociado" name="contenedor_asociado" placeholder="">
                                <label class="align-text-bottom form-label">Asociar BL</label>
                                <input type="text" class="align-text-bottom form-control" id="bl_asociado" name="bl_asociado" placeholder="">
                                <label class="align-text-bottom form-label">Referencia Nexen</label>
                                <input type="text" class="align-text-bottom form-control" id="referencia_nexen_o" name="referencia_nexen" placeholder="">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Imprimir</button>
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Buscar</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-success">Asociar Factura</button>
                                </div>
                            </form>
                        </div>
                        <div class="embed-responsive">
                            <div class="container-fluid">
                                <table class="table w-100 p-3">
                                    <thead>
                                        <tr>
                                            <th scope="col"># FACTURA</th>
                                            <th scope="col">CONCEPTO</th>
                                            <th scope="col">VALOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>MarkDSADSAD</td>
                                            <td>MarkDSADSAD</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FIN EJEMPLO MODAL DOBLE -->

<!--MODAL VER FACTURAS Y DETALLE FACTURAS (MODAL DOBLE)-->
<!--MODAL VER FACTURAS Y DETALLE FACTURAS (MODAL DOBLE)-->
<div class="container mt-3 text-center" style="max-width: 98%;">
    <div class="row">
        <div class="modal fade" id="modalVerFacturas" tabindex="-1" aria-labelledby="modalVerFacturasLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header nft">
                        <h5 class="modal-title" id="modalVerFacturasLabel">Ver Facturas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body table-responsive" style="max-height: 80vh; ">
                        <!-- Aquí va el contenido de la tabla -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



            <div class="modal fade" id="modalVerDetalleFacturas" tabindex="-1" aria-labelledby="modalVerDetalleFacturasLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalVerDetalleFacturasLabel">Ver Detalle Facturas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body table-responsive">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--TERMINA MODAL VER FACTURAS Y DETALLE FACTURAS (MODAL DOBLE)-->

            <!--MODAL EDITAR FACTURAS -->
            <div class="modal fade p-5" id="modalEditarFacturas" tabindex="-1" aria-labelledby="modalEditarFacturas" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header nft">
                <h5 class="modal-title" id="modalEditarFacturasLabel">Editar Facturas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <div class="row my-1">
                    <div class="col-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_proveedor">
                            Agregar Proveedor +
                        </button>
                    </div>
                </div>
                <div class="form_facturas_edit">
                    <div class="row my-1">
                       <div class="col-sm-8  validando">
                            <h6 class="text-center">Proveedor</h6>
                            <select class="align-text-bottom form-control deti validSelect" id="proveedor_fact_edit" name="proveedor_fact_edit" oninput="" onchange="cambiarProveedorEdit(this); ">
                                <option value="">Selecciona una opción</option>
                                <?php
                                foreach ($result_cat_proveedor as $valores) :
                                    echo '<option domicilio="' . $valores['domicilio'] . '" taxid="' . $valores["codigo"] . '" value="' . $valores["proveedor"] . '">' . $valores["proveedor"] . '</option>';
                                    $taxid = $valores["codigo"];
                                endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4  validando">
                            <h6 class="text-center">País de origen</h6>
                            <input type="text" class="align-text-bottom form-control validText" id="modal_pais_origen_edit" oninput="quitarAcentosYComillas(this)" name="modal_pais_origen_edit" placeholder="Introduce país de origen" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 validando">
                            <h6 class="text-center">Domicilio proveedor</h6>
                            <input value="" type="text" class="align-text-bottom form-control" id="modal_domicilio_proveedor_edit" name="modal_domicilio_proveedor" placeholder="Introduce proveedor" required disabled>
                        </div>
                    </div>
                    <br>
                    <div class="row my-1">
                        <div class="col-sm-4 validando">
                            <h6 class="text-center">Número de Factura</h6>
                            <input type="text" class="align-text-bottom form-control" id="modal_num_factura_edit" name="num_factura" placeholder="Introduce # factura" required disabled>
                        </div>
                        <div class="col-sm-4 validando">
                            <h6 class="text-center">Fecha de Factura</h6>
                            <input type="date" class="align-text-bottom form-control validDate" id="modal_fecha_factura_edit" name="fecha_factura" required value="">
                        </div>
                        <div class="col-sm-2 ">
                            <h6 class="text-center">TAX-ID</h6>
                            <input type="text" class="align-text-bottom form-control" name="tax_id" id="tax_id_edit" value="" required disabled placeholder="Escoger proveedor">
                        </div>
                        <div class="col-sm-2">
                            <h6 class="text-center">Incoterms</h6>
                            <select class="form-control" id="incoterms_edit" name="incoterms">
                                <?php
                                try {
                                    // Consulta los datos de la tabla medidas
                                    $stmt = $conn_bd->prepare("SELECT TOP (1000) * FROM [dbo].[Catalogo_Factura_Incoterms] ORDER BY [Descripcion] ASC;");
                                    $stmt->execute();

                                    // Muestra las opciones del select con los valores obtenidos
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row['Descripcion'] . "'>" . $row['Descripcion'] . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                    <div class="row my-1">
                        <div class="col-sm-8">
                            <h6 class="text-center">Operador</h6>
                            <input type="text" class="form-control" value="" id="modal_nombre_operador_edit" name="modal_nombre_operador" disabled>
                        </div>
                        <div class="col-sm-4">
                            <h6 class="text-center">RFC</h6>
                            <input type="text" class="align-text-bottom form-control" id="modal_rfc_operador_edit" name="modal_rfc_operador" placeholder="" disabled value="">
                        </div>
                    </div>
                    <div class="row my-1">
                        <div class="col">
                            <h6 class="text-center">Domicilio Fiscal Operador</h6>
                            <input type="text" class="align-text-bottom form-control" oninput="quitarAcentosYComillas(this)" id="modal_domicilio_operador_edit" name="modal_domicilio_operador" placeholder="" disabled value="">
                        </div>
                    </div>
                </div>
                <div class="partidas">
                    <div class="row my-1">
                        <div class="col-sm-6">
                            <h6 class="text-center">Descripción Cove (Español)</h6>
                            <textarea rows="" class="align-text-bottom form-control validDescription" oninput="quitarAcentosYComillas(this)" id="desc_factura_edit" name="desc_factura" cols="" required></textarea>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="text-center">Cove Description (English)</h6>
                            <textarea rows="" class="align-text-bottom form-control validDescription" oninput="quitarAcentosYComillas(this)" id="desc_factura_i_edit" name="desc_factura_i" cols="" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 ">
                            <h6 class="text-center">Cantidad</h6>
                            <input type="text" class="align-text-bottom form-control validNumber" oninput="quitarAcentosYComillas(this)" id="modal_cantidad_edit" name="modal_valor_factura" placeholder="Introduce cantidad" pattern="^[0-9]+">
                        </div>
                        <div class="col-sm-2">
                            <h6 class="text-center">Medida</h6>
                            <select class="form-control" id="medida_edit" name="medida">
                                <?php
                                try {
                                    // Consulta los datos de la tabla medidas
                                    $stmt = $conn_bd->prepare("SELECT TOP (1000) [Id_medida], [Medida], [Estatus] FROM [dbo].[medidas]");
                                    $stmt->execute();

                                    // Muestra las opciones del select con los valores obtenidos
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row['Medida'] . "'>" . $row['Medida'] . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3 ">
                            <h6 class="text-center">Precio Unitario</h6>
                            <input type="text" class="align-text-bottom form-control" oninput="quitarAcentosYComillas(this)" id="precio_unitario_edit" name="precio_unitario" placeholder="Introduce el Precio Unitario 00.00" disabled>
                        </div>
                        <div class="col-sm-2 ">
                            <h6 class="text-center">Moneda</h6>
                            <select class="form-control" id="modal_moneda_edit" name="modal_moneda_edit">
                               
                            </select>
                        </div>
                        <div class="col-sm-2 ">
                            <h6 class="text-center">Precio Total</h6>
                            <input type="text" class="align-text-bottom form-control validNumber" oninput="quitarAcentosYComillas(this)" id="precio_total_edit" name="precio_total" placeholder="Total">
                        </div>
                    </div>
                    <div class="row pt-2 pb-2 justify-content-end">
                        <div class="col-sm-2">
                            <h6 class="text-center">Mark</h6>
                            <input type="text" class="align-text-bottom form-control validMark" oninput="quitarAcentosYComillas(this)" id="modal_mark_edit" name="modal_mark" placeholder="Mark N/M">
                        </div>
                        <div class="col-sm-2">
                            <h6 class="text-center">Peso Bruto</h6>
                            <input type="text" min="0" class="align-text-bottom form-control validNumber" oninput="quitarAcentosYComillas(this)" id="modal_peso_bruto_edit" name="peso_bruto" placeholder="Bruto">
                        </div>
                        <div class="col-sm-2">
                            <h6 class="text-center">Peso Neto</h6>
                            <input type="text" min="0" class="align-text-bottom form-control validNumber" oninput="quitarAcentosYComillas(this)" id="modal_peso_neto_edit" name="peso_neto" placeholder="Neto">
                        </div>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-sm-3 "></div>
                    <div class="col-sm-6 text-center">
                        <button type="button" class="btn btn-primary" id="btnAgregar_edit">Agregar</button>
                    </div>
                    <div class="col-sm-3 "></div>
                </div>
                <br>
                <div class="embed-responsive card">
    <div class="container-fluid tabla-partidas card-body">
        <table id="tablaPartidasEditar" class="table table-hover stripe">
            <thead>
                <tr>
                    <th>Numero Partida</th>
                    <th>Descripcion Cove</th>
                    <th>Cove Description</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Valor Unitario</th>
                    <th>Total</th>
                    <th>Moneda</th>
                    <th>Peso Bruto</th>
                    <th>Peso Neto</th>
                    <th>Mark</th>
                    <th>Borrar partida</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se cargarán las filas de datos dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

            </div>
            <br><br>
            <div class="modal-footer ">
                <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnEditarFacturas" type="button" class="btn btn-lg btn-warning" onclick="updateFacturasEdit()">
                    <span id="spinner_edit" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Editar
                </button>
            </div>
        </div>
    </div>
</div>

        </div>
        </div>
        <!--TERMINA MODAL EDITAR FACTURAS -->

        <!--EMPIEZA MODAL CONFIRMACION BORRAR FACTURA Y PARTIDAS-->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmación de borrado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Recuerda que al borrar la factura también borrarás sus partidas.</p>
                <h4>Número de factura a borrar:</h4>
                <h3 id="borrarNumFactura"></h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Borrar</button>
            </div>
        </div>
    </div>
</div>

        <!--TEMRINA MODAL CONFIRMACION BORRAR FACTURA Y PARTIDAS-->

        <!-- MODAL PROVEEDOR -->
        <div class="modal fade" id="modal_proveedor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">PROVEEDORES</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_proveedor.php" class="form-control" method="POST">
                            <label class="display-5">Proveedor</label><br><br>
                            <label class="align-text-bottom form-label">Tax-ID</label>
                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z0-9-]+" onblur="validarTextNumber(this)" id="tax_id" name="tax_id" placeholder="Introduce Código TAX-ID" required>
                            <label class="align-text-bottom form-label">Proveedor</label>
                            <input type="text" class="align-text-bottom  form-control" oninput="quitarComillas(this)" id="proveedor" name="proveedor" placeholder="Introduce Nombre Proveedor" required>
                            <label class="align-text-bottom form-label">Domicilio</label>
                            <input type="text" class="align-text-bottom  form-control" oninput="quitarComillas(this)" id="domicilio" name="domicilio" placeholder=" Introduce Domicilio " required>
                            <label class="align-text-bottom form-label">Correo</label>
                            <input type="email" class="align-text-bottom  form-control" id="email" name="email" placeholder="Introduce Correo">
                            <label class="align-text-bottom form-label">Whatsapp</label>
                            <input type="text" class="align-text-bottom  form-control" pattern="\d+" onblur="validarNumber(this)" id="whatsapp" name="whatsapp" placeholder="Introduce Número Whatsapp">
                            <label class="align-text-bottom form-label">Contraseña </label>
                            <input type="password" class="align-text-bottom  form-control" id="pass" name="pass" placeholder="Introduce la contraseña de Supervisor" required>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" id="guardar_proveedor" class="btn btn-success">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL PROVEEDOR -->

        <!-- MODAL EDITAR PROVEEDOR -->
        <div class="modal fade" id="modal_editar_proveedor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">EDITAR PROVEEDORES</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">

                        <label class="display-5">Proveedor</label><br><br>
                        <input type="hidden" class="align-text-bottom  form-control" pattern="[a-zA-Z0-9-]+" onblur="validarTextNumber(this)" id="editar_tax_id" name="editar_tax_id" required disabled>
                        <label class="align-text-bottom form-label">Tax-ID</label>
                        <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z0-9-]+" onblur="validarTextNumber(this)" id="new_tax_id" name="new_tax_id" placeholder="Introduce Código TAX-ID" required>
                        <label class="align-text-bottom form-label mt-3">Proveedor</label>
                        <input type="text" class="align-text-bottom  form-control" oninput="quitarComillas(this)" id="editar_proveedor" name="editar_proveedor" placeholder="Introduce Nombre Proveedor" required>
                        <label class="align-text-bottom form-label mt-3">Domicilio</label>
                        <input type="text" class="align-text-bottom  form-control" oninput="quitarComillas(this)" id="editar_domicilio" name="editar_domicilio" placeholder=" Introduce Domicilio " required>
                        <label class="align-text-bottom form-label mt-3">Correo</label>
                        <input type="email" class="align-text-bottom  form-control" id="editar_email" name="editar_email" placeholder="Introduce Correo">
                        <label class="align-text-bottom form-label mt-3">Whatsapp</label>
                        <input type="text" class="align-text-bottom  form-control" pattern="\d+" onblur="validarNumber(this)" id="editar_whatsapp" name="editar_whatsapp" placeholder="Introduce Número Whatsapp">
                        <label class="align-text-bottom form-label mt-3">Contraseña </label>
                        <input type="password" class="align-text-bottom  form-control" id="editar_pass" name="editar_pass" placeholder="Introduce la contraseña de Supervisor" required>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" id="borrar_proveedor" onclick="deleteProveedor()" class="btn btn-danger">Borrar Proveedor</button>
                            <button type="button" id="editar_proveedor" onclick="updateProveedor()" class="btn btn-warning">Editar Proveedor</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL EDITAR PROVEEDOR -->

        <!-- MODAL TIPO TRAFICO -->
        <div class="modal fade" id="modal-tipo-trafico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel"> Catalago Tipo Tráfico</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="include/guardar_tipo_trafico.php">
                            <label class="display-5"> Tipo Tráfico</label><br><br>
                            <label class="align-text-bottom form-label">Trafico Operación</label>
                            <input type="text" class="align-text-bottom  form-control" id="tipo_trafico" name="tipo_trafico" placeholder="Introduce  El Tipo de Tráfico" required>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-success">Guardar Cambios</button>
                                <button type="button" class="btn btn-success">Actualizar</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
                        <div class="container-fluid">
                            <table class="table w-100 p-3">
                                <thead>
                                    <tr>
                                        <th scope="col"># FACTURA</th>
                                        <th scope="col">CONCEPTO</th>
                                        <th scope="col">VALOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>MarkDSADSAD</td>
                                        <td>MarkDSADSAD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL TIPO TRAFICO -->

        <!--INICIA MODAL BORRAR OPERACION GLOBAL-->
        <!-- Modal Delete Operacion -->
        <div class="modal fade" id="modal_delete_operacion" tabindex="-1" role="dialog" aria-labelledby="modal_delete_operacion_label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom-modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_delete_operacion_label">Borrar Operación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <div class="custom-modal-content-container">
                            <div class="text-center py-3">
                                <h3>¿Estás seguro que deseas borrar la operación?</h3>
                                <br>
                                <h3 id="ref_nex_get"></h3>
                            </div>
                            <div class="form-group mb-3 pb-1 pt-3">
                                <label for="user_supervisor pb-2">Usuario supervisor:</label>
                                <input type="text" class="form-control" id="user_supervisor" name="user_supervisor" placeholder="Ingresa el usuario del supervisor">
                            </div>
                            <div class="form-group mb-3 pb-3">
                                <label for="pass_supervisor pb-2">Contraseña de supervisor:</label>
                                <input type="password" class="form-control" id="pass_supervisor" name="pass_supervisor" placeholder="Ingresa la contraseña, supervisor">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Contenido del modal -->
                        <div id="loadingIndicator" class="custom-modal-loading-overlay d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                        <button id="btnBorrarOperacion" class="btn btn-danger" onclick="verificarContraseñaOperacion()">Si, borrar.</button>
                    </div>
                </div>
            </div>
        </div>
        <!--TERMINA MODAL BORRAR OPERACION GLOBAL-->




        <!-- MMODAL MONEDA -->
        <div class="modal fade" id="modal-moneda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header bg-primary text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">CATALOGO MONEDA</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../include/guardar_moneda.php" method="POST" class="form-control">
                        <label class="display-6">Moneda</label>
                        <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+" onblur="validarText(this)" id="moneda" name="moneda" placeholder="Introduce Descripción Moneda" required>
                        <label class="display-6">Prefijo</label>
                        <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+" onblur="validarText(this)" id="prefijo" name="prefijo" placeholder="Introduce Prefijo" required>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" id="guardar_moneda">Guardar Cambios</button>
                        </div>
                    </form>
                    <div class="embed-responsive">
                        <div class="container-fluid">
                            <table class="table w-100 p-3">
                                <thead>
                                    <tr>
                                        <th scope="col"># FACTURA</th>
                                        <th scope="col">CONCEPTO</th>
                                        <th scope="col">VALOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>MarkDSADSAD</td>
                                        <td>MarkDSADSAD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FIN MODAL MONEDA -->


        <!-- modal rectificaion -->

        <div class="modal fade" id="modal_Rectificacion" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white  text-center">
                        <label class="modal-title text-wrap" style="width: 50rem;" id="exampleModalLabel">Rectificacion
                        </label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formArchivo" method="post" action="Rectificacion.php" enctype="multipart/form-data" class="form-control">
                            <div class="row" style="padding: 10px;">
                                <input type="hidden" id="usuario" name="usuario" value="<?php echo ($_SESSION['usuario_nexen']); ?>">
                                <input type="hidden" id="referencia_nexen_rectifica" name="referencia_nexen_rectifica" value="<?php echo $referencia_nexen ?>">
                                <div class="row my-1" style="padding: 10px;">
                                    <div class="col-sm-4">
                                        <h6 class="text-center">Referencia Nexen</h6>
                                        <input class="form-control" id="referencia_nexen_rectifica" name="referencia_nexen_rectifica" type="text" placeholder="Referencia Nexen" value="<?php echo $referencia_nexen ?>" disabled>
                                    </div>
                                    <div class="col-sm-4 ">
                                        <h6 class="text-center">Numero Pedimento</h6>
                                        <input class="form-control" type="text" id="num_pedimento" name="num_pedimento" oninput="quitarComillas(this)" maxlength="15" placeholder="Pedimento 7 Digitos" class="form-control" autocomplete="off" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1" value="<?php echo isset($num_pedimento) && !empty($num_pedimento) ?  $num_pedimento : "" ?>">
                                    </div>
                                    <div class="col-sm-4 ">
                                        <h6 class="text-center">Clave Pedimento</h6>
                                        <select class="form-control selectpicker" id="cve_pedimento" name="cve_pedimento">
                                            <?php
                                            foreach ($result_clv_pedimento as $valores) :
                                                if ($valores['clave'] == 'R1' || $valores['clave'] == 'G9' || $valores['clave'] == 'V1') {
                                                    echo '<option value="' . $valores["clave"] . '">' . $valores["clave"] . '</option>';
                                                }
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="col-sm-12" style="padding: 10px;">
                                        <h6 class="text-center">Detalle de Rectificación</h6>
                                        <textarea rows="" class="align-text-bottom form-control valid validText" id="rectificacion" oninput="quitarComillas(this)" name="rectificacion" cols="" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding: 10px; margin-left:40%;">
                                    <button class="btn btn-success" id="guardar_rectificaicon" type="submit">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal_Historial_Rectificacion" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white  text-center">
                        <label class="modal-title text-wrap" style="width: 50rem;" id="exampleModalLabel">Historial Rectificacion
                        </label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table id="data_table" class="table table-bordered table-responsive-sm  text-center scroll" style="font-size: 12px">
                            <thead class="bg-success">
                                <tr>
                                    <th>USUARIO</th>
                                    <th>NUMERO PEDIMENTO</th>
                                    <th>CLAVE PEDIMENTO</th>
                                    <th>FECHA OPERACION</th>
                                    <th>HORA DE OPERACION</th>
                                    <th>DESCRIPCION</th>
                                    <th>ESTADO</th>
                                    <th>REFERENCIA</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $select_hist_operacion  = "SELECT * FROM [dbo].[FK_RECTIFICADO]  WHERE REFERENCIA_NEXEN = '$referencia_nexen '";
                                $histo_ope = $conn_bd->prepare($select_hist_operacion);
                                $histo_ope->execute();
                                $results_hist_ope = $histo_ope->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($results_hist_ope as $operacion) : ?>
                                    <tr>
                                        <td class=""><?php echo $operacion['Usuario']; ?></td>
                                        <td class=""><?php echo $operacion['Numero_pedimento']; ?></td>
                                        <td class=""><?php echo $operacion['Clave_pedimento']; ?></td>
                                        <td class=""><?php echo $operacion['Fechope']; ?></td>
                                        <td class=""><?php echo $operacion['Horaope']; ?></td>
                                        <td class=""><?php echo $operacion['Descripcion']; ?></td>
                                        <td class=""><?php echo $operacion['Estatus']; ?></td>
                                        <td class=""><?php echo $operacion['Referencia_Nexen']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal rectificaion -->


        <!-- MODAL SOLICITUD DE PAGOS -->
        <div class="modal fade" id="modal_solicitud_pagos" tabindex="-1">
            <div class="modal-dialog modal-lg text-center">
                <div class="modal-content text-center">
                    <div class="modal-header bg-primary text-white  text-center">
                        <label class="modal-title text-wrap" style="width: 50rem;" id="exampleModalLabel">SOLICITUD DE PAGOS
                        </label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" id="SolicitudPago" enctype="multipart/form-data">
                        <div class="form-table-consulta">
                            <?php $usuario_sesion = $_SESSION['usuario_nexen']; ?>
                            <div class="row my-1">
                                <div class="col-sm-3">
                                    <label for="" class="titulos"> Referencia Nexen</label>
                                    <input class="form-control" id="referencia_nexen_sp" name="referencia_nexen_sp" type="text" placeholder="Referencia Nexen" value="<?php echo isset($referencia_nexen) && !empty($referencia_nexen) ?  $referencia_nexen : "" ?>" disabled>
                                    <input type="hidden" id="referencia_nexen_sp" name="referencia_nexen_sp" value="<?php echo $referencia_nexen ?>">
                                </div><br>
                                <div class="col-sm-6">

                                </div>
                                <div class="col-sm-3">
                                    <label for="">Usuario</label>
                                    <input type="text" class="form-control" value="<?php echo isset($usuario_sesion) && !empty($usuario_sesion) ?  $usuario_sesion : "" ?>" disabled>
                                </div>
                            </div>
                            <!-- DATOS GENERALES -->
                            <div class="row my-1">
                                <h4>Datos Generales</h4>
                                <div class="col-sm-12">
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" name="razon_social_" id="razon_social_" value="<?php echo isset($nombre_cliente) && !empty($nombre_cliente) ?  $nombre_cliente : "" ?>" placeholder="Razon Social" disabled>
                                        <label for="user">Cliente</label>
                                        <input type="hidden" id="razon_social_sp" name="razon_social_sp" value="<?php echo $nombre_cliente ?>">
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="operador_" name="operador_" value="<?php echo isset($impor_export) && !empty($impor_export) ?  $impor_export : "" ?>" placeholder="Operador" disabled>
                                        <label for="user">Operador</label>
                                        <input type="hidden" id="operador_sp" name="operador_sp" value="<?php echo $impor_export ?>">
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <?php if ($tipo_trafico == "CARRETERO") { ?>
                                            <input type="hidden" id="tipo_trafico_pago" name="tipo_trafico_pago" value="<?php echo $tipo_trafico ?>">
                                            <input type="text" name="num_eco_sol_pago" id="num_eco_sol_pago" class="align-text-bottom form-control" value="<?php echo isset($numero_economico) && !empty($numero_economico) ?  $numero_economico : ""  ?>" disabled>
                                            <label for="num_eco"> Número Ecónomico</label>
                                            <input type="hidden" id="num_eco_sol_pago_sp" name="num_eco_sol_pago_sp" value="<?php echo $numero_economico ?>">
                                        <?php   } else if ($tipo_trafico == "AEREO") { ?>
                                            <input type="hidden" id="tipo_trafico_pago" name="tipo_trafico_pago" value="<?php echo $tipo_trafico ?>">
                                            <input type="text" name="guia_sol_pago" id="guia_sol_pago" class="align-text-bottom form-control" value="<?php echo isset($bl) && !empty($bl) ?  $bl : ""  ?>" disabled>
                                            <label for="num_eco"> Guia</label>
                                            <input type="hidden" id="guia_sol_pago_sp" name="guia_sol_pago_sp" value="<?php echo $bl ?>">
                                        <?php  } else { ?>
                                            <input type="text" name="contenedor_sol_pago" id="contenedor_sol_pago" class="align-text-bottom form-control" value="<?php echo isset($contenedor1) && !empty($contenedor1) ?  $contenedor1 : ""  ?>" disabled>
                                            <input type="hidden" id="tipo_trafico_pago" name="tipo_trafico_pago" value="<?php echo $tipo_trafico ?>">
                                            <label for="num_eco"> Contenedor</label>
                                            <input type="hidden" id="contenedor_sol_pago_sp" name="contenedor_sol_pago_sp" value="<?php echo $contenedor1 ?>">
                                        <?php  } ?>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" value="<?php echo isset($num_pedimento) && !empty($num_pedimento) ?  $num_pedimento : "" ?>" disabled>
                                        <label for="user">No Pedimento</label>
                                        <input type="hidden" id="pedimento_sp" name="pedimento_sp" value="<?php echo $num_pedimento ?>">
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" value="<?php echo isset($tipo_trafico) && !empty($tipo_trafico) ?  $tipo_trafico : "" ?>" disabled>
                                        <label for="user">Tipo Trafico</label>
                                        <input type="hidden" id="trafico_sp" name="trafico_sp" value="<?php echo $tipo_trafico ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- INFORMACION DE PAGOS-->
                            <div class="row my-1">
                                <h4>Cuenta Destino</h4>
                                <div class="col-sm-12">
                                    <div class="form-floating  mb-3">
                                        <select class="align-text-bottom form-control" onchange="mostrar_datos_cuenta(this.value)" id="select_Cuenta">
                                            <?php
                                            $query_buscar_razon = "SELECT DISTINCT R.id_Movimiento,
                                                                              E.Razon_Social AS Razon_Social_Empresa,
                                                                              E.ID_EMPRESA,
                                                                              P.alias,
                                                                              P.Razon_Social,
                                                                              P.RFC,
                                                                              C.NOMBRE_BANCO,
                                                                              R.Cuenta,
                                                                              R.Clabe,
                                                                              R.SWT_ABBA,
                                                                              R.Banco_Intermediario,
                                                                              R.Domicilio_Completo,
                                                                              P.Ref_Proveedor
                                                                        FROM
                                                                            Razon_Bancos AS R INNER JOIN
                                                                            Proveedores_Cuentas AS p ON
                                                                            p.id_Razon=r.id_razon_social INNER JOIN
                                                                            FK_Operador_Razon AS O ON
                                                                            O.Id_Razon_Social=R.id_razon_social  AND  O.Id_Razon_Social=P.Id_Razon
                                                                            INNER JOIN EMPRESAS AS E ON
                                                                            E.ID_EMPRESA=O.Id_Operador INNER JOIN Catalogo_Bancos AS C
                                                                            ON C.ID_BANCO=R.Id_banco
                                                                            WHERE E.Razon_Social = '{$impor_export}'";
                                            print_r($query_buscar_razon);

                                            $buscar_razon = $conn_bd->prepare($query_buscar_razon);
                                            $buscar_razon->execute();
                                            $results_buscar_razon = $buscar_razon->fetchAll(PDO::FETCH_ASSOC);


                                            echo '<option value="">Selecciona una opción</option>';
                                            // foreach ($result_cuenta_destino as $valores):
                                            //     echo '<option value="'.$valores["Cuenta_Destino"].'">'.$valores["Cuenta_Destino"].' '.$valores["Banco"].'</option>';
                                            // endforeach;

                                            foreach ($results_buscar_razon as $razon_social) :
                                                echo '<option value="' . $razon_social["id_Movimiento"] . '">' . $razon_social["alias"] . ' ' . $razon_social["NOMBRE_BANCO"] . '</option>';
                                            endforeach;

                                            // echo '<option value="">Selecciona una opción</option>';


                                            // foreach ($result_cuenta_destino as $valores):
                                            //     echo '<option value="'.$valores["Cuenta_Destino"].'">'.$valores["Cuenta_Destino"].' '.$valores["Banco"].'</option>';
                                            // endforeach;
                                            ?>
                                        </select>
                                        <label for="">Cuenta Destino</label>
                                    </div>
                                    <div class="form-floating  mb-3">

                                        <input type="text" class="align-text-bottom form-control" id="razon_social_spc" name="razon_social_spc" disabled value="">
                                        <input type="hidden" class="align-text-bottom form-control" id="razon_social_pago" name="razon_social_pago" value="">
                                        <label for="">Razón Social</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="rfc_sp" name="rfc_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="rfc_sp_pago" name="rfc_sp_pago" value="">
                                        <label for="">RFC</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="banco_sp" name="banco_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="banco_sp_pago" name="banco_sp_pago" value="">
                                        <label for="">Banco</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="cuenta_sp" name="cuenta_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="cuenta_sp_pago" name="cuenta_sp_pago" value="">
                                        <label for="">Cuenta</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="clabe_sp" name="clabe_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="clabe_sp_pago" name="clabe_sp_pago" value="">
                                        <label for="">CLABE</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="abba_sp" name="abba_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="abba_sp_pago" name="abba_sp_pago">
                                        <label for="">SWIFT/ABA</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="banco_inter_sp" name="banco_inter_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="banco_inter_sp_pago" name="banco_inter_sp_pago">
                                        <label for="">Banco Intermediario</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="domicilio_sp" name="domicilio_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="domicilio_sp_pago" name="domicilio_sp_pago">
                                        <label for="">Domicilio Completo</label>
                                    </div>
                                    <div class="form-floating  mb-3">
                                        <input type="text" class="align-text-bottom form-control" id="Referencia_Proveedor" name="clabe_sp" disabled>
                                        <input type="hidden" class="align-text-bottom form-control" id="Referencia_Proveedor_pago" name="Referencia_Proveedor_pago" value="">
                                        <label for="">Referencia Proveedor</label>
                                    </div>
                                </div>
                            </div>
                            <!-- INFORMACION DE PAGOS-->
                            <div class="row my-1">
                                <h4>Información de Pago</h4>
                                <div class="col-sm-6">
                                    <div class="form-floating  mb-3">
                                        <select class="align-text-bottom form-control" id="concepto_sp" name="concepto_sp">
                                            <?php
                                            echo '<option value="">Selecciona una opción</option>';
                                            foreach ($result_catalogo_tipo_solicitud_pago as $valor) :
                                                echo '<option value="' . $valor["DESCRIPCION"] . '">' . $valor["DESCRIPCION"] . '</option>';
                                            endforeach; ?>
                                        </select>
                                        <label for="">Concepto</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating  mb-3">
                                        <textarea class="form-control " oninput="quitarComillas(this)" id="observaciones_sp" name="observaciones_sp" rows="3"></textarea>
                                        <label for="">Observaciones</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-1">
                                <div class="form-floating  mb-3">
                                    <!-- <input type="text"   oninput="quitarComillas(this)" onkeypress="numberSol(event)" class="align-text-bottom form-control " name="monto_sp" id="monto_sp"> -->
                                    <input type="text" id="monto_sp" name="monto_sp" class="align-text-bottom form-control " oninput="formatoMiles(this)">
                                    <label for="">Monto</label>
                                </div>
                                <div class="form-floating  mb-3">
                                    <select class="align-text-bottom form-control" id="moneda_sp" name="moneda_sp">
                                        <?php
                                        echo '<option value="">Selecciona una opción</option>';
                                        foreach ($result_catalogo_monedas as $valor) :
                                            echo '<option value="' . $valor["PREFIJO"] . '">' . $valor["PREFIJO"] . '</option>';
                                        endforeach; ?>
                                    </select>
                                    <label for="">Moneda</label>
                                </div>
                            </div>
                            <div class="row my-1" id="container-documento">
                                <div id="documento-input">
                                    <h4>Documento</h4>
                                    <div class="col-sm-12 "><br>
                                        <div class=" text-center">
                                            <h6 class="text-center">Selecciona el documento PDF que deseas cargar</h6>
                                            <input type="file" class="form-control" name="file" id="file" whidt="80%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-1">
                                <div class="col-sm-6 "><br>
                                    <div class=" text-center">
                                        <input class="form-check-input" type="radio" name="tipo_solicitud" id="anticipo" value="ANTICIPO">
                                        <h6>Anticipo</h6>
                                    </div>
                                </div>
                                <div class="col-sm-6 "><br>
                                    <div class=" text-center">
                                        <input class="form-check-input" type="radio" name="tipo_solicitud" id="financiado" value="FINANCIADO">
                                        <h6>Financiado</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-1">
                                <div class="custom-modal-content-container" id="permiso" style="display: none;">
                                    <div class="text-center py-3">
                                        <label for="supervisor pb-2">Usuario Supervisor:</label>
                                        <input type="text" class="form-control" id="supervisor_user" name="supervisor_user" placeholder="Ingresa el usuario, Supervisor">
                                    </div>
                                    <div class="form-group mb-3 py-3">
                                        <label for="pass_supervisor pb-2">Contraseña de supervisor:</label>
                                        <input type="password" class="form-control" id="pass_supervisor_sol" name="pass_supervisor_sol" placeholder="Ingresa la contraseña, supervisor">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col-sm-1 "></div>
                            <div class="col-sm-10 text-center">
                                <button type="button" id="cancelar_detalle_pago" class="btn btn-lg btn-danger">Cancelar</button>
                                <button type="button" class="btn btn-lg btn-warning" id="checkSolicitudPagos">Revisar</button>
                                <button type="submit" id="enviar_detalle_pago" class="btn btn-lg btn-success" disabled>Enviar</button>
                            </div>
                            <div class="col-sm-1 "></div>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
        <!-- FIN MODAL SOLICITUD DE PAGOS -->



        <!-- Modal Detalle de Pagos Referencia -->
        <script>
            let referenciaNexen = "<?php echo $referencia_nexen; ?>";
        </script>
        <div class="modal fade" id="modal_detalle_pagos_referencia" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal_detalle_pagos_referencia" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
                <div class="modal-content">
                    <div class="modal-header  text-white nft">
                        <h5 class="modal-title" id="staticBackdropLabel">DETALLE DE SOLICITUD DE PAGO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tablaDetallePagosReferencia" class="table-hover stripe w-100">
                                <thead>
                                    <th scope="col">REFERENCIA NEXEN</th>
                                    <th scope="col">CLIENTE</th>
                                    <th scope="col">OPERADOR</th>
                                    <th scope="col">CONCEPTO</th>
                                    <th scope="col">TIPO SOLICITUD</th>
                                    <th scope="col">MONTO</th>
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">ESTATUS</th>
                                    <th scope="col">FECHA</th>
                                    <th scope="col">FACTURAS</th>
                                </thead>
                                <tbody>
                                    <!-- Cuerpo de la tabla -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- TERMINA  Modal Detalle de Pagos Referencia -->


        <!-- Modal AUTORIZAR PAGO-->
        <div class="modal fade" id="modal_autorizar_pago" tabindex="-1" role="dialog" aria-labelledby="modal_autorizar_pago_label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom-modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_autorizar_pago_label">AUTORIZAR PAGO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <div class="custom-modal-content-container">
                            <div class="text-center py-3">
                                <h3>¿DESEAS AUTORIZAR ESTE PAGO FINANCIADO?</h3>
                                <br>
                                <h3 id="ref_nex_get"></h3>
                            </div>
                            <div class="form-group mb-3 py-3">
                                <label for="pass_supervisor pb-2">Contraseña de supervisor:</label>
                                <input type="password" class="form-control" id="pass_supervisor_sol" name="pass_supervisor_sol" placeholder="Ingresa la contraseña, supervisor">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Contenido del modal -->
                        <div id="loadingIndicator" class="custom-modal-loading-overlay d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                        <button id="autorizar_pago" class="btn btn-success" onclick="verificarContraseñaFinanciamiento()" data-bs-target="#modal_detalle_pagos" data-bs-toggle="modal" data-bs-dismiss="modal">Autorizar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--TERMINA MODAL BORRAR OPERACION GLOBAL-->

        <!-- Modal para registrar cliente -->
        <div class="modal fade" id="modalUpdateCliente" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white text-center">
                        <h5 class="modal-title" id="titleModalCliente">Editar cliente</h5>
                        <button type="button" class="btn-close" onclick="limpiarModals()" data-bs-dismiss="modal" aria-label="Close" data-bs-theme="dark"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formClienteUpdate">
                            <fieldset>
                                <legend>Información del cliente</legend>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="RAZON_SOCIAL" name="RAZON_SOCIAL" placeholder="Razón social">
                                    <label for="RAZON_SOCIAL" class="form-label">Razón social</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="CONTACTO" name="CONTACTO" placeholder="Nombre del contacto">
                                    <label for="CONTACTO" class="form-label">Nombre del contacto</label>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="RFC" name="RFC" placeholder="RFC">
                                            <label for="RFC" class="form-label">RFC</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="tipo_cliente" aria-label="Tipo de cliente" name="tipo_cliente">
                                                <option value="" selected disabled>Selecciona el tipo de cliente</option>
                                                <option value="Fisica">Física</option>
                                                <option value="Moral">Moral</option>
                                            </select>
                                            <label for="tipo_cliente">Tipo de cliente</label>
                                        </div>
                                    </div>
                                </div> <!-- .row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="TELEFONO" name="TELEFONO" placeholder="Teléfono">
                                            <label for="TELEFONO" class="form-label">Teléfono</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="MOVIL" name="MOVIL" placeholder="Teléfono móvil">
                                            <label for="MOVIL" class="form-label">Teléfono móvil</label>
                                        </div>
                                    </div>
                                </div> <!-- .row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="EMAIL_1" name="EMAIL_1" placeholder="Correo electrónico">
                                            <label for="EMAIL_1" class="form-label">Correo electrónico</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="EMAIL_2" name="EMAIL_2" placeholder="Añade un segundo correo electrónico">
                                            <label for="EMAIL_2" class="form-label">Añade un segundo correo electrónico</label>
                                        </div>
                                    </div>
                                </div> <!-- .row -->
                            </fieldset>
                            <fieldset>
                                <legend>Domicilio</legend>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="Domicilio_Fisico" name="Domicilio_Fisico" placeholder="Razón social">
                                    <label for="Domicilio_Fisico" class="form-label">Dirección</label>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="Codigo_Postal" name="Codigo_Postal" placeholder="Código postal">
                                            <label for="Codigo_Postal" class="form-label">Código postal</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="Delegacion_Municipio" name="Delegacion_Municipio" placeholder="Delegación o municipio">
                                            <label for="Delegacion_Municipio" class="form-label">Delegación o municipio</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="Estado" name="Estado" placeholder="Estado">
                                            <label for="Estado" class="form-label">Estado</label>
                                        </div>
                                    </div>
                                </div> <!-- .row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="Pais" name="Pais" placeholder="País">
                                            <label for="Pais" class="form-label">País</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="Referencia" name="Referencia" placeholder="Referencia">
                                            <label for="Referencia" class="form-label">Referencia</label>
                                        </div>
                                    </div>
                                </div> <!-- .row -->
                            </fieldset>
                            <fieldset>
                                <legend>Ingresa los permisos de supervisión</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="usuarioSupervisor" name="usuarioSupervisor" placeholder="Usuario">
                                            <label for="usuarioSupervisor" class="form-label">Usuario</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="contrasenaSupervisor" name="contrasenaSupervisor" placeholder="Contraseña">
                                            <label for="contrasenaSupervisor" class="form-label">Contraseña</label>
                                        </div>
                                    </div>
                                </div> <!-- .row -->
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnUpdateCliente" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal para subir Factura -->
        <div class="modal fade" id="fileUploadModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header nft">
                        <h5 class="modal-title" id="exampleModalLabel">SUBIR FACTURA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="opendatatable();"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center align-items-center">
                        <input id="facturaNexenInput" name="facturaNexenInput" type="hidden" />
                        <input id="numOperacionInput" name="numOperacionInput" type="hidden" />
                        <div class="mb-3">
                            <label for="file_pago_aceptado" class="form-label">Subir archivo</label>
                            <input class="form-control" type="file" id="file_pago_aceptado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="opendatatable();" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success" onclick="newFacUpload(1);">
                            <span id="saveFac" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Guardar Factura
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal para subir Factura -->
        <div class="modal fade" id="editFac" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bellnoti">
                        <h5 class="modal-title" id="exampleModalLabel">EDITAR FACTURA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="opendatatable();"></button>
                    </div>

                    <form id="editFacForm"> <!-- Aquí inicia el formulario -->
                        <div class="modal-body d-flex justify-content-center align-items-center">
                            <div class="col-md-10" id="editFacturaLabel">
                                <label>Describe el motivo de la edición</label>
                                <textarea class="form-control" id="Mensaje_Update" style="width: 100%; height: 100%;"></textarea>
                            </div>
                        </div>
                        <div class="modal-body d-flex justify-content-center align-items-center">
                            <input id="editFacturaNexenInput" name="editFacturaNexenInput" type="hidden" />
                            <input id="editNumOperacionInput" name="editNumOperacionInput" type="hidden" />

                            <div class="mb-3">
                                <label for="file_pago_aceptado" class="form-label">Subir archivo</label>
                                <input class="form-control" type="file" id="edit_file_pago_aceptado">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="opendatatable();">Cerrar</button>
                            <button type="button" class="btn btn-success" onclick="newFacUpload(2);">
                                <span id="saveFac" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Guardar Cambios
                            </button>
                        </div>
                    </form> <!-- Aquí termina el formulario -->
                </div>
            </div>
        </div>

        <div id="spinner" class="spinner-overlay">
            <div class="spinner"></div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>

        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/decimal.js/10.2.1/decimal.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.js"></script>

        <script src="../utils/config.js"></script>
        <script src="../utils/functions.js"></script>
        <script src="../resources/js/functions_detalle_pagos.js"></script>
        <script type="module" src="../utils/functions_solicitud_pagos.js"></script>
        <script type="module" src="../utils/functions_solicitud_pagos.js"></script>
        <script src="../resources/js/functions_reporte_carpetas.js"></script>
        <script type="module" src="../utils/functions_checkSolicitudPago.js"></script>

        <script type="module" src="../resources/js/functions_reporte_docsfaltantes.js"></script>
        <!-- Nuevo modal para editar cliente -->
        <script type="module" src="../utils/updateCliente.js"></script>
    </body>

    </html>

<?php

} else {
    echo "NO SE SELECCIONO UN CONTENEDOR PREVIO";
}
?>




<style type="text/css">
    .campo_rojo {
        border: 2px solid red;
    }

    .campo_verde {
        border: 2px solid green;
    }
</style>


<script>
    const soloTexto = /^[a-zA-Z]+$/; //Expresión regular que solo permite letras
    const soloNumeros = /^[0-9]+$/; //Expresión regular que solo permite números
    const soloNumerosYLetras = /^[a-zA-Z0-9\s!@#$%^&*(),.?":{}|<>_+-=\\/\[\]]*$/; //Expresión regular que solo permite números y letras
    const validarText = (input) => {
        if (soloTexto.test(input.value)) {
            document.getElementById("guardar_estatus").disabled = false;
            document.getElementById("guardar_proveedor").disabled = false;
            document.getElementById("guardar_ope").disabled = false;
            document.getElementById("guardar_aduana").disabled = false;
            document.getElementById("guardar_moneda").disabled = false;
            input.classList.remove("campo_rojo");
            input.classList.add("campo_verde");
        } else {
            document.getElementById("guardar_estatus").disabled = true;
            document.getElementById("guardar_proveedor").disabled = true;
            document.getElementById("guardar_aduana").disabled = true;
            document.getElementById("guardar_ope").disabled = true;
            document.getElementById("guardar_moneda").disabled = true;
            input.classList.remove("campo_verde");
            input.classList.add("campo_rojo");
        }
    };

    function numberSol(event) {
        // Obtener el código de la tecla presionada
        var keyCode = event.keyCode || event.which;

        // Permitir solo teclas numéricas (códigos de teclas 48-57)
        if (keyCode < 48 || keyCode > 57) {
            event.preventDefault(); // Cancelar el evento si no es una tecla numérica
        }
    }

    const validarNumber = (input) => {
        if (soloNumeros.test(input.value)) {
            document.getElementById("guardar_proveedor").disabled = false;
            document.getElementById("guardar_aduana").disabled = false;
            input.classList.remove("campo_rojo");
            input.classList.add("campo_verde");

        } else {
            document.getElementById("guardar_proveedor").disabled = true;
            document.getElementById("guardar_aduana").disabled = true;
            input.classList.remove("campo_verde");
            input.classList.add("campo_rojo");
        }
    };


    const validarTextNumber = (input) => {
        if (soloNumerosYLetras.test(input.value)) {
            document.getElementById("guardar_proveedor").disabled = false;
            input.classList.remove("campo_rojo");
            input.classList.add("campo_verde");
        } else {
            document.getElementById("guardar_proveedor").disabled = true;
            input.classList.remove("campo_verde");
            input.classList.add("campo_rojo");
        }
    };
</script>
<script>
    function quitarComillas(elemento) {
        var valor = elemento.value;
        valor = valor.replace(/["']/g, ''); // reemplazar comillas dobles y simples con una cadena vacía
        elemento.value = valor;
    }
</script>



<script>
    const botonLimpiar = document.getElementById('btn_Notificaion');
    const botonArribo = document.getElementById('btn_Arribo');
    const botonPedimentp = document.getElementById('btn_pedimento');
    const btnmodulacion = document.getElementById('btnmodu');
    const btnNexen24 = document.getElementById('btnNex24');

    const campoFecha = document.getElementById('fechNotifi');
    const Arribo = document.getElementById('fecharribo');
    const pedimento = document.getElementById('fechpedimento');
    const modulacion = document.getElementById('fechamodulacion');
    const fechaNex = document.getElementById('opeNex');

    botonLimpiar.addEventListener('click', () => {
        campoFecha.value = '';
    });
    botonArribo.addEventListener('click', () => {
        Arribo.value = '';
    });
    botonPedimentp.addEventListener('click', () => {
        pedimento.value = '';
    });
    btnmodulacion.addEventListener('click', () => {
        modulacion.value = '';
    });
    btnNexen24.addEventListener('click', () => {
        fechaNex.value = '';
    });
</script>






<script>
    $(document).ready(function() {
        $('input[type=checkbox]').on('change', function() {
            if ($(this).is(':checked')) {
                var opcion = confirm("Se ha validado correctamente el documento " + $(this).val());
                if (opcion == true) {
                    document.getElementById($(this).prop("id")).disabled = true;


                } else {
                    document.getElementById($(this).val()).checked = false;
                }
            }
        });
    });
</script>