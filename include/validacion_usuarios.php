<?php
    session_start();
    $session_id=session_id();

    require_once($_SERVER['DOCUMENT_ROOT'].'/nexen_operaciones/include/config.php');
    require_once (CONEXION_PATH.'bd.php');

	
    if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
            $usuario = $_POST['username'];
            $password = $_POST['password'];
            
            $query_user="SELECT * FROM [dbo].[Usuarios_Login] 
                         WHERE Usuario ='$usuario'";

            $consultar_user=$conn_bd->prepare($query_user);
            $consultar_user -> execute();
            $results_user = $consultar_user -> fetchAll(PDO::FETCH_ASSOC);
        
                if($consultar_user -> rowCount() > 0){
                   
                    foreach ($results_user as $row) {
                        $_SESSION['usuario_nexen']=$row['Usuario'];
                        $pass = $row['Contraseña'];

                        if($password == $pass){
                            //echo '<meta http-equiv="REFRESH" content="0;url=../nexen_operaciones.php">';
                            
                            //Verifica si existe sesion previa del usuario
                            $select_exis_sesion = "SELECT TOP 1 * FROM [dbo].[Conexión_Usuario] WHERE Usuario = '$usuario' AND Estatus=1";
                            
                            $buscar_sesion = $conn_bd->prepare($select_exis_sesion);
                            $buscar_sesion -> execute();
                            $results_sesion = $buscar_sesion -> fetch(PDO::FETCH_ASSOC);
                            
                            if($results_sesion){

                                //if($results_sesion['Sesion_Id']!=$session_id){

                                    // echo '<script type="text/javascript">
                                    //         alert("Ya existe una sesión abierta, se cerraran todas las demas abierta en otras PC");
                                    //       </script>';
                                    
                                    //echo '<meta http-equiv="REFRESH" content="0;url=../view/login.php">';
                                    
                                    $user_free = "UPDATE [Conexión_Usuario] SET Estatus=0 WHERE Usuario = '$usuario'";
                                    $free = $conn_bd->prepare($user_free);
                                    $free -> execute();

                                    $insert=$conn_bd->prepare("INSERT INTO [dbo].[Conexión_Usuario] (Usuario, Fecha_Conexion, Hora_Conexion, Estatus, Sesion_Id) VALUES('$usuario', GETDATE(), GETDATE(), 1, '$session_id')");
                                    if($insert->execute()){
                                        //echo '<meta http-equiv="REFRESH" content="0;url=../view/operaciones.php">';
                                        header('Location: '.ruta_relativa().'view/operaciones.php');

                                    }else{
                                        echo'<script type="text/javascript">
                                            alert("No se pudo insertar en Log.");
                                        </script>';
                                    }

                                //}else{

                                    //echo '<meta http-equiv="REFRESH" content="0;url=../view/login.php">';
                                    //header('Location: '.ruta_relativa().'view/login.php');
                                
                                //}

                            }else{

                                $insert=$conn_bd->prepare("INSERT INTO [dbo].[Conexión_Usuario] (Usuario, Fecha_Conexion, Hora_Conexion, Estatus, Sesion_Id) VALUES('$usuario', GETDATE(), GETDATE(), 1, '$session_id')");
                                if($insert->execute()){
                                    /*
                                    echo'<script type="text/javascript">
                                        alert("Inseratdo en Log.");
                                    </script>';
                                    */
                                    //echo '<meta http-equiv="REFRESH" content="0;url=../view/operaciones.php">';
                                    header('Location: '.ruta_relativa().'view/operaciones.php');
                                }else{
                                    echo'<script type="text/javascript">
                                        alert("No se pudo insertar en Log.");
                                    </script>';
                                }
                            }

                        }else{
                            $_SESSION['usuario_nexen']="";
                            unset($_SESSION['usuario_nexen']);
                            //echo'<script type="text/javascript">alert("USUARIO O CONTRASEÑA INCORRECTO");</script>';
                            $_SESSION['msj_alerta']='Usuario o contraseña incorrecto';
                            //echo '<meta http-equiv="REFRESH" content="0;url=../view/login.php">';
                            header('Location: '.ruta_relativa().'view/operaciones.php');
                        }
                    }
                }else{
                    // echo'<script type="text/javascript">
                    // alert("EL USUARIO NO EXISTE");
                    // </script>';
                    $_SESSION['msj_alerta']='Usuario o contraseña incorrecto';
                    //echo '<meta http-equiv="REFRESH" content="0;url=../view/login.php">';
                    header('Location: '.ruta_relativa().'view/login.php');
                }
	}else{
       
    }
?> 