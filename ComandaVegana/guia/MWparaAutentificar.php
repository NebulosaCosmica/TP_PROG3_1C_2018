<?php

require_once "guia/AutentificadorJWT.php";

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
	
		$losmohos = Mozo::TraerTodosLosMozos();

		$params = $request->getParsedBody(); 		

		foreach ($losmohos as $key => $value) {
			
			if($params['usuario'] === $value->getnombre() && $params['contrasena'] === $value->getpass()) {
	
				$response->getBody()->write('Usuario ingresando correctamente<br><br>');
				$response->getBody()->write("<h3>Bienvenido". $value->getnombre()." </h3>");
				//var_dump($value);

				$losda = ["tipo"=>$value->gettipo(),"id"=>$value->getid()];

				$token= AutentificadorJWT::CrearToken($losda); 

				$newResponse = $response->withJson($token,200);  
				$newResponse = $next($request, $newResponse);
				return $newResponse;
			}
		}

		// necesité otro foreach, que no andaba bien la balidaziom
		//var_dump($params['usuario']);
		//var_dump($value->getnombre());
		foreach ($losmohos as $key => $value) {
		if($params['usuario'] === $value->getnombre()) {
		
			$response->getBody()->write('Contraseña inválida, please try again<br><br>');

			
			return $response;
		}		
		
	}
	$response->getBody()->write('Usuario no registrado, please contact admin<br><br>');
	return $response;			
		
	}//verificar usuario	
		
	public function ValidarToc($request, $response, $next) {

		
		try 
		{
			$elt = $request->getHeaderLine('tokenresto');

			AutentificadorJWT::verificarToken($elt);

			//var_dump(AutentificadorJWT::ObtenerPayLoad($elt));
		
			// el token
			//var_dump($elt);
			$esValido=true;      
			}
			catch (Exception $e) {      
				//guardar en un log
				echo $e;
			}
			if( $esValido)
			{					 
					echo "ok<br>";
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