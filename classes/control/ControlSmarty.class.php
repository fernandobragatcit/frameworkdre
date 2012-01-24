<?php
include_once(BIB_SMARTY);

/**
 * Classe singleton smarty
 *
 * @author André Coura
 * @since 18/03/2008
 */
class ControlSmarty{

	static private $_objSmarty;

	/**
	 * Construtor privado singleton
	 *
	 * @author André Coura
	 * @since 1.0 - 21/03/2008
	 */
	private function __construct(){
	}

	/**
	 * método responsável por instanciar o objeto smarty caso ele ainda nao tenha sido instanciado
	 *
	 * @author André Coura
	 * @since 1.0 - 21/03/2008
	 */
    private function singleton(){
		self::$_objSmarty = new Smarty();

		self::$_objSmarty->template_dir = SERVIDOR_FISICO."html/templates/";
		self::$_objSmarty->compile_dir = SERVIDOR_FISICO."html/templates_c/";
		self::$_objSmarty->config_dir = SERVIDOR_FISICO."html/configs/";
		self::$_objSmarty->cache_dir = SERVIDOR_FISICO."html/cache/";
		self::$_objSmarty->register_resource("text", array("text_get_template",
		                                           "text_get_timestamp",
		                                           "text_get_secure",
		                                           "text_get_trusted"));
		self::$_objSmarty->register_function("REGISTRA_TAG","getModuloTags");
		self::$_objSmarty->register_function("VOTACAO","getModuloVotacao");
		self::$_objSmarty->register_function("RESERVA","getModuloReserva");
		self::$_objSmarty->register_function("PAGAMENTO","getModuloPagamento");
		self::$_objSmarty->register_function("ROTEIRO","getModuloRoteiro");
		self::$_objSmarty->register_function("FAVORITOS","getModuloFavoritos");
		self::$_objSmarty->register_function("ESTIVEAQUI","getModuloEstiveAqui");
		self::$_objSmarty->register_function("IMPRIMIR","getModuloImpressao");
		self::$_objSmarty->register_function("QUIZ","getModuloQuiz");
		self::$_objSmarty->register_function("FORUM","getModuloForum");
		
		
		
		
		//funções do framework- @author: André Coura
		self::$_objSmarty->register_function("BANNER","getBanner");
		self::$_objSmarty->register_function("FOTO","getFotosTag");
		self::$_objSmarty->register_function("ARQUIVO","getArquivoTag");
		self::$_objSmarty->register_function("DOCUMENTO","getDocumento");
		self::$_objSmarty->register_function("GALERIA","getGaleria");
		self::$_objSmarty->register_function("FACEBOOK","getFacebook");

    }

    /**
	 * método para retornar o objeto smarty
	 *
	 * @author André Coura
	 * @since 1.0 - 21/03/2008
	 */
	public static function getSmarty(){
		if (is_null((self::$_objSmarty)))
      		self::singleton();
		return self::$_objSmarty;
    }

}
?>