<?php

class Pedido 
//implements Interface ¿? Ver, clase auxiliar
{
    private $pbtv;
    private $pbcca;
    private $ppc;
    private $pbd;

    public function __construct(){}

    public function OBJPedido($pbtv = "",$pbcca="",$ppc="",$pbd=""){

        $elpedido = new Pedido();

        if($pbtv !=""){$elpedido->setpbtv($pbtv);}

        if($pbcca !=""){$elpedido->setpbcca($pbcca);}

        if($ppc !=""){$elpedido->setppc($ppc);}

        if($pbd !=""){$elpedido->setpbd($ppc);}
    }

    public function getpbtv(){return $this->pbtv;}

    public function setpbtv($pbtv){$this->pbtv = $pbtv;}

        public function getpbcca(){return $this->pbcca;}

    public function setpbcca($pbcca){$this->pbcca = $pbcca;}

        public function getppc(){return $this->ppc;}

    public function setppc($ppc){$this->ppc = $ppc;}

        public function getpbd(){return $this->pbd;}

    public function setpbd($pbd){$this->pbd = $pbd;}
    
}




?>