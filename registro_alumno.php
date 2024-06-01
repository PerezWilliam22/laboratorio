<?php
require "vendor/autoload.php";

$url = "http://localhost/webservice/ws.php?wsdl";
$cliente = new nusoap_client($url, "wsdl");
$error = $cliente->getError();

if ($error) {
    echo "Error de conexión al webservice: $error";
    exit();
}

$Nombre = filter_input(INPUT_POST, 'Nombre', FILTER_SANITIZE_STRING);
$Laboratorio1 = filter_input(INPUT_POST, 'Laboratorio1', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$Laboratorio2 = filter_input(INPUT_POST, 'Laboratorio2', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$Parcial = filter_input(INPUT_POST, 'Parcial', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

$parametros = array(
    'Nombre' => $Nombre,
    'Laboratorio1' => $Laboratorio1,
    'Laboratorio2' => $Laboratorio2,
    'Parcial' => $Parcial
);

$resultado = $cliente->call("registrarAlumno", $parametros);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Resultado del Registro</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <?php
            if ($cliente->fault) {
                echo "<h1 class='text-red-500 font-bold text-xl'>Fallo:</h1>";
                echo "<pre>";
                print_r($resultado);
                echo "</pre>";
            } else {
                $error = $cliente->getError();
                if ($error) {
                    echo "<h1 class='text-red-500 font-bold text-xl'>Error:</h1>";
                    echo "<p>$error</p>";
                } else {
                    echo "<h1 class='text-2xl font-bold mb-4'>Resultado del Registro</h1>";
                    echo "
                        <table class='min-w-full bg-white border-collapse'>
                            <thead>
                                <tr>
                                    <th class='py-2 px-4 border-b'>Campo</th>
                                    <th class='py-2 px-4 border-b'>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='py-2 px-4 border-b'>Nombre</td>
                                    <td class='py-2 px-4 border-b'>{$resultado["Nombre"]}</td>
                                </tr>
                                <tr>
                                    <td class='py-2 px-4 border-b'>Laboratorio 1</td>
                                    <td class='py-2 px-4 border-b'>{$resultado["Laboratorio1"]}</td>
                                </tr>
                                <tr>
                                    <td class='py-2 px-4 border-b'>Laboratorio 2</td>
                                    <td class='py-2 px-4 border-b'>{$resultado["Laboratorio2"]}</td>
                                </tr>
                                <tr>
                                    <td class='py-2 px-4 border-b'>Parcial</td>
                                    <td class='py-2 px-4 border-b'>{$resultado["Parcial"]}</td>
                                </tr>
                                <tr>
                                    <td class='py-2 px-4 border-b font-bold'>Promedio</td>
                                    <td class='py-2 px-4 border-b font-bold'>{$resultado["Promedio"]}</td>
                                </tr>
                            </tbody>
                        </table>
                    ";
                }
            }
            ?>
            <a href="alumno.html" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-700">Registrar otro alumno</a>
        </div>
    </div>
</body>
</html>
