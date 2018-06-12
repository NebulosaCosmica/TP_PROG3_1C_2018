<?php

//slim, farebase, composer

// composer: descargar el exe, install siguiente... composer require slim/slim para el slim....

//API rest

//creamos en php el index

// incluimos nuestras clases


// nueva regulaciòn de php PSR 7

// ver y aprender...

//por cada ruta, un verbo para cada accion

// si en mi servidor no anda, uso post y le cambio el nombre


/*

SUBIR LA API a la web

API REST

ABM para cada entidad

sencillo


apuntamos a 

/helado

/helado/id

si no me deja por otros metodos,

uso post /helado/delete


hay metodos que resuelven esta forma de llamar al metodo

//reglas de negocio

// clases

// y datos

(archivos o base de datos)*/


// class slim app not found

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require_once 'vendor/autoload.php';

require_once "entidades/guia/AccesoDatos.php";

require_once "entidades/bartender.php";


$app = new \Slim\App([]);

// acá la funcionalidad de la ABM

// helado, empleado, ventas, etc.

// hacerlo en la web y probalo con el postman

// JWT



$app->post('/login', function (Request $request, Response $response) {    
    $response->getBody()->write("POST <br> Login de un Bartender, dni y contrasena");

    
    // manejar el login

    $toc = Bartender::ManejarLogin($_POST['dni'],$_POST['contraseña']);

    var_dump($toc);

    return $response;

});

$app->get('/bartender', function (Request $request, Response $response) {    
    $response->getBody()->write("GET => Bienvenido!!! ,a SlimFramework <br> Traer todos los Bartenders");
    return $response;

});

$app->post('/bartender', function (Request $request, Response $response) {    
    $response->getBody()->write("POST => Bienvenido!!! ,a SlimFramework <br> Alta de un Bartender");

    $elbartender =  Bartender::OBJBartender($_POST['nombreCompleto'],$_POST['dni'],$_POST['contraseña']);

    //var_dump($elbartender);

    // da error, ver bartender php
    //$elbartender->InsertarElBartenderParametros();

    // sigue tirando error, ver el htaccess
    //$elbartender->InsertarElBartender();

    $elbartender->GuardarBartender();

    Bartender::TraerTodosLosBartenders();

    return $response;

});

$app->delete('/bartender', function (Request $request, Response $response) {    
    $response->getBody()->write("DELETE => Bienvenido!!! ,a SlimFramework <br> Baja de un Bartender");
    return $response;

});

$app->put('/bartender', function (Request $request, Response $response) {    
    $response->getBody()->write("PUT => Bienvenido!!! ,a SlimFramework <br> Modificación, más tarde...");
    return $response;

});



$app->post('/HELADO', function (Request $request, Response $response) {    
    $response->getBody()->write("POST => Bienvenido!!! ,a SlimFramework");
    return $response;

});

$app->run();