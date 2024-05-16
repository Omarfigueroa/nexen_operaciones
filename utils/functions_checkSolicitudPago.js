import FormUtilities from '../resources/js/FormUtilities.js'

const formSolicitudPagoFU = new FormUtilities('#SolicitudPago')

$(document).ready(function () {
    $('#checkSolicitudPagos').click(function (e) { 
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
            ignorarCampos.push('supervisor_user', 'pass_supervisor_sol')
        }
        formSolicitudPagoFU.ignoreInputs = ignorarCampos;

        console.log( formSolicitudPagoFU.data )

        formSolicitudPagoFU.validarCampos();
        if (!formSolicitudPagoFU.validarUserInput()) {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Todos los campos son obligatorios",
          });

          return;
        }
        
        // Construye el contenido de la tabla
        var tableContent = '<div class="table-responsive"><table class="table table-striped">';
        var isGray = true;

        $('#SolicitudPago input[type="text"], #SolicitudPago input[type="radio"]:checked').each(function() {
            // Ignora los inputs específicos y radio buttons no seleccionados
            if (!$(this).closest('.custom-modal-content-container').length &&
                !$(this).is('#supervisor_user') && !$(this).is('#pass_supervisor_sol')) {
                var label = $(this).siblings('label').text();
                var value = $(this).val();

                // Reemplaza input vacío por "S/N"
                if (value.trim() === '') {
                    value = "S/N";
                }

                if ($(this).attr('type') === 'checkbox' && value === 'on') {
                    label = 'Tipo de documento';
                }

                var rowColorClass = isGray ? 'bg-light' : '';
                tableContent += '<tr class="' + rowColorClass + '"><td class="text-start"><strong>' + label + ':</strong></td><td class="text-center">' + value + '</td></tr>';
                isGray = !isGray;
            }
        });

        tableContent += '</table></div>';

        // Muestra el modal de SweetAlert con la tabla de contenido
        Swal.fire({
            title: '¿Estás seguro que la información es correcta?',
            html: tableContent,
            icon: 'info',
            width: '50%', // Hace que la alerta sea más ancha
            confirmButtonText: 'Si, estoy seguro que son correctos',
            cancelButtonText: 'Incorrectos, volver a llenar',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('#enviar_detalle_pago').prop('disabled', false);
                $('#checkSolicitudPagos').prop('disabled', true);
            }
        });
    });
});