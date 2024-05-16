<?php
    if(!isset($_SESSION['usuario_nexen']))  
    {  
        session_start();
    }
    require '../conexion/bd.php'; 
    require '../utils/catalogos.php';  
    require '../include/validacion_usuarios.php';

// if($_SESSION['usuario_nexen'])  
// {  
//     $usuario = $_SESSION['usuario_nexen'];
//     //$usuario = "JOVIEDOR";
// 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar operacion</title>
    <link rel="stylesheet" href="../css/estilos.css">
        <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet"/>

</head>
<body class="d-flex flex-column h-100"> <!-- Obligatorio que la etiqueta body lleve esas Clases -->
    <?php require('../plantilla/menu.php'); ?>

    <!-- Begin page content -->
    <main>
        <br>
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="col"> -->
                    <table class="table my-3">
                        <tr>
                        <th colspan="9" class="text-center" ><h1>BUSCAR OPERACIONES</h1></th>
                        </tr>
                    </table>
                    <table id="filtrado" class="table my-3 border border-dark" bgcolor="#EAE8E8" style="font-size: 12px" >
                        <thead class="align-middle">
                            <tr class="border">
                                <th class="col-xs text-center text-break" style="width: 6.25%"># OPERACIÓN</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">REFERENCIA NEXEN</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">CLIENTE</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">BL</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">CONTENEDOR</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">PEDIMENTO</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">FECHA ARRIBO</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">FECHA NOTIFICACIÓN</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">FECHA MODULACIÓN</th> 
                                <th class="col-xs text-center text-break" style="width: 6.25%">FECHA PAGO</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">IMPO/EXPO</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">CVE PEDIMENTO</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">ESTATUS</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">DENOMINACIÓN ADUANA</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%">TIPO TRAFICO</th>
                                <th class="col-xs text-center text-break" style="width: 6.25%"># ECO</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle border">
                            <?php
                                foreach($result_operacion_nex as $mostrar){    
                            ?>
                                <tr>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['NUM_OPERACION'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['REFERENCIA_NEXEN'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Cliente'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['BL'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Contenedor_1'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['No_Pedimento'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Fecha_Arribo'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Fecha_Notificación'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Fecha_Modulación'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Fecha_Pago_Anticipo'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Importador_Exportador'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Clave_Pedimento'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['Estatus'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['DENOMINACION_ADUANA'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['tipo_trafico'] ?></td>
                                    <td class="col-xs text-center text-break"><?php echo $mostrar['NUMERO_ECONOMICO'] ?></td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>    
                    </table>
                <!-- </div> -->
            </div>

            <div class="row">
                <div class="col">
                    <a href="../include/excel.php" class="btn btn-success btn-lg" type="submit">
                        EXCEL <i class="bi bi-file-earmark-excel"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="../include/csv.php" class="btn btn-success btn-lg" type="submit">
                        CSV <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                            </svg>
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>  
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>

    <script>
        $(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#filtrado thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#filtrado thead');
 
    var table = $('#filtrado').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input class="cajas-filtros" type="text" placeholder="' + title + '" />');
                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
 
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();
 
                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
        },
    });
});


 

</script>

</body>
</html>

<?php
// } else{
//     echo'<script type="text/javascript">
//         alert("Se requiere iniciar sesion");
//     </script>';
// }