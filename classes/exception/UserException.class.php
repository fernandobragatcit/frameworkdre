<?php
/**
 * Classe de exceções referente ao usuário
 *
 * @author André Coura <andreccls@gmail.com>
 * @since 1.0 - 27/05/2008
 */
class UserException  extends Exception {

    public function __contruction($strMsg) {
    	parent::__construct($strMsg);
    }

    /**
     * Override
     *
     * @return unknown
     */
	public function __toString() {
		return $this->message;
	}

	public function getMensagem(){
		return $this->message;
	}
}
?>