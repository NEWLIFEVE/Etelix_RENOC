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
		}
		_showCheck();
		obj=null;
	}

	/**
	 *
	 */
	function _createElement(element,id,name)
	{
		newElement=document.createElement(element);
		newElement.id=id;
		newElement.name=name;
		return newElement;
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
	}

	/**
	 * retorna los metodos publicos
	 */
	return{
		init:init
	}
 })();