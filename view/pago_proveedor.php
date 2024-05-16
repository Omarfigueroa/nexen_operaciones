<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ModalGral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
</head>
<body>
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="row my-5">
                        <div class="col col-md-1">
                        </div>
                        <div class="col">
                            <label for="filtro_cliente" class="form-label">Filtro Cliente</label>
                            <select name="filtro_cliente" id="filtro_cliente" class="form-select" required>
                                <option value="">Selecciona Cliente</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="filtro_operador" class="form-label">Filtro Operador</label>
                            <select name="filtro_operador" id="filtro_operador" class="form-select" required>
                                <option value="">Selecciona Operador</option>
                            </select>
                        </div>
                        <div class="col col-md-1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="pago_proveedor" class="table w-100 text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">NO SOLICITUD</th>
                                        <th scope="col">CONCEPTO</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">OPERADOR</th>
                                        <th scope="col">CUENTA OPERADOR</th>
                                        <th scope="col">PROVEEDOR</th>
                                        <th scope="col">CUENTA PROVEEDOR</th>
                                        <th scope="col">CLAVE RASTREO</th>
                                        <th scope="col">MONTO CARGO</th>
                                        <th scope="col">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../utils/functions_pago_proveedor.js"></script>
</body>
</html>