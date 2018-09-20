<?php


/*

La app empieza cerca de la línea 260

CONTINUAR CON

EL MOZO SE ENTERA CUANDO EL PEDIDO COMPLETO ESTÁ LISTO PARA SERVIR

EL MOZO OPERA SOBRE LOS PENDIENTES

EL MOZO COBRA Y PERMITE AL SOCIO CERRAR LA MESA (o algo así)

PUNTEAR LAS ESTADÍSTICAS DE LOS EMPLEADOS Y LAS VENTAS

SEGUIMIENTO

INICIO

http://localhost/prog/ComandaVegana/

LOGINES

socios mozos bartenders cocineros pasteleros cerveceros

(manejar excepciones)
(revisar el login y el buen funcionamiento cuando eso falla)
(token deformado o ausente)

http://localhost/prog/ComandaVegana/login/


ALTA DE COMANDA/PEDIDO/PENDIENTES

1° login del mozo

2° alta del pedido

http://localhost/prog/ComandaVegana/comandas/alta/

el admin no puede dar de alta comandas

(por el momento se aceptan pedidos completos, no hay otra alternativa)

(ABM de Comandas, luego)

genera un codigo de mesa y de cliente para revisar el pedido

VER EL PEDIDO CARGADO

El pedido le llega a cada empleado para que lo procese

Cerveceros como ejemplo

Login del cervecero

El cervecero ve los pendientes

http://localhost/prog/ComandaVegana/cerveceros/pendientes/


EL EMPLEADO OPERA SOBRE LOS PENDIENTES

Login del cervecero

El cervecero cambia el primer pendiente, y le asigna un tiempo de proceso

El cervecero ve cuándo el pedido está listo para ser servido


OTROS PROCESOS ACTIVOS

http://localhost/prog/ComandaVegana/mozos/alta/

Alta de mozos

http://localhost/prog/ComandaVegana/bartenders/alta/

Alta de bartenders

http://localhost/prog/ComandaVegana/socios/alta/

Alta de socios

http://localhost/prog/ComandaVegana/cerveceros/alta/

Alta de cerveceros

http://localhost/prog/ComandaVegana/cocineros/alta/

Alta de cocineros

http://localhost/prog/ComandaVegana/pasteleros/alta/

Alta de pasteleros

http://localhost/prog/ComandaVegana/socios/baja/

Baja de socios 

(Ver de nuevo el DELETE, que no andaba)

http://localhost/prog/ComandaVegana/socios/modifica/

Modifica socios

http://localhost/prog/ComandaVegana/clientes/

con codigopedido y codigomesa de parametros (GET)

http://localhost/prog/ComandaVegana/clientes/?codigomesa=10004&codigopedido=8dg45

muestra el pedido que coincide con los códigos

http://localhost/prog/ComandaVegana/socios/cerveceros/operaciones/

el socio ve las operaciones del cervecero

http://localhost/prog/ComandaVegana/socios/cerveceros/pendientes/

el socio ve los pendientes del cervecero


COSAS PARA MEJORAR

LOGINES

(manejar excepciones)
(revisar el login y el buen funcionamiento cuando eso falla)
(token deformado o ausente)

ALTA DE COMANDA/PEDIDO/PENDIENTES

(por el momento se aceptan pedidos completos, no hay otra alternativa)

(ABM de Comandas, luego)


VER EL PEDIDO CARGADO

(Cerveceros como ejemplo, extender a los 4 empleados)


// codigo de mesa fantasma, sin entidad, que no hace falta

// (el mozo identifica mesa y comanda por la foto de la mesa ;)  )

// puedo usar el estado generico del pedido para el mozo, cuando esta

// finalizado, que le aparezca en Pendientes del mozo


PRESENTACION

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
    $this->post('/operaciones/',\Bartender::class.':Proceso')
    ->add(\MWparaAutentificar::class.':ValidarBart');

})->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/cocineros',function(){
    $this->post('/alta/',\Cocinero::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarSocio');
    $this->post('/pendientes/',\Cocinero::class.':Trabajo')
    ->add(\MWparaAutentificar::class.':ValidarCoci');
    $this->post('/operaciones/',\Cocinero::class.':Proceso')
    ->add(\MWparaAutentificar::class.':ValidarCoci');

})->add(\MWparaAutentificar::class.':ValidarToc');

$app->group('/pasteleros',function(){
    $this->post('/alta/',\Pastelero::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarSocio');
    $this->post('/pendientes/',\Pastelero::class.':Trabajo')
    ->add(\MWparaAutentificar::class.':ValidarPast');
    $this->post('/operaciones/',\Pastelero::class.':Proceso')
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

    $this->post('/comandas/operaciones/',\Mozo::class.':Proceso');


    $this->post('/cerveceros/pendientes/',\Cervecero::class.':Trabajo');    

    $this->post('/cerveceros/operaciones/',\Cervecero::class.':Proceso');


    $this->post('/cocineros/pendientes/',\Cocinero::class.':Trabajo');    

    $this->post('/cocineros/operaciones/',\Cocinero::class.':Proceso');    

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
	echo "codigomesa: Código de mesa <br>codigopedido: Código de pedido";  
    echo "</pre>";    

});


// sin este, no anda
$app->run();

?>

