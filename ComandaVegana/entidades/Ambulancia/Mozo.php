<?php

/*
A tener en cuenta

LISTADO - EMPLEADOS (fecha logueo - cant. Operaciones - suspensión - borrado)
*/

// estados de la mesa == estado general de la comanda

    // pendiente o esperando pedido

    // comiendo (mozo, que entrega la comida)

class Mozo implements IApiUsable
{
    private $nombre;
    private $pass;
    private $tipo;
    private $id;
    
    public function __construct(){}

    public static function OBJMozo($nombre,$pass,$id=-1){
        
        $unmozo = new Mozo();

        if($id!=-1){$unmozo->setid($id);}        
        
        $unmozo->settipo("Mozo");        
        $unmozo->setnombre($nombre);
        $unmozo->setpass($pass);

        return $unmozo;
    }

    public function getnombre(){return $this->nombre;}

    public function setnombre($nombre){$this->nombre = $nombre;}

    public function getpass(){return $this->pass;}

    public function setpass($pass){$this->pass = $pass;}
    
    public function getid(){return $this->id;}

    public function setid($idmozo){$this->id = $idmozo;}

    public function gettipo(){return $this->tipo;}

    public function settipo($tipo){$this->tipo = $tipo;}

    public function CargarUno($request, $response, $args){

        $params = $request->getParsedBody();    

        $altamozo = Mozo::OBJMozo($params['nombre'],$params['pass']);

        $altamozo->InsertarElMozoParametros();

        echo "Mozo bailando en una pata<br><br>";

        $newResponse = $response->withJson($altamozo, 200);  
        return $newResponse;
    }

    public function InsertarElMozoParametros(){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mozos (nombre,pass,tipo)values(:nombre,:pass,:tipo)");
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function TraerTodos($request, $response, $args){

        $losmozos=Mozo::TraerTodosLosMozos();                             
        $newResponse = $response->withJson($losmozos, 200);         
        return $newResponse;
    } 

    public static function TraerTodosLosMozos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idmozo,nombre as Nombre, pass as Pass from mozos");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salenmozos = $consulta->fetchAll(PDO::FETCH_CLASS, "Mozo");           
            
        foreach ($salenmozos as $key => $value) {
            
            $savior[] = Mozo::OBJMozo($value->Nombre,$value->Pass,$value->idmozo);
        }      
       
        if(isset($savior))
            return $savior;

        return null;
        
    }

    public function BorrarUno($request, $response, $args){

        $ArrayDeParametros = $request->getParsedBody();

        var_dump($ArrayDeParametros['id']);
        $id=$ArrayDeParametros['id'];
             
        $emozo= new Mozo();
        $emozo->setid($id);
        $cantidadDeBorrados=$emozo->BorrarMozo();

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

    public function BorrarMozo()
   {
           $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
          $consulta =$objetoAccesoDato->RetornarConsulta("
              delete 
              from mozos 				
              WHERE idmozo=:id");	
              $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
              $consulta->execute();
              return $consulta->rowCount();
   }
    
    public function ModificarUno($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        var_dump($ArrayDeParametros);    	

       $mozomod = new mozo();
       
       $mozomod->setid($ArrayDeParametros['id']);
       $mozomod->setnombre($ArrayDeParametros['nombre']);
       $mozomod->setpass($ArrayDeParametros['pass']);       
       
       $resultado =$mozomod->ModificarMozoParametros();
       $objDelaRespuesta= new stdclass();
       
       $objDelaRespuesta->resultado=$resultado;
       return $response->withJson($objDelaRespuesta, 200);		
   }

   public function ModificarMozoParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update mozos 
				set nombre=:nombre,
                pass=:pass
                         
				WHERE idmozo=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
			$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
         
			return $consulta->execute();
     }


     // ACONDICIONAR PROCESO
     public function Proceso($request, $response, $args){

        echo "ACONDICIONAR PROCESO";
        
        // que el socio sea gran hermano
        $elt = $request->getHeaderLine('tokenresto');
        $profile = AutentificadorJWT::ObtenerPayLoad($elt);	
    
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        
        // traer el metodo mostrar proceso
        // que algo va a mostrar
        $laburo = Pendiente::TraerTodosLosPendientes();
    
    /*    if($profile->data->tipo === "Socio"){
    
            Mozo::MostrarProceso(array_filter($laburo,function($elemento){
    
                return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Mozo";
            
               }));
    
    
            Mozo::MostrarProceso(array_filter($laburo,function($elemento){
    
                return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Mozo";
            
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
    
        return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Mozo";
    
       });
       // echo "<pre>";
       //var_dump($listo);
       //echo "</pre>";
    
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
    
    Mozo::MostrarProceso(array_filter($laburo,function($elemento){
    
        return $elemento->getestado() === "Listo Para Servir" && $elemento->gettipoempleado() === "Mozo";
    
       }));
    
    
    $cerveza = array_filter($laburo,function($elemento){
    
        return $elemento->getestado() === "Pendiente" && $elemento->gettipoempleado() === "Mozo";
    
       });
    
       // está desordenado
    
       sort($cerveza);
    
       // cambio el estado en la base de datos
    
       // todo lo que necesito cambiar
    
       // id empleado, hora inicio, hora fin, estado
    
      // var_dump($cerveza);
    
       if(empty($cerveza) == false){
       
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
    
       Mozo::MostrarProceso(array_filter($laburo,function($elemento){
    
        return $elemento->getestado() === "En Proceso" && $elemento->gettipoempleado() === "Mozo";
    
       }));*/
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
     
    //public function TraerUno($request, $response, $args){}

}// mozo

?>