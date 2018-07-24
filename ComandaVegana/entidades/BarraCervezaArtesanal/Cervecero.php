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

public function Trabajo(){
    // muestra el listado de pendientes

    // el idpedido corresponde siempre con el idcomanda
    // en la base de datos

    $pend = Pedido::TraerTodosLosPedidos();
    
   // var_dump($pend);

    $lopen=Array();

    foreach ($pend as $key => $value) {
        $lopen[] = Pedido::OBJPedido($value->getidcomanda(),"",$value->getpbcca());
    }

   // var_dump($lopen);

    Cervecero::MostrarPendientes($lopen);
}

public static function MostrarPendientes($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Resumen de Pendientes Cerveceros vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID COMANDA</th>";
        echo "<th>PENDIENTE Cervecero</th>";         
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($pedidos as $key => $value) {

            echo "<tr>";
            echo "<td >".$value->getidcomanda()."</td>";
            echo "<td >".$value->getpbcca()."</td>";
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

//public function TraerUno($request, $response, $args){}

}// Cervecero
?>