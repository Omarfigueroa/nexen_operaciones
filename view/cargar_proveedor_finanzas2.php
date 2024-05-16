
<?php 
require '../conexion/bd.php';
require_once ($_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php');
require '../utils/catalogos.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Proveedor Finanzas</title>
    <link href="../css/estilos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
    <script src="https://link.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css">
    <script src="../utils/functions_docProveedor.js"></script>
</head>
<body>
<?php include('../plantilla/menu.php'); ?>

    <div class = "form-table-consulta text-center">
        <form  action="../include/guardar_proveedor_finanzas.php" method="POST">
            <h1>Cargar Cuenta Proveedor</h1>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8"> 
                    <div class="form-floating  mb-3">
                        <select class="align-text-bottom form-control" id="c_operador" name="c_operador" required>
                            <?php 
                                echo '<option value="">Selecciona una opción</option>';
                                foreach ($result_empresas as $valores):
                                    echo '<option value="'.$valores['Razon_Social'].'">'.$valores['Razon_Social'].'</option>';
                                endforeach; 
                            ?> 
                        </select>
                        <label for="">Operador</label>
                    </div> 
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="c_cuenta_destino" name="c_cuenta_destino" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" required>
                        <label for="">Cuenta Destino</label>
                    </div>  
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4"> 
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="c_razon_social" name="c_razon_social" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" required>
                        <label for="">Razón Social</label>
                    </div> 
                </div>
                <div class="col-4"> 
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="c_referencia_proveedor" name="c_referencia_proveedor" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" >
                        <label for="">Referencia Proveedor</label>
                    </div>     
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" maxLength="18" id="c_curp" name="c_curp" oninput="quitarComillas(this)" required>
                        <label for="">CURP</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating mb-3">
                        <select class="align-text-bottom form-control" id="c_tipo_servicio" name="c_tipo_servicio" >
                            <?php 
                                echo '<option value="">Selecciona una opción</option>';
                                foreach ($result_tipo_servicio as $valores):
                                    echo '<option value="'.$valores['DESCRIPCION'].'">'.$valores['DESCRIPCION'].'</option>';
                                endforeach; 
                            ?> 
                        </select>
                        <label for="">Tipo Servicio</label>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>  
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <select class="align-text-bottom form-control" id="c_tipo_persona" name="c_tipo_persona" onchange='verificarTipoPersona(this.value);' required>
                            <option value="">Selecciona una opción</option>
                            <option value="Fisica">FÍSICA</option>
                            <option value="Moral">MORAL</option>
                           
                        </select>
                        <label for="">Tipo Persona</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="c_rfc"  name="c_rfc" oninput="quitarComillas(this)"  onblur="validarTextNumber(this)" required>
                        <label for="">RFC</label>
                    </div>
                </div>
                
                <div class="col-2">
                    <div class="form-floating mb-3">
                        
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <select class="align-text-bottom form-control" id="c_tipo_cuenta" name="c_tipo_cuenta" onchange='verificarTipoCuenta(this.value);' required>
                            <?php 
                                echo '<option value="">Selecciona una opción</option>';
                                foreach ($result_tipo_cuenta as $valores):
                                    echo '<option value="'.$valores['DESCRIPCION'].'">'.$valores['DESCRIPCION'].'</option>';
                                endforeach; 
                            ?> 
                        </select>
                        <label for="">Tipo Cuenta</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="c_banco" name="c_banco" oninput="quitarComillas(this)" required>
                        <label for="">Banco</label>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4"> 
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="c_cuenta" name="c_cuenta" oninput="quitarComillas(this)" onblur="validarNumber(this)">
                        <label for="">Cuenta</label>
                    </div>
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="c_abba" name="c_abba" oninput="quitarComillas(this)" onblur="validarNumber(this)">
                        <label for="">SWIFT/ABA</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="c_clabe" name="c_clabe" oninput="quitarComillas(this)" onblur="validarNumber(this)">
                        <label for="">CLABE</label>
                    </div>
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="c_banco_inter" name="c_banco_inter" oninput="quitarComillas(this)">
                        <label for="">Banco Intermediario</label>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8"> 
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="c_domicilio" name="c_domicilio" oninput="quitarComillas(this)">
                        <label for="">Domicilio Completo</label>
                    </div>        
                </div>
                <div class="col-2"></div>
            </div>
            <br>
            <button type="submit" class="btn btn-success btn-lg">Guardar Cuenta</button>
        </form>
    <br><br>

        <div class="row my-5">
            <div class="col text-end">
                <label for="">Periodo</label>
            </div>
            <div class="col">                     
                <select class="form-select" name="periodo" id="periodo" required>
                    <option value=""></option>
                    <option value="Enero-2023">Enero 2023</option>
                    <option value="Febrero-2023">Febrero 2023</option>
                    <option value="Marzo-2023">Marzo 2023</option>
                    <option value="Abril-2023">Abril 2023</option>
                    <option value="Mayo-2023">Mayo 2023</option>
                    <option value="Junio-2023">Junio 2023</option>
                    <option value="Julio-2023">Julio 2023</option>
                    <option value="Agosto-2023">Agosto 2023</option>
                    <option value="Septiembre-2023">Septiembre 2023</option>
                    <option value="Octubre-2023">Octubre 2023</option>
                    <option value="Noviembre-2023">Noviembre 2023</option>
                    <option value="Diciembre-2023">Diciembre 2023</option>
                </select>
            </div>
            <div class="col text-start">
                <button onclick="descargar_cuentas()" class="btn btn-success">Descargar Cuentas</button>
            </div>
        </div>


        <table id="data_table" class="table">
            <thead>
                <tr>
                    <th>CUENTA DESTINO</th>
                    <th>RAZÓN SOCIAL</th>
                    <th>RFC</th>
                    <th>BANCO</th>
                    <th>CUENTA</th>
                    <th>CLABE</th>
                    <th>SWT/ABBA</th>
                    <th>BANCO INTERMEDIARIO</th>
                    <th>DOMICILIO</th>
                    <th>OPERADOR</th>
                    <th>DOCUMENTOS</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query_cuenta_destino  = "SELECT * FROM [dbo].[Cuenta_Destino] WHERE Estatus = 'A'";
                    $select_cuentas = $conn_bd->prepare($query_cuenta_destino);
                    $select_cuentas -> execute();
                    $results_select_cuentas = $select_cuentas -> fetchAll(PDO::FETCH_ASSOC); 
                   
                    foreach  ($results_select_cuentas as $cuenta_destino): ?>
                    <tr>
                        <td class=""><?php  echo $cuenta_destino['Cuenta_Destino'];?></td>
                        <td class=""><?php  echo $cuenta_destino['Razon_social'];?></td>
                        <td class=""><?php  echo $cuenta_destino['RFC'];?></td>
                        <td class=""><?php  echo $cuenta_destino['Banco'];?></td>
                        <td class=""><?php  echo $cuenta_destino['Cuenta'];?></td>
                        <td class=""><?php  echo $cuenta_destino['Clabe'];?></td>
                        <td class=""><?php  echo $cuenta_destino['SWT_ABBA'];?></td>
                        <td class=""><?php  echo $cuenta_destino['Banco_Intermediario'];?></td>
                        <td class=""><?php  echo $cuenta_destino['Domicilio_Completo'];?></td>
                        <td class=""><?php  echo $cuenta_destino['Operador'];?></td>
                        <td class=""><button data-id-cuenta="<?php echo $cuenta_destino['Id_Cuenta'];?>" type="button" class="btn btn-primary btn-documents">Documentos</button></td>
                        <td class="">
                            <button class="btn btn-danger" data-id-deletecta="<?php echo $cuenta_destino['Id_Cuenta'];?>" onclick="deleteCtaProveedor(<?php echo $cuenta_destino['Id_Cuenta'];?>)" type="button" ><i class="bi bi-trash-fill"></i></button>
                            <button class="btn btn-info" data-id-cta="<?php echo $cuenta_destino['Id_Cuenta'];?>"  onclick="mostarCtaProveedor(<?php echo $cuenta_destino['Id_Cuenta'];?>)" type="button" ><i class="bi bi-pencil-square"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <input hidden type="text" id="idCuenta_modal">
                <h5 class="modal-title" id="documentModalLabel">Año: <?php echo date('Y'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered  table-striped align-middle table-light">
                    <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Obligaciones Fiscales</th>
                        <th>Cédula de Identificación Fiscal</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <tr>
                        <td>
                            <select class="form-select" aria-label="Mes" id="select_mes">
                                <option selected disabled value="">Selecciona un mes</option>
                                <option value="Enero">Enero</option>
                                <option value="Febrero">Febrero</option>
                                <option value="Marzo">Marzo</option>
                                <option value="Abril">Abril</option>
                                <option value="Mayo">Mayo</option>
                                <option value="Junio">Junio</option>
                                <option value="Julio">Julio</option>
                                <option value="Agosto">Agosto</option>
                                <option value="Septiembre">Septiembre</option>
                                <option value="Octubre">Octubre</option>
                                <option value="Noviembre">Noviembre</option>
                                <option value="Diciembre">Diciembre</option>
                            </select>
                        </td>
                        <td>
                            <div class="input-group mb-3">
                                <input class="form-control" type="file" accept="application/pdf" id="OF_File">
                                <button class="btn btn-success" type="button" id="OF_Up" onclick="comprobarSiExisteSubir(this)">
                                    <span class="bi bi-arrow-up"></span>
                                    <span id="spinner_OF_Up" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <button class="btn btn-primary" type="button" id="OF_Down" onclick="comprobarSiExisteBajar(this)">
                                    <span class="bi bi-arrow-down"></span>
                                    <span id="spinner_OF_Down" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <button class="btn btn-danger" type="button" id="OF_Delete" onclick="comprobarSiExisteBorrar(this)">
                                    <span class="bi bi-trash"></span>
                                    <span id="spinner_OF_Delete" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="input-group mb-3">
                                <input class="form-control" type="file" accept="application/pdf" id="CIF_File">
                                <button class="btn btn-success" type="button" id="CIF_Up" onclick="comprobarSiExisteSubir(this)">
                                    <span class="bi bi-arrow-up"></span>
                                    <span id="spinner_CIF_Up" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <button class="btn btn-primary" type="button" id="CIF_Down" onclick="comprobarSiExisteBajar(this)">
                                    <span class="bi bi-arrow-down"></span>
                                    <span id="spinner_CIF_Down" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <button class="btn btn-danger" type="button" id="CIF_Delete" onclick="comprobarSiExisteBorrar(this)">
                                    <span class="bi bi-trash"></span>
                                    <span id="spinner_CIF_Delete" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Repetir para los demás meses -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- modal accion editar proveedor -->
<div class="modal fade" id="actualizarCuentaDestino" tabindex="-1" aria-labelledby="actualizarCuentaDestinoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white text-center">
        <label class="modal-title  text-wrap" style="width: 50rem;" id="editmodalcliente">EDITAR CUENTA PROVEEDOR</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        
            $select_cta_proveedor = "SELECT * FROM [dbo].[Cuenta_Destino] WHERE Id_Cuenta = ''";
        ?>
            <form >
                <div class="row">
                <input type="hidden"  id="id_cta" name="id_cta" >
                    <div class="col-12">
                        <div class="form-floating  mb-3">
                            <select class="align-text-bottom form-control" id="e_operador" name="e_operador" required>
                                <?php 
                                    echo '<option value="">Selecciona una opción</option>';
                                    foreach ($result_empresas as $valores):
                                        echo '<option value="'.$valores['Razon_Social'].'">'.$valores['Razon_Social'].'</option>';
                                    endforeach; 
                                ?> 
                            </select>
                            <label for="">Operador</label>
                        </div> 
                        <div class="form-floating  mb-3">
                            <input type="text"  class="align-text-bottom form-control" id="e_cuenta_destino" name="e_cuenta_destino" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" required>
                            <label for="">Cuenta Destino</label>
                        </div> 
                    </div>  
                </div>
                <div class="row">
                    <div class="col-6"> 
                        <div class="form-floating  mb-3">
                            <input type="text"  class="align-text-bottom form-control" id="e_razon_social" name="e_razon_social" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" required>
                            <label for="">Razón Social</label>
                        </div> 
                    </div>
                    <div class="col-6"> 
                        <div class="form-floating  mb-3">
                            <input type="text"  class="align-text-bottom form-control" id="e_referencia_proveedor" name="e_referencia_proveedor" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" required>
                            <label for="">Referencia Proveedor</label>
                        </div>     
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" maxLength="18" id="e_curp" name="e_curp" oninput="quitarComillas(this)" required>
                            <label for="">CURP</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <select class="align-text-bottom form-control" id="e_tipo_servicio" name="e_tipo_servicio" >
                                <?php 
                                    echo '<option value="">Selecciona una opción</option>';
                                    foreach ($result_tipo_servicio as $valores):
                                        echo '<option value="'.$valores['DESCRIPCION'].'">'.$valores['DESCRIPCION'].'</option>';
                                    endforeach; 
                                ?> 
                            </select>
                            <label for="">Tipo Servicio</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating  mb-3">
                            <select class="align-text-bottom form-control" id="e_tipo_persona" name="e_tipo_persona" onchange='verificarTipoPersona(this.value);' required>
                                <option value="">Selecciona una opción</option>
                                <option value="Fisica">FÍSICA</option>
                                <option value="Moral">MORAL</option>
                            
                            </select>
                            <label for="">Tipo Persona</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating  mb-3">
                            <input type="text"  class="align-text-bottom form-control" id="e_rfc"  name="e_rfc" oninput="quitarComillas(this)"  onblur="validarTextNumber(this)" required>
                            <label for="">RFC</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating  mb-3">
                            <select class="align-text-bottom form-control" id="e_tipo_cuenta" name="e_tipo_cuenta" onchange='verificarTipoCuenta(this.value);' required>
                                <?php 
                                    echo '<option value="">Selecciona una opción</option>';
                                    foreach ($result_tipo_cuenta as $valores):
                                        echo '<option value="'.$valores['DESCRIPCION'].'">'.$valores['DESCRIPCION'].'</option>';
                                    endforeach; 
                                ?> 
                            </select>
                            <label for="">Tipo Cuenta</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="e_banco" name="e_banco" oninput="quitarComillas(this)" required>
                            <label for="">Banco</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6"> 
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="e_cuenta" name="e_cuenta" oninput="quitarComillas(this)" onblur="validarNumber(this)">
                            <label for="">Cuenta</label>
                        </div>
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="e_abba" name="e_abba" oninput="quitarComillas(this)" onblur="validarNumber(this)">
                            <label for="">SWIFT/ABA</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="e_clabe" name="e_clabe" oninput="quitarComillas(this)" onblur="validarNumber(this)">
                            <label for="">CLABE</label>
                        </div>
                        <div class="form-floating  mb-3">
                            <input type="text"  class="align-text-bottom form-control" id="e_banco_inter" name="e_banco_inter" oninput="quitarComillas(this)">
                            <label for="">Banco Intermediario</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"> 
                        <div class="form-floating  mb-3">
                            <input type="text"  class="align-text-bottom form-control" id="e_domicilio" name="e_domicilio" oninput="quitarComillas(this)">
                            <label for="">Domicilio Completo</label>
                        </div>        
                    </div>
                </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="updateCtaProveedor()" >Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- fin modal accion editar proveedor -->




</body>
</html>
<script src="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var dataTable = document.getElementById("data_table");

        var table = new DataTable(dataTable, {
            paging: true, // Activar paginación
            pageLength: 10, // Mostrar 10 registros por página
            searching: true, // Activar el buscador
            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros en total)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    });

    //SE HACE LA LOGICA DEL MODAL CON LAS FECHAS
    // Seleccionar los botones con la clase 'btn-documents'

    var btnDocuments = document.querySelectorAll(".btn-documents");

    // Agregar evento de clic a cada botón
    btnDocuments.forEach(function(button) {
        button.addEventListener("click", function() {
            // Obtener el valor del atributo de datos 'data-id-cuenta'
            var idCuenta = this.getAttribute("data-id-cuenta");

            console.log(idCuenta);
            // Asignar valor a un input hidden
            $('#idCuenta_modal').val(idCuenta);

            var documentModal = new bootstrap.Modal(document.getElementById('documentModal'));
            documentModal.show();
        });
    });



</script>

<script>
    const soloTexto = /^[a-zA-Z]+$/; //Expresión regular que solo permite letras
    const soloNumeros = /^[0-9]+$/; //Expresión regular que solo permite números
    const soloNumerosYLetras = /^[a-zA-Z0-9\s!@#$%^&*(),.?":{}|<>_+-=\\/\[\]]*$/; //Expresión regular que solo permite números y letras
    const isValid = /^[^'"]*$/.test(value) && /^[a-zA-Z0-9\s!@#$%^&*(),.?":{}|<>_+-=\\/\[\]]*$/.test(value);

    function quitarComillas(elemento) {
        var valor = elemento.value;
        valor = valor.replace(/["']/g, ''); // reemplazar comillas dobles y simples con una cadena vacía
        elemento.value = valor;
    }

    const validarText = (input) => {
        if (soloTexto.test(input.value)) {
            input.classList.remove("campo_rojo");
            input.classList.add("campo_verde");
        } else {
            input.classList.remove("campo_verde");
            input.classList.add("campo_rojo");
        }
    };
    
    const validarNumber = (input) => {
        if (soloNumeros.test(input.value)) {
        input.classList.remove("campo_rojo");
        input.classList.add("campo_verde");

        } else {
        input.classList.remove("campo_verde");
        input.classList.add("campo_rojo");
        }
    };


    const validarTextNumber = (input) => {
        if (soloNumerosYLetras.test(input.value)) {
        input.classList.remove("campo_rojo");
        input.classList.add("campo_verde");
        } else {
        input.classList.remove("campo_verde");
        input.classList.add("campo_rojo");
        }
    };

    function descargar_cuentas() {
        const periodo = document.getElementById('periodo').value;

        if(periodo==""){
            alert("Selecciona un periodo");
        }else{
            location.href = '../include/descargar_cuentas_doc.php?periodo='+periodo;
        }

    }
    
    function verificarTipoPersona(valor){
        if(valor=='Fisica')//SI VALOR ES IGUAL AL TIPO PERSONA  "FISICA"
        {
            document.getElementById("c_rfc").maxLength=13;
            document.getElementById("c_rfc").value="";
        }else{
            document.getElementById("c_rfc").maxLength=12;
            document.getElementById("c_rfc").value="";
        }
    }

    
    function verificarTipoCuenta(cuenta){
        if(cuenta=='NACIONAL')//SI VALOR ES IGUAL AL TIPO DE CUENTA  "NACIONAL"
        {
            document.getElementById("c_cuenta").required;
            document.getElementById("c_clabe").required;
        }else if(cuenta=='INTERNACIONAL'){
           
            document.getElementById("c_cuenta").required;
            document.getElementById("c_abba").required;
        }
    }

</script>
