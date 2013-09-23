<?php
/**
* @package reportes
*/
class ArbolDeTrafico extends Reportes
{
	private $fecha;
	private $destino;
	private $table;
	private $carrier;
	private $titulo=array();
	const DESTINATION_INTERNAL="id_destination_int";
	const DESTINATION_EXTERNAL="id_destination";
	const TABLE_INTERNAL="destination_int";
	const TABLE_EXTERNAL="destination";
	const ID_CUSTOMER="id_carrier_customer";
	const ID_SUPPLIER="id_carrier_supplier";
	const TITULO_CUSTOMER="Clientes";
	const TITULO_SUPPLIER="Proveedores";
	const CUSTOMER="cliente";
	const SUPPLIER="proveedor";

	function __construct($fecha,$tipo=true)
	{
		$this->fecha=$fecha;
		if($tipo)
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
		$cuerpo="<div>
                  <table>";
        $sqlDestinos="SELECT x.{$this->destino} AS id, d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
					  FROM(SELECT {$this->destino}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     					   FROM balance
     					   WHERE date_balance='$this->fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$this->destino}<>(SELECT id FROM {$this->table} WHERE name = 'Unknown_Destination') AND {$this->destino} IS NOT NULL
     					   GROUP BY {$this->destino}
     					   ORDER BY margin DESC) x, {$this->table} d
					  WHERE x.margin > 10 AND x.{$this->destino} = d.id
					  ORDER BY x.margin DESC";

        $destinos=Balance::model()->findAllBySql($sqlDestinos);
        foreach ($destinos as $key => $destino)
        {
        	$pos=$key+1;
        	$cuerpo.="<tr><td  style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;aling-text:left' colspan='13'>POSICION:".$pos."</td></tr>";
        	$cuerpo.=self::cabecera(array('Destino (+10$)','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Cost/Min','Rev/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        	$cuerpo.="<tr>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					$destino->destino.
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->total_calls).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->complete_calls).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->minutes).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->asr).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->acd).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->pdd).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->cost).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->revenue).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->margin).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->costmin).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->ratemin).
        			   "</td>
        				<td style='border:solid #615E5E 2px;background:#AFD699; color:#615E5E;'>".
        					Yii::app()->format->format_decimal($destino->marginmin).
        			   "</td>
        			</tr>";
        	$cuerpo.=$this->cincoPrimeros($destino->id,'border:solid #615E5E 1px;background:#AED7F3; color:#615E5E;',true);
        	$cuerpo.=$this->totales($destino->id,'border:solid #615E5E 1px;background:#999999; color:#615E5E;',true);
        	$cuerpo.=$this->cincoPrimeros($destino->id,'border:solid #615E5E 1px;background:#FFC8AE; color:#615E5E;',false);
        	$cuerpo.=$this->totales($destino->id,'border:solid #615E5E 1px;background:#999999; color:#615E5E;',false);
        	$cuerpo.="<tr><td colspan='13'></td></tr><tr><td colspan='13'></td></tr>";
        }
        $cuerpo.="</table>
        		</div>";
        return $cuerpo;
	}
	/**
	*
	*/
	public function cincoPrimeros($idDestino,$estilo,$tipo=true)
	{
		$this->define($tipo);

		$cuerpo=self::cabecera(array($this->titulo['tabla'],'TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Cost/Min','Rev/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
		$sql="SELECT c.name AS {$this->titulo['sql']}, 
					 b.total_calls, 
					 b.complete_calls, 
					 b.minutes, 
					 CASE WHEN b.complete_calls=0 THEN 0 WHEN b.total_calls=0 THEN 0 ELSE (b.complete_calls*100/b.total_calls) END AS asr, 
					 CASE WHEN b.minutes=0 THEN 0 WHEN b.complete_calls=0 THEN 0 ELSE (b.minutes/b.complete_calls) END AS acd, 
					 CASE WHEN b.pdd=0 THEN 0 WHEN b.total_calls=0 THEN 0 ELSE (b.pdd/b.total_calls) END AS pdd, 
					 b.cost, 
					 b.revenue, 
					 b.margin, 
					 CASE WHEN b.minutes=0 THEN 0 WHEN b.cost=0 THEN 0 ELSE (b.cost/b.minutes)*100 END AS costmin, 
					 CASE WHEN b.minutes=0 THEN 0 WHEN b.revenue=0 THEN 0 ELSE (b.revenue/b.minutes)*100 END AS ratemin, 
					 CASE WHEN b.minutes=0 THEN 0 ELSE (CASE WHEN b.revenue=0 THEN 0 ELSE (b.revenue/b.minutes)*100 END)-(CASE WHEN b.cost=0 THEN 0 ELSE (b.cost/b.minutes)*100 END) END AS marginmin
			   FROM(SELECT {$this->carrier} AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(pdd) AS pdd
			   		FROM balance
			   		WHERE date_balance='{$this->fecha}' AND {$this->destino}={$idDestino} AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$this->destino} IS NOT NULL
			   		GROUP BY {$this->carrier}
			   		ORDER BY minutes DESC
			   		LIMIT 5)b, carrier c
			   WHERE c.id=b.id";
		$varios=Balance::model()->findAllBySql($sql);
		foreach ($varios as $key => $uno)
		{
			$cuerpo.="<tr>
						<td style='".$estilo."'>".
							$uno->{$this->titulo['sql']}.
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
	*
	*/
	public function totales($idDestino,$estilo,$tipo=true)
	{
		$this->define($tipo);
		$sql="SELECT SUM(b.total_calls) AS total_calls, SUM(b.complete_calls) AS complete_calls, SUM(b.minutes) AS minutes, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin, CASE WHEN SUM(b.minutes)=0 THEN 0 WHEN SUM(b.cost)=0 THEN 0 ELSE (SUM(b.cost)/SUM(b.minutes))*100 END AS costmin, CASE WHEN SUM(b.minutes)=0 THEN 0 WHEN SUM(b.revenue)=0 THEN 0 ELSE (SUM(b.revenue)/SUM(b.minutes))*100 END AS ratemin, CASE WHEN SUM(b.minutes)=0 THEN 0 ELSE (CASE WHEN SUM(b.revenue)=0 THEN 0 ELSE (SUM(b.revenue)/SUM(b.minutes))*100 END)-(CASE WHEN SUM(b.cost)=0 THEN 0 ELSE (SUM(b.cost)/SUM(b.minutes))*100 END) END AS marginmin
		      FROM(SELECT {$this->carrier} AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(pdd) AS pdd
		      	   FROM balance
		      	   WHERE date_balance='{$this->fecha}' AND {$this->destino}={$idDestino} AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$this->destino} IS NOT NULL
		      	   GROUP BY {$this->carrier}
		      	   ORDER BY minutes DESC
		      	   LIMIT 5)b";
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
	/**
	*
	*/
	private function define($tipo)
	{
		if($tipo==true)
		{
			$this->carrier=self::ID_CUSTOMER;
			$this->titulo['sql']=self::CUSTOMER;
			$this->titulo['tabla']=self::TITULO_CUSTOMER;
		}
		else
		{
			$this->carrier=self::ID_SUPPLIER;
			$this->titulo['sql']=self::SUPPLIER;
			$this->titulo['tabla']=self::TITULO_SUPPLIER;
		}
	}
}
?>