var currentUrl = window.location.href;
let targetUrl = '';

var fechasel = ''; //variable general para capturar nombre de fecha al abrir la modal

//Nombre de los campos en el filtro
let selc = [
    '#operacion',
    '#usuario',
    '#referencia',
    '#refcliente',
    '#cliente',
    '#bl',
    '#contenedor',
    '#contenedor2',
    '#pedimento',
    '#patente',
    '#fechao',
    '#fechaa',
    '#fechan',
    '#fecham',
    '#fechap',
    '#impexp',
    '#cve',
    '#estaus',
    '#daduana',
    '#ttrafico',
    '#eco',
    '#detm',
];
let limite = [10, 14]; //especifica en que posicion estan los campos fechas en este caso del 7 al 11

//Aqui guardo los valores de los campos de fecha inicial fecha final ejenplo datosFechas['#fechaa'][0] -> Fecha Inicial, datosFechas['#fechaa'][1] -> Fecha Final
let datosFechas = new Array(5);
datosFechas['#fechao'] = new Array();
datosFechas['#fechaa'] = new Array();
datosFechas['#fechan'] = new Array();
datosFechas['#fecham'] = new Array();
datosFechas['#fechap'] = new Array();

const container_terminar = document.getElementById('fechasfiltro');
const modal_terminar = new bootstrap.Modal(container_terminar);

$(document).ready(function () {
    $('#example').dataTable({
        aProcessing: true,
        aServerSide: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json',
        },

        ajax: {
            url: ' ../include/operaciones_ver.php',
            dataSrc: '',
        },
        columns: [
            { data: 'NUM_OPERACION' },
            { data: 'Usuario' },
            { data: 'REFERENCIA_NEXEN' },
            { data: 'Referencia_Cliente' },
            { data: 'Cliente' },
            { data: 'BL' },
            { data: 'Contenedor_1' },
            { data: 'Contenedor_2' },
            { data: 'No_Pedimento' },
            { data: 'Patente' },
            { data: 'FECHOPE' },
            { data: 'Fecha_Arribo' },
            { data: 'Fecha_Notificación' },
            { data: 'Fecha_Modulación' },
            { data: 'Fecha_Pago_Anticipo' },
            { data: 'Importador_Exportador' },
            { data: 'Clave_Pedimento' },
            { data: 'Estatus' },
            { data: 'DENOMINACION_ADUANA' },
            { data: 'tipo_trafico' },
            { data: 'NUMERO_ECONOMICO' },
            { data: 'DETALLE_MERCANCIA' },
        ],

        dom: 'lBfrtip',

        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible',
                },
                text: "<i class='fas fa-file-excel'></i> Excel",
                titleAttr: 'Esportar a Excel',
                className: 'btn btn-success',
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible',
                },
                text: "<i class='fas fa-file-csv'></i> CSV",
                titleAttr: 'Exportar a CSV',
                className: 'btn btn-info',
            },
            {
                text: '<i class="bi bi-file-earmark-spreadsheet"></i> Reporte de Mercancía',
                action: function (e, dt, button, config) {
                    $.ajax({
                        url: '../include/reporte_mercancia.php',
                        method: 'POST',
                        xhrFields: {
                            responseType: 'blob',
                        },
                        success: function (response) {
                            // Crear un enlace temporal y simular un clic para descargar el archivo Excel
                            const url = window.URL.createObjectURL(new Blob([response]));
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = 'datos_excel_mercancia.xls';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                        },
                        error: function (xhr, status, error) {
                            console.log('Error al descargar el archivo Excel:', error);
                        },
                    });
                },
                className: 'btn btn-success',
            },
            {
                text: '<i class="bi bi-file-earmark-spreadsheet"></i> Reporte de Retenidos',
                action: function (e, dt, button, config) {
                    $.ajax({
                        url: '../include/reporte_retenidos.php',
                        method: 'POST',
                        xhrFields: {
                            responseType: 'blob',
                        },
                        success: function (response) {
                            // Crear un enlace temporal y simular un clic para descargar el archivo Excel
                            const url = window.URL.createObjectURL(new Blob([response]));
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = 'datos_excel_retenidos.xls';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                        },
                        error: function (xhr, status, error) {
                            console.log('Error al descargar el archivo Excel:', error);
                        },
                    });
                },
                className: 'btn btn-danger',
            },
            {
                text: 'Crear Nueva Referencia',
                className: 'btn btn-success btn-open-modal',
                action: function (e, dt, node, config) {
                    $('#modalFiltro').modal('show');
                },
            },
            {
                text: 'Crear cliente',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    // $('#modalFiltro').modal('show');
                    $('#modalRegistrarCliente').modal('show');
                },
            },
        ],
        initComplete: function () {
            this.api()
                .columns()
                .every(function (i) {
                    var column = this;

                    $(selc[i] + 'check').prop('checked', true);

                    $(selc[i] + 'check').on('click', function (e) {
                        if ($(this).is(':checked')) {
                            column.visible(true);
                            $(selc[i]).prop('disabled', false);
                        } else {
                            column.visible(false);
                            $(selc[i]).prop('disabled', true);
                        }
                    });

                    if (i >= limite[0] && i <= limite[1]) {
                        $(selc[i]).on('click', function () {
                            modal_terminar.show();

                            $('#cabeceraModal').text(
                                'Filtro de ' +
                                    (this.id == 'fechao'
                                        ? 'fecha operacion'
                                        : this.id == 'fechaa'
                                        ? 'fecha arribo'
                                        : this.id == 'fechan'
                                        ? 'fecha notificacion'
                                        : this.id == 'fecham'
                                        ? 'fecha modulacion'
                                        : this.id == 'fechap'
                                        ? 'fecha pago'
                                        : '')
                            );

                            fechasel = this.id;

                            $('#fechai').val(datosFechas['#' + this.id][0]);
                            $('#fechaf').val(datosFechas['#' + this.id][1]);
                        });
                    } else {
                        var select = $(selc[i]).on('change', function () {
                            var regexr = '({search})';
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column
                                .search(
                                    val != ''
                                        ? regexr.replace(
                                              '{search}',
                                              isNaN(val) ? '(((' + this.value + ')))' : '^' + val + '$'
                                          )
                                        : '',
                                    val != '',
                                    val == ''
                                )
                                .draw();
                        });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                if(d !== null && d.trim() !== '') {
                                    select.append('<option value="' + d + '">' + d + '</option>');
                                }
                            });
                    }
                });

            this.css({ width: '100%', 'text-align': 'center', 'vertical-align': 'middle' });

            this.api()
                .columns()
                .every(function () {
                    var column = this;
                    $(column.header()).css({ width: '5.55%', 'text-align': 'center', 'vertical-align': 'middle' });
                });
        },
    });

    var table = $('#example').DataTable();

    $('#limpiar_fecha').on('click', function () {
        datosFechas['#' + fechasel][0] = '';
        datosFechas['#' + fechasel][1] = '';

        $('#fechai').val('');
        $('#fechaf').val('');

        var pos = selc.indexOf('#' + fechasel);
        table.column(pos).search('', true, false, false).draw();

        $('#' + fechasel + ' option').remove();
        $('#' + fechasel).append('<option value="0"></option>');
        $('#' + fechasel).val('0');
    });

    $('#filtrar_fecha').on('click', function () {
        datosFechas['#' + fechasel][0] = $('#fechai').val();
        datosFechas['#' + fechasel][1] = $('#fechaf').val();

        if ($('#fechaf').val() == '' || $('#fechaf').val() == '') {
            alert('No puedes tener campos vacios');
            die();
        }

        var pos = selc.indexOf('#' + fechasel);
        var cadenab = rango_fechas($('#fechai').val(), $('#fechaf').val());
        console.log(cadenab);
        table.column(pos).search(cadenab, true, false, false).draw();

        $('#' + fechasel + ' option').remove();
        $('#' + fechasel).append(
            '<option value="0">(' + $('#fechai').val() + ') - (' + $('#fechaf').val() + ')</option>'
        );
        $('#' + fechasel).val('0');

        modal_terminar.hide();
    });

    function rango_fechas(fecha_inicio, fecha_fin) {
        var fechaInicio = new Date(fecha_inicio);
        var fechaFin = new Date(fecha_fin);
        var cadena_filtro = '';
        var pipe = '|';

        while (fechaFin.getTime() >= fechaInicio.getTime()) {
            fechaInicio.setDate(fechaInicio.getDate() + 1);

            pipe = cadena_filtro == '' ? '' : '|';

            cadena_filtro +=
                pipe +
                '^' +
                fechaInicio.getFullYear() +
                '/' +
                (fechaInicio.getMonth() + 1).toString().padStart(2, '0') +
                '/' +
                fechaInicio.getDate().toString().padStart(2, '0') +
                '$|' +
                '^' +
                fechaInicio.getFullYear() +
                '-' +
                (fechaInicio.getMonth() + 1).toString().padStart(2, '0') +
                '-' +
                fechaInicio.getDate().toString().padStart(2, '0') +
                '$|' +
                '^' +
                fechaInicio.getDate().toString().padStart(2, '0') +
                '/' +
                (fechaInicio.getMonth() + 1).toString().padStart(2, '0') +
                '/' +
                fechaInicio.getFullYear() +
                '$|' +
                '^' +
                fechaInicio.getDate().toString().padStart(2, '0') +
                '-' +
                (fechaInicio.getMonth() + 1).toString().padStart(2, '0') +
                '-' +
                fechaInicio.getFullYear() +
                '$';
        }

        return cadena_filtro;
    }

    if (document.querySelector('#search-form')) {
        let formSearch = document.querySelector('#search-form');
        formSearch.onsubmit = function (e) {
            e.preventDefault();
            //obtenemos variables del formulario para buscar
            let contenedor = document.querySelector('#Contenedor').value.trim();
            let BL = document.querySelector('#BL').value.trim();
            let Pedimento = document.querySelector('#Pedimento').value.trim();

            let elementsValid = document.getElementsByClassName('valid');
            for (let i = 0; i < elementsValid.length; i++) {
                if (elementsValid[i].classList.contains('is-invalid')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Verifica los campos en Rojo!',
                    });
                    return false;
                }
            }
            if (contenedor != '') {
                enviarParametros(contenedor, '', '', '');
            } else if (BL != '') {
                enviarParametros('', BL, '', '');
            } else if (Pedimento != '') {
                enviarParametros('', '', Pedimento, '');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Un campo tiene que ser obligatorio!',
                });
            }
        };
    }
});
// Función para enviar los parámetros por AJAX
function enviarParametros(contenedor, BL, Pedimento, economico) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../include/buscaOpe.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Aquí puedes manejar la respuesta del servidor
            var objData = JSON.parse(xhr.responseText);
            console.log(objData);
            if (objData.msg == 'true') {
                let tabla = document.querySelector('#tableLista tbody');

                // Limpiar el contenido actual de la tabla
                tabla.innerHTML = '';

                // Iterar sobre los registros y agregar filas a la tabla
                objData[0].forEach(function (registro) {
                    let fila = document.createElement('tr');
                    let columnaUsuario = document.createElement('td');
                    let columnaReferencia = document.createElement('td');
                    let columnaContenedor = document.createElement('td');
                    let columnaBL = document.createElement('td');
                    let columnaPedimento = document.createElement('td');
                    let columnaAcciones = document.createElement('td');
                    let columnaCrear = document.createElement('td');

                    //crea el enlace por cada elemento
                    let enlace = document.createElement('a');
                    enlace.href = '../include/ver_operacion.php?referencia=' + registro.REFERENCIA_NEXEN; // Establecer la URL deseada
                    enlace.textContent = 'Ver detalle'; // Texto del enlace
                    enlace.classList.add('btn', 'btn-primary');

                    let crear = document.createElement('a');
                    crear.classList.add('btn', 'btn-success');
                    crear.setAttribute('data-bs-toggle', 'modal');
                    crear.setAttribute('data-bs-target', '#modalCrear');
                    crear.textContent = 'Nueva Referencia'; // Texto del crear

                    //asigna acada columna su valor
                    columnaAcciones.appendChild(enlace);
                    columnaCrear.appendChild(crear);
                    columnaUsuario.textContent = registro.Usuario;
                    columnaReferencia.textContent = registro.REFERENCIA_NEXEN;
                    columnaContenedor.textContent = registro.Contenedor_1;
                    columnaBL.textContent = registro.BL;
                    columnaPedimento.textContent = registro.No_Pedimento;

                    //envia los valores para mostrar en las columnas
                    fila.appendChild(columnaUsuario);
                    fila.appendChild(columnaReferencia);
                    fila.appendChild(columnaContenedor);
                    fila.appendChild(columnaBL);
                    fila.appendChild(columnaPedimento);
                    fila.appendChild(columnaAcciones);
                    fila.appendChild(columnaCrear);

                    tabla.appendChild(fila);
                });
                $('#modalLista').modal('show');
                $('#modalFiltro').modal('hide');
            } else {
                $('#modalFiltro').modal('hide');
                $('#modalCrear').modal('show');
            }
        }
    };

    let data =
        'contenedor=' +
        encodeURIComponent(contenedor) +
        '&BL=' +
        encodeURIComponent(BL) +
        '&Pedimento=' +
        encodeURIComponent(Pedimento);

    xhr.send(data);
}

//metodo para bloquear todos los campos menos el del contenedor
function verificarInputContenedor() {
    var input = document.getElementById('Contenedor');
    var valor = input.value.trim(); // Elimina espacios en blanco al inicio y al final

    if (valor === '') {
        //desbloquea los inputs
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = false;
        }
    } else {
        //bloquea los inpust
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].id !== 'Contenedor') {
                // Cambia 'input-bloqueado' por el ID de tu input específico
                inputs[i].disabled = true;
            }
        }
    }
}
//metodo para bloquear todos los campos menos el de BL
function verificarInputBL() {
    var input = document.getElementById('BL');
    var valor = input.value.trim(); // Elimina espacios en blanco al inicio y al final

    if (valor === '') {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = false;
        }
    } else {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].id !== 'BL') {
                // Cambia 'input-bloqueado' por el ID de tu input específico
                inputs[i].disabled = true;
            }
        }
    }
}
//metodo que bloquea los de mas campos menos el de pedimento
function verificarInputPedimento() {
    var input = document.getElementById('Pedimento');
    var valor = input.value.trim(); // Elimina espacios en blanco al inicio y al final

    if (valor === '') {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = false;
        }
    } else {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].id !== 'Pedimento') {
                // Cambia 'input-bloqueado' por el ID de tu input específico
                inputs[i].disabled = true;
            }
        }
    }
}
//metodo que bloquea todo los campos menos el economico
function verificarInputEconomico() {
    var input = document.getElementById('economico');
    var valor = input.value.trim(); // Elimina espacios en blanco al inicio y al final

    if (valor === '') {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = false;
        }
    } else {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].id !== 'economico') {
                // Cambia 'input-bloqueado' por el ID de tu input específico
                inputs[i].disabled = true;
            }
        }
    }
}
//test para validar texto
function testText(txtString) {
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9\s]+$/);
    if (stringText.test(txtString)) {
        return true;
    } else {
        return false;
    }
}
// test para validar numeros
function testEntero(intCant) {
    var intCantidad = new RegExp(/^([0-9])*$/);
    if (intCantidad.test(intCant)) {
        return true;
    } else {
        return false;
    }
}
//metodo para validar campos de texto
function fntValidText() {
    let validText = document.querySelectorAll('.validText');
    validText.forEach(function (validText) {
        validText.addEventListener('keyup', function () {
            let inputValue = this.value;
            if (!testText(inputValue)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                if (inputValue === '') {
                    this.classList.remove('is-invalid');
                    this.classList.remove('is-valid');
                }
            } else {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            }
        });
    });
}
//valida que sea solo numeros
function fntValidNumber() {
    let validNumber = document.querySelectorAll('.validNumber');
    validNumber.forEach(function (validNumber) {
        validNumber.addEventListener('keyup', function () {
            let inputValue = this.value;
            if (!testEntero(inputValue)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
                if (inputValue === '') {
                    this.classList.remove('is-valid');
                }
            }
        });
    });
}
//metodo para quitar comillas
function quitarComillas(elemento) {
    var valor = elemento.value;
    valor = valor.replace(/["']/g, ''); // reemplazar comillas dobles y simples con una cadena vacía
    elemento.value = valor;
}
// carga las funciones cada que carga la pagina
window.addEventListener(
    'load',
    function () {
        fntValidText();
        fntValidNumber();
    },
    false
);
function recarga() {
    var modal = document.getElementById('modalCrear');
    modal.addEventListener('hidden.bs.modal', function (event) {
        setTimeout(function () {
            window.location.reload(true);
        }, 1500);
    });
}
function limpiar() {
    var modal = document.getElementById('modalLista');
    modal.addEventListener('hidden.bs.modal', function (event) {
        setTimeout(function () {
            window.location.reload(true);
        }, 1500);
    });
}
function limpiarModals() {
    var modal = document.getElementById('modalFiltro');
    modal.addEventListener('hidden.bs.modal', function (event) {
        setTimeout(function () {
            window.location.reload(true);
        }, 1500);
    });
}

/**
 * --------------------------------------------------------------------------------
 *
 * MODAL PARA REGISTRO DE CLIENTE
 *
 * A partir de aqui, se ecuentra lo necesario para registrar un cliente
 * en el sistema.
 *
 * --------------------------------------------------------------------------------
 */
import { Cliente } from './helpers.js';
import FormUtilities from '../resources/js/FormUtilities.js';
const btnGuardarCliente = document.querySelector('#btnGuardarCliente');

const formClientes = new FormUtilities('#formCliente');

btnGuardarCliente.addEventListener('click', async () => {
    formClientes.validarCampos();
    const formData = formClientes.data;

    Cliente.requestURL = '../include/registrar_cliente.php';
    const cliente = new Cliente(formData);

    if (!formClientes.validarUserInput()) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Todos los campos son obligatorios',
        });
        return;
    }

    const registro = await cliente.guardar();

    if (registro.success) {
        formClientes.reset();
        $('#modalRegistrarCliente').modal('hide');

        Swal.fire({
            title: registro.message,
            icon: 'success',
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: registro.message,
        });
    }
});

/**
 * Limpiar formulario cuando se cierra el modal
 */
$('#modalRegistrarCliente').on('hidden.bs.modal', function () {
    formClientes.reset();
});


// Mostrar el spinner
function showSpinner() {
    document.getElementById('spinner').style.display = 'block';
}

// Ocultar el spinner
function hideSpinner() {
    document.getElementById('spinner').style.display = 'none';
}

// Esperar a que se cargue todo el contenido
window.addEventListener('load', function() {
    // Mostrar el spinner cuando se inicia la carga de la página
    showSpinner();

    // Ocultar el spinner después de un breve retraso
    setTimeout(function() {
        hideSpinner();
    }, 1000); // ajusta el tiempo según sea necesario
});
