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
	
		// todes
		$lossocios = Socio::TraerTodosLosSocios();
		$losmohos = Mozo::TraerTodosLosMozos();
		$losbares = Bartender::TraerTodosLosBartenders();		

		$losveza = Cervecero::TraerTodosLosCerveceros();
		$loscoca = Cocinero::TraerTodosLosCocineros();
		$lospasta = Pastelero::TraerTodosLosPasteleros();
		
		$graciastotales = Array();

		if(isset($losmohos)){

			foreach ($losmohos as $key => $value) {
				$graciastotales[] = $value;
			}
		}

		if(isset($losbares)){
		
			foreach ($losbares as $key => $value) {
				$graciastotales[] = $value;
			}
		}		

		foreach ($lossocios as $key => $value) {
			$graciastotales[] = $value;
		}

		if(isset($losveza)){
		
			foreach ($losveza as $key => $value) {
				$graciastotales[] = $value;
			}
		}	
		
		if(isset($loscoca)){
		
			foreach ($loscoca as $key => $value) {
				$graciastotales[] = $value;
			}
		}	

		if(isset($lospasta)){
		
			foreach ($lospasta as $key => $value) {
				$graciastotales[] = $value;
			}
		}	


		$params = $request->getParsedBody(); 

		

		foreach ($graciastotales as $key => $value) {
			
			if($params['usuario'] === $value->getnombre() && $params['contrasena'] === $value->getpass()) {
	
				$response->getBody()->write('Usuario ingresando correctamente<br><br>');
				$response->getBody()->write("<h3>Bienvenido". $value->getnombre()." </h3>");
				//var_dump($value);

				$losda = ["tipo"=>$value->gettipo(),"id"=>$value->getid()];

				$token= AutentificadorJWT::CrearToken($losda); 

				$newResponse = $response->withJson($token,200);  
				$newResponse = $next($request, $newResponse);

				// atualizar deces
				echo "<pre>";
				echo "<body style='background: palegoldenrod;'>";
				echo "<h1>Ver el gestor de comandas del Restó Vegano edición on line</h1>";
				echo "<h2>Alta calidad</h2>";
				echo "<br><br>";
				//echo " Clientes /clientes/";
				//echo "<br><br>";
				//echo "usuario: Código de mesa. contrasena: Código de pedido";
			   	echo "<br><br>";
				echo " Mozos /mozos/";
			   	echo "<br><br>";
				echo " Comandas /comandas/";
				echo "<br><br>";
				echo " Socios /socios/";
			   	echo "<br><br>";
				echo " Bartenders/bartenders/";
				echo "<br><br>";
				echo " Cerveceros/cerveceros/";
				echo "<br><br>";
				echo " Cocineros/cocineros/";
				echo "<br><br>";
				echo " Pasteleros/pasteleros/";
				echo "<br><br>";
				echo "</pre>";

				return $newResponse;
			}
		}

		// necesité otro foreach, que no andaba bien la balidaziom
		//var_dump($params['usuario']);
		//var_dump($value->getnombre());
		foreach ($graciastotales as $key => $value) {
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

	public function ValidarSocio($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenresto');
		
		//var_dump(AutentificadorJWT::ObtenerPayLoad($elt));
		

		$profile = AutentificadorJWT::ObtenerPayLoad($elt);

		//var_dump($profile);

		if($profile->data->tipo == "Socio")
		{
			echo "El Socio todo lo puede ver<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si no soscio, no podes continuar<br><br>";
		}

		return $response;
	}

	public function ValidarMozo($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenresto');
		$profile = AutentificadorJWT::ObtenerPayLoad($elt);		

		if($profile->data->tipo == "Mozo")
		{
			echo "El Mozo puede<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si no sos Mozo, no podes continuar<br><br>";
		}

		return $response;
	}

	public function ValidarCer($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenresto');
		$profile = AutentificadorJWT::ObtenerPayLoad($elt);		

		if($profile->data->tipo == "Cervecero")
		{
			echo "El Cervecero puede<br><br>";
			// antes del proceso
			$response = $next($request, $response); 


		}else{
			echo "Si no sos Cervecero, no podes continuar<br><br>";
		}

		// despues del proceso
		return $response;
	}

	public function ValidarBart($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenresto');
		$profile = AutentificadorJWT::ObtenerPayLoad($elt);		

		if($profile->data->tipo == "Bartender")
		{
			echo "El Bartender puede<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si no sos Bartender, no podes continuar<br><br>";
		}

		return $response;
	}

	public function ValidarCoci($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenresto');
		$profile = AutentificadorJWT::ObtenerPayLoad($elt);		

		if($profile->data->tipo == "Cocinero")
		{
			echo "El Cocinero puede<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si no sos Cocinero, no podes continuar<br><br>";
		}

		return $response;
	}

	public function ValidarPast($request, $response, $next) {

		$elt = $request->getHeaderLine('tokenresto');
		$profile = AutentificadorJWT::ObtenerPayLoad($elt);		

		if($profile->data->tipo == "Pastelero")
		{
			echo "El Pastelero puede<br><br>";
			$response = $next($request, $response); 
		}else{
			echo "Si no sos Pastelero, no podes continuar<br><br>";
		}

		return $response;
	}

	public function VerificarPedidoCierto($request, $response, $next) {

		// cuando entra
		$response = $next($request, $response); 

		//cuando sale
		return $response;
	}

	/*public function ValidarVenta($request, $response, $next) {

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
	}*/
}//clase MW para Aut