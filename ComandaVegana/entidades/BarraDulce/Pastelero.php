<?php

class Pastelero implements IApiUsable {

private $nombre;
private $id;    
private $pass;
private $tipo;

public function __construct(){

}

public static function OBJPastelero($nombre,$pass,$id=-1){
        
    $unpastelero = new Pastelero();

    if($id!=-1){$unpastelero->setid($id);}        
    
    $unpastelero->settipo("Pastelero");        
    $unpastelero->setnombre($nombre);
    $unpastelero->setpass($pass);

    return $unpastelero;
}

public function getnombre(){return $this->nombre;}

public function setnombre($nombre){$this->nombre = $nombre;}

public function getpass(){return $this->pass;}

public function setpass($pass){$this->pass = $pass;}

public function getid(){return $this->id;}

public function setid($idpastelero){$this->id = $idpastelero;}

public function gettipo(){return $this->tipo;}

public function settipo($tipo){$this->tipo = $tipo;}

public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();    

    $altapastelero = Pastelero::OBJPastelero($params['nombre'],$params['pass']);

    $altapastelero->InsertarElPasteleroParametros();

    echo "Pastero bailando en una pata<br><br>";

    $newResponse = $response->withJson($altapastelero, 200);  
    return $newResponse;

}

public function InsertarElPasteleroParametros(){

    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pasteleros (nombre,pass,tipo)values(:nombre,:pass,:tipo)");
    $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
    $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
    $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
    $consulta->execute();		
    return $objetoAccesoDato->RetornarUltimoIdInsertado();

}

public function TraerTodos($request, $response, $args){

    $lospasteleros=Pastelero::TraerTodosLosPasteleros();                             
    $newResponse = $response->withJson($lospasteleros, 200);         
    return $newResponse;
} 

public static function TraerTodosLosPasteleros()
{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select idpastelero,nombre as Nombre, pass as Pass from pasteleros");
        $consulta->execute();			
        // transformar a objeto a uno que sirva ACÁ
        // si no, da todo null en los atributos            
        $salenpasteleros = $consulta->fetchAll(PDO::FETCH_CLASS, "Pastelero");           
    foreach ($salenpasteleros as $key => $value) {
        
        $savior[] = Pastelero::OBJPastelero($value->Nombre,$value->Pass,$value->idpastelero);
    }      
     
    if(isset($savior))
        return $savior;

    return null;
        
}

public static function TraerPastelero($id)
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("select nombre as Nombre,pass as Contrasena,tipo as Tipo from pasteleros where idpastelero = $id");
    $consulta->execute();
    $elpastelero= $consulta->fetchObject('Pastelero');
        
    $savior = Pastelero::OBJPastelero($elpastelero->Nombre,$elpastelero->Contrasena,$id);
                       
    if(isset($savior))
    {
       
        return $savior;
    }else{

        return null;

    }
}

public static function MostrarReporte($past){

    echo "<table border='2px' solid>";
    echo "<caption>Resumen de Cantidad de operaciones por empleado, PASTELEROS vivos</caption>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>FECHA</th>";
    echo "<th>ID</th>";        
    echo "<th>NOMBRE</th>";
    echo "<th>OPERACIONES</th>";            
    echo "</thead>";
    echo "</tr>";
    echo "<tbody>";   
            
    foreach ($past as $key => $value) {
        
        echo "<tr>";
        echo "<td>".$value['fecha']."</td>";            
        echo "<td>".$value['id']."</td>";            
        echo "<td>".$value['nombre']."</td>";            
        echo "<td>".$value['cantidad']."</td>";                        
        echo "</tr>";

    }
    echo "</tbody>";
    echo "</table>";

}



public function Trabajo(){

    $laburo = Pendiente::TraerTodosLosPendientes();

   Pastelero::MostrarPendientes(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Pendiente" && $elemento->gettipoempleado() === "Pastelero";

   }));
    
}

public static function MostrarPendientes($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Resumen de Pendientes Pasteleros vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID PEDIDO</th>";
        echo "<th>DESCRIPCIÓN Pastelero</th>";
        echo "<th>ESTADO</th>";                          
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($pedidos as $key => $value) {

            echo "<tr>";
            echo "<td >".$value->getidpedido()."</td>";
            echo "<td >".$value->getdescripcion()."</td>";
            echo "<td >".$value->getestado()."</td>";
            echo "</tr>";
        }                    


        echo "</tbody>";
        echo "</table>";
}

public function ProcesoPendiente($request, $response, $args){

    // que el socio sea gran hermano
    $elt = $request->getHeaderLine('tokenresto');
    $profile = AutentificadorJWT::ObtenerPayLoad($elt);	

    date_default_timezone_set("America/Argentina/Buenos_Aires");
    
    $laburo = Pendiente::TraerTodosLosPendientes();

    if($profile->data->tipo === "Socio"){

        Pastelero::MostrarProceso(array_filter($laburo,function($elemento){

            return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Pastelero";
        
           }));


        Pastelero::MostrarProceso(array_filter($laburo,function($elemento){

            return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Pastelero";
        
           }));   
        


        return $request;
    }

    
    $cerveza = array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Pendiente" && $elemento->gettipoempleado() === "Pastelero";

   });

   // está desordenado

   sort($cerveza);

   // cambio el estado en la base de datos

   // todo lo que necesito cambiar

   // id empleado, hora inicio, hora fin, estado

  // var_dump($cerveza);

   if(empty($cerveza) == false){

     // con esto genero la Operacion
   
    
     $responsable = AutentificadorJWT::ObtenerData($elt)->id;

     $fetchr = AutentificadorJWT::ObtenerData($elt)->fecha;
 
     $typen = AutentificadorJWT::ObtenerData($elt)->tipo;
       
     $filla = Ingreso::TraerIdIngreso($fetchr,$typen,$responsable);
 
     Operacion::SumarOperacion($filla);
 
     // con lo anterior generé la Operacion
   
   $cerveza[0]->setestado("En Proceso");  
  
   $ahoras = date("H:i:s");    
   
   $cerveza[0]->sethorainicio($ahoras);

  $nuevafecha = strtotime ( '+10 minute' , strtotime ( $ahoras ) ) ;

  $finale = date("H:i:s",$nuevafecha);   

   $cerveza[0]->sethorafin($finale);

   // está arriba
   //$elt = $request->getHeaderLine('tokenresto');
   //$profile = AutentificadorJWT::ObtenerPayLoad($elt);		

   $cerveza[0]->setidempleado($profile->data->id);

   $cerveza[0]->ModificarPendienteUnoParametros();
   }else{
       echo "Nada pendiente. Puede descansar un rato mirando su celular.<br><br>";
   }

   Pastelero::MostrarProceso(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Pastelero";

   }));
}

public function ProcesoProceso($request, $response, $args){
    
        // que el socio sea gran hermano
        $elt = $request->getHeaderLine('tokenresto');
        $profile = AutentificadorJWT::ObtenerPayLoad($elt);	
    
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        
        $laburo = Pendiente::TraerTodosLosPendientes();
    
        if($profile->data->tipo === "Socio"){
    
            Pastelero::MostrarProceso(array_filter($laburo,function($elemento){
    
                return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Pastelero";
            
               }));
    
    
            Pastelero::MostrarProceso(array_filter($laburo,function($elemento){
    
                return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Pastelero";
            
               }));   
            
    
    
            return $request;
        }
    
        
    
    
    $listo = array_filter($laburo,function($elemento){
    
        return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Pastelero";
    
       });
        
       sort($listo);
       
          // cambio el estado en la base de datos
       
          // todo lo que necesito cambiar
       
          // id empleado, hora inicio, hora fin, estado
       
         // var_dump($listo);
       
          if(empty($listo) == false){
       
            // con esto genero la Operacion
                 
            $responsable = AutentificadorJWT::ObtenerData($elt)->id;
       
            $fetchr = AutentificadorJWT::ObtenerData($elt)->fecha;
        
            $typen = AutentificadorJWT::ObtenerData($elt)->tipo;
              
            $filla = Ingreso::TraerIdIngreso($fetchr,$typen,$responsable);
        
            Operacion::SumarOperacion($filla);
        
            // con lo anterior generé la Operacion
          
          $listo[0]->setestado("Listo Para Servir");  
         
          $ahoras = date("H:i:s");    
              
            if(empty($listo) == false){
        
          
           $listo[0]->setestado("Listo Para Servir"); 
           
           $listo[0]->sethorafin($ahoras);
    
           $listo[0]->setidempleado($profile->data->id);
        
           $listo[0]->ModificarPendienteUnoParametros();
           
                
            }
            
        
        }else{
            echo "no hay en proceso que estén listopara servir";
        }
    
    Pastelero::MostrarProceso(array_filter($laburo,function($elemento){
    
        return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Pastelero";
    
       }));
    
    }

public static function MostrarProceso($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Pedidos Listo Para Servir, y En Proceso Pasteleros vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID PEDIDO</th>";
        echo "<th>DESCRIPCION Pastelero</th>";         
        echo "<th>ESTADO</th>";         
        echo "<th>INICIO</th>";
        echo "<th>FIN</th>";         
        echo "<th>EMPLEADO</th>";         
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($pedidos as $key => $value) {

            echo "<tr>";
            echo "<td >".$value->getidpedido()."</td>";
            echo "<td >".$value->getdescripcion()."</td>";
            echo "<td >".$value->getestado()."</td>";
            echo "<td >".$value->gethorainicio()."</td>";
            echo "<td >".$value->gethorafin()."</td>";
            echo "<td >".$value->getidempleado()."</td>";
            echo "</tr>";
        }   

        echo "</tbody>";
        echo "</table>";
}
 
public function BorrarUno($request, $response, $args){

    $ArrayDeParametros = $request->getParsedBody();

    var_dump($ArrayDeParametros['id']);
    $id=$ArrayDeParametros['id'];
         
    $epastelero= new Pastelero();
    $epastelero->setid($id);
    $cantidadDeBorrados=$epastelero->Borrarpastelero();

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

public function BorrarPastelero()
{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
      $consulta =$objetoAccesoDato->RetornarConsulta("
          delete 
          from pasteleros 				
          WHERE idpastelero=:id");	
          $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
          $consulta->execute();
          return $consulta->rowCount();
}

public function ModificarUno($request, $response, $args) {

    $ArrayDeParametros = $request->getParsedBody();
    var_dump($ArrayDeParametros);    	

   $pasteleromod = new Pastelero();
   
   $pasteleromod->setid($ArrayDeParametros['id']);
   $pasteleromod->setnombre($ArrayDeParametros['nombre']);
   $pasteleromod->setpass($ArrayDeParametros['pass']);       
   
   $resultado =$pasteleromod->ModificarPasteleroParametros();
   $objDelaRespuesta= new stdclass();
   
   $objDelaRespuesta->resultado=$resultado;
   return $response->withJson($objDelaRespuesta, 200);		
}

public function ModificarPasteleroParametros()
 {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update pasteleros 
            set nombre=:nombre,
            pass=:pass
                     
            WHERE idpastelero=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
     
        return $consulta->execute();
 }

 //public function TraerUno($request, $response, $args){}

}//Pastelero
?>