<?php
require_once(FWK_EXCEPTION."DBException.class.php");
require_once(BIBLIOTECAS_DRE."adodb/adodb-errorpear.inc.php");
include_once(BIB_ADODB);
/**
*	Classe controladora De banco de dados
*
*	@author André Coura
*	@since 1.0 - 25/08/2007
*/
class ControlDB{

	 //define('ADODB_FETCH_DEFAULT',0);
	 //define('ADODB_FETCH_NUM',1);
	 //define('ADODB_FETCH_ASSOC',2);
	 //define('ADODB_FETCH_BOTH',3);

	static private $_objBanco;

	private function __construct(){ }

    private function singleton(){
    	self::$_objBanco = NewADOConnection(SGDB);
    	if(!@self::$_objBanco->Connect(SERVER,USUARIO,SENHA,BANCO)){
			throw new DBException(ERRO_CONEXAO_DB);
		}
    }

	public function getBanco(){
		if(is_null((self::$_objBanco))){
			try{
				self::singleton();
			}catch(DBException $e){
				die($e->__toString());
			}
		}
		return self::$_objBanco;
    }

    public static function resetObjDb(){
		if(self::$_objBanco!=null)
			self::$_objBanco->Close();
		self::$_objBanco = null;
    }

	/**
	 * Método de exclusão genérica de dados do banco de dados
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 * @param Array contendo o nome da tabela, o campo de refencia e o valor a ser deletado
	 * @return boolean
	 */
    public static function delRowTable($arrDados){
    	$strQuery = "DELETE FROM ".$arrDados["table"]." WHERE ".$arrDados["campo"]." = ?";
    	return self::getBanco()->Execute($strQuery,array($arrDados["valor"]));
    }

	/**
	 * Método de alteracão genérica de status do banco de dados
	 *
	 * @author Matheus Vieira
	 * @since 1.0 - 02/08/2011
	 * @param Array contendo o nome da tabela e o campo de refencia e o valor do campo referenciado
	 * @return boolean
	 */
    public static function alteraStatus($arrDados){
    	$arrDadosId = self::selectRowTable($arrDados);
    	$novoValor = (strtolower($arrDadosId["status"]) == "s" || $arrDadosId["status"] == 1)?"N":"S";
    	$strQuery = "UPDATE ".$arrDados["table"]." SET status = '".$novoValor."' WHERE ".$arrDados["campo"]." = ?";
    	return self::getBanco()->Execute($strQuery,array($arrDados["valor"]));
    }

    /**
	 * Método buscar todos os dados de uma linha no dados do banco de dados genérico
	 *
	 * @author André Coura
	 * @since 1.0 - 10/08/2008
	 * @param Array contendo o nome da tabela, o campo de refencia e o valor a ser deletado
	 * @param int numero do tipo de associação que irá ser retornado do banco
	 * @return Array
	 */
    public static function selectRowTable($arrDados,$fetchMode = 0){
    	$strQuery = "SELECT * FROM ".$arrDados["table"]." WHERE ".$arrDados["campo"]." = ?";
    	self::getBanco()->SetFetchMode(($fetchMode==0?ADODB_FETCH_ASSOC:ADODB_FETCH_NUM));
    	$arrRet = self::getBanco()->GetRow($strQuery,array($arrDados["valor"]));
		if(self::getBanco()->ErrorMsg()){
				die("<br/><br /><h1>".self::getBanco()->ErrorMsg()."</h1>");
			}
    	return $arrRet;
    }

    public static function selectAllTable($arrDados,$fetchMode = 0){
    	$strQuery = "SELECT * FROM ".$arrDados["table"]." WHERE ".$arrDados["campo"]." = ?";
    	self::getBanco()->SetFetchMode(($fetchMode==0?ADODB_FETCH_ASSOC:ADODB_FETCH_NUM));
    	return self::getBanco()->GetAll($strQuery,array($arrDados["valor"]));
    }

    public static function getRow($strQuery,$fetchMode = 1){
    	self::getBanco()->SetFetchMode($fetchMode==0?2:$fetchMode);
		return self::getBanco()->GetRow(utf8_decode($strQuery));
    }

    public static function getAll($strQuery,$fetchMode = 1){
    	self::getBanco()->SetFetchMode($fetchMode==0?2:$fetchMode);
		return self::getBanco()->GetAll(utf8_decode($strQuery));
    }
}
?>