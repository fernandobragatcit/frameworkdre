<?php
/**
 * Classe para controle de exceções
 * 
 * @author André Coura <andreccls@gmail.com>
 * @since 1.0 - 09/02/2008
 */
class ControlException  extends Exception {

    public function __contruction($strMsg) {
    	parent::__construct($strMsg);
    }
	
	public function __toString() {
		return $this->message;
	}
}
?>