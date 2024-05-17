        <div class="modal" id="cerrarSesion" data-bs-backdrop="static" tabindex="-1" aria-labelledby="cerrarSesionLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cerrarSesionLabel">Sesion Cerrada</h5>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    </div>
                    <div class="modal-body">
                        Sesion Cerrada por Inactividad
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-primary">Cerrar</button> -->
                        <a href="login.php" class="btn btn-primary" role="button">
                            Salir <i class="bi bi-check-square text-light"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para el reporte de documentos faltantes -->
        <div id="modalReporteDocsFaltantes" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reporte de documentos faltantes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formReporteDocsFaltantes">
                            <div class="mb-3 form-floating">
                                <select class="form-select" name="mes" aria-label="Mes del reporte">
                                    <option value="" selected>Seleccionar</option>
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                                <label for="floatingSelect">Mes</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <select class="form-select" name="anio" aria-label="Año del reporte">
                                    <option value="" selected>Seleccionar</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                </select>
                                <label for="floatingSelect">Año</label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="btnCerrarModalReporte">Cerrar</button>
                        <button type="button" class="btn btn-primary"
                            id="btnDescargarReporteDocsFaltantes">Descargar</button>
                    </div>
                </div>
            </div>
        </div>


        <!--MODAL VER FACTURAS Y DETALLE FACTURAS (MODAL DOBLE)-->
        <div class="modal fade" id="modalVerFacturas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header nft">
                        <h5 class="modal-title" id="staticBackdropLabel">Ver Facturas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="loadingMessage" style="display: none;">Cargando...</div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table id="tablefacturas" class="table text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Referencia Nexen</th>
                                            <th>Proveedor</th>
                                            <th>Tax_Id</th>
                                            <th>Numero Factura</th>
                                            <th>Fecha Factura</th>
                                            <th>Importador Exportador</th>
                                            <th>Total General</th>
                                            <th>Usuario</th>
                                            <th>Detalles</th>
                                            <th>Invoice</th>
                                            <th>Packing List</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



    <!-- EJEMPLO MODAL CARGAR FACTURA Y ASOCIAR (MODAL DOBLE) -->
    <div class="modal fade" id="modalCargarFacturas" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-xl text-center">
                                <div class="modal-content text-center">
                                    <div class="modal-body modal-dialog-scrollable">
                                        <div class="form-table-consulta">
                                            <h1>CARGAR FACTURAS</h1>
                                        </div>
                                        <!--<form action="../include/guardar_factura.php" method="POST">-->
                                        <!-- <div class="form-tabla-contenedor">  -->
                                        <div class="row my-1">
                                            <div class="col-3">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_proveedor">
                                                    Agregar Proveedor +
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row my-1">
                                            <div class="col-sm-6">
                                                <h6 class="text-center">Proveedor</h6>
                                                <div class="selectprueba">
                                                    <select class="align-text-bottom form-control selectpicker validSelect" data-show-subtext="true" data-live-search="true" id="proveedor_fact" name="proveedor_fact" onchange="cambiarProveedor(this);">
                                                        <option value="" selected disabled>Selecciona una opción</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <h6 class="text-center">Editar proveedor</h6>
                                                <button id="btnEditarProveedor" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modal_editar_proveedor" disabled><i class="bi bi-pen"></i></button>
                                            </div>
                                            <div class="col-sm-4 ">
                                                <h6 class="text-center">País de origen</h6>
                                                <input type="text" class="align-text-bottom form-control validText" id="modal_pais_origen" oninput="quitarAcentosYComillas(this)" name="num_factura" placeholder="Introduce país de origen" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="text-center">Domicilio proveedor</h6>
                                                <input type="text" class="align-text-bottom form-control" id="modal_domicilio_proveedor" name="modal_domicilio_proveedor" placeholder="Introduce proveedor" required disabled>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row my-1">
                                            <div class="col-sm-4">
                                                <h6 class="text-center">Número de Factura</h6>
                                                <input type="text" class="align-text-bottom form-control validFactura" id="modal_num_factura" oninput="quitarAcentosYComillas(this)" name="num_factura" placeholder="Introduce # factura" required>
                                            </div>
                                            <div class="col-sm-4 validando">
                                                <h6 class="text-center">Fecha de Factura</h6>
                                                <input type="date" class="align-text-bottom form-control validDate" id="modal_fecha_factura" name="fecha_factura" required value="">
                                            </div>
                                            <div class="col-sm-2 ">
                                                <h6 class="text-center">TAX-ID</h6>
                                                <input type="text" class="align-text-bottom form-control" name="tax_id" id="tax_id" value="" required disabled placeholder="Escoger proveedor">
                                            </div>
                                            <div class="col-sm-2">
                                                <h6 class="text-center">Incoterms</h6>
                                                <select class="align-text-bottom form-control selectpicker validSelect" id="incoterms" name="incoterms" data-live-search="true">
                                                    <option value="" selected disabled>Selecciona una opción</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row my-1">
                                            <div class="col-sm-8">
                                                <h6 class="text-center">Operador</h6>
                                                <input type="text" class="form-control" value="" id="modal_nombre_operador" name="modal_nombre_operador" disabled>
                                            </div>
                                            <div class="col-sm-4">
                                                <h6 class="text-center">RFC</h6>
                                                <input type="text" class="align-text-bottom form-control" id="modal_rfc_operador" name="modal_rfc_operador" placeholder="" disabled value="">
                                            </div>
                                        </div>
                                        <div class="row my-1">
                                            <div class="col">
                                                <h6 class="text-center">Domicilio Fiscal Operador</h6>
                                                <input type="text" class="align-text-bottom form-control" id="modal_domicilio_operador" name="modal_domicilio_operador" placeholder="" disabled value="">
                                            </div>
                                        </div>
                                        <div class="partidas">
                                            <div class="row my-1">
                                                <div class="col-sm-6 validando">
                                                    <h6 class="text-center">Descripción Cove (Español)</h6>
                                                    <textarea rows="" class="align-text-bottom form-control validDescription" id="desc_factura" oninput="quitarAcentosYComillas(this)" name="desc_factura" cols="" required></textarea>
                                                    <!-- <input type="tex"  class="align-text-bottom form-control"> -->
                                                </div>
                                                <div class="col-sm-6 validando">
                                                    <h6 class="text-center">Cove Description (English)</h6>
                                                    <textarea rows="" class="align-text-bottom form-control validDescription" id="desc_factura_i" oninput="quitarAcentosYComillas(this)" name="desc_factura_i" cols="" required></textarea>
                                                    <!-- <input type="tex"  class="align-text-bottom form-control"> -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3 validando">
                                                    <h6 class="text-center">Cantidad</h6>
                                                    <input type="text" class="align-text-bottom form-control validNumber" id="modal_cantidad" name="modal_valor_factura" placeholder="Introduce cantidad" pattern="^[0-9]+">
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Medida</h6>
                                                    <select class="align-text-bottom form-control selectpicker validSelect" id="medida" name="medida" data-live-search="true">
                                                        <option value="" selected disabled>Selecciona una opción</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <h6 class="text-center">Precio Unitario</h6>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control validNumber" id="precio_unitario" name="precio_unitario" placeholder="Introduce el Precio Unitario 00.00" hidden>
                                                        <input type="text" class="form-control validNumber" id="precio_fishing" name="precio_fishing" placeholder="Introduce el Precio Unitario 00.00" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Moneda</h6>
                                                    <select class="align-text-bottom form-control selectpicker validSelect" id="modal_moneda" name="modal_moneda" data-live-search="true">
                                                        <option value="" selected disabled>Selecciona una opción</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Precio Total</h6>
                                                    <input type="text" class="align-text-bottom form-control validNumber" id="precio_total" name="precio_total" placeholder="Total">
                                                </div>
                                            </div>
                                            <div class="row pt-2 pb-2 justify-content-end">
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Mark</h6>
                                                    <input type="text" class="align-text-bottom form-control validMark" id="modal_mark" oninput="quitarAcentosYComillas(this)" name="modal_mark" placeholder="Mark N/M">
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Peso Bruto</h6>
                                                    <input type="text" min="0" class="align-text-bottom form-control validNumber" id="modal_peso_bruto" name="peso_bruto" placeholder="Bruto">
                                                </div>
                                                <div class="col-sm-2 validando">
                                                    <h6 class="text-center">Peso Neto</h6>
                                                    <input type="text" min="0" class="align-text-bottom form-control validNumber" id="modal_peso_neto" name="peso_neto" placeholder="Neto">
                                                </div>
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
                                        <div class="embed-responsive card">
                                            <div class="container table-responsive card-body">
                                                <table class="table w-100 p-3 table-responsive table-scrollable table-striped table-hover" id="tablaFacturas">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"># FACTURA</th>
                                                            <th scope="col">DESCRIPCIÓN COVE</th>
                                                            <th scope="col">COVE DESCRIPTION</th>
                                                            <th scope="col">CANTIDAD</th>
                                                            <th scope="col">UNIDAD</th>
                                                            <th scope="col">VALOR UNITARIO</th>
                                                            <th scope="col">MONEDA</th>
                                                            <th scope="col">TOTAL PARTIDA</th>
                                                            <th scope="col">PESO BRUTO</th>
                                                            <th scope="col">PESO NETO</th>
                                                            <th scope="col">MARK</th>
                                                            <th scope="col">ACCION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Aquí se agregarán las filas dinámicamente -->
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>

                                                            <th scope="col">Total:</th>
                                                            <th scope="row"></th>
                                                            <th scope="col">
                                                                <span id="total">0</span>
                                                            </th>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>
                                                            <th scope="col" id="total_peso_bruto" value="0">0</th>
                                                            <th scope="col" id="total_peso_neto" value="0">0</th>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div><br><br>
                                    <div class="modal-footer ">
                                        <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                        <button id="btnGuardarFacturas" type="button" class="btn btn-lg btn-success" onclick="enviarSolicitudAjax()">
                                            <span id="spinner_insert" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                                <!--</form>-->
                            </div>
                        </div>