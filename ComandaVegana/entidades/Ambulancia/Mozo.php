<?php

// El mozo crea la comanda

/*
A tener en cuenta

LISTADO - EMPLEADOS (fecha logueo - cant. Operaciones - suspensión - borrado)
*/

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";

class Mozo implements IApiUsable
{
    private $nombre;
    private $pass;
    private $idmozo;
    
    public function __construct(){}

    public static function OBJMozo($nombre,$pass,$id=-1){

        $unmozo = new Mozo();

        if($id!=-1){$unmozo->setidmozo($id);}

        $unmozo->setnombre($nombre);
        $unmozo->setpass($pass);

        return $unmozo;
    }

    public function getnombre(){return $this->nombre;}

    public function setnombre($nombre){$this->nombre = $nombre;}

    public function getpass(){return $this->pass;}

    public function setpass($pass){$this->pass = $pass;}
    
    public function getidmozo(){return $this->idmozo;}

    public function setidmozo($idmozo){$this->idmozo = $idmozo;}

   /* public function GenerarComanda(){

        $unacomanda = new Comanda();

        return $unacomanda;
    }*/

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
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mozos (nombre,pass)values(:nombre,:pass)");
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
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
       

            return $savior;
    }

    public function TraerUno($request, $response, $args){}
    public function BorrarUno($request, $response, $args){}
    public function ModificarUno($request, $response, $args){}

}



?>