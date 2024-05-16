<?php
date_default_timezone_set('America/Mexico_City');
require_once($_SERVER['DOCUMENT_ROOT'] . '/nexen_operaciones/include/config.php');
require_once(INCLUDE_PATH . '/validar_sesiones.php');
/* require_once(UTILS_PATH . '/catalogos.php'); */
/* require_once(UTILS_PATH . '/utils.php'); */
?>
<!DOCTYPE html>
<html lang="es" class="h-100" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../img/logoNexen-ico.png">
    <title>Nexen | Detalle pagos</title>
    <?php include('../css_files.php') ?>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>

<body class="d-flex flex-column h-100">
    <?php include('../header.php'); ?>
    <div class="container mb-5  mt-3" style="max-width: 90%;">
    <div class="card mb-5">
    <div class="card-body">
        <h5 class="card-title nft text-white text-center w-100 mb-3 p-2">SOLICITUD DE PAGOS</h5>
        <div class="table-responsive">
            <table id="tablaDetallePagos" class="table-hover stripe">
                <thead>
                    <tr>
                        <th>Referencia Nexen</th>
                        <th>Tipo de Operación</th>
                        <th>Estado de Pagos</th>
                        <th class="text-center">Carpeta Digital</th>
                        <th class="text-center">Pagos Realizados</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title nft text-white text-center w-100 mb-3 p-2">LOG MOVIMIENTOS PAGOS</h5>
                <div class="table-responsive-sm">
                    <h3 class="text-center text-primary"></h3>
                    <div class="text-center" id="LogMovimientosPagos">
                    </div>
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
    <!--ShowInfoNexen-->
    <div class="modal fade" id="showInfoNexen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content rounded-3">
                <div class="modal-header nft text-white">
                    <h5 class="modal-title">MÁS DETALLES</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-custom">
                            <thead class="nft bg-dark text-white">
                                <tr>
                                    <th class="text-center">CAMPO</th>
                                    <th class="text-center">NOMBRE</th>
                                </tr>
                            </thead>
                            <tbody id="modalTableBody">
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
    <!-- Modal ACEPTADOS-->
    <div class="modal fade" id="modalSubirComprobantePago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subir comprobante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                            </symbol>
                        </svg>
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                            <use xlink:href="#info-fill" />
                        </svg>
                        Para poder aceptar este pago es necesario subir el comprobante!
                    </div>
                    <div class="mb-3">
                        <label for="file_pago_aceptado" class="form-label">Subir archivo</label>
                        <input class="form-control" type="file" id="file_pago_aceptado">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">
                        <span id="loading_comprobante_pago" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Guardar archivo y aceptar pago
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--termina modal para subir archivo cuando se le de aceptar al pago-->

    <!-- Modal RECHAZADOS-->
    <div class="modal fade" id="modalMotivoRechazado" tabindex="-1" aria-labelledby="modalMotivoRechazadoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMotivoRechazadoLabel">Motivo rechazado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                            </symbol>
                        </svg>
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                            <use xlink:href="#info-fill" />
                        </svg>
                        Para poder rechazar este pago, debes escribir una breve descripción con el motivo de rechazo.
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Dejar un breve comentario del rechazo" id="motivoRechazo"></textarea>
                        <label for="motivoRechazo">Motivo Rechazo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">
                        <span id="loading_motivoRechazo" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Guardar motivo y rechazar pago
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('../../plantilla/modales.php'); ?>

    <!--termina modal para subir archivo cuando se le de aceptar al pago-->
    <?php include('../footer.php') ?>
    <?php include('../js_files.php') ?>
    <script src="../../resources/js/functions_pagos.js"></script>
    <script src="../../utils/config.js"></script>
    <script>
        function asignar_id(id_b, estatus) {
            //alert('Hola');
            document.getElementById("id_pago").value = id_b;
            document.getElementById("estatus").value = estatus;
        }

        function borrar_id() {
            document.getElementById("id_pago").value = "";
        }

        function id_candidato(id, url) {
            //var url="asignar_examen.php";
            if (id == '') {
                document.getElementById("ex_u").src = url;
            } else {
                document.getElementById("ex_u").src = url + '?id_candidato=' + id;
                console.log(url + ' ' + id);
            }
        }
    </script>

    <script type="module" src="../../resources/js/functions_reporte_docsfaltantes.js"></script>
    <script src="../../resources/js/functions_reporte_carpetas.js"></script>
</body>

</html>