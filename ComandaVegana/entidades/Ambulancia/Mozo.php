<?php

// El mozo crea la comanda

/*
A tener en cuenta

LISTADO - EMPLEADOS (fecha logueo - cant. Operaciones - suspensión - borrado)
*/

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
     
    //public function TraerUno($request, $response, $args){}

}// mozo

?>