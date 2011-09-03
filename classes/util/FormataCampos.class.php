<?php
require_once(FWK_EXCEPTION."FieldsException.class.php");
/**
 * Classe statica de formatação de campos diversos
 *
 * @author André Coura
 * @since 1.0 - 20/08/2007
 */

 class FormataCampos{



	  /**
	   * Método de formatação de campos a serem salvos no banco de dados
	   *
	   * @author André Coura
	   * @since 1.1 - 28/08/2007
	   */
	   public static function indexDBCampos($strInput){
		return ($strInput==""||!$strInput)?"null":"'".htmlspecialchars($strInput, ENT_QUOTES)."'";
	   }
	  /**
	   * Contador do numero de bytes de uma determinada string
	   *
	   * @author php.net
	   * @author André Coura
	   * @since 1.0 - 28/09/2007
	   * @param String texto a ser calculado
	   * @return int número de bytes calculado
	   */
	   public static function strBytes($str) {
	      $strlen_var = strlen($str);
	      $d = 0;
	      for ($c = 0; $c < $strlen_var; ++$c) {
	          $ord_var_c = ord($str{$d});
	          switch (true) {
	              case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
	                  $d++;
	                  break;
	              case (($ord_var_c & 0xE0) == 0xC0):
	                  $d+=2;
	                  break;
	              case (($ord_var_c & 0xF0) == 0xE0):
	                  $d+=3;
	                  break;
	              case (($ord_var_c & 0xF8) == 0xF0):
	                  $d+=4;
	                  break;
	              case (($ord_var_c & 0xFC) == 0xF8):
	                  $d+=5;
	                  break;
	              case (($ord_var_c & 0xFE) == 0xFC):
	                  $d+=6;
	                  break;
	              default:
	                $d++;
	          }
	      }
	      return $d;
	    }



	public static function validaCampo($strValor, $strTamanho=100){
    	 if(!$strValor || $strValor == "")
    	 	throw new FieldsException("O campo esta nulo.");
    	 if(strlen($strValor)>$strTamanho)
    	 	throw new FieldsException("O valor do campo excede seu limite.");
    	 //não verifica o primeiro caracter
    	 if(stripos($strValor, "'")===true ||  stripos($strValor, "%")===true)
				throw new FieldsException("Caracteres inválidos.");
    	 return htmlspecialchars($strValor, ENT_QUOTES);
    }

	public static function validaSenha($strValor, $strTamanho=100){
    	 try{
    	 	self::validaCampo($strValor, $strTamanho);
    	 	if(strlen($strValor)<6)
    	 		throw new FieldsException("O campo de senha deve possuir ao menos 6 caracteres");
    	 }catch(FieldsException $e){
    	 	throw new FieldsException($e->__toString());
    	 }
    	 return htmlspecialchars($strValor, ENT_QUOTES);
    }

	public static function validaLogin($strValor, $strTamanho=100){
    	try{
    	 	self::validaCampo($strValor, $strTamanho);
    	 }catch(FieldsException $e){
    	 	throw new FieldsException($e->__toString());
    	 }
    	 return htmlspecialchars($strValor, ENT_QUOTES);
    }
 }
?>