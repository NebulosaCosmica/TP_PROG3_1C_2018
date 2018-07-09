<?php


//header content type  x-www-form-urlencoded         

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require "vendor/autoload.php";
require_once "clases/media.php";
require_once "clases/usuario.php";
require_once "clases/VentaMedia.php";
require_once "clases/guia/MWparaAutentificar.php";
require_once "clases/guia/AutentificadorJWT.php";

//require_once "clases/guia/AccesoDatos.php";


use \Firebase\JWT\JWT;

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$app->group('/medias',function(){

    //$this->post('/alta/', function ($request, Response $response,$args) {    
    $this->post('/alta/', \Media::class.':CargarUno');            
    
    $this->get('/listado/',\Media::class.':TraerTodos')->add(\Media::class.':Nomostrar');

    $this->delete('/borrar/[{id}]',\Media::class.':BorrarUno')
    ->add(\MWparaAutentificar::class.':VerDuen')
    ->add(\MWparaAutentificar::class.':ValidarToc');  

    //$this->get('/traer/[{id}]',\Media::class.':TraerUno');  
    // no me anda el parametro
    //$this->get('/{id}', \cd::class . ':traerUno');
    // pruebo con :id
    /// GET http://myserver.net/api/test?id=1 where index.php is within api/
/*$app->get('/test', function ($request, $response) {
    $params = $request->getQueryParams();
    return $response->write("Hello " . var_dump($params));*/


    // el put no me anda... .
    //$this->post('/modificar/',\Media::class.':ModificarUno');  
});   



$app->group('/usuarios',function(){

    $this->post('/alta/', \Usuario::class.':CargarUno');    
        
    $this->get('/listado/',\Usuario::class.':TraerTodos')->add(\MWparaAutentificar::class.':ValidarToc');
    
});

$app->group('/ventamedias',function(){

    $this->post('/venta/', VentaMedia::class.':CargarUno')
    ->add(\MWparaAutentificar::class.':ValidarVenta')
    ->add(\MWparaAutentificar::class.':ValidarToc');

    // no anda por put
    $this->post('/modificarv/',VentaMedia::class.':ModificarUno')
    ->add(\MWparaAutentificar::class.':ValidarEnc')
    ->add(\MWparaAutentificar::class.':ValidarToc');

});



/*$app->get('/verificarTokenNuevo/', function (Request $request, Response $response) { 
    $datos = array('usuario' => 'rogelio@agua.com','perfil' => 'Administrador', 'alias' => "PinkBoy");
    $token= AutentificadorJWT::CrearToken($datos); 
    $esValido=false;
    try 
    {
      AutentificadorJWT::verificarToken($token);
      $esValido=true;      
    }
    catch (Exception $e) {      
      //guardar en un log
      echo $e;
    }
    if( $esValido)
    {
        /* hago la operacion del  metodo 
        echo "ok";
    }   
    return $response;
});*/

/*$app->get('/devolverPayLoad/', function (Request $request, Response $response) { 
    $datos = array('usuario' => 'rogelio@agua.com','perfil' => 'Administrador', 'alias' => "PinkBoy");
    $token= AutentificadorJWT::CrearToken($datos); 
    $payload=AutentificadorJWT::ObtenerPayload($token);
    $newResponse = $response->withJson($payload, 200); 
    return $newResponse;
});*/

$app->get('/login/',function($request,$response,$args){

   // echo ("luego de la verificacion..., el autentificador");
    
    $eltoque = $request->getParsedBody();

    return $eltoque;
 
// agrega MW para validar login
})->add(\MWparaAutentificar::class.':VerificarUsuario');


$app->run();

?>