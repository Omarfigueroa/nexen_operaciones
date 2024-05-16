<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Factura</title>
    <link href="../css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://link.fontawesome.com/a076d05399.js"></script>
</head>
<body>

<?php include('../plantilla/menu.php'); ?>
<br><br>
    <div class="form-table-consulta">
            <h1>CARGAR FACTURA</h1>
    </div>
        <div class="form-tabla-contenedor"> 
            <div class="row my-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-8 ">
                    <h6 class="text-center">Proveedor</h6>
                    <input type="text"  class="align-text-bottom form-control" placeholder="Introduce Nombre Cliente">
                </div>
                <div class="col-sm-2 "></div>
            </div>
            <div class="row my-1">
                <div class="col-sm-2"></div>
                <div class="col-sm-8 ">
                   
                </div>
                <div class="col-sm-2 "></div>
            </div>
            <br>
            <div class="row my-1">
                <div class="col-sm-4">
                    <h6 class="text-center">Número de Factura</h6>
                    <input type="text"  class="align-text-bottom form-control" placeholder="Introduce # factura">
                </div>
                <div class="col-sm-4 ">
                        <h6 class="text-center">Fecha de Factura</h6>
                        <input type="date"  class="align-text-bottom form-control">
                </div>
                <div class="col-sm-4 ">
                        <h6 class="text-center">TAX-ID</h6>
                        <input type="text"  class="align-text-bottom form-control">
                </div>
            </div>
            <!--Esto va hasta abajo, cuando se tenga todo lo de arriba se descomenta
            <div class="row my-1">
                <div class="col-sm-12">
                    <h6 class="text-center">Descripción Cove</h6>
                    <input type="text"  class="align-text-bottom form-control">
                </div>
            </div>
            -->
            <div class="row my-1">
                <div class="col-sm-8">
                    <h6 class="text-center">Operador</h6>
                    <select class="align-text-bottom form-control">
                        <?php 
                        foreach ($result_cat_proveedor as $valores):
                            echo '<option value="'.$valores["proveedor"].'">'.$valores["proveedor"].'</option>';
                        endforeach; ?> 
                    </select>
                </div>
                <div class="col-sm-4">
                    <h6 class="text-center">RFC</h6>
                    <input type="text" class="align-text-bottom form-control" placeholder="Introduce RFC">
                </div>
            </div>
            <div class="row my-1">
                <div class="col-sm-8">
                    <h6 class="text-center">Domicilio Fiscal Operador</h6>
                    <select class="align-text-bottom form-control">
                        <?php 
                        foreach ($result_cat_proveedor as $valores):
                            echo '<option value="'.$valores["proveedor"].'">'.$valores["proveedor"].'</option>';
                        endforeach; ?> 
                    </select>
                </div>
                <div class="col-sm-4 ">
                    <h6 class="text-center">Valor de Factura</h6>
                    <input type="text" class="align-text-bottom form-control" placeholder="Introduce Valor 00.00">
                </div>
            </div>
        </div>
        <br>
        <div class="row my-1">
            <div class="col-sm-3 "></div>
            <div class="col-sm-6 text-center">
                <button type="button" class="btn btn-lg btn-danger" >Cancelar</button>
                <button type="button" class="btn btn-lg btn-success">Guardar</button>
            </div>
            <div class="col-sm-3 "></div>
        </div>
        <br>
        <div class="embed-responsive form-tabla-contenedor">
            <div class="container-fluid">
                <table class="table w-100 p-3">
                    <thead>
                        <tr>
                            <th scope="col"># FACTURA</th>
                            <th scope="col">CONCEPTO</th>
                            <th scope="col">VALOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>MarkDSADSAD</td>
                            <td>MarkDSADSAD</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php include('../plantilla/footer.php'); ?>
</body>
</html>