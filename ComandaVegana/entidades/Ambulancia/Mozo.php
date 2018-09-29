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
                $objDelaRespuesta->resultado="algo borro!!!";
           }
           else
           {
               $objDelaRespuesta->resultado="no Borro nada!!!";
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

     // ACTUALIZAR LA TABLA, SI NO NO DETECTA EL CAMBIO DE ESTADO

     //VER VER

     // TRABAJO Y PROCESO

     // ANDAN MAL

     // consulto dos veces seguidas, por los estados

     // listo para servir, una me da 2 y la siguiente 3... .

     // El trabajo cambia el estado "Comiendo" y lo pone de nuevo listo para servir


     // consulto dos veces seguidas, por los estados
     // comiendo, una me da uno, y la siguiente me da 0

     // El proceso cambia una vez a comiendo, y despues lo vuelve listo para servir... .

     public function Trabajo(){    
       
        $laburo = Pendiente::TraerTodosLosPendientes();  

        // la tabla pedidos ya tiene estado. Uso eso para el mozo

        $pedios = Pedido::TraerTodosLosPedidos();  

        $last = array_pop($laburo);       

        
        for ($i=1; $i < $last->getidpedido(); $i++) { 
            
        $banda = 0;  
      
        $elpe = array_filter($laburo,function($elemento)use ($i){
    
                return $elemento->getidpedido() == $i;
            
               });  
               
        foreach ($elpe as $key => $value) {
            
            if($value->getestado() === "Listo Para Servir"){

            }else{
              //  echo "Estado del pedido completo: Pendiente";
                $banda = 1;
                break;
            }
        }       

        if($banda == 0){

        // transformo el pendiente listo a pedido
      
        $pedidomod = Pedido::OBJPedido($i,"","","","","Listo Para Servir");
            $pedidomod->ModificarPedidoUnoParametros();

            // Y transformo listo Pendientes, LPS a Servido

            // en operaciones

            /*echo "<pre>";
            var_dump($elpe);
            echo "</pre>";*/

        
        }
        
        } // for  recorre todos los pedidos

        Mozo::MostrarPedidos(array_filter($pedios,function($elemento){
    
            return $elemento->getestado() === "Listo Para Servir";
        
        }));
    
        
    }


    public static function MostrarPedidos($pedidos){

        echo "<table border='2px' solid>";
            echo "<caption>Resumen de Pedidos para servir y Pedidos que se están Comiendo, Mozos vivos</caption>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID PEDIDO</th>";
            echo "<th>BARTENDER</th>";
            echo "<th>CERVECERO</th>";
            echo "<th>COCINERO</th>";
            echo "<th>PASTELERO</th>";
            echo "<th>ESTADO</th>";                          
            echo "</thead>";
            echo "</tr>";
            echo "<tbody>";
    
            foreach ($pedidos as $key => $value) {
    
                echo "<tr>";
                echo "<td >".$value->getidcomanda()."</td>";
                echo "<td >".$value->getpbtv()."</td>";
                echo "<td >".$value->getpbcca()."</td>";
                echo "<td >".$value->getppc()."</td>";
                echo "<td >".$value->getpbd()."</td>";
                echo "<td >".$value->getestado()."</td>";
                echo "</tr>";
            }                    
    
    
            echo "</tbody>";
            echo "</table>";
    }


    // en operaciones, lo sirve (estado comiendo)    
    
    // el socio les cobra

    // etc... fin parte dos    

    public function Proceso($request, $response, $args){
               
        // que el socio sea gran hermano
        $elt = $request->getHeaderLine('tokenresto');
        $profile = AutentificadorJWT::ObtenerPayLoad($elt);	
    
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        
        // ver de volver a pedir la lista de la base de datos??
        $pedios = Pedido::TraerTodosLosPedidos();  
      
        if($profile->data->tipo === "Socio"){
    
            Mozo::MostrarPedidos(array_filter($pedios,function($elemento){
    
                return $elemento->getestado() === "Listo Para Servir" || $elemento->getestado() === "Comiendo";
            
               }));

   
            // mover a pendiente con todo el tema del socio
            /*Mozo::MostrarPedidos(array_filter($laburo,function($elemento){
   
                return $elemento->getestado() === "En Pedidos" && $elemento->gettipoempleado() === "Mozo";
            
               }));   */
    
            return $request;
        }  
        

    
    $listo = array_filter($pedios,function($elemento){
    
        return $elemento->getestado() === "Listo Para Servir";

    
       });

       // algo falla

       

       // REVISAR, la transformacion
       // en este lugar transformo los estados de los pedidos
       // a Comiendo

       // Y transformo listo Pendientes, LPS a Servido

            // en operaciones

            /*echo "<pre>";
            var_dump($elpe);
            echo "</pre>";*/

     
       //REVISAR el undefined offset

       //ANDA MAL, REVISAR/REHACER

    // de listo tomo el primero y le cambio

    $listo = array_reverse($listo);

    

    $opera = array_pop($listo);

    if(empty($opera) == false)   
      {

        $opera->setestado("Comiendo");

       
    $opera->ModificarPedidoUnoParametros();
       
        
    // CAMBIAR ESTADO DEL PENDIENTE ASOCIADO
    //var_dump($opera->getidcomanda());

    $pendientes = Pendiente::TraerTodosLosPendientes();

    foreach ($pendientes as $key => $value) {
        
        if($value->getidpedido() == $opera->getidcomanda()){

            $value->setestado("Servido");

        }

        $value->ModificarPendienteUnoParametros();
    }


      } else {

        echo "No hay laburo listo para servir";


      }

      $peditres = Pedido::TraerTodosLosPedidos();  
      Mozo::MostrarPedidos(array_filter($peditres,function($elemento){
  
          return $elemento->getestado() === "Comiendo";
  
         }));  
    
    

       // la fecha al cerrar la mesa
     /*   $reloje = date("H:i:s");    
    
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
        echo "no hay en pedidos que estén listopara servir";
    }
        */

    // cambio el estado en la base de datos
    
       // todo lo que necesito cambiar
    
       // id empleado, hora inicio, hora fin, estado
    
      // var_dump($cerveza);
    
       /*if(empty($cerveza) == false){
       
       $cerveza[0]->setestado("En Pedidos");  
      
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
    
       Mozo::MostrarPedidos(array_filter($pedios,function($elemento){
    
        return $elemento->getestado() === "En Pedidos" && $elemento->gettipoempleado() === "Mozo";
    
       }));*/
    }


    // falta el dueño gran hermano
    public function Cierre($request, $response, $args){


       /* echo "Acá el mozo transforma el estado de los pedidos ingresando el idcomanda OK<br>";        

        echo "Ingresa el Importe y el resto le queda al dueño OK<br>";

        echo "En pedidos, se cambia el estado por Completo OK<br>";

        echo "Muestra las mesas cerradas OK<br>";*/

        $algo = $request->getParsedBody();

        $manda = (int)$algo["idcomanda"];
        //var_dump($manda);

        $porte = (int)$algo['importe'];
        //var_dump($porte);
        
        
        // SOCIO
      //  date_default_timezone_set("America/Argentina/Buenos_Aires");
        
      //  $reloje = date("H:i:s");   
        
      //var_dump($reloje);
                
        
        $comand = Comanda::TraerComanda($manda);
        
        $comand->setImporte($porte);
        
        $comand->ModificarComandaUnoParametros();

        Pedido::CerrarPedido($manda);
        
        $lamanada = Comanda::TraerTodasLasComandas();
        
        $losed = array_filter($lamanada,function($elemento){

            return $elemento->getImporte() != 0;
            
        });
        
        Comanda::MostrarComandas($losed);
        //echo "<pre>";
        
        //ok
       // var_dump($comand);
//       echo "</pre>";
    }

    

     
    //public function TraerUno($request, $response, $args){}

}// mozo

?>