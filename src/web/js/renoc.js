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
 		var checkFecha=document.getElementsByName('lista[Fecha]');
 		if(checkFecha!="undefined")
 		{
 			optionsDate={
 				elemento:'input',
 				idInput:'startDate',
 				idCheck:'checkDate',
 				name:'startDate',
 				nameClassPicker:'start date',
 				nameClassCheck:'middle date',
 				spot:'div.choice_parametros.fecha'
 			};
 			checkFecha[0].onclick=function(){_changeClass($('.fecha label h4'),'stretchRight','offStretchRight',optionsDate)};
 		}
 		var checkTime=document.getElementsByName('lista[Hora]');
 		if(checkTime!="undefined")
 		{
 			optionsTime={
 				elemento:'input',
 				idInput:'startTime',
 				idCheck:'checkTime',
 				name:'startTime',
 				nameClassPicker:'start time',
 				nameClassCheck:'middle time',
 				spot:'div.choice_parametros.hora'
 			};
 			checkTime[0].onclick=function(){_changeClass($('.hora label h4'),'stretchRight','offStretchRight',optionsTime)};
 		}
 		var checkMonth=document.getElementsByName('lista[Mes]');
 		if(checkMonth!="undefined")
 		{
 			optionsMonth={
 				elemento:'input',
 				idInput:'startMonth',
 				idCheck:'checkMonth',
 				name:'startMonth',
 				nameClassPicker:'start month',
 				nameClassCheck:'middle month',
 				spot:'div.choice_parametros.mes'
 			};
 			checkMonth[0].onclick=function(){_changeClass($('.mes label h4'),'stretchRight','offStretchRight',optionsMonth)};
 		}
 	}

 	/**
	 * Encargado de asignar/quitar una clase.
	 * @access private
	 * @param jQuery obj es el objeto de la fila que se quiere manipular
	 */
	function _changeClass(obj,activeClass,desactiveClass,options)
	{
		if(obj.attr('class')==activeClass)
		{
			obj.removeClass(activeClass).addClass(desactiveClass);
			_hideElement('#'+options.idInput+', #'+options.idCheck);
		}
		else
		{
			obj.removeClass(desactiveClass).addClass(activeClass);
			_showElement($(_createElement(options.elemento,options.idInput,options.name,options.nameClassPicker)).datepicker(),options.spot);
			_showElement(_createElement(options.elemento,options.idCheck,options.name,options.nameClassCheck,'checkbox'),options.spot);
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
	 * @param string type tipo de elemento
	 * @return dom newElement
	 */
	function _createElement(element,id,name,className,type)
	{
		if (element!=undefined)
		{
			newElement=document.createElement(element);
			if (id!=undefined) newElement.id=id;
			if (name!=undefined) newElement.name=name;
			if (className!=undefined) newElement.className=className;
			if (type!=undefined) newElement.type=type;
			return newElement;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Recibe un objeto html y una ubicacion jQuery este mostrara el elemento
	 * @access private
	 * @param dom object es el elemento html a agregar y mostrar
	 * @param string spot es la ubicacion tipo jQuery donde agregar el elemento
	 */
	function _showElement(object,spot)
	{
		$element=$(object).css('display','none');
		$(spot).append($element);
		$element.fadeIn('slow');
		$element=null;
	}

	/**
	 * Recibe un strimng de ubicacion tipo jQuery y esta oculta y luego elimina el elemento
	 * @access private
	 * @param string spot es la ubicacion tipo jQuery
	 */
	function _hideElement(spot)
	{
		$(spot).fadeOut('slow');
		$(spot).remove();
	}

	/**
	 * retorna los metodos publicos
	 */
	return{
		init:init
	}
 })();