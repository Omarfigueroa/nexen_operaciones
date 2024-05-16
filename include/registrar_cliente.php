<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/nexen_operaciones/include/config.php';
// require_once(INCLUDE_PATH . 'validar_sesiones.php');
// require_once(INCLUDE_PATH . 'functions.php');
require_once(CONEXION_PATH . 'bd.php');

date_default_timezone_set('America/Mexico_City');

session_start();

/**
 * Validar si los datos vienen en raw o en Form Data.
 */
if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $rawData = file_get_contents('php://input');
    $_POST = json_decode($rawData, true);
}

function registrar_cliente()
{
    global $conn_bd;

    $usuario = isset($_SESSION['usuario_nexen']) ? $_SESSION['usuario_nexen'] : '';
    if (empty($usuario)) {
        echo json_encode([
            'success' => false,
            'message' => 'La sesión ha caducado'
        ]);
        exit;
    };

    $fechope = date('Y-m-d');
    $horaope = date('H:i:s');

    $Id_cliente = isset($_POST['Id_cliente']) ? $_POST['Id_cliente'] : '';
    $RAZON_SOCIAL = $_POST['RAZON_SOCIAL'];
    $RFC = $_POST['RFC'];
    $TELEFONO = $_POST['TELEFONO'];
    $MOVIL = $_POST['MOVIL'];
    $CONTACTO = $_POST['CONTACTO'];
    $EMAIL_1 = $_POST['EMAIL_1'];
    $EMAIL_2 = $_POST['EMAIL_2'];
    $Domicilio_Fisico = $_POST['Domicilio_Fisico'];
    $Pais = $_POST['Pais'];
    $Codigo_Postal = $_POST['Codigo_Postal'];
    $Estado = $_POST['Estado'];
    $Delegacion_Municipio = $_POST['Delegacion_Municipio'];
    $Referencia = $_POST['Referencia'];
    $tipo_cliente = $_POST['tipo_cliente'];
    $usuarioSup = $_POST['usuarioSupervisor'];
    $passSup = $_POST['contrasenaSupervisor'];

    $contrasenaOK = validarContrasena($usuarioSup, $passSup);
    if (!$contrasenaOK['success']) {
        echo json_encode($contrasenaOK);
        exit;
    }


    try {
        $sqlid = "SELECT TOP(1) id FROM Clientes ORDER BY id DESC";
        $result = $conn_bd->query($sqlid);
        $next_id = intval($result->fetch()['id']) + 1;

        $sqlClientes = "INSERT INTO Clientes
                ([RAZON SOCIAL ], [RFC ], TELEFONO, [MOVIL ], CONTACTO, [EMAIL 1], [EMAIL 2], Domilio_Fisico,
                Pais, Codigo_Postal, Estado, Delegacion_Municipio, [REF_NEXEN], tipo_cliente, [Usuario_Web], Estatus, id)
            VALUES (:razon_social, :rfc, :telefono, :movil, :contacto, :email_1, :email_2, :domicilio_publico,
                :pais, :codigo_postal, :estado, :delegacion, :referencia, :tipo_cliente, :usuario, 'A', :id)";


        $stmt = $conn_bd->prepare($sqlClientes);
        $stmt->bindParam(':razon_social', $RAZON_SOCIAL);
        $stmt->bindParam(':rfc', $RFC);
        $stmt->bindParam(':telefono', $TELEFONO);
        $stmt->bindParam(':movil', $MOVIL);
        $stmt->bindParam(':contacto', $CONTACTO);
        $stmt->bindParam(':email_1', $EMAIL_1);
        $stmt->bindParam(':email_2', $EMAIL_2);
        $stmt->bindParam(':domicilio_publico', $Domicilio_Fisico);
        $stmt->bindParam(':pais', $Pais);
        $stmt->bindParam(':codigo_postal', $Codigo_Postal);
        $stmt->bindParam(':estado', $Estado);
        $stmt->bindParam(':delegacion', $Delegacion_Municipio);
        $stmt->bindParam(':referencia', $Referencia);
        $stmt->bindParam(':tipo_cliente', $tipo_cliente);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':id', $next_id);
        // $stmt->bindParam(':fechope', $fechope);
        // $stmt->bindParam(':horaope', $horaope);
        $resultado = $stmt->execute();

        $sqlClientes1 = "INSERT INTO Clientes1
                (RAZON_SOCIAL, RFC, TELEFONO, MOVIL, CONTACTO, EMAIL_1, EMAIL_2, Domicilio_Fisico,
                Pais, Codigo_Postal, Estado, Delegacion_Municipio, Referencia, tipo_cliente, Usuario, fechope, horaope)
            VALUES (:razon_social, :rfc, :telefono, :movil, :contacto, :email_1, :email_2, :domicilio_publico,
                :pais, :codigo_postal, :estado, :delegacion, :referencia, :tipo_cliente, :usuario, :fechope, :horaope)";

        $stmt = $conn_bd->prepare($sqlClientes1);
        $stmt->bindParam(':razon_social', $RAZON_SOCIAL);
        $stmt->bindParam(':rfc', $RFC);
        $stmt->bindParam(':telefono', $TELEFONO);
        $stmt->bindParam(':movil', $MOVIL);
        $stmt->bindParam(':contacto', $CONTACTO);
        $stmt->bindParam(':email_1', $EMAIL_1);
        $stmt->bindParam(':email_2', $EMAIL_2);
        $stmt->bindParam(':domicilio_publico', $Domicilio_Fisico);
        $stmt->bindParam(':pais', $Pais);
        $stmt->bindParam(':codigo_postal', $Codigo_Postal);
        $stmt->bindParam(':estado', $Estado);
        $stmt->bindParam(':delegacion', $Delegacion_Municipio);
        $stmt->bindParam(':referencia', $Referencia);
        $stmt->bindParam(':tipo_cliente', $tipo_cliente);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':fechope', $fechope);
        $stmt->bindParam(':horaope', $horaope);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Cliente agregado correctamente'
            ]);
        } else {
            throw new Exception('Ocurrió un error al registrar el cliente');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

function actualizar_cliente()
{
    global $conn_bd;

    $Id_cliente = isset($_POST['Id_cliente']) ? $_POST['Id_cliente'] : '';
    $RAZON_SOCIAL = $_POST['RAZON_SOCIAL'];
    $RFC = $_POST['RFC'];
    $TELEFONO = $_POST['TELEFONO'];
    $MOVIL = $_POST['MOVIL'];
    $CONTACTO = $_POST['CONTACTO'];
    $EMAIL_1 = $_POST['EMAIL_1'];
    $EMAIL_2 = $_POST['EMAIL_2'];
    $Domicilio_Fisico = $_POST['Domicilio_Fisico'];
    $Pais = $_POST['Pais'];
    $Codigo_Postal = $_POST['Codigo_Postal'];
    $Estado = $_POST['Estado'];
    $Delegacion_Municipio = $_POST['Delegacion_Municipio'];
    $Referencia = $_POST['Referencia'];
    $tipo_cliente = $_POST['tipo_cliente'];
    $usuarioSup = $_POST['usuarioSupervisor'];
    $passSup = $_POST['contrasenaSupervisor'];
    $REFERENCIA_NEXEN = $_POST['REFERENCIA_NEXEN'];

    $contrasenaOK = validarContrasena($usuarioSup, $passSup);
    if (!$contrasenaOK['success']) {
        echo json_encode($contrasenaOK);
        exit;
    }


    try {
        $sql = "UPDATE Clientes1
                SET
                    RAZON_SOCIAL = :razon_social,
                    RFC = :rfc,
                    TELEFONO = :telefono,
                    MOVIL = :movil,
                    CONTACTO = :contacto,
                    EMAIL_1 = :email_1,
                    EMAIL_2 = :email_2,
                    Domicilio_Fisico = :domicilio_fisico,
                    Pais = :pais,
                    Codigo_Postal = :codigo_postal,
                    Estado = :estado,
                    Delegacion_Municipio = :delegacion,
                    Referencia = :referencia,
                    tipo_cliente = :tipo_cliente
                WHERE Id_cliente = :id_cliente";


        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':razon_social', $RAZON_SOCIAL);
        $stmt->bindParam(':rfc', $RFC);
        $stmt->bindParam(':telefono', $TELEFONO);
        $stmt->bindParam(':movil', $MOVIL);
        $stmt->bindParam(':contacto', $CONTACTO);
        $stmt->bindParam(':email_1', $EMAIL_1);
        $stmt->bindParam(':email_2', $EMAIL_2);
        $stmt->bindParam(':domicilio_fisico', $Domicilio_Fisico);
        $stmt->bindParam(':pais', $Pais);
        $stmt->bindParam(':codigo_postal', $Codigo_Postal);
        $stmt->bindParam(':estado', $Estado);
        $stmt->bindParam(':delegacion', $Delegacion_Municipio);
        $stmt->bindParam(':referencia', $Referencia);
        $stmt->bindParam(':tipo_cliente', $tipo_cliente);
        $stmt->bindParam(':id_cliente', $Id_cliente);
        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Cliente actualizado correctamente'
            ]);

            actualizarClienteOperacionNexen($RAZON_SOCIAL, $REFERENCIA_NEXEN);
        } else {
            throw new Exception('Ocurrió un error al actualizar el cliente');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

function get_cliente($id_cliente)
{
    global $conn_bd;

    try {
        $sql = "SELECT * FROM Clientes1 WHERE Id_cliente = :id_cliente";

        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'data' => $resultado
            ]);
        } else {
            throw new Exception('Ocurrió un error al obtener el cliente');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

function getClienteByRazonSocial($nombre)
{
    global $conn_bd;

    try {
        $sql = "SELECT * FROM Clientes1 WHERE RAZON_SOCIAL = :nombre";

        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'data' => $resultado
            ]);
        } else {
            throw new Exception('Cliente no encontrado');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

function getOperacionByReferencia($referencia)
{
    global $conn_bd;

    try {
        $sql = "SELECT * FROM [Nexen].[dbo].[Operacion_nexen] where REFERENCIA_NEXEN = :referencia";

        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':referencia', $referencia);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'data' => $resultado
            ]);
        } else {
            throw new Exception('Ocurrió un error al obtener la referencia');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

/**
 * Actualiza el campo `Cliente` en la tabla `Operacion_nexen` para que no truene.
 *
 * @param string $nuevo_nombre
 * @return void
 */
function actualizarClienteOperacionNexen($nuevo_nombre, $ref_nexen)
{
    global $conn_bd;

    try {
        $sql = "UPDATE Operacion_nexen
                SET
                    Cliente = :nuevo_nombre
                WHERE REFERENCIA_NEXEN = :ref_nexen";


        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':nuevo_nombre', $nuevo_nombre);
        $stmt->bindParam(':ref_nexen', $ref_nexen);
        $resultado = $stmt->execute();

        if ($resultado) {
            return true;
        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }
}

function validarContrasena($user, $pass)
{
    global $conn_bd;

    try {
        $sql = "SELECT * FROM [dbo].[Contraseña_Sup]
                WHERE ([Usuario] = :user AND [Contraseña] = :pass)
                AND Estatus = 'A'";

        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':pass', $pass);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return [
                'success' => true,
                'message' => 'OK'
            ];
        } else {
            throw new Exception('Usuario y/o contraseña incorrectos');
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'registrar':
            registrar_cliente();
            break;

        case 'actualizar':
            actualizar_cliente();
            break;

        case 'getCliente':
            get_cliente($_POST['Id_cliente']);
            break;

        case 'getClienteByRS':
            getClienteByRazonSocial($_POST['razonSocial']);
            break;

        case 'operacion':
            getOperacionByReferencia($_POST['referencia']);
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => 'Acción inválida'
            ]);
            break;
    }
}
