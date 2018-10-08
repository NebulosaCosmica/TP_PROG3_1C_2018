<?php

require_once "guia/IApiUsable.php";
require_once "guia/AccesoDatos.php";

// seguir

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

     
    public function OperacionesFecha($request, $response, $args){

        $params = $request->getParsedBody();      

        $ciones = Operacion::TraerTodasLasOperaciones();

        $gresos = Ingreso::TraerTodosLosIngresos();

        $idingaop = []; 

        //ingresos por fecha buscada $cfecha
        $cfecha = array_filter($gresos,function($elemento)use($params){

            return $elemento->getfecha() === $params['fecha'];
        });

        
        
        // obsoleto en el metodo siguiente
        foreach ($cfecha as $key => $value) {
            
            //var_dump($value->getidingreso());
            
            $idingaop[] = $value->getidingreso();
        }
        
        
        $cop = array_filter($ciones,function($elemento)use($idingaop){
            
            foreach ($idingaop as $key => $value) {
              
                if($value == $elemento->getidingreso()){
                    return $elemento;
                }
            }
            //return $elemento->getfecha() === $params['fecha'];
        });
        

        //Tengo las operaciones de una fecha especifica
       // var_dump($cop);
        

        //separo por sector


        // ingresos por sector

      $ingmozo =  array_filter($cfecha,function($elemento){

            return $elemento->gettipo() == "Mozo";
        });

        
        $copmozo = array_filter($cop,function($elemento)use($ingmozo){
            
            foreach ($ingmozo as $key => $value) {               
                
               
                if($value->getidingreso() == $elemento->getidingreso()){
                    return $elemento;
                }
            }
        
        });
        
        echo "<pre>";
       // var_dump($copmozo);
        echo "</pre>";

        //sumo las operaciones de los mozos

        $canmozo = 0;
        foreach ($copmozo as $key => $value) {
            
            $canmozo += $value->getcantidad();

        }

    $datosmozos = ["fecha"=>$params['fecha'],"sector"=>"Mozos","cantidad"=>$canmozo];

    //Cerveceros

    // un solo filter?

    //ingresos por fecha buscada $cfecha

    //Tengo las operaciones de una fecha especifica $cop;

    // ingresos por sector Cerv

      $ingcerv =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Cervecero";
    });

    
    $copcerv = array_filter($cop,function($elemento)use($ingcerv){
        
        foreach ($ingcerv as $key => $value) {               
            
           
            if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });

    $cancerv = 0;

    foreach ($copcerv as $key => $value) {
            
        $cancerv += $value->getcantidad();

    }   
    


    
$datoscerv = ["fecha"=>$params['fecha'],"sector"=>"Cerveceros","cantidad"=>$cancerv];

        //bartenders     

      $ingbart =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Bartender";
    });

    
    $copbart = array_filter($cop,function($elemento)use($ingbart){
        
        foreach ($ingbart as $key => $value) {               
            
           
            if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });

    $canbart = 0;

    foreach ($copbart as $key => $value) {
            
        $canbart += $value->getcantidad();

    }   
    
$datosbart = ["fecha"=>$params['fecha'],"sector"=>"Bartenders","cantidad"=>$canbart];

        //cocineros

    // ingresos por sector Cerv

    $ingcoci =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Cocinero";
    });

    
    $copcoci = array_filter($cop,function($elemento)use($ingcoci){
        
        foreach ($ingcoci as $key => $value) {               
            
           
            if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });

    $cancoci = 0;

    foreach ($copcoci as $key => $value) {
            
        $cancoci += $value->getcantidad();

    }   
    
$datoscoci = ["fecha"=>$params['fecha'],"sector"=>"Cocineros","cantidad"=>$cancoci];
        //pasteleros   

    $ingpast =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Pastelero";
    });

    
    $coppast = array_filter($cop,function($elemento)use($ingpast){
        
        foreach ($ingpast as $key => $value) {               
            
           
            if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });

    $canpast = 0;

    foreach ($coppast as $key => $value) {
            
        $canpast += $value->getcantidad();

    }   
    
    
$datospast = ["fecha"=>$params['fecha'],"sector"=>"Pasteleros","cantidad"=>$canpast];


        $datos[] = $datosmozos;
        $datos[] = $datoscerv;
        $datos[] = $datosbart;
        $datos[] = $datoscoci;
        $datos[] = $datospast;        

        //mostrar cantidad de operaciones totales por sector
        self::MostrarOperacionesPorSector($datos);

    }

    

    // cant- de operaciones por empleado Y por sector
    public function OperacionesFechaEmp($request, $response, $args){

        $params = $request->getParsedBody();      

        $ciones = Operacion::TraerTodasLasOperaciones();

        $gresos = Ingreso::TraerTodosLosIngresos();

        
        //ingresos por fecha buscada $cfecha
        $cfecha = array_filter($gresos,function($elemento)use($params){
            
            return $elemento->getfecha() === $params['fecha'];
        });

        
        $cop = array_filter($ciones,function($elemento)use($cfecha){
            
            foreach ($cfecha as $key => $value) {
                
                if($value->getidingreso() == $elemento->getidingreso()){
                    return $elemento;
                }
            }
            //return $elemento->getfecha() === $params['fecha'];
        });

            //separo por sector


        // ingresos por sector mozos

    $ingmozo =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Mozo";
    });

    
    $copmozo = array_filter($cop,function($elemento)use($ingmozo){
        
        foreach ($ingmozo as $key => $value) {               
            
           
            if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });
    
    // separar por empleado, esta separado

    // obtener el nombre del empleado
    
    foreach ($ingmozo as $key => $value) {
        
        $noms[] = Mozo::TraerMozo($value->getidempleado());
    }
    
    echo "<pre>";
   // var_dump($noms);
    echo "</pre>";

    $indicio = 0;

    foreach ($copmozo as $key => $value) {
               
        $xempl = ["fecha"=>$params['fecha'],"id"=>$noms[$indicio]->getid(),"nombre"=>$noms[$indicio]->getnombre(),"cantidad"=>$value->getcantidad()];
        $datos[] = $xempl;

        $indicio ++;
    }


    if(empty($datos) == false)
    {

        Mozo::MostrarReporte($datos);
    }


    $datos= [];

    $xempl = [];

    $indicio = 0;

    $noms = [];

    //Cerveceros

    //ingresos por fecha buscada $cfecha
    
    //Tengo las operaciones de una fecha especifica $cop;    

    $ingcerv =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Cervecero";
    });

    $copcerv = array_filter($cop,function($elemento)use($ingcerv){
        
    foreach ($ingcerv as $key => $value) {               
                       
        if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });
    
    // separar por empleado, esta separado

    // obtener el nombre del empleado
    
    foreach ($ingcerv as $key => $value) {
        
        $noms[] = Cervecero::TraerCervecero($value->getidempleado());
    }   
    
    $indicio = 0;

    foreach ($copcerv as $key => $value) {
               
        $xempl = ["fecha"=>$params['fecha'],"id"=>$noms[$indicio]->getid(),"nombre"=>$noms[$indicio]->getnombre(),"cantidad"=>$value->getcantidad()];
        $datos[] = $xempl;

        $indicio ++;
    }

    Cervecero::MostrarReporte($datos);

    $datos= [];

    $xempl = [];

    $indicio = 0;

    $noms = [];
    
    //bartenders     

    $ingbart =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Bartender";
    });

    $copbart = array_filter($cop,function($elemento)use($ingbart){
        
    foreach ($ingbart as $key => $value) {               
                       
        if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });
    
    // separar por empleado, esta separado

    // obtener el nombre del empleado
    
    foreach ($ingbart as $key => $value) {
        
        $noms[] = Bartender::TraerBartender($value->getidempleado());
    }   
    
    $indicio = 0;

    foreach ($copbart as $key => $value) {
               
        $xempl = ["fecha"=>$params['fecha'],"id"=>$noms[$indicio]->getid(),"nombre"=>$noms[$indicio]->getnombre(),"cantidad"=>$value->getcantidad()];
        $datos[] = $xempl;

        $indicio ++;
    }

    Bartender::MostrarReporte($datos);

    $datos= [];

    $xempl = [];

    $indicio = 0;

    $noms = [];

    //cocineros

    $ingcoci =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Cocinero";
    });

    $copcoci = array_filter($cop,function($elemento)use($ingcoci){
        
    foreach ($ingcoci as $key => $value) {               
                       
        if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });
    
    foreach ($ingcoci as $key => $value) {
        
        $noms[] = Cocinero::TraerCocinero($value->getidempleado());
    }   
    
    $indicio = 0;

    foreach ($copcoci as $key => $value) {
               
        $xempl = ["fecha"=>$params['fecha'],"id"=>$noms[$indicio]->getid(),"nombre"=>$noms[$indicio]->getnombre(),"cantidad"=>$value->getcantidad()];
        $datos[] = $xempl;

        $indicio ++;
    }

    Cocinero::MostrarReporte($datos);

    $datos= [];

    $xempl = [];

    $indicio = 0;

    $noms = [];

    //pasteleros   

    $ingpast =  array_filter($cfecha,function($elemento){

        return $elemento->gettipo() == "Pastelero";
    });

    $coppast = array_filter($cop,function($elemento)use($ingpast){
        
    foreach ($ingpast as $key => $value) {               
                       
        if($value->getidingreso() == $elemento->getidingreso()){
                return $elemento;
            }
        }
    
    });
    
    foreach ($ingpast as $key => $value) {
        
        $noms[] = Pastelero::TraerPastelero($value->getidempleado());
    }   
    
    $indicio = 0;

    foreach ($coppast as $key => $value) {
               
        $xempl = ["fecha"=>$params['fecha'],"id"=>$noms[$indicio]->getid(),"nombre"=>$noms[$indicio]->getnombre(),"cantidad"=>$value->getcantidad()];
        $datos[] = $xempl;

        $indicio ++;
    }

    Pastelero::MostrarReporte($datos);

    $datos= [];

    $xempl = [];

    $indicio = 0;

    $noms = [];

}//OP Fech Emp

public static function MostrarOperacionesPorSector($datos){

     
           echo "<table border='2px' solid>";
            echo "<caption>Resumen de Cantidad de operaciones por sectores vivos</caption>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>FECHA</th>";
            echo "<th>SECTOR</th>";
            echo "<th>OPERACIONES</th>";            
            echo "</thead>";
            echo "</tr>";
            echo "<tbody>";   
            
            
            foreach ($datos as $key => $value) {
                
                echo "<tr>";
                echo "<td>".$value['fecha']."</td>";            
                echo "<td>".$value['sector']."</td>";            
                echo "<td>".$value['cantidad']."</td>";            
                
                echo "</tr>";

            }

    
    
            echo "</tbody>";
            echo "</table>";
    }

    public static function MostrarOperacionesPorEmpleado($datos){

     
        echo "<table border='2px' solid>";
         echo "<caption>Resumen de Cantidad de operaciones por empleado vivo</caption>";
         echo "<thead>";
         echo "<tr>";
         echo "<th>FECHA</th>";
         echo "<th>EMPLEADO</th>";
         echo "<th>TIPO</th>";
         echo "<th>OPERACIONES</th>";            
         echo "</thead>";
         echo "</tr>";
         echo "<tbody>";   
         
         
         foreach ($datos as $key => $value) {
             
             echo "<tr>";
             echo "<td>".$value['fecha']."</td>";            
             echo "<td>".$value['sector']."</td>";            
             echo "<td>".$value['cantidad']."</td>";            
             
             echo "</tr>";

         }

 
 
         echo "</tbody>";
         echo "</table>";
 }

 public function ReportarPedidos(){

    $cerrados = Pedido::TraerTodosLosPedidos();

    
    $pcerrados = array_filter($cerrados,function($elemento){
        
        return $elemento->getestado() == "Cerrado";
    });
    
    echo "<pre>";
    var_dump($pcerrados);
    echo "</pre>";

    // filtrar por fecha

    // combinar con el idcomanda

    // saco el mozo responsable

    // y combino con el idingreso del mozo

    //¿como se de que fecha es el pedido cerrado?

    // ver de poner la fecha con el pedido cerrado y fue

    // agrego comanda fecha o pedido fecha? retoco todos los metodos

    // o puedo tomar la fecha de algun token???
 }
    
    // si lo nesito, ver lamedia
   // public function TraerUno($request, $response, $args){}

}// Socio

?>