document.addEventListener("DOMContentLoaded", function () {
  var facturaCount = 1;
  var total = 0;
  actualizarNumerosFactura();

  $("#btnAgregar").click(function () {
      const fields = [
          "proveedor_fact", "modal_pais_origen", "modal_num_factura",
          "modal_fecha_factura", "incoterms", "desc_factura",
          "desc_factura_i", "modal_cantidad", "medida",
          "precio_unitario", "modal_moneda", "precio_total",
          "modal_mark", "modal_peso_bruto", "modal_peso_neto"
      ];

      const data = fields.reduce((obj, id) => {
          obj[id] = $("#" + id).val();
          return obj;
      }, {});

      if (!validarCampos(data, fields)) {
          return;
      }

      agregarFila(facturaCount, data);
      fields.forEach(id => $("#" + id).val(""));
      $(".form-control").removeClass("is-invalid is-valid");
      facturaCount++;
  });

  function validarCampos(data, fields) {
      let isValid = true;
      fields.forEach(id => {
          const field = $("#" + id);
          if (!data[id]) {
              field.addClass("is-invalid");
              isValid = false;
          } else {
              field.removeClass("is-invalid");
          }
      });

      if (!isValid) {
          const mensaje = fields.filter(id => !data[id]).map(id => obtenerNombreCampo(id)).join(", ");
          SweetView(mensaje);
      }

      return isValid;
  }

  function obtenerNombreCampo(id) {
      const nombres = {
          proveedor_fact: "Proveedor",
          modal_pais_origen: "País",
          modal_num_factura: "Número de Factura",
          modal_fecha_factura: "Fecha",
          incoterms: "Incoterms",
          desc_factura: "Descripción en Español",
          desc_factura_i: "Descripción en Inglés",
          modal_cantidad: "Cantidad",
          medida: "Medida",
          precio_unitario: "Precio Unitario",
          modal_moneda: "Moneda",
          precio_total: "Precio Total",
          modal_mark: "Marcador",
          modal_peso_bruto: "Peso Bruto",
          modal_peso_neto: "Peso Neto"
      };
      return nombres[id] || id;
  }

  function agregarFila(factura, data) {
      const fila = `
          <tr>
              <th scope="row">${factura}</th>
              <td>${data.desc_factura}</td>
              <td>${data.desc_factura_i}</td>
              <td>${data.modal_cantidad}</td>
              <td>${data.medida}</td>
              <td>${data.precio_unitario}</td>
              <td>${data.modal_moneda}</td>
              <td>${data.precio_total}</td>
              <td>${data.modal_peso_bruto}</td>
              <td>${data.modal_peso_neto}</td>
              <td>${data.modal_mark}</td>
              <td><button class="btn btn-danger btn-borrar" data-factura="${factura}">Borrar</button></td>
          </tr>
      `;

      $("#tablaFacturas tbody").append(fila);

      $(`.btn-borrar[data-factura="${factura}"]`).click(function () {
          const fila = $(this).closest("tr");
          const precioTotal = parseFloat(fila.find("td:nth-child(8)").text());
          const modal_peso_bruto = parseFloat(fila.find("td:nth-child(9)").text());
          const modal_peso_neto = parseFloat(fila.find("td:nth-child(10)").text());

          fila.remove();

          total = Math.max(0, total - precioTotal);
          total_peso_bruto = Math.max(0, total_peso_bruto - modal_peso_bruto);
          total_peso_neto = Math.max(0, total_peso_neto - modal_peso_neto);

          actualizarTotal();
          actualizarNumerosFactura();
      });

      actualizarTotal();
      actualizarNumerosFactura();
  }

  function actualizarNumerosFactura() {
      $("#tablaFacturas tbody tr").each(function (index) {
          const numeroFactura = index + 1;
          $(this).find("th").text(numeroFactura);
          $(this).find(".btn-borrar").attr("data-factura", numeroFactura);
      });
  }

  function actualizarTotal() {
      let total = 0;
      let total_peso_bruto = 0;
      let total_peso_neto = 0;

      $("#tablaFacturas tbody tr").each(function () {
          total += parseFloat($(this).find("td:nth-child(8)").text());
          total_peso_bruto += parseFloat($(this).find("td:nth-child(9)").text());
          total_peso_neto += parseFloat($(this).find("td:nth-child(10)").text());
      });

      $("#total").text(total.toFixed(3)).val(total.toFixed(3));
      $("#total_peso_bruto").text(total_peso_bruto.toFixed(3)).val(total_peso_bruto.toFixed(3));
      $("#total_peso_neto").text(total_peso_neto.toFixed(3)).val(total_peso_neto.toFixed(3));
  }
});

let tablefacturas;
function modalVerFacturas() {
  $("#loadingMessage").show();
  const referencia_nexen = $("#referencia_nexen").val();
  tablefacturas = $("#tablefacturas").DataTable({
      aProcessing: true,
      aServerSide: true,
      language: { url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
      ajax: { url: `../include/Facturas/cargarFacturasDetalles.php?referencia_nexen=${referencia_nexen}`, dataSrc: "" },
      columns: [
          { data: "Referencia_Nexen" },
          { data: "Proveedor" },
          { data: "Tax_Id" },
          { data: "Numero_Factura" },
          { data: "Fecha_Factura" },
          { data: "Importador_Exportador" },
          { data: "Total_General" },
          { data: "Usuario" },
          { data: "Detalles" },
          { data: "Invoice" },
          { data: "Packing_List" },
          { data: "Editar" },
          { data: "Eliminar" }
      ],
      bDestroy: true,
      iDisplayLength: 10,
      order: [[0, "desc"]],
      searching: true,
      scrollY: true,
      fixedHeader: true,
      scrollX: true
  });

  $("#tablefacturas").on("init.dt draw.dt", function () {
      $("#loadingMessage").hide();
  });

  $("#modalVerFacturas").modal("show");
}


/**
 * Inicializa los select de la modal carga factura.
 */
function initializePage() {
  setSelects();
}

window.onload = initializePage;

/**
 * Establece los selects dinámicamente
 */
function setSelects() {
  var selects = [
    {
      id: "Id",
      descripcion: "Descripcion",
      idSelect: "incoterms",
      action: "incoterms",
    },
    {
      id: "Id_medida",
      descripcion: "Medida",
      idSelect: "medida",
      action: "extent",
    },
    {
      id: "ID_MONEDA",
      descripcion: "PREFIJO",
      idSelect: "modal_moneda",
      action: "currency",
    },
    {
      id: "proveedor",
      descripcion: "proveedor",
      idSelect: "proveedor_fact",
      action: "provider",
    },
  ];
  selects.forEach(function (select) {
    generalOptionsSelector(
      select.id,
      select.descripcion,
      select.idSelect,
      select.action
    );
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

function generalOptionsSelector(
  dataIdField,
  dataDescriptionField,
  selectId,
  action
) {
  var selectElement = document.getElementById(selectId);
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "../include/Facturas/GetSelectsInvoice.php?action=" + action,
    true
  );
  xhr.setRequestHeader("Content-type", "application/json");
  xhr.onload = function () {
    if (xhr.status == 200) {
      var responseData = JSON.parse(xhr.responseText);
      responseData.forEach(function (item) {
        var option = document.createElement("option");
        option.text = item[dataDescriptionField];
        option.value = item[dataIdField];
        selectElement.appendChild(option);
      });
      $(selectElement).selectpicker("refresh");
    } else {
      console.error("Error al obtener los datos del servidor: " + xhr.status);
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
    url: "../include/Facturas/GetSelectsInvoice.php",
    type: "GET",
    data: {
      action: action,
      id: id,
    },
    success: function (response) {
      callback(response);
    },
    error: function (xhr, status, error) {
      console.error("Error: " + error);
    },
  });
}

/**
 * Calcula el precio unitario y lo muestra en los campos de precio unitario y precio "fishing(dato que se muestra al usuario)".
 */
function calcularPrecioTotal() {
  var cantidad = parseFloat($("#modal_cantidad").val());
  var precioTotal = parseFloat($("#precio_total").val());

  if (!isNaN(cantidad) && !isNaN(precioTotal)) {
    var precioUnitario = precioTotal / cantidad;

    $("#precio_unitario").val(precioUnitario.toFixed(7));
    $("#precio_fishing").val(precioUnitario.toFixed(3));
  }
}

/**
 * Realiza una llamada AJAX para obtener información del proveedor según el ID seleccionado.
 */
var selectElement = document.getElementById("proveedor_fact");
selectElement.addEventListener("change", function () {
  var action = "selectInfo";
  var idText = selectElement.value;
  getProviderByName(action, idText, function (response) {
    try {
      var data = typeof response === "string" ? JSON.parse(response) : response;
      $("#tax_id").val(data[0].codigo);
      $("#modal_domicilio_proveedor").val(data[0].domicilio);
    } catch (e) {
      console.error("Error parsing JSON response: " + e);
    }
  });
});
/**
 * Realiza una llamada AJAX para cargar el modal con los selects.
 */

/**
 * Carga la información de la factura en un modal.
 */
function modalCargarFactura() {
  var nombreOperador = $("#nombre_operador_o").val();

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

function enviarSolicitudAjax() {
  if ($('#tablaFacturas tbody tr').length === 0) {
      SweetView('No hay partidas en la tabla. No se puede guardar');
      return;
  }

  const data = {
      opcion: 'insertarOperacionFactura',
      referencia_nexen: $('#referencia_nexen').val(),
      modal_pais_origen: $('#modal_pais_origen').val(),
      nombreOperador: $('#modal_nombre_operador').val(),
      rfcOperador: $('#modal_rfc_operador').val(),
      domOperador: $('#modal_domicilio_operador').val(),
      numFactura: $('#modal_num_factura').val(),
      proveedorFact: $('#proveedor_fact').val(),
      fechaFactura: $('#modal_fecha_factura').val(),
      taxId: $('#tax_id').val(),
      total: $('#total').val(),
      total_peso_bruto: $('#total_peso_bruto').val(),
      total_peso_neto: $('#total_peso_neto').val()
  };
  Object.entries(data).forEach(([key, value]) => {
    console.log(`${key}: ${value}`);
});

  // $('#spinner_insert').removeClass('d-none');
  // $('#btnGuardarFacturas').prop('disabled', true);

  // $.ajax({
  //     url: '../include/Facturas/cargarFacturas.php',
  //     method: 'POST',
  //     data: data,
  //     dataType: 'json',
  //     beforeSend: () => $('#loadingModal').modal('show'),
  //     success: (response) => {
  //         if (!response.success) {
  //             alert(response.message);
  //             location.reload();
  //             return;
  //         }

  //         const { lastId, referencia_nexen } = response;
  //         const numFactura = $('#modal_num_factura').val();
  //         const incoterms = $('#incoterms').val();
  //         let successShown = false;

  //         $('#tablaFacturas tbody tr').each((index, element) => {
  //             const rowData = {
  //                 opcion: 'insertarFacturaDetalle',
  //                 lastId,
  //                 referencia_nexen,
  //                 numFactura,
  //                 incoterms,
  //                 partida: $(element).find('th').text(),
  //                 descripcion: $(element).find('td:nth-child(2)').text(),
  //                 descripcion_i: $(element).find('td:nth-child(3)').text(),
  //                 cantidad: parseFloat($(element).find('td:nth-child(4)').text()),
  //                 medida: $(element).find('td:nth-child(5)').text(),
  //                 precioUnitario: parseFloat($(element).find('td:nth-child(6)').text()),
  //                 moneda: $(element).find('td:nth-child(7)').text(),
  //                 total_partida: parseFloat($(element).find('td:nth-child(8)').text()),
  //                 peso_bruto: $(element).find('td:nth-child(9)').text(),
  //                 peso_neto: $(element).find('td:nth-child(10)').text(),
  //                 mark: $(element).find('td:nth-child(11)').text()
  //             };

  //             $.post('../include/cargarFacturas.php', rowData, (response) => {
  //                 if (!successShown) {
  //                     SweetViewTrue(response.message, () => location.reload());
  //                     successShown = true;
  //                 }
  //             }, 'json').fail((xhr, status, error) => {
  //                 console.error('Error al realizar el insert para factura ' + lastId, error);
  //             });
  //         });
  //     },
  //     error: (xhr, status, error) => console.error(error),
  //     complete: () => $('#loadingModal').modal('hide')
  // });
}
