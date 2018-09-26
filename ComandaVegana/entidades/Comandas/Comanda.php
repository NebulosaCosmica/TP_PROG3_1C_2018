<?php

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";

require_once "entidades/Ambulancia/Pedido.php";
require_once "entidades/Ambulancia/Pendiente.php";

require_once "entidades/Administra/GesCliente.php";

class Comanda implements IApiUsable{    
    
    /*A tener en cuenta
    
    7 - LISTADO - ( hora ini; hora fin; importe).
    
    */

    // información necesaria en la comanda

    // vista por el empleado correspondiente    

    private $IdComanda;
    private $IdMozo;
    private $NombreCliente;
    private $HoraIni;
    private $Importe;
    private $HoraFin;
    
    private $FotoMesa;

    // numero de pedido, no? (igual a idcomanda)
    private $ElPedido;   


    public function __construct(){}


    // uso el objcomanda cuando ingreso la comanda 
    // y cuando traigo todas las comandas
    public static function OBJComanda($idmozo,$nombrecliente,$elpedido,$importe = 0,$horaini="",$horafin="",$fotomesa="",$id= -1){
        
        $lacomanda = new Comanda();        
            
        $lacomanda->setIdMozo($idmozo);

        $lacomanda->setNC($nombrecliente);        
        
        $lacomanda->setElPedido($elpedido);

        if($importe != 0){ $lacomanda->setImporte($importe);}

        date_default_timezone_set("America/Argentina/Buenos_Aires");
        if($horaini == ""){ $lacomanda->setHoraIni(date("H:i:s"));}
        else{
            $lacomanda->setHoraIni($horaini);
        }

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

    public function setHoraIni($HoraIni){
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        $this->HoraIni = $HoraIni;}

    public function getImporte(){return $this->Importe;}

    public function setImporte($Importe){$this->Importe = $Importe;}

    public function getHoraFin(){return $this->HoraFin;}

    public function setHoraFin($HoraFin){
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        $this->HoraFin = $HoraFin;}

    public function getFotoMesa(){return $this->FotoMesa;}

    public function setFotoMesa($fotomesa){$this->FotoMesa = $fotomesa;}

    public function getIdComanda(){return $this->IdComanda;}

    public function setIdComanda($IdComanda){$this->IdComanda = $IdComanda;}
    
    public function getIdMozo(){return $this->IdMozo;}

    public function setIdMozo($IdMozo){$this->IdMozo = $IdMozo;}
    

    // datos de la mesa:

    // codigo de 5 caracteres.

    // mis mesas van desde el 10000 al 10080

    // el cliente ve el tiempo restante para su pedido

    // estados de la mesa == estado general de la comanda

    // pendiente o esperando pedido

    // comiendo (mozo, que entrega la comida)

    // pagada y cerrada (socio)
    public function CargarUno($request, $response, $args){

    // ver los datos del empleado que cargó la comanda
    $elt = $request->getHeaderLine('tokenresto');

    $responsable = AutentificadorJWT::ObtenerData($elt)->id;
        
    $params = $request->getParsedBody();    
        
    $unpedido = new Pedido();

    // no mete pendiente
    if(empty($params['bartender']) == false){
        $unpedido->setpbtv($params['bartender']);
        Pedido::MetePendiente("Bartender",$params['bartender'],"Pendiente");
    }

    if(empty($params['cervecero']) == false){
        $unpedido->setpbcca($params['cervecero']);
        Pedido::MetePendiente("Cervecero",$params['cervecero'],"Pendiente");
        
    }

    if(empty($params['cocinero']) == false){
        $unpedido->setppc($params['cocinero']);
        Pedido::MetePendiente("Cocinero",$params['cocinero'],"Pendiente");        
    }

    if(empty($params['pastelero']) == false){
        $unpedido->setpbd($params['pastelero']);
        Pedido::MetePendiente("Pastelero",$params['pastelero'],"Pendiente");        
    }
    
    $unpedido->setestado("Pendiente");

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

        // inserto el pedido

        $altaComanda = Comanda::OBJComanda($responsable,$params['nombrecliente'],$unpedido->InsertarElPedidoParametros(),0,"","",$ruta);
     $elnumerofavorito =  $altaComanda->InsertarLaComandaParametros();   
        
        
        // mostrar codigo alfa OK
        // que revise en la lista de codigos y que si es el mismo, que tire de nuevo... . último detalle
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

        // insert into... .
        
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 5; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        //verificar la tabla clientes y asignar 10000 o el numero que corresponda

        // segun las mesas abiertas

        $mesacon = GesCliente::ProximaMesa();
        
        echo "Comanda puesta en escena<br><br>";
        echo "Código de mesa: $mesacon <br><br>";
        echo "Código del pedido: $string";

        $control = GesCliente::OBJGesCliente($mesacon,$string,$elnumerofavorito);

        $control->InsertarElGesClienteParametros();
        $newResponse = $response->withJson($altaComanda, 200);  

        return $newResponse;

    }else{

        echo "al mozo se le escapó el pedido";        
    }

    // revisar esto
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

    public function TraerTodos($request, $response, $args){
        
        // listado de comandas/pedidos        
        $lascomandas=Socio::TraerTodasLasComandas();                             
        $newResponse = $response->withJson($lascomandas, 200);         
        return $newResponse;
    } 

    public static function TraerTodasLasComandas()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idcomanda,idmozo as Mozo, nombrecliente as Nombre, idpedido as Pedido, fotomesa as Foto, horaini as Inicio, importe as Importe, horafin as Fin from comandas");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salencomandas = $consulta->fetchAll(PDO::FETCH_CLASS, "Comanda");           

        foreach ($salencomandas as $key => $value) {
            
          // Pedido::TraerPedido($value->Pedido)
          // para que se vea el pedido, y no solo el id

            $savior[] = Comanda::OBJComanda($value->Mozo,$value->Nombre,Pedido::TraerPedido($value->Pedido),$value->Importe,$value->Inicio,$value->Fin,$value->Foto,$value->idcomanda);

        }      
       
            return $savior;
    }

    // hay un problema con el select idcomanda 

    // revisar la construccion del objeto OBJComanda
    
    //public static function OBJComanda($idmozo,$nombrecliente,$elpedido,$importe = 0,$horaini="",$horafin="",$fotomesa="",$id= -1)
    public static function TraerComanda($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select idcomanda,idmozo as Mozo,nombrecliente as Nombre,horaini as Inicio,horafin as Fin from comandas where idpedido = $id");
			$consulta->execute();
            $lacomanda= $consulta->fetchObject('Comanda');           
            
            var_dump($lacomanda);

            echo "<pre>";

            var_dump($lacomanda->idcomanda);
            echo "</pre>";

            $savior = Comanda::OBJComanda($lacomanda->Mozo,$lacomanda->Nombre,$idcomanda,0,$lacomanda->Inicio,$lacomanda->Fin,$id);
                  
                     
            if(isset($savior))
            {
        
                return $savior;
            }else{

                return null;

            }
    
       
			
    }

    
  //  public function TraerUno($request, $response, $args){}
    public function BorrarUno($request, $response, $args){}
    public function ModificarUno($request, $response, $args){}
    
}// clase Comanda

?>