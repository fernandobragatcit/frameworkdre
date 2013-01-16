<?php

require_once(FWK_EXCEPTION . "PastaException.class.php");

/**
 * Classe responsável pela manutenção de pastas no sistema

 * @author André Coura <andreccls@gmail.com>
 * @since 1.0 - 15/04/2011
 */
class ControlPastas {

    public function __construct() {
        
    }

    public function criarPasta($strPasta, $strCaminhoPasta = "") {
        if (isset($strPasta) && $strPasta != "") {
            if (is_dir($strCaminhoPasta . $strPasta . "/"))
                throw new PastaException("Ja existe uma pasta com o nome sugerido.");
            if (!mkdir($strCaminhoPasta . $strPasta))
                throw new PastaException("Não foi possível criar a pasta.");
            //Criada a pasta, gera-se a estrutura interna dela.
            mkdir($strCaminhoPasta . $strPasta . "/config/");
            copy(FWK_DEFAULT . "index.php", $strCaminhoPasta . $strPasta . "/index.php");
            copy(FWK_DEFAULT . "config.xml", $strCaminhoPasta . $strPasta . "/config/config.xml");
        }else {
            throw new PastaException("Não foi passado o nome para a criação da pasta.");
        }

        return true;
    }

    public function novaPasta($strCaminhoPasta) {
        $result = true;
        if (!is_dir($strCaminhoPasta)) {
            mkdir($strCaminhoPasta, 0777);
        } else {
            $result = false;
        }
        return $result;
    }

    /* exemplo de uso: chamo a função copiar passando url origem e destino respectivamente. 
     */

    public function rrmdir($Dir_destino) {
        if (is_dir($Dir_destino)) {
            $files = scandir($Dir_destino);
            foreach ($files as $file)
                if (is_dir("$Dir_destinho / $Dir_Origem")) {
                    if ($file != "." && $file != "..")
                        self::rrmdir("$Dir_destinho/$file");
                }else {
                    if ($file != "." && $file != "..")
                        self::rrmdir("$Dir_destinho/$file");
                }
            self::rrmdir($dir);
        }else if (file_exists($dir))
            unlink($dir);
    }

    public function copiar($src, $dst) {
        if (file_exists($dst))
            self::rrmdir($dst);
        if (is_dir($src)) {
           if(!is_dir($dst)){
            mkdir($dst,0777);
           }
            $files = scandir($src);
            foreach ($files as $file)
                if ($file != "." && $file != "..")
                    self::copiar("$src/$file", "$dst/$file");
        }
        else if (file_exists($src))
            copy($src, $dst);
    }

    public function renomeiaPasta($strPastaAntiga, $strPastaNova, $strCaminhoPasta = "") {
        if (isset($strPastaAntiga) && $strPastaAntiga != "" && isset($strPastaNova) && $strPastaNova != "") {
            if (!is_dir($strCaminhoPasta . $strPastaAntiga . "/"))
                throw new PastaException("Nao existe uma pasta com o nome especificado.");
            if (!rename($strCaminhoPasta . $strPastaAntiga, $strCaminhoPasta . $strPastaNova))
                throw new PastaException("Não foi possível renomear a pasta.");
//Criada a pasta, gera-se a estrutura interna dela.
        }else {
            throw new PastaException("Não foi passado o nome para renomear a pasta.");
        }
        return true;
    }

    public function deletaPastaeSubpasta($strPasta, $strCaminhoPasta = "") {
        if (isset($strPasta) && $strPasta != "") {
            if (is_dir($strCaminhoPasta . $strPasta . "/")) {
                if ($ponteiro = opendir($strCaminhoPasta . $strPasta . "/")) {
                    while ($nome_itens = readdir($ponteiro)) {
                        if ($nome_itens != "." && $nome_itens != "..") {
                            if (is_dir($strCaminhoPasta . $strPasta . "/" . $nome_itens)) {
                                $pastas[] = $nome_itens;
                            } else {
                                chmod($strCaminhoPasta . $strPasta . "/" . $nome_itens, 0777);
                                unlink($strCaminhoPasta . $strPasta . "/" . $nome_itens);
                            }
                        }
                    }

                    if ($pastas[0] != "") {
                        foreach ($pastas as $pasta) {
                            self::deletaPastaeSubpasta($pasta, $strCaminhoPasta . $strPasta . "/");
                        }
                    }
                }
                closedir($ponteiro);
                rmdir($strCaminhoPasta . $strPasta . "/");
            } else {
                throw new PastaException("A pasta referida não existe.");
            }
        }
    }

    public function deletaPasta($strPasta, $strCaminhoPasta = "") {
        if (isset($strPasta) && $strPasta != "") {
            if (!is_dir($strCaminhoPasta . $strPasta . "/"))
                throw new PastaException("A pasta referida não existe.");
//verifica-se se existem pastas filhas, caso tenha não será permitido deletar
            if (is_dir($strCaminhoPasta . $strPasta)) {
                if ($dh = opendir($strCaminhoPasta . $strPasta)) {
                    while (($file = readdir($dh)) !== false) {
                        if (!in_array($file, self::getArrConfigPastas()) && $file != "index.php") {
                            throw new PastaException("A pasta referida não pode ser deletada por possuir pastas internas.");
                        }
                    }
                    closedir($dh);
                }
            }

            if ($dh = opendir($strCaminhoPasta . $strPasta . "/config/")) {
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir($strCaminhoPasta . $strPasta . "/config/" . $file))
                        if (!unlink($strCaminhoPasta . $strPasta . "/config/" . $file))
                            throw new PastaException("A pasta não pode ser alterada.");
                }
                closedir($dh);
            }
            if (!rmdir($strCaminhoPasta . $strPasta . "/config/"))
                throw new PastaException("A pasta \"" . $strCaminhoPasta . $strPasta . "/config/\" não pode ser alterada.");
            if (!unlink($strCaminhoPasta . $strPasta . "/index.php"))
                throw new PastaException("O arquivo \"" . $strCaminhoPasta . $strPasta . "/index.php\" não pode ser alterada.");
            chmod($strCaminhoPasta . $strPasta . "/", 755);
            if (!rmdir($strCaminhoPasta . $strPasta))
                throw new PastaException("A pasta \"" . $strCaminhoPasta . $strPasta . "\" não pode ser deletada.");
        }
    }

    /**
     * As pastas a serem ignoradas
     */
    public function getArrConfigPastas() {
        $arrFilesSistem = array(".",
            "..",
            ".svn",
            "classes",
            "config",
            "framework",
            "html",
            "modulos",
            "uploads",
            ".settings",
            "arquivos");

        return $arrFilesSistem;
    }

    public function apagarPastaByDir($dir) {

        if (is_dir($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    if (($file == ".") or ($file == "..")) {
                        continue;
                    }
                    if (is_dir($dir . $file)) {
                        self::apagarPastaByDir($dir . $file . "/");
                    } else {
                        unlink($dir . $file);
                    }
                }
            } else {
                print("nao foi possivel abrir o arquivo.");
                return false;
            }

// fecha a pasta aberta
            closedir($handle);

// apaga a pasta, que agora esta vazia
            rmdir($dir);
        } else {
            print("diretorio informado invalido");
            return false;
        }
    }

}