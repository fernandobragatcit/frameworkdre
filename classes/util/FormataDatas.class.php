<?php

class FormataDatas {

    /**
     * Parser de datas em formato brasileiro para formato SQL
     *
     * @author André Coura
     * @since 1.0 - 20/08/2007
     * @param String data em formato brasileiro
     */
    public static function parseDataSql($data) {
        if (!ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $data))
            return null;
        $newDate = explode("/", $data);
        if (count($newDate) != 3)
            return null;
        return $newDate[2] . "-" . $newDate[1] . "-" . $newDate[0];
    }

    /**
     * Parser de datas em formato SQL para formato brasileiro
     *
     * @author André Coura
     * @since 1.0 - 20/08/2007
     */
    public static function parseDataBR($data) {
        $newDate = self::getArrData($data);
        return $newDate[2] . "/" . $newDate[1] . "/" . $newDate[0];
    }

    public static function getArrData($data) {
        if (!ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $data))
            return null;
        $newDate = explode("-", $data);
        if (count($newDate) != 3)
            return null;
        return $newDate;
    }

    /**
     * Parser de datas em formato SQL para formato brasileiro
     *
     * @author André Coura
     * @since 1.0 - 20/08/2007
     */
    public static function parseDataUSA($data) {
        $newDate = self::getArrData($data);
        return $newDate[1] . "/" . $newDate[2] . "/" . $newDate[0];
    }

    /**
     * Parser de datas em formato SQL para formato brasileiro
     *
     * @author André Coura
     * @since 1.0 - 20/08/2007
     */
    public static function parseDataMesAno($data) {
        $newDate = self::getArrData($data);
        return $newDate[1] . " / " . $newDate[0];
    }

    /**
     * Parser de datas em formato SQL para formato brasileiro
     *
     * @author André Coura
     * @since 1.0 - 20/08/2007
     */
    public static function parseDataDiaMes($data) {
        $newDate = self::getArrData($data);
        return $newDate[2] . " / " . $newDate[1];
    }

    public static function parseDataUSAExtenso($data) {
        if (!ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $data))
            return null;
        $newDate = explode("-", $data);
        if (count($newDate) != 3)
            return null;
        $arrMeses = array("", "Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec");
        $dia = $newDate[2];
        if ($dia{0} == "0")
            $dia = $dia{1};
        if (strlen($dia) == 1) {
            switch ($dia) {
                case 1: $dia = $dia . "st";
                    break;
                case 2: $dia = $dia . "nd";
                    break;
                case 3: $dia = $dia . "rd";
                    break;
                default: $dia = $dia . "th";
                    break;
            }
        } else {
            if ($dia > 20) {
                switch ($dia{1}) {
                    case 1: $dia = $dia . "st";
                        break;
                    case 2: $dia = $dia . "nd";
                        break;
                    case 3: $dia = $dia . "rd";
                        break;
                    default: $dia = $dia . "th";
                        break;
                }
            } else {
                $dia = $dia . "th";
            }
        }
        return $arrMeses[$newDate[1]] . " " . $dia . ", " . $newDate[0];
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
    public static function parsePeriodosDatas($dateIni, $dateFim) {
        if (strpos($dateIni, '-') === false) {
            $dateIni = self::parseDataSql($dateIni);
        }
        if (strpos($dateFim, '-') === false) {
            $dateFim = self::parseDataSql($dateFim);
        }

        $arrDatas[0] = $dateIni;
        if ($dateIni < $dateFim) {
            $i = 0;
            while ($dateIni < $dateFim) {
                $dateIni = date('Y-m-d', strtotime("+1 days", strtotime($dateIni)));
                array_push($arrDatas, $dateIni);
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
    public static function parserDatasRecorrentes($strDatas) {
        $arrDatasBR = explode(",", $strDatas);
        $arrDatas = array();
        foreach ($arrDatasBR as $dataBR) {
            $strNewDate = "";
            //parser de lixos entre os caracteres
            for ($i = 0; $i < strlen($dataBR); $i++) {
                if (ord($dataBR{$i}) != 13 && ord($dataBR{$i}) != 10)
                    $strNewDate.= $dataBR{$i};
            }
            //retirada da quebra de linha adicionada pela máscara do javascript
            if ($strNewDate != '')
                array_push($arrDatas, FormataCampos::parseDataSql($strNewDate));
        }
        return $arrDatas;
    }

    public static function getMesExtenso($mes) {
        switch (intval($mes)) {
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
    public static function getDataBrasilExtenso() {
        $diaAtual = date("d");
        $mesAtual = date("m");
        $anoAtual = date("Y");


        $dataExtenso = $diaAtual . " de " . self::getMesExtenso($mesAtual) . " de " . $anoAtual;

        return $dataExtenso;
    }

    public static function diaSemana($data) {
        $ano = substr("$data", 0, 4);
        $mes = substr("$data", 5, -3);
        $dia = substr("$data", 8, 9);

        $diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));

        switch ($diasemana) {
            case"0": $diasemana = 1;
                break; /* "Domingo"; */
            case"1": $diasemana = 2;
                break; /* "Segunda-Feira"; */
            case"2": $diasemana = 3;
                break; /* "Terça-Feira"; */
            case"3": $diasemana = 4;
                break; /* "Quarta-Feira"; */
            case"4": $diasemana = 5;
                break; /* "Quinta-Feira"; */
            case"5": $diasemana = 6;
                break; /* "Sexta-Feira"; */
            case"6": $diasemana = 7;
                break; /* "Sábado"; */
        }
        return $diasemana;
    }

    public static function diaSemanaExtenso($data) {
        $ano = substr("$data", 0, 4);
        $mes = substr("$data", 5, -3);
        $dia = substr("$data", 8, 9);

        $diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));

        switch ($diasemana) {
            case"0": $strDiasemana = "Domingo";
                break; /* "Domingo"; */
            case"1": $strDiasemana = "Segunda-Feira";
                break; /* "Segunda-Feira"; */
            case"2": $strDiasemana = "Terça-Feira";
                break; /* "Terça-Feira"; */
            case"3": $strDiasemana = "Quarta-Feira";
                break; /* "Quarta-Feira"; */
            case"4": $strDiasemana = "Quinta-Feira";
                break; /* "Quinta-Feira"; */
            case"5": $strDiasemana = "Sexta-Feira";
                break; /* "Sexta-Feira"; */
            case"6": $strDiasemana = "Sábado";
                break; /* "Sábado"; */
        }
        return $strDiasemana;
    }

    public static function calculaNumDiasEntreDatas($dataInicial, $dataFinal) {

        if (strpos($dataInicial, '-') === false) {
            $arrDateIni = explode("/", $dataInicial);
            $dataIni = mktime(0, 0, 0, $arrDateIni[1], $arrDateIni[0], $arrDateIni[2]);
        } else {
            $arrDateIni = explode("-", $dataInicial);
            $dataIni = mktime(0, 0, 0, $arrDateIni[1], $arrDateIni[2], $arrDateIni[0]);
        }
        if (strpos($dataFinal, '-') === false) {
            $arrDateFim = explode("/", $dataFinal);
            $dataFim = mktime(0, 0, 0, $arrDateFim[1], $arrDateFim[0], $arrDateFim[2]);
        } else {
            $arrDateFim = explode("-", $dataFinal);
            $dataFim = mktime(0, 0, 0, $arrDateFim[1], $arrDateFim[2], $arrDateFim[0]);
        }

        $dias = ($dataFim - $dataIni) / 86400;
        $dias = ceil($dias);
        return $dias;
    }

    public static function addDiaData($dataInicial, $qtdAdd) {

        if (strpos($dataInicial, '-') === false) {
            $arrDateIni = explode("/", $dataInicial);
            $dataIni = mktime(0, 0, 0, $arrDateIni[1], $arrDateIni[0], $arrDateIni[2]);
        } else {
            $arrDateIni = explode("-", $dataInicial);
            $dataIni = mktime(0, 0, 0, $arrDateIni[1], $arrDateIni[2], $arrDateIni[0]);
        }
        $dias = mktime(0, 0, 0, $arrDateIni[1], $arrDateIni[2] + $qtdAdd, $arrDateIni[0]);

        return strftime("%Y-%m-%d", $dias);
    }

    /**
     * Função para comparação de datas.
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 1.0 10/02/2012 
     * @param string $data1 //primeira data a ser comparada.
     * @param string $comparacao //tipo de comparação. ex.(>=, <=, ==...)
     * @param string $data2 //segunda data a ser comparada.
     */
    public static function comparaDatas($data1, $comparacao, $data2) {
        $result = false;
        //se padrão BR, transforma para padrão SQL
        if (strpos($data1, '/') === false) {
            self::parseDataSql($data1);
        }
        //se padrão BR, transforma para padrão SQL
        if (strpos($data2, '/') === false) {
            self::parseDataSql($data2);
        }
        // trabalhando a primeira data
        $dt1 = strtotime($data1);
        // trabalhando a segunda data
        $dt2 = strtotime($data2);

        if ($comparacao == "==") {
            if ($dt1 == $dt2) {
                $result = true;
            }
        } else if ($comparacao == "<=") {
            if ($dt1 <= $dt2) {
                $result = true;
            }
        } else if ($comparacao == ">=") {
            if ($dt1 >= $dt2) {
                $result = true;
            }
        } else if ($comparacao == "!=") {
            if ($dt1 != $dt2) {
                $result = true;
            }
        } else if ($comparacao == "<") {
            if ($dt1 < $dt2) {
                $result = true;
            }
        } else if ($comparacao == ">") {
            if ($dt1 > $dt2) {
                $result = true;
            }
        } else if ($comparacao == "===") {
            if ($dt1 === $dt2) {
                $result = true;
            }
        } else if ($comparacao == "<>") {
            if ($dt1 <> $dt2) {
                $result = true;
            }
        } else if ($comparacao == "!==") {
            if ($dt1 !== $dt2) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Função para converter um determinado horario(00:00:00 ou 00:00) em numeração(000000 ou 0000).
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 14/05/2013 
     * @param string $hora //parametro com a hora.
     * @return String $hora //Hora Convertida
     */
    public static function converteHorarioEmNumeros($hora) {
        if (!empty($hora)) {
            $hora = str_replace(":", "", $hora);
        } else {
            $hora = "00:00:00";
        }
        return $hora;
    }

    /**
     * Função para converter um determinado horario(00:00) para o padrão TimesTamp(00:00:00).
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 14/05/2013 
     * @param string $hora //parametro com a hora.
     * @return String $hora //Hora Convertida
     */
    public static function converteHorarioEmPadraoTimesTamp($hora) {
        if (!empty($hora)) {
            $hora .=":00";
        } else {
            $hora = "00:00:00";
        }
        return trim($hora);
    }

    /**
     * Função para somar Horas(00:00:00).
     * @author Fernando Braga <fernando.braga@tcit.com.br>
     * @since 3.0 14/05/2013 
     * @param string $horaX //parametro com a hora X para somar com Hora Y.
     * @param string $horaY //parametro com a hora Y que vai sem somada com Hora X.
     * @return String $hora //Hora Total da Soma.
     */
    public static function somandoHoras($horaX, $horaY) {
        if (!empty($horaX) && !empty($horaY)) {

            $times = array(
                $horaX,
                $horaY,
            );
            $seconds = 0;
            foreach ($times as $time) {
                list( $g, $i, $s ) = explode(':', $time);
                $seconds += $g * 3600;
                $seconds += $i * 60;
                $seconds += $s;
            }

            $hours = floor($seconds / 3600);
            $seconds -= $hours * 3600;
            $minutes = floor($seconds / 60);
            $seconds -= $minutes * 60;

            if (FormataString::contaCaracteresString($seconds) < 2) {
                $seconds = "0" . $seconds;
            }
            if (FormataString::contaCaracteresString($minutes) < 2) {
                $minutes = "0" . $minutes;
            }
            if (FormataString::contaCaracteresString($hours) < 2) {
                $hours = "0" . $hours;
            }

            $hora = $hours . ":" . $minutes . ":" . $seconds;
        } else {
            $hora = "00:00:00";
        }
        return $hora;
    }

    //* Converte formato do TIMESTAMP do MySQL para uma data BR
    //2003-12-30 23:30:59 -> 30/12/2003*/
    public static function parse_mysql_timestamp_BR($dt) {
        $yr = substr($dt, 0, 4);
        $mo = substr($dt, 5, 2);
        $da = substr($dt, 8, 2);
        return trim($da . "/" . $mo . "/" . $yr);
    }

    public static function parse_minutos_horas($mins) {
        // Se os minutos estiverem negativos
        if ($mins < 0)
            $min = abs($mins);
        else
            $min = $mins;

        // Arredonda a hora
        $h = floor($min / 60);
        $m = ($min - ($h * 60)) / 100;
        $horas = $h + $m;

        // Matemática da quinta série
        // Detalhe: Aqui também pode se usar o abs()
        if ($mins < 0)
            $horas *= -1;

        // Separa a hora dos minutos
        $sep = explode('.', $horas);
        $h = $sep[0];
        if (empty($sep[1]))
            $sep[1] = 00;

        $m = $sep[1];

        // Aqui um pequeno artifício pra colocar um zero no final
        if (strlen($m) < 2)
            $m = $m . 0;

        return sprintf('%02d:%02d', $h, $m);
    }

    /**
     * Esta função retorna uma data escrita da seguinte maneira:
     * Exemplo: Terça-feira, 17 de Abril de 2007
     * @author Leandro Vieira Pinho [http://leandro.w3invent.com.br]
     * @param string $strDate data a ser analizada; por exemplo: 2007-04-17 15:10:59
     * @return string
     */
    function formata_data_extenso($strDate) {
        // Array com os dia da semana em português;
        $arrDaysOfWeek = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
        // Array com os meses do ano em português;
        $arrMonthsOfYear = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        // Descobre o dia da semana
        $intDayOfWeek = date('w', strtotime($strDate));
        // Descobre o dia do mês
        $intDayOfMonth = date('d', strtotime($strDate));
        // Descobre o mês
        $intMonthOfYear = date('n', strtotime($strDate));
        // Descobre o ano
        $intYear = date('Y', strtotime($strDate));
        // Formato a ser retornado
        return $arrDaysOfWeek[$intDayOfWeek] . ', ' . $intDayOfMonth . ' de ' . $arrMonthsOfYear[$intMonthOfYear] . ' de ' . $intYear;
    }

}

?>