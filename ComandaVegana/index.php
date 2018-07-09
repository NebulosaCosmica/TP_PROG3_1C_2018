<?php

// pendientes:

//(CONTINUAR) pensar como linkear el mozo que crea una comanda

// creé una clase pedido, pero le falta un poquito aceitar el asunto

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

/*

A tener en cuenta

// postman header content type  x-www-form-urlencoded         
    
    4 - ABM - todas las entidades
    6 - LISTADO - todos los pedidos
 */

require "vendor/autoload.php";
require_once "guia/MWparaAutentificar.php";

require_once "entidades/Comandas/Comanda.php";
require_once "entidades/Ambulancia/Mozo.php";






$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);



$app->post('/login/',function($request,$response,$args){   
     
     $eltoque = $request->getParsedBody();
 
     return $eltoque;  

 })->add(\MWparaAutentificar::class.':VerificarUsuario');


$app->group('/comandas',function(){
    $this->post('/alta/',\Comanda::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarToc');

});
//->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/mozos',function(){
    $this->post('/alta/',\Mozo::class.':CargarUno');

});
//->add(\MWparaAutentificar::class.':ValidarToc');


//abajo muestro deces por pantalla

$app->any('[/]',function($req,$resp){
    
    echo "<pre>";
    echo "<body style='background: palegoldenrod;'>";
    echo "<h1>Ver el gestor de comandas del Restó Vegano edición on line</h1>";
    echo "<h2>Alta calidad</h2>";
    echo "<br><br>";
    echo "¿Quién sos? (tokenresto) /login/";
   /* echo "<br><br>";
    echo " Comandas /comandas/";
    echo "<br><br>";
    echo " Mozos /mozos/";*/
    echo "</pre>";
    
});

// sin este, no anda
$app->run();

?>

