<?php

// mas adelante veremos si nesito el abm



class Pedido 
{   

    private $pbtv;
    private $pbcca;
    private $ppc;
    private $pbd;
    private $estado;

    private $idcomanda;

    // el objpedido se usa tambien para traer todos, no va como para insertar

    public static function OBJPedido($idcomanda,$pbtv = "",$pbcca="",$ppc="",$pbd="",$estado ="Pendiente"){

        $elpedido = new Pedido();

        $elpedido->setidcomanda($idcomanda);

        if($pbtv !=""){$elpedido->setpbtv($pbtv);}

        if($pbcca !=""){$elpedido->setpbcca($pbcca);}

        if($ppc !=""){$elpedido->setppc($ppc);}

        if($pbd !=""){$elpedido->setpbd($pbd);}

        $elpedido->setestado($estado);

        return $elpedido;
    }

    public function getpbtv(){return $this->pbtv;}

    public function setpbtv($pbtv){$this->pbtv = $pbtv;}

    public function getpbcca(){return $this->pbcca;}

    public function setpbcca($pbcca){$this->pbcca = $pbcca;}

    public function getppc(){return $this->ppc;}

    public function setppc($ppc){$this->ppc = $ppc;}

    public function getpbd(){return $this->pbd;}

    public function setpbd($pbd){$this->pbd = $pbd;}

    public function getidcomanda(){return $this->idcomanda;}

    public function setidcomanda($idcomanda){$this->idcomanda = $idcomanda;}

    public function getestado(){return $this->estado;}

    public function setestado($estado){$this->estado = $estado;}

    public static function MetePendiente($tipoempleado,$descripcion,$estado){   
        
        // acá resuelvo el idpedido

       // el próximo pedido va a ser mi idpedido

      
       if(empty(Pedido::TraerTodosLosPedidos())){

           $elpendiente = Pendiente::OBJPendiente($tipoempleado,$descripcion,$estado,1);
       }else{

//        var_dump(Pedido::TraerTodosLosPedidos());

        $entero = Pedido::TraerTodosLosPedidos();
        $ultimo = array_pop($entero);

        $nuevo = $ultimo->getidcomanda() + 1;
        //var_dump($nuevo);


        $elpendiente = Pendiente::OBJPendiente($tipoempleado,$descripcion,$estado,$nuevo);
           
       }
       

        $elpendiente->InsertarElPendienteParametros();

    }

    public function InsertarElPedidoParametros()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (pbtv,pbcca,ppc,pbd,estado)values(:pbtv,:pbcca,:ppc,:pbd,:estado)");
        if(isset($this->pbtv)){$consulta->bindValue(':pbtv',$this->pbtv, PDO::PARAM_STR);}

        if(isset($this->pbcca)){$consulta->bindValue(':pbcca', $this->pbcca, PDO::PARAM_STR);}
        if(isset($this->ppc)){$consulta->bindValue(':ppc',$this->ppc, PDO::PARAM_STR);}
        if(isset($this->pbd)){$consulta->bindValue(':pbd', $this->pbd, PDO::PARAM_STR);}
        if(isset($this->estado)){$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);}
        $consulta->execute();	
        
        // a demas agrego el pendiente, por comodidad más arriba


        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }


    public static function TraerTodosLosPedidos(){        

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idpedido,pbtv as PendienteBTV, pbcca as PendienteCCA,ppc as PendientePC,pbd as PendienteBD, estado as Estado from pedidos");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salenpedidos = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");           
       
        foreach ($salenpedidos as $key => $value) {
         
            $savior[] = Pedido::OBJPedido($value->idpedido,$value->PendienteBTV,$value->PendienteCCA,$value->PendientePC,$value->PendienteBD,$value->Estado);
        }      
       

        if(isset($savior))
            return $savior;

        return null;

    }

    public static function TraerPedido($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select idpedido,pbtv as Bartenders,pbcca as Cerveceros,ppc as Cocineros,pbd as Pasteleros,estado as Estado from pedidos where idpedido = $id");
			$consulta->execute();
            $elpedido= $consulta->fetchObject('Pedido');
            
         //   var_dump($elpedido);
                
            $savior = Pedido::OBJPedido($elpedido->idpedido,$elpedido->Bartenders,$elpedido->Cerveceros,$elpedido->Cocineros,$elpedido->Pasteleros, $elpedido->Estado);
                  
                     
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

    public function ModificarPedidoUnoParametros()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update pedidos 
        
               set estado=:estado
                        
               WHERE idpedido=:id");
           $consulta->bindValue(':id',$this->idcomanda, PDO::PARAM_INT);
           $consulta->bindValue(':estado',$this->estado, PDO::PARAM_STR);            
           
           return $consulta->execute();
    }    

    public static function CerrarPedido($comanda){


        $dido = Pedido::TraerPedido($comanda);

        $dido->setestado("Cerrado");

        $dido->ModificarPedidoUnoParametros();      

        return $dido;
    }

    
}// Pedido
?>