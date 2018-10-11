<?php

use Firebase\JWT\JWT;

class AutentificadorJWT
{
    
    private static $claveSecreta = "tokenresto";
    private static $tipoEncriptacion = ['HS256'];
    private static $aud = null;
    
    public static function CrearToken($datos)
    {
        $ahora = time();        
        /*
         parametros del payload
         https://tools.ietf.org/html/rfc7519#section-4.1
         + los que quieras ej="'app'=> "API REST CD 2017" 
        */
        $payload = array(
        	'iat'=>$ahora,
            'exp' => $ahora + (72000), // 20 horas
            'aud' => self::Aud(),
            'data' => $datos,
            'app'=> "Restó Comanda Vegana",


        );
        return JWT::encode($payload, self::$claveSecreta);
    }
    
    // viendo de manejar excepciones
    public static function VerificarToken($token)
    {
        // en retoque, todavia tocando de oido sordo
        
        if(empty($token))
                {
                    try{

                        throw new Exception("El token esta vacio.");
                     }
    
                    catch (\Exception $e){
                          

                          echo "Token vacío <br><br>";
                          
                        // que hago con estas excepciones?

                        //   echo $e.msgfmt_get_error_message();
                         throw $e;                         
                    }               
                    
                    
                }        
        
        
        // las siguientes lineas lanzan una excepcion, de no ser correcto o de haberse terminado el tiempo       
      
        // parece que esto anda, voy a ver el otro metodo
      try {
            $decodificado = JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        );
        } catch (\Exception $e) {
                
            // Wrong Number of Segments
            echo "Cantidad de segmentos equivocado o Caracteres mal formados<br><br>";


             //echo $e.msgfmt_get_error_message();
          //  throw $e;
        } 
        
        // si no da error,  verifico los datos de AUD que uso para saber de que lugar viene  
        if($decodificado->aud !== self::Aud())
        {
            throw new Exception("No es el usuario valido");
        }
    }
    
   
     public static function ObtenerPayLoad($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        );
    }
     public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        )->data;
    }
    private static function Aud()
    {
        $aud = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        
        return sha1($aud);
    }
}