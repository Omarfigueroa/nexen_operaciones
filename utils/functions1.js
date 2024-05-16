$(document).ready(function() {

  
    actualizarNumerosFactura();

    $('.selectpicker').selectpicker();

    //botones incrementar y disminuir decimales de precio unitario
    $('.decrease-btn').click(function() {
        decreaseDecimals();
    });

    $('.increase-btn').click(function() {
        increaseDecimals();
    });

    $('#precio_total, #modal_cantidad').change(function() {
        updateDivisionResult();
    });
    //termina incrementar y disminuir decimales precio unitario


    $('#modal_fecha_factura').change(function() {
        var fechaInput = $(this).val();
        //console.log(fechaInput);
        var fechaFormateada = formatearFecha(fechaInput);
        //console.log(fechaFormateada);
      });

    var facturaCount = 1; 
    var total = 0;


    // Función para actualizar el número de factura en las filas
    function actualizarNumerosFactura() {
        var filas = $('#tablaFacturas tbody tr');
        filas.each(function(index) {
            var numeroFactura = index + 1;
            $(this).find('th').text(numeroFactura);
            $(this).find('.btn-borrar').attr('data-factura', numeroFactura);
        });
    }



  // Función para agregar una fila a la tabla
  function agregarFila(factura, descripcion, descripcion_i, cantidad, medida, precioUnitario, moneda, precio_total, peso_bruto, peso_neto, mark) {

        var precioTotal = precio_total;
        total += precioTotal;

        var fila = '<tr>' +
          '<th scope="row">' + factura + '</th>' +
          '<td>' + descripcion + '</td>' +
          '<td>' + descripcion_i + '</td>' +
          '<td>' + cantidad + '</td>' +
          '<td>' + medida + '</td>' +
          '<td>' + precioUnitario + '</td>' +
          '<td>' + moneda + '</td>' +
          '<td>' + precio_total + '</td>' +
          '<td>' + peso_bruto + '</td>' +
          '<td>' + peso_neto + '</td>' +
          '<td>' + mark + '</td>' +
          '<td>' +
          '<button class="btn btn-danger btn-borrar" data-factura="' + factura + '">Borrar</button>' +
          '</td>' +
          '</tr>';

    $('#tablaFacturas tbody').append(fila);

        // Agregar evento para borrar la fila cuando se haga clic en el botón
        $('.btn-borrar[data-factura="' + factura + '"]').click(function() {
          var fila = $(this).closest('tr');
          var precioTotal = parseFloat(fila.find('td:nth-child(8)').text());

          //pesos totales
          var modal_peso_bruto = parseFloat(fila.find('td:nth-child(9)').text());
          var modal_peso_neto = parseFloat(fila.find('td:nth-child(10)').text());


    fila.remove();

    // Restar precioTotal solo si es mayor a 0
    if (precioTotal > 0) {
      total -= precioTotal;
    }

    // Restar pesos solo si son mayores a 0
    if (modal_peso_bruto > 0) {
      total_peso_bruto -= modal_peso_bruto;
    }
    if (modal_peso_neto > 0) {
      total_peso_neto -= modal_peso_neto;
    }

    // Asignar valor mínimo de 0 al total si es negativo
    if (total < 0) {
      total = 0;
    }

    // Asignar valor mínimo de 0 a los pesos si son negativos
    if (total_peso_bruto < 0) {
      total_peso_bruto = 0;
    }
    if (total_peso_neto < 0) {
      total_peso_neto = 0;
    }

    $('#total').text(total.toFixed(7));
    $('#total').val(total.toFixed(7));

    // total pesos asignacion
    $('#total_peso_bruto').text(total_peso_bruto.toFixed(7));
    $('#total_peso_bruto').val(total_peso_bruto.toFixed(7));
    $('#total_peso_neto').text(total_peso_neto.toFixed(7));
    $('#total_peso_neto').val(total_peso_neto.toFixed(7));

        actualizarTotal();

        actualizarNumerosFactura();

      });

    actualizarTotal();

    actualizarNumerosFactura();
  }

  // Función para actualizar el valor total

    function actualizarTotal() {
        var total = new Decimal(0);
        var total_peso_bruto = new Decimal(0);
        var total_peso_neto = new Decimal(0);

        // Iterar sobre todas las filas y sumar los precios totales
        $('#tablaFacturas tbody tr').each(function() {
            var cantidad = new Decimal($(this).find('td:nth-child(4)').text());
            var precioUnitario = new Decimal($(this).find('td:nth-child(6)').text());
            var modal_peso_bruto = new Decimal($(this).find('td:nth-child(9)').text());
            var modal_peso_neto = new Decimal($(this).find('td:nth-child(10)').text());

            var precioTotal = cantidad.times(precioUnitario);
            total = total.plus(precioTotal);

            total_peso_bruto = total_peso_bruto.plus(modal_peso_bruto);
            total_peso_neto = total_peso_neto.plus(modal_peso_neto);
        });

        $('#total').text(total.toFixed(7).toString());
        $('#total').val(total.toFixed(7).toString());

        $('#total_peso_bruto').text(total_peso_bruto.toFixed(7).toString());
        $('#total_peso_neto').text(total_peso_neto.toFixed(7).toString());

        $('#total_peso_bruto').val(total_peso_bruto.toFixed(7).toString());
        $('#total_peso_neto').val(total_peso_neto.toFixed(7).toString());
    }

  // Evento click del botón "Agregar"
  $('#btnAgregar').click(function() {
    //Comprobacion de campos partida
    // Reiniciar clases de validación
    $('.form-control').removeClass('is-invalid');

    var isValid = true;

    // Comprobar si los campos tienen valor
    $('.partidas .form-control').each(function() {
      var valor = $(this).val();
      if (valor === '') {
        $(this).addClass('is-invalid');
        isValid = false;
      }
    });

    if (!isValid) {
      alert('Por favor, complete todos los campos.');
      return;
    }
    //Termina comprobacion de campos partidas

    var factura = facturaCount;
    var descripcion = $('#desc_factura').val();
    var descripcion_i = $('#desc_factura_i').val();
    var cantidad = parseFloat($('#modal_cantidad').val());
    var medida = $('#medida').val();
    var precioUnitario = parseFloat($('#precio_unitario').val());
    var moneda = $('#modal_moneda').val();
    var precio_total = $('#precio_total').val();
    var peso_bruto = $('#modal_peso_bruto').val();
    var peso_neto = $('#modal_peso_neto').val();
    var mark = $('#modal_mark').val();

    if (isNaN(cantidad) || isNaN(precioUnitario)) {
      alert('Ingrese un valor numérico válido para cantidad y precio unitario.');
      return;
    }

    agregarFila(factura, descripcion, descripcion_i, cantidad, medida, precioUnitario, moneda, precio_total, peso_bruto, peso_neto, mark);

    $('#desc_factura').val('');
    $('#desc_factura_i').val('');
    $('#modal_cantidad').val('');
    $('#precio_unitario').val('');
    $('#modal_peso_bruto').val('');
    $('#modal_peso_neto').val('');
    $('#precio_total').val('');
    $(mark).val(mark);

        facturaCount++;
    });

  //Para calcular el total dinamicamente en el campo del modal
  // Evento keyup del campo de cantidad
  $('#modal_cantidad').keyup(calcularPrecioTotal);

  // Evento keyup del campo de precio unitario
  $('#precio_total').keyup(calcularPrecioTotal);

  // Función para calcular el precio total
  function calcularPrecioTotal() {
    var cantidad = parseFloat($('#modal_cantidad').val());
    var precioTotal = parseFloat($('#precio_total').val());

    if (!isNaN(cantidad) && !isNaN(precioTotal)) {
      var precioUnitario = precioTotal / cantidad;
      $('#precio_unitario').val(precioUnitario.toFixed(7));
    }
  }

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
    $('#modal_editar_cliente').on('hidden.bs.modal', function() {
        // Destruir o reiniciar la DataTable al cerrar el modal
        if ($.fn.DataTable.isDataTable('#dataTableClientes')) {
            $('#dataTableClientes').DataTable().destroy();
        }
    });
});








    


function documentos(referencia_nexen, tipo_trafico) { 
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = '../include/datos.php';
  
  // Ajustar la cadena de datos para enviar las variables por separado
  let strData = "referencia_nexen=" + referencia_nexen + "&tipo_trafico=" + tipo_trafico;
  
  request.open("POST", ajaxUrl, true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(strData);
  var botonCarpeta = document.getElementById('verDocumentos');
  botonCarpeta.disabled= true;
  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);

      if(objData){
        let res = document.querySelector('#tableDocumentos');
      
        // Construir la tabla HTML
        let tableHTML = '<table>';
        for (let index = 0; index < objData.length; index++) {
          let element = objData[index];
          tableHTML += '<tr>';
          if (element.hasOwnProperty('array2')) {
            let array2 = element.array2;
            tableHTML += '<td>' + array2.Nombre_Documento + '</td>';
            tableHTML += '<td>' + array2.OPTIONS + '</td>';
            tableHTML += '<td>' + array2.Estatus + '</td>';
            // ... y así sucesivamente para los demás campos que deseas mostrar
          } else {
             // Mostrar todos los valores en una fila si no hay 'array2'
             for (const key in element) {
              if (element.hasOwnProperty(key)) {
                if (key === 'DOCUMENTO' || key === 'Estatus' || key === 'OPTIONS') {
                  tableHTML += '<td>' + element[key] + '</td>';
                }
              }
            }
          }
          tableHTML += '</tr>';
        }
        tableHTML += '</table>';
        
        // Insertar la tabla en el elemento HTML
        res.innerHTML = tableHTML;
       
      }else{
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          html:objData.msg
        });
      }      
    }
    botonCarpeta.disabled= false;
  }
}

function fntUpdate(id_catalogo,referencia,tipo_trafico){
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = '../include/valida.php';
  
  // Ajustar la cadena de datos para enviar las variables por separado
  let strData =  "&id_catalogo=" + id_catalogo;
  
  request.open("POST", ajaxUrl, true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(strData);
  
  request.onreadystatechange = function() {
    if(request.readyState == 4 && request.status == 200)
        {
            let objData = JSON.parse(request.responseText);

            document.querySelector("#nombre").value = objData[0]['DOCUMENTO'];
            document.querySelector('#Referencia').value = referencia;
            document.querySelector("#id_catalogo").value = objData[0]['ID_CATALOGO_DOCUMENTOS'];
            document.querySelector("#Tipo_Ope").value = tipo_trafico;
            
            $('#modalUpload').modal('show');
        }
  }
}

function tipoTransporte(valor){
    if(valor==="CARRETERO-FERROVIARIO" || valor==="CARRETERO" ){
        document.getElementById("contenedor1").disabled=true;
        document.getElementById("contenedor2").disabled=true;
        document.getElementById("bl").disabled=true;
        document.getElementById("house").disabled=true;
        document.getElementById("contenedor1").value="";
        document.getElementById("contenedor2").value="";
        document.getElementById("bl").value="";
        document.getElementById("house").value="";
        document.getElementById("num_eco").disabled=false;

    }else if(valor==="AEREO"){
        document.getElementById("bl").disabled=false;
        document.getElementById("house").disabled=false;
        document.getElementById("contenedor1").disabled=true;
        document.getElementById("contenedor2").disabled=true;
        document.getElementById("num_eco").disabled=true;

        document.getElementById("contenedor1").value="";
        document.getElementById("contenedor2").value="";
        document.getElementById("num_eco").value="";

    }else {
        document.getElementById("contenedor1").disabled=false;
        document.getElementById("contenedor2").disabled=false;
        document.getElementById("num_eco").value="";
        document.getElementById("bl").value="";
        document.getElementById("house").value="";
        document.getElementById("num_eco").disabled=true;
        document.getElementById("bl").disabled=true;
        document.getElementById("house").disabled=true;
    }
}

function mostrar_codigo_aduana(valor){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = '../include/recuperar_pto_llegada.php';
    let strData = "valor="+valor;
    request.open("POST",ajaxUrl,true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(strData);
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200)
        {
            let objData = JSON.parse(request.responseText);
            console.log(objData);
            document.querySelector("#cve_aduana").value = objData[0]['Codigo'];
        }
    }
}



//Function para cargar datos en modal Cargar factura
function modalCargarFactura() {

    var nombreOperador = $('#nombre_operador_o').val();
    
    if (nombreOperador === '') {
      alert('Falta elegir el campo de nombre del operador.');
     // return;
    }else{
        // Realizar la solicitud AJAX aquí y usar el valor de nombreOperador en la consulta SQL
    
        $.ajax({
            url: '../include/cargarFacturas.php',
            method: 'POST',
            data: { opcion: 'leerEmpresas', nombreOperador: nombreOperador },
            dataType: 'json',
            success: function(response) {
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
            error: function() {
                alert('Error al obtener los datos del servidor.');
            }
        });
          
          
         
    }
    
  }

  //function para hacer los insert de la factura y detalles factura
  // Función para enviar la solicitud AJAX
function updateFacturasEdit() {

  //Comprobar todos los campos llenos del formulario para darle update
  $('#modalEditarFacturas .form_facturas_edit .form-control').removeClass('is-invalid');

  var isValid = true;

  // Comprobar si los campos tienen valor
  $('#modalEditarFacturas .form_facturas_edit .form-control').each(function() {
    var valor = $(this).val();
    if (valor === '') {
      $(this).addClass('is-invalid');
      isValid = false;
    }
  });

  if (!isValid) {
    alert('Por favor, complete todos los campos.');
    return;
  }
  // Comprobar si la tabla tiene registros de partidas
  var rowCount = $('#tablaPartidasEditar tbody tr').length;
  if (rowCount === 0) {
    alert('No hay partidas en la tabla. No se puede guardar.');
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


    //se muestra spinner loading
    $('#spinner_edit').removeClass('d-none');

    //bloquear con un disabled el boton de editar para que no lo aprenten
    $('#btnEditarFacturas').prop('disabled', true);
   
    //console.log(fechaFactura);
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
        modal_num_factura_edit: modal_num_factura_edit,
        modal_fecha_factura_edit: modal_fecha_factura_edit,
        tax_id_edit: tax_id_edit,
        incoterms_edit: incoterms_edit,
        modal_nombre_operador_edit: modal_nombre_operador_edit,
        modal_domicilio_operador_edit: modal_domicilio_operador_edit
      },
      dataType: 'json',
      success: function(response) {
        // Manejar la respuesta del servidor
        if (response.success) {
          console.log(response.message);
          console.log(response.messageDeletePartidas);
          console.log(response.idFactura);
          console.log(response.Referencia_Nexen);

            
            var referencia_nexen = response.Referencia_Nexen;
            // Realizar el insert por cada registro en la tabla
            var registros_edit = $('#tablaPartidasEditar tbody tr');
            var numFactura_edit = $('#modal_num_factura_edit').val();
            var incoterms_edit = $('#incoterms_edit').val();
            

            var successShown = false; // Variable de control
            

            
            
            // Iterar sobre cada fila y asignar el número de partida
            registros_edit.each(function(index, element) {
                var partida = (index + 1).toString(); // El número de partida es el índi
                var descripcion = $(element).find('td:nth-child(2)').text();
                var descripcion_i = $(element).find('td:nth-child(3)').text();
                var cantidad = parseFloat($(element).find('td:nth-child(4)').text());
                var medida = $(element).find('td:nth-child(5)').text();
                var precioUnitario = parseFloat($(element).find('td:nth-child(6)').text());
                var total_partida = parseFloat($(element).find('td:nth-child(7)').text());
                var moneda = $(element).find('td:nth-child(8)').text();
                var peso_bruto = $(element).find('td:nth-child(9)').text();
                var peso_neto = $(element).find('td:nth-child(10)').text();
                var mark = $(element).find('td:nth-child(11)').text();

                

                //console.log("partida:", partida);

                //console.log("total partida:", totalPartida);

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
                        cantidad: cantidad,
                        medida: medida,
                        precioUnitario: precioUnitario,
                        total_partida: total_partida,
                        moneda: moneda,
                        peso_bruto: peso_bruto,
                        peso_neto: peso_neto,
                        mark: mark
                    },
                    dataType: 'json',
                    success: function(response) {
                      if (!successShown) { // Verificar si el mensaje aún no se ha mostrado
                        alert(response.message);
                        successShown = true; // Establecer la variable de control en true para evitar mostrar el mensaje nuevamente
                        $('#spinner_edit').addClass('d-none');
                        location.reload();
                      }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al realizar el insert para factura ' + response.idFactura);
                        console.log(error);
                        
                    }
                });
                
            });
            
        } else {
            // La inserción principal falló
            console.log(response.message);
            //location.reload();
        }
    },
      error: function(xhr, status, error) {
        // Manejar errores de la solicitud AJAX
        console.error(error);
      }
    });
    
  }

  //function para UPDATE DE FACTURAS Y DETALLES
  //function para hacer los insert de la factura y detalles factura
  // Función para enviar la solicitud AJAX
function enviarSolicitudAjax() {
  // Comprobar si la tabla tiene registros
  var rowCount = $('#tablaFacturas tbody tr').length;
  if (rowCount === 0) {
    alert('No hay partidas en la tabla. No se puede guardar.');
    return;
  }

    // Obtener los valores de los input
    var opcion = 'insertarOperacionFactura';
    var referencia_nexen = $('#referencia_nexen').val();

    var modal_pais_origen = $('#modal_pais_origen').val();

    var nombreOperador = $('#modal_nombre_operador').val();
    var rfcOperador = $('#modal_rfc_operador').val();
    var domOperador = $('#modal_domicilio_operador').val();
    var numFactura = $('#modal_num_factura').val();
    var proveedorFact = $('#proveedor_fact').val();
    var fechaFactura = $('#modal_fecha_factura').val();
    var taxId = $('#tax_id').val();
    var total = $('#total').val();
    var total_peso_bruto = $('#total_peso_bruto').val();
    var total_peso_neto = $('#total_peso_neto').val();

    //se muestra spinner loading
    $('#spinner_insert').removeClass('d-none');

    //bloquear con un disabled el boton de editar para que no lo aprenten
    $('#btnGuardarFacturas').prop('disabled', true);

    //console.log(fechaFactura);
  
    // Enviar la solicitud AJAX
    $.ajax({
      url: '../include/cargarFacturas.php',
      method: 'POST',
      data: {
        opcion: opcion,
        referencia_nexen: referencia_nexen,
        modal_pais_origen,
        nombreOperador: nombreOperador,
        rfcOperador: rfcOperador,
        domOperador: domOperador,
        proveedorFact: proveedorFact,
        numFactura: numFactura,
        fechaFactura: fechaFactura,
        taxId: taxId,
        total: total,
        total_peso_bruto: total_peso_bruto,
        total_peso_neto: total_peso_neto
        
      },
      dataType: 'json',
       // Datos que deseas enviar al servidor
       beforeSend: function() {
        // Muestra el loading
        $('#loadingModal').modal('show');
      },
      success: function(response) {
        // Manejar la respuesta del servidor
        if (response.success) {
            // La inserción principal fue exitosa
            console.log(response.message);
            var lastId = response.lastId;
            var referencia_nexen = response.referencia_nexen;
            // Realizar el insert por cada registro en la tabla
            var registros = $('#tablaFacturas tbody tr');
            var numFactura = $('#modal_num_factura').val();
            var incoterms = $('#incoterms').val();
            

            var successShown = false; // Variable de control

            registros.each(function(index, element) {
                var partida = $(element).find('th').text();
                var descripcion = $(element).find('td:nth-child(2)').text();
                var descripcion_i = $(element).find('td:nth-child(3)').text();
                var cantidad = parseFloat($(element).find('td:nth-child(4)').text());
                var medida = $(element).find('td:nth-child(5)').text();
                var precioUnitario = parseFloat($(element).find('td:nth-child(6)').text());
                var moneda = $(element).find('td:nth-child(7)').text();
                var total_partida = parseFloat($(element).find('td:nth-child(8)').text());
                var peso_bruto = $(element).find('td:nth-child(9)').text();
                var peso_neto = $(element).find('td:nth-child(10)').text();
                var mark = $(element).find('td:nth-child(11)').text();
                


                  //console.log(peso_bruto+'-'+peso_neto);
                

                $.ajax({
                    url: '../include/cargarFacturas.php',
                    method: 'POST',
                    data: {
                        opcion: 'insertarFacturaDetalle',
                        lastId: lastId,
                        referencia_nexen: referencia_nexen,
                        numFactura: numFactura,
                        incoterms: incoterms,
                        partida: partida,
                        descripcion: descripcion,
                        descripcion_i: descripcion_i,
                        cantidad: cantidad,
                        medida: medida,
                        precioUnitario: precioUnitario,
                        moneda: moneda,
                        total_partida: total_partida,
                        peso_bruto: peso_bruto,
                        peso_neto: peso_neto,
                        mark: mark
                    },
                    dataType: 'json',
                    success: function(response) {
                      if (!successShown) { // Verificar si el mensaje aún no se ha mostrado
                        alert(response.message);
                        successShown = true; // Establecer la variable de control en true para evitar mostrar el mensaje nuevamente
                        location.reload();
                      }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al realizar el insert para factura ' + lastId);
                        console.log(error);
                        
                    }
                });
            });
        } else {
            // La inserción principal falló
            alert(response.message);
            location.reload();
        }
    },
      error: function(xhr, status, error) {
        // Manejar errores de la solicitud AJAX
        console.error(error); 
        console.error(xhr);
        console.error(status);
      },
      complete: function() {
        // Oculta el loading después de que la solicitud se complete, ya sea éxito o error
        $('#loadingModal').modal('hide');
      }
    });
  }


  //modal para ver facturas
  function modalVerFacturas() {
    //console.log("hola"); 

    //Variables
    var referencia_nexen = $('#referencia_nexen').val();
    var nombreOperador = $('#nombre_operador_o').val();
  
    $.ajax({
      url: '../include/cargarFacturas.php',
      method: 'POST',
      data: {
        opcion: 'obtenerFacturas',
        referencia_nexen: referencia_nexen,
        nombreOperador: nombreOperador
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          var facturas = response.data;
  
          // Construir la tabla de facturas
          var table = '<table>';
          table += '<thead><tr>';
          table += '<th>ID Factura</th>';
          table += '<th>Referencia Nexen</th>';
          table += '<th>Proveedor</th>';
          table += '<th>Tax ID</th>';
          table += '<th>Número Factura</th>';
          table += '<th>Fecha Factura</th>';
          table += '<th>Importador/Exportador</th>';
          table += '<th>Total General</th>';
          table += '<th>Fech. Operación</th>';
          table += '<th>Hora Operación</th>';
          table += '<th>Usuario</th>';
          table += '<th>Estatus</th>';
          table += '<th>Detalles</th>'; // Agregar columna para el botón "Detalles"
          table += '<th>Invoice</th>'; // Agregar columna para el botón "Detalles"
          table += '<th>Packing List</th>'; // Agregar columna para el botón "Detalles"
          table += '<th>Editar</th>'; // Agregar columna para el botón "Editar factura"
          table += '<th>Borrar</th>'; // Agregar columna para el botón "Editar factura"
            table += '</tr></thead>';
          table += '<tbody>';
  
          for (var i = 0; i < facturas.length; i++) {
            var factura = facturas[i];
  
            table += '<tr>';
            table += '<td>' + factura.Id_Factura + '</td>';
            table += '<td>' + factura.Referencia_Nexen + '</td>';
            table += '<td>' + factura.Proveedor + '</td>';
            table += '<td>' + factura.Tax_Id + '</td>';
            table += '<td>' + factura.Numero_Factura + '</td>';
            table += '<td>' + factura.Fecha_Factura + '</td>';
            table += '<td>' + factura.Importador_Exportador + '</td>';
            var totalFormateado = '$' + factura.Total_General;
            table += '<td>' + totalFormateado + '</td>';
            table += '<td>' + factura.Fechope + '</td>';
            var horaLegible = new Date("2000-01-01T" + factura.HoraoPe).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            table += '<td>' + horaLegible + '</td>';
            table += '<td>' + factura.Usuario + '</td>';
            table += '<td>' + factura.Estatus + '</td>';
            table += '<td><button type="button" class="btn btn-primary btn-detalles" data-bs-toggle="modal" data-bs-target="#modalVerDetalleFacturas" data-id="' + factura.Id_Factura + '">Detalles</button></td>'; // Agregar botón "Detalles"
            table += '<td><a class="btn btn-primary btn-detalles" href="generarinvoice.php?id='+factura.Id_Factura+'" target="_blank">&nbsp;&nbsp;<i class="bi bi-printer text-light"></i>&nbsp;&nbsp;</a></td>'; // Agregar botón "Impresion invoice"
            table += '<td><a class="btn btn-primary btn-detalles" href="generarpacking.php?id='+factura.Id_Factura+'" target="_blank">&nbsp;&nbsp;<i class="bi bi-printer text-light"></i>&nbsp;&nbsp;</a></td>';// Agregar botón "Impresion packing list"
            table += '<td><a class="btn btn-warning btn-editarFactura" data-bs-toggle="modal" data-bs-target="#modalEditarFacturas" id_factura="'+factura.Id_Factura+'" numero_factura="'+factura.Numero_Factura+'" onclick="editarFacturas(this)">&nbsp;&nbsp;<i class="bi bi-pencil-square text-primary"></i>&nbsp;&nbsp;</a></td>';// Agregar botón "editar factura"
            table += '<td><a class="btn btn-danger btn-borrarFactura"  id_factura="'+factura.Id_Factura+'" referencia_nexen="'+factura.Referencia_Nexen+'" numero_factura="'+factura.Numero_Factura+'" fecha_factura="'+factura.Fecha_Factura+'" tax_id="'+factura.Tax_Id+'" usuario="'+factura+'"  onclick="borrarFacturas(this)">&nbsp;&nbsp;<i class="bi bi-trash text-white"></i>&nbsp;&nbsp;</a></td>';// Agregar botón "borrar factura y sus partidas"
            table += '</tr>';
          }
          
          table += '</tbody>';
          table += '</table>';
  
          // Agregar la tabla al contenido del modal
          $('#modalVerFacturas .modal-body').html(table);
  
          // Inicializar DataTables en la tabla
          $('#modalVerFacturas table').DataTable();

          // Al hacer clic en el botón "Detalles"
            $('#modalVerFacturas').on('click', '.btn-detalles', function() {
                var idFactura = $(this).data('id');
                
                // Aquí puedes realizar la lógica para mostrar los detalles de la factura en otro modal o realizar cualquier otra acción
                console.log('Mostrar detalles de la factura con ID: ' + idFactura);
                //SEgUNDO NIVEL DE AJAX PARA DETALLES***********************************/
                $.ajax({
                    url: '../include/cargarFacturas.php',
                    method: 'POST',
                    data: {
                      opcion: 'obtenerDetalleFacturas',
                      idFactura: idFactura
                    },
                    dataType: 'json',
                    success: function(response) {
                      if (response.success) {
                        var facturas = response.data;
                
                        // Construir la tabla de facturas
                        var table = '<table id="tablaDetalles">';
                        table += '<thead><tr>';
                        table += '<th>ID Factura</th>';
                        table += '<th>Referencia Nexen</th>';
                        table += '<th>Número Factura</th>';
                        table += '<th>Número Partida</th>';
                        table += '<th>Descripción Cove</th>';
                        table += '<th>Cantidad</th>';
                        table += '<th>Unidad Medida</th>';
                        table += '<th>Moneda</th>';
                        table += '<th>Precio Unitario</th>';
                        table += '<th>Total</th>';
                        table += '<th>Peso Bruto</th>';
                        table += '<th>Peso Neto</th>';
                        table += '<th>Estatus</th>';
                        table += '<th>Fecha Operación</th>';
                        table += '<th>Hora Operación</th>';
                        table += '<th>Usuario</th>';
                        table += '<th>Borrar partida</th>';
                        table += '</tr></thead>';
                        table += '<tbody>';

                        for (var i = 0; i < facturas.length; i++) {
                        var factura = facturas[i];

                        table += '<tr>';
                        table += '<td>' + factura.Id_Factura + '</td>';
                        table += '<td>' + factura.Referencia_Nexen + '</td>';
                        table += '<td>' + factura.Numero_Factura + '</td>';
                        table += '<td>' + factura.Numero_Partida + '</td>';
                        table += '<td>' + factura.Descripcion_Cove + '</td>';
                        table += '<td>' + factura.Cantidad + '</td>';
                        table += '<td>' + factura.Unidad_Medida + '</td>';
                        table += '<td>' + factura.Moneda + '</td>';
                        table += '<td>' + factura.Precio_Unitario + '</td>';
                        table += '<td>' + factura.Total + '</td>';
                        table += '<td>' + factura.Peso_Bruto + '</td>';
                        table += '<td>' + factura.Peso_Neto + '</td>';
                        table += '<td>' + factura.Estatus + '</td>';
                        table += '<td>' + factura.Fechope + '</td>';
                        table += '<td>' + factura.Horaope + '</td>';
                        table += '<td>' + factura.Usuario + '</td>';
                        table += '<td><button type="button" class="btn btn-danger btn-borrarPartida" partida="'+factura.Numero_Partida+'" factura="'+factura.Numero_Factura+'" onclick="borrarPartidas(this)">Borrar</button></td>'; // Agregar botón "Detalles"
                        table += '</tr>';
                        }

                        table += '</tbody>';
                        table += '</table>';
                
                        // Agregar la tabla al contenido del modal
                        $('#modalVerDetalleFacturas .modal-body').html(table);
                
                        // Inicializar DataTables en la tabla
                        $('#modalVerDetalleFacturas table').DataTable();
              
                        // Al hacer clic en el botón "Detalles"
                        /*
                          $('#modalVerFacturas').on('click', '.btn-detalles', function() {
                              var idFactura = $(this).data('id');
                              // Aquí puedes realizar la lógica para mostrar los detalles de la factura en otro modal o realizar cualquier otra acción
                              console.log('Mostrar detalles de la factura con ID: ' + idFactura);
                          });
                          */
                      } else {
                        console.log('Error al obtener las facturas');
                        console.log(response.message);
                      }
                    },
                    error: function(xhr, status, error) {
                      console.log('Error en la solicitud AJAX');
                      console.log(error);
                      console.log(xhr.responseText);
                    }
                  });
            });
        } else {
          console.log('Error al obtener las facturas');
          console.log(response.message);
        }
      },
      error: function(xhr, status, error) {
        console.log('Error en la solicitud AJAX');
        console.log(error);
        console.log(xhr.responseText);
      }
    });
  }

//functiones para editar facturas
function editarFacturas(btnEditarFacturas){
  //alert("hola");
  var numero_factura = $(btnEditarFacturas).attr('numero_factura');
  //console.log(numero_factura);
  
  $.ajax({
    url: '../include/cargarFacturas.php',
    method: 'POST',
    data: {
      opcion: 'obtenerEditarFacturas',
      numero_factura: numero_factura
    },
    dataType: 'json',
    success: function(response) {
      if (response.success) {

        console.log(response.data);
        console.log(response.partidas);

        $('#modalEditarFacturas').on('shown.bs.modal', function () {
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

        
            // Logica para llenar la tabla de partidas
            var partidas = response.partidas;

            // Construir la tabla de partidas
            var table = '<table id="tablaPartidasEditar" class="table">';
            table += '<thead><tr>';
            table += '<th>Numero Partida</th>';
            table += '<th>Descripcion Cove</th>';
            table += '<th>Cove Description</th>';
            table += '<th>Cantidad</th>';
            table += '<th>Unidad</th>';
            table += '<th>Valor Unitario</th>';
            table += '<th>Total</th>';
            table += '<th>Moneda</th>';
            table += '<th>Peso Bruto</th>';
            table += '<th>Peso Neto</th>';
            table += '<th>Mark</th>';
            table += '<th>Borrar partida</th>';
            table += '</tr></thead>';
            table += '<tbody>';

            for (var i = 0; i < partidas.length; i++) {
              var partida = partidas[i];

              

              var subtotal = parseFloat(partida.Cantidad) * parseFloat(partida.Precio_Unitario); // Calcular el subtotal de la partida

              table += '<tr>';
              table += '<td>' + partida.Numero_Partida + '</td>';
              table += '<td>' + partida.Descripcion_Cove + '</td>';
              table += '<td>' + partida.Descripcion_cove_I + '</td>';
              table += '<td>' + partida.Cantidad + '</td>';
              table += '<td>' + partida.Unidad_Medida + '</td>';
              table += '<td>' + partida.Precio_Unitario + '</td>';
              table += '<td>' + subtotal.toFixed(7) + '</td>'; // Mostrar el subtotal
              table += '<td>' + partida.Moneda + '</td>';
              table += '<td>' + partida.Peso_Bruto + '</td>';
              table += '<td>' + partida.Peso_Neto + '</td>';
              table += '<td>' + partida.Mark + '</td>';
              table += '<td><button type="button" class="btn btn-danger btn-borrarPartidaEdit" partida="' + partida.Numero_Partida + '" factura="' + partida.Numero_Factura + '" onclick="borrarPartidasEdit(this)">Borrar</button></td>'; // Agregar botón "Borrar"
              table += '</tr>';
            }

            //Se asigna incoterm
            $('#incoterms_edit').val(partidas[0].Incoterms);

            table += '</tbody>';

            // Agregar fila de total al pie de la tabla
            table += '<tfoot>';
            table += '<tr>';
            table += '<td colspan="5"></td>'; // Las columnas anteriores al total
            table += '<td><strong>Total:</strong></td>'; // Columna del total
            table += '<td><strong class="total_edit">' + calcularTotalEdit(partidas).toFixed(7) + '</strong></td>'; // Mostrar el total
            table += '<td colspan="5"></td>'; // Las columnas siguientes al total
            table += '</tr>';
            table += '</tfoot>';

            table += '</table>';

            // Agregar la tabla al contenido del modal
            $('#modalEditarFacturas .tabla-partidas').html(table);

            // Inicializar DataTables en la tabla
           $('#tablaPartidasEditar').DataTable();

            

            
          }else{
            console.log('No tiene registros');
          }
        });


      } else {
        console.log('Error al obtener las facturas');
        console.log(response.message);
      }
    },
    error: function(xhr, status, error) {
      console.log('Error en la solicitud AJAX');
      console.log(error);
      console.log(xhr.responseText);
    }
  });
}

// Evento click del botón "Agregar"
$('#btnAgregar_edit').click(function() {
  // Comprobación de campos partida
  // Reiniciar clases de validación
  $('.form-control').removeClass('is-invalid');

  var isValid = true;

  // Comprobar si los campos tienen valor
  $('.partidas_edit .form-control').each(function() {
    var valor = $(this).val();
    if (valor === '') {
      $(this).addClass('is-invalid');
      isValid = false;
    }
  });

  if (!isValid) {
    alert('Por favor, complete todos los campos.');
    return;
  }
  // Termina comprobación

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
    '<td class="numero-partida">' + numeroPartida + '</td>' +
    '<td>' + descFactura + '</td>' +
    '<td>' + descFacturaI + '</td>' +
    '<td>' + cantidad + '</td>' +
    '<td>' + medida + '</td>' +
    '<td>' + precioUnitario + '</td>' +
    '<td>' + precioTotal + '</td>' +
    '<td>' + moneda + '</td>' +
    '<td>' + pesoBruto + '</td>' +
    '<td>' + pesoNeto + '</td>' +
    '<td>' + mark + '</td>' +
    '<td><button type="button" class="btn btn-danger btn-borrarPartidaEdit" temporal partida="' + numeroPartida + '" factura="' + Numero_Factura + '" onclick="borrarPartidasEdit(this)">Borrar</button></td>' + // Agregar botón "Borrar"
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
    $('#tablaPartidasEditar tbody tr').each(function() {
      var partida = {
        Cantidad: $(this).find('td:nth-child(4)').text(),
        Precio_Unitario: $(this).find('td:nth-child(6)').text()
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
  function borrarFacturas(btnBorrarFacturas){
    var id_factura = $(btnBorrarFacturas).attr('id_factura');
    var referencia_nexen = $(btnBorrarFacturas).attr('referencia_nexen');
    var numero_factura = $(btnBorrarFacturas).attr('numero_factura');
    var fecha_factura = $(btnBorrarFacturas).attr('fecha_factura');
    var tax_id = $(btnBorrarFacturas).attr('tax_id');
    var usuario = $(btnBorrarFacturas).attr('usuario');
  
    

    console.log(numero_factura+' '+id_factura);
    //MODAL CONFIRMACION Y BORRADO DE FACTURAS Y PARTIDAS
    // Agregar un evento click al botón de borrado
    
      // Mostrar el modal de confirmación
      $('#confirmDeleteModal').modal('show');
      $('#borrarNumFactura').text(numero_factura);

    // Agregar un evento click al botón de confirmación de borrado
    $('#confirmDeleteButton').click(function() {
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
                  usuario: usuario
                },
                dataType: 'json',
                success: function(response) {
                  if (response.success) {
                    console.log(response.message);
                    location.reload(); // Recargar la página
                  } else {
                    console.log('Error al hacer la consulta');
                    console.log(response.message);
                  }
                },
                error: function(xhr, status, error) {
                  console.log('Error en la solicitud AJAX');
                  console.log(error);
                  console.log(xhr.responseText);
                }
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
  
function borrarPartidas(button){

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
        factura: factura
      },
      dataType: 'json',
      success: function(response) {
        if(response.success){
          alert(response.message);
          location.reload();
        }else{
          alert(response.message);
        }
      },
      error: function(xhr, status, error) {
        console.log('Error en la solicitud AJAX');
        console.log(error);
        console.log(xhr.responseText);
      }
    });
}
//Borrar partidas edit
function borrarPartidasEdit(button){
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
          factura: factura
        },
        dataType: 'json',
        success: function(response) {
          if(response.success){
            var fila = $(button).closest('tr');

            // Eliminar la fila de la tabla
            fila.remove();
      
            // Calcular y actualizar el total
            var partidas = obtenerPartidasEdit(); // Obtener todas las partidas de la tabla
            var total = calcularTotalEdit(partidas); // Calcular el nuevo total
            $('#tablaPartidasEditar tfoot td .total_edit').text(total.toFixed(7)); // Actualizar el valor en el pie de la tabla

            alert(response.message);
            //location.reload();
            
          }else{
            alert(response.message);
          }
        },
        error: function(xhr, status, error) {
          console.log('Error en la solicitud AJAX');
          console.log(error);
          console.log(xhr.responseText);
        }
      });
    }
}




function fntDownload(id_catalogo, referencia_nexen) {
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = '../include/download.php';

  // Ajustar la cadena de datos para enviar las variables por separado
  let strData = "referencia_nexen=" + referencia_nexen + "&id_catalogo=" + id_catalogo;

  request.open("POST", ajaxUrl, true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.responseType = 'blob';

  request.onreadystatechange = function() {
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
  }

  request.send(strData);
}
function fntDelet(id_catalogo,referencia_nexen){
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
let ajaxUrl = '../include/Delet.php';

// Ajustar la cadena de datos para enviar las variables por separado
let strData = "referencia_nexen=" + referencia_nexen + "&id_catalogo=" + id_catalogo;

request.open("POST", ajaxUrl, true);
request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
request.send(strData);

request.onreadystatechange = function() {
  if (request.readyState == 4 && request.status == 200) {
    location.reload(true);
  }
}
}
function fntView(id_catalogo,referencia_nexen){
let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
let ajaxUrl = '../include/download.php';

// Ajustar la cadena de datos para enviar las variables por separado
let strData = "referencia_nexen=" + referencia_nexen + "&id_catalogo=" + id_catalogo;

request.open("POST", ajaxUrl, true);
request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
request.responseType = 'blob';

request.onreadystatechange = function() {
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
}

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
        $('#btnEditarProveedor').on('click', function() {
            // Realizar la solicitud AJAX
            $.ajax({
                url: '../include/cargarFacturas.php',
                method: 'POST',
                data: {
                    opcion: 'leerProveedor',
                    taxID: taxID
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.proveedor);

                        //asignamos los valores en los input
                        var editar_tax_id = $('#editar_tax_id').val(response.proveedor['codigo']);
                        var editar_proveedor = $('#editar_proveedor').val(response.proveedor['Proveedor']);
                        var editar_domicilio = $('#editar_domicilio').val(response.proveedor['domicilio']);
                        var editar_email = $('#editar_email').val(response.proveedor['correo']);
                        var editar_whatsapp = $('#editar_whatsapp').val(response.proveedor['whatsapp']);


                        // Abrir el modal solo si se recibe una respuesta exitosa
                        $('#modal_editar_proveedor').modal('show');


                    } else {
                        console.log(response.message);
                    }


                },
                error: function(xhr, status, error) {
                    // Manejo de errores
                    console.log('Error en la solicitud AJAX');
                    console.log(xhr.responseText);
                }
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
                editar_proveedor: editar_proveedor,
                editar_domicilio: editar_domicilio,
                editar_email: editar_email,
                editar_whatsapp: editar_whatsapp
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // Abrir el modal solo si se recibe una respuesta exitosa
                    $('#modal_editar_proveedor').modal('hide');
                    location.reload();
                } else {
                    console.log(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Manejo de errores
                console.log('Error en la solicitud AJAX');
                console.log(xhr.responseText);
            }
        });
    } else {
        alert('Contraseña incorrecta o campo contraseña vacío');
        $('#editar_pass').addClass('is-invalid');
    }
}


function deleteProveedor() {
    if (verificarContraseña()) {
        var confirmacion = confirm("¿Estás seguro de eliminar este proveedor? Esta acción no se puede deshacer.");

        if (confirmacion) {
            var editar_tax_id = $('#editar_tax_id').val();

            $.ajax({
                url: '../include/cargarFacturas.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    opcion: 'deleteProveedor',
                    editar_tax_id: editar_tax_id
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        // Cerrar el modal si se recibe una respuesta exitosa
                        $('#modal_editar_proveedor').modal('hide');
                        location.reload();
                    } else {
                        console.log(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Manejo de errores
                    console.log('Error en la solicitud AJAX');
                    console.log(xhr.responseText);
                }
            });
        }
    } else {
        alert('Contraseña incorrecta o campo contraseña vacío');
        $('#editar_pass').addClass('is-invalid');
    }
}


// Asociar el evento click al botón btnEditarProveedor
$('#btnEditarCliente').on('click', function() {

    var nombre_cliente = $('#btnEditarCliente').attr('nombre_cliente');

    // Realizar la solicitud AJAX
    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        data: {
            opcion: 'leerCliente',
            nombre_cliente: nombre_cliente
        },
        success: function(response) {
            if (response.success) {
                //console.log(response.proveedor);

                //asignamos los valores en los input
                var razon_social_cliente_edit = $('#razon_social_cliente_edit').val(response.cliente['RAZON SOCIAL ']);
                var rfc_cliente_edit = $('#rfc_cliente_edit').val(response.cliente['RFC ']);
                var telefono_cliente_edit = $('#telefono_cliente_edit').val(response.cliente['TELEFONO']);
                var movil_cliente_edit = $('#movil_cliente_edit').val(response.cliente['MOVIL ']);
                var nombre_contacto_edit = $('#nombre_contacto_edit').val(response.cliente['CONTACTO']);
                var email_cliente_1_edit = $('#email_cliente_1_edit').val(response.cliente['EMAIL 1']);
                var email_cliente_2_edit = $('#email_cliente_2_edit').val(response.cliente['EMAIL 2']);
                var domicilio_cliente_edit = $('#domicilio_cliente_edit').val(response.cliente['Domilio_Fisico']);

                //console.log(response.cliente);

                // Abrir el modal solo si se recibe una respuesta exitosa
                $('#modal_editar_cliente').modal('show');

                // Rellenar la DataTable
                var dataTableClientes = $('#dataTableClientes').DataTable({
                    data: response.data,
                    pageLength: 5, // Mostrar solo 5 registros por página
                    columns: [
                        { data: 'id' },
                        { data: 'RazonSocial' },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return '<span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Bloqueado hasta proxima actualización">\n' +
                                        '<button type="button" class="btn btn-danger" onclick="borrarCliente(' + data.id + ')" disabled >Borrar</button>\n' +
                                       '</span>';
                               //return '<button class="btn btn-danger" onclick="borrarCliente(' + data.id + ')" disabled title="Hola">Borrar</button>';
                            }
                        }
                    ]
                });

            } else {
                console.log(response.message);
            }


        },
        error: function(xhr, status, error) {
            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        }
    });

});

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
                        dom_cliente_edit: dom_cliente_edit

                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Abrir el modal solo si se recibe una respuesta exitosa
                            $('#editmodalcliente').modal('hide');
                            location.reload();
                        } else {
                            console.log(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manejo de errores
                        console.log('Error en la solicitud AJAX');
                        console.log(xhr.responseText);
                    }


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
            password: password
        },success: function(response) {
            if(response.success == true){
                borrarOperacion(password);
            }else{
                alert('Contraseña incorrecta o campo contraseña vacío');
                $('#user_supervisor').addClass('is-invalid');
                $('#pass_supervisor').addClass('is-invalid');
            }
        },
        error: function(xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#loadingIndicator').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        }
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
                password_sup: password_sup
            },success: function(response) {
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
            error: function(xhr, status, error) {
                // En caso de error en la petición AJAX
                // Puedes ocultar el indicador de carga u realizar otras acciones
                $('#loadingIndicator').addClass('d-none');

                // Manejo de errores
                console.log('Error en la solicitud AJAX');
                console.log(xhr.responseText);
            }
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
          password: password
      },success: function(response) {
          if(response.success == true){
	    alert('Pago Aurotizado correctamnete');
            actualizarEstatus();
          }else{
              alert('Contraseña incorrecta o campo contraseña vacío');
              $('#pass_supervisor').addClass('is-invalid');
          }
      },
      error: function(xhr, status, error) {
          // En caso de error en la petición AJAX
          // Puedes ocultar el indicador de carga u realizar otras acciones
          $('#loadingIndicator').addClass('d-none');

          // Manejo de errores
          console.log('Error en la solicitud AJAX');
          console.log(xhr.responseText);
      }
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
      },success: function(response) {
              if (response.success) {
                  $('#loadingIndicator').addClass('d-none');
                  alert(response.message);
                  // Abrir el modal solo si se recibe una respuesta exitosa
                  $('#modal_detalle_pagos').modal('show');
                 
              } else {
                  console.log(response.message);
              }
      },
      error: function(xhr, status, error) {
          // En caso de error en la petición AJAX
          // Puedes ocultar el indicador de carga u realizar otras acciones
          $('#loadingIndicator').addClass('d-none');

          // Manejo de errores
          console.log('Error en la solicitud AJAX');
          console.log(xhr.responseText);
      }
  });




}




//FUNCTION BTN DETALLE RETENIDOS
$('#btnDetalleRetenidos').click(function() {
  $('#modalDetalleRetenidos').show();
});


function GuardarEstatus() {



        $('#spinner_guardarEstatus').removeClass('d-none');
        $('#btnGuardarEstatus').prop('disabled', true);


        var Referencia_Nexen = $('#referencia_nexen').val();
        var Fecha_Retenido  = $('#modal_fecha_retenido').val();
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
              Estatus: Estatus

          },
          success: function(response) {
              if (response.success) {
                  alert(response.message);
                  // Abrir el modal solo si se recibe una respuesta exitosa
                  $('#modalDetalleRetenidos').modal('hide');
                  location.reload();
              } else {
                  console.log(response.message);
              }
          },
          error: function(xhr, status, error) {
              // Manejo de errores
              console.log('Error en la solicitud AJAX');
              console.log(xhr.responseText);
          }
      });

}


function mostrar_datos_cuenta(valor){
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = 'recuperar_datos_cuenta.php';
  let strData = "valor="+valor;
  request.open("POST",ajaxUrl,true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(strData);
  
  //console.log(request.responseText);
  request.onreadystatechange = function(){
      if(request.readyState == 4 && request.status == 200)
      {
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

          document.querySelector("#razon_social_spc").value = objData[0]['Razon_social'];
          document.querySelector("#razon_social_pago").value = objData[0]['Razon_social'];
          document.querySelector("#rfc_sp").value = objData[0]['RFC'];
          document.querySelector("#rfc_sp_pago").value = objData[0]['RFC'];
          document.querySelector("#banco_sp").value = objData[0]['Banco'];
          document.querySelector("#banco_sp_pago").value = objData[0]['Banco'];
          document.querySelector("#cuenta_sp").value = objData[0]['Cuenta'];
          document.querySelector("#cuenta_sp_pago").value = objData[0]['Cuenta'];
          document.querySelector("#clabe_sp").value = objData[0]['Clabe'];
          document.querySelector("#clabe_sp_pago").value = objData[0]['Clabe'];
          document.querySelector("#abba_sp").value = objData[0]['SWT_ABBA'];
          document.querySelector("#abba_sp_pago").value = objData[0]['SWT_ABBA'];
          document.querySelector("#banco_inter_sp").value = objData[0]['Banco_Intermediario'];
          document.querySelector("#banco_inter_sp_pago").value = objData[0]['Banco_Intermediario'];
          document.querySelector("#domicilio_sp").value = objData[0]['Domicilio_Completo'];
          document.querySelector("#domicilio_sp_pago").value = objData[0]['Domicilio_Completo'];

      }
  }
}

$('#btnDeleteRetenido').click(function() {
    $('#modal_fecha_retenido').val('');
});
$('#btnDeleteLiberacion').click(function() {
    $('#modal_fecha_liberacion').val('');
});

//modal operador
$('#btnEditarOperador').click(function() {
    $('#modalOperador').modal('show');
});

$('#comprobarContraSupervisor').click(function(){

    var user_supervisor = $('#user_operador_edit');
    var pw_supervisor = $('#pw_operador_edit');

    if (user_supervisor.val() === '' || pw_supervisor.val() === '') {
        user_supervisor.addClass('is-invalid');
        pw_supervisor.addClass('is-invalid');
    } else {
        user_supervisor.removeClass('is-invalid');
        pw_supervisor.removeClass('is-invalid');
        $('#modal_spinner_operador').removeClass('d-none');

        comprobarSupervisor(user_supervisor,pw_supervisor);
    }
});

//ajax para saber si es correcta la contraseña de supervisor
function comprobarSupervisor(user_supervisor,pw_supervisor) {
    var user_sup = user_supervisor.val();
    var password = pw_supervisor.val();

    $.ajax({
        url: '../include/cargarFacturas.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'VerificarPasswordSupervisor',
            user_sup: user_sup,
            password: password
        },success: function(response) {
            if(response.success == true){
                $('#alerta_edit_operador').addClass('d-none');
                $('#user_operador_edit').val('');
                $('#pw_operador_edit').val('');
                $('#modal_spinner_operador').removeClass('d-none');
                $('#modalOperador').modal('hide');
                $('#nombre_operador').prop('disabled', false);
            }else{
                $('#alerta_edit_operador').removeClass('d-none');
                //alert('Contraseña incorrecta o campo contraseña vacío');
                $('#user_supervisor').addClass('is-invalid');
                $('#pass_supervisor').addClass('is-invalid');
                $('#modal_spinner_operador').addClass('d-none');
                $('#nombre_operador').prop('disable',false);

            }
        },
        error: function(xhr, status, error) {
            // En caso de error en la petición AJAX
            // Puedes ocultar el indicador de carga u realizar otras acciones
            $('#modal_spinner_operador').addClass('d-none');

            // Manejo de errores
            console.log('Error en la solicitud AJAX');
            console.log(xhr.responseText);
        }
    });
}

//asignar el onchange al hidden del select
$('#nombre_operador').change(function() {
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
          dir_empresa_input: dir_empresa_input
      },
       success: function(response) {
           if(response.success == true){
               alert(response.message);
               location.reload();
           } else {
               alert(response.message);
           }
       },
       error: function(xhr, status, error) {
           // En caso de error en la petición AJAX
           // Puedes ocultar el indicador de carga u realizar otras acciones
           $('#modal_spinner_operador').addClass('d-none');

           // Manejo de errores
           console.log('Error en la solicitud AJAX');
           console.log(xhr.responseText);
       }
   });
});