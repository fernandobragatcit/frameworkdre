<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class ListBox extends AbsCompHtml {

	public function getComponente($value = "") {
		self::regTags();
		self::registraOptions($value);
		self::addJsListBox();
		self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS . "listBoxDre.tpl"));
		self::setCampos();
	}

	private function addJsListBox() {
		$objCtrlCss = ControlJS::getJS();
		$objCtrlCss->addJs(FWK_JS."listBox.js");
	}

	private function registraOptions($id) {
		if ($id != "") {
			$arrSource = self::getOptionsSourceAlter(self::removeFields(self::getArrDadosDBSource(), self::getArrDadosOptionDest($id)));
			$this->objSmarty->assign("CAMPOS_ALTERACAO", self::getHiddenInputs($id));
			$this->objSmarty->assign("DESTINO_LISTBOX", self::getCamposAlter($id));
			$this->objSmarty->assign("SOURCE_LISTBOX", $arrSource);
			return;
		}
		$this->objSmarty->assign("SOURCE_LISTBOX", self::getOptionsSource());
	}

	private function getHiddenInputs($id){
		$this->objSmarty->assign("NOME_COMP",(string)$this->objXmlComp->name);
		$this->objSmarty->assign("ARR_HINPUT", self::getArrDadosOptionDest($id));
		return $this->objSmarty->fetch(FWK_HTML_FORMS."hiddenIputsDre.tpl");

	}

	private function removeFields($arrSource, $arrDest) {
		if(count($arrDest)>0){
			$arrRet = array ();
			foreach ($arrSource as $source) {
				$flag = true;
				foreach ($arrDest as $dest){
					if (in_array($source[0], $dest))
						$flag = false;
				}
				if ($flag)
					$arrRet[] = $source;
			}
			return $arrRet;
		}
		return $arrSource;
	}

	private function getOptionsSource() {
		if ($this->objXmlComp->querySource || $this->objXmlComp->querySource != "") {
			$this->objSmarty->assign("ARR_OPTIONS", self::getArrDadosDBSource());
			return $this->objSmarty->fetch(FWK_HTML_FORMS . "optionsSelectDre.tpl");
		}
	}

	private function getOptionsSourceAlter($arrDatas) {
		if ($this->objXmlComp->querySource || $this->objXmlComp->querySource != "") {
			$this->objSmarty->assign("TIPO_OPTIONS", "listBox");
			$this->objSmarty->assign("ARR_OPTIONS", $arrDatas);
			return $this->objSmarty->fetch(FWK_HTML_FORMS . "optionsSelectDre.tpl");
		}
	}

	private function getArrDadosDBSource() {
		self::getBanco();
		$this->objBanco->SetFetchMode(ADODB_FETCH_NUM);
		$querySor = (string) $this->objXmlComp->querySource;
		if(isset($this->objXmlComp->qsWHERE) && (string)$this->objXmlComp->qsWHERE !="")
			$querySor.=" WHERE ".(string) $this->objXmlComp->qsWHERE;
		if(strpos($querySor, "#idReferencia#") > 0)
			$querySor =str_replace("#idReferencia#", parent::getIdReferencia(), $querySor);
		if(isset($this->objXmlComp->orderby) && (string)$this->objXmlComp->orderby !="")
			$querySor.=" ORDER BY ".$this->objXmlComp->orderby;
		$arrDados = array();
		$arrReturn = $this->objBanco->GetAll($querySor);

		if(is_array($arrReturn) && count($arrReturn) > 0){
			foreach ($arrReturn as $arrResult) {
				$arrResult[1] = utf8_encode($arrResult[1]);
				//$arrResult[1] = $arrResult[1];
				$arrDados[] = $arrResult;
			}
		}

		//die($querySor);

		return $arrDados;
	}

	private function getCamposAlter($id) {
		$this->objSmarty->assign("TIPO_OPTIONS", "listBox");
		$this->objSmarty->assign("ARR_OPTIONS", self::getArrDadosOptionDest($id));
		return $this->objSmarty->fetch(FWK_HTML_FORMS . "optionsSelectDre.tpl");
	}

	private function getArrDadosOptionDest($id) {
		if ($id != "") {
			if ($this->objXmlComp->queryAlt || $this->objXmlComp->queryAlt != "") {
				self::getBanco();
				$this->objBanco->SetFetchMode(ADODB_FETCH_NUM);
				$query = (string) $this->objXmlComp->queryAlt . " = '" . $id . "'";


				$arrQueryComp = $this->objBanco->GetAll($query);
				if ($arrQueryComp) {
					$arrCompSelect = array ();
					$whereField = (string) $this->objXmlComp->values;
					foreach ($arrQueryComp as $idDest) {
						$queryDest = (string) $this->objXmlComp->querySource;
						$queryDest.= " WHERE " . $whereField . " = '" . $idDest[0] . "'";
						if(isset($this->objXmlComp->qsWHERE) && (string)$this->objXmlComp->qsWHERE !="")
							$queryDest.=" AND ".(string) $this->objXmlComp->qsWHERE;
							$arrResult = $this->objBanco->GetRow($queryDest);
						$arrResult[1] = utf8_encode($arrResult[1]);
						$arrCompSelect[] = $arrResult;
					}
					return $arrCompSelect;
				}
			}
		}
	}
}
?>