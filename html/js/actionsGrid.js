jQuery.noConflict();
SortableManager = function () {
    this.thead = null;
    this.tbody = null;
    this.columns = [];
    this.rows = [];
    this.sortState = {};
    this.sortkey = 0;
};

mouseOverFunc = function () {
    addElementClass(this, "over");
};

mouseOutFunc = function () {
    removeElementClass(this, "over");
};

ignoreEvent = function (ev) {
    if (ev && ev.preventDefault) {
        ev.preventDefault();
        ev.stopPropagation();
    } else if (typeof(event) != 'undefined') {
        event.cancelBubble = false;
        event.returnValue = false;
    }
};


update(SortableManager.prototype, {

    "initWithTable": function (table) {
        // Ensure that it's a DOM element
        table = getElement(table);
        // Find the thead
        this.thead = table.getElementsByTagName('thead')[0];
        // get the mochi:format key and contents for each column header
        var cols = this.thead.getElementsByTagName('th');
        for (var i = 0; i < cols.length; i++) {
            var node = cols[i];
            var attr = null;
            try {
                attr = node.getAttribute("mochi:format");
            } catch (err) {
                // pass
            }
            var o = node.childNodes;
            this.columns.push({
                "format": attr,
                "element": node,
                "proto": node.cloneNode(true)
            });
        }
        // scrape the tbody for data
        this.tbody = table.getElementsByTagName('tbody')[0];
        // every row
        var rows = this.tbody.getElementsByTagName('tr');
        for (var i = 0; i < rows.length; i++) {
            // every cell
            var row = rows[i];
            var cols = row.getElementsByTagName('td');
            var rowData = [];
            for (var j = 0; j < cols.length; j++) {
                // scrape the text and build the appropriate object out of it
                var cell = cols[j];
                var obj = scrapeText(cell);
                switch (this.columns[j].format) {
                    case 'isodate':
                        obj = isoDate(obj);
                        break;
                    case 'str':
                        break;
                    case 'istr':
                        obj = obj.toLowerCase();
                        break;
                    // cases for numbers, etc. could be here
                    default:
                        break;
                }
                rowData.push(obj);
            }
            // stow away a reference to the TR and save it
            rowData.row = row.cloneNode(true);
            this.rows.push(rowData);
        }
        // do initial sort on first column
        this.drawSortedRows(this.sortkey, true, false);
		zebrarTrs();
    },

    "onSortClick": function (name) {
        /***

            Return a sort function for click events

        ***/
        return method(this, function () {
            log('onSortClick', name);
            var order = this.sortState[name];
            if (order == null) {
                order = true;
            } else if (name == this.sortkey) {
                order = !order;
            }
            this.drawSortedRows(name, order, true);
        });
        zebrarTrs();
    },

    "drawSortedRows": function (key, forward, clicked) {
        /***

            Draw the new sorted table body, and modify the column headers
            if appropriate

        ***/
        log('drawSortedRows', key, forward);
        this.sortkey = key;
        // sort based on the state given (forward or reverse)
        var cmp = (forward ? keyComparator : reverseKeyComparator);
        this.rows.sort(cmp(key));
        // save it so we can flip next time
        this.sortState[key] = forward;
        // get every "row" element from this.rows and make a new tbody
        var newBody = TBODY(null, map(itemgetter("row"), this.rows));
        // swap in the new tbody
        this.tbody = swapDOM(this.tbody, newBody);
        for (var i = 0; i < this.columns.length; i++) {
            var col = this.columns[i];
            var node = col.proto.cloneNode(true);
            // remove the existing events to minimize IE leaks
            col.element.onclick = null;
            col.element.onmousedown = null;
            col.element.onmouseover = null;
            col.element.onmouseout = null;
            // set new events for the new node
            node.onclick = this.onSortClick(i);
            node.onmousedown = ignoreEvent;
            node.onmouseover = mouseOverFunc;
            node.onmouseout = mouseOutFunc;
            // if this is the sorted column
            if (key == i) {
                // \u2193 is down arrow, \u2191 is up arrow
                // forward sorts mean the rows get bigger going down
                var arrow = (forward ? "\u2193" : "\u2191");
                // add the character to the column header
                node.appendChild(SPAN(null, arrow));
                if (clicked) {
                    node.onmouseover();
                }
            }
            // swap in the new th
            col.element = swapDOM(col.element, node);
        }
        zebrarTrs();
    }
});

/*
sortableManager = new SortableManager();

addLoadEvent(function () {
   try{
   	sortableManager.initWithTable('tableGrid');
   }catch(e){
   	//n�o foi constru�da a tabela, suprime-se o erro js aqui....
   }


});
*/


function zebrarTrs(){
	jQuery("tr").removeClass("gridLin1");
	jQuery("tr").addClass("gridLin2");
	jQuery("tr:odd").removeClass("gridLin2");
	jQuery("tr:odd").addClass("gridLin1");
}

function vaiPara(strPag){
	location.replace(strPag);
}

function confirmIr(strPag,strMens){
	if(confirm(strMens)){
		location.replace(strPag);
	}
}

function imprimir(strParams){
	var strClass  = RET_SERVIDOR + "ConsAjax.class.php?ajx="+strParams;
	window.open(strClass, "Impressao", "scrollbars=yes", "menubar=no", "fullscreen = yes");
	return false;
}

function abreFechaDiv(idDiv){
	if(jQuery("#"+idDiv).hasClass("opened")){
		jQuery("#abrirFiltro").html("Exibir filtros");
		jQuery("#"+idDiv).slideUp("slow");
		jQuery("#"+idDiv).removeClass("opened");
	}else{
		jQuery("#"+idDiv).slideDown();
		jQuery("#"+idDiv).addClass("opened");
		jQuery("#abrirFiltro").html("Ocultar filtros");
	}
}