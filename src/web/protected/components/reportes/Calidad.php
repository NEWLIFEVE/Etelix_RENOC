<?php
/**
* Creada para generar reporte de calidad
* @package reportes
*/
class Calidad extends Reportes
{
	/**
	 * 
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
			  WHERE b.id_destination=d.id";
		return Balance::model()->findAllBySql($sql);
	}
}
?>