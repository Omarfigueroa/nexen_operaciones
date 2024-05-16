<?php
require '../conexion/bd.php';
if(!isset($_SESSION['usuario_nexen'])) { 
    session_start();

    if(!isset($_SESSION['usuario_nexen'])){
        header('Location: login.php');
    }else{
        require '../utils/catalogos.php'; 
        require '../utils/utils.php';

        $user_nexen = $_SESSION['usuario_nexen'];

        date_default_timezone_set('America/Mexico_City');

        $fechope = date('Y-m-d');
        $horaope = date('H:i:s');

        // Comprobar si se recibió el parámetro id_razon
        if (isset($_POST['id_razon'])) {
            $id_razon = $_POST['id_razon'];

            // Consultar la base de datos para verificar si existe la razón con el id proporcionado
            $query = "SELECT COUNT(*) AS razon_count FROM Razon_Bancos WHERE id_razon_social = :id_razon";
            $statement = $conn_bd->prepare($query);
            $statement->bindParam(':id_razon', $id_razon, PDO::PARAM_INT);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $razon_count = $result['razon_count'];

            if ($razon_count > 0) {
                // Existe la razón en la tabla Razon_Bancos, ahora obtén los datos asociados
                $query_datos = "SELECT * FROM Razon_Bancos WHERE id_razon_social = :id_razon";
                $statement_datos = $conn_bd->prepare($query_datos);
                $statement_datos->bindParam(':id_razon', $id_razon, PDO::PARAM_INT);
                $statement_datos->execute();

                $datos = array();
                while ($row = $statement_datos->fetch(PDO::FETCH_ASSOC)) {
                    // Obtener el nombre del banco correspondiente al ID_BANCO
                    $idBanco = $row['Id_banco'];
                    $query_banco = "SELECT NOMBRE_BANCO FROM Catalogo_Bancos WHERE ID_BANCO = :idBanco";
                    $statement_banco = $conn_bd->prepare($query_banco);
                    $statement_banco->bindParam(':idBanco', $idBanco, PDO::PARAM_INT);
                    $statement_banco->execute();
                    $nombreBanco = $statement_banco->fetchColumn();

                    // Agregar el nombre del banco al array de datos
                    $row['Nombre_Banco'] = $nombreBanco;

                    $datos[] = $row;
                }
                    $colors = ["#f8f9fa", "#ffffff"]; // Colores para las filas alternas
                    $colorIndex = 0;
                    // Obtener la lista de operadores para marcar y deshabilitar los checkboxes
                    $query_operadores = "SELECT Id_Operador FROM FK_Operador_Razon WHERE Id_Razon_Social = :id_razon";
                    $statement_operadores = $conn_bd->prepare($query_operadores);
                    $statement_operadores->bindParam(':id_razon', $id_razon, PDO::PARAM_INT);
                    $statement_operadores->execute();
                    $operadores = $statement_operadores->fetchAll(PDO::FETCH_COLUMN);

                    // Generar el HTML de los checkboxes de operadores y marcarlos según los operadores encontrados
                    $checkboxHTML = '';
                    foreach ($result_empresas as $valores) {
                        $isChecked = in_array($valores['ID_EMPRESA'], $operadores);
                        $isDisabled = $isChecked;

                        $checkboxHTML .= '<label class="list-group-item list-group-item-action py-1 d-flex justify-content-between align-items-center" style="background-color: ' . $colors[$colorIndex] . '">';
                        $checkboxHTML .= htmlspecialchars($valores['Razon_Social']);
                        $checkboxHTML .= '<input class="form-check-input" type="checkbox" id_empresa="' . $valores['ID_EMPRESA'] . '" rfc="' . $valores['RFC'] . '" domicilio="' . $valores['DOMICILIO_FISCAL'] . '" repLegal="' . $valores['REPRESENTANTE_LEGAL'] . '" value="' . htmlspecialchars($valores['Razon_Social']) . '" name="operadores[]" ' . ($isChecked ? 'checked' : '') . ' ' . ($isDisabled ? 'disabled' : '') . '>';
                        $checkboxHTML .= '</label>';

                        $colorIndex = 1 - $colorIndex; // Cambia el color para la siguiente fila
                    }

                // Preparar la respuesta con los datos encontrados
                $response = array(
                    'success' => true,
                    'data' => $datos,
                    'checkboxHTML' => $checkboxHTML
                );
            } else {
                // No se encontraron datos, preparar la respuesta indicando que no hay datos
                $response = array(
                    'success' => false,
                    'message' => 'sin_data'
                );
            }
        } else {
            // El parámetro id_razon no se recibió, preparar la respuesta de error
            $response = array(
                'success' => false,
                'message' => 'Parámetro id_razon no proporcionado'
            );
        }

        // Devolver la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }

}//Si existe Usuario

?>
