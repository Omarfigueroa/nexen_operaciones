<?php 
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones</title>
    <link href="../css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
    <script src="https://link.fontawesome.com/a076d05399.js"></script>


    

    
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
    <?php include('../plantilla/menu.php'); ?>
    <br><br><br>
    <div class="row my-1">
        <div class="row">
            <h1>Registro de Operaciones</h1>
            <div class="col-md-4" style="margin-left: 30px;">
                <button type="submit" class="btn btn-success btn-md" onclick="crearReferencia()"><i
                        class="bi bi-plus-square"></i> CREAR NUEVO</button>
            </div>
        </div>

        <div class="col-10">
            <div class="mh-100"><br>
                <form action="../include/actualizar_operacion.php?referencia=<?php echo $ref ?>" method="POST">
                    <div class="form-control ">
                        <span class="title">EMPRESAS</span>
                        <div class="row my-1">
                            <div class="col">
                                <label for="" class="titulos"> Referencia Nexen</label>
                                <input class="form-control" id="referencia_nexen" name="referencia_nexen" type="text"
                                    placeholder="Referencia Nexen"
                                    value="<?php echo isset($ref) && !empty($ref) ?  $ref : "" ?>" disabled>
                            </div>
                            <div class="col">
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-cliente">
                                        <label for="btn-modal"> Cliente
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <select class="form-control" id="razon_social" name="razon_social" required>
                                    <option value="">Selecciona una opción</option>
                                    <?php  
                                        foreach ($result_cat_razon as $valores):
                                            echo '<option value="'.$valores["RAZON SOCIAL "].'">'.$valores["RAZON SOCIAL "].'</option>';
                                        endforeach; 
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label class="titulos">Referencia Cliente</label>
                                <input type="text" id="referencia_cliente" name="referencia_cliente"
                                    oninput="quitarComillas(this)" placeholder="Introduce Referencia Cliente"
                                    class="form-control" autocomplete="off"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90)"
                                    min="1">
                            </div>
                            <div class="col">
                                <div class="boton-modal1">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-estatus">
                                        <label for="btn-modal1"> Estatus
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <select class="form-control" id="estatus" name="estatus" required>
                                    <option value="">Selecciona una opción</option>
                                    <?php 
                                    foreach ($result_cat_estatus as $valores):
                                        echo '<option value="'.$valores["Descripcion"].'">'.$valores["Descripcion"].'</option>';
                                    endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label class="titulos">Saldo Cliente</label>
                                <input class="form-control" id="saldo_cliente" name="saldo_cliente" type="text"
                                    placeholder="Saldo Actual" disabled>
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col">
                                <div class="boton-modal3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-impo-expo">
                                        <label for="btn-modal3">Importador/Exportador
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <select class="align-text-bottom form-control" id="nombre_operador" name="nombre_operador" required>
                                    <option value="">Selecciona una opción</option>
                                    <?php 
                                    foreach ($result_empresas as $valores):
                                        echo '<option value="'.$valores["Razon_Social"].'">'.$valores["Razon_Social"].'</option>';
                                    endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <div class="boton-modal3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-tipo-operacion">
                                        <label for="btn-modal3">Operación
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <select class="form-control" id="tipo_operacion" name="tipo_operacion">
                                    <option value="">Selecciona una opción</option>
                                    <?php   
                                        foreach ($result_cat_tipoope as $valores):
                                            echo '<option value="'.$valores["DESCRIPCION"].'">'.$valores["DESCRIPCION"].'</option>';
                                        endforeach; 
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <div class="boton-modal4">
                                    <label for="btn-modal4" class="titulos">Clave Pedimento
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </label>
                                </div>
                                <select class="form-control" id="cve_pedimento" name="cve_pedimento">
                                    <option value="">Selecciona una opción</option>
                                    <?php  
                                        foreach ($result_clv_pedimento as $valores):
                                            echo '<option value="'.$valores["clave"].'">'.$valores["clave"].'</option>';
                                        endforeach; 
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <div class="boton-modal5">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-tipo-trafico">
                                        <label for="btn-modal5"> Tipo Tráfico
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                        </label>
                                </div>
                                <select class="form-control" onchange='tipoTransporte(this.value);' id="tipo_trafico"
                                    name="tipo_trafico" required>
                                    <option value="">Selecciona una opción</option>
                                    <?php foreach ($result_cat_vias as $valores): 
                                    echo '<option value="'.$valores["medios"].'">'.$valores["medios"].'</option>';
                                            endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-control ">
                        <span class="title">EMPRESAS</span>
                        <div class="row my-1">
                            <div class="col">
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-aduana">
                                        <label for="btn-modal"> Aduana
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <select class="form-control" id="denominacion_aduana" name="denominacion_aduana"
                                    onchange="mostrar_codigo_aduana(this.value)">
                                    <option value="">Selecciona una opción</option>
                                    <?php 
                                        foreach ($result_catalogo_aduanas as $valores):
                                            echo '<option value="'.$valores["Denominación"].'">'.$valores["Denominación"].'</option>';
                                             ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label class="titulos">Clave Aduana</label>
                                <input class="form-control" type="text" id="cve_aduana" name="cve_aduana" disabled>
                            </div>
                            <div class="col">
                                <label class="titulos">BL</label>
                                <input class="form-control" type="text" id="bl" name="bl" oninput="quitarComillas(this)"
                                    placeholder="Introduce BL">
                            </div>
                            <div class="col">
                                <label class="titulos">Master</label>
                                <input class="form-control" type="text" id="master" name="master"
                                    oninput="quitarComillas(this)" placeholder="Introduce Master">
                            </div>
                            <div class="col">
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-house">
                                        <label for="btn-modal"> House
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <path
                                    d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098L9.05.435ZM7.25 4h1.5v3.25H12v1.5H8.75V12h-1.5V8.75H4v-1.5h3.25V4Z" />
                                </svg></a></button> </label>
                                <input class="form-control" type="text" id="house" name="house"
                                    oninput="quitarComillas(this)" placeholder="Introduce House">
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col-1">
                                <br>
                                <label for="">Contenedores</label>
                            </div>
                            <div class="col-2">
                                <input class="form-control" name="contenedor1" id="contenedor1"
                                    oninput="quitarComillas(this)" type="text" maxlength="12 "
                                    placeholder="Contenedor 1">
                                <input class="form-control" name="contenedor2" id="contenedor2"
                                    oninput="quitarComillas(this)" type="text" maxlength="12 "
                                    placeholder="Contenedor 2">
                            </div>
                            <div class="col-3">
                                <label class="titulos"> Buscar Número Ecónomico </label>
                                <input class="form-control" type="text" placeholder="Introduce Numero Economico"
                                    oninput="quitarComillas(this)" name="num_eco" id="num_eco" disabled>
                            </div>
                            <div class="col-3">
                                <label class="titulos">Bultos</label>
                                <input type="text" name="bultos" id="bultos" oninput="quitarComillas(this)"
                                    placeholder="Introduce Bultos 00.00" class="form-control" autocomplete="off"
                                    onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)"
                                    min="1">
                            </div>
                            <div class="col-3">
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-proveedor">
                                        <label for="btn-modal"> Proveedor
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                        </buuton>
                                </div>
                                <select class="form-control" id="proveedor" name="proveedor">
                                    <option value="">Selecciona una opción</option>
                                    <?php  
                                        foreach ($result_cat_proveedor as $valores):
                                            echo '<option value="'.$valores["proveedor"].'">'.$valores["proveedor"].'</option>';
                                            endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-control ">
                        <span class="title">FACTURAS</span>
                        <div class="row my-1">
                            <div class="col">
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalFacturas">
                                        <label for="btn-modal">Asociar Facturas
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" A stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCargarFacturas" onclick="modalCargarFactura()">
                                        <label for="btn-modal">Cargar Factura
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" A stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                <label class="titulos">Valor Factura</label>
                                <input class="form-control" id="valor_factura" name="valor_factura"
                                    oninput="quitarComillas(this)" type="text" placeholder="Introduce Valor 00.00"
                                    class="form-control" autocomplete="off"
                                    onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)"
                                    min="1" disabled>
                            </div>
                            <div class="col">
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-moneda">
                                        <label for="btn-modal"> Moneda
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <select class="form-control" id="moneda" name="moneda">
                                    <option value="">Selecciona una opción</option>
                                    <?php  
                                        foreach ($result_catalogo_monedas as $valores):
                                            echo '<option value="'.$valores["PREFIJO"].'">'.$valores["PREFIJO"].'</option>';
                                            endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col">
                                <label>Tipo Cambio</label>
                                <input class="form-control" id="tipo_cambio" name="tipo_cambio"
                                    oninput="quitarComillas(this)" type="text" placeholder="Introduce Tipo Cambio 00.00"
                                    class="form-control" autocomplete="off"
                                    onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)"
                                    min="1">
                            </div>
                            <div class="col">
                                <label>Peso Bruto</label>
                                <input class="form-control" type="text" id="peso_bruto" oninput="quitarComillas(this)"
                                    name="peso_bruto" placeholder="Peso Bruto 00.00" class="form-control"
                                    autocomplete="off"
                                    onkeypress="return (event.charCode >= 46 && event.charCode <= 57  || event.charCode== 46)"
                                    min="1">
                            </div>
                            <div class="col">
                                <label>Fecha Notificacion</label><button type="button" class="btn btn-danger"
                                    id="btn_Notificaion"><i class="bi bi-trash"></i></button>
                                <input class="form-control" type="date" id="fechNotifi">

                            </div>
                            <div class="col">
                                <label>Fecha Arribo</label><button type="button" class="btn btn-danger"
                                    id="btn_Arribo"><i class="bi bi-trash"></i></button>
                                <input class="form-control" type="date" id="fecharribo">
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col">
                                <label>Fecha Pago Pedimento</label><button type="button" class="btn btn-danger"
                                    id="btn_pedimento"><i class="bi bi-trash"></i></button>
                                <input class="form-control" type="date" id="fechpedimento" name="fecha_pago_pedimento">
                            </div>
                            <div class="col">
                                <label>Fecha Modulación</label><button type="button" class="btn btn-danger"
                                    id="btnmodu"><i class="bi bi-trash"></i></button>
                                <input class="form-control" type="date" id="fechamodulacion">
                            </div>
                            <div class="col">
                                <label>Patente</label>
                                <input class="form-control" type="text" id="patente" oninput="quitarComillas(this)"
                                    name="patente" maxlength="4" placeholder="Patente  4 Digitos" class="form-control"
                                    autocomplete="off"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1">
                            </div>
                            <div class="col">
                                <div class="boton-modal">
                                    <label for="btn-modal">Buscar Número Pedimento
                                        <i class="bi bi-search"></i>
                                    </label>
                                </div>
                                <input class="form-control" type="text" id="num_pedimento"
                                    oninput="quitarComillas(this)" name="num_pedimento" maxlength="15"
                                    placeholder="Pedimento 7 Digitos" class="form-control" autocomplete="off"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1">
                            </div>
                        </div>
                        <div class="row my-1">
                            <div class="col">
                                <label>Número Recibo WMS</label>
                                <input class="form-control" type="text" id="wms" oninput="quitarComillas(this)"
                                    name="wms" placeholder="Introduce WMS">
                            </div>
                            <div class="col">
                                <label>Número Anexo #24</label>
                                <input class="form-control" type="text" id="anexo_24" oninput="quitarComillas(this)"
                                    name="anexo_24" placeholder="Introduce Anexo #24">
                            </div>
                            <div class="col">
                                <label>Fecha Factura Anexo #24</label><button type="button" class="btn btn-danger"
                                    id="btnNex24"><i class="bi bi-trash"></i></button>
                                <input class="form-control" type="date" id="opeNex">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-control">
                        <span class="title">CONTROLES</span>
                        <div class="row my-1">
                            <div class="col-sm-6">
                                <label>Descripción Cove</label>
                                <input type="text" class="form-control" id="descripcion_cove"
                                    oninput="quitarComillas(this)" name="descripcion_cove" placeholder="Introduce  Cove"
                                    autocomplete="off"
                                    onkeypress="return (event.charCode != 39 || event.charCode != 40 || event.charCode != 41 || event.charCode != 42)"
                                    min="1">
                            </div>
                            <div class="col-sm-3 ">
                                <label for="">Usuario</label>
                                <input type="text" name="user" id="user" oninput="quitarComillas(this)"
                                    class="form-control" disabled>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Hora</label>
                                <input type="datetime" name="hora" id="hora" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
            </div>
            <br>
            <div class="text-center">

                <button type="submit" class="btn btn-primary btn-lg"> <i class="bi bi-arrow-repeat"></i>
                    ACTUALIZAR</button>
                <button class="btn btn-danger btn-lg"><i class="bi bi-x-circle"></i> CANCELAR</button>
                <button class="btn btn-info btn-lg" onclick="window.location.href='historialdetalle.php'"><i
                        class="bi bi-clock-history"></i> HISTORIAL</button>
            </div>
            <br>
            </form>

            <!-- TABLA CONTENEDORES -->
            <div class="section-table">
                <div class="form-control">
                    CONSULTAR CONTENEDORES
                    <table id="data_table" class="table table-bordered table-responsive-sm  text-center"
                        style="font-size: 12px">
                        <thead class="bg-success">
                            <tr>
                                <th>CLIENTE</th>
                                <th>CONTENEDOR</th>
                                <th>REFERENCIA NEXEN</th>
                                <th>FECHA</th>
                                <th>USUARIO</th>
                                <th>VISUALIZAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result_operacion_nex as $valores): ?>
                            <tr>
                                <td class="">
                                    <?php  echo $valores['Cliente']; ?>
                                </td>
                                <td class="">
                                    <?php  echo $valores['Contenedor_1']; ?>
                                </td>
                                <td class="">
                                    <?php  echo $valores['REFERENCIA_NEXEN']; ?>
                                </td>
                                <td class="">
                                    <?php  echo $valores['FECHOPE']; ?>
                                </td>
                                <td class="">
                                    <?php  echo $valores['Usuario']; ?>
                                </td>
                                <td class="">
                                    <a class="btn-sm btn-success" type="button"
                                        href="../include/ver_operacion.php?contenedor=<?php echo $valores['Contenedor_1'] ?>&referencia=<?php echo $valores['REFERENCIA_NEXEN'] ?>"
                                        class="dropdown-item" target="_blank"
                                        style="text-decoration:none;">Visualizar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- FIN TABLA CONTENEDORES -->
        </div>
        <!-- </div> -->
        <!-- DIV DOCUMENTOS -->
        <div class="col">
            <div class="col-12">
                <div id="data"></div>
                <div class="tile">
                    <div class="tile-body">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>DOCUMENTO</th>
                                        <th>ACCIONES</th>
                                        <th>ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody id="tableDocumentos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->

        <!-- modal para subir archivos -->
        <div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header headerRegister">
                        <h5 class="modal-title" id="titleModal">Subir Archivo</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formArchivo" method="post" action="../include/upload.php"
                            enctype="multipart/form-data" class="form-control">
                            <div class="row" style="padding: 10px;">
                                <div class="col-md-12" style="padding: 10px;">
                                    <input type="hidden" id="id_catalogo" name="id_catalogo" value="">

                                </div>
                                <div class="col-md-12" style="padding: 10px;">
                                    <input type="text" class="form-control" name="Nombre_Archivo" id="Nombre_Archivo"
                                        disabled style="border:none">
                                    <input type="file" name="archivo" style="padding: 10px;">
                                    <button class="btn btn-success" type="submit" name="subir"><i
                                            class="bi bi-upload"></i></button>
                                </div>
                                <div class="col-md-12" style="padding: 10px;">
                                    <div id="progress-bar-container">
                                        <div id="progress-bar"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header headerRegister">
                        <h5 class="modal-title" id="titleModal">Crear Referencia</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formArchivo" method="post" action=""
                            enctype="multipart/form-data" class="form-control">
                            <div class="col" style="padding: 30px;">
                                <label for="razon_social">Cliente</label>
                                <select class="form-control" id="razon_social" name="razon_social">
                                    <?php
                                        if($nombre_cliente){
                                            echo '<option value="'.$nombre_cliente.'">'.$nombre_cliente.'</option>';
                                        }else{
                                            echo '<option value="">Selecciona una opción</option>';
                                        }
                                        foreach ($result_cat_razon as $valores):
                                            echo '<option value="'.$valores["RAZON SOCIAL "].'">'.$valores["RAZON SOCIAL "].'</option>';
                                        endforeach; 
                                    ?>
                                </select>
                                <label for="estatus">Estatus</label>
                                <select class="form-control" id="estatus" name="estatus" required>
                                    <option value="">Selecciona una opción</option>
                                    <?php 
                                    foreach ($result_cat_estatus as $valores):
                                        echo '<option value="'.$valores["Descripcion"].'">'.$valores["Descripcion"].'</option>';
                                    endforeach; ?>
                                </select>
                                <label for="nombre_operador">Importador/Exportador</label>
                                <select class="align-text-bottom form-control" name="nombre_operador" required>
                                    <option selected disabled>Selecciona una opción</option>
                                    <?php 
                                    foreach ($result_empresas as $valores):
                                        echo '<option value="'.$valores["Razon_Social"].'">'.$valores["Razon_Social"].'</option>';
                                    endforeach; ?>
                                </select>
                                <label for="tipo_trafico">Tipo Trafico</label>
                                <select class="form-control" onchange='tipoTransporte(this.value);' id="tipo_trafico"
                                    name="tipo_trafico" required>
                                    <option value="">Selecciona una opción</option>
                                    <?php foreach ($result_cat_vias as $valores): 
                                    echo '<option value="'.$valores["medios"].'">'.$valores["medios"].'</option>';
                                            endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg"
                                onclick="this.form.action =''"><i
                                    class="bi bi-plus-square"></i> CREAR
                                NUEVO</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  modal para subir archivos -->



        <!-- MODAL CLIENTES -->
        <div class="modal fade" id="modal-cliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;"
                            id="exampleModalLabel">CLIENTES</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_cliente.php" method="POST">
                            <label class="display-5">Clientes</label><br><br>
                            <label class="align-text-bottom form-label">Razón Social</label>
                            <input type="text" class="align-text-bottom form-control" name="razon_social_cliente"
                                id="razon_social_cliente" placeholder="Introduce Razón Social" required>
                            <label class="align-text-bottom form-label">RFC</label>
                            <input type="text" class="align-text-bottom form-control" name="rfc_cliente"
                                id="rfc_cliente" placeholder="Introduce RFC Cliente" required>
                            <label class="align-text-bottom form-label">Teléfono</label>
                            <input type="text" class="align-text-bottom form-control" name="telefono_cliente"
                                id="telefono_cliente" placeholder=" Introduce Telefono Cliente" required>
                            <label class="align-text-bottom form-label">Movíl</label>
                            <input type="text" class="align-text-bottom form-control" name="movil_cliente"
                                id="movil_cliente" placeholder="Introduce Movil Cliente">
                            <label class="align-text-bottom form-label"> Nombre Contacto</label>
                            <input type="text" class="align-text-bottom form-control" name="nombre_contacto"
                                id="nombre_contacto" placeholder="Introduce nombre contacto" required>
                            <label class="align-text-bottom form-label"> Email # 1</label>
                            <input type="email" class="align-text-bottom form-control" name="email_cliente_1"
                                id="email_cliente_1" placeholder="Introduce email cliente" required>
                            <label class="align-text-bottom form-label"> Email # 2</label>
                            <input type="email" class="align-text-bottom form-control" name="email_cliente_2"
                                id="email_cliente_2" placeholder="Introduce segundo email">
                            <label class="align-text-bottom form-label"> Domicilio</label>
                            <input type="text" class="align-text-bottom form-control" name="domicilio_cliente"
                                id="domicilio_cliente" placeholder="Introduce domicilio cliente" required>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
                        <div class="container-fluid">
                            <table class="table w-100 p-3">
                                <thead>
                                    <tr>
                                        <th scope="col"># REGISTRO</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">RFC</th>
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
                </div>
            </div>
        </div>
        <!-- FIN MODAL CLIENTES -->

        <!-- MODAL ESTATUS -->
        <div class="modal fade" id="modal-estatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header bg-primary text-white text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">CATALOGO
                            ESTATUS</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_estatus.php" method="POST">
                            <label class="display-5">ESTATUS</label><br><br>
                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+"
                                onblur="validarText(this)" name="estatus" id="estatus" placeholder="Introduce ESTATUS"
                                required>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-success" id="guardar_estatus">Guardar
                                    Cambios</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
                        <div class="container-fluid">
                            <table class="table w-100 p-3">
                                <thead>
                                    <tr>
                                        <th scope="col"># ESTATUS</th>
                                        <th scope="col">ESTATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>MarkDSADSAD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL ESTATUS-->

        <!-- MODAL IMPORTADOR/EXPORTADOR -->
        <div class="modal fade" id="modal-impo-expo" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;"
                            id="exampleModalLabel">IMPORTADOR/EXPORTADOR</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_importador_exportador.php" method="POST">
                            <label class="display-5">Empresas</label><br><br>
                            <label class="align-text-bottom form-label">Razon Social</label>
                            <input type="text" class="align-text-bottom form-control" name="razon_social_impo_expo"
                                id="razon_social_impo_expo" placeholder="Introduce Razón Social">
                            <label class="align-text-bottom form-label">RFC</label>
                            <input type="text" class="align-text-bottom form-control" name="rfc_impo_expo"
                                id="rfc_impo_expo" placeholder="Introduce RFC">
                            <label class="align-text-bottom form-label">Domicilio Fiscal</label>
                            <input type="text" class="align-text-bottom form-control" name="domicilio_fiscal"
                                id="domicilio_fiscal" placeholder="Domicilio Fiscal">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
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
                </div>
            </div>
        </div>
        <!-- FIN MODAL IMPORTADOR/EXPORTADOR -->

        <!-- MODAL TIPO OPERACION -->
        <div class="modal fade" id="modal-tipo-operacion" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">CATALOGO TIPO
                            OPERACION</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_tipo_operacion.php" method="POST">
                            <label class="display-6">TIPO OPERACIÓN</label><br><br>
                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+"
                                onblur="validarText(this)" id="tipo_operacion" name="tipo_operacion"
                                placeholder="Introduce Tipo Operación">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="sumbit" class="btn btn-success" id="guardar_ope">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
                        <div class="container-fluid">
                            <table class="table w-100 p-3">
                                <thead>
                                    <tr>
                                        <th scope="col"># TIPO OPERACIÓN</th>
                                        <th scope="col">DESCRIPCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>MarkDSADSAD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FIN MODAL TIPO OPERACION -->

        <!-- MODAL ADUANA -->
        <div class="modal fade" id="modal-aduana" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header bg-primary text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel"> Catalago
                            Aduanas</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_aduana.php" method="POST">
                            <label class="display-5">Aduanas</label><br><br>

                            <label class="align-text-bottom form-label">Numero Aduana</label>
                            <input type="number" class="align-text-bottom  form-control" onblur="validarNumber(this)"
                                id="numero_aduana" name="numero_aduana" require placeholder="Introduce  Numero Aduana">

                            <label class="align-text-bottom form-label">Denominación Aduana</label>
                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+"
                                onblur="validarText(this)" id="denominacion_aduana" name="denominacion_aduana"
                                placeholder="Introduce  Denominación Aduana">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-success" id="guardar_aduana">Guardar
                                    Cambios</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
                        <div class="container-fluid">
                            <table class="table w-100 p-3">
                                <thead>
                                    <tr>
                                        <th scope="col"># ADUANA</th>
                                        <th scope="col">DENOMINACION ADUANA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>MarkDSADSAD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL FIN ADUANA -->

        <!-- MODAL CONTENEDORES-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  text-center">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary text-white  text-center">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">ASOCIAR
                            CONTENEDOR</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#">
                        <div class="modal-body">
                            <label class="display-5">Contenedor Principal</label><br><br>
                            <label class="blockquote-footer text-center"># Pedimento</label> <br><br>
                            <label class="blockquote-footer text-center"># BL</label> <br><br>
                            <label class="align-text-bottom form-label">Contenedor Asociado</label>
                            <input type="text" class="align-text-bottom  form-control"
                                placeholder="Introduce # Contenedor Asociado">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONTENEDORES -->

        <!-- MODAL FACTURAS -->
        <div class="modal fade" id="modalFacturas" tabindex="-1" z-index="2" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">ASOCIAR
                            FACTURAS</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_factura.php">
                            <label class="display-5">Facturas</label><br><br>
                            <div class="row">
                                <div class="col">
                                    <label class="blockquote-footer text-center"># Pedimento</label> <br>
                                    <label class="blockquote-footer text-center"># Contenedor</label> <br>
                                </div>
                                <div class="col">
                                    <label class="blockquote-footer text-center"># Económico</label> <br>
                                    <label class="blockquote-footer text-center"># BL</label><br>
                                </div>
                            </div>
                            <label class="align-text-bottom form-label">Buscar Factura</label>
                            <input type="text" class="align-text-bottom form-control" name="num_factura"
                                id="num_factura" placeholder="Introduce # factura">
                            <label class="align-text-bottom form-label">Descripción de Factura</label>
                            <input type="text" class="align-text-bottom form-control" name="concepto" id="concepto"
                                placeholder="Introduce Concepto" disabled>
                            <label class="align-text-bottom form-label">Fecha de Factura</label>
                            <input type="date" class="align-text-bottom form-control" name="fecha_factura"
                                id="fecha_factura" disabled>
                            <label class="align-text-bottom form-label">Proveedor</label><br>
                            <select class="align-text-bottom form-control" name="proveedor_factura"
                                id="proveedor_factura" disabled>
                                <option value="">Selecciona una opción</option>
                                <?php 
                                foreach ($result_cat_proveedor as $valores):
                                    echo '<option value="'.$valores["proveedor"].'">'.$valores["proveedor"].'</option>';
                                endforeach; ?>
                            </select>
                            <label class="align-text-bottom form-label">Valor de Factura</label>
                            <input type="text" class="align-text-bottom form-control" name="val_factura"
                                id="val_factura" placeholder="Introduce Valor 00.00" disabled>
                            <div class="row">
                                <div class="col">
                                    <label class="align-text-bottom form-label">Asociar Contenedor</label>
                                    <input type="text" class="align-text-bottom form-control" name="contenedor_asociado"
                                        id="contenedor_asociado" placeholder="">
                                </div>
                                <div class="col">
                                    <label class="align-text-bottom form-label">Asociar BL</label>
                                    <input type="text" class="align-text-bottom form-control" name="bl_asociado"
                                        id="bl_asociado" placeholder="">
                                </div>
                            </div>
                            <label class="align-text-bottom form-label">Referencia Nexen</label>
                            <input type="text" class="align-text-bottom form-control" placeholder="">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Imprimir</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Buscar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-success">Asociar Factura</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
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
                </div>
            </div>
        </div>
        <!-- FIN MODAL FACTURAS -->

        <!-- EJEMPLO MODAL CARGAR FACTURA Y ASOCIAR (MODAL DOBLE) -->
        <div class="modal fade" id="modalCargarFacturas" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-xl text-center">
                <div class="modal-content text-center">
                    <div class="modal-header bg-primary  text-white  text-center ">
                        <label class="modal-title  text-wrap text-center " style="width: 50rem;"
                            id="exampleModalLabel">CARGAR FACTURAS</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <div class="form-table-consulta">
                            <h1>CARGAR FACTURA</h1>
                        </div>
                        <!--<form action="../include/guardar_factura.php" method="POST">-->
                            <!-- <div class="form-tabla-contenedor">  -->
                            <div class="row my-1">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8 ">
                                    <h6 class="text-center">Proveedor</h6>
                                    <select class="align-text-bottom form-control" id="proveedor_fact"
                                        name="proveedor_fact">
                                        <option value="">Selecciona una opción</option>
                                        <?php 
                                        foreach ($result_cat_proveedor as $valores):
                                            echo '<option value="'.$valores["proveedor"].'">'.$valores["proveedor"].'</option>';
                                            $taxid = $valores["codigo"];
                                        endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-2 "></div>
                            </div>
                            <br>
                            <div class="row my-1">
                                <div class="col-sm-4">
                                    <h6 class="text-center">Número de Factura</h6>
                                    <input type="text" class="align-text-bottom form-control" id="modal_num_factura"
                                        name="num_factura" placeholder="Introduce # factura" required>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="text-center">Fecha de Factura</h6>
                                    <input type="date" class="align-text-bottom form-control" id="modal_fecha_factura"
                                        name="fecha_factura" required value="">
                                </div>
                                <div class="col-sm-4 ">
                                    <h6 class="text-center">TAX-ID</h6>
                                    <input type="text" class="align-text-bottom form-control" name="tax_id" id="tax_id"
                                        value="<?php echo $taxid ?> " required>
                                </div>
                            </div>
                            <div class="row my-1">
                                <div class="col-sm-8">
                                    <h6 class="text-center">Operador</h6>
                                    <input type="text" class="form-control" value=""
                                        id="modal_nombre_operador" name="nombre_operador" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="text-center">RFC</h6>
                                    <input type="text" class="align-text-bottom form-control" id="modal_rfc_operador"
                                        name="modal_rfc_operador" placeholder="" disabled value="">
                                </div>
                            </div>
                            <div class="row my-1">
                                <div class="col">
                                    <h6 class="text-center">Domicilio Fiscal Operador</h6>
                                    <input type="text" class="align-text-bottom form-control" id="modal_domicilio_operador"
                                        name="modal_domicilio_operador" placeholder="" disabled value="">
                                </div>
                            </div>
                            <div class="row my-1">
                                <div class="col-sm-12">
                                    <h6 class="text-center">Descripción Cove</h6>
                                    <textarea rows="" class="align-text-bottom form-control" id="desc_factura" name="desc_factura" cols=""
                                        required></textarea>
                                    <!-- <input type="tex"  class="align-text-bottom form-control"> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 ">
                                    <h6 class="text-center">Cantidad</h6>
                                    <input type="number" class="align-text-bottom form-control" id="modal_cantidad" name="modal_valor_factura"
                                        placeholder="Introduce cantidad">
                                </div>
                                <div class="col-sm-2">
                                    <h6 class="text-center">Medida</h6>
                                    <select class="form-control" id="medida" name="medida">
                                        <?php
                                        try {
                                            // Consulta los datos de la tabla medidas
                                            $stmt = $conn_bd->prepare("SELECT TOP (1000) [Id_medida], [Medida], [Estatus] FROM [dbo].[medidas]");
                                            $stmt->execute();

                                            // Muestra las opciones del select con los valores obtenidos
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='".$row['Medida']."'>".$row['Medida']."</option>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3 ">
                                    <h6 class="text-center">Precio Unitario</h6>
                                    <input type="number" class="align-text-bottom form-control" id="precio_unitario" name="precio_unitario"
                                        placeholder="Introduce el Precio Unitario 00.00">
                                </div>
                                <div class="col-sm-2 ">
                                    <h6 class="text-center">Moneda</h6>
                                    <select class="form-control" id="modal_moneda" name="modal_moneda">
                                        <?php
                                        try {
                                            // Consulta los datos de la tabla medidas
                                            $stmt = $conn_bd->prepare("SELECT TOP (1000) * FROM [dbo].[MONEDAS]");
                                            $stmt->execute();

                                            // Muestra las opciones del select con los valores obtenidos
                                            while ($row2 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='".$row2['PREFIJO']."'>".$row2['PREFIJO']."</option>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-2 ">
                                    <h6 class="text-center">Precio Total</h6>
                                    <input type="number" class="align-text-bottom form-control" id="precio_total" name="precio_total"
                                        placeholder="Total" disabled>
                                </div>
                            </div>
                            <!-- </div> -->
                            <div class="row my-1">
                                <div class="col-sm-3 "></div>
                                <div class="col-sm-6 text-center">
                                    <button type="button" class="btn btn-primary" id="btnAgregar">Agregar</button>
                                </div>
                                <div class="col-sm-3 "></div>
                            </div>
                            <br>
                            <div class="embed-responsive">
                                <div class="container-fluid">
                                    <table class="table w-100 p-3" id="tablaFacturas">
                                        <thead>
                                            <tr>
                                                <th scope="col"># FACTURA</th>
                                                <th scope="col">DESCRIPCIÓN COVE</th>
                                                <th scope="col">CANTIDAD</th>
                                                <th scope="col">UNIDAD</th>
                                                <th scope="col">VALOR UNITARIO</th>
                                                <th scope="col">MONEDA</th>
                                                <th scope="col">ESTATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Aquí se agregarán las filas dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col" colspan="2">Total:</th>
                                                <th scope="col" id="total" value="0">0</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            </div><br><br>
                            <div class="modal-footer ">
                                <button type="button" class="btn btn-lg btn-light ">
                                    <i class="bi bi-printer-fill"></i> Imprimir Factura
                                </button>
                                <button type="button" class="btn btn-lg btn-light">
                                    <i class="bi bi-printer"></i> Imprimir Packing List
                                </button>
                                <div class="boton-modal">
                                    <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalToggle2">
                                        <label for="btn-modal">Asociar Facturas
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="red" A stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </label>
                                    </button>
                                </div>
                                <button type="button" class="btn btn-lg btn-danger">Cancelar</button>
                                <button type="button" class="btn btn-lg btn-success" onclick="enviarSolicitudAjax()">Guardar</button>
                            </div>
                    </div>
                    <!--</form>-->
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
            tabindex="-1">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">ASOCIAR
                            FACTURAS</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="include/guardar_factura.php">
                            <label class="display-5">Facturas</label><br><br>
                            <div class="row">
                                <div class="col">
                                    <label class="blockquote-footer text-center"># Pedimento</label> <br>
                                    <label class="blockquote-footer text-center"># Contenedor</label> <br>
                                </div>
                                <div class="col">
                                    <label class="blockquote-footer text-center"># Económico</label> <br>
                                    <label class="blockquote-footer text-center"># BL</label> <br>
                                </div>
                            </div>
                            <label class="align-text-bottom form-label">Buscar Factura</label>
                            <input type="text" class="align-text-bottom form-control" id="num_factura"
                                name="num_factura" placeholder="Introduce # factura">
                            <label class="align-text-bottom form-label">Descripción de Factura</label>
                            <input type="text" class="align-text-bottom form-control" id="concepto" name="concepto"
                                placeholder="Introduce Concepto" disabled>
                            <label class="align-text-bottom form-label">Fecha de Factura</label>
                            <input type="date" class="align-text-bottom form-control" id="fecha_factura"
                                name="fecha_factura" disabled>
                            <label class="align-text-bottom form-label">Proveedor</label><br>
                            <select class="align-text-bottom form-control" id="proveedor_factura"
                                name="proveedor_factura" disabled>
                                <option value="">Selecciona una opción</option>
                                <?php 
                                foreach ($result_cat_proveedor as $valores):
                                    echo '<option value="'.$valores["proveedor"].'">'.$valores["proveedor"].'</option>';
                                endforeach; ?>
                            </select>
                            <label class="align-text-bottom form-label">Valor de Factura</label>
                            <input type="text" class="align-text-bottom form-control" id="valor_factura"
                                name="valor_factura" placeholder="Introduce Valor 00.00" disabled>
                            <label class="align-text-bottom form-label">Asociar Contenedor</label>
                            <input type="text" class="align-text-bottom form-control" id="contenedor_asociado"
                                name="contenedor_asociado" placeholder="">
                            <label class="align-text-bottom form-label">Asociar BL</label>
                            <input type="text" class="align-text-bottom form-control" id="bl_asociado"
                                name="bl_asociado" placeholder="">
                            <label class="align-text-bottom form-label">Referencia Nexen</label>
                            <input type="text" class="align-text-bottom form-control" id="referencia_nexen"
                                name="referencia_nexen" placeholder="">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Imprimir</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Buscar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-success">Asociar Factura</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
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
                </div>
            </div>
        </div>

        <!-- FIN EJEMPLO CARGAR FACTURA Y ASOCIAR (MODAL DOBLE) -->

        <!-- MODAL PROVEEDOR -->
        <div class="modal fade" id="modal-proveedor" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;"
                            id="exampleModalLabel">PROVEEDORES</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="../include/guardar_proveedor.php" class="form-control" method="POST">
                            <label class="display-5">Proveedor</label><br><br>
                            <label class="align-text-bottom form-label">Tax-ID</label>
                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z0-9]+"
                                onblur="validarTextNumber(this)" id="tax_id" name="tax_id"
                                placeholder="Introduce Código TAX-ID">
                            <label class="align-text-bottom form-label">Proveedor</label>
                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+"
                                onblur="validarText(this)" id="proveedor" name="proveedor"
                                placeholder="Introduce Nombre Proveedor">
                            <label class="align-text-bottom form-label">Domicilio</label>
                            <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z0-9]+"
                                onblur="validarTextNumber(this)" id="domicilio" name="domicilio"
                                placeholder=" Introduce Domicilio ">
                            <label class="align-text-bottom form-label">Correo</label>
                            <input type="email" class="align-text-bottom  form-control" id="email" name="email"
                                placeholder="Introduce Correo">
                            <label class="align-text-bottom form-label">Whatsapp</label>
                            <input type="text" class="align-text-bottom  form-control" pattern="\d+"
                                onblur="validarNumber(this)" id="whatsapp" name="whatsapp"
                                placeholder="Introduce Número Whatsapp">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" id="guardar_proveedor" class="btn btn-success">Guardar
                                    Cambios</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
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
                </div>
            </div>
        </div>
        <!-- FIN MODAL PROVEEDOR -->

        <!-- MODAL TIPO TRAFICO -->
        <div class="modal fade" id="modal-tipo-trafico" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header   bg-primary   text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel"> Catalago
                            Tipo Tráfico</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-dialog-scrollable">
                        <form action="include/guardar_tipo_trafico.php">
                            <label class="display-5"> Tipo Tráfico</label><br><br>
                            <label class="align-text-bottom form-label">Trafico Operación</label>
                            <input type="text" class="align-text-bottom  form-control" id="tipo_trafico"
                                name="tipo_trafico" placeholder="Introduce  El Tipo de Tráfico" required>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-success">Guardar Cambios</button>
                                <button type="button" class="btn btn-success">Actualizar</button>
                            </div>
                        </form>
                    </div>
                    <div class="embed-responsive">
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
                </div>
            </div>
        </div>
        <!-- FIN MODAL TIPO TRAFICO -->


        <!-- MMODAL MONEDA -->
        <div class="modal fade" id="modal-moneda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class=" modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-header bg-primary text-white  text-center ">
                        <label class="modal-title  text-wrap" style="width: 50rem;" id="exampleModalLabel">CATALOGO
                            MONEDA</label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../include/guardar_moneda.php" method="POST" class="form-control">
                        <label class="display-6">Moneda</label>
                        <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+"
                            onblur="validarText(this)" id="moneda" name="moneda"
                            placeholder="Introduce Descripción Moneda" required>
                        <label class="display-6">Prefijo</label>
                        <input type="text" class="align-text-bottom  form-control" pattern="[a-zA-Z]+"
                            onblur="validarText(this)" id="prefijo" name="prefijo" placeholder="Introduce Prefijo"
                            required>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" id="guardar_moneda">Guardar Cambios</button>
                        </div>
                    </form>
                    <div class="embed-responsive">
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
                </div>
            </div>
        </div>

        <!-- FIN MODAL MONEDA -->


        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
        <script type="text/javascript" language="javascript"
            src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" language="javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" language="javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" language="javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" language="javascript"
            src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>



</body>

</html>

<script>
const botonLimpiar = document.getElementById('btn_Notificaion');
const botonArribo = document.getElementById('btn_Arribo');
const botonPedimentp = document.getElementById('btn_pedimento');
const btnmodulacion = document.getElementById('btnmodu');
const btnNexen24 = document.getElementById('btnNex24');

const campoFecha = document.getElementById('fechNotifi');
const Arribo = document.getElementById('fecharribo');
const pedimento = document.getElementById('fechpedimento');
const modulacion = document.getElementById('fechamodulacion');
const fechaNex = document.getElementById('opeNex');

botonLimpiar.addEventListener('click', () => {
    campoFecha.value = '';
});
botonArribo.addEventListener('click', () => {
    Arribo.value = '';
});
botonPedimentp.addEventListener('click', () => {
    pedimento.value = '';
});
btnmodulacion.addEventListener('click', () => {
    modulacion.value = '';
});
btnNexen24.addEventListener('click', () => {
    fechaNex.value = '';
});
</script>

<style type="text/css">
.campo_rojo {
    border: 2px solid red;
}

.campo_verde {
    border: 2px solid green;
}
</style>


<script>
const soloTexto = /^[a-zA-Z]+$/; //Expresión regular que solo permite letras
const soloNumeros = /^[0-9]+$/; //Expresión regular que solo permite números
const soloNumerosYLetras =
    /^[a-zA-Z0-9\s!@#$%^&*(),.?":{}|<>_+-=\\/\[\]]*$/; //Expresión regular que solo permite números y letras
const isValid = /^[^'"]*$/.test(value) && /^[a-zA-Z0-9\s!@#$%^&*(),.?":{}|<>_+-=\\/\[\]]*$/.test(value);

const validarText = (input) => {
    if (soloTexto.test(input.value)) {
        document.getElementById("guardar_estatus").disabled = false;
        document.getElementById("guardar_proveedor").disabled = false;
        document.getElementById("guardar_ope").disabled = false;
        document.getElementById("guardar_aduana").disabled = false;
        document.getElementById("guardar_moneda").disabled = false;
        input.classList.remove("campo_rojo");
        input.classList.add("campo_verde");
    } else {
        document.getElementById("guardar_estatus").disabled = true;
        document.getElementById("guardar_proveedor").disabled = true;
        document.getElementById("guardar_aduana").disabled = true;
        document.getElementById("guardar_ope").disabled = true;
        document.getElementById("guardar_moneda").disabled = true;
        input.classList.remove("campo_verde");
        input.classList.add("campo_rojo");
    }
};


const validarNumber = (input) => {
    if (soloNumeros.test(input.value)) {
        document.getElementById("guardar_proveedor").disabled = false;
        document.getElementById("guardar_aduana").disabled = false;
        input.classList.remove("campo_rojo");
        input.classList.add("campo_verde");

    } else {
        document.getElementById("guardar_proveedor").disabled = true;
        document.getElementById("guardar_aduana").disabled = true;
        input.classList.remove("campo_verde");
        input.classList.add("campo_rojo");
    }
};


const validarTextNumber = (input) => {
    if (soloNumerosYLetras.test(input.value)) {
        document.getElementById("guardar_proveedor").disabled = false;
        input.classList.remove("campo_rojo");
        input.classList.add("campo_verde");
    } else {
        document.getElementById("guardar_proveedor").disabled = true;
        input.classList.remove("campo_verde");
        input.classList.add("campo_rojo");
    }
};
</script>
<script>
function quitarComillas(elemento) {
    var valor = elemento.value;
    valor = valor.replace(/["']/g, ''); // reemplazar comillas dobles y simples con una cadena vacía
    elemento.value = valor;
}
</script>


<script type="text/javascript">
function obtener_codigo_aduana() {
    var codigo = $('#codigo_aduana').val();
    console.log(codigo);
    //document.getElementById("cve_aduana").value=codigo ;
    $('#cve_aduana').text(codigo);
}
</script>

<script src="../utils/functions.js"></script>
