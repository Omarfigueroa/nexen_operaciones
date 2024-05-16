//Functions de cargar_proveedor_finanzas
$(document).ready(function() {
    $('#select_mes').change(function() {
        $('#OF_File').val('');
        $('#CIF_File').val('');
    });
});

//Subir archivos
//Primero se comprueba que no exista ya un archivo correspodiente a ese mes y año.
function comprobarSiExisteSubir(btn_up) {

    var opcion = 'InsertarDocumento';
    var mes = $('#select_mes').val();
    var btn_doc = btn_up.id;
    var idCuenta = $('#idCuenta_modal').val();

    var campoFile = btn_up.id;
    campoFile = campoFile.replace(/_.*$/, "_File");

    if (!validarMes()) {
        // No se ha seleccionado un mes, detener la ejecución
        return;
    }

    // Validar el campo de tipo file
    var campoValido = validarCampoFile(campoFile);
    if (!campoValido) {
        // Mostrar alerta para el campo vacío
        alert("El campo " + campoFile + " está vacío. Por favor, selecciona un archivo.");
        return;
    }

    //activar el loading de cada boton
    var spinner = $('#spinner_' + btn_doc);
    spinner.removeClass('d-none');

    //desactivar boton para que no puedan presionarlo dos veces
    $('#'+btn_doc).prop('disabled', true);

    var formData = new FormData();
    formData.append('opcion', opcion);
    formData.append('mes', mes);
    formData.append('btn_doc', btn_doc);
    formData.append('idCuenta', idCuenta);
    formData.append('documento', $('#' + campoFile)[0].files[0]);



    $.ajax({
        url: '../include/procesar_archivoProveedor.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Procesar la respuesta exitosa
            console.log(response);
            if(response.success){
                alert(response.message);
                location.reload();
            }else{
                alert(response.message);
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            // Manejar el error
            var errorMessage = 'Ocurrió un error en la solicitud AJAX:\n\n';
            errorMessage += 'Código de error: ' + xhr.status + '\n';
            errorMessage += 'Mensaje: ' + xhr.responseText + '\n';
            errorMessage += 'Estado: ' + status + '\n';
            errorMessage += 'Error: ' + error;

            console.log(errorMessage);
            alert(errorMessage);
        }
    });
}


function comprobarSiExisteBajar(btn_down) {

    var opcion = 'DescargarDocumento';
    var mes = $('#select_mes').val();
    var btn_doc = btn_down.id;
    var idCuenta = $('#idCuenta_modal').val();


    if (!validarMes()) {
        // No se ha seleccionado un mes, detener la ejecución
        return;
    }

    //activar el loading de cada boton
    var spinner = $('#spinner_' + btn_doc);
    spinner.removeClass('d-none');

    //desactivar boton para que no puedan presionarlo dos veces
    $('#'+btn_doc).prop('disabled', true);

    var formData = new FormData();
    formData.append('opcion', opcion);
    formData.append('mes', mes);
    formData.append('btn_doc', btn_doc);
    formData.append('idCuenta', idCuenta);

    $.ajax({
        url: '../include/procesar_archivoProveedor.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Procesar la respuesta exitosa
            //console.log(response);
            if(response.success){

                location.reload();

                var pdfData = response.pdfData;

                // Crear un enlace temporal para descargar el archivo
                var link = document.createElement('a');
                link.href = "data:application/pdf;base64," + pdfData;
                link.download = response.nombre_archivo; // Reemplaza "nombre_archivo.pdf" con el nombre deseado para el archivo
                link.target = "_blank";

                // Simular un clic en el enlace para iniciar la descarga
                link.click();


            }else{
                alert(response.message);
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            // Manejar el error
            var errorMessage = 'Ocurrió un error en la solicitud AJAX:\n\n';
            errorMessage += 'Código de error: ' + xhr.status + '\n';
            errorMessage += 'Mensaje: ' + xhr.responseText + '\n';
            errorMessage += 'Estado: ' + status + '\n';
            errorMessage += 'Error: ' + error;

            console.log(errorMessage);
            alert(errorMessage);
        }
    });
}

function comprobarSiExisteBorrar(btn_delete) {

    var opcion = 'BorrarDocumento';
    var mes = $('#select_mes').val();
    var btn_doc = btn_delete.id;
    var idCuenta = $('#idCuenta_modal').val();

    if (!validarMes()) {
        // No se ha seleccionado un mes, detener la ejecución
        return;
    }

    var confirmacion = confirm('¿Estás seguro de que deseas borrar?\n' + 'Cuenta: ' + idCuenta + '\n' + 'Mes: ' + mes);
    if (confirmacion) {

    //activar el loading de cada boton
    var spinner = $('#spinner_' + btn_doc);
    spinner.removeClass('d-none');

    //desactivar boton para que no puedan presionarlo dos veces
    $('#'+btn_doc).prop('disabled', true);

    var formData = new FormData();
    formData.append('opcion', opcion);
    formData.append('mes', mes);
    formData.append('btn_doc', btn_doc);
    formData.append('idCuenta', idCuenta);

    $.ajax({
        url: '../include/procesar_archivoProveedor.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Procesar la respuesta exitosa
            //console.log(response);
            if(response.success){
                alert(response.message);
                location.reload();
            }else{
                alert(response.message);
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            // Manejar el error
            var errorMessage = 'Ocurrió un error en la solicitud AJAX:\n\n';
            errorMessage += 'Código de error: ' + xhr.status + '\n';
            errorMessage += 'Mensaje: ' + xhr.responseText + '\n';
            errorMessage += 'Estado: ' + status + '\n';
            errorMessage += 'Error: ' + error;

            console.log(errorMessage);
            alert(errorMessage);
        }
    });

    } else {
        // El usuario canceló el borrado, realizar alguna acción adecuada
        alert('Borrado cancelado.');
        location.reload();
    }

}



function validarCampoFile(campoFile) {
    var archivoInput = $("#" + campoFile);
    var archivo = archivoInput[0].files[0];

    if (!archivo) {
        // El campo de tipo file está vacío
        alert("El campo " + campoFile + " está vacío. Por favor, selecciona un archivo.");
        return false;
    }

    return true;
}

function validarMes() {
    var selectMes = $('#select_mes').val();

    if (!selectMes) {
        // No se ha seleccionado un mes
        alert('Debes escoger un mes.');
        return false;
    } else {
        // Se ha seleccionado un mes
        return true;
    }
}

function updateCtaProveedor() {

    var u_id_cta = $('#id_cta').val();
    var u_operador = $('#e_operador').val();
    var u_cuenta_destino = $('#e_cuenta_destino').val();
    var u_razon_social = $('#e_razon_social').val();
    var u_referencia_proveedor = $('#e_referencia_proveedor').val();
    var u_curp = $('#e_curp').val();
    var u_tipo_servicio = $('#e_tipo_servicio').val();
    var u_tipo_persona = $('#e_tipo_persona').val();
    var u_rfc = $('#e_rfc').val();
    var u_tipo_cuenta = $('#e_tipo_cuenta').val();
    var u_banco = $('#e_banco').val();
    var u_cuenta = $('#e_cuenta').val();
    var u_abba = $('#e_abba').val();
    var u_clabe = $('#e_clabe').val();
    var u_banco_inter = $('#e_banco_inter').val();
    var u_domicilio = $('#e_domicilio').val();

            $.ajax({
                url: '../include/crud_cta_proveedor.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    opcion: 'updateCtaProveedor',
                    u_id_cta: u_id_cta,
                    u_operador: u_operador,
                    u_cuenta_destino: u_cuenta_destino,
                    u_razon_social: u_razon_social,
                    u_referencia_proveedor: u_referencia_proveedor,
                    u_curp: u_curp,
                    u_tipo_servicio: u_tipo_servicio,
                    u_tipo_persona: u_tipo_persona,
                    u_rfc: u_rfc,
                    u_tipo_cuenta: u_tipo_cuenta,
                    u_banco: u_banco,
                    u_cuenta: u_cuenta,
                    u_abba: u_abba,
                    u_clabe: u_clabe,
                    u_banco_inter: u_banco_inter,
                    u_domicilio: u_domicilio
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        
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

function deleteCtaProveedor(id) {
    
    $.ajax({
        url: '../include/crud_cta_proveedor.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'deleteCtaProveedor',
            d_id_cuenta: id
        },
        
        success: function(response) {
            if (response.success) {
                alert(response.message);
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

function mostarCtaProveedor(id) {
    
    $.ajax({
        url: '../include/crud_cta_proveedor.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'mostarCtaProveedor',
            d_id_cuenta: id
        },
        
        success: function(response) {
            if (response.success) {
                  $('#actualizarCuentaDestino').modal('show');
                  
                    var cuentas = response.object;
                    console.log(cuentas);
                    if (cuentas.length > 0) {
                      var cuenta = cuentas[0];
                        $('#id_cta').val(cuenta.Id_Cuenta);
                        $('#e_operador').val(cuenta.Operador);
                        $('#e_cuenta_destino').val(cuenta.Cuenta_Destino);
                        $('#e_razon_social').val(cuenta.Razon_social);
                        $('#e_referencia_proveedor').val(cuenta.Ref_proveedor);
                        $('#e_curp').val(cuenta.CURP);
                        $('#e_tipo_servicio').val(cuenta.Tipo_servicio);
                        $('#e_tipo_persona').val(cuenta.Tipo_Persona);
                        $('#e_rfc').val(cuenta.RFC);
                        $('#e_tipo_cuenta').val(cuenta.tipo_cuenta);
                        $('#e_banco').val(cuenta.Banco);
                        $('#e_cuenta').val(cuenta.Cuenta);
                        $('#e_abba').val(cuenta.SWT_ABBA);
                        $('#e_clabe').val(cuenta.Clabe);
                        $('#e_banco_inter').val(cuenta.Banco_Intermediario);
                        $('#e_domicilio').val(cuenta.Domicilio_Completo);
                    }else{
                        console.log(response.message);
                    }
            }else {
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