import FormUtilities from '../resources/js/FormUtilities.js'

const formSolicitudPagoFU = new FormUtilities('#SolicitudPago')
const containerDocumento = $('#container-documento')
const documentoInput = $('#documento-input')

$(document).ready(function() {
    if(document.querySelector("#SolicitudPago")){

      let formSolicitudPago = document.querySelector("#SolicitudPago");
      formSolicitudPago.onsubmit = function (e) {
        e.preventDefault();

        const ignorarCampos = [
          "Referencia_Proveedor_pago",
          "banco_inter_sp",
          "banco_inter_sp_pago",
          "clabe_sp",
          "clabe_sp_pago",
          "abba_sp",
          "abba_sp_pago",
          "cuenta_sp",
          "cuenta_sp_pago",
        ];

        const userInputData = formSolicitudPagoFU.data;
        if (userInputData.tipo_solicitud === "FINANCIADO") {
          ignorarCampos.push("file");
        } else {
          ignorarCampos.push("supervisor_user", "pass_supervisor_sol");
        }
        formSolicitudPagoFU.ignoreInputs = ignorarCampos;

        formSolicitudPagoFU.validarCampos();
        if (!formSolicitudPagoFU.validarUserInput()) {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Todos los campos son obligatorios",
          });

          return;
        }

        // variables para las validaciones
        var botonenviar = document.getElementById("enviar_detalle_pago");
        var botonCancelar = document.getElementById("cancelar_detalle_pago");
        botonenviar.disabled = true;
        botonCancelar.disabled = true;
        var concepto_sp = document.getElementById("concepto_sp").value;
        var selectCuenta = document.getElementById("select_Cuenta").value;
        var moneda_sp = document.getElementById("moneda_sp").value;
        var monto_sp = document.getElementById("monto_sp").value;
        var fileInput = document.getElementById("file");
        //validaciones de campos obligatorios
        if (selectCuenta === "") {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Por favor, selecciona una opción en el campo Cuenta.",
          });
          botonenviar.disabled = false;
          botonCancelar.disabled = false;
          return false; // Evita que el formulario se envíe si no se selecciona una opción
        }
        if (concepto_sp === "") {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Por favor, selecciona una opción en el campo Concepto.",
          });
          botonenviar.disabled = false;
          botonCancelar.disabled = false;
          return false; // Evita que el formulario se envíe si no se selecciona una opción
        }
        if (concepto_sp === "OTROS") {
          var observaciones_sp =
            document.getElementById("observaciones_sp").value;
          if (observaciones_sp === "") {
            Swal.fire({
              position: "center",
              icon: "error",
              title: "Por favor, La observacion es obligatoria",
            });
            botonenviar.disabled = false;
            botonCancelar.disabled = false;
            return false;
          }
        }
        if (monto_sp === "") {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Por favor, ingrese un Monto.",
          });
          botonenviar.disabled = false;
          botonCancelar.disabled = false;
          return false; // Evita que el formulario se envíe si no se selecciona una opción
        }
        if (moneda_sp === "") {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Por favor, selecciona una opción en el campo Moneda.",
          });
          botonenviar.disabled = false;
          botonCancelar.disabled = false;
          return false; // Evita que el formulario se envíe si no se selecciona una opción
        }
        var financiadoRadioButton = document.getElementById("financiado");
        if (financiadoRadioButton.checked != true) {
          if (fileInput.files.length === 0) {
            Swal.fire({
              position: "center",
              icon: "error",
              title: "Por favor, Es necesario subir el comprobante de pago",
            });
            botonenviar.disabled = false;
            botonCancelar.disabled = false;
            return false;
          }
        }

        //despues de validar se manda la solicitud para el manejo de datos
        let request = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");
        let ajaxUrl = "../include/guardar_solicitud_pago.php";
        let formData = new FormData(formSolicitudPago);

        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function () {
          if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            console.log(objData.id);
            if (objData.status === true) {
              Swal.fire({
                title: "Exito",
                html: `<p>${objData.msg}</p>`,
                icon: "success",
                color: "#000000",
                background: "#fff",
                showConfirmButton: false,
                timer: 1500,
              }).then(function () {
                // Limpiar el campo de entrada después de cerrar la alerta
                formSolicitudPago.reset();
                $("#modal_solicitud_pagos").modal("hide");
                var id_pago = objData.id; // Obtener el valor de $id_pago desde PHP

                var url = "../include/pdf_pagos.php"; // Ruta del archivo PHP en el servidor

                // Crear objeto de datos con el valor de id_pago
                var data = {
                  id: id_pago,
                };

                // Realizar la solicitud AJAX
                $.ajax({
                  type: "GET",
                  url: url,
                  data: data,
                  success: function (response) {
                    location.reload();
                    console.log("ID enviado exitosamente.");
                    // Puedes realizar acciones adicionales después de enviar el ID aquí.
                  },
                  error: function (xhr, status, error) {
                    console.error("Error al enviar el ID:", error);
                    // Manejar el error de envío del ID aquí si es necesario.
                  },
                });
              });
            } else {
              if (objData.val === "1") {
                Swal.fire({
                  title: "Atencion",
                  html: `<p>${objData.msg}</p>`,
                  icon: "error",
                  color: "#000000",
                  background: "#fff",
                }).then(function () {
                  botonenviar.disabled = false;
                  botonCancelar.disabled = false;
                });
              } else if (objData.val === "5") {
                Swal.fire({
                  title: "Atencion",
                  html: `<p>${objData.msg}</p>`,
                  icon: "error",
                  color: "#000000",
                  background: "#fff",
                }).then(function () {
                  botonenviar.disabled = false;
                  botonCancelar.disabled = false;
                });
              } else if (objData.val === "6") {
                Swal.fire({
                  title: "Atencion",
                  html: `<p>${objData.msg}</p>`,
                  icon: "error",
                  color: "#000000",
                  background: "#fff",
                }).then(function () {
                  botonenviar.disabled = false;
                  botonCancelar.disabled = false;
                });
              }
            }
          }
        };
      };
    }
});

function openDetalle(){
    $('#modal_solicitud_pagos').modal('show');
  }

  $('#modal_solicitud_pagos').on('hidden.bs.modal', function() {
    formSolicitudPagoFU.reset()
  });
  
  var anticipoRadioButton = document.getElementById("anticipo");
  var ContraseñaInputs = document.getElementById("permiso");
  var financiadoRadioButton = document.getElementById("financiado");
  var supervisorUserInput = document.getElementById("supervisor_user");
  var passSupervisorInput = document.getElementById("pass_supervisor_sol");
  // Agregar evento de cambio a ambos radio buttons
  financiadoRadioButton.addEventListener("change", function() {

    documentoInput.detach()

    // Verificar si el radio button está seleccionado
    if (this.checked) {
        ContraseñaInputs.style.display = "block";
        supervisorUserInput.setAttribute('required', 'required');
        passSupervisorInput.setAttribute('required', 'required');
     } 
    });
    anticipoRadioButton.addEventListener("change", function(){

      documentoInput.appendTo(containerDocumento)

      if (this.checked) {
        supervisorUserInput.removeAttribute('required');
        passSupervisorInput.removeAttribute('required');
          ContraseñaInputs.style.display = "none";
      }
    });

    $('#cancelar_detalle_pago').on('click', CloseDetalle)

    function CloseDetalle(){
      let formSolicitudPago = document.querySelector("#SolicitudPago");
      formSolicitudPago.reset();
      $('#modal_solicitud_pagos').modal('hide');
    }
    function limitarDecimales(event) {
      const input = event.target;
      const value = input.value;
    
      // Limitar a dos decimales
      input.value = parseFloat(value).toFixed(2);
    }

    function formatoMiles(input) {
      // Remover todos los caracteres que no sean números o punto decimal
      let valorSinComas = input.value.replace(/[^\d.]/g, '');
      
      // Dividir la parte entera y decimal del valor
      let [parteEntera, parteDecimal] = valorSinComas.split('.');
      
      // Agregar comas a la parte entera para representar los miles
      parteEntera = parteEntera.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      
      // Combinar la parte entera y decimal con dos decimales
      let valorFormateado = parteDecimal !== undefined ? parteEntera + "." + parteDecimal.slice(0, 2) : parteEntera;
      
      // Actualizar el valor del input con la máscara de miles
      input.value = valorFormateado;
    }