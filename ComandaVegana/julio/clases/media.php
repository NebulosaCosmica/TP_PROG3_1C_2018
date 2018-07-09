<?php

require_once "clases/guia/AccesoDatos.php";
require_once "clases/guia/IApiUsable.php";

// no anda... .
//use guzzlehttp\psr7\src\LazyOpenStream;

class Media implements IApiUsable{

    private $id;
    private $color;
    private $marca;
    private $precio;
    private $talle;
    private $foto;

    public function __construct(){}

    public static function OBJMedia($color,$marca,$precio,$talle,$foto,$id = -1){

    $unamedia = new Media();

    if($id != -1){$unamedia->id = $id;}
    $unamedia->color = $color;
    $unamedia->marca = $marca;
    $unamedia->precio = $precio;
    $unamedia->talle = $talle;
    $unamedia->foto = $foto;

    return $unamedia;

    }

    public function getid(){return $this->id;}

    public function setid($id){$this->id = $id;}

    public function getcolor(){return $this->color;}

    public function setcolor($color){$this->color = $color;}

    public function getmarca(){return $this->marca;}

    public function setmarca($marca){$this->marca = $marca;}

    public function getprecio(){return $this->precio;}

    public function setprecio($precio){$this->precio = $precio;}

    public function gettalle(){return $this->talle;}

    public function settalle($talle){$this->talle = $talle;}

    public function getfoto(){return $this->foto;}

    public function setfoto($foto){$this->foto = $foto;}

    // metodos implementados de contacto con MW
    public function TraerUno($request, $response, $args){
        /*$params = $request->getQueryParams();
        return $response->write("Hello " . var_dump($params));*/
        // lo atamos con alambre
       if($args)
            $id=$args['id'];       

        $params = $request->getQueryParams();
      //  var_dump($params);        
    	$lamedia =Media::TraerUnaMedia($params['id']);
     	$newResponse = $response->withJson($lamedia, 200);  
    	return $newResponse;
    }    

    public static function TraerUnaMedia($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select idmedias,color as Color, marca as Marca,precio as Precio,talle as Talle,foto as Foto from medias where idmedias = $id");
			$consulta->execute();
			$lamemedia= $consulta->fetchObject('Media');
			return $lamemedia;				

			
    }
    
    public function Nomostrar($req,$resp,$next){

        // cuando vuelve
        // NO me sale el ultimo paso
        $next($req,$resp);

//        $mw_response = $resp->__toString();
        //no
    //    $eltoque = $resp->getParsedBody();

        //var_dump($eltoque);

    //    $requestobject = json_decode($mw_response);



       // $request = $request->withParsedBody($requestobject);
        //var_dump($request);
        /*$dato = $resp->__toString();        
        
        var_dump($dato);*/

        // Get response set by middleware.

    // Now reset the response.

    echo "las bolas, la tabla se imprime directamente cuando pasa, como acá =>...";

    $Lasmedias=Media::TraerTodasLasMedias();          
    Media::GenerarListadoMediasNomostrar($Lasmedias);

    $resp = new \Slim\Http\Response();
        
        

        return $resp;

        // no me sale una 
        /*$resp = $next($req, $resp);   					              
        $newStream = new \GuzzleHttp\Psr7\LazyOpenStream('..\vendor\guzzlehttp\psr7\src\LazyOpenStream.php', 'r');
        
        
        //var_dump($resp->__toString());
        $newResponse = $resp->withBody($newStream);        
        
        return $newResponse;*/
    }

    public function TraerTodos($request, $response, $args) {

            // tira null

            $Lasmedias=Media::TraerTodasLasMedias();                      
            $salen = Media::GenerarListadoMedias($Lasmedias);
            $newResponse = $response->withJson($salen, 200);  
           //$newResponse = $response->withJson(Media::GenerarListadoMedias($Lasmedias), 200);  
          return $newResponse;
    }

    //metodos de acceso a datos
    public static function TraerTodasLasMedias()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idmedias,color as Color, marca as Marca,precio as Precio,talle as Talle,foto as Foto from medias");
			$consulta->execute();			
            // transformar a objeto media ACÁ
            // si no, da todo null en los atributos
            $assit;            
            $salenmedias = $consulta->fetchAll(PDO::FETCH_CLASS, "Media");           

        foreach ($salenmedias as $key => $value) {
            
            $assit[] = Media::OBJMedia($value->Color,$value->Marca,$value->Precio,$value->Talle,$value->Foto,$value->idmedias);
        }      
       

            return $assit;
    }
    
    public static function GenerarListadoMedias($medias){
     // no sale   $build ="<table border='2px' solid><caption>Resumen de medias vivas</caption><thead><tr><th>ID</th>";/*          
       
        echo "<table border='2px' solid>";
        echo "<caption>Resumen de medias vivas</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>COLOR</th>";  
        echo "<th>MARCA</th>"; 
        echo "<th>PRECIO</th>";
        echo "<th>TALLE</th>";
        echo "<th>FOTO</th>";
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($medias as $key => $value) {
            echo "<tr>";

                      

            echo "<td >".$value->getid()."</td>";
            echo "<td >".$value->getcolor()."</td>";
                                 
            echo "<td >".$value->getmarca()."</td>";
            echo "<td >".$value->getprecio()."</td>";
            echo "<td >".$value->gettalle()."</td>";

            echo "<td ><img height = '200px' width='200px' src='./fotos/".$value->getfoto()."' alt='imagensale.punk'></td>";            
            echo "</tr>";
        }                    


        echo "</tbody>";
        echo "</table>";
        
    }

    

    public function CargarUno($request, $response, $args){
     
     //header content type  x-www-form-urlencoded         

     // tira objeto vacío

    $params = $request->getParsedBody();

    $archi = $request->getUploadedFiles();
    if(!file_exists("./fotos/")){
        mkdir("./fotos/");
    }
    $destino="./fotos/";

    $prevarch = $archi['foto']->getClientFilename();

    $ext = explode(".",$prevarch);
    $ext = array_pop($ext);
    
    $archi['foto']->moveTo($destino.$params['marca'].$params['color'].$params['talle'].".".$ext);    

    $ruta = ($params['marca'].$params['color'].$params['talle'].".".$ext);

    $altamedia = Media::OBJMedia($params['color'],$params['marca'],$params['precio'],$params['talle'],$ruta);


    $altamedia->InsertarLaMediaParametros();
    echo "Media puesta";
    $newResponse = $response->withJson($altamedia, 200);  
    return $newResponse;
        
    }

    public function BorrarUno($request, $response, $args){

        //nomianda
      //  $ArrayDeParametros = $request->getParsedBody();
        /*$params = $request->getQueryParams();
        return $response->write("Hello " . var_dump($params));*/
        // lo atamos con alambre
        $ArrayDeParametros = $request->getParsedBody();
        $id=$ArrayDeParametros['id'];
             
        $emedia= new Media();
        $emedia->setid($id);
        $cantidadDeBorrados=$emedia->BorrarMedia();

        $objDelaRespuesta= new stdclass();
       $objDelaRespuesta->cantidad=$cantidadDeBorrados;
       if($cantidadDeBorrados>0)
           {
                $objDelaRespuesta->resultado="algo borró!!!";
           }
           else
           {
               $objDelaRespuesta->resultado="no Borró nada!!!";
           }
       $newResponse = $response->withJson($objDelaRespuesta, 200);  
         return $newResponse;
   }

   public function BorrarMedia()
   {
           $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
          $consulta =$objetoAccesoDato->RetornarConsulta("
              delete 
              from medias 				
              WHERE idmedias=:id");	
              $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
              $consulta->execute();
              return $consulta->rowCount();
   }
    
    public function ModificarUno($request, $response, $args) {

        
        /*
        $request->getParsedBodyParam('user_email');
        and route segments from something like

        $request->getQueryParam('id');`
         */
        //$response->getBody()->write("<h1>Modificar  uno</h1>");

        // a veces no me andan las rutas

          //nomianda
        
        // lo atamos con alambre
      
        $ArrayDeParametros = $request->getParsedBody();
        var_dump($ArrayDeParametros);    	

       $mediamod = new Media();
       
       $mediamod->setid($ArrayDeParametros['id']);
       $mediamod->setcolor($ArrayDeParametros['color']);
       $mediamod->setmarca($ArrayDeParametros['marca']);
       $mediamod->setprecio($ArrayDeParametros['precio']);
       $mediamod->settalle($ArrayDeParametros['talle']);
       // revisar lo de la foto urgente
       $archi = $request->getUploadedFiles();
      
       if(!file_exists("./fotos/")){
           mkdir("./fotos/");
       }
       $destino="./fotos/";
   
       $prevarch = $archi['foto']->getClientFilename();
   
       $ext = explode(".",$prevarch);
       $ext = array_pop($ext);
       
       $archi['foto']->moveTo($destino.$ArrayDeParametros['marca'].$ArrayDeParametros['color'].$ArrayDeParametros['talle'].".".$ext);    
   
       $ruta = ($ArrayDeParametros['marca'].$ArrayDeParametros['color'].$ArrayDeParametros['talle'].".".$ext);

       $mediamod->setfoto($ruta);

       // la foto vieja guardarla en el backup, metodo aparte... .

       //o borrar la foto vieja

          $resultado =$mediamod->ModificarMediaParametros();
          $objDelaRespuesta= new stdclass();
       //var_dump($resultado);
       $objDelaRespuesta->resultado=$resultado;
       return $response->withJson($objDelaRespuesta, 200);		
   }

   public function ModificarMediaParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update medias 
				set color=:color,
				marca=:marca,
                precio=:precio,
                talle=:talle,
                foto=:foto
				WHERE idmedias=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':color',$this->color, PDO::PARAM_STR);
			$consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':talle', $this->talle, PDO::PARAM_STR);
            $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
			return $consulta->execute();
	 }
 

    public function InsertarLaMediaParametros()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into medias (color,marca,precio,talle,foto)values(:color,:marca,:precio,:talle,:foto)");
        $consulta->bindValue(':color',$this->color, PDO::PARAM_STR);
        $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':talle', $this->talle, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function GenerarListadoMediasNomostrar($medias){
        echo "<pre>";
      //  var_dump($medias);
        echo "</pre>";
        echo "<table border='2px' solid>";
        echo "<caption>Resumen de medias vivas</caption>";
        echo "<thead>";
        echo "<tr>";        
        echo "<th>COLOR</th>";  
        echo "<th>MARCA</th>";         
        echo "<th>TALLE</th>";
        echo "<th>FOTO</th>";
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($medias as $key => $value) {
            echo "<tr>";

                      

            
            echo "<td >".$value->getcolor()."</td>";
                                 
            echo "<td >".$value->getmarca()."</td>";
            
            echo "<td >".$value->gettalle()."</td>";

            echo "<td ><img height = '200px' width='200px' src='./fotos/".$value->getfoto()."' alt='imagensale.punk'></td>";            
            echo "</tr>";
        }                    


        echo "</tbody>";
        echo "</table>";
    }

}//Media

?>