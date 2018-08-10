<?php

class Cocinero implements IApiUsable {

private $nombre;
private $id;    
private $pass;
private $tipo;

public function __construct(){

}

public static function OBJCocinero($nombre,$pass,$id=-1){
        
    $uncocinero = new Cocinero();

    if($id!=-1){$uncocinero->setid($id);}        
    
    $uncocinero->settipo("Cocinero");        
    $uncocinero->setnombre($nombre);
    $uncocinero->setpass($pass);

    return $uncocinero;
}

public function getnombre(){return $this->nombre;}

public function setnombre($nombre){$this->nombre = $nombre;}

public function getpass(){return $this->pass;}

public function setpass($pass){$this->pass = $pass;}

public function getid(){return $this->id;}

public function setid($idcocinero){$this->id = $idcocinero;}

public function gettipo(){return $this->tipo;}

public function settipo($tipo){$this->tipo = $tipo;}

public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();    

    $altacocinero = Cocinero::OBJCocinero($params['nombre'],$params['pass']);

    $altacocinero->InsertarElCocineroParametros();

    echo "Cocinero bailando en una pata<br><br>";

    $newResponse = $response->withJson($altacocinero, 200);  
    return $newResponse;

}

public function InsertarElCocineroParametros(){

    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into cocineros (nombre,pass,tipo)values(:nombre,:pass,:tipo)");
    $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
    $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
    $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
    $consulta->execute();		
    return $objetoAccesoDato->RetornarUltimoIdInsertado();

}

public function TraerTodos($request, $response, $args){

    $loscocineros=Cocinero::TraerTodosLosCocineros();                             
    $newResponse = $response->withJson($loscocineros, 200);         
    return $newResponse;
} 

public static function TraerTodosLosCocineros()
{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select idcocinero,nombre as Nombre, pass as Pass from cocineros");
        $consulta->execute();			
        // transformar a objeto a uno que sirva ACÁ
        // si no, da todo null en los atributos            
        $salencocineros = $consulta->fetchAll(PDO::FETCH_CLASS, "Cocinero");           

    foreach ($salencocineros as $key => $value) {
        
        $savior[] = Cocinero::OBJCocinero($value->Nombre,$value->Pass,$value->idcocinero);
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
        $lopen[] = Pedido::OBJPedido($value->getidcomanda(),"","",$value->getppc(),"",$value->getestado());
    }

   // var_dump($lopen);

    cocinero::MostrarPendientes($lopen);
}

public static function MostrarPendientes($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Resumen de Pendientes Cocineros vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID COMANDA</th>";
        echo "<th>PENDIENTE Cocinero</th>";
        echo "<th>ESTADO</th>";                          
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($pedidos as $key => $value) {

            echo "<tr>";
            echo "<td >".$value->getidcomanda()."</td>";
            echo "<td >".$value->getppc()."</td>";
            echo "<td >".$value->getestado()."</td>";
            echo "</tr>";
        }                    


        echo "</tbody>";
        echo "</table>";
}

public function BorrarUno($request, $response, $args){

    $ArrayDeParametros = $request->getParsedBody();

    var_dump($ArrayDeParametros['id']);
    $id=$ArrayDeParametros['id'];
         
    $ecocinero= new Cocinero();
    $ecocinero->setid($id);
    $cantidadDeBorrados=$ecocinero->BorrarCocinero();

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

public function BorrarCocinero()
{
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
      $consulta =$objetoAccesoDato->RetornarConsulta("
          delete 
          from cocineros 				
          WHERE idcocinero=:id");	
          $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
          $consulta->execute();
          return $consulta->rowCount();
}

public function ModificarUno($request, $response, $args) {

    $ArrayDeParametros = $request->getParsedBody();
    var_dump($ArrayDeParametros);    	

   $cocineromod = new Cocinero();
   
   $cocineromod->setid($ArrayDeParametros['id']);
   $cocineromod->setnombre($ArrayDeParametros['nombre']);
   $cocineromod->setpass($ArrayDeParametros['pass']);       
   
   $resultado =$cocineromod->ModificarCocineroParametros();
   $objDelaRespuesta= new stdclass();
   
   $objDelaRespuesta->resultado=$resultado;
   return $response->withJson($objDelaRespuesta, 200);		
}

public function ModificarCocineroParametros()
 {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update cocineros 
            set nombre=:nombre,
            pass=:pass
                     
            WHERE idcocinero=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
     
        return $consulta->execute();
 }

 //public function TraerUno($request, $response, $args){}

}// cocinero
?>