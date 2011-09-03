<?php
require_once(FWK_MODEL."AbsModelDao.class.php");

class FavoritosCipoDAO extends AbsModelDao{


    /** Retornar o conteúdo de Atividade */
	public function getConteudoFavoritoAtividadesPerfil($idfavorito){
    	$strQuery ="SELECT 'ATIVIDADE', fau.data_cadastro, ct.titulo_categoria_atrativo, ia.nome_popular,
					ft .legenda_foto, ft .autor_foto, ft .nome_arquivo, ft .id_usuario_alt, fau.id_fav_atividades_usuario, ia.id_atrativo
					FROM inv_atrativo ia LEFT JOIN cip_atrativo_comp cdc ON ia.id_atrativo = cdc.id_atrativo
					LEFT JOIN fwk_fotos ft  ON cdc.id_foto = ft .id_foto
					JOIN inv_categoria_atrativo ct ON ia.id_categoria_atrativo = ct.id_categoria_atrativo
					JOIN fav_atividades_usuario fau ON ia.id_atrativo = fau.id_atividade
					WHERE ia.tipo_atrativo = 'T' AND fau.id_fav_atividades_usuario = $idfavorito";

		//die($strQuery);
        return ControlDB::getRow($strQuery);
    }

    /** Retornar o conteudo de dica */
    public function getConteudoFavoritoDicasCipoPerfil($idfavorito){
		$strQuery =" select 'DICA', fdu.data_cadastro, ccd.titulo_categoria, cp.titulo_dica, cp.txt_dica, fdu.id_fav_dicas_usuario, cp.id_categoria_dica, cp.id_dica
					 FROM cip_categorias_dicas ccd JOIN cip_dicas cp ON ccd.id_categoria_dica = cp.id_categoria_dica
					 JOIN fav_dicas_usuario fdu ON fdu.id_dica = cp.id_dica
					 WHERE fdu.id_fav_dicas_usuario = '".$idfavorito."'";
        //die($strQuery);
        return ControlDB::getRow($strQuery);
    }

    /** Retornar o conteudo de Evento */
	public function getConteudoFavoritoEventoPerfil($idfavorito){
		//metodo responsavel por selecionar todas as informações para implementar o favoritos de perfil
		$strQuery ="SELECT 'EVENTO', feu.data_cadastro, ica.titulo_categoria_atrativo, ia.nome_popular, ff.legenda_foto, ff.autor_foto, ff.nome_arquivo, cdce.descricao_comercial, feu.id_fav_eventos_usuario, ia.id_atrativo
					FROM inv_atrativo ia JOIN inv_categoria_atrativo ica ON ia.id_categoria_atrativo = ica.id_categoria_atrativo
					JOIN cip_dados_comerciais_eventos cdce ON ia.id_atrativo = cdce.id_atrativo
					LEFT JOIN fwk_fotos ff ON cdce.id_foto = ff.id_foto
					JOIN fav_eventos_usuario feu ON ia.id_atrativo = feu.id_evento
					WHERE feu.id_fav_eventos_usuario = $idfavorito";
		//die($strQuery);
        return ControlDB::getRow($strQuery);

    }

    /** Retornar o conteudo de fotos */
    public function getConteudoFavoritoFotosPerfil($idfavorito){
		//metodo responsavel por selecionar todas as informações para implementar o favoritos de perfil
		$strQuery ="SELECT 'FOTOS', ffu.data_cadastro, 'Fotos',
					f.titulo_foto, f.id_foto, f.legenda_foto, f.autor_foto, f.nome_arquivo, f.id_usuario_ALT, ffu.id_fav_fotos_usuario
					FROM fav_fotos_usuario ffu LEFT JOIN fwk_fotos f  ON f.id_foto = ffu.id_foto
					WHERE  ffu.id_fav_fotos_usuario = $idfavorito";
		//die($strQuery);
        return ControlDB::getRow($strQuery);
    }

    /** Retornar o conteudo de notícias */
    public function getConteudoFavoritoNoticiasPerfil($idfavorito){
    	$strQuery ="SELECT 'NOTICIA', fnu.data_cadastro, cn.titulo_categoria, n.titulo_noticia, n.texto_noticia, fnu.id_fav_noticias_usuario, n.id_noticia
					FROM fwk_categoria_noticia cn JOIN fwk_noticias n ON cn.id_categoria_noticia = n.id_categoria_noticia
					JOIN fav_noticias_usuario fnu ON n.id_noticia = fnu.id_noticia
					WHERE fnu.id_fav_noticias_usuario ='".$idfavorito."'";
		//die($strQuery);
    	return ControlDB::getRow($strQuery);
     }

     /** Retornar o conteudo de Serviço */
     public function getConteudoFavoritoServicosPerfil($idfavorito){
		//metodo responsavel por selecionar todas as informações para implementar o favoritos de perfil
		$strQuery ="SELECT 'SERVICO', fsu.data_cadastro, itse.descricao_serv_equip, ise.nome_fantasia_serv_equip, ff.nome_arquivo , fsu.id_fav_servs_usuario, ise.id_serv_equip, fsu.categoria_servico, ise.descricao_serv_equip
					FROM inv_tipo_serv_equip itse JOIN inv_serv_equip ise ON itse.id_serv_equip = ise.id_tipo_serv_equip
					LEFT JOIN cip_hospedagem_comp chc ON ise.id_serv_equip = chc.id_serv_equip
					LEFT JOIN fwk_fotos ff ON chc.id_foto = ff.id_foto
					JOIN fav_servs_usuario fsu ON ise.id_serv_equip = fsu.id_serv_equip
					WHERE fsu.id_fav_servs_usuario = $idfavorito";

		//die($strQuery);
        return ControlDB::getRow($strQuery);
    }
    /** Retornar o conteudo de dica */
    public function getConteudoFavoritoAtrativoPerfil($idfavorito){
		//metodo responsavel por selecionar todas as informações para implementar o favoritos de perfil
		/*$strQuery ="SELECT 'ATRATIVO', fau.data_cadastro, ct.titulo_categoria_atrativo, ia.nome_popular,
					ft.id_foto, ft .legenda_foto, ft .autor_foto, ft .nome_arquivo, ft .id_usuario_alt, fau.id_fav_atrativos_usuario, ia.id_atrativo
					FROM inv_atrativo IA
					JOIN fwk_fotos ft  ON ft .id_foto = ia.id_foto
					JOIN inv_categoria_atrativo ct ON ia.id_categoria_atrativo = ct.id_categoria_atrativo
					JOIN FAV_atrativoS_usuario fau ON ia.id_atrativo = fau.id_atrativo
					WHERE fau.id_FAV_atrativoS_usuario = $idfavorito";
        */
       $strQuery ="SELECT 'ATRATIVO', fau.data_cadastro, ct.titulo_categoria_atrativo, ia.nome_popular,
				   ft .legenda_foto, ft .autor_foto, ft .nome_arquivo, ft .id_usuario_alt, fau.id_fav_atrativos_usuario, ia.id_atrativo
				   FROM inv_atrativo ia LEFT JOIN cip_atrativo_comp cdc ON ia.id_atrativo = cdc.id_atrativo
				   LEFT JOIN fwk_fotos ft  ON cdc.id_foto = ft .id_foto
				   JOIN inv_categoria_atrativo ct ON ia.id_categoria_atrativo = ct.id_categoria_atrativo
				   JOIN fav_atrativos_usuario fau ON ia.id_atrativo = fau.id_atrativo
				   WHERE ia.tipo_atrativo = 'N' AND fau.id_fav_atrativos_usuario = $idfavorito";

	//die($strQuery);
    return ControlDB::getRow($strQuery);
    }

    /** Retornar todos os itens favoritados */
    public function getTodosFavoritos($id){
    	$strQuery ="(select DISTINCT 'FAV_ATIVIDADES_USUARIO',fau.id_fav_atividades_usuario, fau.data_cadastro,'id_fav_atividades_usuario' from fav_atividades_usuario fau JOIN inv_atrativo ia ON fau.id_atividade = ia.id_atrativo WHERE ia.tipo_atrativo = 'T' AND fau.id_usuario = $id ORDER BY data_cadastro) UNION
					(SELECT DISTINCT 'FAV_ATRATIVOS_USUARIO', fau.id_fav_atrativos_usuario, fau.data_cadastro, 'id_fav_atrativos_usuario' FROM fav_atrativos_usuario fau JOIN inv_atrativo ia ON ia.id_atrativo = fau.id_atrativo WHERE ia.tipo_atrativo = 'N' AND fau.id_usuario = $id ORDER BY data_cadastro) UNION
					(SELECT DISTINCT 'FAV_DICAS_USUARIO', id_fav_dicas_usuario, data_cadastro, 'id_fav_dicas_usuario' FROM fav_dicas_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
					(SELECT DISTINCT 'FAV_EVENTOS_USUARIO', id_fav_eventos_usuario, data_cadastro, 'id_fav_eventos_usuario' FROM fav_eventos_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
					(SELECT DISTINCT 'FAV_FOTOS_USUARIO', id_fav_fotos_usuario, data_cadastro, 'id_fav_fotos_usuario' FROM fav_fotos_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
					(SELECT DISTINCT 'FAV_NOTICIAS_USUARIO', id_fav_noticias_usuario, data_cadastro, 'id_fav_noticias_usuario' FROM fav_noticias_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
					(SELECT DISTINCT 'FAV_SERVS_USUARIO', id_fav_servs_usuario, data_cadastro, 'id_fav_servs_usuario' FROM fav_servs_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
					(SELECT DISTINCT 'FAV_VIDEOS_USUARIO', id_fav_videos_usuario, data_cadastro, 'id_fav_videos_usuario' FROM fav_videos_usuario WHERE id_usuario = $id ORDER BY data_cadastro)
					ORDER BY data_cadastro DESC";
    	/*$strQuery ="(SELECT DISTINCT 'fav_atividades_usuario',fau.id_fav_atividades_usuario, fau.data_cadastro,'id_fav_atividades_usuario' FROM fav_atividades_usuario fau JOIN inv_atrativo ia ON fau.id_atividade = ia.id_atrativo WHERE ia.tipo_atrativo = 'T' AND fau.id_usuario = $id ORDER BY data_cadastro) UNION
				(SELECT DISTINCT 'FAV_atrativoS_usuario', fau.id_FAV_atrativoS_usuario, fau.data_cadastro, 'id_FAV_atrativoS_usuario' FROM FAV_atrativoS_usuario fau JOIN inv_atrativo ia ON ia.id_atrativo = fau.id_atrativo WHERE ia.tipo_atrativo = 'N' AND fau.id_usuario = $id ORDER BY data_cadastro) UNION
				(SELECT DISTINCT 'fav_dicas_usuario', id_fav_dicas_usuario, data_cadastro, 'id_fav_dicas_usuario' FROM fav_dicas_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
				(SELECT DISTINCT 'FAV_eventos_usuario', id_fav_eventos_usuario, data_cadastro, 'id_fav_eventos_usuario' FROM FAV_eventos_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
				(SELECT DISTINCT 'fav_fotos_usuario', id_fav_fotos_usuario, data_cadastro, 'id_fav_fotos_usuario' FROM fav_fotos_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
				(SELECT DISTINCT 'fav_noticias_usuario', id_fav_noticias_usuario, data_cadastro, 'id_fav_noticias_usuario' FROM fav_noticias_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
				(SELECT DISTINCT 'FAV_SERVS_usuario', id_FAV_SERVS_usuario, data_cadastro, 'id_FAV_SERVS_usuario' FROM FAV_SERVS_usuario WHERE id_usuario = $id ORDER BY data_cadastro) UNION
				(SELECT DISTINCT 'FAV_VIDEOS_usuario', id_FAV_VIDEOS_usuario, data_cadastro, 'id_FAV_VIDEOS_usuario' FROM FAV_VIDEOS_usuario WHERE id_usuario = $id ORDER BY data_cadastro)
				ORDER BY data_cadastro DESC";
		*/


    //die($strQuery);
    return ControlDb::getAll($strQuery);
    }

    /** Buscar a quantidade de cada categoria favoritada */
    public function getBuscarQuantidadeItensFavoritados($idUsuario){
    	$strQuery ="(SELECT 'FAV_DICAS_USUARIO','Dicas de viagens' AS CAMPOMENU, COUNT(*) FROM fav_dicas_usuario WHERE id_usuario = $idUsuario LIMIT 1)UNION
					(SELECT 'FAV_FOTOS_USUARIO','Fotos' AS CAMPOMENU, COUNT(*) FROM fav_fotos_usuario WHERE id_usuario = $idUsuario LIMIT 1)UNION
					(SELECT 'FAV_EVENTOS_USUARIO','Eventos' AS CAMPOMENU, COUNT(*) FROM fav_eventos_usuario WHERE id_usuario = $idUsuario LIMIT 1)UNION
					(SELECT 'FAV_NOTICIAS_USUARIO','Noticias' AS CAMPOMENU, COUNT(*) FROM fav_noticias_usuario WHERE id_usuario = $idUsuario LIMIT 1)UNION
					(SELECT 'FAV_SERVS_USUARIO','Hospedagens' AS CAMPOMENU, COUNT('Hospedagem') FROM fav_servs_usuario WHERE id_usuario = $idUsuario AND CATEGORIA_SERVICO = 'Hospedagens' LIMIT 1)UNION
					(SELECT 'FAV_SERVS_USUARIO','Alimentacao' AS CAMPOMENU, COUNT('Alimentacao') FROM fav_servs_usuario WHERE id_usuario = $idUsuario AND CATEGORIA_SERVICO = 'Alimentacao' LIMIT 1)UNION
					(SELECT 'FAV_VIDEOS_USUARIO','Videos' AS CAMPOMENU, COUNT(*) FROM fav_videos_usuario WHERE id_usuario = $idUsuario LIMIT 1)UNION
					(SELECT 'FAV_ATRATIVOS_USUARIO','Atrativos' AS CAMPOMENU, COUNT(*) FROM fav_atrativos_usuario WHERE id_usuario = $idUsuario LIMIT 1)UNION
					(SELECT 'FAV_ATIVIDADES_USUARIO','Atividades' AS CAMPOMENU,COUNT(*) FROM fav_atividades_usuario WHERE id_usuario = $idUsuario LIMIT 1)
					 ORDER BY CAMPOMENU";

					/*(SELECT 'fav_dicas_usuario','Dicas de viagens', COUNT(*) FROM fav_dicas_usuario WHERE id_usuario = $id LIMIT 1)UNION
					(SELECT 'fav_fotos_usuario','Fotos', COUNT(*) FROM fav_fotos_usuario WHERE id_usuario = $id LIMIT 1)UNION
					(SELECT 'FAV_eventos_usuario','Eventos', COUNT(*) FROM FAV_eventos_usuario WHERE id_usuario = $id LIMIT 1)UNION
					(SELECT 'fav_noticias_usuario','Noticias', COUNT(*) FROM fav_noticias_usuario WHERE id_usuario = $id LIMIT 1)UNION
					(SELECT 'FAV_SERVS_usuario','Servicos', COUNT(*) FROM FAV_SERVS_usuario WHERE id_usuario = $id LIMIT 1)UNION
					(SELECT 'FAV_SERVS_usuario','Hotel', COUNT('Hotel') FROM FAV_SERVS_usuario WHERE id_usuario = $id AND CATEGORIA_SERVICO = 'hotel' LIMIT 1)UNION
					(SELECT 'FAV_SERVS_usuario','restaurante', COUNT('restaurante') FROM FAV_SERVS_usuario WHERE id_usuario = $id AND CATEGORIA_SERVICO = 'restaurante' LIMIT 1)UNION
					(SELECT 'FAV_VIDEOS_usuario','Videos', COUNT(*) FROM FAV_VIDEOS_usuario WHERE id_usuario = $id LIMIT 1)UNION
					(SELECT 'FAV_atrativoS_usuario','Atrativos', COUNT(*) FROM FAV_atrativoS_usuario WHERE id_usuario = $id LIMIT 1)UNION
					(SELECT 'fav_atividades_usuario','Atividades',COUNT(*) FROM fav_atividades_usuario WHERE id_usuario = $id LIMIT 1)*/

			/* Obs.: o campo 'dicas, Fotos,Eventos, Noticias e etc... são para criar menu automático...'*/
			//die($strQuery);


	return ControlDb::getAll($strQuery);
    }

	/** Buscar conteudo de todas categorias favoritadas */
    public function getBuscarConteudoTodosItensFavoritados($id, $tabela){
    	$strQuery ="(SELECT DISTINCT $tabela, id_$tabela, data_cadastro, 'id_$tabela' FROM $tabela WHERE id_usuario = $id ORDER BY data_cadastro DESC)";
	//die($strQuery);
	return ControlDb::getAll($strQuery);
    }

    /** Buscar conteudo de cada categoria favoritada */
    public function getBuscarConteudoItensFavoritados($id, $tabela){

    	$strQuery ="(SELECT DISTINCT '$tabela', id_".strtolower($tabela).", data_cadastro, 'id_".strtolower($tabela)."' FROM ".strtolower($tabela)." WHERE id_usuario = $id ORDER BY data_cadastro DESC)";
	//die($strQuery);
	return ControlDb::getAll($strQuery);
    }

    /** Buscar conteudo de cada servico favoritado */
    public function getBuscarConteudoServicoFavoritados($id, $tabela,$testeLogico){
    	$strQuery ="(SELECT DISTINCT '$tabela', id_".strtolower($tabela).", data_cadastro, 'id_".strtolower($tabela)."' FROM ".strtolower($tabela)." WHERE id_usuario = $id AND CATEGORIA_SERVICO = '$testeLogico' ORDER BY data_cadastro DESC)";
    	//$strQuery ="(SELECT DISTINCT '$tabela', id_$tabela, data_cadastro, 'id_$tabela','$testeLogico' FROM $tabela WHERE id_usuario = $id AND CATEGORIA_SERVICO = '$testeLogico' ORDER BY data_cadastro DESC)";
	return ControlDb::getAll($strQuery);
    }







}
?>