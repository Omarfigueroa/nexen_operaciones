$(document).ready(function () {
  $(".selectpicker").selectpicker();
  var facturaCount = 1;
  var cantidadTotal = 0;
  var total_unitario = 0;
  var total_General = 0;
  var total_peso_bruto = 0;
  var total_peso_neto = 0;
  // Evento click del botón "Agregar"
  $("#btnAgregar").click(function () {
    // //Comprobacion de campos partida
    // // Reiniciar clases de validación
    // $(".form-control").removeClass("is-invalid");
    const proveedorSelect = $("#proveedor_fact").val();
    const Pais_origen = $("#modal_pais_origen").val();
    const Num_Factura = $("#modal_num_factura").val();
    const Fecha_Factura = $("#modal_fecha_factura").val();
    const incoterms = $("#incoterms").val();
    const CoveEspañol = $("#desc_factura").val();
    const CoveInlges = $("#desc_factura_i").val();
    const Cantidad = $("#modal_cantidad").val();
    const Medida = $("#medida").val();
    const Precio_Unitario = $("#precio_unitario").val();
    const Moneda = $("#modal_moneda").val();
    const Precio_Total = $("#precio_total").val();
    const Mark = $("#modal_mark").val();
    const Peso_Bruto = $("#modal_peso_bruto").val();
    const Peso_Neto = $("#modal_peso_neto").val();

    const arrayValidator = [
      proveedorSelect,
      Pais_origen,
      Num_Factura,
      Fecha_Factura,
      incoterms,
      CoveEspañol,
      CoveInlges,
      Cantidad,
      Medida,
      Precio_Unitario,
      Moneda,
      Precio_Total,
      Peso_Bruto,
      Peso_Neto,
      Mark,
    ];
    //CONSTANTE Y RECORRIDO DE ARRAY PARA VALIDACION DE CAMPOS
    var isValid = true;
    arrayValidator.forEach((value, index) => {
      const fieldId = getFieldId(index);
      const field = $("#" + fieldId);

      if (
        value === null ||
        value === "" ||
        value === undefined ||
        value === "undefined"
      ) {
        $("#proveedor_fact").selectpicker("destroy");
        $("#incoterms").selectpicker("destroy");
        $("#medida").selectpicker("destroy");
        $("#modal_moneda").selectpicker("destroy");

        $("#" + fieldId).addClass("is-invalid");

        $("#proveedor_fact").selectpicker("render");
        $("#incoterms").selectpicker("render");
        $("#medida").selectpicker("render");
        $("#modal_moneda").selectpicker("render");

        isValid = false;
      } else {
        $("#proveedor_fact").selectpicker("destroy");
        $("#incoterms").selectpicker("destroy");
        $("#medida").selectpicker("destroy");
        $("#modal_moneda").selectpicker("destroy");

        $("#" + fieldId).removeClass("is-invalid");

        $("#proveedor_fact").selectpicker("render");
        $("#incoterms").selectpicker("render");
        $("#medida").selectpicker("render");
        $("#modal_moneda").selectpicker("render");
      }
    });
    //FUNCION PARA ASIGNAR ID CONFORME AL ARRAY
    function getFieldId(index) {
      switch (index) {
        case 0:
          return "proveedor_fact";
        case 1:
          return "modal_pais_origen";
        case 2:
          return "modal_num_factura";
        case 3:
          return "modal_fecha_factura";
        case 4:
          return "incoterms";
        case 5:
          return "desc_factura";
        case 6:
          return "desc_factura_i";
        case 7:
          return "modal_cantidad";
        case 8:
          return "medida";
        case 9:
          return "precio_unitario";
        case 10:
          return "modal_moneda";
        case 11:
          return "precio_total";
        case 12:
          return "modal_mark";
        case 13:
          return "modal_peso_bruto";
        case 14:
          return "modal_peso_neto";
        default:
          return "";
      }
    }

    if (!isValid) {
      // Crear un array de objetos con dos campos cada uno
      var mensajeserrores = [
        { idcampo: "#proveedor_fact", mensaje: "Proveedor" },
        { idcampo: "#modal_pais_origen", mensaje: "País" },
        { idcampo: "#modal_num_factura", mensaje: "Número de Factura" },
        { idcampo: "#modal_fecha_factura", mensaje: "Fecha" },
        { idcampo: "#incoterms", mensaje: "Incoterms" },
        { idcampo: "#desc_factura", mensaje: "Descripción en Español" },
        { idcampo: "#desc_factura_i", mensaje: "Descripción en Inglés" },
        { idcampo: "#modal_cantidad", mensaje: "Cantidad" },
        { idcampo: "#medida", mensaje: "Medida" },
        { idcampo: "#modal_moneda", mensaje: "Moneda" },
        { idcampo: "#precio_total", mensaje: "Precio Total" },
        { idcampo: "#modal_mark", mensaje: "Marcador" },
        { idcampo: "#modal_peso_bruto", mensaje: "Peso Bruto" },
        { idcampo: "#modal_peso_neto", mensaje: "Peso Neto" },
      ];

      // Variable para almacenar los mensajes de error
      var mensajesErroresHTML = "";

      // Variable para indicar si hay errores
      var isValid = true;

      mensajeserrores.forEach(function (item) {
        var valor = $(item.idcampo).val();

        if (valor === "" || valor === null || valor === undefined) {
          $(item.idcampo).addClass("is-invalid");
          isValid = false;

          mensajesErroresHTML += `${item.mensaje}, `;
        } else {
          $(item.idcampo).removeClass("is-invalid");
        }
      });

      // Mostrar SweetAlert con los mensajes de error
      if (!isValid) {
        const mensaje = mensajesErroresHTML;
        SweetView(mensaje);
        return;
      }
    }
    var factura = facturaCount;
    agregarFila(
      factura,
      proveedorSelect,
      Pais_origen,
      Num_Factura,
      Fecha_Factura,
      incoterms,
      CoveEspañol,
      CoveInlges,
      Cantidad,
      Medida,
      Precio_Unitario,
      Moneda,
      Precio_Total,
      Peso_Bruto,
      Peso_Neto,
      Mark
    );
    $("#proveedor_fact").selectpicker("destroy");
    $("#incoterms").selectpicker("destroy");
    $("#medida").selectpicker("destroy");
    $("#modal_moneda").selectpicker("destroy");

    $("#proveedor_fact").val("");
    $("#modal_pais_origen").val("");
    $("#modal_num_factura").val("");
    $("#modal_fecha_factura").val("");
    $("#incoterms").val("");
    $("#desc_factura").val("");
    $("#desc_factura_i").val("");
    $("#modal_cantidad").val("");
    $("#medida").val("");
    $("#precio_unitario").val("");
    $("#modal_moneda").val("");
    $("#precio_total").val("");
    $("#modal_mark").val("");
    $("#modal_peso_bruto").val("");
    $("#modal_peso_neto").val("");

    $(".form-control").removeClass("is-invalid");
    $(".form-control").removeClass("is-valid");

    $("#proveedor_fact").selectpicker("render");
    $("#incoterms").selectpicker("render");
    $("#medida").selectpicker("render");
    $("#modal_moneda").selectpicker("render");
    facturaCount++;
  });
  // Función para agregar una fila a la tabla
  function agregarFila(
    factura,
    proveedor,
    pais,
    num_factura,
    Fecha_factura,
    icoterms,
    descripcion,
    descripcion_i,
    cantidad,
    medida,
    precioUnitario,
    moneda,
    precio_total,
    peso_bruto,
    peso_neto,
    mark
  ) {
    var fila =
      "<tr>" +
      '<th scope="row">' +
      factura +
      "</th>" +
      "<td>" +
      proveedor +
      "</td>" +
      "<td>" +
      pais +
      "</td>" +
      "<td>" +
      num_factura +
      "</td>" +
      "<td>" +
      Fecha_factura +
      "</td>" +
      "<td>" +
      icoterms +
      "</td>" +
      "<td>" +
      descripcion +
      "</td>" +
      "<td>" +
      descripcion_i +
      "</td>" +
      "<td>" +
      cantidad +
      "</td>" +
      "<td>" +
      medida +
      "</td>" +
      "<td>" +
      precioUnitario +
      "</td>" +
      "<td>" +
      moneda +
      "</td>" +
      "<td>" +
      precio_total +
      "</td>" +
      "<td>" +
      peso_bruto +
      "</td>" +
      "<td>" +
      peso_neto +
      "</td>" +
      "<td>" +
      mark +
      "</td>" +
      "<td>" +
      '<button class="btn btn-danger btn-borrar" data-factura="' +
      factura +
      '">Borrar</button>' +
      "</td>" +
      "</tr>";

    $("#tablaFacturas tbody").append(fila);
    $('.btn-borrar[data-factura="' + factura + '"]').click(function () {
      var fila = $(this).closest("tr");
      fila.remove();
      actualizarNumerosFactura();
      actualizarTotal(
        cantidad,
        precioUnitario,
        precio_total,
        peso_bruto,
        peso_neto,
        (accion = "resta")
      );
    });
    actualizarNumerosFactura();
    actualizarTotal(
      cantidad,
      precioUnitario,
      precio_total,
      peso_bruto,
      peso_neto,
      (accion = "suma")
    );
  }

  // Función para actualizar el número de factura en las filas
  function actualizarNumerosFactura() {
    var filas = $("#tablaFacturas tbody tr");
    filas.each(function (index) {
      var numeroFactura = index + 1;
      $(this).find("th").text(numeroFactura);
      $(this).find(".btn-borrar").attr("data-factura", numeroFactura);
    });
  }

  function actualizarTotal(
    cantidad,
    precioUnitario,
    precio_total,
    peso_bruto,
    peso_neto,
    accion
  ) {
    if (accion === "suma") {
      cantidadTotal += parseFloat(cantidad);
      total_unitario += parseFloat(precioUnitario);
      total_General += parseFloat(precio_total);
      total_peso_bruto += parseFloat(peso_bruto);
      total_peso_neto += parseFloat(peso_neto);
    } else if (accion === "resta") {
      cantidadTotal -= parseFloat(cantidad);
      total_unitario -= parseFloat(precioUnitario);
      total_General -= parseFloat(precio_total);
      total_peso_bruto -= parseFloat(peso_bruto);
      total_peso_neto -= parseFloat(peso_neto);
    }

    $("#total_cantidad").text(cantidadTotal);
    $("#total_peso_unitario").text(total_unitario);
    $("#totalgeneral").text(total_General);
    $("#totalpesobruto").text(total_peso_bruto);
    $("#totalpesoneto").text(total_peso_neto);
  }
  // Manejador de evento para el botón de obtener registros
  $("#btnGuardarFacturas").on("click", function () {
    // Seleccionar la tabla
    var tabla = $("#tablaFacturas");

    // Obtener todas las filas de la tabla, excepto la primera que suele ser la fila de encabezado
    var filas = tabla.find("tbody tr"); // Solo seleccionamos las filas del tbody
    var rowCount = $("#tablaFacturas tbody tr").length;
    if (rowCount === 0) {
      const mensaje = "No hay partidas en la tabla. No se puede guardar";
      SweetView(mensaje);
      return;
    }

    // Array para almacenar los registros
    var registros = [];
    var nombreOperador = $("#modal_nombre_operador").val();
    var rfcOperador = $("#modal_rfc_operador").val();
    var domOperador = $("#modal_domicilio_operador").val();
    var taxId = $("#tax_id").val();
    var referencia_nexen = $("#referencia_nexen").val();

    // Iterar sobre cada fila para obtener la información de cada registro
    filas.each(function () {
      var fila = $(this);
      var proveedor = fila.find("td:eq(0)").text().trim();
      var pais = fila.find("td:eq(1)").text().trim();
      var num_facturas = fila.find("td:eq(2)").text().trim();
      var fecha = fila.find("td:eq(3)").text().trim();
      var icoterm = fila.find("td:eq(4)").text().trim();
      var coveEspañol = fila.find("td:eq(5)").text().trim();
      var coveingles = fila.find("td:eq(6)").text().trim();
      var cantidad = fila.find("td:eq(7)").text().trim();
      var unidad = fila.find("td:eq(8)").text().trim();
      var valorunitario = fila.find("td:eq(9)").text().trim();
      var moneda = fila.find("td:eq(10)").text().trim();
      var totalpartida = fila.find("td:eq(11)").text().trim();
      var brutro = fila.find("td:eq(12)").text().trim();
      var neto = fila.find("td:eq(13)").text().trim();
      var mark = fila.find("td:eq(14)").text().trim();

      // Verificar si la fila no está vacía
      if (
        proveedor ||
        pais ||
        num_facturas ||
        fecha ||
        icoterm ||
        coveEspañol ||
        coveingles ||
        cantidad ||
        unidad ||
        valorunitario ||
        moneda ||
        totalpartida ||
        brutro ||
        neto ||
        mark
      ) {
        // Crear un objeto con la información de la fila y agregarlo al array de registros
        var registro = {
          Proveedor: proveedor,
          pais: pais,
          num_facturas: num_facturas,
          fecha: fecha,
          icoterm: icoterm,
          coveEspañol: coveEspañol,
          coveingles: coveingles,
          cantidad: cantidad,
          unidad: unidad,
          valorunitario: valorunitario,
          moneda: moneda,
          totalpartida: totalpartida,
          brutro: brutro,
          neto: neto,
          mark: mark,
        };
        registros.push(registro);
      }
    });
    var footer = {
      total_cantidad: $("#total_cantidad").text().trim(),
      total_peso_unitario: $("#total_peso_unitario").text().trim(),
      totalgeneral: $("#totalgeneral").text().trim(),
      totalpesobruto: $("#totalpesobruto").text().trim(),
      totalpesoneto: $("#totalpesoneto").text().trim(),
    };
    var operador = {
      referencia_nexen: referencia_nexen,
      nombreOperador: nombreOperador,
      rfcOperador: rfcOperador,
      domOperador: domOperador,
      taxId: taxId,
    }; // Crear el objeto final que será enviado al servidor
    var dataToSend = {
      registros: registros,
      footer: footer,
      operador: operador,
    };
    //se muestra spinner loading
    $("#spinner_insert").removeClass("d-none");
    //bloquear con un disabled el boton de editar para que no lo aprenten
    $("#btnGuardarFacturas").prop("disabled", true);
    $("#closeFacturas").prop("disabled", true);
    // Enviar los datos mediante AJAX a un archivo PHP
    $.ajax({
      url: "../include/Facturas/guardar_facturas.php", // Ruta del archivo PHP
      type: "POST",
      data: JSON.stringify(dataToSend),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      success: function (response) {
        Swal.fire({
          icon: "success",
          title: "Exito",
          text: response.message,
        }).then(function () {
          $("#modalCargarFacturas").modal("hide");
          $("#btnGuardarFacturas").prop("disabled", false);
          $("#closeFacturas").prop("disabled", false);
          $("#spinner_insert").addClass("d-none");
        });

        return;
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Manejar errores
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: response.message,
        });
        return;
      },
    });
  });
});

/**
 * Variable y funcion para cargar modal con tabla que visualiza las facturas.
 */
let tablefacturas;
function modalVerFacturas() {
  // Mostrar mensaje de carga
  $("#loadingMessage").show();
  var referencia_nexen = $("#referencia_nexen").val();
  tablefacturas = $("#tablefacturas").DataTable({
    aProcessing: true,
    aServerSide: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
    },
    ajax: {
      url:
        "../include/Facturas/cargarFacturasDetalles.php?referencia_nexen=" +
        referencia_nexen,
      dataSrc: "",
    },
    columns: [
      // { "data": "Id_TipoCambio" },
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
      { data: "Eliminar" },
    ],
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
    searching: true,
    scrollY: true,
    fixedHeader: true, // Fijar la cabecera de la tabla
    scrollX: true,
  });

  // Ocultar mensaje de carga y mostrar tabla cuando la tabla se dibuje
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
      id: "Descripcion",
      descripcion: "Descripcion",
      idSelect: "incoterms",
      action: "incoterms",
    },
    {
      id: "Medida",
      descripcion: "Medida",
      idSelect: "medida",
      action: "extent",
    },
    {
      id: "PREFIJO",
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

    $("#precio_unitario").val(precioUnitario.toFixed(3));
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

function modalCargarFactura() {
  var nombreOperador = $("#nombre_operador_o").val();

  if (nombreOperador === "") {
    const mensaje = "Falta seleccionar el campo de nombre del operador";
    SweetView(mensaje);
  } else {
    $.ajax({
      url: "../include/Facturas/cargarFacturas.php",
      method: "POST",
      data: { opcion: "leerEmpresas", nombreOperador: nombreOperador },
      dataType: "json",
      success: function (response) {
        // Procesar la respuesta del servidor y mostrarla en el modal

        // Ejemplo: Recorrer los datos y mostrarlos en la consola
        for (var i = 0; i < response.length; i++) {
          var empresa = response[i];

          $("#modal_nombre_operador").val(empresa.Razon_Social);
          $("#modal_rfc_operador").val(empresa.RFC);
          $("#modal_domicilio_operador").val(empresa.DOMICILIO_FISCAL);

          // console.log(empresa.ID_EMPRESA, empresa.Razon_Social, empresa.RFC, empresa.DOMICILIO_FISCAL, empresa.REPRESENTANTE_LEGAL, empresa.ESTATUS);
        }
      },
      error: function () {
        const mensaje = "Error al obtener los datos del servidor";
        SweetView(mensaje);
      },
    });
  }
}


/**modal para ver detalles de factura */
function DetalleFactura(){
  Swal.fire({
    icon: "warning",
    title: "Oops...",
    text: "MODULO EN MANTENIMIENTO",

  });
  return
}


/**modal para editar facturas */

function EditarFactura(){
  Swal.fire({
    icon: "warning",
    title: "Oops...",
    text: "MODULO EN MANTENIMIENTO",

  });
  return
}

/**funcion para eliminar facturas */
function EliminarFactura(){
  Swal.fire({
    icon: "warning",
    title: "Oops...",
    text: "MODULO EN MANTENIMIENTO",

  });
  return
}