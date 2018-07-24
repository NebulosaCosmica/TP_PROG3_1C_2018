<?php

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";

class Cliente implements IApiUsable
{
    private $nombre;
    private $pass;
    private $tipo;
    private $id;
    
    public function __construct(){}

    public static function OBJCliente($nombre,$pass,$id=-1){
        
        $uncliente = new Cliente();

        if($id!=-1){$uncliente->setid($id);}        
        
        $uncliente->settipo("Cliente");        
        $uncliente->setnombre($nombre);
        $uncliente->setpass($pass);

        return $uncliente;
    }

    public function getnombre(){return $this->nombre;}

    public function setnombre($nombre){$this->nombre = $nombre;}

    public function getpass(){return $this->pass;}

    public function setpass($pass){$this->pass = $pass;}
    
    public function getid(){return $this->id;}

    public function setid($idcliente){$this->id = $idcliente;}

    public function gettipo(){return $this->tipo;}

    public function settipo($tipo){$this->tipo = $tipo;}

    public function CargarUno($request, $response, $args){

        $params = $request->getParsedBody();    

        $altacliente = Cliente::OBJCliente($params['nombre'],$params['pass']);

        $altacliente->InsertarElClienteParametros();

        echo "Cliente bailando en una pata<br><br>";

        $newResponse = $response->withJson($altacliente, 200);  
        return $newResponse;
    }

    public function InsertarElClienteParametros(){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into clientes (nombre,pass,tipo)values(:nombre,:pass,:tipo)");
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();

    }

    public function TraerTodos($request, $response, $args){

        $losclientes=Cliente::TraerTodosLosClientes();                             
        $newResponse = $response->withJson($losclientes, 200);         
        return $newResponse;
    } 

    public static function TraerTodosLosClientes()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idcliente,nombre as Nombre, pass as Pass from clientes");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salenclientes = $consulta->fetchAll(PDO::FETCH_CLASS, "Cliente");           

        foreach ($salenclientes as $key => $value) {
            
            $savior[] = Cliente::OBJCliente($value->Nombre,$value->Pass,$value->idcliente);
        }      
       
            return $savior;
    }

    public function TraerUno($request, $response, $args){}
    public function BorrarUno($request, $response, $args){}
    public function ModificarUno($request, $response, $args){}

}// cliente

?>