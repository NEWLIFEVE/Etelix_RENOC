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
			$cuerpo.="<tr><td colspan='13' style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>".Carrier::getName($carrier)."</td></tr>";
        	$cuerpo.=self::cabecera(array('Ranking','Destino','Minutos','Intentos Totales','Intentos Completados','Intentos NC Inc. RI','Intentos NC Exc. RI','ASR Inc. RI','ASR Exc. RI','&Delta; ASR','ACD','PDD Inc. RI','PDD Exc. RI'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        	foreach ($model as $key => $destino)
        	{
        		$pos=$key+1;
        		if($destino->delta>1)
        		{
        			$estilo="background-color:yellow; color:#584E4E; border: 1px solid rgb(121, 115, 115);";
        		}
        		else
        		{
        			$estilo=self::colorEstilo($pos);
        		}
        		$cuerpo.="<tr>
        					<td style='".$estilo."'>".$pos."</td>
        					<td style='".$estilo."'>".$destino->destino."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->minutes)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->total_calls)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->complete_calls_exc)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->incomplete_calls_inc)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->incomplete_calls_exc)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->asr_inc)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->asr_exc)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->delta)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->acd)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->pdd_inc)."</td>
        					<td style='".$estilo."'>".Yii::app()->format->format_decimal($destino->pdd_exc)."</td>
        				</tr>";
        	}
        	$cuerpo.=self::cabecera(array('Ranking','Destino','Minutos','Intentos Totales','Intentos Completados','Intentos NC Inc. RI','Intentos NC Exc. RI','ASR Inc. RI','ASR Exc. RI','&Delta; ASR','ACD','PDD Inc. RI','PDD Exc. RI'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
			$total=self::getTotalDestinations($startDate,$endingDate,$carrier);
			$cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
						<td></td>
						<td> TOTAL </td>
						<td>".Yii::app()->format->format_decimal($total->minutes)."</td>
						<td>".Yii::app()->format->format_decimal($total->total_calls)."</td>
						<td>".Yii::app()->format->format_decimal($total->complete_calls_exc)."</td>
						<td>".Yii::app()->format->format_decimal($total->incomplete_calls_inc)."</td>
						<td>".Yii::app()->format->format_decimal($total->incomplete_calls_exc)."</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					  </tr>";
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
		$sql="SELECT d.name AS destino, b.minutes, b.total_calls, b.complete_calls_exc, b.incomplete_calls_inc, b.incomplete_calls_exc, b.asr_inc, b.asr_exc, b.asr_exc-b.asr_inc AS delta, b.acd, b.pdd_inc, b.pdd_exc
			  FROM(SELECT b.id_destination, SUM(b.minutes) AS minutes, SUM(b.incomplete_calls_inc+b.complete_calls_inc) AS total_calls, SUM(b.complete_calls_exc) AS complete_calls_exc, SUM(b.incomplete_calls_inc) AS incomplete_calls_inc, SUM(b.incomplete_calls_exc) AS incomplete_calls_exc, CASE WHEN SUM(b.complete_calls_inc)=0 THEN 0 ELSE (SUM(b.complete_calls_inc)*100/SUM(b.incomplete_calls_inc+b.complete_calls_inc)) END AS asr_inc, CASE WHEN SUM(b.complete_calls_exc)=0 THEN 0 ELSE (SUM(b.complete_calls_exc)*100/SUM(b.incomplete_calls_exc+b.complete_calls_exc)) END AS asr_exc, CASE WHEN SUM(b.minutes)=0 THEN 0 ELSE (SUM(b.minutes)/SUM(b.complete_calls_exc)) END AS acd, CASE WHEN SUM(pdd_inc)=0 THEN 0 ELSE (SUM(pdd_inc)/SUM(incomplete_calls_inc+complete_calls_inc)) END AS pdd_inc, CASE WHEN SUM(pdd_exc)=0 THEN 0 ELSE (SUM(pdd_exc)/SUM(incomplete_calls_exc+complete_calls_exc)) END AS pdd_exc
			  	   FROM(SELECT id_destination, SUM(minutes) AS minutes, SUM(incomplete_calls) AS incomplete_calls_exc, CAST(0 AS double precision) AS incomplete_calls_inc, SUM(complete_calls) AS complete_calls_exc, CAST(0 AS double precision) AS complete_calls_inc, SUM(pdd) AS pdd_exc, CAST(0 AS double precision) AS pdd_inc
			  	   		FROM balance
			  	   		WHERE id_carrier_customer={$carrier} AND date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination')
			  	   		GROUP BY id_destination
			  	   		UNION
			  	   		/*Trae los datos sin Unkwon_carrier*/
			  	   		SELECT id_destination, CAST(0 AS double precision) AS minutes, CAST(0 AS double precision) AS incomplete_calls_exc, SUM(incomplete_calls) AS incomplete_calls_inc, CAST(0 AS double precision) AS complete_calls_exc, SUM(complete_calls) AS complete_calls_inc, CAST(0 AS double precision) AS pdd_exc, SUM(pdd) AS pdd_inc
			  	   		FROM balance
			  	   		WHERE id_carrier_customer={$carrier} AND date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination')
			  	   		GROUP BY id_destination) b
				   GROUP BY id_destination
				   ORDER BY minutes DESC) b, destination d
			  WHERE b.id_destination=d.id
			ORDER by b.minutes DESC";
		return Balance::model()->findAllBySql($sql);
	}

	/**
	 * Metodo encargado de traer de base de datos los totales de los destinos de calidad de un proveedor especifico
	 * @access private
	 * @param date $startDate fecha de inicio de consulta
	 * @param date $endingDate fecha fin de la consulta
	 * @param int $carrier id del carrier que quieres consultar
	 * @return object $model
	 */
	private static function getTotalDestinations($startDate,$endingDate,$carrier)
	{
		$sql="SELECT SUM(b.minutes) AS minutes, SUM(b.total_calls) AS total_calls, SUM(b.complete_calls_exc) AS complete_calls_exc, SUM(b.incomplete_calls_inc) AS incomplete_calls_inc, SUM(b.incomplete_calls_exc) AS incomplete_calls_exc
			  FROM(SELECT b.id_destination, SUM(b.minutes) AS minutes, SUM(b.incomplete_calls_inc+b.complete_calls_inc) AS total_calls, SUM(b.complete_calls_exc) AS complete_calls_exc, SUM(b.incomplete_calls_inc) AS incomplete_calls_inc, SUM(b.incomplete_calls_exc) AS incomplete_calls_exc
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
				   ORDER BY minutes DESC) b";
		return Balance::model()->findBySql($sql);
	}
}
?>