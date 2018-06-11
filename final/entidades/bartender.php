<?php


// mucha rotación de personal


// clase comanda

// nombre del cliente, 

// el mozo entrega la comanda al bartender

// nombre cliente, detalles de los vinos

// foto de la mesa que hizo el pedido

// cada comanda tiene id de pedido

// el pedido en preparacions tiene un tiempo estimado 


// las mesas tambien tienen un codigo de mesa

// el cliente puede entrar a la app y ver

// al encuesta de la mesa y el mozo son ambiguas

// 



// puede haber más de un empleado en el mismo puesto

// 2 bartenders

// el bartender maneja comandas, tiene un atributo lista de comandas o algo asi


// traer todos

// guardar en base de datos
class Bartender {


    private $nombreCompleto;
    private $idBartender;
    private $dni;
    private $contraseña;

    public function __construct(){

    }

    public function getnombreCompleto(){
        return $this->nombreCompleto;
    }
    public function setnombreCompleto($nombreCompleto){
        $this->nombreCompleto = $nombreCompleto;
    }

    public function getdni(){
        return $this->dni;
    }
    public function setdni($dni){
        $this->dni = $dni;
    }

    public function getcontraseña(){
        return $this->contraseña;
    }
    public function setcontraseña($contraseña){
        $this->contraseña = $contraseña;
    }

    public function getidBartender(){
        return $this->idBartender;
    }

    public function setidBartender($idBartender){
        $this->idBartender = $idBartender;
    }
    
    public static function OBJBartender($nombreCompleto,$dni,$contraseña,$idBartender = ""){

        $bart = new Bartender();

        $bart->setnombreCompleto($nombreCompleto);

        $bart->setdni($dni);

        $bart->setcontraseña($contraseña);

        if($idBartender != ""){
            $bart->setidBartender($idBartender);
        }

        return $bart;

    }

    // tira error, preguntar


    /*public function InsertarElBartenderParametros()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into bartenders (nombreCompleto,dni,contraseña)values(:nombreCompleto,:dni,:contraseña)");
				$consulta->bindValue(':nombreCompleto',$this->getnombreCompleto(), PDO::PARAM_STR);
				$consulta->bindValue(':dni', $this->getdni(), PDO::PARAM_INT);
				$consulta->bindValue(':contraseña', $this->getcontraseña(), PDO::PARAM_STR);
				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }*/

     // tira error, ver el htaccess
     /*public function InsertarElBartender()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into restaurante (nombreCompleto,dni,contraseña)values('".$this->getnombreCompleto()."','".$this->getdni()."','".$this->getcontraseña()."')");
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
				

     }*/

     // le saco el ID que es para la base de datos cuando ande
     
     public function Mostrar(){
        $salida = trim($this->getnombreCompleto())."-".trim($this->getdni())."-".trim($this->getcontraseña());
        return $salida;
    }       
    
    public function GuardarBartender(){
    
        if(!file_exists("archivos")){
            mkdir("archivos");
        }

        $ar = fopen("archivos/Bartenders.txt", "a");
            
        //ESCRIBO EN EL ARCHIVO
        fwrite($ar, $this->Mostrar()."\r\n");		
    
        //CIERRO EL ARCHIVO
        fclose($ar);		
        
        echo "<br>Bartender dado de alta";
    
    }// fin 1

    public static function TraerTodosLosBartenders(){

        $ListaDeBartendersLeidos = array();
        //leo todos los helados del archivo
        $archivo=fopen("archivos/Bartenders.txt","r");
        
        while(!feof($archivo))
        {
            $archAux = fgets($archivo);
            $bartenders = explode("-",$archAux);
                           
          $nombreCompleto ="";// bartenders[0]
          $dni ="";// bartenders[1]
          $contraseña = "";// bartenders [2]
          
    
          // hace que el último objeto vacío no entre en la lista
          if(trim($bartenders[0])!= ""){
            $nombreCompleto = $bartenders[0];
            $dni = $bartenders[1];
            $contraseña = $bartenders[2];
            
            
            $elbar = Bartender::OBJBartender($nombreCompleto,$dni,$contraseña);
                $ListaDeBartendersLeidos[] = $elbar;
                       
            }
            
        }
        fclose($archivo);

       /* echo "<pre>";
        var_dump($ListaDeBartendersLeidos);
        echo "</pre>";*/
     
        return $ListaDeBartendersLeidos;
    }

    public static function ManejarLogin($dni,$contraseña){


        $losbares = Bartender::TraerTodosLosBartenders();
        $ok1 = "";

        $logger;

        foreach ($losbares as $key => $value) {
            
            if($value->getdni()== $dni){

                echo "<br>El Bartender existe <br>";

                $ok1 = 1;

                $logger = $value;

                break;

            }

        }

        if($ok1 == 1){


            // texto TRIM TRIM TRIM
            if(trim($value->getcontraseña()) === trim($contraseña)){

                echo "<br>Log in exitoso <br>";

                return true;

            }else{

                echo "<br> La contraseña no coincide <br>";
                return false;

            }

        } else{

            echo "<br>El Bartender no está registrado <br>";
            return false;
        }

        }


    }// bartender

?>