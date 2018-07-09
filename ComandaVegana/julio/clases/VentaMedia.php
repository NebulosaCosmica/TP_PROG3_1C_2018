<?php
require_once "clases/guia/AccesoDatos.php";
require_once "clases/guia/IApiUsable.php";



class VentaMedia implements IApiUsable{

    private $id;
        private $idmedias;
        private $nombreC;
        private $fecha;
        private $importe;
        private $foto;
    
        public function __construct(){}
    
        public static function OBJVentaMedia($idmedias,$nombreC,$fecha,$importe,$foto,$id = -1){
    
        $unaventamedia = new VentaMedia();
    
        if($id != -1){$unaventamedia->id = $id;}
        $unaventamedia->idmedias = $idmedias;
        $unaventamedia->nombreC = $nombreC;
        $unaventamedia->fecha = $fecha;
        $unaventamedia->importe = $importe;
        $unaventamedia->foto = $foto;
    
        return $unaventamedia;
    
        }
    
        public function getid(){return $this->id;}
    
        public function setid($id){$this->id = $id;}
    
        public function getidmedias(){return $this->idmedias;}
    
        public function setidmedias($idmedias){$this->idmedias = $idmedias;}
    
        public function getnombreC(){return $this->nombreC;}
    
        public function setnombreC($nombreC){$this->nombreC = $nombreC;}
    
        public function getfecha(){return $this->fecha;}
    
        public function setfecha($fecha){$this->fecha = $fecha;}
    
        public function getimporte(){return $this->importe;}
    
        public function setimporte($importe){$this->importe = $importe;}
    
        public function getfoto(){return $this->foto;}
    
        public function setfoto($foto){$this->foto = $foto;}
    
        // metodos implementados de contacto con MW
        public function TraerUno($request, $response, $args){
          
        }    
    
        public static function TraerUnaVenta($id) 
        {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("select idventamedia,idmedias as IdMedias, nombrecliente as NombreC,fecha as Fecha,importe as Importe,foto as Foto from ventamedia where idventamedia = $id");
                $consulta->execute();
                
                $laventamedia= $consulta->fetchObject('VentaMedia');

                $salida = VentaMedia::OBJVentaMedia($laventamedia->IdMedias,$laventamedia->NombreC,$laventamedia->Fecha,$laventamedia->Importe,$laventamedia->Foto,$laventamedia->idventamedia);

                var_dump($salida);
                return $salida;			    
                
        }

        public function TraerTodos($request, $response, $args) {
    
        }    

        public function BorrarUno($request, $response, $args){
            
        }
        
        public static function TraerTodasLasVentas()
        {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("select idventamedia,idmedias as IdMedias, nombrecliente as NombreC,fecha as Fecha,importe as Importe,foto as Foto from ventamedia");
                $consulta->execute();			
                // transformar a objeto media ACÁ
                // si no, da todo null en los atributos
                $assit;            
                $salenventas = $consulta->fetchAll(PDO::FETCH_CLASS, "VentaMedia");           
    
            foreach ($salenventas as $key => $value) {
                
                $assit[] = Media::OBJMedia($value->IdMedias,$value->NombreC,$value->Fecha,$value->Importe,$value->Foto,$value->idventamedia);
            }      
           
            return $assit;
        }
     
        public function CargarUno($request, $response, $args){
        
        $params = $request->getParsedBody();
    
        $archi = $request->getUploadedFiles();
        if(!file_exists("./FotosVentas/")){
            mkdir("./FotosVentas/");
        }
        $destino="./FotosVentas/";
    
        $prevarch = $archi['foto']->getClientFilename();
    
        $ext = explode(".",$prevarch);
        $ext = array_pop($ext);
        
        $archi['foto']->moveTo($destino.$params['idmedias'].$params['nombreC'].$params['fecha'].".".$ext);    
    
        $ruta = ($params['idmedias'].$params['nombreC'].$params['fecha'].".".$ext);
    
        $altaventamedia = VentaMedia::OBJVentaMedia($params['idmedias'],$params['nombreC'],$params['fecha'],$params['importe'],$ruta);
    
    
        $altaventamedia->InsertarLaVentaMediaParametros();
        echo "Venta puesta";
        $newResponse = $response->withJson($altaventamedia, 200);  
        return $newResponse;
            
        }
            // no anda por put
        public function ModificarUno($request, $response, $args) {
                
            /*
            $request->getParsedBodyParam('user_email');
            and route segments from something like
    
            $request->getQueryParam('id');`
             */
            //$response->getBody()->write("<h1>Modificar  uno</h1>");
    
            // a veces no me andan las rutas
    
              //nomianda
            
            // lo atamos con alambre
          
            $ArrayDeParametros = $request->getParsedBody();
           // var_dump($ArrayDeParametros);    	
    
           $ventamediamod = new VentaMedia();
          

           $ventamediamod->setid($ArrayDeParametros['id']);
           $ventamediamod->setidmedias($ArrayDeParametros['idmedias']);
           $ventamediamod->setnombreC($ArrayDeParametros['nombreC']);
           $ventamediamod->setfecha($ArrayDeParametros['fecha']);
           $ventamediamod->setimporte($ArrayDeParametros['importe']);
           // revisar lo de la foto urgente
           $archi = $request->getUploadedFiles();
           /*echo "<pre>";
           
           var_dump($archi);
           echo "</pre>";*/
          
           // ver de hacer lo que pide el enunciado
           if(!file_exists("./Backup/")){
               mkdir("./Backup/");
           }
           $destino="./FotosVentas/";
       
           $prevarch = $archi['foto']->getClientFilename();
       
           $ext = explode(".",$prevarch);
           $ext = array_pop($ext);
           // la foto vieja guardarla en el backup, metodo aparte... .

           VentaMedia::procesarbackup($ArrayDeParametros['id']);
           
           $archi['foto']->moveTo($destino.$ArrayDeParametros['idmedias'].$ArrayDeParametros['nombreC'].$ArrayDeParametros['fecha'].".".$ext);    
    
           $ruta = ($ArrayDeParametros['idmedias'].$ArrayDeParametros['nombreC'].$ArrayDeParametros['fecha'].".".$ext);
       
           $altaventamedia = VentaMedia::OBJVentaMedia($ArrayDeParametros['idmedias'],$ArrayDeParametros['nombreC'],$ArrayDeParametros['fecha'],$ArrayDeParametros['importe'],$ruta,$ArrayDeParametros['id']);
       
              $resultado =$altaventamedia->ModificarVentaMediaParametros();
              $objDelaRespuesta= new stdclass();           
           $objDelaRespuesta->resultado=$resultado;
           return $response->withJson($objDelaRespuesta, 200);		
       }
    
       public function ModificarVentaMediaParametros()
         {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("
                    update ventamedia 
                    set idmedias=:idmedias,
                    nombrecliente=:nombreC,
                    fecha=:fecha,
                    importe=:importe,
                    foto=:foto
                    WHERE idventamedia=:id");
                $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
                $consulta->bindValue(':idmedias',$this->idmedias, PDO::PARAM_INT);
                $consulta->bindValue(':nombreC', $this->nombreC, PDO::PARAM_STR);
                $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
                $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
                $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
                return $consulta->execute();
         }
     
    
        public function InsertarLaVentaMediaParametros()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into ventamedia (idmedias,nombrecliente,fecha,importe,foto)values(:idmedias,:nombreC,:fecha,:importe,:foto)");
            $consulta->bindValue(':idmedias',$this->idmedias, PDO::PARAM_INT);
            $consulta->bindValue(':nombreC', $this->nombreC, PDO::PARAM_STR);
            $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
            $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
            $consulta->execute();		
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }    
    
        public static function procesarbackup($id){

            $lab = VentaMedia::TraerUnaVenta($id);

          //  var_dump($lab->getfoto());
            copy("./FotosVentas/".$lab->getfoto()."","./Backup/bk".$lab->getfoto()."");

            // eliminar la foto vieja

            unlink("./FotosVentas/".$lab->getfoto()."");
        }


    }//Venta Media
?>