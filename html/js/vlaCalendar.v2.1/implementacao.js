//jQuery.noConflict();
/**
 * Calendário preto, estilo vista
 *
 * @author André Coura
 * @since 1.0 - 05/08/2008
 */
function impCalendario(id, estilo,filePath){
	new vlaDatePicker(id,
		{ style: estilo,
		  offset: { x: 3, y: 1 },
		  filePath : filePath
		});
}

function preencheCalendario(id, dia, mes, ano, estilo,filePath){
	new vlaDatePicker(id,
		{ style: estilo,
		  offset: { x: 3, y: 1 },
		  filePath : filePath,
		  prefillDate: { day: dia, month: mes, year: ano }
		});
}