let tablefacturas;
function modalVerFacturas() {
    // Mostrar mensaje de carga
    $('#loadingMessage').show();
    var referencia_nexen = $('#referencia_nexen').val();
    tablefacturas = $('#tablefacturas').DataTable({
        aProcessing: true,
        aServerSide: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        ajax: {
            url: "../include/Facturas/cargarFacturasDetalles.php?referencia_nexen=" + referencia_nexen,
            dataSrc: ""
        },
        columns: [
            // { "data": "Id_TipoCambio" }, 
            { data: "Referencia_Nexen"},
            { data: "Proveedor"},
            { data: "Tax_Id"},
            { data: "Numero_Factura"},
            { data: "Fecha_Factura"},
            { data: "Importador_Exportador"},
            { data: "Total_General"},
            { data: "Usuario"},
            { data: "Detalles"},
            { data: "Invoice"},
            { data: "Packing_List"},
            { data: "Editar"},
            { data: "Eliminar"}

        ],
        bDestroy: true,
        iDisplayLength: 10,
        order: [[0, "desc"]],
        searching: true,
        scrollY: true,
        fixedHeader: true, // Fijar la cabecera de la tabla
        scrollX: true
    });

    // Ocultar mensaje de carga y mostrar tabla cuando la tabla se dibuje
    $('#tablefacturas').on('init.dt draw.dt', function () {
        $('#loadingMessage').hide();
    });

    $('#modalVerFacturas').modal('show');
}