const selectCliente = document.querySelector('#filtro_cliente');
const selectOperador = document.querySelector('#filtro_operador');

document.addEventListener("DOMContentLoaded", function(event) {
    cat_clientes();
    cat_operadores();
    obtener_pagos('','');
});

function obtener_pagos(idCliente,idOperador){
    //$('#registros_archivos').clear()
    $('#pago_proveedor').DataTable().destroy();

    $('#pago_proveedor').dataTable( {
        aProcessing:true,
        aServerSide:true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        ajax:{
            url: "../include/obtener_pago_proveedor.php", 
            dataSrc:"" 
        },
        columns:[
            {data:"NO_SOLICITUD"},
            {data:"CONCEPTO"},
            {data:"CLIENTE"},
            {data:"OPERADOR"},
            {data:"CUENTA_OPERADOR"},
            {data:"PROVEEDOR"},
            {data:"CUENTA_PROVEEDOR"},
            {data:"CLAVE_RASTREO"},
            {data:"MONTO_CARGO"},
            {data:"ACCIONES"}
        ],
        columnDefs: [
            {
                targets: -1,
                render: function(data, type, row) {
                    //console.log(row.Id_documento_Ruta);
                    var idArchivo = row.Id_documento_Ruta; // Obtener el valor del Id_Comprobante de la fila
                    var descargar='<a href="include/descargar_archivos.php?id='+idArchivo+'" class="btn btn-primary"><i class="bi bi-cloud-download"></i></a>';
                    return descargar;
                }
            }
        ],
        dom: 'Bfrtip',
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

function cat_clientes() {

    fetch("../include/obtener_clientes.php", {
        method: "POST"
    }).then (data => data.json()
    ).then (data => {
        obtenerCatClientes(data.datos)
        console.log(data.datos);
    }).catch(function(error) {
        console.error("Error en la solicitud:", error);
    });

}

function obtenerCatClientes(clientes) {

    clientes.forEach(function(cliente) {

        var option = new Option(cliente.RAZON_SOCIAL,cliente.Id_cliente);
        selectCliente.appendChild(option);

    });

}


function cat_operadores() {

    fetch("../include/obtener_operadores.php", {
        method: "POST"
    }).then (data => data.json()
    ).then (data => {
        obtenerCatOperadores(data.datos)
        console.log(data.datos);
    }).catch(function(error) {
        console.error("Error en la solicitud:", error);
    });

}

function obtenerCatOperadores(operadores) {

    operadores.forEach(function(operador) {

        var option = new Option(operador.Razon_Social,operador.ID_EMPRESA);
        selectOperador.appendChild(option);

    });

}

selectCliente.addEventListener('change', (event) => {
    //alert('Hola');
    var idCliente=event.target.value;
    var table = $('#pago_proveedor').DataTable();
    if(idCliente==''){ 
        selectCliente.options[0].selected="selected"; 
        table.search('').columns().search('').draw();
    }else{
        var display=selectCliente.options[selectCliente.selectedIndex].text;
        table.column(2).search(display, true, false).draw();
    }
});

selectOperador.addEventListener('change', (event) => {
    //alert('Hola');
    var idOperador=event.target.value;
    var table = $('#pago_proveedor').DataTable();
    if(idOperador==''){ 
        selectOperador.options[0].selected="selected"; 
        table.search('').columns().search('').draw();
    }else{
        var display=selectOperador.options[selectOperador.selectedIndex].text;
        table.column(3).search(display, true, false).draw();
    }
});