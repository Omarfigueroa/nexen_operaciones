let tableHistorial;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){

    tableHistorial = $('#tableHistorial').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": "../include/getHistorial.php",
            "dataSrc":""
        },
        "columns":[
            {"data":"Usuario"},
            {"data":"NUM_OPERACION"},
            {"data":"Referencia_Cliente"},
            {"data":"Cliente"},
            {"data":"BL"},
            {"data":"Contenedor_1"},
            {"data":"Pto_LLegada"},
            {"data":"Fecha_Arribo"},
            {"data":"Fecha_Notificacion"},
            {"data":"Fecha_Pago_Anticipo"},
            {"data":"Fecha_Modulacion"},
            {"data":"No_Pedimento"},
            {"data":"Importador_Exportador"},
            {"data":"Clave_Pedimento"},
            {"data":"No_Factura"},
            {"data":"Valor_Factura"},
            {"data":"Descripcion_Cove"},
            {"data":"Factura_Anexo24"},
            {"data":"tipo_cambio"},
            {"data":"Fecha_Factura24"},
            {"data":"WMS"},
            {"data":"Estatus"},
            {"data":"HORA_OPE"},
            {"data":"FECHOPE"},
            {"data":"Patente"},
            {"data":"Moneda"},
            {"data":"DENOMINACION_ADUANA"},
            {"data":"Guia_House"},
            {"data":"Tipo_Operacion"},
            {"data":"proveedor"},
            {"data":"BULTOS"},
            {"data":"peso_bruto"},
            {"data":"tipo_trafico"},
            {"data":"GUIA_HOUSE1"},
            {"data":"valida_fecha"},
            {"data":"fecha_factura"},
            {"data":"FACTURA_SALIDA_ANEXO24"},
            {"data":"NUM_SALIDA_WMS"},
            {"data":"NUM_RECTIFICACION"},
            {"data":"FECHA_LIBERACION"},
            {"data":"OBSERVACIONES"},
            {"data":"ID_CLIENTE"},
            {"data":"NUMERO_ECONOMICO"},
            {"data":"REFERENCIA_NEXEN"},
            {"data":"Contenedor_2"},
            {"data":"Tipo_OPE"}

            


        ],
        'dom': 'lBfrtip',
        'buttons': [
           {
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr":"Esportar a Excel",
                "className": "btn btn-success"
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Esportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });
}, false);

