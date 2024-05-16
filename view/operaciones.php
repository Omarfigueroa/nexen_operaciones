<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/nexen_operaciones/include/config.php';
require_once(INCLUDE_PATH . 'validar_sesiones.php');
require_once(CONEXION_PATH . 'bd.php');
require(UTILS_PATH . 'catalogos.php');
?>
<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="d-flex flex-column h-100">

    <?php require('../plantilla/menu.php'); ?>
    <main>

        <div id="spinner" class="spinner-overlay">
            <div class="spinner"></div>
        </div>
        <div class="container mt-3 text-center" style="max-width: 98%;">
            <div class="row ">
                <div id="tabla1" class="col-md-12">
                    <div class=" card text-center">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th class="text-center">
                                            <h1 class=" nft nftit">BUSCAR OPERACIONES</h1>
                                        </th>
                                    </tr>
                                </table>
                                <div class="container text-center" style="max-width: 98%;">
                                    <div class="row ">
                                        <div class="" style="overflow: hidden;">
                                            <div class="" style="overflow-x: auto;">
                                                <table id="cabecera" class="table table-hover stripe" style="font-size:12px; text-align:center; vertical-align:middle;">
                                                    <thead>
                                                        <tr>
                                                            <th>#OPERACION</th>
                                                            <th>USUARIO</th>
                                                            <th>REFERENCIA NEXEN</th>
                                                            <th>REFERENCIA CLIENTE</th>
                                                            <th>CLIENTE</th>
                                                            <th>BL</th>
                                                            <th>CONTENEDOR1</th>
                                                            <th>CONTENEDOR2</th>
                                                            <th>PEDIMENTO</th>
                                                            <th>PATENTE</th>
                                                            <th>FECHA OPERACION</th>
                                                            <th>FECHA ARRIBO</th>
                                                            <th>FECHA NOTIFICACION</th>
                                                            <th>FECHA MODULACION</th>
                                                            <th>FECHA PAGO</th>
                                                            <th>IMPO/EXPO</th>
                                                            <th>CVE PEDIMENTO</th>
                                                            <th>ESTATUS</th>
                                                            <th>DENOMINACION ADUANA</th>
                                                            <th>TIPO TRAFICO</th>
                                                            <th># ECO</th>
                                                            <th>DET. MERCANCIA</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><select id="operacion" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="usuario" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="referencia" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="refcliente" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="cliente" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="bl" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="contenedor" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="contenedor2" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="pedimento" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="patente" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="fechao" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="fechaa" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="fechan" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="fecham" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="fechap" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="impexp" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="cve" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="estaus" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="daduana" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="ttrafico" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="eco" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                            <td><select id="detm" class="form-select">
                                                                    <option value=""></option>
                                                                </select></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="operacioncheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="usuariocheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="referenciacheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="refclientecheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="clientecheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="blcheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="contenedorcheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="contenedor2check"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="pedimentocheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="patentecheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="fechaocheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="fechaacheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="fechancheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="fechamcheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="fechapcheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="impexpcheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="cvecheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="estauscheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="daduanacheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="ttraficocheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="ecocheck"></td>
                                                            <td><input class="form-check-input" type="checkbox" value="" id="detmcheck"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="container mt-3 text-center" style="max-width: 98%;">
            <div class="row mt-1">
                <div id="tabla1" class="col-md-12">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-hover stripe border " style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>#OPERACION</th>
                                            <th>USUARIO</th>
                                            <th>REFERENCIA NEXEN</th>
                                            <th>REFERENCIA CLIENTE</th>
                                            <th>CLIENTE</th>
                                            <th>BL</th>
                                            <th>CONTENEDOR 1</th>
                                            <th>CONTENEDOR 2</th>
                                            <th>PEDIMENTO</th>
                                            <th>PATENTE</th>
                                            <th>FECHA OPERACION</th>
                                            <th>FECHA ARRIBO</th>
                                            <th>FECHA NOTIFICACION</th>
                                            <th>FECHA MODULACION</th>
                                            <th>FECHA PAGO</th>
                                            <th>IMPO/EXPO</th>
                                            <th>CVE PEDIMENTO</th>
                                            <th>ESTATUS</th>
                                            <th>DENOMINACION ADUANA</th>
                                            <th>TIPO TRAFICO</th>
                                            <th># ECO</th>
                                            <th>DET. MERCANCIA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //foreach($result_operacion_nex as $mostrar){
                                        ?>
                                        <tr>
                                            <td><?php //echo $mostrar['NUM_OPERACION'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Usuario'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['FECHOPE'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['REFERENCIA_NEXEN'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Referencia_Cliente'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Cliente'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['BL'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Contenedor_1'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Contenedor_2'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['No_Pedimento'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Patente'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Fecha_Arribo'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Fecha_Notificación'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Fecha_Modulación'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Fecha_Pago_Anticipo'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Importador_Exportador'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Clave_Pedimento'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['Estatus'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['DENOMINACION_ADUANA'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['tipo_trafico'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['NUMERO_ECONOMICO'] 
                                                ?></td>
                                            <td><?php //echo $mostrar['DETALLE_MERCANCIA'] 
                                                ?></td>
                                        </tr>
                                        <?php //} 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL FILTRO INICIO -->
        <div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header  bg-primary   text-white  text-center ">
                        <h5 class="modal-title" id="titleModal">Buscar Operacion</h5>
                        <button type="button" class="close" onclick="limpiarModals()" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="search-form" name="search-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="align-text-bottom form-label">Contenedor</label>
                                    <input class="form-control valid validText " type="text" id="Contenedor" oninput="verificarInputContenedor(); quitarComillas(this);" placeholder="Ingresa el contenedor">
                                </div>
                                <div class="col-md-4">
                                    <label class="align-text-bottom form-label">BL</label>
                                    <input class="form-control valid validText" type="text" id="BL" placeholder="Ingrese el BL" oninput="verificarInputBL();quitarComillas(this);">
                                </div>
                                <div class="col-md-4">
                                    <label class="align-text-bottom form-label">N° Pedimento</label>
                                    <input class="form-control valid validNumber" type="text" id="Pedimento" placeholder="Ingresa N° pedimento" oninput="verificarInputPedimento();quitarComillas(this);">
                                </div>
                            </div>
                            <br>
                            <div class="col" style="margin-left:45%; padding: 15px;">
                                <button class="btn btn-primary" type="submit">Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal filtro -->
        <!-- modal lista de filtro -->
        <div class="modal fade" id="modalLista" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header  bg-primary   text-white  text-center ">
                        <h5 class="modal-title" id="titleModal">Lista de Operaciones</h5>
                        <button type="button" onclick="limpiar()" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover table-bordered" id="tableLista">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Referencia Nexen</th>
                                    <th>Contenedor</th>
                                    <th>BL</th>
                                    <th>N° Pedimento</th>
                                    <th>Ver Operacion</th>
                                    <th>Crear Operacion</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal lista filtro -->
        <!-- modal crear operacion -->
        <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header  bg-primary   text-white  text-center ">
                        <h5 class="modal-title" id="titleModal">Crear Referencia</h5>
                        <button type="button" class="close" onclick="recarga()" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formCrear" method="post" action="" class="form-control">
                            <div class="col" style="padding: 30px;">
                                <!-- clientes -->
                                <div class="col">
                                    <div class="boton-modal">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-cliente">
                                            <label for="btn-modal3">Clientes
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
                                                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    <path d="M2 13c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z" />
                                                </svg>
                                            </label>
                                        </button>
                                    </div>
                                    <select class="form-control" id="razon_social" name="razon_social" required>
                                        <?php
                                        echo '<option value="">Selecciona una opción</option>';
                                        foreach ($result_cat_razon as $valores) :
                                            echo '<option value="' . $valores["RAZON SOCIAL "] . '">' . $valores["RAZON SOCIAL "] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                                <!-- operaciones -->
                                <div class="col">
                                    <div class="boton-modal3">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-impo-expo">
                                            <label for="btn-modal3">Importador/Exportador
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building-add" viewBox="0 0 16 16">
                                                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Z" />
                                                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1V1Z" />
                                                    <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                                                </svg>
                                            </label>
                                        </button>
                                    </div>
                                    <select class="align-text-bottom form-control" name="nombre_operador" required>
                                        <?php
                                        echo '<option value="">Selecciona una opción</option>';
                                        foreach ($result_empresas as $valores) :
                                            echo '<option value="' . $valores["Razon_Social"] . '">' . $valores["Razon_Social"] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="boton-modal3">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tipo-operacion">
                                            <label for="btn-modal3">Operación
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-expand" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8ZM7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2ZM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10Z" />
                                                </svg>
                                            </label>
                                        </button>
                                    </div>
                                    <select class="form-control" id="tipo_operacion" name="tipo_operacion" required>
                                        <?php
                                        echo '<option value="">Selecciona una opción</option>';
                                        foreach ($result_cat_tipoope as $valores) :
                                            echo '<option value="' . $valores["DESCRIPCION"] . '">' . $valores["DESCRIPCION"] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="boton-modal5">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tipo-trafico">
                                            <label for="btn-modal5"> Tipo Tráfico
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stoplights" viewBox="0 0 16 16">
                                                    <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm1.5 2.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                                    <path d="M4 2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2h2c-.167.5-.8 1.6-2 2v2h2c-.167.5-.8 1.6-2 2v2h2c-.167.5-.8 1.6-2 2v1a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-1c-1.2-.4-1.833-1.5-2-2h2V8c-1.2-.4-1.833-1.5-2-2h2V4c-1.2-.4-1.833-1.5-2-2h2zm2-1a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6z" />
                                                </svg>
                                            </label>
                                            </label>
                                        </button>
                                    </div>
                                    <select class="form-control" id="tipo_trafico" name="tipo_trafico" required>
                                        <?php
                                        echo '<option value="">Selecciona una opción</option>';
                                        foreach ($result_cat_vias as $valor) :
                                            echo '<option value="' . $valor["medios"] . '">' . $valor["medios"] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col" style="align-items: center;">
                                <button type="submit" class="btn btn-success " onclick="this.form.action ='../include/crear_operacion.php'">CREAR
                                    NUEVO</button>
                                <button class="btn btn-danger" onclick="recarga()" data-bs-dismiss="modal">Cerrar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal operacion -->
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
        <!-- MODAL TIPO TRAFICO -->
        <div class="modal fade" id="modal-tipo-trafico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel"> Catalago
                            Tipo Tráfico</label>
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

        <!-- MODAL FECHAS -->
        <div class="modal fade" id="fechasfiltro" tabindex="-1" aria-labelledby="fechasfiltroLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cabeceraModal"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <label class="form-label" for="fechainicio">Fecha Inicio</label>
                                    <input id="fechai" type="date" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="fechafin">Fecha Fin</label>
                                    <input id="fechaf" type="date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="limpiar_fecha">Limpiar</button>
                        <button type="button" class="btn btn-primary" id="filtrar_fecha">Filtrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para registrar cliente -->
        <div class="modal fade" id="modalRegistrarCliente" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white text-center">
                        <h5 class="modal-title" id="titleModalCliente">Registrar nuevo cliente</h5>
                        <button type="button" class="btn-close" onclick="limpiarModals()" data-bs-dismiss="modal" aria-label="Close" data-bs-theme="dark"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formCliente">
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
                        <button type="button" id="btnGuardarCliente" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
            <!--Documentos-->
    <div class="modal" id="openCarpetas" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content rounded-3">
                <div class="modal-header nft text-white">
                    <h5 class="modal-title">Carpetas Digitales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-custom">
                            <thead class="nft bg-dark text-white">
                                <tr>
                                    <th class="text-center">DOCUMENTO</th>
                                    <th class="text-center">ACCIONES</th>
                                    <th class="text-center">ESTADO</th>
                                </tr>
                            </thead>
                            <tbody id="tableDocumentos">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
        <!-- Fin modal para registrar cliente -->

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
        <script src="../utils/sesiones.js"></script>

        <script type="module" src="../utils/function_table.js"></script>

        <script>
            /*
                var n=0;
                var cerrada = 0;
                var periodo_sesion = window.setInterval(checa_sesion, 1000); //Revisa cada 1 segundo

                function checa_sesion() {
                    //alert('Hola');

                    document.onmousemove = function(){
                        n=0;
                    };

                    n++;

                    if(n>=600){

                        $.ajax({
                            url:'logout.php',
                            method:"GET",
                            data:{},
                            success:function(data)
                            {
                            }
                        });

                        const container_cerrar = document.getElementById("cerrarSesion");
                        const modal_cerrar = new bootstrap.Modal(container_cerrar);
                        modal_cerrar.show();

                        window.clearInterval(periodo_sesion);
                    }
                }
                */
        </script>
        <!-- <script src="../utils/sesiones.js"></script> -->
        <?php require_once('../plantilla/modales.php'); ?>
    </main>

    <script type="module" src="../resources/js/functions_reporte_docsfaltantes.js"></script>
    <script src="../utils/config.js"></script>
    <script src="../resources/js/functions_reporte_carpetas.js"></script>
</body>

</html>