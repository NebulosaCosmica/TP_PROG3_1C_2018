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

public function Trabajo(){
    // muestra el listado de pendientes

    // el idpedido corresponde siempre con el idcomanda
    // en la base de datos

    $pend = Pedido::TraerTodosLosPedidos();
    
   // var_dump($pend);

    $lopen=Array();

    foreach ($pend as $key => $value) {
        $lopen[] = Pedido::OBJPedido($value->getidcomanda(),"","","",$value->getpbd(),$value->getestado());
    }

   // var_dump($lopen);

    pastelero::MostrarPendientes($lopen);
}

public static function MostrarPendientes($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Resumen de Pendientes Pasteleros vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID COMANDA</th>";
        echo "<th>PENDIENTE Pastelero</th>"; 
        echo "<th>ESTADO</th>";                 
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($pedidos as $key => $value) {

            echo "<tr>";
            echo "<td >".$value->getidcomanda()."</td>";
            echo "<td >".$value->getpbd()."</td>";
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