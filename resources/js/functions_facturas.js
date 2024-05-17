function initializePage() {
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

function generalOptionsSelector(dataIdField, dataDescriptionField, selectId, action, id = null) {
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


function calcularPrecioTotal() {
    var cantidad = parseFloat($('#modal_cantidad').val());
    var precioTotal = parseFloat($('#precio_total').val());

    if (!isNaN(cantidad) && !isNaN(precioTotal)) {
        var precioUnitario = precioTotal / cantidad;

        $('#precio_unitario').val(precioUnitario.toFixed(7));
        $('#precio_fishing').val(precioUnitario.toFixed(3));
    }
}




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




