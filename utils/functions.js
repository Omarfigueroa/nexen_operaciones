
var commonOptions = {
    aProcessing: true,
    aServerSide: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
    },
    responsive: true,
    bDestroy: true,
    iDisplayLength: 5,
    searching: true,
    scrollY: true,
    scrollX: true,
  };
$(document).ready(function () {
   
  

    $('.selectpicker').selectpicker();

    //botones incrementar y disminuir decimales de precio unitario
    $('.decrease-btn').click(function () {
        decreaseDecimals();
    });

    $('.increase-btn').click(function () {
        increaseDecimals();
    });

    $('#precio_total, #modal_cantidad').change(function () {
        updateDivisionResult();
    });
    //termina incrementar y disminuir decimales precio unitario

    $('#modal_fecha_factura').change(function () {
        var fechaInput = $(this).val();
        //console.log(fechaInput);
        var fechaFormateada = formatearFecha(fechaInput);
        //console.log(fechaFormateada);
    });

    var facturaCount = 1;
    var total = 0;



    //Para calcular el total dinamicamente en el campo del modal
    // Evento keyup del campo de cantidad
    $('#modal_cantidad').keyup(calcularPrecioTotal);

    // Evento keyup del campo de precio unitario
    $('#precio_total').keyup(calcularPrecioTotal);

    // Función para calcular el precio total
 

    //Seccion modal edit detalles
    // Evento keyup del campo de cantidad
    $('#modal_cantidad_edit').keyup(calcularPrecioTotalEdit);

    // Evento keyup del campo de precio unitario
    $('#precio_total_edit').keyup(calcularPrecioTotalEdit);

    // Función para calcular el precio total
    function calcularPrecioTotalEdit() {
        var cantidad = parseFloat($('#modal_cantidad_edit').val());
        var precioTotal = parseFloat($('#precio_total_edit').val());

        if (!isNaN(cantidad) && !isNaN(precioTotal)) {
            var precioUnitario = precioTotal / cantidad;
            $('#precio_unitario_edit').val(precioUnitario.toFixed(7));
        }
    }

    //Para reinicialiar tabla de editar clientes
    $('#modal_editar_cliente').on('hidden.bs.modal', function () {
        // Destruir o reiniciar la DataTable al cerrar el modal
        if ($.fn.DataTable.isDataTable('#dataTableClientes')) {
            $('#dataTableClientes').DataTable().destroy();
        }
    });
});



function selectMoneda(moneda){
    if (document.querySelector('#modal_moneda_edit')) {
        let ajaxUrl = '../include/moneda.php';
        let request = new XMLHttpRequest();
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                // $('#Mensajeria').selectpicker('destroy');
                document.querySelector('#modal_moneda_edit').innerHTML = request.responseText;
                
                document.querySelector('#modal_moneda_edit').value =moneda;
                // $('#EditProveedor').selectpicker('render');

            }
        }
    }
}


function fntUpdate(id_catalogo, referencia, tipo_trafico) {
    let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = '../include/valida.php';

    // Ajustar la cadena de datos para enviar las variables por separado
    let strData = '&id_catalogo=' + id_catalogo;

    request.open('POST', ajaxUrl, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(strData);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            document.querySelector('#nombre').value = objData[0]['DOCUMENTO'];
            document.querySelector('#Referencia').value = referencia;
            document.querySelector('#id_catalogo').value = objData[0]['ID_CATALOGO_DOCUMENTOS'];
            document.querySelector('#Tipo_Ope').value = tipo_trafico;

            $('#modalUpload').modal('show');
        }
    };
}

function tipoTransporte(valor) {
    if (valor === 'CARRETERO-FERROVIARIO' || valor === 'CARRETERO') {
        document.getElementById('contenedor1').disabled = true;
        document.getElementById('contenedor2').disabled = true;
        document.getElementById('bl').disabled = true;
        document.getElementById('house').disabled = true;
        document.getElementById('contenedor1').value = '';
        document.getElementById('contenedor2').value = '';
        document.getElementById('bl').value = '';
        document.getElementById('house').value = '';
        document.getElementById('num_eco').disabled = false;
    } else if (valor === 'AEREO') {
        document.getElementById('bl').disabled = false;
        document.getElementById('house').disabled = false;
        document.getElementById('contenedor1').disabled = true;
        document.getElementById('contenedor2').disabled = true;
        document.getElementById('num_eco').disabled = true;

        document.getElementById('contenedor1').value = '';
        document.getElementById('contenedor2').value = '';
        document.getElementById('num_eco').value = '';
    } else {
        document.getElementById('contenedor1').disabled = false;
        document.getElementById('contenedor2').disabled = false;
        document.getElementById('num_eco').value = '';
        document.getElementById('bl').value = '';
        document.getElementById('house').value = '';
        document.getElementById('num_eco').disabled = true;
        document.getElementById('bl').disabled = true;
        document.getElementById('house').disabled = true;
    }
}

function mostrar_codigo_aduana(valor) {
    let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = '../include/recuperar_pto_llegada.php';
    let strData = 'valor=' + valor;
    request.open('POST', ajaxUrl, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(strData);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            console.log(objData);
            document.querySelector('#cve_aduana').value = objData[0]['Codigo'];
        }
    };
}


//function para hacer los insert de la factura y detalles factura
// Función para enviar la solicitud AJAX
function updateFacturasEdit() {

    //Editar facturaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    //Comprobar todos los campos llenos del formulario para darle update
    $('#modalEditarFacturas .form_facturas_edit .form-control').removeClass('is-invalid');

    var isValid = true;

    // Comprobar si los campos tienen valor
    $('#modalEditarFacturas .form_facturas_edit .form-control').each(function () {
        var valor = $(this).val();
        if (valor === '') {
            $(this).addClass('is-invalid');
            isValid = false;
        }
    });

    if (!isValid) {
        const mensaje='Por favor, complete todos los campos';
        SweetView(mensaje);
        return;
    }
    // Comprobar si la tabla tiene registros de partidas
    var rowCount = $('#tablaPartidasEditar tbody tr').length;
    if (rowCount === 0) {
        const mensaje='No hay partidas en la tabla. No se puede guardar';
        SweetView(mensaje);
        return;
    }

    // Obtener los valores de los input
    var opcion = 'UpdateOperacionFactura';

    var proveedor_fact_edit = $('#proveedor_fact_edit').val();
    var modal_pais_origen_edit = $('#modal_pais_origen_edit').val();
    var modal_domicilio_proveedor_edit = $('#modal_domicilio_proveedor_edit').val();
    var modal_rfc_operador_edit = $('#modal_rfc_operador_edit').val();
    var total_edit = $('.total_edit').text();
    var modal_num_factura_edit = $('#modal_num_factura_edit').val();
    var modal_fecha_factura_edit = $('#modal_fecha_factura_edit').val();
    var tax_id_edit = $('#tax_id_edit').val();
    var incoterms_edit = $('#incoterms_edit').val();
    var modal_nombre_operador_edit = $('#modal_nombre_operador_edit').val();
    var modal_domicilio_operador_edit = $('#modal_domicilio_operador_edit').val();
    var precio_total = $('#precio_total_edit').val();
    var moneda = $('#modal_moneda_edit').val();
    var precioUnitario = $('#precio_unitario_edit').val();
    var cantidad = $('#modal_cantidad_edit').val();

    //se muestra spinner loading
    $('#spinner_edit').removeClass('d-none');

    //bloquear con un disabled el boton de editar para que no lo aprenten
    $('#btnEditarFacturas').prop('disabled', true);

   

    // Enviar la solicitud AJAX
    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        data: {
            opcion: opcion,
            proveedor_fact_edit: proveedor_fact_edit,
            modal_pais_origen_edit: modal_pais_origen_edit,
            modal_domicilio_proveedor_edit: modal_domicilio_proveedor_edit,
            modal_rfc_operador_edit: modal_rfc_operador_edit,
            total_edit: total_edit,
            precio_total: precio_total,
            modal_num_factura_edit: modal_num_factura_edit,
            modal_fecha_factura_edit: modal_fecha_factura_edit,
            tax_id_edit: tax_id_edit,
            incoterms_edit: incoterms_edit,
            modal_nombre_operador_edit: modal_nombre_operador_edit,
            modal_domicilio_operador_edit: modal_domicilio_operador_edit,
        },
        dataType: 'json',
        success: function (response) {
            // Manejar la respuesta del servidor
            if (response.success) {
             

                var referencia_nexen = response.Referencia_Nexen;
                // Realizar el insert por cada registro en la tabla
                var registros_edit = $('#tablaPartidasEditar tbody tr');
                var numFactura_edit = $('#modal_num_factura_edit').val();
                var incoterms_edit = $('#incoterms_edit').val();

                var successShown = false; // Variable de control

                // Iterar sobre cada fila y asignar el número de partida
                registros_edit.each(function (index, element) {
                    var partida = (index + 1).toString(); // El número de partida es el índi
                    var descripcion = $(element).find('td:nth-child(2)').text();
                    var descripcion_i = $(element).find('td:nth-child(3)').text();
                   
                    var medida = $(element).find('td:nth-child(5)').text();

                   
                    var peso_bruto = $(element).find('td:nth-child(9)').text();
                    var peso_neto = $(element).find('td:nth-child(10)').text();
                    var mark = $(element).find('td:nth-child(11)').text();

     

                    $.ajax({
                        url: '../include/cargarFacturas.php',
                        method: 'POST',
                        data: {
                            opcion: 'insertarFacturaDetalle',
                            lastId: response.idFactura,
                            referencia_nexen: referencia_nexen,
                            numFactura: numFactura_edit,
                            incoterms: incoterms_edit,
                            partida: partida,
                            descripcion: descripcion,
                            descripcion_i: descripcion_i,
                            medida: medida,
                            total_partida: precio_total,
                            moneda: moneda,
                            peso_bruto: peso_bruto,
                            peso_neto: peso_neto,
                            mark: mark,
                            precioUnitario:precioUnitario,
                            cantidad:cantidad
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (!successShown) {
                                
                                alert(response.message);
                                successShown = true; // Establecer la variable de control en true para evitar mostrar el mensaje nuevamente
                                $('#spinner_edit').addClass('d-none');
                                location.reload();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('Error al realizar el insert para factura ' + response.idFactura);
                            console.log(error);
                        },
                    });
                });
            } else {
                // La inserción principal falló
                console.log(response.message);
                //location.reload();
            }
        },
        error: function (xhr, status, error) {
            // Manejar errores de la solicitud AJAX
            console.error(error);
        },
    });
}

//function para UPDATE DE FACTURAS Y DETALLES
//function para hacer los insert de la factura y detalles factura


//functiones para editar facturas
//functiones para editar facturas
function editarFacturas(btnEditarFacturas) {
    var numero_factura = $(btnEditarFacturas).attr('numero_factura');

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        data: {
            opcion: 'obtenerEditarFacturas',
            numero_factura: numero_factura,
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var facturas = response.data;
            
                if (facturas.length > 0) {
                    var factura = facturas[0];

                    $('#proveedor_fact_edit').val(factura.Proveedor);
                    $('#modal_pais_origen_edit').val(factura.PAIS_ORIGEN);
                    $('#modal_domicilio_proveedor_edit').val(factura.domicilio);
                    $('#modal_num_factura_edit').val(factura.Numero_Factura);
                    $('#modal_fecha_factura_edit').val(factura.Fecha_Factura);
                    $('#tax_id_edit').val(factura.Tax_Id);
                    //incoterms mas abajo, en el siguiente se establece***
                    $('#modal_nombre_operador_edit').val(factura.Importador_Exportador);
                    $('#modal_rfc_operador_edit').val(factura.RFC_Importador_Exportador);
                    $('#modal_domicilio_operador_edit').val(factura.Domicilio_Fiscal);
                    $('#precio_total_edit').val(factura.Total_General);
                    // $('#modal_moneda_edit').val(factura.Moneda);
                    $('#modal_cantidad_edit').val(factura.Cantidad);
                    selectMoneda(factura.Moneda);
                    $('#precio_unitario_edit').val(factura.Precio_Unitario);





                    // Logica para llenar la tabla de partidas
                    var partidas = response.partidas;

    
                // Inicializar DataTable para la tabla de partidas a editar
            var tablaPartidasEditar = $('#tablaPartidasEditar').DataTable(Object.assign({}, commonOptions, {
                data: partidas,
                columns: [
                    { data: 'Numero_Partida' },
                    { data: 'Descripcion_Cove' },
                    { data: 'Descripcion_cove_I' },
                    { data: 'Cantidad' },
                    { data: 'Unidad_Medida' },
                    { data: 'Precio_Unitario' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            var subtotal = parseFloat(data.Cantidad) * parseFloat(data.Precio_Unitario);
                            return subtotal.toFixed(7); // Mostrar el subtotal
                        }
                    },
                    { data: 'Moneda' },
                    { data: 'Peso_Bruto' },
                    { data: 'Peso_Neto' },
                    { data: 'Mark' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button type="button" class="btn btn-danger btn-borrarPartidaEdit" partida="' + data.Numero_Partida + '" factura="' + data.Numero_Factura + '" onclick="borrarPartidasEdit(this)">Borrar</button>';
                        }
                    }
                ]
            }));


                    // Mostrar el modal aquí después de que se ha construido la tabla
                    $('#modalEditarFacturas').modal('show');
                } else {
                    console.log('No tiene registros');
                }
            } else {
                console.log('Error al obtener las facturas');
                console.log(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.log('Error en la solicitud AJAX');
            console.log(error);
            console.log(xhr.responseText);
        },
    });
}


// Evento click del botón "Agregar"
$('#btnAgregar_edit').click(function () {
   
    $('.form-control').removeClass('is-invalid');

    /**
     * Validar el proveedor
     */
    const proveedorSelect = $('#proveedor_fact_edit')
    const inputPaisOrigen = $('#modal_pais_origen_edit')
    const inputNumFactura = $('#modal_num_factura_edit')
    const inputFechaFactura = $('#modal_fecha_factura_edit')
    const arrayValidator = [proveedorSelect, inputPaisOrigen, inputNumFactura, inputFechaFactura]

    

    var isValid = true;

    arrayValidator.forEach(element => {
        if(element.val() === null || element.val() === '') {
            $('#proveedor_fact_edit').selectpicker('destroy');
          
            if(element == proveedorSelect) {
                $('#proveedor_fact_edit').addClass('is-invalid');
                $('#proveedor_fact_edit').selectpicker('render');
            }
            element.addClass('is-invalid')
            element.removeClass('is-valid')
            isValid = false
        } else {
            element.removeClass('is-invalid')
            element.addClass('is-valid')
        }
    })


    // Crear un array de objetos con los campos requeridos
    var mensajeserrores = [
        { idcampo: '#proveedor_fact_edit', mensaje: 'Proveedor' },
        { idcampo: '#modal_pais_origen_edit', mensaje: 'País' },
        { idcampo: '#modal_num_factura_edit', mensaje: 'Número de Factura' },
        { idcampo: '#modal_fecha_factura_edit', mensaje: 'Fecha' },
        { idcampo: '#desc_factura_edit', mensaje: 'Descripción en Español' },
        { idcampo: '#desc_factura_i_edit', mensaje: 'Descripción en Inglés' },
        { idcampo: '#modal_cantidad_edit', mensaje: 'Cantidad' },
        { idcampo: '#modal_mark_edit', mensaje: 'Marcador' },
        { idcampo: '#modal_peso_bruto_edit', mensaje: 'Peso Bruto' },
        { idcampo: '#modal_peso_neto_edit', mensaje: 'Peso Neto' }
    ];
// Variable para almacenar los mensajes de error
var mensajesErroresHTML = '';

// Variable para indicar si hay errores

$('#modalEditarFacturas .form-control').each(function() {
    var valor = $(this).val();
    var idCampo = $(this).attr('id');
    var mensajeError = mensajeserrores.find(function(item) {
        return item.idcampo === '#' + idCampo;
    });

    if (valor === '' || valor === null ) {
        $(this).addClass('is-invalid');
        isValid = false;

        if (mensajeError) {
            mensajesErroresHTML += `${mensajeError.mensaje}, `;
        }
    }
});

// Mostrar SweetAlert con los mensajes de error
if (!isValid) {
    const mensaje= mensajesErroresHTML;
    SweetView(mensaje);
    return;
}
    // Obtener el número de partida
    var numeroPartida; // Declarar la variable sin asignar un valor

    var tablaPartidas = $('.tabla-partidas tbody');
    if (tablaPartidas.children().length > 0) {
        // Si la tabla tiene filas, obtener el número de partida de la última fila y sumar 1
        var ultimaFila = tablaPartidas.children().last();
        var numeroPartidaTexto = ultimaFila.find('td:first-child').text();
        numeroPartida = parseInt(numeroPartidaTexto) + 1;
    } else {
        // Si la tabla no tiene filas, asignar el valor predeterminado de 1 al número de partida
        numeroPartida = 1;
    }

    // Obtener los valores de los campos
    var descFactura = $('#desc_factura_edit').val();
    var descFacturaI = $('#desc_factura_i_edit').val();
    var cantidad = $('#modal_cantidad_edit').val();
    var medida = $('#medida_edit').val();
    var precioUnitario = $('#precio_unitario_edit').val();
    var moneda = $('#modal_moneda_edit').val();
    var precioTotal = $('#precio_total_edit').val();
    var mark = $('#modal_mark_edit').val();
    var pesoBruto = $('#modal_peso_bruto_edit').val();
    var pesoNeto = $('#modal_peso_neto_edit').val();

    //obtiene el numero de factura
    var Numero_Factura = $('#modal_num_factura_edit').val();

    // Crear la nueva fila HTML con los valores de los campos
    var nuevaFila =
        '<tr>' +
        '<td class="numero-partida">' +
        numeroPartida +
        '</td>' +
        '<td>' +
        descFactura +
        '</td>' +
        '<td>' +
        descFacturaI +
        '</td>' +
        '<td>' +
        cantidad +
        '</td>' +
        '<td>' +
        medida +
        '</td>' +
        '<td>' +
        precioUnitario +
        '</td>' +
        '<td>' +
        precioTotal +
        '</td>' +
        '<td>' +
        moneda +
        '</td>' +
        '<td>' +
        pesoBruto +
        '</td>' +
        '<td>' +
        pesoNeto +
        '</td>' +
        '<td>' +
        mark +
        '</td>' +
        '<td><button type="button" class="btn btn-danger btn-borrarPartidaEdit" temporal partida="' +
        numeroPartida +
        '" factura="' +
        Numero_Factura +
        '" onclick="borrarPartidasEdit(this)">Borrar</button></td>' + // Agregar botón "Borrar"
        '</tr>';

    // Agregar la nueva fila al contenedor de la tabla
    $('.tabla-partidas tbody').append(nuevaFila);

    $('#desc_factura_edit').val('');
    $('#desc_factura_i_edit').val('');
    $('#modal_cantidad_edit').val('');
    $('#precio_unitario_edit').val('');
    $('#modal_peso_bruto_edit').val('');
    $('#modal_peso_neto_edit').val('');
    $('#precio_total_edit').val('');
    $('#modal_mark_edit').val(mark);

    // Calcular y actualizar el total
    var partidas = obtenerPartidasEdit(); // Obtener todas las partidas de la tabla
    var total = calcularTotalEdit(partidas); // Calcular el nuevo total
    $('#tablaPartidasEditar tfoot td .total_edit').text(total.toFixed(7)); // Actualizar el valor en el pie de la tabla
});

// Obtener todas las partidas de la tabla
function obtenerPartidasEdit() {
    var partidas = [];
    $('#tablaPartidasEditar tbody tr').each(function () {
        var partida = {
            Cantidad: $(this).find('td:nth-child(4)').text(),
            Precio_Unitario: $(this).find('td:nth-child(6)').text(),
        };
        partidas.push(partida);
    });
    return partidas;
}

// Calcula el total de la columna de totales en editar facturas
function calcularTotalEdit(partidas) {
    var total = 0;
    for (var i = 0; i < partidas.length; i++) {
        var partida = partidas[i];
        var subtotal = parseFloat(partida.Cantidad) * parseFloat(partida.Precio_Unitario);
        total += subtotal;
    }
    return total;
}

//Borrar factura y detalle facturas
function borrarFacturas(btnBorrarFacturas) {
    var id_factura = $(btnBorrarFacturas).attr('id_factura');
    var referencia_nexen = $(btnBorrarFacturas).attr('referencia_nexen');
    var numero_factura = $(btnBorrarFacturas).attr('numero_factura');
    var fecha_factura = $(btnBorrarFacturas).attr('fecha_factura');
    var tax_id = $(btnBorrarFacturas).attr('tax_id');
    var usuario = $(btnBorrarFacturas).attr('usuario');

    console.log(numero_factura + ' ' + id_factura);
    //MODAL CONFIRMACION Y BORRADO DE FACTURAS Y PARTIDAS
    // Agregar un evento click al botón de borrado

    // Mostrar el modal de confirmación
    $('#confirmDeleteModal').modal('show');
    $('#borrarNumFactura').text(numero_factura);

    // Agregar un evento click al botón de confirmación de borrado
    $('#confirmDeleteButton').click(function () {
        // Lógica para realizar el borrado
        // Aquí puedes realizar la lógica para mostrar los detalles de la factura en otro modal o realizar cualquier otra acción
        //console.log('Mostrar detalles de la factura con ID: ' + id_factura);
        $.ajax({
            url: '../include/cargarFacturas.php',
            method: 'POST',
            data: {
                opcion: 'borrarFacturayDetalles',
                id_factura: id_factura,
                numero_factura: numero_factura,
                referencia_nexen: referencia_nexen,
                fecha_factura: fecha_factura,
                tax_id: tax_id,
                usuario: usuario,
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    console.log(response.message);
                    location.reload(); // Recargar la página
                } else {
                    console.log('Error al hacer la consulta');
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log('Error en la solicitud AJAX');
                console.log(error);
                console.log(xhr.responseText);
            },
        });
        // Una vez que se complete la acción de borrado, puedes cerrar el modal
        $('#confirmDeleteModal').modal('hide');
    });
}

function formatearFecha(fecha) {
    var partesFecha = fecha.split('-');
    var anio = partesFecha[0];
    var mes = partesFecha[1];
    var dia = partesFecha[2];
    return anio + '/' + mes + '/' + dia;
}

function cambiarProveedor(proveedor) {
    var proveedorValue = $(proveedor).val(); // Obtiene el valor seleccionado del proveedor
    var proveedorTaxId = $(proveedor).find('option:selected').attr('taxid'); // Obtiene el valor del atributo taxid seleccionado
    var proveedorDomicilio = $(proveedor).find('option:selected').attr('domicilio');

    $('#modal_domicilio_proveedor').val(proveedorDomicilio);
    $('#tax_id').val(proveedorTaxId);

    console.log(proveedorTaxId);

    if (!proveedorValue) {
        editarProveedor(false);
    } else {
        editarProveedor(proveedorTaxId);
    }
}

function cambiarProveedorEdit(proveedor) {
    var proveedorValue = $(proveedor).val(); // Obtiene el valor seleccionado del proveedor
    var proveedorTaxId = $(proveedor).find('option:selected').attr('taxid'); // Obtiene el valor del atributo taxid seleccionado
    var proveedorDomicilio = $(proveedor).find('option:selected').attr('domicilio');

    $('#modal_domicilio_proveedor_edit').val(proveedorDomicilio);
    $('#tax_id_edit').val(proveedorTaxId);

    console.log(proveedorDomicilio);
    console.log(proveedorTaxId);
}

function borrarPartidas(button) {
    var partida = $(button).attr('partida');
    var factura = $(button).attr('factura');

    // Realiza las operaciones que deseas con los valores de partida y factura
    // Por ejemplo, puedes mostrarlos en una alerta
    //alert("Partida: " + partida + "\nFactura: " + factura);

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        data: {
            opcion: 'borrarPartidas',
            partida: partida,
            factura: factura,
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const mensaje=response.message;
                SweetViewTrue(mensaje, () => {
                    location.reload();
                });
            } else {
                const mensaje=response.message;
                SweetView(mensaje, () => {
                    location.reload();
                });
            }
        },
        error: function (xhr, status, error) {
            console.log('Error en la solicitud AJAX');
            console.log(error);
            console.log(xhr.responseText);
        },
    });
}
//Borrar partidas edit
function borrarPartidasEdit(button) {
    // Verificar si el botón tiene el atributo 'mi-atributo'
    if ($(button).attr('temporal') !== undefined) {
        // El botón tiene el atributo 'mi-atributo'
        //console.log("El botón tiene el atributo 'mi-atributo'");
        // Obtener la fila a eliminar
        var fila = $(button).closest('tr');

        // Eliminar la fila de la tabla
        fila.remove();

        // Calcular y actualizar el total
        var partidas = obtenerPartidasEdit(); // Obtener todas las partidas de la tabla
        var total = calcularTotalEdit(partidas); // Calcular el nuevo total
        $('#tablaPartidasEditar tfoot td .total_edit').text(total.toFixed(7)); // Actualizar el valor en el pie de la tabla
    } else {
        // El botón no tiene el atributo 'mi-atributo'
        //console.log("El botón no tiene el atributo 'mi-atributo'");
        var partida = $(button).attr('partida');
        var factura = $(button).attr('factura');

        // Realiza las operaciones que deseas con los valores de partida y factura
        // Por ejemplo, puedes mostrarlos en una alerta
        //alert("Partida: " + partida + "\nFactura: " + factura);

        $.ajax({
            url: '../include/cargarFacturas.php',
            method: 'POST',
            data: {
                opcion: 'borrarPartidas',
                partida: partida,
                factura: factura,
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    var fila = $(button).closest('tr');

                    // Eliminar la fila de la tabla
                    fila.remove();

                    // Calcular y actualizar el total
                    var partidas = obtenerPartidasEdit(); // Obtener todas las partidas de la tabla
                    var total = calcularTotalEdit(partidas); // Calcular el nuevo total
                    $('#tablaPartidasEditar tfoot td .total_edit').text(total.toFixed(7)); // Actualizar el valor en el pie de la tabla

                    alert(response.message);
                    //location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log('Error en la solicitud AJAX');
                console.log(error);
                console.log(xhr.responseText);
            },
        });
    }
}

function fntDownload(id_catalogo, referencia_nexen) {
    let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = '../include/download.php';

    // Ajustar la cadena de datos para enviar las variables por separado
    let strData = 'referencia_nexen=' + referencia_nexen + '&id_catalogo=' + id_catalogo;

    request.open('POST', ajaxUrl, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.responseType = 'blob';

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // Crear un enlace temporal para descargar el archivo
            var url = window.URL.createObjectURL(request.response);
            var a = document.createElement('a');
            a.href = url;
            a.download;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    };

    request.send(strData);
}
function fntDelet(id_catalogo, referencia_nexen) {
    let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = '../include/Delet.php';

    // Ajustar la cadena de datos para enviar las variables por separado
    let strData = 'referencia_nexen=' + referencia_nexen + '&id_catalogo=' + id_catalogo;

    request.open('POST', ajaxUrl, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(strData);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            location.reload(true);
        }
    };
}
function fntView(id_catalogo, referencia_nexen) {
    let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = '../include/download.php';

    // Ajustar la cadena de datos para enviar las variables por separado
    let strData = 'referencia_nexen=' + referencia_nexen + '&id_catalogo=' + id_catalogo;

    request.open('POST', ajaxUrl, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.responseType = 'blob';

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            // Crear un enlace temporal para descargar el archivo
            var url = window.URL.createObjectURL(request.response);
            var a = document.createElement('a');
            a.href = url;
            a._blank;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    };

    request.send(strData);
}

//PRECIO UNITARIO INC DISM

function decreaseDecimals() {
    var input = $('#precio_unitario');
    if (input.val() !== '') {
        var value = input.val();
        var decimalIndex = value.indexOf('.');
        if (decimalIndex !== -1) {
            var decimalPart = value.substring(decimalIndex + 1);
            if (decimalPart.length > 1) {
                decimalPart = decimalPart.substring(0, decimalPart.length - 1);
                value = value.substring(0, decimalIndex + 1) + decimalPart;
            }
        }
        input.val(value);
        updateDivisionResult();
    }
}

function increaseDecimals() {
    var input = $('#precio_unitario');
    if (input.val() !== '') {
        var value = input.val();
        var decimalIndex = value.indexOf('.');
        if (decimalIndex !== -1) {
            var decimalPart = value.substring(decimalIndex + 1);
            decimalPart += '0';
            value = value.substring(0, decimalIndex + 1) + decimalPart;
        } else {
            value += '.00';
        }
        input.val(value);
        updateDivisionResult();
    }
}

function updateDivisionResult() {
    var precioTotal = parseFloat($('#precio_total').val());
    var modalCantidad = parseFloat($('#modal_cantidad').val());

    if (!isNaN(precioTotal) && !isNaN(modalCantidad) && modalCantidad !== 0) {
        var divisionResult = precioTotal / modalCantidad;
        var input = $('#precio_unitario');
        var currentDecimals = input.val().split('.')[1];
        var decimalsToShow = currentDecimals ? currentDecimals.length : 0;
        var formattedResult = divisionResult.toFixed(decimalsToShow);
        input.val(formattedResult);
    }
}

//TERMINA PRECIO UNITARIO

//editar proveedores!
function editarProveedor(taxID) {
    // Realizar acciones de edición para el proveedor seleccionado
    //console.log('tax id: ' + taxID);
    if (taxID === false) {
        $('#btnEditarProveedor').prop('disabled', true);
    } else {
        $('#btnEditarProveedor').prop('disabled', false);

        // Asociar el evento click al botón btnEditarProveedor
        $('#btnEditarProveedor').on('click', function () {
            // Realizar la solicitud AJAX
            $.ajax({
                url: '../include/cargarFacturas.php',
                method: 'POST',
                data: {
                    opcion: 'leerProveedor',
                    taxID: taxID,
                },
                success: function (response) {
                    if (response.success) {
                        console.log(response.proveedor);

                        //asignamos los valores en los input
                        $('#editar_tax_id').val(response.proveedor['codigo']);
                        $('#new_tax_id').val(response.proveedor['codigo']);
                        $('#editar_proveedor').val(response.proveedor['Proveedor']);
                        $('#editar_domicilio').val(response.proveedor['domicilio']);
                        $('#editar_email').val(response.proveedor['correo']);
                        $('#editar_whatsapp').val(response.proveedor['whatsapp']);

                        // Abrir el modal solo si se recibe una respuesta exitosa
                        $('#modal_editar_proveedor').modal('show');
                    } else {
                        console.log(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Manejo de errores
                    console.log('Error en la solicitud AJAX');
                    console.log(xhr.responseText);
                },
            });
        });
    }

    // Resto de tu lógica de edición...
}

//verificar contraseña de supervision
function verificarContraseña() {
    var password = $('#editar_pass').val();
    return password === 'NEXOPE2023' && password !== '';
}

function updateProveedor() {
    if (verificarContraseña()) {
        var editar_tax_id = $('#editar_tax_id').val();
        var new_tax_id = $('#new_tax_id').val();
        var editar_proveedor = $('#editar_proveedor').val();
        var editar_domicilio = $('#editar_domicilio').val();
        var editar_email = $('#editar_email').val();
        var editar_whatsapp = $('#editar_whatsapp').val();

        $.ajax({
            url: '../include/cargarFacturas.php',
            method: 'POST',
            dataType: 'json',
            data: {
                opcion: 'updateProveedor',
                editar_tax_id: editar_tax_id,
                new_tax_id: new_tax_id,
                editar_proveedor: editar_proveedor,
                editar_domicilio: editar_domicilio,
                editar_email: editar_email,
                editar_whatsapp: editar_whatsapp,
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    // Abrir el modal solo si se recibe una respuesta exitosa
                    $('#modal_editar_proveedor').modal('hide');
                    location.reload();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                // Manejo de errores
                console.log('Error en la solicitud AJAX');
                console.log(xhr.responseText);
            },
        });
    } else {
        alert('Contraseña incorrecta o campo contraseña vacío');
        $('#editar_pass').addClass('is-invalid');
    }
}

function deleteProveedor() {
    if (verificarContraseña()) {
        var confirmacion = confirm('¿Estás seguro de eliminar este proveedor? Esta acción no se puede deshacer.');

        if (confirmacion) {
            var editar_tax_id = $('#editar_tax_id').val();

            $.ajax({
                url: '../include/cargarFacturas.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    opcion: 'deleteProveedor',
                    editar_tax_id: editar_tax_id,
                },
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        // Cerrar el modal si se recibe una respuesta exitosa
                        $('#modal_editar_proveedor').modal('hide');
                        location.reload();
                    } else {
                        console.log(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Manejo de errores
                    console.log('Error en la solicitud AJAX');
                    console.log(xhr.responseText);
                },
            });
        }
    } else {
        alert('Contraseña incorrecta o campo contraseña vacío');
        $('#editar_pass').addClass('is-invalid');
    }
}


function verificarContraseñaClientes() {
    var password = $('#pw_cliente_edit').val();
    return password === 'NEXOPE2023' && password !== '';
}

function updateCliente() {
    if (verificarContraseñaClientes()) {
        $('#pw_cliente_edit').removeClass('is-invalid');
        //console.log('La contraseña es correcta y pasa');

        var razon_social_cliente_edit = $('#razon_social_cliente_edit').val();
        var rfc_cliente_edit = $('#rfc_cliente_edit').val();
        var telefono_cliente_edit = $('#telefono_cliente_edit').val();
        var movil_cliente_edit = $('#movil_cliente_edit').val();
        var nombre_contacto_edit = $('#nombre_contacto_edit').val();
        var email_cliente_1_edit = $('#email_cliente_1_edit').val();
        var email_cliente_2_edit = $('#email_cliente_2_edit').val();
        var dom_cliente_edit = $('#dom_cliente_edit').val();

        $.ajax({
            url: '../include/cargarFacturas.php',
            method: 'POST',
            dataType: 'json',
            data: {
                opcion: 'updateCliente',
                razon_social_cliente_edit: razon_social_cliente_edit,
                rfc_cliente_edit: rfc_cliente_edit,
                telefono_cliente_edit: telefono_cliente_edit,
                movil_cliente_edit: movil_cliente_edit,
                nombre_contacto_edit: nombre_contacto_edit,
                email_cliente_1_edit: email_cliente_1_edit,
                email_cliente_2_edit: email_cliente_2_edit,
                dom_cliente_edit: dom_cliente_edit,
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    // Abrir el modal solo si se recibe una respuesta exitosa
                    $('#editmodalcliente').modal('hide');
                    location.reload();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                // Manejo de errores
                console.log('Error en la solicitud AJAX');
                console.log(xhr.responseText);
            },
        });
    } else {
        alert('Contraseña incorrecta o campo contraseña vacío');
        $('#pw_cliente_edit').addClass('is-invalid');
    }
}

function verificarContraseñaOperacion() {
    var user_sup = $('#user_supervisor').val();
    var password = $('#pass_supervisor').val();

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'VerificarPasswordSupervisor',
            user_sup: user_sup,
            password: password,
        },
        success: function (response) {
            if (response.success == true) {
                borrarOperacion(password);
            } else {
                alert('Contraseña incorrecta o campo contraseña vacío');
                $('#user_supervisor').addClass('is-invalid');
                $('#pass_supervisor').addClass('is-invalid');
            }
        },
        error: function (xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#loadingIndicator').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        },
    });
}

//BORRAR OPERACION GLOBAL
function modalborrarOperacion() {
    var referencia_nexen = $('#referencia_nexen').val();

    $('#modal_delete_operacion').modal('show');

    $('#ref_nex_get').text(referencia_nexen);
}

function borrarOperacion(password) {
    var password_sup = password;
    var referencia_nexen = $('#referencia_nexen').val();

    $('#loadingIndicator').removeClass('d-none');

    $('#btnBorrarOperacion').prop('disabled', true);

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'deleteOperacion',
            referencia_nexen: referencia_nexen,
            password_sup: password_sup,
        },
        success: function (response) {
            if (response.success) {
                $('#loadingIndicator').addClass('d-none');
                alert(response.message);
                // Abrir el modal solo si se recibe una respuesta exitosa
                $('#modal_delete_operacion').modal('hide');
                window.location.href = '../view/operaciones.php';
            } else {
                console.log(response.message);
            }
        },
        error: function (xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#loadingIndicator').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        },
    });
}

function verificarContraseñaFinanciamiento() {
    var password = $('#pass_supervisor_sol').val();

    $.ajax({
        url: '../include/autorizar_pago.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'VerificarPassword',
            password: password,
        },
        success: function (response) {
            if (response.success == true) {
                alert('Pago Aurotizado correctamnete');
                actualizarEstatus();
            } else {
                alert('Contraseña incorrecta o campo contraseña vacío');
                $('#pass_supervisor').addClass('is-invalid');
            }
        },
        error: function (xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#loadingIndicator').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        },
    });
}

function actualizarEstatus() {
    var solicitud_pago = $('#id_solicitud_p').val();

    $('#loadingIndicator').removeClass('d-none');

    //$('#btnBorrarOperacion').prop('disabled', true);

    $.ajax({
        url: '../include/autorizar_pago.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'actualizarEstatusFinanciamiento',
            solicitud_pago: solicitud_pago,
        },
        success: function (response) {
            if (response.success) {
                $('#loadingIndicator').addClass('d-none');
                alert(response.message);
                // Abrir el modal solo si se recibe una respuesta exitosa
                $('#modal_detalle_pagos').modal('show');
            } else {
                console.log(response.message);
            }
        },
        error: function (xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#loadingIndicator').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        },
    });
}

//FUNCTION BTN DETALLE RETENIDOS
$('#btnDetalleRetenidos').click(function () {
    $('#modalDetalleRetenidos').show();
});

function GuardarEstatus() {
    $('#spinner_guardarEstatus').removeClass('d-none');
    $('#btnGuardarEstatus').prop('disabled', true);

    var Referencia_Nexen = $('#referencia_nexen').val();
    var Fecha_Retenido = $('#modal_fecha_retenido').val();
    var Fecha_Liberacion = $('#modal_fecha_liberacion').val();
    var MSA = $('input[name="msa_estado"]:checked').val();
    var Incidencia = $('input[name="incidencia_estado"]:checked').val();
    var Observacion = $('#observacion').val();
    var Estatus = 'A';

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'GuardarDetalleRetenidos',
            Referencia_Nexen: Referencia_Nexen,
            Fecha_Retenido: Fecha_Retenido,
            Fecha_Liberacion: Fecha_Liberacion,
            MSA: MSA,
            Incidencia: Incidencia,
            Observacion: Observacion,
            Estatus: Estatus,
        },
        success: function (response) {
            if (response.success) {
                alert(response.message);
                // Abrir el modal solo si se recibe una respuesta exitosa
                $('#modalDetalleRetenidos').modal('hide');
                location.reload();
            } else {
                console.log(response.message);
            }
        },
        error: function (xhr, status, error) {
            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        },
    });
}

function mostrar_datos_cuenta(valor) {
    let request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = 'recuperar_datos_cuenta.php';
    let strData = 'valor=' + valor;
    request.open('POST', ajaxUrl, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(strData);

    //console.log(request.responseText);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            console.log(objData);
            // document.querySelector("#razon_social_spc").value = objData[0]['Razon_social'];
            // document.querySelector("#rfc_sp").value = objData[0]['RFC'];
            // document.querySelector("#banco_sp").value = objData[0]['Banco'];
            // document.querySelector("#cuenta_sp").value = objData[0]['Cuenta'];
            // document.querySelector("#clabe_sp").value = objData[0]['Clabe'];
            // document.querySelector("#abba_sp").value = objData[0]['SWT_ABBA'];
            // document.querySelector("#banco_inter_sp").value = objData[0]['Banco_Intermediario'];
            // document.querySelector("#domicilio_sp").value = objData[0]['Domicilio_Completo'];

            document.querySelector('#razon_social_spc').value = objData[0]['Razon_Social'];
            document.querySelector('#razon_social_pago').value = objData[0]['Razon_Social'];
            document.querySelector('#rfc_sp').value = objData[0]['RFC'];
            document.querySelector('#rfc_sp_pago').value = objData[0]['RFC'];
            document.querySelector('#banco_sp').value = objData[0]['NOMBRE_BANCO'];
            document.querySelector('#banco_sp_pago').value = objData[0]['NOMBRE_BANCO'];
            document.querySelector('#cuenta_sp').value = objData[0]['Cuenta'];
            document.querySelector('#cuenta_sp_pago').value = objData[0]['Cuenta'];
            document.querySelector('#clabe_sp').value = objData[0]['Clabe'];
            document.querySelector('#clabe_sp_pago').value = objData[0]['Clabe'];
            document.querySelector('#abba_sp').value = objData[0]['SWT_ABBA'];
            document.querySelector('#abba_sp_pago').value = objData[0]['SWT_ABBA'];
            document.querySelector('#banco_inter_sp').value = objData[0]['Banco_Intermediario'];
            document.querySelector('#banco_inter_sp_pago').value = objData[0]['Banco_Intermediario'];
            document.querySelector('#domicilio_sp').value = objData[0]['Domicilio_Completo'];
            document.querySelector('#domicilio_sp_pago').value = objData[0]['Domicilio_Completo'];
        }
    };
}

$('#btnDeleteRetenido').click(function () {
    $('#modal_fecha_retenido').val('');
});
$('#btnDeleteLiberacion').click(function () {
    $('#modal_fecha_liberacion').val('');
});

//modal operador
$('#btnEditarOperador').click(function () {
    $('#modalOperador').modal('show');
});

$('#comprobarContraSupervisor').click(function () {
    var user_supervisor = $('#user_operador_edit');
    var pw_supervisor = $('#pw_operador_edit');

    if (user_supervisor.val() === '' || pw_supervisor.val() === '') {
        user_supervisor.addClass('is-invalid');
        pw_supervisor.addClass('is-invalid');
    } else {
        user_supervisor.removeClass('is-invalid');
        pw_supervisor.removeClass('is-invalid');
        $('#modal_spinner_operador').removeClass('d-none');

        comprobarSupervisor(user_supervisor, pw_supervisor);
    }
});

//ajax para saber si es correcta la contraseña de supervisor
function comprobarSupervisor(user_supervisor, pw_supervisor) {
    var user_sup = user_supervisor.val();
    var password = pw_supervisor.val();

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'VerificarPasswordSupervisor',
            user_sup: user_sup,
            password: password,
        },
        success: function (response) {
            if (response.success == true) {
                $('#alerta_edit_operador').addClass('d-none');
                $('#user_operador_edit').val('');
                $('#pw_operador_edit').val('');
                $('#modal_spinner_operador').removeClass('d-none');
                $('#modalOperador').modal('hide');
                $('#nombre_operador').prop('disabled', false);
            } else {
                $('#alerta_edit_operador').removeClass('d-none');
                //alert('Contraseña incorrecta o campo contraseña vacío');
                $('#user_supervisor').addClass('is-invalid');
                $('#pass_supervisor').addClass('is-invalid');
                $('#modal_spinner_operador').addClass('d-none');
                $('#nombre_operador').prop('disable', false);
            }
        },
        error: function (xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#modal_spinner_operador').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        },
    });
}

//asignar el onchange al hidden del select
$('#nombre_operador').change(function () {
    // Obtener el valor seleccionado
    var nombre_operador = $('#nombre_operador').val();
    var rfc_empresa = $('#nombre_operador option:selected').attr('rfc_empresa'); // Obtener el valor del atributo rfc_empresa
    var dir_empresa = $('#nombre_operador option:selected').attr('dir_empresa'); // Obtener el valor del atributo dir_empresa
    $('#nombre_operador_o').val(nombre_operador);
    // Asignar los valores directamente a las variables
    var rfc_empresa_input = rfc_empresa;
    var dir_empresa_input = dir_empresa;

    var referencia_nexen = $('#referencia_nexen').val();

    $('#nombre_operador').prop('disabled', true);

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'updateOperador',
            nombre_operador: nombre_operador,
            referencia_nexen: referencia_nexen,
            rfc_empresa_input: rfc_empresa_input,
            dir_empresa_input: dir_empresa_input,
        },
        success: function (response) {
            if (response.success == true) {
                alert(response.message);
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#modal_spinner_operador').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        },
    });
});

window.addEventListener('load', function() {
//Mandamos llamar funciones del archivo config.js
    setupSelectValidation();
    fntValidText();
    fntValidNumber();
    fntValidDescription();
    fntValidMark();
    fntValidFactura();
    fntValidDate();
}, false);

const closeButton = document.querySelector('.close');

closeButton.addEventListener('click', () => {
  // Perform any custom actions here, e.g., logging, confirmation messages, etc.
  console.log('Modal closed!');
});
