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
	 * Encargado de asignar/quitar una clase.
	 * @access private
	 * @param jQuery obj es el objeto de la fila que se quiere manipular
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
         * En lo que la data es cargada, elimina los div que conforman el msj cargando y genera un fancybox con la data (body)
         * Carga la opcion de imprimir y la de cerrar el fancybox
         * @param {type} body
         * @returns {undefined}
         */
        function fancyBox(body)
        {
            $(".cargando").remove();
            var background =$("<div class='emergingBackground'></div> <div class='fancybox'> <div class='imprimir'><img src='/images/print.png'class='ver'></div><div class='a_imprimir'>" + body + "</div> </div>").hide();
            $("body").append(background);
            background.slideDown('slow');
            $('.imprimir').on('click', function() {
                $RENOC.UI.imprimir(".a_imprimir");
            });
            $RENOC.UI.closeEmergingBackground();
        }
        /**
         * Escucha el click para cerrar el fancybox
         * @returns {undefined}
         */
        function closeEmergingBackground()
        {
            $('.emergingBackground').on('click', function()
            {
                $(".fancybox,.mensaje").fadeOut('slow');
                $(".emergingBackground,.fancybox").remove();
            });
        }
        /**
         * Imprime la data que se encuentre en el div que se le pase
         * @param {type} div
         * @returns {undefined}
         */
        function imprimir(div)
        {
            var imp,
            contenido = $(div).clone().html();                    //selecciona el objeto
            imp = window.open(" RENOC ", "Formato de Impresion"); // titulo
            imp.document.open();                                //abre la ventana
            imp.document.write(contenido);                      //agrega el objeto
            imp.document.close();
            imp.print();                                        //Abre la opcion de imprimir
            imp.close();                                        //cierra la ventana nueva
        }
        /**
         * 
         * @param {type} hide
         * @param {type} show
         * @returns {undefined}
         */
        function showHideElement(hide, show)
        {
            for (var i = 0, j = hide.length - 1; i <= j; i++) {
                $(hide[i]).fadeOut('fast');
            }
            for (var x = 0, z = show.length - 1; x <= z; x++) {
                $(show[x]).toggle('slide');
            }
        }

	/**
	 * retorna los metodos publicos
	 */
	return{
		init:init,
                fancyBox:fancyBox,
                closeEmergingBackground:closeEmergingBackground,
                imprimir:imprimir,
                showHideElement:showHideElement
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
	 * Crea un array con los nombres de carrier y los grupos de carriers
	 * @access private
	 */
	function _getNamesCarriers()
	{
		$.ajax({url:"carrier/nombres",success:function(datos)
		{
			$RENOC.DATA.carriers=JSON.parse(datos);
			$RENOC.DATA.nombresCarriers=Array();
			for(var i=0, j=$RENOC.DATA.carriers.length-1; i<=j; i++)
			{
				$RENOC.DATA.nombresCarriers[i]=$RENOC.DATA.carriers[i].name;
			};
		}
		});
		$.ajax({url:"grupos/nombres",success:function(datos)
		{
			$RENOC.DATA.groups=JSON.parse(datos);
			$RENOC.DATA.nombresGroups=Array();
			for(var i=0, j=$RENOC.DATA.groups.length-1; i<=j; i++)
			{
				$RENOC.DATA.nombresGroups[i]=$RENOC.DATA.groups[i].name;
			};
		}
		});
	}


	/**
	 * Inicializa las funciones del submodulo
	 * @access public
	 */
	function init()
	{
		_getNamesCarriers();
	}

	return {init:init}
})();

$RENOC.DATA={};