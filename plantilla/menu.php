<?php

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['usuario_nexen'])) {
        header('Location: ../view/login.php');
    }
}

$usuario = $_SESSION['usuario_nexen'];
$sql_usuario = "SELECT RTRIM(LTRIM(area)) area, RTRIM(LTRIM(tipo_usuario)) tipo_usuario FROM Usuarios_Login_Web WHERE RTRIM(LTRIM(Usuario))=RTRIM(LTRIM('" . $usuario . "'))";

try {

    $tipo_usuario = $conn_bd->prepare($sql_usuario);
    $tipo_usuario->execute();
    $area_tipo = $tipo_usuario->fetch(PDO::FETCH_ASSOC);

    if ($area_tipo) {
        $area = $area_tipo['area'];
    } else {
        $area = '';
    }
} catch (PDOException $e) {
    echo "Error consultar tipo usuario";
}
?>
<header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light ">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a href="../index.php" class="navbar-brand d-flex align-items-center">
                    <img class="img-fluid " src="../img/logoNexen.png" alt="">
                </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <?php if ($area == 'Finanzas' || $area == 'Direccion' || $usuario == 'Admin' || $usuario == 'DIEGE') { ?>

                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 navbar-nav">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle  fw-bold" data-bs-toggle="dropdown">Administración</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="../view/cargar_proveedor_finanzas.php">Cargar Cuentas</a></li>
                                    <li><a class="dropdown-item" href="../views/finanzas/index.php">Solicitud de Pagos</a></li>
                                    <li><a class="dropdown-item" href="../view/pagos_proveedores.php">Pagos Proveedores</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <!-- <a class="nav-link fw-bold" aria-current="page" href="http://158.69.113.62/prealertas_ups/">Prealerta</a>             -->
                                <a class="nav-link fw-bold" aria-current="page" href="https://xtrategas.com.mx/prealertas_ups/">Prealerta</a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle  fw-bold" data-bs-toggle="dropdown">Reportes</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" aria-current="page" id="btnReporteDocsFaltantes" href="#">Documentos faltantes</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" aria-current="page" id="btnReporteCarpetas" href="#">Carpetas digitales</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle  fw-bold" data-bs-toggle="dropdown">Psicométrico</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="">Operaciones</a></li>
                                <li><a class="dropdown-item" href="">Finanzas</a></li>
                                <li><a class="dropdown-item" href="">Contabilidad</a></li>
                                <li><a class="dropdown-item" href="">Sistemas</a></li>
                                <li><a class="dropdown-item" href="">Desarrollo</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" aria-current="page" href="permisos.php">Permisos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" aria-current="page" href="nosotros.html">Clima</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" aria-current="page" href="servicios.html">Salida</a>
                        </li> -->
                        </ul>

                    <?php } ?>

                    <ul class="nav navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <label class="fw-bold"> <?php if (isset($usuario)) {
                                                            echo $usuario;
                                                        } ?></label>
                                                           <img src="../img/usuario.png" alt="" style="width: 35px; height:35px;">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item">Ajustes</a>
                                <div class="dropdown-divider">
                                </div>
                                <a href="../view/logout.php" class="dropdown-item">Cerrar Sesión</a>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
  
</header>

<?php require('../plantilla/modales.php'); ?>

<script>
    var modalAbierto = false; // Variable para llevar el registro si el modal ya se abrió o no
    var totalRegistrosAnterior = 0; // Variable para almacenar el valor anterior de totalRegistros

    function alerta_pagos() {
        // Realizar la solicitud AJAX para obtener los datos actualizados
        $.ajax({
            type: "GET",
            url: "../include/getAlerta_Pagos.php",
            data: "data",
            dataType: "json",
            success: function(response) {
                if (response.success && response.totalRegistros > 0) {
                    // Verificar si hay nuevos registros comparando con el valor anterior
                    if (!modalAbierto || response.totalRegistros > totalRegistrosAnterior) {
                        // Construimos el contenido que se mostrará en SweetAlert2

                        let contenido = '<table class="table">';
                        contenido += '<thead>';
                        contenido += '<tr>';
                        contenido += '<th scope="col">Referencia Nexen</th>';
                        contenido += '<th scope="col">Cliente</th>';
                        contenido += '<th scope="col">Razon social receptora</th>';
                        contenido += '<th scope="col">Concepto</th>';
                        contenido += '<th scope="col">Monto</th>';
                        contenido += '<th scope="col">Estatus</th>';
                        contenido += '<th scope="col">Acción</th>';
                        contenido += '</tr>';
                        contenido += '</thead>';
                        contenido += '<tbody>';

                        response.data.forEach(registro => {
                            contenido += '<tr>';
                            contenido += '<td>' + registro.Referencia_Nexen + '</td>';
                            contenido += '<td>' + registro.Cliente + '</td>';
                            contenido += '<td>' + registro.Razon_Social_Receptora + '</td>';
                            contenido += '<td>' + registro.Concepto + '</td>';
                            contenido += '<td>' + registro.Monto + '</td>';
                            contenido += '<td>' + (registro.Estatus === 'ACEPTADO' ? '<h5><span class="badge bg-success">ACEPTADO</span></h5>' : '<h5><span class="badge bg-danger">RECHAZADO</span></h5>') + '</td>';
                            contenido += '<td><a type="button" onclick="updateStatusPago(' + registro.Num_Operacion + ')" class="btn btn-primary">Check</a></td>'; // Puedes personalizar cómo se muestra cada registro
                            contenido += '</tr>';
                        });

                        contenido += '</tbody>';
                        contenido += '</table>';



                        // Mostramos los datos en SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Registros encontrados',
                            html: contenido,
                            confirmButtonText: 'Cerrar',
                            width: '90%'
                        });

                        modalAbierto = true; // Marcamos que el modal se ha abierto
                        totalRegistrosAnterior = response.totalRegistros; // Actualizamos el valor anterior
                    }
                }
            }
        });
    }

    // Ejecutar la función cada 5 segundos para comprobar si hay nuevos registros
    setInterval(alerta_pagos, 5000); // Actualizar cada 5 segundos

    //Para cambiar estatus de pago
    function updateStatusPago(Num_Operacion) {
        console.log(Num_Operacion);
        $.ajax({
            type: "POST",
            url: "../include/setAlertaEstatusPago.php",
            data: {
                Num_Operacion: Num_Operacion
            },
            dataType: "json",
            success: function(response) {
                alert('Solicitud de pago CHECK!');
                if (Swal) {
                    Swal.close();
                    modalAbierto = false; // Marcamos que el modal ha sido cerrado automáticamente
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", status, error);
            }
        });
    }
</script>

<?php
/*
} else{
    echo'<script type="text/javascript">
        alert("Se requiere iniciar sesion");
    </script>';
}
*/
?>