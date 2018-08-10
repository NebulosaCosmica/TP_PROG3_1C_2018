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
    // muestra el listado de pendientes

    // el idpedido corresponde siempre con el idcomanda
    // en la base de datos

    $pend = Pedido::TraerTodosLosPedidos();
    
   // var_dump($pend);

    $lopen=Array();

    foreach ($pend as $key => $value) {
        $lopen[] = Pedido::OBJPedido($value->getidcomanda(),$value->getpbtv(),"","","",$value->getestado());
    }

   // var_dump($lopen);

    Bartender::MostrarPendientes($lopen);
}

public static function MostrarPendientes($pedidos){

    echo "<table border='2px' solid>";
        echo "<caption>Resumen de Pendientes Bartenders vivos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID COMANDA</th>";
        echo "<th>PENDIENTE BARTENDER</th>";
        echo "<th>ESTADO</th>";                          
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($pedidos as $key => $value) {

            echo "<tr>";
            echo "<td >".$value->getidcomanda()."</td>";
            echo "<td >".$value->getpbtv()."</td>";
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