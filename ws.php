<?php
require "vendor/autoload.php";
$server = new nusoap_server;
$server->configureWSDL('server', 'urn:server');
$server->wsdl->chemaTargetNamespace='urn:server';
$server->register(
    'hola',
    array('usuario'=>'xsd:string'),
    array('return'=>'xsd:string'),
    'urn:server',
    'urn:server#holaServer',
    'rpc',
    'encoded',
    'Funcion hola mundo en un webservice'
);

$server->register(
    'sumatoria',
    array('v1'=>'xsd:int','v2'=>'xsd:int'),
    array('resultado'=>'xsd:int'),
    'urn:server',
    'urn:server#sumatoriaServer',
    'rpc',
    'encoded',
    'funcion para calcular la sumatoria de entre dos numeros'
);

$server->wsdl->addComplexType(
    'Persona',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id_user'=>array('name'=>'id_user','type'=>'xsd:int'),
        'fullname'=>array('name'=>'fullname','type'=>'xsd:string'),
        'email'=>array('name'=>'email','type'=>'xsd:string'), 
        'msg'=>array('name'=>'msg','type'=>'xsd:string'),
        'level'=>array('name'=>'level','type'=>'xsd:int')
        )
);

$server->register(
    'login',
    array('username'=>'xsd:string', 'password'=>'xsd:string'),
    array('return'=>'tns:Persona'),
    'urn:server',
    'urn:server#loginServer',
    'rpc',
    'encoded',
    'funcion para validar credenciales'

);

function login($username, $password){
    if(($username=="admin") && ($password=="catolica")){
        $valor=array(
            'id_user'=>1,
            'fullname'=>'Juan Perez',
            'email'=>'juan.perez@catolica.edu.sv',
            'msg'=>'Usuario correcto',
            'level'=>1
        );
    }else{
        $valor=array(
            'id_user'=>0,
            'fullname'=>'',
            'email'=>'',
            'msg'=>'Usuario incorrecto',
            'level'=>0
        );
    }
    return $valor;
}

function hola($usuario){
    return "Bienvenido $usuario";
}

function sumatoria($v1,$v2){
    $total=0;
    for($i=$v1;$i<=$v2;$i++){
        $total+=$i;
    }
    return $total;
}

//laboratorio
$server->wsdl->addComplexType(
    'Alumno',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'Nombre' => array('name' => 'Nombre', 'type' => 'xsd:string'),
        'Laboratorio1' => array('name' => 'Laboratorio1', 'type' => 'xsd:float'),
        'Laboratorio2' => array('name' => 'Laboratorio2', 'type' => 'xsd:float'),
        'Parcial' => array('name' => 'Parcial', 'type' => 'xsd:float'),
        'Promedio' => array('name' => 'Promedio', 'type' => 'xsd:float')
    )
);

$server->register(
    'registrarAlumno',
    array(
        'Nombre' => 'xsd:string',
        'Laboratorio1' => 'xsd:float',
        'Laboratorio2' => 'xsd:float',
        'Parcial' => 'xsd:float'
    ),
    array('return' => 'tns:Alumno'),
    'urn:server',
    'urn:server#registrarAlumnoServer',
    'rpc',
    'encoded',
    'Función para registrar un alumno y calcular el promedio'
);

function registrarAlumno($Nombre, $Laboratorio1, $Laboratorio2, $Parcial) {
    $Promedio = ($Laboratorio1 * 0.25) + ($Laboratorio2 * 0.25) + ($Parcial * 0.5);

    return array(
        'Nombre' => $Nombre,
        'Laboratorio1' => $Laboratorio1,
        'Laboratorio2' => $Laboratorio2,
        'Parcial' => $Parcial,
        'Promedio' => $Promedio
    );
}


$server->service(file_get_contents("php://input"));