
<?php

require_once "clases/guia/AccesoDatos.php";
require_once "clases/guia/IApiUsable.php";

class Usuario implements IApiUsable{

    private $id;
    private $nombre;
    private $perfil;    
    private $contra;

    public function __construct(){}

    public static function OBJUsuario($nombre,$perfil,$contra,$id=-1){

    $unusuario = new Usuario();

    if($id != -1){$unusuario->id = $id;}
    $unusuario->nombre = $nombre;
    $unusuario->perfil = $perfil;    
    $unusuario->contra = $contra;

    return $unusuario;

    }

    public function getid(){return $this->id;}

    public function setid($id){$this->id = $id;}

    public function getnombre(){return $this->nombre;}

    public function setnombre($nombre){$this->nombre = $nombre;}

    public function getperfil(){return $this->perfil;}

    public function setperfil($perfil){$this->perfil = $perfil;}

    public function getcontra(){return $this->contra;}

    public function setcontra($contra){$this->contra = $contra;}
    
    public function TraerUno($request, $response, $args){
        
    }    

    public function TraerTodos($request, $response, $args) {
            $losusuarios=Usuario::TraerTodosLosUsuarios();

            Usuario::GenerarListadoUsuarios($losusuarios);
           $newResponse = $response->withJson($losusuarios, 200);  
          return $newResponse;
    }

    public static function TraerTodosLosUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select idusuarios,nombre as Nombre, perfil as Perfil, contrasena as Contraseña from usuarios");
			$consulta->execute();			
            // transformar a objeto media ACÁ
            // si no, da todo null en los atributos
            $assit;            
            $salenusuarios = $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");           

        foreach ($salenusuarios as $key => $value) {
            
            $assit[] = Usuario::OBJUsuario($value->Nombre,$value->Perfil,$value->Contraseña,$value->idusuarios);
        }      
       

            return $assit;
    }
    
    public static function GenerarListadoUsuarios($usuarios){
        echo "<pre>";
      //  var_dump($usuarios);
        echo "</pre>";
        echo "<table border='2px' solid>";
        echo "<caption>Resumen de usuarios activos</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>NOMBRE</th>";  
        echo "<th>PERFIL</th>";
        echo "<th>CONTRASEÑA</th>";             
        echo "</thead>";
        echo "</tr>";
        echo "<tbody>";

        foreach ($usuarios as $key => $value) {
            echo "<tr>";

            echo "<td >".$value->getid()."</td>";
            echo "<td >".$value->getnombre()."</td>";
                                 
            echo "<td >".$value->getperfil()."</td>";   
            echo "<td >".$value->getcontra()."</td>";            
        }                    


        echo "</tbody>";
        echo "</table>";
    }

    

    public function CargarUno($request, $response, $args){
     
     // para usar postman necesito 
     // x-www-form-urlencoded     
     // ahora no puedo subir fotos
     // pasé al form data , y ahora anda... 
     // si no,da null     

    $params = $request->getParsedBody();

    $altauser = Usuario::OBJUsuario($params['nombre'],$params['perfil'],$params['contrasena']);

    $altauser->InsertarUsuarioParametros();
    $newResponse = $response->withJson($altauser, 200);  
    return $newResponse;
    
    
    }

    public function BorrarUno($request, $response, $args){}
    public function ModificarUno($request, $response, $args){}

    public function InsertarUsuarioParametros()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (nombre,perfil,contrasena)values(:nombre,:perfil,:contrasena)");
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);        
        $consulta->bindValue(':contrasena', $this->contra, PDO::PARAM_STR);        
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

}//Usuario

?>