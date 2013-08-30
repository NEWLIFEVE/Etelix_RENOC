<?php
/**
* Creada para generar reporte de compra venta
* @package reportes
*/
class RankingCompraVenta extends Reportes
{
	/**
    * @param $fecha date fecha que va a ser consultada
    * @return $cuerpo string con el cuerpo de la tabla
    */
	public static function reporte($fecha)
	{
		$sqlVendedores="SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
						FROM(SELECT id_carrier_customer, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
						     FROM balance 
						     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
						     GROUP BY id_carrier_customer)b,
						     managers m,
						     carrier_managers cm
						WHERE m.id = cm.id_managers AND b.id_carrier_customer = cm.id_carrier
						GROUP BY m.name, m.lastname
						ORDER BY margin DESC";
		$cuerpo="<div>
                    <table>
                        <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Vendedor','Minutos','Revenue','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.="<thead>
                  <tbody>";
        $vendedores=Balance::model()->findAllBySql($sqlVendedores);
        if($vendedores!=null)
        {
        	foreach ($vendedores as $key => $vendedor)
        	{
        		$pos=$key+1;
        		$cuerpo.="<tr>
        					<td style='".self::colorRankingCV(1)."'>".
        					$pos.
        					"</td>
        					<td style='".self::colorRankingCV(1)."'>".
        					$vendedor->nombre." ".$vendedor->apellido.
        					"</td>
        					<td style='".self::colorRankingCV(1)."'>".
        					Yii::app()->format->format_decimal($vendedor->minutes).
        					"</td>
        					<td style='".self::colorRankingCV(1)."'>".
        					Yii::app()->format->format_decimal($vendedor->revenue).
        					"</td>
        					<td style='".self::colorRankingCV(1)."'>".
        					Yii::app()->format->format_decimal($vendedor->margin).
        					"</td>
        					<td style='".self::colorRankingCV(1)."'>".
        					$pos.
        					"</td>
        				  </tr>";
        	}
        }
        else
        {
        	$cuerpo.="<tr>
        				<td colspan='6'>No se encontraron resultados</td>
        			  </tr>";
        }
        $sqlTotalVendedores="SELECT SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
							FROM(SELECT id_carrier_customer, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
							     FROM balance 
							     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
							     GROUP BY id_carrier_customer)b";
        $cuerpo.=self::cabecera(array('Ranking','Vendedor','Minutos','Revenue','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
		$totalVendedores=Balance::model()->findBySql($sqlTotalVendedores);
		if($totalVendedores->minutes!=null)
		{
			$cuerpo.="<tr>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					TOTAL
        					</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalVendedores->minutes).
        					"</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalVendedores->revenue).
        					"</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalVendedores->margin).
        					"</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					</td>
        				  </tr>";
		}
		else
        {
        	$cuerpo.="<tr>
        				<td colspan='6'>No se encontraron resultados</td>
        			  </tr>";
        }
        $cuerpo.="</tbody></table>";
        $sqlCompradores="SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
						FROM(SELECT id_carrier_supplier, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
						     FROM balance 
						     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
						     GROUP BY id_carrier_supplier)b,
						     managers m,
						     carrier_managers cm
						WHERE m.id = cm.id_managers AND b.id_carrier_supplier = cm.id_carrier
						GROUP BY m.name, m.lastname
						ORDER BY margin DESC";
		$cuerpo.="<br>
		<table >
                    <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Comprador','Minutos','Revenue','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.="<thead>
                  <tbody>";
        $compradores=Balance::model()->findAllBySql($sqlCompradores);
        if($compradores!=null)
        {
        	foreach($compradores as $key => $comprador)
        	{
        		$pos=$key+1;
        		$cuerpo.="<tr>
        					<td style='".self::colorRankingCV(2)."'>".
        					$pos.
        					"</td>
        					<td style='".self::colorRankingCV(2)."'>".
        					$comprador->nombre." ".$comprador->apellido.
        					"</td>
        					<td style='".self::colorRankingCV(2)."'>".
        					Yii::app()->format->format_decimal($comprador->minutes).
        					"</td>
        					<td style='".self::colorRankingCV(2)."'>".
        					Yii::app()->format->format_decimal($comprador->revenue).
        					"</td>
        					<td style='".self::colorRankingCV(2)."'>".
        					Yii::app()->format->format_decimal($comprador->margin).
        					"</td>
        					<td style='".self::colorRankingCV(2)."'>".
        					$pos.
        					"</td>
        				  </tr>";
        	}
        }
        else
        {
        	$cuerpo.="<tr>
        				<td colspan='6'>
        					No se encontraron resultados
        				</td>
        			  </tr>";
        }
        $cuerpo.=self::cabecera(array('Ranking','Comprador','Minutos','Revenue','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $sqlTotalCompradores="SELECT SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
								FROM(SELECT id_carrier_supplier, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
								     FROM balance 
								     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
								     GROUP BY id_carrier_supplier)b";
		$totalCompradores=Balance::model()->findBySql($sqlTotalCompradores);
		if($totalCompradores->minutes!=null)
		{
			$cuerpo.="<tr>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					TOTAL
        					</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalCompradores->minutes).
        					"</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalCompradores->revenue).
        					"</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalCompradores->margin).
        					"</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					</td>
        				  </tr>";
		}
		else
        {
        	$cuerpo.="<tr>
        				<td colspan='6'>No se encontraron resultados</td>
        			  </tr>";
        }
        $cuerpo.="</tbody></table>";
        $cuerpo.="<br>
		<table >
                    <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Consolidado (Ventas + Compras)','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.="<thead>
                  <tbody>";
        $sqlConsolidado="SELECT m.name AS nombre, m.lastname AS apellido, SUM(cs.margin) AS margin
						FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
						     FROM balance
						     WHERE date_balance='$fecha' 
						       AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
						       AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
						     GROUP BY id_carrier_customer
						     UNION
						     SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
						     FROM balance 
						     WHERE date_balance='$fecha' 
						       AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
						       AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
						     GROUP BY id_carrier_supplier)cs,
						     managers m,
						     carrier_managers cm
						WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier
						GROUP BY m.name, m.lastname
						ORDER BY margin DESC";
		$consolidados=Balance::model()->findAllBySql($sqlConsolidado);
		if($consolidados!=null)
		{
			foreach($consolidados as $key => $consolidado)
			{
				$pos=$key+1;
        		$cuerpo.="<tr>
        					<td style='".self::colorRankingCV(3)."'>".
        					$pos.
        					"</td>
        					<td style='".self::colorRankingCV(3)."'>".
        					$consolidado->nombre." ".$consolidado->apellido.
        					"</td>
        					<td style='".self::colorRankingCV(3)."'>".
        					Yii::app()->format->format_decimal($consolidado->margin).
        					"</td>
        					<td style='".self::colorRankingCV(3)."'>".
        					$pos.
        					"</td>
        				  </tr>";
			}
		}
		else
        {
        	$cuerpo.="<tr>
        				<td colspan='4'>No se encontraron resultados</td>
        			  </tr>";
        }
        $cuerpo.=self::cabecera(array('Ranking','Consolidado (Ventas + Compras)','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');

        $sqlTotalConsolidado="SELECT SUM(cs.margin) AS margin
							 FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
							      FROM balance
							      WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
							      GROUP BY id_carrier_customer
							      UNION
							      SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
							      FROM balance 
							      WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
							      GROUP BY id_carrier_supplier)cs";
		$totalConsolidado=Balance::model()->findBySql($sqlTotalConsolidado);
		if($totalConsolidado->margin!=null)
		{
			$cuerpo.="<tr>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					Total Consolidado
        					</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalConsolidado->margin).
        					"</td>
        					<td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
        					</td>
        				  </tr>";
		}
		else
        {
        	$cuerpo.="<tr>
        				<td colspan='4'>No se encontraron resultados</td>
        			  </tr>";
        }
        $cuerpo.="<tr>
        					<td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>
        					</td>
        					<td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>
        					Total Margen
        					</td>
        					<td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>".
        					Yii::app()->format->format_decimal($totalCompradores->margin).
        					"</td>
        					<td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>
        					</td>
        				  </tr>";
        $cuerpo.="</div>";
        return $cuerpo;
	}
}
?>