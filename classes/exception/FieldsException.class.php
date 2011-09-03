<?php

/**
 * Classe de exceção para validação de campos de formulários
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 05/07/2008
 */

class FieldsException extends Exception {

	public function __construct($message, $code = 0) {
		parent :: __construct($message, $code);
	}

	public function __toString() {
		return $this->message;
	}

	public function getMensagem(){
		return $this->message;
	}
}
?>