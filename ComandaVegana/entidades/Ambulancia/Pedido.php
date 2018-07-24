<?php

// mas adelante veremos si nesito el abm
class Pedido 
{   

    private $pbtv;
    private $pbcca;
    private $ppc;
    private $pbd;

    private $idcomanda;

    public function __construct(){}

    public function OBJPedido($idcomanda,$pbtv = "",$pbcca="",$ppc="",$pbd=""){

        $elpedido = new Pedido();

        $elpedido->setidcomanda($idcomanda);

        if($pbtv !=""){$elpedido->setpbtv($pbtv);}

        if($pbcca !=""){$elpedido->setpbcca($pbcca);}

        if($ppc !=""){$elpedido->setppc($ppc);}

        if($pbd !=""){$elpedido->setpbd($pbd);}

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

    public function InsertarElPedidoParametros()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (pbtv,pbcca,ppc,pbd)values(:pbtv,:pbcca,:ppc,:pbd)");
        if(isset($this->pbtv)){$consulta->bindValue(':pbtv',$this->pbtv, PDO::PARAM_STR);}

        if(isset($this->pbcca)){$consulta->bindValue(':pbcca', $this->pbcca, PDO::PARAM_STR);}
        if(isset($this->ppc)){$consulta->bindValue(':ppc',$this->ppc, PDO::PARAM_STR);}
        if(isset($this->pbd)){$consulta->bindValue(':pbd', $this->pbd, PDO::PARAM_STR);}
        
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }


    public static function TraerTodosLosPedidos(){        

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idpedido,pbtv as PendienteBTV, pbcca as PendienteCCA,ppc as PendientePC,pbd as PendienteBD from pedidos");
			$consulta->execute();			
            // transformar a objeto a uno que sirva ACÁ
            // si no, da todo null en los atributos            
            $salenpedidos = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");           
       
        foreach ($salenpedidos as $key => $value) {
         
            $savior[] = Pedido::OBJPedido($value->idpedido,$value->PendienteBTV,$value->PendienteCCA,$value->PendientePC,$value->PendienteBD);
        }      
       

        if(isset($savior))
            return $savior;

        return null;

    }

    public static function TraerPedido($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select idpedido,pbtv as Bartenders,pbcca as Cerveceros,ppc as Cocineros,pbd as Pasteleros from pedidos where idpedido = $id");
			$consulta->execute();
			$elpedido= $consulta->fetchObject('Pedido');
			return $elpedido;	
    }

    
}// Pedido




?>