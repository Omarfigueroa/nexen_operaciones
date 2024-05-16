<?php
    if(!isset($_SESSION)){  
        session_start();
    }
    
    if (isset($_SESSION['msj_alerta']) && !empty($_SESSION['msj_alerta'])){
        $mensaje=$_SESSION['msj_alerta'];
    }

    require_once $_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php';
    include (VIEW_PATH.'logoutsr.php');
?>
<!DOCTYPE html> 
<html lang="en">
<head> 
    <title>Buscar Operaciones</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        function mostrarPassword(){
            var cambio = document.getElementById("txtPassword");        
            if(cambio.type == "password"){ 
                cambio.type = "text"; 
                document.getElementById("icono").className = "bi bi-eye";
            }else{
                cambio.type = "password";
                document.getElementById("icono").className = "bi bi-eye-slash";
            }
        } 
    </script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4"> 
                <div class="card my-5">
                    <form action="../include/validacion_usuarios.php" method="POST" class="card-body cardbody-color p-lg-4">
                        <div class="text-center">
                            <img src="../img/logoNexen.png" class="img-fluid my-3" alt="profile">
                        </div>
                        <?php
                        if (isset($mensaje)){
                        ?>
						
                        <div class="text-center my-4 alert alert-danger">
                            <?php echo $mensaje;?> 
                        </div>
                        <?php
                        unset($mensaje);
                        }
                        ?>
						<div class="text-center my-4">
							<h1>Operaciones</h1>
						</div>
                        <div class="text-center my-4">
                            <h5>Ingresa tus Credenciales</h5>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" placeholder="Usuario" required>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="txtPassword" name="password" placeholder="Contraseña" required>
                            <div class="input-group-append">
                                <button id="show_password" class="btn btn-primary" type="button" onclick="mostrarPassword()">
                                    <i class="bi bi-eye-slash icon" id="icono"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="form-control btn btn-primary rounded submit px-5">Iniciar Sesión</button>
                        </div>
                        <div class="mb-3 my-4 text-center">
                            <p>¿Olvidaste tu contraseña?</p>
					        <a href="#" >Recuperar Contraseña</a>
                            <p>¿No tienes cuenta?</p>
					        <a href="#" >Crear cuenta</a>
                        </div>
                        <div class="text-center pt-1 text-muted">
                            Copyrights All Rights Reserved <br>Nexen E-logistics  &copy;  2022
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>