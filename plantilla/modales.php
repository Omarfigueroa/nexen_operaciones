        <div class="modal" id="cerrarSesion" data-bs-backdrop="static" tabindex="-1" aria-labelledby="cerrarSesionLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCerrarModalReporte">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnDescargarReporteDocsFaltantes">Descargar</button>
                    </div>
                </div>
            </div>
        </div>