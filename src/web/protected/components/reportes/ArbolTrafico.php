<?php
/**
* @package reportes
* @version 1.0.1
*/
class ArbolTrafico extends ArbolDestino
{
	private $estilos;
	/**
	 * Si $tipo es true es clientes
	 */
	function __construct($fecha,$tipo=true,$destino=true)
	{
		$this->fecha=$fecha;
		if($tipo)
		{
			$this->carrier=self::ID_CUSTOMER;
			$this->titulo['sql']=self::CUSTOMER;
			$this->titulo['tabla']=self::TITULO_CUSTOMER;
			$this->estilos="border:solid #615E5E 2px;background:#AED7F3; color:#615E5E;";
		}
		else
		{
			$this->carrier=self::ID_SUPPLIER;
			$this->titulo['sql']=self::SUPPLIER;
			$this->titulo['tabla']=self::TITULO_SUPPLIER;
			$this->estilos="border:solid #615E5E 2px;background:#FFC8AE; color:#615E5E;";
		}
		if($destino)
		{
			$this->destino=self::DESTINATION_EXTERNAL;
			$this->table=self::TABLE_EXTERNAL;
		}
		else
		{
			$this->destino=self::DESTINATION_INTERNAL;
			$this->table=self::TABLE_INTERNAL;
		}
	}
	/**
	* Genera la tabla de Arbol de Trafico (+10$)
	* @return string con la tabla armada
	*/
	public function reporte()
	{
             ini_set('max_execution_time', 1200);
		$cuerpo="<div>
                  <table>";
        $sql="SELECT x.{$this->carrier} AS id, c.name AS {$this->titulo['sql']}, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
		      FROM(SELECT {$this->carrier}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
			       FROM balance
			       WHERE date_balance='{$this->fecha}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$this->destino}<>(SELECT id FROM {$this->table} WHERE name='Unknown_Destination') AND {$this->destino} IS NOT NULL 
				   GROUP BY {$this->carrier}
				   ORDER BY margin DESC) x, carrier c
              WHERE x.margin>10 AND x.{$this->carrier}=c.id
              ORDER BY x.margin DESC";

        $carriers=Balance::model()->findAllBySql($sql);
        foreach ($carriers as $key => $carrier)
        {
        	$pos=$key+1;
        	$cuerpo.="<tr><td  style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;aling-text:left' colspan='13'>POSICION:".$pos."</td></tr>";
        	$cuerpo.=self::cabecera(array($this->titulo['tabla']." (+10$)",'TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Cost/Min','Rev/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        	$cuerpo.="<tr>
        				<td style='".$this->estilos."'>".
        					$carrier->{$this->titulo['sql']}.
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->total_calls).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->complete_calls).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->minutes).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->asr).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->acd).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->pdd).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->cost).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->revenue).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->margin).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->costmin).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->ratemin).
        			   "</td>
        				<td style='".$this->estilos."'>".
        					Yii::app()->format->format_decimal($carrier->marginmin).
        			   "</td>
        			</tr>";
        	$cuerpo.=$this->firstSeven($carrier->id,'border:solid #615E5E 1px;background:#AFD699; color:#615E5E;');
        	$cuerpo.=$this->total($carrier->id,'border:solid #615E5E 1px;background:#999999; color:#615E5E;');
        	$cuerpo.="<tr><td colspan='13'></td></tr><tr><td colspan='13'></td></tr>";
        }
        $cuerpo.="</table>
        		</div>";
        return $cuerpo;
	}

	/**
	 * Genera la tabla de destinos por carrier
	 * @access public
	 * @param int $idCarrier el id del carrier a consultar
	 * @param string $estilo el estilo que se le va a la tabla
	 * @return string $cuerpo
	 */
	public function firstSeven($idCarrier,$estilo)
	{
		$cuerpo=self::cabecera(array('Destinos','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Cost/Min','Rev/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
		$sql="SELECT b.{$this->destino}, d.name AS destino, b.total_calls, b.complete_calls, b.minutes, b.asr, b.acd, b.pdd, b.cost, b.revenue, b.margin, b.costmin, b.ratemin, b.marginmin
			  FROM(SELECT {$this->destino}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE ((SUM(complete_calls)*100)/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE SUM(minutes)/SUM(complete_calls) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(pdd)/SUM(incomplete_calls+complete_calls)) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, CASE WHEN SUM(cost)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 ELSE (SUM(cost)/SUM(minutes)*100) END AS costmin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 ELSE (SUM(revenue)/SUM(minutes)*100) END AS ratemin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 WHEN SUM(cost)=0 THEN 0 ELSE (SUM(revenue)/SUM(minutes)*100)-(SUM(cost)/SUM(minutes)*100) END AS marginmin
			       FROM balance
			       WHERE date_balance='{$this->fecha}' AND {$this->destino} IS NOT NULL AND {$this->destino}<>(SELECT id FROM {$this->table} WHERE name='Unknown_Destination') AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$this->carrier}={$idCarrier}
			       GROUP BY {$this->destino}) b, {$this->table} d
			  WHERE b.{$this->destino}=d.id
			  ORDER BY b.margin DESC
			  LIMIT 7";
		$varios=Balance::model()->findAllBySql($sql);
		foreach ($varios as $key => $uno)
		{
			$cuerpo.="<tr>
						<td style='".$estilo."'>".
							$uno->destino.
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->total_calls).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->complete_calls).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->minutes).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->asr).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->acd).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->pdd).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->cost).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->revenue).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->margin).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->costmin).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->ratemin).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($uno->marginmin).
					   "</td>
					 </tr>";
		}
		return $cuerpo;
	}

	/**
	 * Arma el html de una fila con los totales
	 * @access public
	 * @param int $idCarrier el id del carrier a consultar
	 * @param string $estilo es el estilo que se define para la fila
	 * @return string $cuerpo
	 */
	public function total($idCarrier,$estilo)
	{
		$sql="SELECT SUM(b.total_calls) AS total_calls, SUM(b.complete_calls) AS complete_calls, SUM(b.minutes) AS minutes, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin, CASE WHEN SUM(b.minutes)=0 THEN 0 WHEN SUM(b.cost)=0 THEN 0 ELSE (SUM(b.cost)/SUM(b.minutes))*100 END AS costmin, CASE WHEN SUM(b.minutes)=0 THEN 0 WHEN SUM(b.revenue)=0 THEN 0 ELSE (SUM(b.revenue)/SUM(b.minutes))*100 END AS ratemin, CASE WHEN SUM(b.minutes)=0 THEN 0 ELSE (CASE WHEN SUM(b.revenue)=0 THEN 0 ELSE (SUM(b.revenue)/SUM(b.minutes))*100 END)-(CASE WHEN SUM(b.cost)=0 THEN 0 ELSE (SUM(b.cost)/SUM(b.minutes))*100 END) END AS marginmin
		      FROM(SELECT b.{$this->destino}, d.name AS destino, b.total_calls, b.complete_calls, b.minutes, b.asr, b.acd, b.pdd, b.cost, b.revenue, b.margin, b.costmin, b.ratemin, b.marginmin
			  	   FROM(SELECT {$this->destino}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE ((SUM(complete_calls)*100)/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE SUM(minutes)/SUM(complete_calls) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(pdd)/SUM(incomplete_calls+complete_calls)) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, CASE WHEN SUM(cost)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 ELSE (SUM(cost)/SUM(minutes)*100) END AS costmin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 ELSE (SUM(revenue)/SUM(minutes)*100) END AS ratemin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 WHEN SUM(cost)=0 THEN 0 ELSE (SUM(revenue)/SUM(minutes)*100)-(SUM(cost)/SUM(minutes)*100) END AS marginmin
			            FROM balance
			            WHERE date_balance='{$this->fecha}' AND {$this->destino} IS NOT NULL AND {$this->destino}<>(SELECT id FROM {$this->table} WHERE name='Unknown_Destination') AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$this->carrier}={$idCarrier}
			            GROUP BY {$this->destino}) b, {$this->table} d
			       WHERE b.{$this->destino}=d.id
			       ORDER BY b.margin DESC
			       LIMIT 7)b";
		$total=Balance::model()->findBySql($sql);
		$cuerpo="<tr>
						<td style='".$estilo."'>
							TOTAL
						</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->total_calls).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->complete_calls).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->minutes).
					   "</td>
						<td style='".$estilo."'></td>
						<td style='".$estilo."'></td>
						<td style='".$estilo."'></td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->cost).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->revenue).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->margin).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->costmin).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->ratemin).
					   "</td>
						<td style='".$estilo."'>".
							Yii::app()->format->format_decimal($total->marginmin).
					   "</td>
					 </tr>";
		return $cuerpo;
	}
}
?>