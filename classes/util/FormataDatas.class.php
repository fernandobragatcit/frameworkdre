<?php

class FormataDatas {

	/**
	 * Parser de datas em formato brasileiro para formato SQL
	 *
	 * @author André Coura
	 * @since 1.0 - 20/08/2007
	 * @param String data em formato brasileiro
	 */
     public static function parseDataSql($data){
    	if(!ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $data))
    		return null;
   		$newDate = explode("/",$data);
    	if(count($newDate)!=3)
    		return null;
    	return $newDate[2]."-".$newDate[1]."-".$newDate[0];
     }

    /**
	 * Parser de datas em formato SQL para formato brasileiro
	 *
	 * @author André Coura
	 * @since 1.0 - 20/08/2007
	 */
	public static function parseDataBR($data){
    	$newDate = self::getArrData($data);
    	return $newDate[2]."/".$newDate[1]."/".$newDate[0];
    }

	public static function getArrData($data){
    	if(!ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $data))
    		return null;
    	$newDate = explode("-",$data);
    	if(count($newDate)!=3)
    		return null;
    	return $newDate;
    }

    /**
	 * Parser de datas em formato SQL para formato brasileiro
	 *
	 * @author André Coura
	 * @since 1.0 - 20/08/2007
	 */
	public static function parseDataUSA($data){
    	$newDate = self::getArrData($data);
    	return $newDate[1]."/".$newDate[2]."/".$newDate[0];
    }
    /**
	 * Parser de datas em formato SQL para formato brasileiro
	 *
	 * @author André Coura
	 * @since 1.0 - 20/08/2007
	 */
	public static function parseDataMesAno($data){
    	$newDate = self::getArrData($data);
    	return $newDate[1]." / ".$newDate[0];
    }
    /**
	 * Parser de datas em formato SQL para formato brasileiro
	 *
	 * @author André Coura
	 * @since 1.0 - 20/08/2007
	 */
	public static function parseDataDiaMes($data){
    	$newDate = self::getArrData($data);
    	return $newDate[2]." / ".$newDate[1];
    }

    public static function parseDataUSAExtenso($data){
		if(!ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $data))
    		return null;
    	$newDate = explode("-",$data);
    	if(count($newDate)!=3)
    		return null;
		$arrMeses = array("","Jan","Feb","Mar","Apr","May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec");
		$dia = $newDate[2];
		if($dia{0} == "0")
			$dia = $dia{1};
		if(strlen($dia)==1){
			switch($dia){
				case 1: 	$dia = $dia."st"; break;
				case 2: 	$dia = $dia."nd"; break;
				case 3: 	$dia = $dia."rd"; break;
				default: 	$dia = $dia."th"; break;
			}
   		}else{
			if($dia > 20){
				switch($dia{1}){
				case 1: 	$dia = $dia."st"; break;
				case 2: 	$dia = $dia."nd"; break;
				case 3: 	$dia = $dia."rd"; break;
				default: 	$dia = $dia."th"; break;
			}
			}else{
				$dia = $dia."th";
			}
		}
		return $arrMeses[$newDate[1]]." ".$dia.", ".$newDate[0];
    }



    /**
	  * Função para preencher em um array as datas de um determinado intervalo entre 2 datas
	  *
	  * @author André Coura
	  * @since 30/07/2007
	  * @param date $dateIni data de inicio do intervalo
	  * @param date $dateFim data de termino do intervalo
	  * @return array $arrDatas vetor de datas ja tratadas em formato SQL
	  */
	 public static function parsePeriodosDatas($dateIni,$dateFim){
		if(strpos($dateIni, '-') === false){
	 		$arrDateIni = explode("/",$dateIni);
			$horaInicial = mktime (0,0,0,$arrDateIni[1],$arrDateIni[0],$arrDateIni[2]);
		}else{
	 		$arrDateIni = explode("-",$dateIni);
			$horaInicial = mktime (0,0,0,$arrDateIni[1],$arrDateIni[2],$arrDateIni[0]);
		}
		if(strpos($dateFim, '-') === false){
	 		$arrDateFim = explode("/",$dateFim);
			$horaFinal = mktime (0,0,0,$arrDateFim[1],$arrDateFim[0],$arrDateFim[2]);
		}else{
	 		$arrDateFim = explode("-",$dateFim);
			$horaFinal = mktime (0,0,0,$arrDateFim[1],$arrDateFim[2],$arrDateFim[0]);
		}
		$arrDatas = array();
		if($horaInicial<$horaFinal){
			while($horaInicial<=$horaFinal){
				array_push($arrDatas,date("Y-m-d",$horaInicial));
				$horaInicial+=86400;//numeros de segundo em um dia
			}
		}
		return $arrDatas;
	 }
	 /**
	  * Parser para transformar as data em formato texto, passados pelo formulário em um array de datas recorrentes
	  *
	  * @author André Coura
	  * @since 1.0 - Jul 30, 2007
	  * @param String texto contendo as datas separadas por vírgulas
	  * @return array $arrDatas vetor de datas em formato SQL
	  */
	  public static function parserDatasRecorrentes($strDatas){
	  	$arrDatasBR = explode(",",$strDatas);
	  	$arrDatas = array();
	  	foreach($arrDatasBR as $dataBR){
	  		$strNewDate="";
	  		//parser de lixos entre os caracteres
	  		for($i=0;$i<strlen($dataBR);$i++){
	  			if(ord($dataBR{$i})!=13&&ord($dataBR{$i})!=10)
	  				$strNewDate.= $dataBR{$i};
	  		}
	  		//retirada da quebra de linha adicionada pela máscara do javascript
  			if($strNewDate!='')
  				array_push($arrDatas,FormataCampos::parseDataSql($strNewDate));
	  	}
	  return $arrDatas;
	  }

	  public static function getMesExtenso($mes){
	  	switch(intval($mes)){
			case 1:
				return "janeiro";
			case 2:
				return "fevereiro";
			case 4:
				return "março";
			case 4:
				return "abril";
			case 5:
				return "maio";
			case 6:
				return "junho";
			case 7:
				return "julho";
			case 8:
				return "agosto";
			case 9:
				return "setembro";
			case 10:
				return "outrubro";
			case 11:
				return "novembro";
			case 12:
				return "dezembro";
			default;
				return "mes invalido";
		}
	  }

	/**
	 * Data no formato brasileiro por extenso exemplo : 08 de novembro de 2009
	 *
	 * @author André Coura
	 * @since 1.0 - 14/11/2009
	 */
	  public static function getDataBrasilExtenso(){
		$diaAtual = date("d");
		$mesAtual = date("m");
		$anoAtual = date("Y");


		$dataExtenso = $diaAtual." de ".self::getMesExtenso($mesAtual)." de ". $anoAtual;

		return $dataExtenso;
	  }

	  public static function diaSemana($data) {
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, -3);
		$dia =  substr("$data", 8, 9);

		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

		switch($diasemana) {
			case"0": $diasemana = 1;   break; /*"Domingo"; */
			case"1": $diasemana = 2;   break; /*"Segunda-Feira";*/
			case"2": $diasemana = 3;   break; /*"Terça-Feira"; */
			case"3": $diasemana = 4;   break; /*"Quarta-Feira";*/
			case"4": $diasemana = 5;   break; /*"Quinta-Feira";*/
			case"5": $diasemana = 6;   break; /*"Sexta-Feira"; */
			case"6": $diasemana = 7;   break; /*"Sábado"; */
		}
		return $diasemana;
	}
	 public static function diaSemanaExtenso($data) {
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, -3);
		$dia =  substr("$data", 8, 9);

		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

		switch($diasemana) {
			case"0": $strDiasemana = "Domingo";   break; /*"Domingo"; */
			case"1": $strDiasemana = "Segunda-Feira";   break; /*"Segunda-Feira";*/
			case"2": $strDiasemana = "Terça-Feira";   break; /*"Terça-Feira"; */
			case"3": $strDiasemana = "Quarta-Feira";   break; /*"Quarta-Feira";*/
			case"4": $strDiasemana = "Quinta-Feira";   break; /*"Quinta-Feira";*/
			case"5": $strDiasemana = "Sexta-Feira";   break; /*"Sexta-Feira"; */
			case"6": $strDiasemana = "Sábado";   break; /*"Sábado"; */
		}
		return $strDiasemana;
	}

	public static function calculaNumDiasEntreDatas ($dataInicial, $dataFinal){

		if(strpos($dataInicial, '-') === false){
	 		$arrDateIni = explode("/",$dataInicial);
			$dataIni = mktime (0,0,0,$arrDateIni[1],$arrDateIni[0],$arrDateIni[2]);
		}else{
	 		$arrDateIni = explode("-",$dataInicial);
			$dataIni = mktime (0,0,0,$arrDateIni[1],$arrDateIni[2],$arrDateIni[0]);
		}
		if(strpos($dataFinal, '-') === false){
	 		$arrDateFim = explode("/",$dataFinal);
			$dataFim = mktime (0,0,0,$arrDateFim[1],$arrDateFim[0],$arrDateFim[2]);
		}else{
	 		$arrDateFim = explode("-",$dataFinal);
			$dataFim = mktime (0,0,0,$arrDateFim[1],$arrDateFim[2],$arrDateFim[0]);
		}
		
		$dias = ($dataFim - $dataIni)/86400;
		$dias = ceil($dias);
		return $dias;
	}

	public static function addDiaData ($dataInicial, $qtdAdd){

		if(strpos($dataInicial, '-') === false){
	 		$arrDateIni = explode("/",$dataInicial);
			$dataIni = mktime (0,0,0,$arrDateIni[1],$arrDateIni[0],$arrDateIni[2]);
		}else{
	 		$arrDateIni = explode("-",$dataInicial);
			$dataIni = mktime (0,0,0,$arrDateIni[1],$arrDateIni[2],$arrDateIni[0]);
		}
		$dias = mktime(0,0,0,$arrDateIni[1],$arrDateIni[2]+$qtdAdd,$arrDateIni[0]);
		
		return strftime("%Y-%m-%d", $dias);
	}

}
?>