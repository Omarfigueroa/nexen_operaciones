let tablaDetallePagosReferencia;
document.addEventListener(
    "DOMContentLoaded",
    function () {
        
        /************************************************************
        -------------------------------------------------------------
        |         DATATABLE DETALLE PAGOS POR REFERENCIA             |
        -------------------------------------------------------------
        ************************************************************/
  
        
    }, false
);
//Limpiar formulario 
function limpiarModal(modid) {
    var inputsToClear = (modid === "#editFac") ? ["#Mensaje_Update", "#edit_file_pago_aceptado"] : ["#file_pago_aceptado"];
    inputsToClear.forEach(function(input) {
        $(input).val('');
    });
}
//Abrir formulario correcto
function newFac(facturaNexen, Num_Operacion, Id) {
    var modid = (Id === 1) ? "#fileUploadModal" : "#editFac";
    var facnex = (Id === 1) ? "#facturaNexenInput" : "#editFacturaNexenInput";
    var numop = (Id === 1) ? "#numOperacionInput" : "#editNumOperacionInput";

    limpiarModal(modid);
    $("#modal_detalle_pagos_referencia").modal("hide");
    $(modid).modal('show');
    $(facnex).val(facturaNexen);
    $(numop).val(Num_Operacion);
}

function opendatatable(){
    $("#modal_detalle_pagos_referencia").modal("show");
}

//Subir y editar la Factura
function newFacUpload(idIf) {

    var varidsel = (idIf === 1) ? "#file_pago_aceptado" : "#edit_file_pago_aceptado";
    var varidsave = (idIf === 1) ? "#fileUploadModal" : "#editFac";
    var varNumOpe = (idIf === 1) ? "#numOperacionInput" : "#editNumOperacionInput";
    var varFacNex = (idIf === 1) ? "#facturaNexenInput" : "#editFacturaNexenInput";
    var Mensaje_Update = (idIf === 2) ? $('#Mensaje_Update').val() : "";
    
    var facturaNexen = $(varFacNex).val();
    var Num_Operacion = $(varNumOpe).val();
    var fileInput=  $(varidsel)[0].files[0];
   

    if (!fileInput || fileInput.type !== 'application/pdf' || (varidsave === "#editFac" && Mensaje_Update === "")) {
        var mensajeOpcionalMo = (varidsave === "#editFac" && Mensaje_Update === "") ? "Un motivo" : "";
        var mensajeOpcionalPdf = (!fileInput) ? "Un PDF" : "";

        var mensajeCompleto = "Asegúrate de añadir ";
        mensajeCompleto += (mensajeOpcionalMo && mensajeOpcionalPdf) ? mensajeOpcionalMo + " y " + mensajeOpcionalPdf :
                           (mensajeOpcionalMo || mensajeOpcionalPdf);
        Swal.fire({
            icon: 'warning',
            title: "¡Datos faltantes!",
            text: mensajeCompleto,
            showConfirmButton: true,
            timer: 2500 // tiempo en milisegundos
        });
        return;
    }


    var formData = new FormData();
    formData.append('facturaNexen', facturaNexen);
    formData.append('Num_Operacion', Num_Operacion);
    if(idIf===2){ formData.append('Mensaje_Update', Mensaje_Update); }
    formData.append('file', fileInput); // Agregar el archivo al FormData

    $('#saveFacSpinner').removeClass('d-none');

    $.ajax({
        type: 'POST',
        url: '../request/finanzas/SaveFactura.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            $('#saveFacSpinner').addClass('d-none');
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: response.message 
            }).then((result) => {
                $(varidsave).modal('hide');
                $('#tablaDetallePagosReferencia').DataTable().ajax.reload();
            });
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            $('#saveFacSpinner').addClass('d-none');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al intentar guardar la factura. Por favor, inténtelo de nuevo más tarde.'
            });
        }
    });
}


function abrirmodaldet(){
    $("#spinner").show();
    $("#modal_detalle_pagos_referencia").modal("show");
setTimeout(function (){ datatablede();  setTimeout(function (){ $("#spinner").hide(); },700); },700);
}

function datatablede(){
    tablaDetallePagosReferencia = $("#tablaDetallePagosReferencia").DataTable({
        aProcessing: true,
        aServerSide: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json",
        },
        ajax: {
            "type": 'POST',
            "url": "../request/finanzas/fetchDetallePagosPorReferencia.php",
            "dataSrc": "data",
            "data": {
                'Referencia_Nexen': referenciaNexen
            },
        },
        columns: [
            { data: "Referencia_Nexen" },
            { data: "Cliente" },
            { data: "Operador" },
            { data: "Concepto" },
            { data: "Tipo_Solicitud" },
            { data: "Monto" },
            { data: "Usuario" },
            { data: "Estatus" },
            { data: "Fechope" },
        ],
        "columnDefs": [
            {
                "targets": 9,
                "render": function (data, type, row) {
                    descargar = '<span style="display: inline-flex; flex-direction: column; align-items: center; justify-content: center; margin-right: 10px;"><a href="../include/pdf_pagos_refac.php?id=' + row.Num_Operacion + '" class="custom-icon dowloand"><i class="bi bi-save-fill"></i></a><span style="text-align: center;">Descargar</span></span>';
                    if (row.Estatus === 'ACEPTADO') {
                       if (row.Ruta_Factura === null) {
                            subirArchivo = '<span style="display: inline-flex; flex-direction: column; align-items: center; justify-content: center; margin-left: 20px;"><a class="custom-icon arrowUp" onclick="newFac(\'' + row.Referencia_Nexen + '\', \'' + row.Num_Operacion + '\', 1);"><i class="bi bi-arrow-up-square-fill"></i></a><span style="text-align: center;">Subir</span></span>';
                        } else {
                            subirArchivo = '<span style="display: inline-flex; flex-direction: column; align-items: center; justify-content: center; margin-left: 20px;"><a class="custom-icon editbtn" onclick="newFac(\'' + row.Referencia_Nexen + '\', \'' + row.Num_Operacion + '\', 2);"><i class="bi bi-pencil-square" style="font-size: 25px;"></i></a><span style="text-align: center;">Modificar</span></span>';
                        }
                    } else {
                        subirArchivo = '';
                    }
                    contenedor = '<div style="display: flex; justify-content: center;">' + descargar + subirArchivo +'</div>';
                    return contenedor;
                }
            }
            
            
        ],
        bDestroy: true,
        iDisplayLength: 10,
        searching: true,
    });
}