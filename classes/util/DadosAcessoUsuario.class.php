<?php

require_once (BIB_ISMOBILE);

class DadosAcessoUsuario {

    public static function is_mobile() {
        $ismobi = new IsMobile();
        if ($ismobi->CheckMobile())
            return true;
        else
            return false;
    }

    /**
     * Metodo utilizado para comparacao entre o navegador do usuario e os homologados pelo sistema.
     * @author Matheus Vieira <matheus.vieira@tcit.com.br>
     * @since 12/01/2012
     * @return seta na sessao se o navegador for autorizado;
     */
    public function validaNavegador() {
        if ($_COOKIE["tarjaNavegador"] != "true") {
            //seta na sessão o navegador e a sessão.
            self::getBrowser();
            //FormataString::debuga($_SESSION);
            $htmlAviso = "
	    		<div id=\"tarjaNavegador\">
	    			<strong>Atenção!</strong>
	    			<p>Este portal não está homologado para o seu navegador, portando, erros poderão ocorrer.<br /> 
	    			Para evitar falhas utilize: ";

            for ($i = 0; $i < count($_SESSION["navegadores"]); $i++) {
                $navegadores[] = $_SESSION["navegadores"][$i]["navegador"];
                $versao[] = $_SESSION["navegadores"][$i]["menorVersao"];
                $htmlAviso .= $_SESSION["navegadores"][$i]["navegador"] . " ";
                $htmlAviso .= $_SESSION["navegadores"][$i]["menorVersao"];
                if ($i != count($_SESSION["navegadores"]) - 1)
                    $htmlAviso .= ", ";
                else
                    $htmlAviso .= " ou versões superiores. ";
            }

            $htmlAviso .= "
	    			</p>
	    			<a href=\"javascript:void(0);\" id=\"fechar\" onclick=\"fechaTarjaNavegador();\" title=\"Fechar\">X Fechar</a>
	    		</div>
	    	";

            if (in_array($_SESSION['navegador'], $navegadores)) {
                $aux = false;
                foreach ($navegadores as $key => $navegador) {
                    if ($_SESSION['navegador'] == $navegador) {
                        if ($_SESSION['versao'] < $versao[$key]) {
                            $aux = true;
                        }
                    }
                }
                if ($aux) {
                    return $htmlAviso;
                } else {
                    return false;
                }
            } else {
                return $htmlAviso;
            }
        }
    }

    /**
     * Metodo utilizado para retorno dos dados do navegador do usuario.
     * @author Matheus Vieira <matheus.vieira@tcit.com.br>
     * @since 12/01/2012
     * @return Array com os dados do navegador do usuario;
     */
    public static function getBrowser() {
        $u_agent = $_SERVER ['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        } elseif (preg_match('/Trident/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches ['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches ['version'][0];
            } else {
                $version = $matches ['version'][1];
            }
        } else {
            $version = $matches ['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $novaVersao = self::versaoNovoNavegador($u_agent);
            if ($novaVersao === false) {
                $version = "?";
            } else {
                $version = $novaVersao;
            }
        }


        $_SESSION['navegador'] = $bname;
        $_SESSION['versao'] = $version;

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }

    private function versaoNovoNavegador($u_agent) {
        $pos = strpos($u_agent, "rv");
        if ($pos === false) {
            return false;
        } else {
            $resultVersion = trim(str_replace("rv:", "", substr($u_agent, $pos, 7)));
            return $resultVersion;
        }
    }

}

?>