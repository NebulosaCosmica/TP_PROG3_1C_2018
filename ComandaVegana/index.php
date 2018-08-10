<?php
/*  SEGUIMIENTO

Verificar las rutas!

orde/localhost/


// codigo de mesa fantasma, sin entidad, que no hace falta

// (el mozo identifica mesa y comanda por la foto de la mesa ;)  )

// EN LA TABLA CLIENTES tengo el codigo de mesa OK

// el cliente puede entrar a la app y ver 

// código de pedido para el cliente junto al codigo de mesa OK

// puedo usar el estado generico del pedido para el mozo, cuando esta

// finalizado, que le aparezca en Pendientes del mozo

http://localhost/prog/orde/localhost/ComandaVegana/login/

POST (login de usuario)
(socios mozos bartenders cocineros pasteleros cerveceros)

http://localhost/prog/ComandaVegana/mozos/alta/

POST (alta de mozos) solo Socios

http://localhost/prog/orde/localhost/ComandaVegana/comandas/alta/

POST (alta de comandas) solo Mozos

 */

/* PRESENTACION

    Comanda Vegana Base

    -----------------

    Entidades

    Socios (3 socios + admin)

    Empleados (Mozos, Cerveceros, Pasteleros, Bartenders, Cocineros)

    Operaciones (Comandas con foto)

    Auxiliares (Pedido,GesCliente,Pendiente)

    
    AccesoDatos
    AutentificadorJWT
    IApiUsable
    MWparaAutentificar

    medias (reemplaza cdapi)

    --------------------

    Base de datos restobd con 10 tablas 

    // RESUMEN
    
    // Web Hosting    

    // el socio da de alta
    // solo el mozo da de alta comandas
    // TOKEN para todos y todas
    // primera parte OK

    // comanda con información necesaria vista por el empleado correspondiente OK

    // listado de pendientes por empleado OK

    // código de pedido para el cliente junto al codigo de mesa OK

    // ABM - WEB SERVICE -POO- PDO
    
    // ABM - API Rest - PDO (resto de las acciones).

    // JWT logeo y perfiles

    // Manejo de archivo e imágenes, cambiar nombre

    // tiempo estimado estandar de preparacion de todos los pedidos para simplificar el asunto OK

 */




//CONTINUAR

// NECESITO ESTADOS POR EMPLEADO OK
// creé una tabla pendientes, para volcar el proceso de preparación por separado OK

// cuando un empleado recibe algo en su lista de pendientes
// se supone que trabajará en el primer pedido llegado. 
// pondrá el estado del pedido en "en preparacion" OK
// empleado/operacion , cambia el estado del primer pedido ingresado OK


// el pedido en preparacions tiene un tiempo estimado OK      
// puede haber más de un empleado en el mismo puesto OK

// PREVIOS

// TIEMPO RESTANTE GES CLIENTES

// MOZO PENDIENTE CAMBIAR ESTADOS, ver si el pedido esta listo
// puedo usar el estado generico del pedido para el mozo, cuando esta finalizado, que le aparezca en Pendientes del mozo

//SOCIO CIERRA LA MESA


// - ABM - todas las entidades

// filtrar por estado para pendientes (bart, past, cocin)
// agregar la operacion para empleados razos, cambiar estados

// la encuesta de la mesa y el mozo son ambiguas
// La encuesta luego de cerrar la mesa

//Administración: 

// FECHA EN PARTICULAR O LAPSO DE TIEMPO

// empleados

// fecha y hora de logueo

// Cantidad de operaciones: (evaluar contador simple)

// por sector OR por empleado (evaluar contador simple)

// pedidios

// mas vendidos, menos vendidos

// no entregados a tiempo (no existen)

// cancelados (no existen)

//mesas

// mayor facturacion / menor facturacion (evaluar acumulador simple)

// factura con mayor o menor importe (evaluar max/min simple)

// Comentarios de la encuesta

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
  
require "vendor/autoload.php";
require_once "guia/MWparaAutentificar.php";

require_once "entidades/Administra/Socio.php";

require_once "entidades/Comandas/Comanda.php";

require_once "entidades/Ambulancia/Mozo.php";
require_once "entidades/BarraTragosVinos/Bartender.php";
require_once "entidades/BarraCervezaArtesanal/Cervecero.php";
require_once "entidades/Cocina/Cocinero.php";
require_once "entidades/BarraDulce/Pastelero.php";

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$app->post('/login/',function($request,$response,$args){   
     
     $eltoque = $request->getParsedBody();
 
     return $eltoque;  

 })->add(\MWparaAutentificar::class.':VerificarUsuario');



$app->group('/comandas',function(){
    $this->post('/alta/',\Comanda::class.':CargarUno');
    $this->post('/operaciones/',\Mozo::class.':Proceso');
    

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

    $this->post('/operaciones/',\Cervecero::class.':Proceso')
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

//comandas pendientes, dar permiso al socio para que las vea
$app->group('/socios',function(){

    $this->post('/alta/',\Socio::class.':CargarUno');

    // el delete no borra ?¿?
    //$this->delete('/baja/',\Socio::class.':BorrarUno');

    $this->post('/baja/',\Socio::class.':BorrarUno');
    // put anda
    $this->put('/modifica/',\Socio::class.':ModificarUno');


    $this->post('/cerveceros/pendientes/',\Cervecero::class.':Trabajo');
    

    $this->post('/cerveceros/operaciones/',\Cervecero::class.':Proceso');

    $this->post('/comandas/operaciones/',\Mozo::class.':Proceso');

})->add(\MWparaAutentificar::class.':ValidarSocio')
->add(\MWparaAutentificar::class.':ValidarToc');

$app->get('/clientes/',\GesCliente::class.':ConsultarPedido')
->add(\MWparaAutentificar::class.':VerificarPedidoCierto');

//abajo muestro deces por pantalla

$app->any('[/]',function($req,$resp){
    
    echo "<pre>";
    echo "<body style='background: palegoldenrod'>";
    echo "<h1>Ver el gestor de comandas del Restó Vegano edición on line</h1>";
    echo "<h2>Alta calidad</h2>";
    echo "<br><br>";
    echo "¿Quién sos? (tokenresto) /login/"; 
    echo "<br><br>";
    echo "Los clientes pueden revisar su pedido /clientes/";
	echo "<br><br>";
	echo "codigomesa: Código de mesa. codigopedido: Código de pedido";  
    echo "</pre>";    

});


// sin este, no anda
$app->run();

?>

