
<?php 
require '../conexion/bd.php';
require_once ($_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php');
require '../utils/catalogos1.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Proveedor Finanzas</title>
    <link href="../css/estilos.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet"> -->

    <!-- Estilos de DataTables y Select2 -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" rel="stylesheet">
    
    <!-- Script de jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts de Font Awesome -->
    <script src="https://link.fontawesome.com/a076d05399.js"></script>
    
    <!-- Scripts de DataTables y Select2 -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script src="../utils/functions_docProveedor1.js"></script>

    <script>
        $(document).ready(function() {
            $("#id_collapse_operador").click(function() {
                $("#operadorCollapse").toggle();
            });
        });
    </script>


</head>
<body>
<?php include('../plantilla/menu.php'); ?>

<!--input donde va a cargar el id de fk_banco oculto--> 
<input type="text" id="idBancoInput" class="d-none">
<input type="text" id="id_razonselect" class="d-none">

<select name="selectGenerico" id="selectGenerico" class="form-select d-none">
    <option value="">Selecciona Banco</option>    
</select>

    <div class = "form-table-consulta text-center">
            <h1>Cargar Cuenta Proveedor</h1>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8"> 
                    <div class="d-flex justify-content-end"> <!-- Use d-flex and justify-content-end to align to the right -->
                        <button type="button" class="btn btn-primary my-2" onclick="modalGuardarOperador(this)" modalTitle="Agregar Operador">Agregar Operador +</button>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="list-group">
                                <button class="list-group-item list-group-item-action bg-secondary text-white d-flex justify-content-between align-items-center" type="button" id="id_collapse_operador">
                                    <span class="text-center">Selecciona un operador</span>
                                </button>
                                <div class="collapse" id="operadorCollapse">
                                    <?php
                                    $colors = ["#f8f9fa", "#ffffff"]; // Colores para las filas alternas
                                    $colorIndex = 0;

                                    foreach ($result_empresas as $valores) :
                                    ?>
                                        <label class="list-group-item list-group-item-action py-1 d-flex justify-content-between align-items-center" style="background-color: <?php echo $colors[$colorIndex]; ?>">
                                            <?php echo htmlspecialchars($valores['Razon_Social']); ?>
                                            <input class="form-check-input" type="checkbox" 
                                            id_empresa="<?=  $valores['ID_EMPRESA'] ?>"
                                            rfc="<?=  $valores['RFC'] ?>"
                                            domicilio="<?=  $valores['DOMICILIO_FISCAL'] ?>"
                                            repLegal="<?=  $valores['REPRESENTANTE_LEGAL'] ?>"
                                            value="<?=  htmlspecialchars($valores['Razon_Social']); ?>" 
                                            name="operadores[]">
                                        </label>
                                        <?php
                                        $colorIndex = 1 - $colorIndex; // Cambia el color para la siguiente fila
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group form-floating mb-3">
                        <select class="form-control select2" id="fk_cuenta_destino" name="fk_cuenta_destino" required>
                            <option value="" disabled selected>Selecciona una cuenta destino...</option>
                            <?php foreach ($result_catalogo_proveedoresCuentas as $row): ?>
                                <option id_razon="<?= $row['id_Razon'] ?>" 
                                        razon_social="<?= $row['Razon_Social'] ?>" 
                                        refProveedor="<?= $row['Ref_Proveedor'] ?>"
                                        curp="<?= $row['CURP'] ?>"
                                        tipo_servicio="<?= $row['Tipo_Servicio'] ?>"
                                        tipo_persona="<?= $row['Tipo_Persona'] ?>"
                                        rfc="<?= $row['RFC'] ?>"
                                        value="<?= $row['Alias'] ?>"
                                >
                                    <!--Valor-->
                                    <?= $row['Alias'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-outline-secondary" type="button" onclick="modalGuardarTipoCuenta(this)"  modalTitle="Agregar Cuenta">Agregar Cuenta+</button>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4"> 
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="fk_razon_social" name="fk_razon_social" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" required disabled>
                        <label for="">Razón Social</label>
                    </div> 
                </div>
                <div class="col-4"> 
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="fk_referencia_proveedor" name="fk_referencia_proveedor" oninput="quitarComillas(this)" onblur="validarTextNumber(this)" disabled>
                        <label for="">Referencia Proveedor</label>
                    </div>     
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" maxLength="18" id="fk_curp" name="fk_curp" oninput="quitarComillas(this)" required disabled> 
                        <label for="">CURP</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="align-text-bottom form-control" id="fk_tipo_servicio" name="fk_tipo_servicio" oninput="quitarComillas(this)" required disabled> 
                        <label for="">Tipo Servicio</label>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>  
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="fk_tipo_persona" name="fk_tipo_persona" oninput="quitarComillas(this)" required disabled> 
                        <label for="">Tipo Persona</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="fk_rfc"  name="fk_rfc" oninput="quitarComillas(this)"  onblur="validarTextNumber(this)" required disabled>
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
                        <select class="align-text-bottom form-control" id="fk_tipo_cuenta" name="fk_tipo_cuenta" onchange='verificarTipoCuenta(this.value);' required>
                            <?php 
                                echo '<option value="">Selecciona una opción</option>';
                                foreach ($result_tipo_cuenta as $valores):
                                    echo '<option value="'.$valores['DESCRIPCION'].'">'.$valores['DESCRIPCION'].'</option>';
                                endforeach; 
                            ?> 
                        </select>
                        <label for="c_tipo_cuenta">Tipo Cuenta</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="input-group form-floating mb-3">
                        <select class="align-text-bottom form-control h-100" id="fk_banco" name="fk_banco" required>
                            <?php 
                                echo '<option value="">Selecciona una opción</option>';
                                foreach ($result_catalogo_banco as $banco) :
                                    echo '<option idBanco="'.htmlspecialchars($banco['ID_BANCO']).'" value="'.htmlspecialchars($banco['NOMBRE_BANCO']).'">'.htmlspecialchars($banco['NOMBRE_BANCO']).'</option>';
                                endforeach; 
                            ?> 
                        </select>
                        <button class="btn btn-outline-secondary" type="button" onclick="modalAgregarEditarBanco(this)"  modalTitle="Agregar/Editar Banco">Agregar/Editar banco</button>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4"> 
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="fk_cuenta" name="fk_cuenta" oninput="quitarComillas(this)" onblur="validarNumber(this)" disabled>
                        <label for="">Cuenta</label>
                    </div>
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="fk_abba" name="fk_abba" oninput="quitarComillas(this)" onblur="validarNumber(this)" disabled>
                        <label for="">SWIFT/ABA</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating  mb-3">
                        <input type="text" class="align-text-bottom form-control" id="fk_clabe" name="fk_clabe" oninput="quitarComillas(this)" onblur="validarNumber(this)" disabled>
                        <label for="">CLABE</label>
                    </div>
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="fk_banco_inter" name="fk_banco_inter" oninput="quitarComillas(this)">
                        <label for="">Banco Intermediario</label>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8"> 
                    <div class="form-floating  mb-3">
                        <input type="text"  class="align-text-bottom form-control" id="fk_domicilio" name="fk_domicilio" oninput="quitarComillas(this)">
                        <label for="">Domicilio Completo</label>
                    </div>        
                </div>
                <div class="col-2"></div>
            </div>
            <br>
                <button id="agregarBancoACuenta" modalTitle="Agregar Banco a Cuenta" onclick="ModalagregarBancoACuenta()" class="d-none btn btn-primary btn-lg">
                    Agregar Banco a Cuenta
                    <span id="spinner_agregar_BancoACuenta" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                <button id="actualizar_cuenta" onclick="actualizarCuenta()" class="d-none btn btn-warning btn-lg">
                    Actualizar cuenta
                    <span id="spinner_actualizar_cuenta" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                <button id="guardar_cuenta" onclick="guardarCuenta()" type="button" class="btn btn-success btn-lg">
                    Guardar Cuenta
                    <span id="spinner_guardar_cuenta" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            
            

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
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <table id="registros_proveedor" class="table table-bordered table-striped w-100 text-center border">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Id</th>
                                <th scope="col" class="text-center">Razon Social</th>
                                <th scope="col" class="text-center">CURP</th>
                                <th scope="col" class="text-center">RFC</th>
                                <th scope="col" class="text-center">Tipo Persona</th>
                                <th scope="col" class="text-center">Tipo Servicio</th>
                                <th scope="col" class="text-center">Cuentas</th>
                                <th scope="col" class="text-center">Proveedores</th>
                                <th scope="col" class="text-center">Documentos</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <input type="hidden" id="idProveedor_modal">
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
                                <!-- <button class="btn btn-primary" type="button" id="OF_Down" onclick="comprobarSiExisteBajar(this)">
                                    <span class="bi bi-arrow-down"></span>
                                    <span id="spinner_OF_Down" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <button class="btn btn-danger" type="button" id="OF_Delete" onclick="comprobarSiExisteBorrar(this)">
                                    <span class="bi bi-trash"></span>
                                    <span id="spinner_OF_Delete" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button> -->
                            </div>
                        </td>
                        <td>
                            <div class="input-group mb-3">
                                <input class="form-control" type="file" accept="application/pdf" id="CIF_File">
                                <button class="btn btn-success" type="button" id="CIF_Up" onclick="comprobarSiExisteSubir(this)">
                                    <span class="bi bi-arrow-up"></span>
                                    <span id="spinner_CIF_Up" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <!--  
                                <button class="btn btn-primary" type="button" id="CIF_Down" onclick="comprobarSiExisteBajar(this)">
                                    <span class="bi bi-arrow-down"></span>
                                    <span id="spinner_CIF_Down" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <button class="btn btn-danger" type="button" id="CIF_Delete" onclick="comprobarSiExisteBorrar(this)">
                                    <span class="bi bi-trash"></span>
                                    <span id="spinner_CIF_Delete" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                -->
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table id="cuentas_proveedor" class="table table-bordered table-striped w-100 text-center">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Id</th>
                                        <th scope="col" class="text-center">Archivo</th>
                                        <th scope="col" class="text-center">Mes</th>
                                        <th scope="col" class="text-center">Año</th>
                                        <th scope="col" class="text-center">Tipo</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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


<!--INICIA MODAL DINAMICO --> 
<div class="modal fade" id="modalDinamico" tabindex="-1" aria-labelledby="modalDinamico" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDinamicoTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="modalDinamicoBody" class="modal-body">
        
      </div>
      <div id="modalDinamicoFooter" class="modal-footer">
        
      </div>
    </div>
  </div>
</div>
<!--MODAL DINAMICO -->

<!--INICIA MODAL banco a cuenta --> 
<div class="modal fade" id="modalBancoACuenta" tabindex="-1" aria-labelledby="modalDinamico" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBancoACuenta">Cargar Banco a Cuenta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="modalBancoACuentaBody" class="modal-body">
        <div class="row">
                <input type="text" id="idBancoBC" class="d-none">
                <div class="col-12">
                    <div class=" input-group form-floating  mb-3">
                        <select class="align-text-bottom form-control" id="bc_tipo_cuenta" name="bc_tipo_cuenta" onchange='verificarTipoCuentabc(this.value);' required>
                            <?php 
                                echo '<option value="">Selecciona una opción</option>';
                                foreach ($result_tipo_cuenta as $valores):
                                    echo '<option value="'.$valores['DESCRIPCION'].'">'.$valores['DESCRIPCION'].'</option>';
                                endforeach; 
                            ?> 
                        </select>
                        <label for="c_tipo_cuenta">Tipo Cuenta</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-group form-floating mb-3">
                        <select class="align-text-bottom form-control h-100" id="bc_banco" name="bc_banco" required>
                            <?php 
                                echo '<option value="">Selecciona una opción</option>';
                                foreach ($result_catalogo_banco as $banco) :
                                    echo '<option idBanco="'.htmlspecialchars($banco['ID_BANCO']).'" value="'.htmlspecialchars($banco['NOMBRE_BANCO']).'">'.htmlspecialchars($banco['NOMBRE_BANCO']).'</option>';
                                endforeach; 
                            ?> 
                        </select>
                    </div>
                </div>
                   
                </div>
                <div class="row">
                    
                    <div class="col-6"> 
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="bc_cuenta" name="bc_cuenta" oninput="quitarComillas(this)" onblur="validarNumber(this)" disabled>
                            <label for="">Cuenta</label>
                        </div>
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="bc_abba" name="bc_abba" oninput="quitarComillas(this)" onblur="validarNumber(this)" disabled>
                            <label for="">SWIFT/ABA</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="bc_clabe" name="bc_clabe" oninput="quitarComillas(this)" onblur="validarNumber(this)" disabled>
                            <label for="">CLABE</label>
                        </div>
                        <div class="form-floating  mb-3">
                            <input type="text"  class="align-text-bottom form-control" id="bc_banco_inter" name="bc_banco_inter" oninput="quitarComillas(this)">
                            <label for="">Banco Intermediario</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating  mb-3">
                            <input type="text" class="align-text-bottom form-control" id="bc_domicilio" name="bc_domicilio" oninput="quitarComillas(this)" onblur="validarNumber(this)" >
                            <label for="">Domicilio</label>
                        </div>
                    </div>
                    
                </div>
        </div>
      <div id="modalBancoACuentaFooter" class="modal-footer">
            <button id="btnAgregarBancoACuenta" onclick="agregarBancoACuenta()" class="btn btn-success">
                Agregar Banco a Cuenta
                <span id="spinner_bancoacuenta" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
      </div>
    </div>
  </div>
</div>
<!--MODAL DINAMICO -->

<!--modal pal loading--> 
<!-- Modal de carga -->
<div class="modal" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Cargando...</p>
            </div>
        </div>
    </div>
</div>
<!--Termina modal loading--> 


<!-- Inicia Modal que Abre Tabla para mostrar datos-->
<div class="modal fade" id="listadoCuentas" tabindex="-1" aria-labelledby="listadoCuentas">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-light">
                <h5 class="modal-title" id="listadoCuentasLabel">Cuentas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="registro_cuentas" class="table table-bordered table-striped w-100 text-center border">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Id</th>
                            <th scope="col" class="text-center">Banco</th>
                            <th scope="col" class="text-center">Cuenta</th>
                            <th scope="col" class="text-center">CLABE</th>
                            <th scope="col" class="text-center">SWT ABBA</th>
                            <th scope="col" class="text-center">Banco Intermediario</th>
                            <th scope="col" class="text-center">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Termina Modal que Abre Tabla para mostrar datos-->



<!-- Inicia Modal que Abre Tabla para mostrar datos-->
<div class="modal fade" id="modalFormProveedores" tabindex="-1" aria-labelledby="modalFormProveedores">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="modalFormProveedoresLabel">Proveedores</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="guardar_proveedor" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                        <input type="hidden" id="id_razon" name="id_razon">
                        <div class="row my-2">
                            <div class="col">
                                <h6><label for="razon_social" class="titulos">Razon Social</label></h6>
                                <input type="text" class="align-text-bottom form-control" id="razon_social" name="razon_social" required>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <h6><label for="alias" class="titulos">Alias</label></h6>
                                <input type="text" class="align-text-bottom form-control" id="alias" name="alias">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <h6><label for="tipo_persona" class="titulos">Tipo de Persona</label></h6>
                                <input type="text" class="align-text-bottom form-control" id="tipo_persona" name="tipo_persona">
                            </div>
                            <div class="col">
                                <h6><label for="tipo_servicio" class="titulos">Tipo de Servicio</label></h6>
                                <input type="text" class="align-text-bottom form-control" id="tipo_servicio" name="tipo_servicio">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <h6><label for="curp" class="titulos">CURP</label></h6>
                                <input type="text" class="align-text-bottom form-control" id="curp" name="curp">
                            </div>
                            <div class="col">
                                <h6><label for="rfc" class="titulos">RFC</label></h6>
                                <input type="text" class="align-text-bottom form-control" id="rfc" name="rfc">
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col">
                                <div class="d-grid gap-2 mx-auto">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar<i class="bi bi-x-circle"></i></button>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-grid gap-2 mx-auto">
                                    <button class="btn btn-success" type="submit" id="boton_guardar">Modificar Proveedor<i class="bi bi-pencil-square"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Termina Modal que Abre Tabla para mostrar datos-->



</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../utils/functions_table_proveedor1.js"></script>

<script type="module" src="../resources/js/functions_reporte_docsfaltantes.js"></script>
<script src="../resources/js/functions_reporte_carpetas.js"></script>

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
/*
    var btnDocuments = document.querySelectorAll(".btn-documents");

    // Agregar evento de clic a cada botón
    btnDocuments.forEach(function(button) {
        button.addEventListener("click", function() {
            console.log('Hola');
            // Obtener el valor del atributo de datos 'data-id-cuenta'
            var idCuenta = this.getAttribute("data-id-cuenta");

            console.log(idCuenta);
            // Asignar valor a un input hidden
            $('#idCuenta_modal').val(idCuenta);

            var documentModal = new bootstrap.Modal(document.getElementById('documentModal'));
            documentModal.show();
        });
    });

*/


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
            document.getElementById("fk_rfc").maxLength=13;
            document.getElementById("fk_rfc").value="";
        }else{
            document.getElementById("fk_rfc").maxLength=12;
            document.getElementById("fk_rfc").value="";
        }
    }

    
    function verificarTipoCuenta(cuenta){
        if(cuenta=='NACIONAL')//SI VALOR ES IGUAL AL TIPO DE CUENTA  "NACIONAL"
        {
            document.getElementById("fk_cuenta").required;
            document.getElementById("fk_clabe").required;
        }else if(cuenta=='INTERNACIONAL'){
           
            document.getElementById("fk_cuenta").required;
            document.getElementById("fk_abba").required;
        }
    }

    function verificarTipoCuentabc(cuenta){
        if(cuenta=='NACIONAL')//SI VALOR ES IGUAL AL TIPO DE CUENTA  "NACIONAL"
        {
            document.getElementById("bc_cuenta").required;
            document.getElementById("bc_clabe").required;
        }else if(cuenta=='INTERNACIONAL'){
           
            document.getElementById("bc_cuenta").required;
            document.getElementById("bc_abba").required;
        }
    }

</script>
