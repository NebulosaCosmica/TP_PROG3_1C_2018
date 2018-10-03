<?php

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";


// los socios controlan  todo incluso los pagos

// los socios cierran la mesa

// pagada y cerrada (socio)

// los socios pueden ver el estado de todos los pedidos OK
class Socio implements IApiUsable
{
    private $nombre;
    private $pass;
    private $tipo;
    private $id;
        
    public static function OBJSocio($nombre,$pass,$id=-1){
        
        $unsocio = new Socio();

        if($id!=-1){$unsocio->setid($id);}        
        
        $unsocio->settipo("Socio");        
        $unsocio->setnombre($nombre);
        $unsocio->setpass($pass);

        return $unsocio;
    }

    public function getnombre(){return $this->nombre;}

    public function setnombre($nombre){$this->nombre = $nombre;}

    public function getpass(){return $this->pass;}

    public function setpass($pass){$this->pass = $pass;}
    
    public function getid(){return $this->id;}

    public function setid($idsocio){$this->id = $idsocio;}

    public function gettipo(){return $this->tipo;}

    public function settipo($tipo){$this->tipo = $tipo;}

    public function CargarUno($request, $response, $args){

        $params = $request->getParsedBody();    

        $altasocio = Socio::OBJSocio($params['nombre'],$params['pass']);

        $altasocio->InsertarElSocioParametros();

        echo "Socio bailando en una pata<br><br>";

        $newResponse = $response->withJson($altasocio, 200);  
        return $newResponse;
    }

    public function InsertarElSocioParametros(){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into socios (nombre,pass,tipo)values(:nombre,:pass,:tipo)");
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();

    }

    public function TraerTodos($request, $response, $args){

        $lossocios=socio::TraerTodosLosSocios();                             
        $newResponse = $response->withJson($lossocios, 200);         
        return $newResponse;
    } 

    public static function TraerTodosLosSocios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idsocio,nombre as Nombre, pass as Pass from socios");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salensocios = $consulta->fetchAll(PDO::FETCH_CLASS, "Socio");           

        foreach ($salensocios as $key => $value) {
            
            $savior[] = Socio::OBJSocio($value->Nombre,$value->Pass,$value->idsocio);
        }      
       
            return $savior;
    }

    public function BorrarUno($request, $response, $args){

        $ArrayDeParametros = $request->getParsedBody();

        var_dump($ArrayDeParametros['id']);
        $id=$ArrayDeParametros['id'];
             
        $esocio= new Socio();
        $esocio->setid($id);
        $cantidadDeBorrados=$esocio->BorrarSocio();

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

    public function BorrarSocio()
   {
           $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
          $consulta =$objetoAccesoDato->RetornarConsulta("
              delete 
              from socios 				
              WHERE idsocio=:id");	
              $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
              $consulta->execute();
              return $consulta->rowCount();
   }
    
    public function ModificarUno($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        var_dump($ArrayDeParametros);    	

       $sociomod = new socio();
       
       $sociomod->setid($ArrayDeParametros['id']);
       $sociomod->setnombre($ArrayDeParametros['nombre']);
       $sociomod->setpass($ArrayDeParametros['pass']);
       //$sociomod->settipo($ArrayDeParametros['tipo']);       
       
       $resultado =$sociomod->ModificarSocioParametros();
       $objDelaRespuesta= new stdclass();
       
       $objDelaRespuesta->resultado=$resultado;
       return $response->withJson($objDelaRespuesta, 200);		
   }

   public function ModificarSocioParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update socios 
				set nombre=:nombre,
                pass=:pass
                         
				WHERE idsocio=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
			$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
         //   $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);            
			return $consulta->execute();
     }
     
     public function Cierre($request, $response, $args){


         echo "Acá el Socio pone fin a una comanda con la hora<br>";
 
         echo "Muestra las mesas cerradas <br>";
 
         $algo = $request->getParsedBody();
 
         $manda = (int)$algo["idcomanda"];
         
        
         date_default_timezone_set("America/Argentina/Buenos_Aires");
         
         $reloje = date("H:i:s");   
         
       //var_dump($reloje);
                 
         
        $comand = Comanda::TraerComanda($manda);
         
         $comand->setHoraFin($reloje);
         // var_dump($comand);

         $comand->ModificarComandaUnoParametros();
         
                 
        $lamanada = Comanda::TraerTodasLasComandas();
         
        $losed = array_filter($lamanada,function($elemento){
 
             return $elemento->getHoraFin() != NULL;
             
         });
         
        Comanda::MostrarComandas($losed);
         //echo "<pre>";
         //        Pedido::CerrarPedido($manda);
         
         //ok
        // var_dump($comand);
 //       echo "</pre>";*/
     }

    // si lo nesito, ver lamedia
   // public function TraerUno($request, $response, $args){}

}// Socio

?>