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
	mainLayer:'#capa',
	newLayer:'.vistas'
};