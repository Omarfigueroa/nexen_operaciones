document.addEventListener("DOMContentLoaded", function(event) {
    obtener_razon();
    obtener_bancos();
});

var rCuentas=$('#registro_cuentas').DataTable();
var rProveedores=$('#registros_proveedor').DataTable();

var modalListadoCuentas = document.getElementById('listadoCuentas');
var modalFormProveedores = document.getElementById('modalFormProveedores');

var cbancos;
var ultimoEditarBanco;

function obtener_razon(){
    //$('#registros_archivos').clear()
    rProveedores.destroy();

    rProveedores=$('#registros_proveedor').DataTable( {
        bDestroy:true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        ajax:{
            url: "../include/obtener_proveedor1.php", 
            dataSrc:"" 
        },
        columns:[
            {data:"id_razon"},
            {data:"Razon_Social"},
            {data:"CURP"},
            {data:"RFC"},
            {data:"Tipo_Persona"},
            {data:"Tipo_Servicio"},
            {data:"Cuentas"},
            {data:"Proveedores"},
            {data:"Documentos"},
        ],
        columnDefs: [
            {
                targets: "_all",
                sortable: false
            },
            {
                targets: 6,
                render: function(data, type, row) {
                
                    var idRazon = row.id_razon; // Obtener el valor del Id_Comprobante de la fila
                    var razonSocial = row.Razon_Social; // Obtener el valor del Id_Comprobante de la fila
                    
                    var botonCuentas='<button type="button" class="btn btn-info btn-sm" onclick="listadoCuentas('+idRazon+',\''+razonSocial+'\')"><i class="bi bi-eye"></i> Cuentas</button>';
                    return botonCuentas;
                                        
                }

            },
            {
                targets: 7,
                render: function(data, type, row) {
                
                    var idRazon = row.id_razon; // Obtener el valor del Id_Comprobante de la fila
                    var razonSocial = row.Razon_Social; // Obtener el valor del Id_Comprobante de la fila
                    
                    //var botonProveedor='<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalFormProveedores" onclick="formProveedores('+idRazon+',\''+razonSocial+'\')"><i class="bi bi-eye"></i> Proveedor</button>';
                    var botonProveedor='<button type="button" class="btn btn-warning btn-sm" onclick="formProveedores('+idRazon+',\''+razonSocial+'\')"><i class="bi bi-eye"></i> Proveedor</button>';
                    return botonProveedor;
                                        
                }

            },
            {
                targets: 8,
                render: function(data, type, row) {
                
                    var idRazon = row.id_razon; // Obtener el valor del Id_Comprobante de la fila
                    
                    //var botonProveedor='<button type="button" class="btn btn-primary btn-sm" onclick="formProveedores('+idRazon+')"><i class="bi bi-eye"></i> Documentos</button>';
                    //var botonProveedor='<button data-id-cuenta="'+idRazon+'" type="button" class="btn btn-primary btn-documents">Documentos</button>';
                    var botonProveedor='<button type="button" class="btn btn-primary btn-sm" onclick="archivosProveedores('+idRazon+')">Documentos</button>';
                    return botonProveedor;
                                        
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

function obtener_bancos(){

    fetch("../include/obtener_bancos1.php", {
        method: "POST"
    }).then (data => data.json()
    ).then (data => {
        cbancos=data.datos;
    }).catch(function(error) {
        console.error("Error en la solicitud:", error);
    });

}

function datosCuentas(idRazon) {
    //$('#registros_archivos').clear()
    //$('#registro_cuentas').DataTable().destroy();
    
    rCuentas.destroy();
    
    rCuentas=$('#registro_cuentas').DataTable( {

        bDestroy:true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        ajax:{
            url: "../include/obtener_datos_cuentas1.php?idRazon="+idRazon, 
            dataSrc:"" 
        },
        columns:[
            {data:"id_Movimiento"},
            {data:"NOMBRE_BANCO"},
            {data:"Cuenta"},
            {data:"Clabe"},
            {data:"SWT_ABBA"},
            {data:"Banco_Intermediario"},
            {data:"Modificar"}
        ],
        columnDefs: [
            {
                targets: 0,
                visible: false
            },
            {
                targets: 1,
                render: function(data, type, row) {

                    var selectB = document.getElementById("selectGenerico"); //Referencia a elemento select generico
                    var selectBanco = selectB.cloneNode(true); //En base al generico se clona para hacer copia

                    selectBanco.setAttribute('id', 'banco'+row.id_Movimiento); //cambia el id (se le agrega id dinamico) del elemento clonado
                    selectBanco.setAttribute('name', 'banco'+row.id_Movimiento); //cambia el nombre (se le agrega nombre dinamico) del elemento clonado

                    /* Inicio ciclo foreach para agregar el catalogo de bancos al select clonado */
                    cbancos.forEach(function(cbanco) {
                        /* Inicia If para seleccionar un elemento del select (selected si es igual a celda) */
                        if(cbanco.NOMBRE_BANCO==row.NOMBRE_BANCO){
                            var option = new Option(cbanco.NOMBRE_BANCO,cbanco.ID_BANCO,true,true);
                            selectBanco.appendChild(option);
                        }else{
                            var option = new Option(cbanco.NOMBRE_BANCO,cbanco.ID_BANCO);
                            selectBanco.appendChild(option);
                        }
                        /* Termina If para seleccionar un elemento del select (selected si es igual a celda) */
                    });
                    /* Fin ciclo foreach para agregar el catalogo de bancos al select clonado */

                    //Crea elemento <p> para poder ocultar contenido de celda datatable agregamos un id dinamico
                    var cBanco='<p id="pbanco'+row.id_Movimiento+'">'+row.NOMBRE_BANCO+'</p>'; 

                    //Retornamos el valor de <p> banco 
                    return cBanco+selectBanco.outerHTML;
                }
            },
            {
                targets: 2,
                render: function(data, type, row) {
                    var cCuenta='<p id="pcuenta'+row.id_Movimiento+'">'+row.Cuenta+'</p>';
                    var inputCuenta='<textarea class="align-text-bottom form-control d-none" id="cuenta'+row.id_Movimiento+'"  name="cuenta'+row.id_Movimiento+'" rows="1">'+row.Cuenta+'</textarea>';
                    return cCuenta+inputCuenta;
                }
            },
            {
                targets: 3,
                render: function(data, type, row) {
                    var cClabe='<p id="pclabe'+row.id_Movimiento+'">'+row.Clabe+'</p>';
                    var inputClabe='<textarea class="align-text-bottom form-control d-none" id="clabe'+row.id_Movimiento+'"  name="cuenta'+row.id_Movimiento+'" rows="1">'+row.Clabe+'</textarea>';
                    return cClabe+inputClabe;
                }
            },
            {
                targets: 4,
                render: function(data, type, row) {
                    var cSwt='<p id="pswt'+row.id_Movimiento+'">'+row.SWT_ABBA+'</p>';
                    var inputSwt='<textarea class="align-text-bottom form-control d-none" id="swt'+row.id_Movimiento+'"  name="swt'+row.id_Movimiento+'" rows="1">'+row.SWT_ABBA+'</textarea>';
                    return cSwt+inputSwt;
                }
            },
            {
                targets: 5,
                render: function(data, type, row) {
                    var cIntermediario='<p id="pintermediario'+row.id_Movimiento+'">'+row.Banco_Intermediario+'</p>';
                    var inputIntermediario='<textarea class="align-text-bottom form-control d-none" id="intermediario'+row.id_Movimiento+'"  name="intermediario'+row.id_Movimiento+'" rows="1">'+row.Banco_Intermediario+'</textarea>';
                    return cIntermediario+inputIntermediario;
                }
            },
            {
                targets: 6,
                render: function(data, type, row) {
                    var botonEditar='<button type="button" id="editar'+row.id_Movimiento+'" value="'+row.id_Movimiento+'" class="btn btn-info btn-sm" onclick="mostrarBotonesEditar(this)"><i class="bi bi-pencil"></i></button>';
                    var botonGuardar='<button type="button" id="guardar'+row.id_Movimiento+'" value="'+row.id_Movimiento+'" class="btn btn-success btn-sm d-none" onclick="editarCuentas(this)"><i class="bi bi-save"></i></button>';
                    var botoneCancelar='<button type="button" id="cancelar'+row.id_Movimiento+'" value="'+row.id_Movimiento+'" class="btn btn-danger btn-sm d-none" onclick="mostrarBoton(this)"><i class="bi bi-x-circle"></i></button>';
                    return botonEditar+botonGuardar+botoneCancelar;
                }
            }
        ],
        dom: 'Bfrtip',
        order: [[ 0, false ]]
    } );  
}

//var proveedorTable;
const formulariom=document.querySelector('#guardar_proveedor');

formulariom.addEventListener('submit', (e)=>{
    
    var sig=true;
    e.preventDefault();

    if (!formulariom.checkValidity()) {
        e.stopPropagation();
        sig=false;
    }

    formulariom.classList.add('was-validated');

    if(sig==false){return;}

    //const spin = document.getElementById('contenedor_carga');
    const datos = new FormData(document.getElementById('guardar_proveedor'));
    //spin.style.visibility= 'visible';

    const btnguardar = document.getElementById('boton_guardar');
    btnguardar.disabled = true; 

    fetch("../include/guardar_proveedores1.php", {
        method: "POST",
        body: datos
    }).then (data => data.json()
     ).then (data => {

          var proveedorForm = bootstrap.Modal.getInstance(modalFormProveedores); 

        if(data.success==true){
            formulariom.classList.remove('was-validated'); //habilita validar campos
            formulariom.reset(); //limpiar formulario
        }

        //spin.style.visibility= 'hidden';
        
        btnguardar.disabled = false; 

        if(data.success==false){
            iconv='error';
            titlev='Error';
        }else{
            iconv='success';
            titlev='Exito';
        }
       

        Swal.fire({
            icon: iconv,
            title: titlev,
            text: data.message
        }).then(function () {

            proveedorForm.hide();
            rProveedores.ajax.reload();         
        });

    }).catch(function(error) {
        console.error("Error en la solicitud:", error);
    });
    
});

function formProveedores(idRazon,razonSocial){
    document.getElementById('id_razon').value=idRazon;
    console.log(idRazon);

    var modalProveedores = new bootstrap.Modal(modalFormProveedores);
    modalProveedores.show();

    obtenerDatosProveedor(idRazon);

    var modalTitle = modalFormProveedores.querySelector('.modal-title');
    modalTitle.textContent = 'MODIFICACION DE PROVEEDOR: '+razonSocial;
}


function listadoCuentas(idRazon,razonSocial){
    // Actualizar el contenido del modal.

    var modalCuentas = new bootstrap.Modal(modalListadoCuentas);
    modalCuentas.show();

    var modalTitle = modalListadoCuentas.querySelector('.modal-title');
    modalTitle.textContent = 'CUENTAS DE PROVEEDOR '+razonSocial;

    datosCuentas(idRazon);
}


function obtenerDatosProveedor(idRazon) {
    var datos = {
        idRazon: idRazon
    };

    fetch("../include/obtener_datos_proveedor1.php", {
        method: "POST",
        body: JSON.stringify(datos)
    }).then (data => data.json()
    ).then (data => {

        document.getElementById('razon_social').value=data.datos[0].Razon_Social;
        document.getElementById('alias').value=data.datos[0].Alias;
        document.getElementById('tipo_persona').value=data.datos[0].Tipo_Persona;
        document.getElementById('tipo_servicio').value=data.datos[0].Tipo_Servicio;
        document.getElementById('curp').value=data.datos[0].CURP;
        document.getElementById('rfc').value=data.datos[0].RFC;

    }).catch(function(error) {
        console.error("Error en la solicitud:", error);
    });

}


function mostrarBotonesEditar(ident){

    if((ultimoEditarBanco!=undefined || ultimoEditarBanco!=null) && document.getElementById(ultimoEditarBanco.id) != null){
        mostrarBoton(ultimoEditarBanco);
    }

    var botonesGuardar = document.querySelector('#guardar'+ident.value);
    var botonesCancelar = document.querySelector('#cancelar'+ident.value);
    
    var inputBanco = document.querySelector('#banco'+ident.value);
    var pBanco=document.querySelector('#pbanco'+ident.value);
    var inputCuenta = document.querySelector('#cuenta'+ident.value);
    var pCuenta=document.querySelector('#pcuenta'+ident.value);
    var inputClabe = document.querySelector('#clabe'+ident.value);
    var pClabe=document.querySelector('#pclabe'+ident.value);
    var inputSwt = document.querySelector('#swt'+ident.value);
    var pSwt=document.querySelector('#pswt'+ident.value);
    var inputIntermediario = document.querySelector('#intermediario'+ident.value);
    var pIntermediario=document.querySelector('#pintermediario'+ident.value);

    
    ident.classList.add('d-none');
    botonesGuardar.classList.remove('d-none');
    botonesCancelar.classList.remove('d-none');

    inputBanco.classList.remove('d-none');
    pBanco.classList.add('d-none');
    inputCuenta.classList.remove('d-none');
    pCuenta.classList.add('d-none');
    inputClabe.classList.remove('d-none');
    pClabe.classList.add('d-none');
    inputSwt.classList.remove('d-none');
    pSwt.classList.add('d-none');
    inputIntermediario.classList.remove('d-none');
    pIntermediario.classList.add('d-none');

    ultimoEditarBanco=ident;

}


function mostrarBoton(ident){

/* Localiza los identificadores en el datatable */
    var botonesEditar = document.querySelector('#editar'+ident.value);
    var botonesGuardar = document.querySelector('#guardar'+ident.value);
    var botonesCancelar = document.querySelector('#cancelar'+ident.value);

    var inputBanco = document.querySelector('#banco'+ident.value);
    var pBanco=document.querySelector('#pbanco'+ident.value);
    var inputCuenta = document.querySelector('#cuenta'+ident.value);
    var pCuenta=document.querySelector('#pcuenta'+ident.value);
    var inputClabe = document.querySelector('#clabe'+ident.value);
    var pClabe=document.querySelector('#pclabe'+ident.value);
    var inputSwt = document.querySelector('#swt'+ident.value);
    var pSwt=document.querySelector('#pswt'+ident.value);
    var inputIntermediario = document.querySelector('#intermediario'+ident.value);
    var pIntermediario=document.querySelector('#pintermediario'+ident.value);
/* Localiza los identificadores en el datatable */

    botonesCancelar.classList.add('d-none');
    botonesGuardar.classList.add('d-none');
    botonesEditar.classList.remove('d-none');

    inputBanco.classList.add('d-none');
    pBanco.classList.remove('d-none');
    inputCuenta.classList.add('d-none');
    pCuenta.classList.remove('d-none');
    inputClabe.classList.add('d-none');
    pClabe.classList.remove('d-none');
    inputSwt.classList.add('d-none');
    pSwt.classList.remove('d-none');
    inputIntermediario.classList.add('d-none');
    pIntermediario.classList.remove('d-none');
}

function editarCuentas(ident){

    var idMovimiento=ident.value;
    var Banco=document.getElementById('banco'+idMovimiento).value;
    var Cuenta=document.getElementById('cuenta'+idMovimiento).value;
    var Clabe=document.getElementById('clabe'+idMovimiento).value;
    var Swt=document.getElementById('swt'+idMovimiento).value;
    var Intermediario=document.getElementById('intermediario'+idMovimiento).value;

    var datos = {
        idMovimiento: idMovimiento,
        Banco: Banco,
        Cuenta: Cuenta,
        Clabe: Clabe,
        Swt: Swt,
        Intermediario: Intermediario
    };

    fetch("../include/editar_cuentas1.php", {
        method: "POST",
        body: JSON.stringify(datos)
    }).then (data => data.json()
    ).then (data => {
        rCuentas.ajax.reload();
        if(data.success==false){
            iconv='error';
            titlev='Error';
        }else{
            iconv='success';
            titlev='Exito';
        }

        Swal.fire({
            icon: iconv,
            title: titlev,
            text: data.message
        });
        
    }).catch(function(error) {
        console.error("Error en la solicitud:", error);
    });

}
