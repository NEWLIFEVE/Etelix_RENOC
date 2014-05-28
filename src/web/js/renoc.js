/**
 * Objeto Global
 */
 var $RENOC={
 	init:function()
 	{
 		$RENOC.ERRORS.setStatus('NONE');
 		$RENOC.UI.init();
        $RENOC.AJAX.init();
        //Descomentar para ver las caracteristicas de la aplicación
 		console.dir(this);
 	}
 };

/**
 * Modulo para almacenamiento de data traida desde el servidor
 */
$RENOC.DATA={};

/**
 * Modulo donde se instancian los elementos del dom
 */
$RENOC.DOM={};

/**
 * Modulo que guarda las comfiguraciones
 */
$RENOC.SETTINGS={
	links:'a#flecha-forward, a#flecha-backward',
	mainLayer:'#capa',
	newLayer:'.vistas',
	mail:'/site/mail',
	excel:'/site/excel',
	mailList:'/site/maillista'
};

/**
 * Modulo handler de errores
 */
$RENOC.ERRORS={
	status:null,
	NONE:0,
	ANY_SELECTED_REPORT:1,
	RUNNING_RERATE:2,
	MISSING_DATA:3,
	setStatus:function(status)
	{
		this.status=this[status];
	}
};

/**
 * Modulo para almacenmiento temporal
 */
$RENOC.TEMP={
	getFormPost:function()
	{
		this.form=$("#formulario").serializeArray();
	}
};

/**
 * Modulo encarcado de las validaciones
 */
$RENOC.VALIDATOR=(function()
{
	/**
	 * Metodo encarcado de validar que el rerate no este en funcionamiento
	 * @access public
	 * @return void
	 */
	function validateRerate()
	{
		var mensaje=null;
		if($RENOC.ERRORS.status==$RENOC.ERRORS.NONE)
		{
			if($RENOC.DATA.rerate==true)
			{
				mensaje="<h4>En estos momentos se esta corriendo un proceso de Re-Rate, es posible que la data en los reportes no sea fiable, desea igualmente emitir el/los reporte/es?.</h4><p>Si esta seguro presione Aceptar, de lo contrario cancelar</p><div class='rerateBtn'><div id='cancelar' class='cancelar'>Cancelar</div><div id='confirma' class='confirma'>Confirmar</div></div>";
            	$RENOC.UI.createLayer(mensaje);
            	$RENOC.ERRORS.setStatus('RUNNING_RERATE');
            	$('#cancelar, #confirma').on('click',function()
            	{
            		id=$(this).attr('id');
            		if(id=="confirma")
            		{
            			$RENOC.ERRORS.setStatus('NONE');
                	}
                	else
                	{
                    	$RENOC.ERRORS.setStatus('RUNNING_RERATE');
                	}
                	$RENOC.UI.destroyLayer();
                	//self.validarReporte('calidad','carrier');
            	});
        	}
    	}
	}

	/**
	 *
	 */
	function validationReport()
	{
		if($RENOC.ERRORS.status==$RENOC.ERRORS.NONE)
		{
			//validad el reporte de calidad
			if($('#calidad:checked').val()=="true")
			{
				var carrier=$('#carrier').val(),
				    group=$('#group').val(),
				    fecha=$('#startDate').val(),
				    frase="Debe ",
				    mensaje=null;
            	if(((carrier==="" || carrier===undefined) && (group==="" || group===undefined)) || (fecha==="" || fecha===undefined))
            	{
            		if((carrier=="" || carrier==undefined) || (group==="" || group===undefined)) frase=frase+" seleccionar carrier";
            		if((carrier=="" || carrier==undefined) && (fecha=="" || fecha==undefined)) frase=frase+" y";
            		if(fecha=="" || fecha==undefined) frase=frase+" seleccionar al menos una fecha";
            		frase=frase+" para generar el reporte";
            		mensaje="<h3>"+frase+"</h3><img src='/images/stop.png'width='25px' height='25px'/>";
            		$RENOC.UI.createLayer(mensaje);
            		setTimeout(function()
            		{
            			$RENOC.UI.destroyLayer();
            		}, 2000);
            		carrier=group=fecha=frase=mensaje=null;
            		$RENOC.ERRORS.setStatus('MISSING_DATA');
            	}
            }
        	//valida los demas reportes
        	if($('#compraventa:checked').val()=="true" || $('#AI10:checked').val()=="true" || $('#AI10R:checked').val()=="true")
        	{
        		if($('#startDate').val()=="" || $('#startDate').val()==undefined)
        		{
        			mensaje="<h3>Debe seleccionar al menos una fecha para generar el reporte</h3><img src='/images/stop.png'width='25px' height='25px'/>";
        			$RENOC.UI.createLayer(mensaje);
        			setTimeout(function()
        			{
        				$RENOC.UI.destroyLayer();
        			}, 2000);
        			mensaje=null;
        			$RENOC.ERRORS.setStatus('MISSING_DATA');
        		}
        	}
        }
    }

	/**
	 *
	 */
	return {
		validateRerate:validateRerate,
		validationReport:validationReport
	}
})();