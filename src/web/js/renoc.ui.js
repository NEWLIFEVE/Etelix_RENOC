/**
 * Submodulo para manejo de interfaz grafica
 */
 $RENOC.UI=(function()
 {
 	/**
 	 * Metodo para inicialzar acciones de click en interfaz
 	 * @acces {public}
 	 */
 	function init()
 	{
 		//
 		setAll();
 		//Pendiente de los click
 		_listen();
 		

 		//Crea los inputs usados para la fecha en especificos
 		var checkFecha=document.getElementsByName('lista[Fecha]');
 		if(checkFecha.length!=0)
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
 		//crea el input usado para carrier en la interfaz especificos
 		var checkCarrier=document.getElementsByName('lista[Carrier]');
 		if(checkCarrier.length!=0)
 		{
 			optionsCarrier={
 				elemento:'input',
 				idInputStart:'carrier',
 				idInputEnd:'',
 				idCheck:'',
 				nameClassPicker:'middle_carrier carrier',
 				nameClassCheck:'middle carrier',
 				spot:'div.choice_parametros.carrier'
 			};
 			checkCarrier[0].onclick=function()
 			{
 				_changeClass($('.carrier label h4'),'stretchRight','offStretchRight',optionsCarrier);
 			};
 		}
 		//crea el input usado para grupos en la interfaz especificos
 		var checkGroup=document.getElementsByName('lista[Group]');
 		if(checkGroup.length!=0)
 		{
 			optionsGroup={
 				elemento:'input',
 				idInputStart:'group',
 				idInputEnd:'',
 				checks:{
 					primero:{
 						id:'asr',
 						name:'alarma',
 						className:'',
 						type:'radio',
 						text:'ASR'
 					},
 					segundo:{
 						id:'pdd',
 						name:'alarma',
 						className:'',
 						type:'radio',
 						text:'PDD'
 					},
 					tercero:{
 						id:'uno',
 						name:'porcentaje',
 						className:'',
 						type:'radio',
 						text:'+1%'
 					},
 					cuarto:{
 						id:'dos',
 						name:'porcentaje',
 						className:'',
 						type:'radio',
 						text:'+5%'
 					}
 				},
 				nameClassPicker:'middle_group group',
 				nameClassCheck:'middle group',
 				spot:'div.choice_parametros.group'
 			};
 			checkGroup[0].onclick=function()
 			{
 				_changeClass($('.group label h4'),'stretchRight','offStretchRight',optionsGroup);
 			};
 		}
 	}

 	/**
 	 *
 	 */
 	function _listen()
 	{
 		if($RENOC.DOM.links.length!=0)
 		{
 			$RENOC.DOM.links.on('click',function(e)
 			{
 				e.preventDefault();
 				if($(this).attr('href')=="/")
 				{
 					_back();
 				}
 				else
 				{
 					_go($(this));
 				}
 			});
 		}
 	}

 	/**
	 * Encargado de asignar/quitar una clase.
	 * @access {private}
	 * @param {jQuery} obj es el objeto de la fila que se quiere manipular
	 */
	function _changeClass(obj,activeClass,desactiveClass,options)
	{
		if(obj.attr('class')==activeClass)
		{
			var todos="";
			obj.removeClass(activeClass).addClass(desactiveClass);
			if (options.idInputStart!="") todos+='#'+options.idInputStart;
			if (options.idCheck!="") todos+=',#'+options.idCheck;
			if (options.idInputEnd!="") todos+=',#'+options.idInputEnd;
			_hideElement(todos);
		}
		else
		{
			obj.removeClass(desactiveClass).addClass(activeClass);
			if(options.idInputStart=='carrier')
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Carrier')).autocomplete({source:$RENOC.DATA.nombresCarriers}),options.spot);
			}
			else if(options.idInputStart=='group')
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);
				//radios
				/*var radios=options.checks;
				for(var key in radios)
				{
					console.dir(radios[key]);
					_showElement($("<input class='"+radios[key].className+"' id='"+radios[key].id+"' type='"+radios[key].type+"' name='"+radios[key].name+"'>"+radios[key].text+"</input>"),options.spot);
				}
				/*_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);*/
			}
			else
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Inicio')).datepicker({dateFormat: 'yy-mm-dd'}),options.spot);
				_showElement(_createElement(options.elemento,options.idCheck,options.idCheck,options.nameClassCheck,'checkbox'),options.spot);
			}
		}
		obj=null;
	}

	/**
	 * Crea un elemento html con todas caracteristicas
	 * @access {private}
	 * @param {String} element es el nombre del elemento a crear
	 * @param {String} id es el id que se le asigna al elemento
	 * @param {String} name es el nombre del elemento
	 * @param {String} className son la/las clases que llevara el elemento
	 * @param {String} type tipo de elemento
	 * @return {DOM} newElement
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
	 * @access {private}
	 * @param {DOM} object es el elemento html a agregar y mostrar
	 * @param {String} spot es la ubicacion tipo jQuery donde agregar el elemento
	 * @return {void}
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
	 * @access {private}
	 * @param {String} spot es la ubicacion tipo jQuery
	 * @return {void}
	 */
	function _hideElement(spot)
	{
		$(spot).fadeOut('slow');
		$(spot).remove();
	}

	/**
	 *
	 */
	function setAll()
	{
		$RENOC.DOM=null;
		$RENOC.DOM={};
		_setMain();
		_setNew();
		_setLinks();
	}

	/**
	 * Asigna al submodulo de DOM.main el objeto principal
	 * @access {private}
	 * @return {void}
	 */
	function _setMain()
	{
		$RENOC.DOM.mainLayer=$($RENOC.SETTINGS.mainLayer);
	}

	/**
	 * Asigna al submodulo de DOM.nueva el objeto que se va amanipular para cargar las vistas por AJAX
	 * @access {private}
	 * @return {void}
	 */
	function _setNew()
	{
		$RENOC.DOM.newLayer=$($RENOC.SETTINGS.newLayer);
	}

	/**
	 * Asigna los botones con lo que se navegara en la aplicacion
	 * @access {private}
	 * @return {void}
	 */
	function _setLinks()
	{
		$RENOC.DOM.links=$($RENOC.SETTINGS.links);
	}

	/**
	 * Encargada de cargar la nueva capa con efectos
	 * @access {private}
	 * @return {void}
	 */
	function _go(obj)
	{
		$RENOC.DOM.newLayer.hide().load(obj.attr('href'),function()
		{
			$RENOC.DOM.mainLayer.hide('slow');
			$RENOC.DOM.newLayer.show('slow',function()
			{
				setAll();
				if(obj.attr('href')=='/site/rutinarios')
				{
					_routineBottons();
				}
			});
			_listen();
		});
	}

	/**
	 * Encargada de cargar la anterior vista con efectos
	 * @access {private}
	 * @return {void}
	 */
	function _back()
	{
		$RENOC.DOM.newLayer.hide('slow');
		$RENOC.DOM.mainLayer.show('slow',function()
		{
			$RENOC.DOM.newLayer.html("",setAll());
		});
	}

	/** 
	 * Activa los botones de rutinarios
	 */
	function _routineBottons()
	{
		$('#mail,#excel,#lista').on('click',function(e)
		{
			var numero=mensaje=null;
			e.preventDefault();
			//Reviso cuantos check han sido seleccionados
		    numero=$('input[type="checkbox"]').filter(function()
		    {
		        return $(this).is(':checked');
		    });
		    $RENOC.TEMP.type=$(this).attr('id');
		    if(numero.length<=0)
	        {
	            mensaje="<h3>Debe seleccionar al menos un tipo de reporte</h3><img src='/images/stop.png'width='25px' height='25px'/>";
	            _createLayer(mensaje);
	            setTimeout(function()
	            {
	                self.destruirCapa();
	            }, 2000);
	            mensaje=null;
	            self.setUno();
	        }
		});
	}

	/**
	 * Encargada de crear un capa con cierto mensaje pasado por parametros
	 * @param {String} message
	 * @access private
	 */
	function _createLayer(message)
	{
		if($('.cargando').length>0)
		{
			$('.mensaje').html(message);
		}
		else
		{
			$RENOC.DOM.messageLayer=$("<div class='cargando'><div class='mensaje'></div></div>").hide();
			$("body").append($RENOC.DOM.messageLayer);
			$('.mensaje').html(mensaje);
			$('.cargando').fadeIn('fast');
		}
	}


	/**
	 * retorna los metodos publicos
	 */
	return{
		init:init
	}
 })();