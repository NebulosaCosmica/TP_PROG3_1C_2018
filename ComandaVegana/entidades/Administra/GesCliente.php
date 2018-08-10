<?php

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";


// el cliente ve el tiempo restante para su pedido

// el cliente puede entrar a la app y ver

// código de pedido para el cliente junto al codigo de mesa

class GesCliente implements IApiUsable
{
    private $codigomesa;
    private $codigopedido;
    private $idpedido;
    private $id;
    
    public function __construct(){}

    public static function OBJGesCliente($codigomesa,$codigopedido,$idpedido,$id=-1){
        
        $ungescliente = new GesCliente();

        if($id!=-1){$ungescliente->setid($id);}        
        
        $ungescliente->setidpedido($idpedido);        
        $ungescliente->setcodigomesa($codigomesa);
        $ungescliente->setcodigopedido($codigopedido);

        return $ungescliente;
    }

    public function getcodigomesa(){return $this->codigomesa;}

    public function setcodigomesa($codigomesa){$this->codigomesa = $codigomesa;}

    public function getcodigopedido(){return $this->codigopedido;}

    public function setcodigopedido($codigopedido){$this->codigopedido = $codigopedido;}
    
    public function getid(){return $this->id;}

    public function setid($idgescliente){$this->id = $idgescliente;}

    public function getidpedido(){return $this->idpedido;}

    public function setidpedido($idpedido){$this->idpedido = $idpedido;}    

    public function InsertarElGesClienteParametros(){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into clientes (codigomesa,codigopedido,idpedido)values(:codigomesa,:codigopedido,:idpedido)");
        $consulta->bindValue(':codigomesa',$this->codigomesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigopedido', $this->codigopedido, PDO::PARAM_INT);
        $consulta->bindValue(':idpedido', $this->idpedido, PDO::PARAM_INT);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();

    }

    public function TraerTodos($request, $response, $args){

        $losgesclientes=gescliente::TraerTodosLosGesClientes();                             
        $newResponse = $response->withJson($losgesclientes, 200);         
        return $newResponse;
    } 

    public static function TraerTodosLosGesClientes()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idcliente,codigomesa as Mesa, codigopedido as CodigoPedido,idpedido as Pedido from clientes");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salengesclientes = $consulta->fetchAll(PDO::FETCH_CLASS, "GesCliente");           
        foreach ($salengesclientes as $key => $value) {
            
            $savior[] = GesCliente::OBJGesCliente($value->Mesa,$value->CodigoPedido,$value->Pedido,$value->idcliente);
        }      
       
        if(empty($savior))
                return null;
        

        return $savior;
    
    }

    public static function ProximaMesa(){

        $mesada = Self::TraerTodosLosGesClientes();     

        if(empty($mesada) == false){

            // faia con uno sholo
            //$utli = array_pop($mesada);

            $ulti = array_reverse($mesada)[0];
            
            $numerodeseado = $ulti->getcodigomesa()+1;

        }else{
            $numerodeseado = 10000;
        }

        return $numerodeseado;
    }

    public function BorrarUno($request, $response, $args){

        $ArrayDeParametros = $request->getParsedBody();

        var_dump($ArrayDeParametros['id']);
        $id=$ArrayDeParametros['id'];
             
        $egescliente= new GesCliente();
        $egescliente->setid($id);
        $cantidadDeBorrados=$egescliente->BorrarGesCliente();

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

    public function BorrarGesCliente()
   {
           $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
          $consulta =$objetoAccesoDato->RetornarConsulta("
              delete 
              from clientes 				
              WHERE idcliente=:id");	
              $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
              $consulta->execute();
              return $consulta->rowCount();
   }
    
    public function ModificarUno($request, $response, $args) {

        $ArrayDeParametros = $request->getParsedBody();
        var_dump($ArrayDeParametros);    	

       $gesclientemod = new GesCliente();
       
       $gesclientemod->setid($ArrayDeParametros['id']);
       $gesclientemod->setcodigomesa($ArrayDeParametros['codigomesa']);
       $gesclientemod->setcodigopedido($ArrayDeParametros['codigopedido']);
       $gesclientemod->setidpedido($ArrayDeParametros['idpedido']);       
       
       $resultado =$gesclientemod->ModificarGesClienteParametros();
       $objDelaRespuesta= new stdclass();
       
       $objDelaRespuesta->resultado=$resultado;
       return $response->withJson($objDelaRespuesta, 200);		
   }

   public function ModificarGesClienteParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update clientes 
				set codigomesa=:codigomesa,
                codigopedido=:codigopedido,
                idpedido = :idpedido
                         
				WHERE idgescliente=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':codigomesa',$this->codigomesa, PDO::PARAM_INT);
			$consulta->bindValue(':codigopedido', $this->codigopedido, PDO::PARAM_INT);
            $consulta->bindValue(':idpedido', $this->idpedido, PDO::PARAM_INT);            
			return $consulta->execute();
     }

     

     //triple funca
     
    public function ConsultarPedido($request,$response,$args){
     
        $lespedos =  GesCliente::TraerTodosLosGesClientes();

        if(empty($lespedos)){
            echo "No hay cliente";
            return null;
        }

        /*echo "<pre>";
        var_dump($lespedos);
        var_dump($_GET['codigopedido']);
        var_dump($_GET['codigomesa']);
        echo "</pre>";*/
        
        foreach ($lespedos as $key => $value) {
            
            // codigomesa con triple igüal no lo encuentra
            if($_GET['codigopedido'] === $value->getcodigopedido() && $_GET['codigomesa'] == $value->getcodigomesa())
            {
                GesCliente::MostrarPedido($value);

                return $response->withJson($value, 200);		
                
            }
        }


        echo "No hemos encontrado supedido, llame al mozo";

        return $response;        
    }

    public static function MostrarPedido($elpedido){

        //var_dump($elpedido->getidpedido());

        // Filtrar pendientes por id pedido

        // minimo

        // o generar una tabla nueva con valores deseados para el cliente        

        // datos de la comanda. Id comanda == Id pedido

        $pedid = Pedido::TraerPedido($elpedido->getidpedido());
        $command = Comanda::TraerComanda($elpedido->getidpedido());

        //var_dump($command);
        var_dump($pedid);
        
        echo "<table border='2px' solid>";
        echo "<caption>Pedido Generico que el Cliente desea ver</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th> CLIENTE</th>";        
        echo "<th>MOZO</th>";                 
        echo "<th>ESTADO</th>";         
        echo "<th>INICIO</th>";
        echo "<th>FIN</th>";         
            
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";
    
        echo "<tr>";
        echo "<td >".$command->getNC()."</td>";                    
        echo "<td >".$command->getIdMozo()."</td>";  
        echo "<td >".$pedid->getestado()."</td>";  
        echo "<td >".$command->getHoraIni()."</td>";  
        echo "</tr>";

        echo "</tbody>";
        echo "</table>";
    }

    public function CargarUno($request, $response, $args){}
    // si lo nesito, ver lamedia
   // public function TraerUno($request, $response, $args){}

}// gescliente

?>