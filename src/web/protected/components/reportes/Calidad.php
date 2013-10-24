<?php
/**
* Creada para generar reporte de calidad
* @package reportes
*/
class Calidad extends Reportes
{
	/**
	 * Metodo encargado de generar la tabla de html con las fechas pasadas
	 * @access public
	 * @static
	 * @param date $startDate fecha de inicio de la consulta
	 * @param date $endingDate fecha fin de la consulta
	 * @param int $carrier id del carrier que se quiere consultar
	 * @return string $cuerpo html de la tabla construida
	 */
	public static function getHtmlDestinations($startDate,$endingDate,$carrier)
	{
		$model=self::getDestinations($startDate,$endingDate,$carrier);
		if($model!=null)
		{
			$cuerpo="<table>";
			$cuerpo.="<tr><td colspan='11'>".Carrier::getName($carrier)."</td></tr>";
        	$cuerpo.=self::cabecera(array('Ranking','Destino','Minutos','Intentos Totales','Intentos Completados','Intentos NC Inc. RI','Intentos NC Exc. RI','ASR Inc. RI','ASR Exc. RI','&Delta; ASR','ACD'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        	foreach ($model as $key => $destino)
        	{
        		$cuerpo.="<tr>
        					<td>".$key+1."</td>
        					<td>".$destino->destino."</td>
        					<td>".$destino->minutes."</td>
        					<td>".$destino->total_calls."</td>
        					<td>".$destino->complete_calls_exc."</td>
        					<td>".$destino->incomplete_calls_inc."</td>
        					<td>".$destino->incomplete_calls_exc."</td>
        					<td>".$destino->asr_inc."</td>
        					<td>".$destino->asr_exc."</td>
        					<td>".$destino->delta."</td>
        					<td>".$destino->acd."</td><td></td></tr>";
        	}
			$cuerpo.="</table>";
			return $cuerpo;
		}
		return false;
	}

	/**
	 * Metodo encargado de traer de base de datos los destinos de calidad de un proveedor pasado
	 * @access private
	 * @static
	 * @param date $startDate fecha de inicio de consulta
	 * @param date $endingDate fecha fin de la consulta
	 * @param int $carrier id del carrier que quieres consultar
	 * @return object $model
	 */
	private static function getDestinations($startDate,$endingDate,$carrier)
	{
		$sql="SELECT d.name AS destino, b.minutes, b.total_calls, b.complete_calls_exc, b.incomplete_calls_inc, b.incomplete_calls_exc, b.asr_inc, b.asr_exc, b.asr_exc-b.asr_inc AS delta, b.acd
			  FROM(SELECT b.id_destination, SUM(b.minutes) AS minutes, SUM(b.incomplete_calls_inc+b.complete_calls_inc) AS total_calls, SUM(b.complete_calls_exc) AS complete_calls_exc, SUM(b.incomplete_calls_inc) AS incomplete_calls_inc, SUM(b.incomplete_calls_exc) AS incomplete_calls_exc, (SUM(b.complete_calls_inc)*100/SUM(b.incomplete_calls_inc+b.complete_calls_inc)) AS asr_inc, CASE WHEN SUM(b.complete_calls_exc)=0 THEN 0 ELSE (SUM(b.complete_calls_exc)*100/SUM(b.incomplete_calls_exc+b.complete_calls_exc)) END AS asr_exc, CASE WHEN SUM(b.minutes)=0 THEN 0 ELSE (SUM(b.minutes)/SUM(b.complete_calls_exc)) END AS acd
			  	   FROM(SELECT id_destination, SUM(minutes) AS minutes, SUM(incomplete_calls) AS incomplete_calls_exc, CAST(0 AS double precision) AS incomplete_calls_inc, SUM(complete_calls) AS complete_calls_exc, CAST(0 AS double precision) AS complete_calls_inc 
			  	   	    FROM balance
			  	   	    WHERE id_carrier_customer={$carrier} AND date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination')
			  	   	    GROUP BY id_destination
			  	   	    UNION
			  	   	    /*Trae los datos sin Unkwon_carrier*/
			  	   	    SELECT id_destination, CAST(0 AS double precision) AS minutes, CAST(0 AS double precision) AS incomplete_calls_exc, SUM(incomplete_calls) AS incomplete_calls_inc, CAST(0 AS double precision) AS complete_calls_exc, SUM(complete_calls) AS complete_calls_inc
			  	   	    FROM balance
			  	   	    WHERE id_carrier_customer={$carrier} AND date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination')
			  	   	    GROUP BY id_destination) b
				   GROUP BY id_destination
				   ORDER BY minutes DESC) b, destination d
			  WHERE b.id_destination=d.id AND b.minutes>0";
		return Balance::model()->findAllBySql($sql);
	}
}
?>