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
 				idInputStart:'startDate',
 				idInputEnd:'endingDate',
 				idCheck:'checkDate',
 				nameClassPicker:'start date',
 				nameClassCheck:'middle date',
 				spot:'div.choice_parametros.fecha'
 			};
 			checkFecha[0].onclick=function()
 			{
 				_changeClass($('.fecha label h4'),'stretchRight','offStretchRight',optionsDate);
 				document.getElementById(optionsDate.idCheck).onclick=function()
 				{
 					if (this.checked) _showElement($(_createElement(optionsDate.elemento,optionsDate.idInputEnd,optionsDate.idInputEnd,'end date',undefined,'Fin')).datepicker({dateFormat: 'yy-mm-dd'}),optionsDate.spot);
 					else _hideElement('#'+optionsDate.idInputEnd);
 				}
 			};
 		}
 		var checkTime=document.getElementsByName('lista[Hora]');
 		if(checkTime!="undefined")
 		{
 			optionsTime={
 				elemento:'input',
 				idInputStart:'startTime',
 				idInputEnd:'endingTime',
 				idCheck:'checkTime',
 				nameClassPicker:'start time',
 				nameClassCheck:'middle time',
 				spot:'div.choice_parametros.hora'
 			};
 			checkTime[0].onclick=function()
 			{
 				_changeClass($('.hora label h4'),'stretchRight','offStretchRight',optionsTime);
 				document.getElementById(optionsTime.idCheck).onclick=function()
 				{
	 				if (this.checked) _showElement($(_createElement(optionsTime.elemento,optionsTime.idInputEnd,optionsTime.idInputEnd,'end time',undefined,'Fin')).datepicker({dateFormat: 'yy-mm-dd'}),optionsTime.spot);
	 				else _hideElement('#'+optionsTime.idInputEnd);
 				};
 			};
 			
 		}
 		var checkMonth=document.getElementsByName('lista[Mes]');
 		if(checkMonth!="undefined")
 		{
 			optionsMonth={
 				elemento:'input',
 				idInputStart:'startMonth',
 				idInputEnd:'endingMonth',
 				idCheck:'checkMonth',
 				nameClassPicker:'start month',
 				nameClassCheck:'middle month',
 				spot:'div.choice_parametros.mes'
 			};
 			checkMonth[0].onclick=function()
 			{
 				_changeClass($('.mes label h4'),'stretchRight','offStretchRight',optionsMonth);
 				document.getElementById(optionsMonth.idCheck).onclick=function()
	 			{
	 				if (this.checked) _showElement($(_createElement(optionsMonth.elemento,optionsMonth.idInputEnd,optionsMonth.idInputEnd,'end month',undefined,'Fin')).datepicker({dateFormat: 'yy-mm-dd'}),optionsMonth.spot);
	 				else _hideElement('#'+optionsMonth.idInputEnd);
	 			};
 			};
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
			_hideElement('#'+options.idInputStart+', #'+options.idCheck+',#'+options.idInputEnd);
		}
		else
		{
			obj.removeClass(desactiveClass).addClass(activeClass);
			_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Inicio')).datepicker({dateFormat: 'yy-mm-dd'}),options.spot);
			_showElement(_createElement(options.elemento,options.idCheck,options.idCheck,options.nameClassCheck,'checkbox'),options.spot);
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
	function _createElement(element,id,name,className,type,placeholder)
	{
		if (element!=undefined)
		{
			newElement=document.createElement(element);
			if (id!=undefined) newElement.id=id;
			if (name!=undefined) newElement.name=name;
			if (className!=undefined) newElement.className=className;
			if (type!=undefined) newElement.type=type;
			if (placeholder!=undefined) newElement.placeholder=placeholder;
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
	 * Recibe un string de ubicacion tipo jQuery y esta oculta y luego elimina el elemento
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

/**
 * Submodulo de manejo de peticiones AJAX
 */
$RENOC.AJAX=(function()
{
	/**
	 * Obtiene los datos del formulario 
	 * @access private
	 * @param string id es el id tipo jQuery para llamar el formulario
	 */
	function _getFormPost(id)
	{
	    return $(id).serializeArray();
	}

	/**
	 *
	 */
	/*function action()
	{
		$('#excelEspecificos,#listaEspecificos,#mailEspecificos').on('click',function()
		{
			$tipo=$(this).attr('id');
			switch($tipo)
			{
			case 'mailEspecificos':
			  execute code block 1
			  break;
			case 2:
			  execute code block 2
			  break;
			default:
			  code to be executed if n is different from case 1 and 2
			}
		});
	}*/
})();