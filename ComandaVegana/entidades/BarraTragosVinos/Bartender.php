<?php

class Bartender implements IApiUsable {

private $nombre;
private $id;    
private $pass;
private $tipo;

public function __construct(){

}

public static function OBJBartender($nombre,$pass,$id=-1){
        
    $unbartender = new Bartender();

    if($id!=-1){$unbartender->setid($id);}        
    
    $unbartender->settipo("Bartender");        
    $unbartender->setnombre($nombre);
    $unbartender->setpass($pass);

    return $unbartender;
}

public function getnombre(){return $this->nombre;}

public function setnombre($nombre){$this->nombre = $nombre;}

public function getpass(){return $this->pass;}

public function setpass($pass){$this->pass = $pass;}

public function getid(){return $this->id;}

public function setid($idBartender){$this->id = $idBartender;}

public function gettipo(){return $this->tipo;}

public function settipo($tipo){$this->tipo = $tipo;}

public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();    

    $altabartender = Bartender::OBJBartender($params['nombre'],$params['pass']);

    $altabartender->InsertarElBartenderParametros();

    echo "Bartender bailando en una pata<br><br>";

    $newResponse = $response->withJson($altabartender, 200);  
    return $newResponse;

}

public function InsertarElBartenderParametros(){

    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into bartenders (nombre,pass,tipo)values(:nombre,:pass,:tipo)");
    $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
    $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
    $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
    $consulta->execute();		
    return $objetoAccesoDato->RetornarUltimoIdInsertado();

}

public function TraerTodos($request, $response, $args){

    $losbartenders=Bartender::TraerTodosLosBartenders();                             
    $newResponse = $response->withJson($losbartenders, 200);         
    return $newResponse;
} 

public static function TraerTodosLosBartenders()
{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select idbartender,nombre as Nombre, pass as Pass from bartenders");
        $consulta->execute();			
        // transformar a objeto a uno que sirva ACÁ
        // si no, da todo null en los atributos            
        $salenbartenders = $consulta->fetchAll(PDO::FETCH_CLASS, "Bartender");           

    foreach ($salenbartenders as $key => $value) {
        
        $savior[] = Bartender::OBJBartender($value->Nombre,$value->Pass,$value->idbartender);
    }      
     
    if(isset($savior))
        return $savior;

    return null;
        
}

public function Trabajo(){
    
    $laburo = Pendiente::TraerTodosLosPendientes();

   Bartender::MostrarPendientes(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Pendiente" && $elemento->gettipoempleado() === "Bartender";

   }));
    
}

public static function MostrarPendientes($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Resumen de Pendientes Bartenders vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID PEDIDO</th>";
        echo "<th>DESCRIPCIÓN Bartender</th>";
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

        Bartender::MostrarProceso(array_filter($laburo,function($elemento){

            return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Bartender";
        
           }));


        Bartender::MostrarProceso(array_filter($laburo,function($elemento){

            return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Bartender";
        
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

    return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Bartender";

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

Bartender::MostrarProceso(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Bartender";

   }));


$cerveza = array_filter($laburo,function($elemento){

    return $elemento->getestado() === "Pendiente" && $elemento->gettipoempleado() === "Bartender";

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

   Bartender::MostrarProceso(array_filter($laburo,function($elemento){

    return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Bartender";

   }));
}

public static function MostrarProceso($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Pedidos Listo Para Servir, y En Proceso Bartenders vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID PEDIDO</th>";
        echo "<th>DESCRIPCION Bartender</th>";         
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
         
    $ebartender= new Bartender();
    $ebartender->setid($id);
    $cantidadDeBorrados=$ebartender->BorrarBartender();

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

public function BorrarBartender()
{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
      $consulta =$objetoAccesoDato->RetornarConsulta("
          delete 
          from bartenders 				
          WHERE idbartender=:id");	
          $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
          $consulta->execute();
          return $consulta->rowCount();
}

public function ModificarUno($request, $response, $args) {

    $ArrayDeParametros = $request->getParsedBody();
    var_dump($ArrayDeParametros);    	

   $bartendermod = new Bartender();
   
   $bartendermod->setid($ArrayDeParametros['id']);
   $bartendermod->setnombre($ArrayDeParametros['nombre']);
   $bartendermod->setpass($ArrayDeParametros['pass']);       
   
   $resultado =$bartendermod->ModificarBartenderParametros();
   $objDelaRespuesta= new stdclass();
   
   $objDelaRespuesta->resultado=$resultado;
   return $response->withJson($objDelaRespuesta, 200);		
}

public function ModificarBartenderParametros()
 {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update bartenders 
            set nombre=:nombre,
            pass=:pass
                     
            WHERE idbartender=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
     
        return $consulta->execute();
 }

 //public function TraerUno($request, $response, $args){}

}//bartender
?>