<?php

class MWparaAutentificar
{
 /**
   * @api {any} /MWparaAutenticar/  Verificar Usuario
   * @apiVersion 0.1.0
   * @apiName VerificarUsuario
   * @apiGroup MIDDLEWARE
   * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
   *
   * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
 * @apiParam {ResponseInterface} response El objeto RESPONSE.
 * @apiParam {Callable} next  The next middleware callable.
   *
   * @apiExample Como usarlo:
   *    ->add(\MWparaAutenticar::class . ':VerificarUsuario')
   */
	public function VerificarUsuario($request, $response, $next) {
				 
		
		//$_GET['usuario'];
		//$_GET['contrasena'];

		$algo = Usuario::TraerTodosLosUsuarios();

		foreach ($algo as $key => $value) {

			if($value->getnombre() === $_GET['usuario'])
			{
			//	echo "OK";	

				$token= AutentificadorJWT::CrearToken($value->getperfil()); 
				$newResponse = $response->withJson($token,200);  
				$newResponse = $next($request, $newResponse);

    	return $newResponse;
				

			}

		}

			echo "Usted no está autorizado para continuar";
			return $response;
		
	


		/* Así dios lo trajo al mundo
		
		if($request->isGet())
		  {
		     $response->getBody()->write('<p>NO necesita credenciales para los get </p>');
		     $response = $next($request, $response);
		  }
		  else
		  {
		    $response->getBody()->write('<p>verifico credenciales</p>');
		    $ArrayDeParametros = $request->getParsedBody();
		    $nombre=$ArrayDeParametros['nombre'];
		    $tipo=$ArrayDeParametros['tipo'];
		    if($tipo=="administrador")
		    {
		      $response->getBody()->write("<h3>Bienvenido $nombre </h3>");
		      $response = $next($request, $response);
		    }
		    else
		    {
		      $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
		    }  
		  }
		  $response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
		  return $response;  */ 
	}//verificar usuario

	public function ValidarToc($request, $response, $next) {

			$elt = $request->getHeaderLine('tokenmedias');
			//var_dump(AutentificadorJWT::ObtenerPayLoad($elt));
			//var_dump($elt);
		  try 
			{
				AutentificadorJWT::verificarToken($elt);
				$esValido=true;      
			}
			catch (Exception $e) {      
				//guardar en un log
				echo $e;
			}
			if( $esValido)
			{

					 
					echo "ok";
					$response = $next($request, $response);   					
			}

	return $response;
	}

	public function VerDuen($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenmedias');
	//	var_dump(AutentificadorJWT::ObtenerPayLoad($elt));
		

	$profile = AutentificadorJWT::ObtenerPayLoad($elt);
	//var_dump($profile);
		if($profile->data == "Dueño")
		{
			echo "El dueño borra lo que quiere<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si no sos dueño, no podes borrar<br><br>";
		}

		return $response;
	}

	public function ValidarVenta($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenmedias');
	//	var_dump(AutentificadorJWT::ObtenerPayLoad($elt));
		

	$profile = AutentificadorJWT::ObtenerPayLoad($elt);
	//var_dump($profile);
		if($profile->data == "Empleado" || $profile->data == "Encargado")
		{
			echo "$profile->data ha hecho una venta<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si sos dueño, no corresponde que vendas<br><br>";
		}

		return $response;
	}

	public function ValidarEnc($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenmedias');
	//	var_dump(AutentificadorJWT::ObtenerPayLoad($elt));
		

	$profile = AutentificadorJWT::ObtenerPayLoad($elt);
	//var_dump($profile);
		if($profile->data == "Encargado")
		{
			echo "$profile->data ha hecho una modificación a una venta<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si sos dueño o empleado, no entendés de modificar ventas<br><br>";
		}

		return $response;
	}
}//clase