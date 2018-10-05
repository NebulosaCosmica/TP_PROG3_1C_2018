<?php


// Incompleta
class Ingreso 
{
    private $idingreso;
    private $fecha;
    private $tipo;
    private $idempleado;
    
    public static function OBJIngreso($fecha,$tipo,$idempleado,$id=-1){
        
        $uningreso = new Ingreso();

        if($id!=-1){$uningreso->setidingreso($id);}        
        
        $uningreso->setfecha($fecha);
        $uningreso->settipo($tipo);        
        $uningreso->setidempleado($idempleado);
        
        return $uningreso;
    }

    public function getidingreso(){return $this->idingreso;}

    public function setidingreso($id){$this->idingreso = $id;}

    public function getfecha(){return $this->fecha;}

    public function setfecha($fecha){$this->fecha = $fecha;}

    public function gettipo(){return $this->tipo;}

    public function settipo($tipo){$this->tipo = $tipo;}

    public function getidempleado(){return $this->idempleado;}

    public function setidempleado($idempleado){$this->idempleado = $idempleado;}
    
    public function InsertarElIngresoParametros(){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into ingresos (fecha,tipoempleado,idempleado)values(:fecha,:tipo,:idempleado)");
        $consulta->bindValue(':fecha',$this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':idempleado', $this->idempleado, PDO::PARAM_INT);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function TraerTodosLosIngresos()
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();             
        $consulta =$objetoAccesoDato->RetornarConsulta("select idingreso,fecha as Fecha,tipoempleado as Tipo,idempleado as IdEmpleado from ingresos");
        $consulta->execute();
            
        $saleningresos = $consulta->fetchAll(PDO::FETCH_CLASS, "ingreso");          
           
        foreach ($saleningresos as $key => $value) {
                
        $savior[] = ingreso::OBJingreso($value->Fecha,$value->Tipo,$value->IdEmpleado,$value->idingreso);
          
        }      
       
        if(isset($savior))
            return $savior;

        return null;
        
    }
    
    public static function TraerIngreso($id){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("select idingreso,fecha as Fecha,tipoempleado as Tipo,idempleado as IdEmpleado from ingresos where idingreso = $id");
                $consulta->execute();
                $elingreso= $consulta->fetchObject('ingreso');
                    
                $savior = ingreso::OBJingreso($elingreso->Fecha,$elingreso->Tipo,$elingreso->IdEmpleado,$elingreso->idingreso);
                      
                         
                if(isset($savior))
                {
                   /* echo "<pre>";
                    var_dump($savior);
                    echo "</pre>";*/
    
                    return $savior;
                }else{
    
                    return null;
    
                }
        
        
    }

    public static function TraerIdIngreso($fecha,$tipo,$idempleado){

        $eling = Ingreso::OBJIngreso($fecha,$tipo,$idempleado);

        $losing = Ingreso::TraerTodosLosIngresos();

        //var_dump($eling);

        foreach ($losing as $key => $value) {
            
            if($value->getfecha() == $eling->getfecha() && $value->gettipo() == $eling->gettipo() && $value->getidempleado() == $eling->getidempleado()){
                
                return $value->getidingreso();
            }
        }

        echo "no se encontraron resultados positivos";

        return null;
    }

    
    
}


?>