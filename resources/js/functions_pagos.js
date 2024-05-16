$(document).ready(function () {

    var table = $('#tablaDetallePagos').DataTable({
        "language": {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
        },
        "responsive": true,
        "ajax": {
            "url": "../../request/finanzas/fetchDetallePagos.php",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "REFERENCIA" },
            { "data": "OPERACION" }
        ],
        "columnDefs": [
            {
                "targets": 2,
                "render": function (data, type, row) {
                    return '<center><div class="d-flex align-items-center"><div class="mr-3" ><div class="custom-icon pay"><span class="number-circle">' + row.CANTIDAD_PAGOS + '</span><i class="bi bi-credit-card-2-back-fill"></i></div><div style="margin-top: 5px;">Pagos</div></div><div style="width:30px;"></div><div class="mr-3" ><div class="custom-icon bellnoti"><span class="number-circle">' + row.CANTIDAD_PENDIENTES + '</span><i class="bi bi-bell-fill" style="font-size:25px;"></i></div><div style="margin-top: 5px;">Pendientes</div></div></div></center>';
                }
            },
            {
                "targets": 3,
                "render": function (data, type, row) {
                    if (row.OPERACION !== null && row.CARPETAS !== '0') {
                        return `<div class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="custom-icon file text-white" onclick="openCarpetas('${row.REFERENCIA}', '${row.OPERACION}');">
                                            <i class="bi bi-folder-fill"></i>
                                        </div>
                                        <div class="mt-2">Ver</div>
                                    </div>
                                </div>`;
                    } else {
                        return `<div class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="custom-icon danger text-white">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </div>
                                        <div class="mt-2">Sin Datos</div>
                                    </div>
                                </div>`;
                    }
                }
            },
            {
                "targets": 4,
                "render": function (data, type, row, meta) {
                    return `<div class="text-center">
                                <div class="d-flex flex-column align-items-center justify-content-center btn-details">
                                    <div class="custom-icon dowloand text-white" onclick="listNexen('${row.REFERENCIA}', ${meta.row});">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="mt-2">Más Pagos...</div>
                                </div>
                            </div>`;
                }
            }
        ],
        "rowCallback": function (row, data, index) {
            $(row).addClass('details-control').next().hide();
        }

    });

    //**************************termina movimientos pagos */
    //Traer log de movimientos pagos
    $.ajax({
        url: '../../request/finanzas/fetchMovimientosLog.php',
        method: 'GET',
        dataType: 'json',
        success: function (response, textStatus, jqXHR) {
            if (jqXHR.status === 200) {
                var LogdetallePagos = response.data;

                if (LogdetallePagos && LogdetallePagos.length > 0) {

                    $('#LogMovimientosPagos').html('<table id="tablaLogDetalles" class="table-hover stripe" style="max-width: 100%; table-layout: fixed; word-break: break-word;"><thead ><tr><th >ID OPERACIÓN</th><th >REFERENCIA NEXEN</th><th ># OPERACIÓN</th><th >CONCEPTO</th><th>TIPO SOLICITUD</th><th>MONTO</th><th>MONEDA</th><th>ESTATUS</th><th>USUARIO ADMIN</th><th>FECHA</th><th>HORA</th></tr></thead><tbody class="table-light"></tbody></table>');

                    // Llenar la tabla con datos
                    $('#tablaLogDetalles').DataTable({
                        data: LogdetallePagos,
                        columns: [
                            { data: 'Id_Operacion' },
                            { data: 'Referencia_Nexen' },
                            { data: 'Num_Operacion' },
                            { data: 'Concepto' },
                            { data: 'Tipo_Solicitud' },
                            { data: 'Monto', render: $.fn.dataTable.render.number(',', '.', 2) }, // Formatear monto
                            { data: 'Moneda' },
                            { data: 'Estatus' },
                            { data: 'Usuario_Administracion' },
                            { data: 'Fechope' },
                            { data: 'Horaope' }
                        ],
                        lengthMenu: [10, 25, 50],
                        pageLength: 10,
                        searching: true,
                        ordering: true,
                        autoWidth: false,
                        columnDefs: [{
                            targets: 'no-sort',
                            orderable: false
                        }]
                    });
                } else {
                    $('#LogMovimientosPagos').html('No encontramos resultados');
                }
            } else {
                console.log('Error al obtener los detalles de pagos');
                console.log(response.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud:', errorThrown);
        }
    });

    //*************************termina log de movimientos pagos */
    // los toggle
    function toggleDropdownMenu(element) {
        $(element).next('.dropdown-menu').toggleClass('show');
    }

    // Utiliza delegación de eventos para los "dropdowns"
    $(document).on('click', '.dropdown-toggle', function () {
        toggleDropdownMenu(this);
    });

});

// Update de estatus para num_operacion ACEPTADO
function updateEstatusAceptado(id, estatus, referencia_nexen, contenedor, concepto, tipo_solicitud, monto, moneda, usuario, file) {


    usuario = cambiarGuionBajoAEspacios(usuario);


    //para bloquear todos los botones
    $('.dropdown-toggle').attr('disabled', true);

    $('#loading_comprobante_pago').removeClass('d-none');

    // Crear un FormData para enviar el archivo y otros datos
    var formData = new FormData();
    formData.append('id', id);
    formData.append('estatus', estatus);
    formData.append('referencia_nexen', referencia_nexen);
    formData.append('contenedor', contenedor);
    formData.append('concepto', concepto);
    formData.append('tipo_solicitud', tipo_solicitud);
    formData.append('monto', monto);
    formData.append('moneda', moneda);
    formData.append('usuario', usuario);
    formData.append('file_pago_aceptado', file); // Nombre correcto del archivo

    console.log("id:", id);
    console.log("estatus:", estatus);
    console.log("referencia_nexen:", referencia_nexen);
    console.log("contenedor:", contenedor);
    console.log("concepto:", concepto);
    console.log("tipo_solicitud:", tipo_solicitud);
    console.log("monto:", monto);
    console.log("moneda:", moneda);
    console.log("usuario:", usuario);



    // Realizar la solicitud AJAX para enviar el archivo y los datos
    $.ajax({
        type: "post",
        url: "../../include/setEstatus_Pagos.php",
        data: formData, // Usamos el formData para enviar todo el contenido, incluyendo el archivo
        dataType: "json",
        contentType: false, // No configurar el contentType (se dejará que jQuery lo determine automáticamente)
        processData: false, // No procesar los datos (también se manejarán automáticamente)
        success: function (response) {
            // Resto del código
            // Mostrar mensaje de éxito o error según la respuesta del servidor
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000 // La ventana se cerrará automáticamente después de 1.5 segundos
                }).then(function () {
                    // Recargar la página después de cerrar el mensaje de éxito
                    $('#modalSubirComprobantePago').modal('hide');
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000 // La ventana se cerrará automáticamente después de 1.5 segundos
                }).then(function () {
                    // Recargar la página después de cerrar el mensaje de error
                    location.reload();
                });
            }
        },
        error: function (xhr, status, error) {
            // Remover pantalla en blur en caso de error
            $('body').removeClass('modal-open');

            // Mostrar mensaje de error
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Ocurrió un error al actualizar la operación.',
                showConfirmButton: false,
                timer: 1500 // La ventana se cerrará automáticamente después de 1.5 segundos
            });
            console.error("Error en la solicitud AJAX:", status, error);
        }
    });
}

// Update de estatus para num_operacion RECHAZADO
function updateEstatusRechazado(id, estatus, referencia_nexen, contenedor, concepto, tipo_solicitud, monto, moneda, usuario, motivoRechazo) {


    usuario = cambiarGuionBajoAEspacios(usuario);


    //para bloquear todos los botones
    $('.dropdown-toggle').attr('disabled', true);

    $('#loading_motivoRechazo').removeClass('d-none');

    // Crear un FormData para enviar el archivo y otros datos
    var formData = new FormData();
    formData.append('id', id);
    formData.append('estatus', estatus);
    formData.append('referencia_nexen', referencia_nexen);
    formData.append('contenedor', contenedor);
    formData.append('concepto', concepto);
    formData.append('tipo_solicitud', tipo_solicitud);
    formData.append('monto', monto);
    formData.append('moneda', moneda);
    formData.append('usuario', usuario);
    formData.append('motivoRechazo', motivoRechazo); // Nombre correcto del archivo

    //console.log(motivoRechazo);

    // Realizar la solicitud AJAX para enviar el archivo y los datos
    $.ajax({
        type: "post",
        url: "../../include/setEstatus_Pagos.php",
        data: formData, // Usamos el formData para enviar todo el contenido, incluyendo el archivo
        dataType: "json",
        contentType: false, // No configurar el contentType (se dejará que jQuery lo determine automáticamente)
        processData: false, // No procesar los datos (también se manejarán automáticamente)
        success: function (response) {
            // Resto del código
            // Mostrar mensaje de éxito o error según la respuesta del servidor
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000 // La ventana se cerrará automáticamente después de 1.5 segundos
                }).then(function () {
                    // Recargar la página después de cerrar el mensaje de éxito
                    $('#modalMotivoRechazado').modal('hide');
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000 // La ventana se cerrará automáticamente después de 1.5 segundos
                }).then(function () {
                    // Recargar la página después de cerrar el mensaje de error
                    location.reload();
                });
            }
        },
        error: function (xhr, status, error) {
            // Remover pantalla en blur en caso de error
            $('body').removeClass('modal-open');

            // Mostrar mensaje de error
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Ocurrió un error al actualizar la operación.',
                showConfirmButton: false,
                timer: 1500 // La ventana se cerrará automáticamente después de 1.5 segundos
            });
            console.error("Error en la solicitud AJAX:", status, error);
        }
    });
}

// Update de estatus para num_operacion Sin Fondos
function updateEstatusSinFondos(id, estatus, referencia_nexen, contenedor, concepto, tipo_solicitud, monto, moneda, usuario) {


    usuario = cambiarGuionBajoAEspacios(usuario);


    //para bloquear todos los botones
    $('.dropdown-toggle').attr('disabled', true);

    $('#loading_motivoRechazo').removeClass('d-none');

    // Crear un FormData para enviar el archivo y otros datos
    var formData = new FormData();
    formData.append('id', id);
    formData.append('estatus', estatus);
    formData.append('referencia_nexen', referencia_nexen);
    formData.append('contenedor', contenedor);
    formData.append('concepto', concepto);
    formData.append('tipo_solicitud', tipo_solicitud);
    formData.append('monto', monto);
    formData.append('moneda', moneda);
    formData.append('usuario', usuario);

    console.log("id:", id);
    console.log("estatus:", estatus);
    console.log("referencia_nexen:", referencia_nexen);
    console.log("contenedor:", contenedor);
    console.log("concepto:", concepto);
    console.log("tipo_solicitud:", tipo_solicitud);
    console.log("monto:", monto);
    console.log("moneda:", moneda);
    console.log("usuario:", usuario);



    //console.log(motivoRechazo);

    // Realizar la solicitud AJAX para enviar el archivo y los datos
    $.ajax({
        type: "post",
        url: "../../include/setEstatus_Pagos.php",
        data: formData, // Usamos el formData para enviar todo el contenido, incluyendo el archivo
        dataType: "json",
        contentType: false, // No configurar el contentType (se dejará que jQuery lo determine automáticamente)
        processData: false, // No procesar los datos (también se manejarán automáticamente)
        success: function (response) {
            // Resto del código
            // Mostrar mensaje de éxito o error según la respuesta del servidor
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000 // La ventana se cerrará automáticamente después de 1.5 segundos
                }).then(function () {
                    // Recargar la página después de cerrar el mensaje de éxito
                    $('#modalMotivoRechazado').modal('hide');
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000 // La ventana se cerrará automáticamente después de 1.5 segundos
                }).then(function () {
                    // Recargar la página después de cerrar el mensaje de error
                    //location.reload();
                });
            }
        },
        error: function (xhr, status, error) {
            // Remover pantalla en blur en caso de error
            $('body').removeClass('modal-open');

            // Mostrar mensaje de error
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Ocurrió un error al actualizar la operación.',
                showConfirmButton: false,
                timer: 1500 // La ventana se cerrará automáticamente después de 1.5 segundos
            });
            console.error("Error en la solicitud AJAX:", status, error);
        }
    });
}

//Helper cambiar espacios a guiones y viceversa
function cambiarEspaciosAGuionBajo(texto) {
    return texto.replace(/\s+/g, '_');
}

function cambiarGuionBajoAEspacios(texto) {
    if (texto !== undefined) {
        return texto.replace(/_/g, ' ');
    } else {
        return ''; // O un valor predeterminado si es necesario
    }
}

/*abrir modal de subir comprobante para pagos*/
// Función para abrir el modal de subir comprobante para pagos
function abrirModalComprobantePago(id, estatus, referencia_nexen, contenedor, concepto, tipo_solicitud, monto, moneda, usuario) {
    // Abrir el modal
    $('#modalSubirComprobantePago').modal('show');

    // Asignar evento click al botón de guardar archivo y aceptar pago
    $('#modalSubirComprobantePago .btn-primary').on('click', function () {
        // Obtener el archivo seleccionado
        var file = $('#file_pago_aceptado')[0].files[0];

        if (!file) {
            alert('Por favor, seleccione un archivo.');
            return;
        }

        // Comprobar que el archivo sea de tipo PDF
        if (file.type !== 'application/pdf') {
            alert('El archivo debe ser de tipo PDF.');
            return;
        }

        // Llamar a la función updateEstatus() con los datos actualizados y el archivo
        updateEstatusAceptado(id, estatus, referencia_nexen, contenedor, concepto, tipo_solicitud, monto, moneda, usuario, file);
    });
}
/*Se cierra modal de subir comprobante pagos*/


/*abrir modal de subir comprobante para pagos*/
// Función para abrir el modal de subir comprobante para pagos
function abrirModalRechazado(id, estatus, referencia_nexen, contenedor, concepto, tipo_solicitud, monto, moneda, usuario) {
    // Abrir el modal
    $('#modalMotivoRechazado').modal('show');

    // Asignar evento click al botón de guardar archivo y aceptar pago
    $('#modalMotivoRechazado .btn-primary').on('click', function () {
        // Obtener el archivo seleccionado
        var motivoRechazo = $('#motivoRechazo').val();

        if (!motivoRechazo) {
            alert('Por favor, escribe un motivo de rechazo');
            return;
        }

        // Llamar a la función updateEstatus() con los datos actualizados y el archivo
        updateEstatusRechazado(id, estatus, referencia_nexen, contenedor, concepto, tipo_solicitud, monto, moneda, usuario, motivoRechazo);
    });
}


function fntUpdate(id_catalogo, referencia, tipo_trafico) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = '../../include/valida.php';

    // Ajustar la cadena de datos para enviar las variables por separado
    let strData = "&id_catalogo=" + id_catalogo;

    request.open("POST", ajaxUrl, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(strData);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            document.querySelector("#nombre").value = objData[0]['DOCUMENTO'];
            document.querySelector('#Referencia').value = referencia;
            document.querySelector("#id_catalogo").value = objData[0]['ID_CATALOGO_DOCUMENTOS'];
            document.querySelector("#Tipo_Ope").value = tipo_trafico;

            $('#modalUpload').modal('show');
        }
    }
}



// Objeto para almacenar los estados de las filas abiertas
var filasAbiertas = {};

function listNexen(Referencia_Nexen, rowIndex) {
    var table = $('#tablaDetallePagos').DataTable();
    var row = table.row(rowIndex).node();
    if ($(row).hasClass('shown')) {
        table.row(rowIndex).child.hide();
        $(row).removeClass('shown');
        filasAbiertas[rowIndex] = false; // Marca la fila como cerrada
    } else {
        table.row(rowIndex).child(formatDetail(Referencia_Nexen, rowIndex)).show();
        $(row).addClass('shown');
        filasAbiertas[rowIndex] = true; // Marca la fila como abierta
    }
}



function formatDetail(Referencia_Nexen, rowIndex) {
    var html = '<div class="detalle divsub"><table id="subReferencias' + rowIndex + '" class="table-hover stripe subReferencias">' +
        '<thead>' +
        '<tr>' +
        '<th>Referencia Nexen</th>' +
        '<th>Proveedor</th>' +
        '<th>Tipo Solicitud</th>' +
        '<th>Estado</th>' +
        '<th>Solicitud</th>' +
        '<th>Anticipo</th>' +
        '<th>Factura</th>' +
        '<th>Actividad</th>' +
        '<th>Detalles</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody>' +
        '</tbody>' +
        '</table></div>';

    // Inicializar la DataTable dentro del HTML
    setTimeout(function () {
        $('#subReferencias' + rowIndex + '').DataTable({
            "processing": true,
            "serverSide": true,
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": false,
            "ajax": {
                "url": "../../request/finanzas/fetchDetalleReferencias.php",
                "type": "POST",
                "dataType": "json",
                "data": { Referencia_Nexen: Referencia_Nexen },
            },
            "columns": [
                { "data": "Referencia_Nexen" },
                { "data": "Razon_Social_Receptora" },
                { "data": "Tipo_Solicitud" },
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "render": function (data, type, row) {
                        const estatusBotones = {
                            'ACEPTADO': '<div class="custom-icon true text-white"><span class="bi bi-check-lg"></span></div>',
                            'RECHAZADO': '<div class="custom-icon danger text-white"><span class="bi bi-x h1"></span></div>',
                            'PENDIENTE': '<div class="custom-icon wait text-white"><span class="bi bi-hourglass-split"></span></div>'
                        };
                        const btnStatus = estatusBotones[row.Estatus] || '';
                        return `<div class="d-flex align-items-center justify-content-center">${btnStatus}</div>${row.Estatus}`;
                    }
                },
                {
                    "targets": 4,
                    "render": function (data, type, row) {

                        if (row.Tipo_Solicitud !== null && row.Tipo_Solicitud !== '') {
                        btnsolicitud = '<div class="d-flex flex-column align-items-center justify-content-center"><a href="../../include/pdf_pagos_m.php?id=' + row.Num_Operacion + '" class="custom-icon dowloand" target="_blank"><i class="bi bi-save-fill"></i></a><span>Descargar</span></div>';
                        }else{
                            btnsolicitud = '<div class="d-flex flex-column align-items-center justify-content-center"><div class="custom-icon danger text-white"><i class="bi bi-x-circle-fill"></i></div><span>Faltan<br>Datos</span></div>'; // Simply assign btnsin for consistent structure
                        }
                        return btnsolicitud;
                    }
                },
                {
                    "targets": 5,
                    "render": function (data, type, row) {
                        if (row.Id_Pago !== null) {
                            btnpago = '<div class="d-flex flex-column align-items-center justify-content-center"><a href="../../src/Models/pdf_comprobante.php?id=' + row.Num_Operacion + '" target="_blank" class="btn dowloandbtn"><i class="bi bi-save2-fill" style="color:white;"></i></a><span>Anticipo</span></div>';
                        } else {
                            btnpago = '<div class="d-flex flex-column align-items-center justify-content-center"><div class="custom-icon danger text-white"><i class="bi bi-x-circle-fill"></i></div><span>Sin <br> Subir</span></div>'; // Simply assign btnsin for consistent structure
                        }

                        return btnpago;
                    }
                },
                {
                    "targets": 6,
                    "render": function (data, type, row) {
                        if (row.Estatus === 'ACEPTADO' && (row.Ruta_Factura !== null && row.Ruta_Factura !== "")) {
                            btnsolicitud = '<div class="d-flex flex-column align-items-center justify-content-center"><a href="../../reportes/facturas/' + row.Referencia_Nexen + '/' + row.Num_Operacion + '.pdf" class="custom-icon dowloand" target="_blank"><i class="bi bi-save-fill"></i></a><span>Descargar</span></div>';
                        } else {
                            btnsolicitud = '<div class="d-flex flex-column align-items-center justify-content-center"><div class="custom-icon danger text-white"><i class="bi bi-x-circle-fill"></i></div><span>No disponible</span></div>';
                        }
                        
                        return btnsolicitud;
                    }
                },
                {
                    "targets": 7,
                    "render": function (data, type, row) {
                        if (row.Estatus === 'PENDIENTE') {
                            return '<div class="d-flex align-items-center justify-content-center">' +
                                '<div class="custom-icon setting">' +
                                '<div class="dropdown">' +
                                '<button type="button" class="btn dropdown-toggle" aria-expanded="false" id="dropdownMenuButton_' + row.Num_Operacion + '">' +
                                '<i class="bi bi-gear-fill "></i>' +
                                '</button>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_' + row.Num_Operacion + '">' +
                                '<li><a class="dropdown-item" href="#" onclick="abrirModalComprobantePago(' + row.Num_Operacion + ', \'ACEPTADO\', \'' + row.Referencia_Nexen + '\', \'' + row.Contenedor + '\', \'' + row.Concepto + '\', \'' + row.Tipo_Solicitud + '\', ' + row.Monto + ', \'' + row.Moneda + '\', \'' + cambiarEspaciosAGuionBajo(row.Usuario) + '\');">Aceptar</a></li>' +
                                '<li><a class="dropdown-item" href="#" onclick="abrirModalRechazado(' + row.Num_Operacion + ', \'RECHAZADO\', \'' + row.Referencia_Nexen + '\', \'' + row.Contenedor + '\', \'' + row.Concepto + '\', \'' + row.Tipo_Solicitud + '\', ' + row.Monto + ', \'' + row.Moneda + '\', \'' + cambiarEspaciosAGuionBajo(row.Usuario) + '\');">Rechazar</a></li>' +
                                '<li><a class="dropdown-item" href="#" onclick="updateEstatusSinFondos(' + row.Num_Operacion + ', \'SIN FONDOS\', \'' + row.Referencia_Nexen + '\','+' \'' + row.Contenedor + '\', \'' + row.Concepto + '\', \'' + row.Tipo_Solicitud + '\', ' + row.Monto + ', \'' + row.Moneda + '\', \'' + cambiarEspaciosAGuionBajo(row.Usuario) + '\');">Sin fondos</a></li>' +
                                '</ul>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else {
                            return '<div class="d-flex align-items-center justify-content-center"><div class="custom-icon true text-white"><span class="bi bi-check-lg"></span></div></div>Atendido';
                        }
                    }
                },
                {
                    "targets": 8,
                    "render": function (data, type, row) {
                        return '<div class="d-flex align-items-center justify-content-center"><div class="custom-icon info text-white" onclick="mostrarDetallesEnModal(\'' + encodeURIComponent(JSON.stringify(row)) + '\');"><i class="bi bi-patch-question-fill"></i></div></div>Más...';
                    }
                }


            ],
            "error": function (xhr, status, error) {
                console.error(error);
            }
        });

    }, 0);

    return html;
}


function mostrarDetallesEnModal(data) {
    const decodedData = decodeURIComponent(data);
    const item = JSON.parse(decodedData);
    var fields = {
        'Tipo_Operacion': 'Tipo Operación',
        'Contenedor': 'Contenedor 1',
        'Contenedor_2': 'Contenedor 2',
        'Guia_House': 'Guía House',
        'Cliente': 'Cliente',
        'Operador': 'Operador',
        'Banco_Destino': 'Banco Destino',
        'Usuario': 'Usuario',
        'Fechope': 'Fecha',
        'Hora': 'Hora',
        'Observaciones': 'Observaciones',
    };

    var fechopeValue = item['Fechope'] + ' ' + item['Hora'];

    var noDataFields = [];
    var modalContent = '<div class="table-responsive">';
    modalContent += '<table id="showInfoNexenTable" class="table table-bordered table-custom" style="width:100%">';
    modalContent += '<thead class="nft bg-dark text-white"><tr><th>DATOS</th><th>INFORMACIÓN</th></tr></thead>';
    modalContent += '<tbody>';

    Object.keys(fields).forEach(function (key) {
        var value = item[key] || '<span class="badge bg-danger">No contiene</span>';
        if (value === '<span class="badge bg-danger">No contiene</span>') {
            noDataFields.push(fields[key]);
        } else {
            if (key === 'Fechope') { // Si es 'Fechope', mostramos el valor concatenado
                modalContent += `<tr><td>${fields[key]}</td><td>${fechopeValue}</td></tr>`;
            } else if (key !== 'Hora') { // Si no es 'Hora', mostramos el valor normal
                modalContent += `<tr><td>${fields[key]}</td><td>${value}</td></tr>`;
            }
        }
    });

    if (noDataFields.length > 0) {
        modalContent += `<tr><td>${noDataFields.join('<br>')}</td><td><span class="badge bg-danger">No contiene</span></td></tr>`;
    }

    modalContent += '</tbody></table>';
    modalContent += '</div>';

    $('#showInfoNexen .modal-body').html(modalContent);


    $('#showInfoNexenTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false
    });

    $('#showInfoNexen').modal('show');
}

