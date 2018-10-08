<?php

class Cervecero implements IApiUsable {

private $nombre;
private $id;    
private $pass;
private $tipo;

public function __construct(){

}

public static function OBJCervecero($nombre,$pass,$id=-1){
        
    $uncervecero = new Cervecero();

    if($id!=-1){$uncervecero->setid($id);}        
    
    $uncervecero->settipo("Cervecero");        
    $uncervecero->setnombre($nombre);
    $uncervecero->setpass($pass);

    return $uncervecero;
}

public function getnombre(){return $this->nombre;}

public function setnombre($nombre){$this->nombre = $nombre;}

public function getpass(){return $this->pass;}

public function setpass($pass){$this->pass = $pass;}

public function getid(){return $this->id;}

public function setid($idcervecero){$this->id = $idcervecero;}

public function gettipo(){return $this->tipo;}

public function settipo($tipo){$this->tipo = $tipo;}

public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();    

    $altacervecero = Cervecero::OBJCervecero($params['nombre'],$params['pass']);

    $altacervecero->InsertarElCerveceroParametros();

    echo "Cervecero bailando en una pata<br><br>";

    $newResponse = $response->withJson($altacervecero, 200);  
    return $newResponse;

}

public function InsertarElCerveceroParametros(){

    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into cerveceros (nombre,pass,tipo)values(:nombre,:pass,:tipo)");
    $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
    $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
    $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
    $consulta->execute();		
    return $objetoAccesoDato->RetornarUltimoIdInsertado();

}

public function TraerTodos($request, $response, $args){

    $loscerveceros=Cervecero::TraerTodosLosCerveceros();                             
    $newResponse = $response->withJson($loscerveceros, 200);         
    return $newResponse;
} 

public static function TraerTodosLosCerveceros()
{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select idcervecero,nombre as Nombre, pass as Pass from cerveceros");
        $consulta->execute();			
        // transformar a objeto a uno que sirva ACÁ
        // si no, da todo null en los atributos            
        $salencerveceros = $consulta->fetchAll(PDO::FETCH_CLASS, "Cervecero");         

    foreach ($salencerveceros as $key => $value) {
        
        $savior[] = Cervecero::OBJCervecero($value->Nombre,$value->Pass,$value->idcervecero);
    }      
     
    if(isset($savior))
        return $savior;

    return null;
        
}

public static function TraerCervecero($id)
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("select nombre as Nombre,pass as Contrasena,tipo as Tipo from cerveceros where idcervecero = $id");
    $consulta->execute();
    $elcervecero= $consulta->fetchObject('cervecero');
        
    $savior = cervecero::OBJcervecero($elcervecero->Nombre,$elcervecero->Contrasena,$id);
          
             
    if(isset($savior))
    {
       
        return $savior;
    }else{

        return null;

    }


}

public function Trabajo(){
    

    $laburo = Pendiente::TraerTodosLosPendientes();

   Cervecero::MostrarPendientes(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Pendiente" && $elemento->gettipoempleado() === "Cervecero";

   }));
}

public static function MostrarPendientes($pedidos){


    echo "<table border='2px' solid>";
        echo "<caption>Resumen de Pendientes Cerveceros vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID PEDIDO</th>";
        echo "<th>DESCRIPCION Cervecero</th>";         
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

public function Proceso($request, $response, $args){

    // que el socio sea gran hermano
    $elt = $request->getHeaderLine('tokenresto');
    $profile = AutentificadorJWT::ObtenerPayLoad($elt);	

    date_default_timezone_set("America/Argentina/Buenos_Aires");
    
    $laburo = Pendiente::TraerTodosLosPendientes();

    if($profile->data->tipo === "Socio"){

        Cervecero::MostrarProceso(array_filter($laburo,function($elemento){

            return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Cervecero";
        
           }));


        Cervecero::MostrarProceso(array_filter($laburo,function($elemento){

            return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Cervecero";
        
           }));   
        


        return $request;
    }

    // verifico la lista de pedidos en proceso, y si alguno supera la hora de finalizacion, lo cambio a "listo para servir"

    // muestro en la misma tabla los listo para servir?!

    // por ahora si

// pendientes genericos
// arriba
//$laburo = Pendiente::TraerTodosLosPendientes();


$listo = array_filter($laburo,function($elemento){

    return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Cervecero";

   });
    /*echo "<pre>";
   var_dump($listo);
   echo "</pre>";*/

    $reloje = date("H:i:s");    

    if(empty($listo) == false){

   foreach ($listo as $key => $value) {
       
    //if()
    

    if($reloje >$value->gethorafin()){
        // FUNCA!!
        //var_dump($value->gethorafin());

        // cambio el estado en la base de datos

   // todo lo que necesito cambiar es el estado

   $value->setestado("Listo Para Servir");  

   $value->ModificarPendienteUnoParametros();


        
    }
    
   }

}else{
    echo "no hay en proceso que estén listopara servir";
}

Cervecero::MostrarProceso(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Cervecero";

   }));


$cerveza = array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Pendiente" && $elemento->gettipoempleado() === "Cervecero";

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

  $nuevafecha = strtotime ( '+5 minute' , strtotime ( $ahoras ) ) ;

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

   Cervecero::MostrarProceso(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Cervecero";

   }));
}

public static function MostrarProceso($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Pedidos Listo Para Servir, y En Proceso Cerveceros vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID PEDIDO</th>";
        echo "<th>DESCRIPCION Cervecero</th>";         
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
         
    $ecervecero= new Cervecero();
    $ecervecero->setid($id);
    $cantidadDeBorrados=$ecervecero->BorrarCervecero();

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

public function BorrarCervecero()
{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
      $consulta =$objetoAccesoDato->RetornarConsulta("
          delete 
          from cerveceros 				
          WHERE idcervecero=:id");	
          $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
          $consulta->execute();
          return $consulta->rowCount();
}

public function ModificarUno($request, $response, $args) {

    $ArrayDeParametros = $request->getParsedBody();
    var_dump($ArrayDeParametros);    	

   $cerveceromod = new Cervecero();
   
   $cerveceromod->setid($ArrayDeParametros['id']);
   $cerveceromod->setnombre($ArrayDeParametros['nombre']);
   $cerveceromod->setpass($ArrayDeParametros['pass']);       
   
   $resultado =$cerveceromod->ModificarCerveceroParametros();
   $objDelaRespuesta= new stdclass();
   
   $objDelaRespuesta->resultado=$resultado;
   return $response->withJson($objDelaRespuesta, 200);		
}

public function ModificarCerveceroParametros()
 {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update cerveceros 
            set nombre=:nombre,
            pass=:pass
                     
            WHERE idcervecero=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
     
        return $consulta->execute();
 }

 public static function MostrarReporte($cerv){


    echo "<table border='2px' solid>";
    echo "<caption>Resumen de Cantidad de operaciones por empleado, CERVECEROS vivos</caption>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>FECHA</th>";
    echo "<th>ID</th>";        
    echo "<th>NOMBRE</th>";
    echo "<th>OPERACIONES</th>";            
    echo "</thead>";
    echo "</tr>";
    echo "<tbody>";   
            
    foreach ($cerv as $key => $value) {
        
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

//public function TraerUno($request, $response, $args){}

}// Cervecero
?>