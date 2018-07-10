<?php

// El mozo crea la comanda

/*
A tener en cuenta

LISTADO - EMPLEADOS (fecha logueo - cant. Operaciones - suspensión - borrado)
*/

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";

require_once "entidades/Administra/ETipoUsuario.php";

// todos los usuarios tienen tipo e id, herencia ni hablar
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
        
        $unmozo->settipo(ETipoUsuario::Mozo);        
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
        $consulta->bindValue(':tipo', $this->pass, PDO::PARAM_INT);
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