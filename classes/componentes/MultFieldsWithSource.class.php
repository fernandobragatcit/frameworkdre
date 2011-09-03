<?php
require_once (FWK_COMP."AbsCompHtml.class.php");
require_once (FWK_COMP."FactoryCompHtml.class.php");

class MultFieldsWithSource extends AbsCompHtml {

	public function getComponente($arrDados = "") {
		self::regValores($arrDados);

		self::regTags();

		self::setCampos();
	}

	private function regValores($arrDados) {
		$strHtmlCompGeral = "";
		$strCompLocal = "";
		$strHtmlComp = "";
		$objXmlCampos = $this->objXmlCompChilds->camposRept;
		$intSufixo = 0;

		if (count($arrDados) > 0) {
			foreach ($arrDados as $vetDados) {
				$strCompLocal = $this->objXmlCompChilds->fontHtml;
				foreach ($vetDados as $campos => $dados) {
					$this->objFactoryComps = null;
					foreach ($objXmlCampos->campos as $camposComp) {
						if ($campos == $camposComp->attributes()->name) {
							self::getFactoryCompHtml()->setSufixo("_".$intSufixo);
							self::getFactoryCompHtml()->buildComp($camposComp,$dados);
							$strHtmlComp = self::getFactoryCompHtml()->getHtmlComp();
							$strCompLocal = str_replace("{\$".$campos."}", $strHtmlComp, $strCompLocal);
						}
					}
				}
				$intSufixo++;
				$strHtmlCompGeral.=$strCompLocal;
			}
		}
		$strHtmlCompGeral.= "\n\n<input type=\"hidden\" name=\"multFieldsWithSource\"   id=\"multFieldsWithSource\" value=\"".$intSufixo."\" />";

		self::setHtmlComp($strHtmlCompGeral);
	}

	public function getFactoryCompHtml() {
		if ($this->objFactoryComps == null)
			$this->objFactoryComps = new FactoryCompHtml();
		return $this->objFactoryComps;
	}

}
?>