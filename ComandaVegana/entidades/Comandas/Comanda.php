<?php

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";

class Comanda implements IApiUsable{    

    /*
    A tener en cuenta

    // clase ajustada al listado inferior
    7 - LISTADO - ( hora ini; hora fin; importe).
    
    */

    // información necesaria en la comanda

    // vista por el empleado correspondiente

    //ingresa al listado de pendientes

    private $NombreCliente;
    private $FotoMesa;
    private $IdComanda;
    private $HoraIni;
    private $HoraFin;
    private $Importe;
    private $elpedido;

    public function __construct(){}

    public static function OBJComanda($nombrecliente,$horaini,$importe = 0,$horafin="",$fotomesa="",$id= -1){

        $lacomanda = new Comanda();

        $lacomanda->setNC($nombrecliente);

        $lacomanda->setHoraIni($horaini);

        if($importe != 0){
            $lacomanda->setImporte($importe);
        }

        if($horafin != ""){
            $lacomanda->setHoraFin($horafin);
        }

        if($fotomesa != ""){
            $lacomanda->setFotoMesa($fotomesa);
        }

        if($id != -1){
            $lacomanda->setIdComanda($id);
        }


        return $lacomanda;
    }



    public function getNC(){return $this->NombreCliente;}

    public function setNC($nombrecliente){$this->NombreCliente = $nombrecliente;}

    public function getHoraIni(){return $this->HoraIni;}

    public function setHoraIni($HoraIni){$this->HoraIni = $HoraIni;}

    public function getImporte(){return $this->Importe;}

    public function setImporte($Importe){$this->Importe = $Importe;}

    public function getHoraFin(){return $this->HoraFin;}

    public function setHoraFin($HoraFin){$this->HoraFin = $HoraFin;}

    public function getFotoMesa(){return $this->FotoMesa;}

    public function setFotoMesa($fotomesa){$this->FotoMesa = $fotomesa;}

    public function getIdComanda(){return $this->IdComanda;}

    public function setIdComanda($IdComanda){$this->IdComanda = $IdComanda;}
    
    public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();    
    
    $archi = $request->getUploadedFiles();

    //estoy parado en la carpeta ComandaVegana

    if(!file_exists("./fotos/")){
        mkdir("./fotos/");
    }

    if(!file_exists("./fotos/mesacomanda/")){
        mkdir("./fotos/mesacomanda");
    }

    $destino="./fotos/mesacomanda/";

    $prevarch = $archi['fotomesa']->getClientFilename();

    $ext = explode(".",$prevarch);
    $ext = array_pop($ext);    
    
    $archi['fotomesa']->moveTo($destino.$params['nombrecliente'].".".$ext);    
    $ruta = ($params['nombrecliente'].".".$ext);

    $altaComanda = Comanda::OBJComanda($params['nombrecliente'],$ruta);

    $altaComanda->InsertarLaComandaParametros();
    echo "Comanda puesta en escena<br><br>";
    $newResponse = $response->withJson($altaComanda, 200);  
    return $newResponse;

    }

    public function InsertarLaComandaParametros()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into comandas (nombrecliente,fotomesa)values(:nombre,:foto)");
        $consulta->bindValue(':nombre',$this->NombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->FotoMesa, PDO::PARAM_STR);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function TraerUno($request, $response, $args){}
    public function TraerTodos($request, $response, $args){} 
    public function BorrarUno($request, $response, $args){}
    public function ModificarUno($request, $response, $args){}
    
}// clase Comanda

?>