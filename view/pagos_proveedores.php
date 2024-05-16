<?php 
/*
 if(!isset($_SESSION['usuario_nexen'])) 
 { 
     session_start();

     if(!isset($_SESSION['usuario_nexen'])){
         header('Location: login.php');
     }
     
 }

require '../utils/catalogos.php'; 
require '../utils/utils.php';


if (isset($_GET['referencia']) && !empty($_GET['referencia'])) {
    $ref=$_GET['referencia'];
}else{
    $ref="";
}
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones</title>
    <link href="../css/estilos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
    <script src="../utils/functions.js"></script>
    <style>
    .check {
        width: 20px;
        height: 20px;
    }

    .titulos {
        height: 37px;
        font: 20px;
    }
    </style>
</head>

<body>
    <?php /* include('../plantilla/menu.php'); */?>
    <br><br><br>

        <h1>Pagos Proveedores</h1>

        <div class="container py-3 my-5 rounded  border shadow" id="fathercontainerPagos">
            <div class="cuentas_origen border rounded-top">
                <div class="headerPagosOrginen bg-secondary bg-gradient rounded-top text-white">
                    <div class="row px-2 py-2">
                        <div class="col-4">
                            <span id="referecna_pago_cliente" class=""  >{Referencia pago Cliente}</span>
                            <br><br>
                            <div class="row d-flex justify-content-between">
                                <div class="col">
                                    <input type="date" disabled name="fechope_operacion" id="fechope_operacion py-2" class="form-control" value="2023-01-05">
                                </div>
                                <div class="col">
                                    <input type="text" disabled name="horaope_operacion" id="horaope_operacion py-2" class="form-control" value="15:50:16">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <span><strong>PAGOS PROVEEDORES</strong></span>
                            <br>
                            <span><strong>ORIGEN</strong></span>
                        </div>
                        <div class="col-4 text-end text">
                            <span>
                                {Referencia_nexen}
                            </span>
                            <br>
                            <span>
                                {Usuario}
                            </span>
                        </div>
                    </div>
                </div>  
                <div class="cuerpoOrigen py-2">
                    <div class="row px-2">
                        <div class="col-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="operador_pagos">
                                <label for="operador_pagos">OPERADOR</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="rfc_operador_pagos">
                                <label for="rfc_operador_pagos">RFC OPERADOR</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="banco_emisor_pagos">
                                <label for="banco_emisor_pagos">BANCO EMISOR</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tipo_movimiento_pagos">
                                <label for="tipo_movimiento_pagos">TIPO MOVIMIENTO</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cuenta_emisora_pagos">
                            <label for="cuenta_emisora_pagos">CUENTA EMISORA</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cuentas_destino border rounded-top mt-3">
                <div class="text-center text-white w-100 rounded-top bg-secondary bg-gradient py-3 ">
                    <strong>Cuenta Destino</strong>
                </div>
                <div class="row pt-2 px-2">
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="proveedor_pagos">
                            <label for="proveedor_pagos">PROVEEDOR</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="banco_receptor">
                            <label for="banco_receptor">BANCO RECEPTOR</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cuenta_receptora_destino">
                            <label for="cuenta_receptora_destino">CUENTA RECEPTORA</label>
                        </div>
                    </div>
                </div>
                <div class="row px-2">
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="rfc_proveedor_destino">
                            <label for="rfc_proveedor_destino">RFC PROVEEDOR</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="moneda_pagos">
                            <label for="moneda_pagos">MONEDA</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="tipo_cambio">
                            <label for="tipo_cambio">TIPO CAMBIO</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="subtotal_destino_pagos">
                            <label for="subtotal_destino_pagos">SUBTOTAL</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="iva_pagos">
                            <label for="iva_pagos">IVA</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="total_destino_pagos">
                            <label for="total_destino_pagos">TOTAL</label>
                        </div>
                    </div>
                </div>
                <div class="row px-2">
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cuenta_receptora">
                            <label for="concepto_destino">CONCEPTO</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cuenta_receptora">
                            <label for="observacion_destino">OBSERVACION</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end py-2">
                <button class="btn btn-danger">
                    GENERAR PAGO
                </button>
            </div>
        </div>
        <br>
           
        

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>


</body>

</html>