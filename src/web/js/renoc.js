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
 		$(".fecha").on('click',function()
 		{
 			console.log("hey");
 			//_changeClass($('.fecha label h4'),'stretchRight');
 		});
 	}

 	/**
	 * Encargado de asignar/quitar una clase.
	 * @access private
	 * @param jQuery obj es el objeto de la fila que se quiere manipular
	 */
	function _changeClass(obj,nameClass)
	{
		if(obj.attr('class')==nameClass)
		{
			obj.removeClass(nameClass);
		}
		else
		{
			obj.addClass(nameClass);
		}
	}

	/**
	 * retorna los metodos publicos
	 */
	return{
		init:init
	}
 })();