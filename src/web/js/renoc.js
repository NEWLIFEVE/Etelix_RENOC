/**
 * Objeto Global
 */
 var $RENOC={
 	init:function()
 	{
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
	setStatus:function(status)
	{
		this.status=this[status];
	}
};

/**
 * Modulo para almacenmiento temporal
 */
$RENOC.TEMP={};

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
	return {
		validateRerate:validateRerate
	}
})();