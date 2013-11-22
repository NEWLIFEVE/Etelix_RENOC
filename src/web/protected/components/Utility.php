<?php
/**
 * Clase preparada para actividades muy especificas
 * @package components
 */
class Utility
{
	/**
	 * Metodo estatico encargado de restar o sumar dias a una fecha
	 * @access public
	 * @static
	 * @param string $days es la cantidad de dias a sumar o restar pero debe incluir el + o - para la operación
	 * ejemplo para sumar un dia seria "+1" o restar dos "-2"
	 * @param date $date es la fecha formato yyyy-mm-dd
	 * @return date la fecha nueva formato yyyy-mm-dd
	 */
	public static function calculateDate($days,$date)
	{
		$newDate=strtotime($days.' day',strtotime($date));
		return date('Y-m-d',$newDate);
	}

	/**
	 * Metodo que lleva a dia uno cualquier fecha psada como parametro
	 * @access public
	 * @static
	 * @param date $date es la fecha en formato yyyy-mm-dd
	 * @return date yyyy-mm-dd
	 */
	public static function getDayOne($date)
	{
		$arrayDate=explode("-",$date);
		return $arrayDate[0]."-".$arrayDate[1]."-01";
	}
}
?>