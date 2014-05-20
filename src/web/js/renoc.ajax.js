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