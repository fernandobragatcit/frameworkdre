<?php
/**
 * Classe ainda a ser implementada para criptografia de dados
 *
 * @author André Coura
 * @since 1.0 - 13/07/2008
 */
class Cryptografia {

    public function __construct() {

    }

    public function cryptData($data){
    	return base64_encode($data);
    }

    public function decryptData($data){
    	return base64_decode($data);
    }

    public function cryptMd5($data){
    	return base64_encode(md5($data));
    }
}
?>