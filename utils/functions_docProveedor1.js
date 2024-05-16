var docProveedores=$('#cuentas_proveedor').DataTable();
//Functions de cargar_proveedor_finanzas
$(document).ready(function() {
    $('#select_mes').change(function() {
        $('#OF_File').val('');
        $('#CIF_File').val('');
    });

    $("#fk_tipo_cuenta").change(function() {
        verificarTipoCuenta($(this).val());
        disabledTipoCuenta($(this).val());
    });

    //modal agregar banco a cuenta
    $("#bc_tipo_cuenta").change(function() {
        verificarTipoCuentabc($(this).val());
        disabledTipoCuentabc($(this).val());
    });

    //agregar valor del banco a un input hidden
    $('#bc_banco').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var idBanco = selectedOption.attr('idBanco');
        $('#idBancoBC').val(idBanco);
    });

    //poner buscadores en selects
    $("#fk_cuenta_destino").select2({
        language: "es",
        theme: 'bootstrap-5',
        placeholder: "Cuenta destino",
        allowClear: true
    });

    $("#fk_banco").select2({
        language: "es",
        theme: 'bootstrap-5',
        placeholder: "Banco",
        allowClear: true
    });

    $("#fk_tipo_cuenta").select2({
        language: "es",
        theme: 'bootstrap-5',
        placeholder: "Tipo de cuenta",
        allowClear: true    
    });

    $("#bc_banco").select2({
        language: "es",
        theme: 'bootstrap-5',
        placeholder: "Banco",
        allowClear: true
    });

    $("#bc_tipo_cuenta").select2({
        language: "es",
        theme: 'bootstrap-5',
        placeholder: "Tipo de cuenta",
        allowClear: true    
    });
    


    // Agregar la función onchange al select cuenta destino para asignar propiedades
    $("#fk_cuenta_destino").on("change", function() {
        // Obtener la opción seleccionada
        var selectedOption = $(this).find(":selected");

        // Obtener propiedades de la opción seleccionada
        var id_razon = selectedOption.attr("id_razon");
        var razonSocial = selectedOption.attr("razon_social");
        var refProveedor = selectedOption.attr("refProveedor");
        var curp = selectedOption.attr("curp");
        var tipoServicio = selectedOption.attr("tipo_servicio");
        var tipoPersona = selectedOption.attr("tipo_persona");
        var rfc = selectedOption.attr("rfc");

        $('#id_razonselect').val(id_razon);

        // Asignar propiedades a los inputs correspondientes
        $("#fk_razon_social").val(razonSocial);
        $("#fk_referencia_proveedor").val(refProveedor);
        $("#fk_curp").val(curp);
        $("#fk_tipo_servicio").val(tipoServicio);
        $("#fk_tipo_persona").val(tipoPersona);
        $("#fk_rfc").val(rfc);

        //para traer todos los datos y clavarlos
        // Antes de enviar la petición AJAX, muestra el modal de carga
        $('#loadingModal').modal('show');
        // Hacer la llamada AJAX
        $.ajax({
            type: "POST", // Método POST
            url: "../include/getInfoCuenta1.php", // URL a la que enviarás los datos
            data: {
                id_razon: id_razon // Datos que enviarás (incluyendo id_razon)
            },
            dataType: "json", // Tipo de dato que esperas recibir (JSON en este caso)
            success: function(response) {
                $('#loadingModal').modal('hide');
                if (response.success) {
                    // La operación fue exitosa, puedes procesar los datos recibidos
                    var datos = response.data[0]; // Obtener el primer objeto de los datos

                    // Cambiar el valor seleccionado del campo select2 fk_tipo_cuenta
                    $('#fk_tipo_cuenta').val(datos.Tipo_Cuenta).trigger('change');

                    // Cambiar el valor seleccionado del campo select2 fk_banco
                    $('#fk_banco option[idBanco="' + datos.Id_banco + '"]').prop('selected', true).trigger('change');

                    // Asignar los demás valores a los campos correspondientes
                    $('#fk_cuenta').val(datos.Cuenta);
                    $('#fk_abba').val(datos.SWT_ABBA);
                    $('#fk_clabe').val(datos.Clabe);
                    $('#fk_banco_inter').val(datos.Banco_Intermediario);
                    $('#fk_domicilio').val(datos.Domicilio_Completo);

                    
                    $('#fk_tipo_cuenta, #fk_cuenta, #fk_clabe, #fk_abba, #fk_banco, #fk_banco_inter, #fk_domicilio').prop('disabled', true);
                    

                    // Actualizar los checkboxes en el collapse
                    var checkboxHTML = response.checkboxHTML;
                    $('#operadorCollapse').html(checkboxHTML);

                    // Mostrar el contenido del collapse
                    $('#operadorCollapse').collapse('show');
                    //console.log(response.data);
                    $('#guardar_cuenta').attr('disabled', true);
                    // Luego, puedes realizar alguna acción con los datos recibidos
                    $('#actualizar_cuenta').removeClass('d-none');

                    $('#agregarBancoACuenta').removeClass('d-none');
                } else {
                    // La operación falló, muestra el mensaje de error
                    // Asignar valores vacíos a los campos correspondientes
                    $('#fk_tipo_cuenta').val('').trigger('change');
                    $('#fk_banco').val('').trigger('change');
                    $('#fk_cuenta').val('');
                    $('#fk_abba').val('');
                    $('#fk_clabe').val('');
                    $('#fk_banco_inter').val('');
                    $('#fk_domicilio').val('');

                    // Deshabilitar los campos
                    $('#fk_tipo_cuenta, #fk_banco, #fk_banco_inter, #fk_domicilio').prop('disabled', false);
                    //alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Ocurrió un error en la llamada AJAX
                alert("Error en la llamada AJAX: " + error);
            }
        });
   

    });

    //PARA EDITAR BANCOS
    
    // Función para manejar el cambio de banco
    function handleBancoChange(id) {
        idBanco = id; // Actualizar el valor de idBanco

        $('#idBancoInput').val(idBanco);
        //alert(idBanco);

        // Ahora que tenemos idBanco, llamamos a modalAgregarEditarBanco con los parámetros adecuados
        //modalAgregarEditarBanco();
    }

    // Escuchar el evento de cambio en el select de banco
    $('#fk_banco').on('change', function() {
        var selectedOption = $(this).find(':selected'); // Obtener la opción seleccionada
        var idBanco = selectedOption.attr('idBanco'); // Obtener el valor del atributo idBanco

        handleBancoChange(idBanco);
    });
    
/*
    $("#fk_banco").on("change", function() {
        // Obtener la opción seleccionada
        var selectedOption = $(this).find(":selected");

        // Obtener propiedades de la opción seleccionada
        var razonSocial = selectedOption.attr("razon_social");
        var refProveedor = selectedOption.attr("refProveedor");
        var curp = selectedOption.attr("curp");
        var tipoServicio = selectedOption.attr("tipo_servicio");
        var tipoPersona = selectedOption.attr("tipo_persona");
        var rfc = selectedOption.attr("rfc");

        // Asignar propiedades a los inputs correspondientes
        $("#fk_razon_social").val(razonSocial);
        $("#fk_referencia_proveedor").val(refProveedor);
        $("#fk_curp").val(curp);
        $("#fk_tipo_servicio").val(tipoServicio);
        $("#fk_tipo_persona").val(tipoPersona);
        $("#fk_rfc").val(rfc);
        
    });
    */
});

//ESTE ES PARA ACTUALIZAR TODA LA CUENTA CON OPERADOR NUEVO
function actualizarCuenta(){
        
        //PRIMERO VAMOS A COMPROBAR QUE EXISTE UN CHECKBOX QUE NO ES DISABLED
        var selectedCheckboxes = $('#operadorCollapse input[type="checkbox"]:checked:not(:disabled)');

        if (selectedCheckboxes.length > 0) {
            $('#actualizar_cuenta').prop('disabled', true);
            $('#spinner_actualizar_cuenta').removeClass('d-none');

            var selectedOperadores = [];
    
            $("input[name='operadores[]']:checked:not(:disabled)").each(function() {
                var idEmpresa = $(this).attr("id_empresa");
                selectedOperadores.push(idEmpresa);
            });

        var cuentaDestinoSelected = $("#fk_cuenta_destino option:selected");

        var datos = {
            fk_idRazon: cuentaDestinoSelected.attr("id_razon"),
            fk_razon_social: $("#fk_razon_social").val(),
            fk_referencia_proveedor: $("#fk_referencia_proveedor").val(),
            fk_curp: $("#fk_curp").val(),
            fk_tipo_servicio: $("#fk_tipo_servicio").val(),
            fk_tipo_persona: $("#fk_tipo_persona").val(),
            fk_rfc: $("#fk_rfc").val(),
            fk_tipo_cuenta: $("#fk_tipo_cuenta").val(),
            fk_banco: $("#fk_banco option:selected").attr("idBanco"),
            fk_cuenta: $("#fk_cuenta").val(),
            fk_abba: $("#fk_abba").val(),
            fk_clabe: $("#fk_clabe").val(),
            fk_banco_inter: $("#fk_banco_inter").val(),
            fk_domicilio: $('#fk_domicilio').val(),
            operadores: selectedOperadores,
            opcion: 'actualizarCuenta'
        };

        $.ajax({
            type: "POST",
            url: "../include/guardar_proveedor_finanzas1.php",
            data: datos,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert(response.message); // Mostrar mensaje de éxito
                    // Recargar la página después de que se acepte la alerta
                    window.location.reload();
                } else {
                    alert(response.message); // Mostrar mensaje de error
                }
                // Recargar la página después de que se acepte la alerta
                window.location.reload();
            },
            error: function(xhr, status, error) {
                // En caso de error en la llamada AJAX en sí
                console.log("Error en la llamada AJAX: " + error);
                console.log("Error en la llamada AJAX: " + status);
                console.log("Error en la llamada AJAX: " + xhr.responseText);
            }
        });
    } else {
        // Ningún checkbox seleccionado o todos los seleccionados están deshabilitados
        alert("Debes seleccionar al menos un nuevo operador para actualizar.");
    }
}

//Subir archivos
//Primero se comprueba que no exista ya un archivo correspodiente a ese mes y año.
function comprobarSiExisteSubir(btn_up) {

    var opcion = 'InsertarDocumento';
    var mes = $('#select_mes').val();
    var btn_doc = btn_up.id;
    var idProveedor = $('#idProveedor_modal').val();

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
    formData.append('idProveedor', idProveedor);
    formData.append('documento', $('#' + campoFile)[0].files[0]);



    $.ajax({
        url: '../include/procesar_archivoProveedor1.php',
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


function comprobarSiExisteBajar(btn_down,id_doc) {

    var opcion = 'DescargarDocumento';
    var mes = $('#select_mes').val();
    var btn_doc = btn_down.id;
    console.log(btn_doc);
    var idProveedor = $('#idProveedor_modal').val();

    /*
    if (!validarMes()) {
        // No se ha seleccionado un mes, detener la ejecución
        return;
    }
    */
   
    //activar el loading de cada boton
    //var spinner = $('#spinner_' + btn_doc);
    //spinner.removeClass('d-none');

    //desactivar boton para que no puedan presionarlo dos veces
    $('#'+btn_doc).prop('disabled', true);

    var formData = new FormData();
    formData.append('opcion', opcion);
    formData.append('mes', mes);
    formData.append('btn_doc', btn_doc);
    formData.append('idProveedor', idProveedor);
    formData.append('id_doc', id_doc);

    $.ajax({
        url: '../include/procesar_archivoProveedor1.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Procesar la respuesta exitosa
            //console.log(response);
            if(response.success){

                //location.reload();
                var link = document.createElement("a");
                link.href = "data:application/pdf;base64," + response.pdfData;
                link.download = response.nombre_archivo;
                link.target = "_blank";
                link.click();
                

                /*
                //var pdfData = response.pdfData;

                // Crear un enlace temporal para descargar el archivo
                var link = document.createElement('a');
                link.href = "data:application/pdf;base64," + pdfData;
                link.download = response.nombre_archivo; // Reemplaza "nombre_archivo.pdf" con el nombre deseado para el archivo
                link.target = "_blank";

                // Simular un clic en el enlace para iniciar la descarga
                link.click();
                */


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

function comprobarSiExisteBorrar(btn_delete,id_doc) {

    var opcion = 'BorrarDocumento';
    var mes = $('#select_mes').val();
    var btn_doc = btn_delete.id;
    var idProveedor = $('#idProveedor_modal').val();
    /*
    if (!validarMes()) {
        // No se ha seleccionado un mes, detener la ejecución
        return;
    }
    */
    var confirmacion = confirm('¿Estás seguro de que deseas borrar?\n' + 'Documento: ' + id_doc);
    if (confirmacion) {

    //activar el loading de cada boton
    //var spinner = $('#spinner_' + btn_doc);
    //spinner.removeClass('d-none');

    //desactivar boton para que no puedan presionarlo dos veces
    $('#'+btn_doc).prop('disabled', true);

    var formData = new FormData();
    formData.append('opcion', opcion);
    formData.append('mes', mes);
    formData.append('btn_doc', btn_doc);
    formData.append('idProveedor', idProveedor);
    formData.append('id_doc', id_doc);

    $.ajax({
        url: '../include/procesar_archivoProveedor1.php',
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
                url: '../include/crud_cta_proveedor1.php',
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
        url: '../include/crud_cta_proveedor1.php',
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
        url: '../include/crud_cta_proveedor1.php',
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


//SE CAMBIA EL SUBMIT PARA GUARDAR CUENTAS POR UN AJAX PARA MOSTRAR COMPROBACIONES---****NUEVA UPDATE
function guardarCuenta() {


    if (!validarCheckboxSeleccionado()) {
        return; // Detener la función si la validación no se cumple
    }

    if (!validarCamposLlenos()) {
        return; // Detener la función si la validación no se cumple
    }

    $('#guardar_cuenta').prop('disabled', true);
    $('#spinner_guardar_cuenta').removeClass('d-none');

    var selectedOperadores = [];
    $("input[name='operadores[]']:checked").each(function() {

        var idEmpresa = $(this).attr("id_empresa");
        selectedOperadores.push(idEmpresa);

        //selectedOperadores.push($(this).val());
    });

    var cuentaDestinoSelected = $("#fk_cuenta_destino option:selected");

    var datos = {
        fk_idRazon: cuentaDestinoSelected.attr("id_razon"),
        fk_razon_social: $("#fk_razon_social").val(),
        fk_referencia_proveedor: $("#fk_referencia_proveedor").val(),
        fk_curp: $("#fk_curp").val(),
        fk_tipo_servicio: $("#fk_tipo_servicio").val(),
        fk_tipo_persona: $("#fk_tipo_persona").val(),
        fk_rfc: $("#fk_rfc").val(),
        fk_tipo_cuenta: $("#fk_tipo_cuenta").val(),
        fk_banco: $("#fk_banco option:selected").attr("idBanco"),
        fk_cuenta: $("#fk_cuenta").val(),
        fk_abba: $("#fk_abba").val(),
        fk_clabe: $("#fk_clabe").val(),
        fk_banco_inter: $("#fk_banco_inter").val(),
        fk_domicilio: $('#fk_domicilio').val(),
        operadores: selectedOperadores,
        opcion: 'guardarCuenta'
    };

    $.ajax({
        type: "POST",
        url: "../include/guardar_proveedor_finanzas1.php",
        data: datos,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert(response.message); // Mostrar mensaje de éxito
                // Recargar la página después de que se acepte la alerta
                //window.location.reload();
            } else {
                alert(response.message); // Mostrar mensaje de error
            }
            // Recargar la página después de que se acepte la alerta
            window.location.reload();
        },
        error: function(xhr, status, error) {
            // En caso de error en la llamada AJAX en sí
            console.log("Error en la llamada AJAX: " + error);
            console.log("Error en la llamada AJAX: " + status);
            console.log("Error en la llamada AJAX: " + xhr.responseText);
        }
    });
}


function validarCheckboxSeleccionado() {
    var selectedOperadores = [];
    $("input[name='operadores[]']:checked").each(function() {
        selectedOperadores.push($(this).val());
    });

    if (selectedOperadores.length === 0) {
        alert("Debes seleccionar al menos un operador.");
        return false;
    }

    return true;
}


function validarCamposLlenos() {
    var campos = [
        $("#fk_cuenta_destino"),
        $("#fk_razon_social"),
        $("#fk_referencia_proveedor"),
        $("#fk_curp"),
        $("#fk_tipo_servicio"),
        $("#fk_tipo_persona"),
        $("#fk_rfc"),
        $("#fk_tipo_cuenta"),
        $("#fk_banco"),
        $("#fk_cuenta"),
        $("#fk_abba"),
        $("#fk_clabe"),
        $("#fk_banco_inter"),
        $('#fk_domicilio')
    ];

    var camposValidos = true;

    for (var i = 0; i < campos.length; i++) {
        var campo = campos[i];

        if (!campo.prop("disabled")) {
            if (campo.val().trim() === "") {
                campo.addClass("is-invalid");
                camposValidos = false;
            } else {
                campo.removeClass("is-invalid");
            }
        } else {
            campo.removeClass("is-invalid"); // Si está deshabilitado, quitar la clase is-invalid
        }
    }

    if (!camposValidos) {
        alert("Debes llenar todos los campos.");
    }

    return camposValidos;
}


function disabledTipoCuenta(value) {
    if(value == 'INTERNACIONAL'){
        $('#fk_cuenta').attr('disabled', true);
        $('#fk_clabe').attr('disabled', true);

        $('#fk_abba').attr('disabled', false);
      

    }else if(value == 'NACIONAL'){
        $('#fk_cuenta').attr('disabled', false);
        $('#fk_clabe').attr('disabled', false);

        $('#fk_abba').attr('disabled', true);
        
    }
    //alert(value);
}

//para agregar banco a cuenta
function disabledTipoCuentabc(value) {
    if(value == 'INTERNACIONAL'){
        $('#bc_cuenta').attr('disabled', true);
        $('#bc_clabe').attr('disabled', true);

        $('#bc_abba').attr('disabled', false);
      

    }else if(value == 'NACIONAL'){
        $('#bc_cuenta').attr('disabled', false);
        $('#bc_clabe').attr('disabled', false);

        $('#bc_abba').attr('disabled', true);
        
    }
    //alert(value);
}


//MODAL GUARDAR PROVEEDOR
function modalGuardarOperador(atributos){
    modalTitle = $(atributos).attr('modalTitle');

    
    $('#modalDinamicoTitle').text(modalTitle);

    // Definir el contenido inicial del modalBody
    var modalBody = '<div class="row">';

    // Agregar contenido HTML al modalBody usando +=
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="u_razon_social" name="u_razon_social" oninput="quitarComillas(this)"> \
                        <label for="">Razon Social</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" maxlength="13" id="u_rfc" name="u_rfc" oninput="quitarComillas(this)"> \
                        <label for="">RFC</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="u_dom_fiscal" name="u_dom_fiscal" oninput="quitarComillas(this)"> \
                        <label for="">Domicilio Fiscal</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="u_rep_legal" name="u_rep_legal" oninput="quitarComillas(this)"> \
                        <label for="">Representante Legal</label> \
                    </div> \
                </div>';

    // Cerrar la fila
    modalBody += '</div>';

    // Mostrar el modal con el contenido actualizado
    $('#modalDinamicoBody').html(modalBody);


    var modalDinamicoFooter = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
    modalDinamicoFooter += '<button id="btn_guardarOperador" type="button" class="btn btn-primary" onclick="guardarOperador()">';
    modalDinamicoFooter += 'Guardar';
    modalDinamicoFooter += '<span class="spinner-border spinner-border-sm d-none" id="spinner_guardarOperador" role="status" aria-hidden="true"></span>';
    modalDinamicoFooter += '</button>';
    

    // Mostrar el modal con el contenido actualizado
    $('#modalDinamicoFooter').html(modalDinamicoFooter);



    $('#modalDinamico').modal('show');
}

function guardarOperador() {
    var isValid = validateInputs('modalDinamicoBody');

    if (isValid) {
        var inputValues = getInputValues('modalDinamicoBody');
        inputValues.opcion = 'guardarOperador'; // Agregar la opción aquí
        $('#btn_guardarOperador').attr('disabled', true);
        $('#spinner_guardarOperador').removeClass('d-none');
        enviarDatos(inputValues); // Llama a la función para enviar los datos por AJAX
        console.log(inputValues);
    } else {
        // Los campos no son válidos, puedes mostrar un mensaje o tomar alguna acción
    }
}

function modalGuardarTipoCuenta(atributos){
    modalTitle = $(atributos).attr('modalTitle');

    
    $('#modalDinamicoTitle').text(modalTitle);

    // Definir el contenido inicial del modalBody
    var modalBody = '<div class="row">';

    // Agregar contenido HTML al modalBody usando +=
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="c_alias" name="c_alias" oninput="quitarComillas(this)"> \
                        <label for="">Alias</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="c_razon_social" name="c_razon_social" oninput="quitarComillas(this)"> \
                        <label for="">Razon Social</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="c_tipo_persona" name="c_tipo_persona" oninput="quitarComillas(this)"> \
                        <label for="">Tipo persona</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="c_tipo_servicio" name="c_tipo_servicio" oninput="quitarComillas(this)"> \
                        <label for="">Tipo servicio</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="c_curp" name="c_curp" oninput="quitarComillas(this)"> \
                        <label for="">CURP</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="c_rfc" name="c_rfc" oninput="quitarComillas(this)"> \
                        <label for="">RFC</label> \
                    </div> \
                </div>';
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                        <input type="text" class="align-text-bottom form-control" id="c_ref_proveedor" name="c_ref_proveedor" oninput="quitarComillas(this)"> \
                        <label for="">Referencia Proveedor</label> \
                    </div> \
                </div>';

    // Cerrar la fila
    modalBody += '</div>';

    // Mostrar el modal con el contenido actualizado
    $('#modalDinamicoBody').html(modalBody);


    var modalDinamicoFooter = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
    modalDinamicoFooter += '<a id="btn_guardarTipoCuenta" type="button" class="btn btn-primary" onclick="guardarTipoCuenta()">';
    modalDinamicoFooter += 'Guardar';
    modalDinamicoFooter += '<span class="spinner-border spinner-border-sm d-none" id="spinner_guardarTipoCuenta" role="status" aria-hidden="true"></span>';
    modalDinamicoFooter += '</a>';
    

    // Mostrar el modal con el contenido actualizado
    $('#modalDinamicoFooter').html(modalDinamicoFooter);



    $('#modalDinamico').modal('show');
}

function guardarTipoCuenta() {
    var isValid = validateInputs('modalDinamicoBody');

    if (isValid) {
        var inputValues = getInputValues('modalDinamicoBody');
        inputValues.opcion = 'guardarTipoCuenta'; // Agregar la opción aquí
        $('#btn_guardarTipoCuenta').attr('disabled', true);
        $('#spinner_guardarTipoCuenta').removeClass('d-none');
        enviarDatos(inputValues); // Llama a la función para enviar los datos por AJAX
        console.log(inputValues);
    } else {
        // Los campos no son válidos, puedes mostrar un mensaje o tomar alguna acción
    }
}


//EDITAR O INSERTAR MODAL
function modalAgregarEditarBanco(atributos){
    modalTitle = $(atributos).attr('modalTitle');

    selectBanco = $('#fk_banco').val();


    if (selectBanco == '') {
        tipoOperacion = 'Insertar';
    } else {
        tipoOperacion = 'Update';
        // Obtener el valor actualizado de idBanco
        idBanco = $('#idBancoInput').val();
    }

    //console.log(idBanco);

    
    $('#modalDinamicoTitle').text(modalTitle);

    // Definir el contenido inicial del modalBody
    var modalBody = '<div class="row">';

    // Agregar contenido HTML al modalBody usando +=
    modalBody += '<div class="col-12"> \
                    <div class="form-floating  mb-3"> \
                    <input class="d-none" id="idBanco" value="'+idBanco+'"></input> \
                        <input type="text" class="align-text-bottom form-control" id="e_banco" name="e_banco" oninput="quitarComillas(this)" value="'+selectBanco+'"> \
                        <label for="">Banco</label> \
                    </div> \
                </div>';
    modalBody += '<div class="text-center"> \
                    <div class="form-check form-switch"> \ \
                        <input class="form-check-input" type="checkbox" role="switch" id="e_estatus" checked> \
                        <label class="form-check-label" for="e_estatus">Estatus (Activo por defecto)</label> \
                    </div> \
                  </div>';
   
    // Cerrar la fila
    modalBody += '</div>';

    // Mostrar el modal con el contenido actualizado
    $('#modalDinamicoBody').html(modalBody);


    var modalDinamicoFooter = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
    modalDinamicoFooter += '<a id="btn_guardarBanco" type="button" class="btn btn-primary" onclick="guardarBanco(\''+tipoOperacion+'\')">';
    modalDinamicoFooter += tipoOperacion;
    modalDinamicoFooter += '<span class="spinner-border spinner-border-sm d-none" id="spinner_guardarBanco" role="status" aria-hidden="true"></span>';
    modalDinamicoFooter += '</a>';
    

    // Mostrar el modal con el contenido actualizado
    $('#modalDinamicoFooter').html(modalDinamicoFooter);



    $('#modalDinamico').modal('show');
}

function guardarBanco(tipoOperacion) {
    var isValid = validateInputs('modalDinamicoBody');

    if (isValid) {
        if(tipoOperacion == 'Update'){
            var inputValues = getInputValues('modalDinamicoBody');
            inputValues.opcion = 'guardarBanco'; // Agregar la opción aquí
            inputValues.Operacion = 'Update';
            $('#btn_guardarBanco').attr('disabled', true);
            $('#spinner_guardarBanco').removeClass('d-none');
            enviarDatos(inputValues); // Llama a la función para enviar los datos por AJAX
            console.log(inputValues);
        }else if(tipoOperacion == 'Insertar'){
            var inputValues = getInputValues('modalDinamicoBody');
            inputValues.opcion = 'guardarBanco'; // Agregar la opción aquí
            inputValues.Operacion = 'Insertar';
            $('#btn_guardarBanco').attr('disabled', true);
            $('#spinner_guardarBanco').removeClass('d-none');
            enviarDatos(inputValues); // Llama a la función para enviar los datos por AJAX
            console.log(inputValues);
        }
        
    } else {
        // Los campos no son válidos, puedes mostrar un mensaje o tomar alguna acción
    }
}

//MODAL AGREGAR BANCO A CUENTAS
function ModalagregarBancoACuenta(atributos){
    $('#modalBancoACuenta').modal('show');
}
//mandar datos de banco a cuenta
function agregarBancoACuenta(){

    var requiredFields = ['bc_tipo_cuenta', 'bc_banco', 'bc_cuenta', 'bc_clabe', 'bc_domicilio'];
    var hasEmptyFields = false;

    requiredFields.forEach(function(fieldId) {
        if (!$('#' + fieldId).prop('disabled') && !$('#' + fieldId).val()) {
            $('#' + fieldId).addClass('is-invalid');
            hasEmptyFields = true;
        } else {
            $('#' + fieldId).removeClass('is-invalid');
        }
    });

    if (hasEmptyFields) {
        return; // Detener la ejecución si hay campos vacíos
    }

    idBanco = $('#idBancoBC').val();

    idRazon = $('#id_razonselect').val();

    console.log(idRazon);


    var datos = {
        bc_idBanco: idBanco,
        bc_idRazon: idRazon,
        bc_tipo_cuenta:  $('#bc_tipo_cuenta').val(),
        bc_banco : $('#bc_banco').val(),
        bc_cuenta:  $('#bc_cuenta').val(),
        bc_abba:  $('#bc_abba').val(),
        bc_clabe:  $('#bc_clabe').val(),
        bc_banco_inter:  $('#bc_banco_inter').val(),
        bc_domicilio: $('#bc_domicilio').val(),
        opcion: 'guardarBancoACuenta'
    };

    $('#spinner_bancoacuenta').removeClass('d-none');
    $('#btnAgregarBancoACuenta').attr('disabled', true);

    $.ajax({
        type: "POST",
        url: "../include/guardar_proveedor_finanzas1.php",
        data: datos,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert(response.message); // Mostrar mensaje de éxito
                // Recargar la página después de que se acepte la alerta
                //window.location.reload();
            } else {
                alert(response.message); // Mostrar mensaje de error
            }
            // Recargar la página después de que se acepte la alerta
            window.location.reload();
        },
        error: function(xhr, status, error) {
            // En caso de error en la llamada AJAX en sí
            console.log("Error en la llamada AJAX: " + error);
            console.log("Error en la llamada AJAX: " + status);
            console.log("Error en la llamada AJAX: " + xhr.responseText);
        }
    });
    
}


function validateInputs(containerId) {
    var container = $('#' + containerId);
    var inputs = container.find('input');
    
    var isValid = true;
    
    inputs.each(function() {
        var input = $(this);
        if (input.val().trim() === '') {
            input.addClass('is-invalid');
            isValid = false;
        } else {
            input.removeClass('is-invalid');
        }
    });
    
    return isValid;
}

function getInputValues(containerId) {
    var container = $('#' + containerId);
    var inputValues = {};
    
    container.find('input').each(function() {
        var input = $(this);
        var inputId = input.attr('id');
        var inputValue = input.val().trim();
        
        inputValues[inputId] = inputValue;
    });
    
    return inputValues;
}


function enviarDatos(inputValues) {
    
    $.ajax({
        type: 'POST',
        url: '../include/guardar_proveedor_finanzas1.php', // Reemplaza con la URL del servidor
        data: inputValues,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Éxito: ' + response.message);
                // Realiza acciones adicionales si es necesario
                location.reload(); // Recargar la página
            } else {
                alert('Error: ' + response.message);
                $('#btn_guardarOperador').attr('disabled', false);
                $('#spinner_guardarOperador').addClass('d-none');
            }
        },
        error: function(xhr, status, error) {
            alert('Error en la llamada AJAX: ' + error);
            // Maneja el error si es necesario
        }
    });
}

function archivosProveedores(idProveedor){

    // Obtener el valor del atributo de datos 'data-id-cuenta'
    //var idCuenta = this.getAttribute("data-id-cuenta");

    console.log(idProveedor);
    // Asignar valor a un input hidden
    $('#idProveedor_modal').val(idProveedor);
    var documentModal = new bootstrap.Modal(document.getElementById('documentModal'));
    documentModal.show();

    documentosProveedor(idProveedor);


}


function documentosProveedor(idProveedor){
    
    docProveedores.destroy();

    docProveedores=$('#cuentas_proveedor').DataTable( {
        bDestroy:true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        ajax:{
            url: "../include/obtener_documentos_proveedor1.php?idProveedor="+idProveedor, 
            dataSrc:"" 
        },
        columns:[
            {data:"id_doc"},
            {data:"Nombre_Archivo"},
            {data:"Mes"},
            {data:"Anio"},
            {data:"Tipo_Documento"},
            {data:"Acciones"}
        ],
        columnDefs: [
            {
                targets: 0,
                visible: false
            },
            {
                targets: 5,
                render: function(data, type, row) {
                    var id_doc = row.id_doc;
                    var botonDescargar='<button class="btn btn-primary" type="button" id="CIF_Down'+id_doc+'" onclick="comprobarSiExisteBajar(this,'+id_doc+')"><span class="bi bi-arrow-down"></span></button>';
                    var botonBorrar='<button class="btn btn-danger" type="button" id="CIF_Delete'+id_doc+'" onclick="comprobarSiExisteBorrar(this,'+id_doc+')"><span class="bi bi-trash"></span></button>';
                    //var botonCuentas='<button type="button" class="btn btn-info btn-sm" onclick="listadoCuentas('+idProveedor+')"><i class="bi bi-eye"></i> Cuentas</button>';
                    return botonDescargar+botonBorrar;
                                        
                }

            }
        ],
        dom: 'Bfrtip',
        order: [[ 0, false ]],
        buttons: [
            {
                text: 'Descargar Excel',
                className: 'btn btn-success btn-open-modal',
                action: function (e, dt, node, config) {
                    location.href ='../include/descargar_manifiesto.php?id='+getid;
                }
            }
        ]
    } );  

}