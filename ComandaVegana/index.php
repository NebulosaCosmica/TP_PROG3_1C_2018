<?php

/* PRESENTACION

    Comanda Vegana Incompleta

    -----------------

    Entidades

    Socios (3 socios + admin)

    Empleados (Mozos, Cerveceros, Pasteleros, Bartenders, Cocineros)

    Operaciones (Comandas con foto)

    Auxiliares (Pedido)

    Clientes (en proceso y revisión) ¿Hasta dónde necesito una clase cliente o solamente un MW de acceso?

    Más clases

    AccesoDatos
    AutentificadorJWT
    IApiUsable
    MWparaAutentificar

    medias (reemplaza cd y cdapi)

    --------------------

    Base de datos restobd con 8 tablas 

    // RESUMEN

    WEB Hosting
    
    - ABM - todas las entidades

    // el socio da de alta
    // solo el mozo da de alta comandas
    // TOKEN para todos y todas
    // primera parte OK

    // comanda con información necesaria vista por el empleado correspondiente OK

    // listado de pendientes por empleado OK

    // código de pedido para el cliente, en proceso 

    // ABM de todos los empleados realizada solamente por un Socio

    // ABM - WEB SERVICE -POO- PDO
    
    // ABM - API Rest - PDO (resto de las acciones).

    // JWT logeo y perfiles

    // Manejo de archivo e imágenes, cambiar nombre

 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;


//CONTINUAR


// modifiqué el accesodatos con esos dato

// para que ande el web hosting

//pass mysql vO5PrSALAY0x

//nombre bdmysql u227410637_resto

// usuario mysql u227410637_abd


// código de pedido para el cliente, en proceso
// Agrandar el tamaño de la clase comanda incorporando el codigo de pedido

// - LISTADO - todos los pedidos, en proceso

// Manejo de errores, en proceso

// cuando un empleado recibe algo en su lista de pendientes
// se supone que trabajará en el primer pedido llegado. 
// pondrá el estado del pedido en "en preparacion"

// empleado/operacion , cambia el estado del primer pedido ingresado

   
// el pedido en preparacions tiene un tiempo estimado 
    
// las mesas tambien tienen un codigo de mesa (por?)
    
// el cliente puede entrar a la app y ver

// la encuesta de la mesa y el mozo son ambiguas

// puede haber más de un empleado en el mismo puesto

require "vendor/autoload.php";
require_once "guia/MWparaAutentificar.php";

require_once "entidades/Administra/Socio.php";

require_once "entidades/Comandas/Comanda.php";

require_once "entidades/Ambulancia/Mozo.php";
require_once "entidades/BarraTragosVinos/Bartender.php";
require_once "entidades/BarraCervezaArtesanal/Cervecero.php";
require_once "entidades/Cocina/Cocinero.php";
require_once "entidades/BarraDulce/Pastelero.php";

// cliente en proceso
require_once "entidades/Ambulancia/Cliente.php";

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$app->post('/login/',function($request,$response,$args){   
     
     $eltoque = $request->getParsedBody();
 
     return $eltoque;  

 })->add(\MWparaAutentificar::class.':VerificarUsuario');



$app->group('/comandas',function(){
    $this->post('/alta/',\Comanda::class.':CargarUno');
 

})->add(\MWparaAutentificar::class.':ValidarMozo')
->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/mozos',function(){
    $this->post('/alta/',\Mozo::class.':CargarUno');

})->add(\MWparaAutentificar::class.':ValidarSocio')
->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/cerveceros',function(){
    $this->post('/alta/',\Cervecero::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarSocio');
    
    $this->post('/pendientes/',\Cervecero::class.':Trabajo')
    ->add(\MWparaAutentificar::class.':ValidarCer');

})->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/bartenders',function(){
    $this->post('/alta/',\Bartender::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarSocio');
    $this->post('/pendientes/',\Bartender::class.':Trabajo')
    ->add(\MWparaAutentificar::class.':ValidarBart');

})->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/cocineros',function(){
    $this->post('/alta/',\Cocinero::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarSocio');
    $this->post('/pendientes/',\Cocinero::class.':Trabajo')
    ->add(\MWparaAutentificar::class.':ValidarCoci');

})->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/pasteleros',function(){
    $this->post('/alta/',\Pastelero::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarSocio');
    $this->post('/pendientes/',\Pastelero::class.':Trabajo')
    ->add(\MWparaAutentificar::class.':ValidarPast');

})->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/socios',function(){

    $this->post('/alta/',\Socio::class.':CargarUno');

    // el delete no borra
    //$this->delete('/baja/',\Socio::class.':BorrarUno');

    $this->post('/baja/',\Socio::class.':BorrarUno');
    // put anda
    $this->put('/modifica/',\Socio::class.':ModificarUno');

})->add(\MWparaAutentificar::class.':ValidarSocio')
->add(\MWparaAutentificar::class.':ValidarToc');


//abajo muestro deces por pantalla

$app->any('[/]',function($req,$resp){
    
    echo "<pre>";
    echo "<body style='background: palegoldenrod;'>";
    echo "<h1>Ver el gestor de comandas del Restó Vegano edición on line</h1>";
    echo "<h2>Alta calidad</h2>";
    echo "<br><br>";
    echo "¿Quién sos? (tokenresto) /login/"; 
    echo "<br><br>";
    echo " Clientes";
	echo "<br><br>";
	echo "usuario: Código de mesa. contrasena: Código de pedido";  
    echo "</pre>";
    
    // try

    /*$lossocios=Socio::TraerTodosLosSocios(); 
    echo "<pre>";
    var_dump($lossocios);
    echo "</pre>";*/

    // ver, retocar
    /*$lasmandas=Comanda::TraerTodasLasComandas(); 
    echo "<pre>";
    var_dump($lasmandas);
    echo "</pre>";*/
});


// sin este, no anda
$app->run();

?>

