/**
 * Objeto Global
 */
 var $RENOC={};

/**
 * Submodulo para manejo de interfaz grafica
 */
 $RENOC.UI=(function()
 {
 	/**
 	 * Metodo para inicialzar acciones de click en interfaz
 	 * @acces public
 	 */
 	function init()
 	{
 		var checkFecha=document.getElementsByName('lista[fecha]');
 		if(checkFecha!="undefined")
 		{
 			checkFecha[0].onclick=function(){_changeClass($('.fecha label h4'),'stretchRight','offStretchRight')};
 		}
 	}

 	/**
	 * Encargado de asignar/quitar una clase.
	 * @access private
	 * @param jQuery obj es el objeto de la fila que se quiere manipular
	 */
	function _changeClass(obj,activeClass,desactiveClass)
	{
		if(obj.attr('class')==activeClass)
		{
			obj.removeClass(activeClass).addClass(desactiveClass);
		}
		else
		{
			obj.removeClass(desactiveClass).addClass(activeClass);
			_showCheck();
		}
		obj=null;
	}

	/**
	 * Crea un elemento html con todas caracteristicas
	 * @access private
	 * @param string element es el nombre del elemento a crear
	 * @param string id es el id que se le asigna al elemento
	 * @param string name es el nombre del elemento
	 * @param string className son la/las clases que llevara el elemento
	 * @return dom newElement
	 */
	function _createElement(element,id,name,className)
	{
		if (element!=undefined)
		{
			newElement=document.createElement(element);
			if (id!=undefined) newElement.id=id;
			if (name!=undefined) newElement.name=name;
			if (className!=undefined) newElement.className=className;
			return newElement;
		}
		else
		{
			return false;
		}
	}

	/**
	 *
	 */
	function _showCheck()
	{
		start=$(_createElement('input','startDate','startDate')).datepicker().css('display','none');
		ending=$(_createElement('input','endingDate','endingDate')).datepicker().css('display','none');
		$("div.choice_parametros.fecha").append(start,ending);
		start.fadeIn('slow');
		ending.fadeIn('slow');
		start=null;
	}

	/**
	 *
	 */
	function _hideCheck()
	{
		start=$(_createElement('input','startDate','startDate')).datepicker().css('display','none');
		ending=$(_createElement('input','endingDate','endingDate')).datepicker().css('display','none');
		$("div.choice_parametros.fecha").append(start,ending);
		start.fadeIn('slow');
		ending.fadeIn('slow');
		start=ending=null;
	}

	/**
	 * retorna los metodos publicos
	 */
	return{
		init:init
	}
 })();