let tablefacturas;
function modalVerFacturas() {
    // Mostrar mensaje de carga
    $('#loadingMessage').show();
    var referencia_nexen = $('#referencia_nexen').val();
    tablefacturas = $('#tablefacturas').DataTable({
        aProcessing: true,
        aServerSide: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        ajax: {
            url: "../include/Facturas/cargarFacturasDetalles.php?referencia_nexen=" + referencia_nexen,
            dataSrc: ""
        },
        columns: [
            // { "data": "Id_TipoCambio" }, 
            { data: "Referencia_Nexen"},
            { data: "Proveedor"},
            { data: "Tax_Id"},
            { data: "Numero_Factura"},
            { data: "Fecha_Factura"},
            { data: "Importador_Exportador"},
            { data: "Total_General"},
            { data: "Usuario"},
            { data: "Detalles"},
            { data: "Invoice"},
            { data: "Packing_List"},
            { data: "Editar"},
            { data: "Eliminar"}

        ],
        bDestroy: true,
        iDisplayLength: 10,
        order: [[0, "desc"]],
        searching: true,
        scrollY: true,
        fixedHeader: true, // Fijar la cabecera de la tabla
        scrollX: true
    });

    // Ocultar mensaje de carga y mostrar tabla cuando la tabla se dibuje
    $('#tablefacturas').on('init.dt draw.dt', function () {
        $('#loadingMessage').hide();
    });

    $('#modalVerFacturas').modal('show');
}function initializePage() {
    setSelects();
}

window.onload = initializePage;

/**
 * Establece los selects dinámicamente
 */
function setSelects(){
    var selects = [
        { id: 'Id', descripcion: 'Descripcion', idSelect: 'incoterms', action: 'incoterms' },
        { id: 'Id_medida', descripcion: 'Medida', idSelect: 'medida', action: 'extent' },
        { id: 'ID_MONEDA', descripcion: 'PREFIJO', idSelect: 'modal_moneda', action: 'currency' },
        { id: 'proveedor', descripcion: 'proveedor', idSelect: 'proveedor_fact', action: 'provider' }
    ];
    selects.forEach(function(select) {
        generalOptionsSelector(select.id, select.descripcion, select.idSelect, select.action);
    });
}

/**
 * Obtiene y agrega opciones a un select.
 *
 * @param {string} dataIdField El campo de ID en los datos recibidos.
 * @param {string} dataDescriptionField El campo de descripción en los datos recibidos.
 * @param {string} selectId El ID del select donde se agregarán las opciones.
 * @param {string} action La acción que se enviará al servidor para obtener los datos.
 */

function generalOptionsSelector(dataIdField, dataDescriptionField, selectId, action) {
    var selectElement = document.getElementById(selectId);
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../include/Facturas/GetSelectsInvoice.php?action=' + action, true);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.onload = function () {
        if (xhr.status == 200) {
            var responseData = JSON.parse(xhr.responseText);
            responseData.forEach(function(item) {
                var option = document.createElement('option');
                option.text = item[dataDescriptionField]; 
                option.value = item[dataIdField]; 
                selectElement.appendChild(option);
            });
            $(selectElement).selectpicker('refresh');
        } else {
            console.error('Error al obtener los datos del servidor: ' + xhr.status);
        }
    };

    xhr.send();
}

/**
 * Realiza una llamada AJAX para obtener información del proveedor por su nombre.
 *
 * @param {string} action - La acción para la solicitud.
 * @param {string} id - El nombre del proveedo.
 * @param {function} callback - La función de retorno.
 */
function getProviderByName(action, id, callback) {
    $.ajax({
        url: '../include/Facturas/GetSelectsInvoice.php',
        type: 'GET',
        data: { 
            action: action,
            id: id 
        },
        success: function(response) {
            callback(response);
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
}


/**
 * Calcula el precio unitario y lo muestra en los campos de precio unitario y precio "fishing(dato que se muestra al usuario)".
 */
function calcularPrecioTotal() {
    var cantidad = parseFloat($('#modal_cantidad').val());
    var precioTotal = parseFloat($('#precio_total').val());

    if (!isNaN(cantidad) && !isNaN(precioTotal)) {
        var precioUnitario = precioTotal / cantidad;

        $('#precio_unitario').val(precioUnitario.toFixed(7));
        $('#precio_fishing').val(precioUnitario.toFixed(3));
    }
}

/**
 * Realiza una llamada AJAX para obtener información del proveedor según el ID seleccionado.
 */
var selectElement = document.getElementById('proveedor_fact');
selectElement.addEventListener('change', function() {
    var action = 'selectInfo';
    var idText = selectElement.value;
    getProviderByName(action, idText, function(response) {
        try {
            var data = typeof response === 'string' ? JSON.parse(response) : response;
            $('#tax_id').val(data[0].codigo);
            $('#modal_domicilio_proveedor').val(data[0].domicilio);
        } catch (e) {
            console.error('Error parsing JSON response: ' + e);
        }
    });
});


function modalCargarFactura() {
    var nombreOperador = $('#nombre_operador_o').val();

    if (nombreOperador === '') {
        const mensaje='Falta seleccionar el campo de nombre del operador';
        SweetView(mensaje);
    } else {
        $.ajax({
            url: '../include/Facturas/cargarFacturas.php',
            method: 'POST',
            data: { opcion: 'leerEmpresas', nombreOperador: nombreOperador },
            dataType: 'json',
            success: function (response) {
                // Procesar la respuesta del servidor y mostrarla en el modal

                // Ejemplo: Recorrer los datos y mostrarlos en la consola
                for (var i = 0; i < response.length; i++) {
                    var empresa = response[i];

                    $('#modal_nombre_operador').val(empresa.Razon_Social);
                    $('#modal_rfc_operador').val(empresa.RFC);
                    $('#modal_domicilio_operador').val(empresa.DOMICILIO_FISCAL);

                    // console.log(empresa.ID_EMPRESA, empresa.Razon_Social, empresa.RFC, empresa.DOMICILIO_FISCAL, empresa.REPRESENTANTE_LEGAL, empresa.ESTATUS);
                }
            },
            error: function () {
                const mensaje='Error al obtener los datos del servidor';
                SweetView(mensaje);
            },
        });
    }
}




