<?php

// Incompleta

//TRAER TODOS

class Operacion 
{
    private $idoperacion;
    private $idingreso;
    private $cantidad;    
    
    public static function OBJOperacion($idingreso,$cantidad = 0,$id=-1){
        
        $unaoperacion = new Operacion();
        
        if($id!=-1){$unaoperacion->setidoperacion($id);}        
        
       $unaoperacion->setcantidad($cantidad);
        
        $unaoperacion->setidingreso($idingreso);                
        
        return $unaoperacion;
    }

    public function getidoperacion(){return $this->idoperacion;}

    public function setidoperacion($id){$this->idoperacion = $id;}

    public function getidingreso(){return $this->idingreso;}

    public function setidingreso($idingreso){$this->idingreso = $idingreso;}

    public function getcantidad(){return $this->cantidad;}

    public function setcantidad($cantidad){$this->cantidad = $cantidad;}    
    
    public function InsertarLaOperacionParametros(){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into operaciones (idingreso,cantidad)values(:idingreso,:cantidad)");
        $consulta->bindValue(':idingreso',$this->idingreso, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }    

    public static function SumarOperacion($idingreso){

        //tomar del token los datos del ingreso OK,  

        // si no es un socio OK
        
        
        $change = self::TraerOperacion($idingreso);
        
        $change->setcantidad($change->getcantidad()+1);
               
        $change->ModificarOperacionParametros();



        // sumarte 1 a la cantidad.

        // ver de loguear una vez por dia a los empleados

        // o tener en cuenta solamente el primer logueo

    }

    public static function TraerTodasLasOperaciones()
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();             
        $consulta =$objetoAccesoDato->RetornarConsulta("select idoperacion, idingreso as IdIngreso,cantidad as Cantidad from operaciones");
        $consulta->execute();
            
        $salenoperaciones = $consulta->fetchAll(PDO::FETCH_CLASS, "Operacion");                     
        foreach ($salenoperaciones as $key => $value) {
                
        $savior[] = Operacion::OBJOperacion($value->IdIngreso,$value->Cantidad,$value->idoperacion);
          
        }      
       
        if(isset($savior))
            return $savior;

        return null;
        
    }

    //TIRA ERROR DE NO OBJETO, es un notice, asi que lo dejo para despues... .
    public static function TraerOperacion($idingreso){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("select idoperacion,idingreso as IdIngreso, cantidad as Cantidad from operaciones where idingreso = $idingreso");
                $consulta->execute();
                $laoperacion= $consulta->fetchObject('Operacion');                
                    
               // var_dump($laoperacion);

                $savior = Operacion::OBJOperacion($idingreso,$laoperacion->Cantidad,$laoperacion->idoperacion);
                      
                         
                if(isset($savior))
                {                   
    
                    return $savior;
                }else{
    
                    return null;
    
                }
        
        
    }

    public function ModificarOperacionParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update operaciones 
				set cantidad=:cantidad
                         
				WHERE idoperacion=:id");
			$consulta->bindValue(':id',$this->idoperacion, PDO::PARAM_INT);
			$consulta->bindValue(':cantidad',$this->cantidad, PDO::PARAM_INT);			
         
			return $consulta->execute();
     }
}


?>