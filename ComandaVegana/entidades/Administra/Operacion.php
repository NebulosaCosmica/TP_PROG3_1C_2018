<?php

// Incompleta
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

        //tomar del token los datos del ingreso, 

        // si no es un socio

        // agregar una operacion a la tabla, si ya existe

        // sumarte 1 a la cantidad.

        // ver de loguear una vez por dia a los empleados

        // o tener en cuenta solamente el primer logueo

    }
}


?>