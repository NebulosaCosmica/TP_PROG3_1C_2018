<?php

// mas adelante veremos si nesito el abm, el IapiUsable, etc.

// refleja el funcionamiento de restoran

class Pendiente 
{   

    private $idempleado;
    private $tipoempleado;    
    private $estado;
    private $id;
    private $descripcion;
    private $horainicio;
    private $horafin;    

    private $idpedido;

    // ver que pasa que no da de alta

    // no le gustan los valores null
    public static function OBJPendiente($tipoempleado,$descripcion,$estado,$idpedido,$horainicio = "00:00",$horafin="00:00",$idempleado = 0,$id = 0){

        $elpendiente = new Pendiente();

        $elpendiente->setidpedido($idpedido);

        $elpendiente->settipoempleado($tipoempleado);

        $elpendiente->setidempleado($idempleado);

        if($id != 0){$elpendiente->setid($id);}

        $elpendiente->setdescripcion($descripcion);

        if($horainicio !=""){$elpendiente->sethorainicio($horainicio);}

        if($horafin !=""){$elpendiente->sethorafin($horafin);}

        $elpendiente->setestado($estado);

        return $elpendiente;
    }

    public function InsertarElPendienteParametros()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pendientes (idpedido,idempleado,tipoempleado,descripcion,horainicio,horafin,estado)values(:idpedido,:idempleado,:tipoempleado,:descripcion,:horainicio,:horafin,:estado)");

        if(isset($this->idpedido)){$consulta->bindValue(':idpedido',$this->idpedido, PDO::PARAM_INT);}

        if(isset($this->idempleado)){$consulta->bindValue(':idempleado',$this->idempleado, PDO::PARAM_INT);}

        if(isset($this->tipoempleado)){$consulta->bindValue(':tipoempleado', $this->tipoempleado, PDO::PARAM_STR);}
        if(isset($this->descripcion)){$consulta->bindValue(':descripcion',$this->descripcion, PDO::PARAM_STR);}
        if(isset($this->horainicio)){$consulta->bindValue(':horainicio', $this->horainicio, PDO::PARAM_STR);}

        if(isset($this->horafin)){$consulta->bindValue(':horafin', $this->horafin, PDO::PARAM_STR);}
        if(isset($this->estado)){$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);}
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function getidempleado(){return $this->idempleado;}

    public function setidempleado($idempleado){$this->idempleado = $idempleado;}

    public function getidpedido(){return $this->idpedido;}

    public function setidpedido($idpedido){$this->idpedido = $idpedido;}

    public function gettipoempleado(){return $this->tipoempleado;}

    public function settipoempleado($tipoempleado){$this->tipoempleado = $tipoempleado;}

    public function getid(){return $this->id;}

    public function setid($id){$this->id = $id;}

    public function getdescripcion(){return $this->descripcion;}

    public function setdescripcion($descripcion){$this->descripcion = $descripcion;}

    public function gethorainicio(){return $this->horainicio;}

    public function sethorainicio($horainicio){$this->horainicio = $horainicio;}

    public function gethorafin(){return $this->horafin;}

    public function sethorafin($horafin){$this->horafin = $horafin;}

    public function getestado(){return $this->estado;}

    public function setestado($estado){$this->estado = $estado;}

    


    public static function TraerTodosLosPendientes(){        

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idpedido as IdPedido,idpendiente,idempleado as IdEmpleado, tipoempleado as TipoEmpleado,descripcion as Descripcion, horainicio as Inicio, horafin as Fin, estado as Estado from pendientes");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salenpendientes = $consulta->fetchAll(PDO::FETCH_CLASS, "Pendiente");           
       
        foreach ($salenpendientes as $key => $value) {
         
            $savior[] = Pendiente::OBJPendiente($value->TipoEmpleado,$value->Descripcion,$value->Estado,$value->IdPedido,$value->Inicio,$value->Fin,$value->IdEmpleado,$value->idpendiente);
        }      
       

        if(isset($savior))
            return $savior;

        return null;

    }

    public function ModificarPendienteUnoParametros()
	 {
         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
         $consulta =$objetoAccesoDato->RetornarConsulta("
             update pendientes 
         
                set estado=:estado,
                horainicio = :inicio,
                horafin = :fin,
                 idempleado = :idempleado               
                         
				WHERE idpendiente=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':estado',$this->estado, PDO::PARAM_STR);            
            $consulta->bindValue(':inicio',$this->horainicio, PDO::PARAM_STR);
			$consulta->bindValue(':fin',$this->horafin, PDO::PARAM_STR);
            $consulta->bindValue(':idempleado', $this->idempleado, PDO::PARAM_INT);            
			return $consulta->execute();
	 }
      
    /*public static function TraerPendiente($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select idpendiente,idempleado as IdEmpleadobctipoempleado CerTipoEmpleadoadescripcionocinDescripcion  horainicioteleInicioa  horafinteleFin,s Estado from pendientes where idpendiente = $id");
			$consulta->execute();
            $elpendiente= $consulta->fetchObject('pendiente');
            

                
         $savior = pendiente::OBJpendiente($elpendiente->idpendiente,$elpendiente->Bartenders,$elpendiente->Cerveceros,$elpendiente->Cocineros,$elpendiente->Pasteleros, $elpendiente->Estado);
                  
                     
            if(isset($savior))
            {
                echo "<pre>";
                var_dump($savior);
                echo "</pre>";

                return $savior;
            }else{

                return null;

            }
    
       
			
    }*/

    
}// pendiente
?>