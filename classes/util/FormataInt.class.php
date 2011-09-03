<?php
require_once(FWK_EXCEPTION."FieldsException.class.php");

class FormataInt {

	/**
	 * Método para garantir a integridade de um número inteiro
	 *
	 * @author André Coura
	 * @since 1.0 - 23/11/2008
	 */
	public static function parseInt($int){
		if(is_numeric((int)$int)){
			$nInt = (int)$int;
			if(is_int($nInt))
				return $nInt;
		}
		throw new FieldsException("Número passado não pode ser corvetido para inteiro.");
	}
}
?>