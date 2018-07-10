<?php

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";

require_once "entidades/Ambulancia/Pedido.php";


class Comanda implements IApiUsable{    

    /*
    A tener en cuenta
    
    7 - LISTADO - ( hora ini; hora fin; importe).
    
    */

    // información necesaria en la comanda

    // vista por el empleado correspondiente

    //ingresa al listado de pendientes

    private $IdComanda;
    private $IdMozo;
    private $NombreCliente;
    private $HoraIni;
    private $Importe;
    private $HoraFin;
    
    private $FotoMesa;

    // en la base de datos, el pedido lleva la id comanda
    // pero cuando hago la comanda, el id no se cual es... .
    private $ElPedido;   


    public function __construct(){}

    public static function OBJComanda($idmozo,$nombrecliente,$elpedido,$importe = 0,$horaini="",$horafin="",$fotomesa="",$id= -1){

        
        $lacomanda = new Comanda();        

        // agregar idmozo

        
        $lacomanda->setIdMozo($idmozo);

        $lacomanda->setNC($nombrecliente);
        
        $lacomanda->setElPedido($elpedido->InsertarElPedidoParametros());

        if($importe != 0){ $lacomanda->setImporte($importe);}

        if($horaini == ""){ $lacomanda->setHoraIni(date("H:i:s"));}

        if($horafin != ""){ $lacomanda->setHoraFin($horafin);}

        if($fotomesa != ""){ $lacomanda->setFotoMesa($fotomesa);}

        if($id != -1){ $lacomanda->setIdComanda($id); }

        return $lacomanda;
    }

    public function getNC(){return $this->NombreCliente;}

    public function setNC($nombrecliente){$this->NombreCliente = $nombrecliente;}

    public function getElPedido(){return $this->ElPedido;}

    public function setElPedido($ElPedido){$this->ElPedido = $ElPedido;}

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
    
    public function getIdMozo(){return $this->IdMozo;}

    public function setIdMozo($IdMozo){$this->IdMozo = $IdMozo;}
    
    public function CargarUno($request, $response, $args){

    // ver los datos del empleado que cargó la comanda
    $elt = $request->getHeaderLine('tokenresto');
    
    
    /*echo "<pre>";
    var_dump(AutentificadorJWT::ObtenerData($elt));
        echo "</pre>";   */

    $responsable = AutentificadorJWT::ObtenerData($elt)->id;
        
    $params = $request->getParsedBody();          

    $unpedido = new Pedido();


    if(empty($params['bartender']) == false){
        $unpedido->setpbtv($params['bartender']);
    }

    if(empty($params['cervecero']) == false){
        $unpedido->setpbcca($params['cervecero']);
    }

    if(empty($params['cocinero']) == false){
        $unpedido->setppc($params['cocinero']);
    }

    if(empty($params['pastelero']) == false){
        $unpedido->setpbd($params['pastelero']);
    }
    
    if(empty($request->getUploadedFiles()) == false){
    
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

    if(empty($unpedido)== false){

        $altaComanda = Comanda::OBJComanda($responsable,$params['nombrecliente'],$unpedido,0,"","",$ruta);
        $altaComanda->InsertarLaComandaParametros();
        echo "Comanda puesta en escena<br><br>";
        $newResponse = $response->withJson($altaComanda, 200);  

        return $newResponse;

    }else{

        echo "al mozo se le escapó el pedido";        
    }

    }else {
        if(empty($unpedido)== false){

        $altaComanda = Comanda::OBJComanda($responsable,$params['nombrecliente'],$unpedido);
        $altaComanda->InsertarLaComandaParametros();
    echo "Comanda puesta en escena<br><br>";
    $newResponse = $response->withJson($altaComanda, 200);  
    return $newResponse;
        }else{
            echo "al mozo se le escapó el pedido";
        }
    }
    
    return $response;

    }

    public function InsertarLaComandaParametros()
    {        

        //falta guardar el id del pedido en la tabla comanda
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into comandas (idmozo,nombrecliente,idpedido,fotomesa,horaini,importe,horafin)values(:idmozo,:nombre,:idpedido,:foto,:horaini,:importe,:horafin)");

        $consulta->bindValue(':idmozo',$this->IdMozo, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->NombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idpedido',$this->ElPedido, PDO::PARAM_INT);

        if(isset($this->FotoMesa)){$consulta->bindValue(':foto', $this->FotoMesa, PDO::PARAM_STR); }else{ $consulta->bindValue(':foto', "", PDO::PARAM_STR);}

        $consulta->bindValue(':horaini',$this->HoraIni, PDO::PARAM_STR);

        if(isset($this->Importe)){ $consulta->bindValue(':importe', $this->Importe, PDO::PARAM_INT);    
        }else{ $consulta->bindValue(':importe', 0, PDO::PARAM_INT);}

        if(isset($this->HoraFin)){$consulta->bindValue(':horafin', $this->HoraFin, PDO::PARAM_STR); }else{ $consulta->bindValue(':horafin', "", PDO::PARAM_STR);}

        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function TraerUno($request, $response, $args){}
    public function TraerTodos($request, $response, $args){} 
    public function BorrarUno($request, $response, $args){}
    public function ModificarUno($request, $response, $args){}
    
}// clase Comanda

?>