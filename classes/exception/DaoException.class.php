<?php

class DaoException extends Exception {

    public function __contruction($strMsg) {
        parent::__construct($strMsg);
    }

    public function __toString() {
        $objSmarty = self::regTags();
        $restricao = explode(":", $this->message);
        if ($restricao[0] == "Cannot delete or update a parent row") {
            return $objSmarty->fetch(FWK_HTML_EXCEPTION . "estruturaExceptionRestricao.tpl");
        } else {
            return $objSmarty->fetch(FWK_HTML_EXCEPTION . "estruturaException.tpl");
        }
    }

    public function getMensagem() {
        return $this->message;
    }

    private function regTags() {
        $objSmarty = ControlSmarty::getSmarty();
        $objSmarty->assign("MENS_EXCECAO", $this->message);
        $objSmarty->assign("FILE_EXCECAO", $this->file);
        $objSmarty->assign("NUM_EXCECAO", $this->line);
        return $objSmarty;
    }

}

?>