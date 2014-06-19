<?php
/**
* @package reportes
* @version 1.0.1
*/
class Arbol2NProveedor extends Reportes
{
	protected $destino;
	protected $table;
	protected $startDate;
	protected $endingDate;
	protected $carrier;
	protected $idcarrier;
	protected $nameGroup;
	protected $totalStyle;
	protected $headStyle;
        /*summary*/
	protected $summary;
        protected $cost; 
        protected $minutes; 
        protected $costmin; 
        protected $revenue; 
        protected $ratemin;
        
	protected $titulo=array();
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

	function __construct($startDate,$tipo=true,$endingDate,$carrier,$nameGroup, $carriersSummary)
	{
		$this->startDate=$startDate;
		$this->endingDate=$endingDate;
		$this->carrier=$carrier;
		$this->idcarrier=$carrier;
		$this->nameGroup=$nameGroup;
		$this->summary=$carriersSummary;
		$this->totalStyle="border:solid #615E5E 1px;background:#FFC8AE; color:#615E5E;";
		$this->headStyle="background:#615E5E; color:#62C25E;";
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
            ini_set('max_execution_time', 1200);
            ini_set('memory_limit', '512M'); 
            $cuerpo=NULL;
            $sqlamountTotal="SELECT SUM(x.minutes)AS minutes, SUM(x.cost)AS cost, SUM(x.revenue)AS revenue, ((SUM(x.cost)/SUM(x.minutes))*100) AS costmin, ((SUM(x.revenue)/SUM(x.minutes))*100) AS ratemin
                                             FROM(SELECT {$this->destino}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(pdd)/SUM(incomplete_calls+complete_calls)) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                              FROM balance
                                              WHERE date_balance>='$this->startDate' AND date_balance<='$this->endingDate' AND id_carrier_supplier=$this->carrier  AND {$this->destino}<>(SELECT id FROM {$this->table} WHERE name = 'Unknown_Destination') AND {$this->destino} IS NOT NULL
                                              GROUP BY {$this->destino}
                                              ORDER BY margin DESC) x, {$this->table} d
                                             WHERE x.margin<>0 AND x.{$this->destino} = d.id";
           $amountTotal=Balance::model()->findBySql($sqlamountTotal);

           $sqlDestinos="SELECT x.{$this->destino} AS id, d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
                                             FROM(SELECT {$this->destino}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(pdd)/SUM(incomplete_calls+complete_calls)) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                              FROM balance
                                              WHERE date_balance>='$this->startDate' AND date_balance<='$this->endingDate' AND id_carrier_supplier=$this->carrier  AND {$this->destino}<>(SELECT id FROM {$this->table} WHERE name = 'Unknown_Destination') AND {$this->destino} IS NOT NULL
                                              GROUP BY {$this->destino}
                                              ORDER BY margin DESC) x, {$this->table} d
                                             WHERE x.margin<>0 AND x.{$this->destino} = d.id
                                             ORDER BY x.margin DESC";

           $destinos=Balance::model()->findAllBySql($sqlDestinos);
           if($destinos!=NULL)
           {
               $cuerpo="<div>
                         <table>
                            <tr>
                               <td style='background:#4B4B4B;font-weight: bold;text-align: center;color:white;' colspan='7'>
                                   Grupo: {$this->nameGroup} / Periodo: {$this->startDate} - {$this->endingDate}
                               </td>
                            </tr>
                            <tr>
                               <td style='{$this->headStyle}text-align: center;' colspan='2'> Proveedor </td>
                               <td style='{$this->headStyle}'>   Cost     </td>
                               <td style='{$this->headStyle}'>   Minutes  </td>
                               <td style='{$this->headStyle}'>   Cost/Min </td>
                               <td style='{$this->headStyle}'>   Revenue  </td>   
                               <td style='{$this->headStyle}'>   Tarifa   </td>
                            </tr>
                            <tr>
                               <td style='{$this->totalStyle} text-align: center;' colspan='2'> 
                                   ".Carrier::getName($this->idcarrier)."  
                               </td>
                               <td style='{$this->totalStyle}' >
                                      ".Yii::app()->format->format_decimal($amountTotal->cost)."
                               </td>
                               <td style='{$this->totalStyle}' >
                                      ".Yii::app()->format->format_decimal($amountTotal->minutes)."
                               </td>
                               <td style='{$this->totalStyle}' >
                                      ".Yii::app()->format->format_decimal($amountTotal->costmin)."
                               </td>
                               <td style='{$this->totalStyle}'>
                                      ".Yii::app()->format->format_decimal($amountTotal->revenue)."
                               </td>
                               <td style='{$this->totalStyle}'>
                                      ".Yii::app()->format->format_decimal($amountTotal->ratemin)."
                               </td>
                            </tr>";

               foreach ($destinos as $key => $destino)
               {
                       $cuerpo.="<tr>
                                        <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'colspan='2'>".
                                                $destino->destino.
                                   "</td>
                                       <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                                Yii::app()->format->format_decimal($destino->cost).
                                   "</td>
                                        <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                                Yii::app()->format->format_decimal($destino->minutes).
                                   "</td>
                                       <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                                Yii::app()->format->format_decimal($destino->costmin).
                                   "</td>
                                        <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                                Yii::app()->format->format_decimal($destino->revenue).
                                   "</td>
                                        <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                                Yii::app()->format->format_decimal($destino->ratemin).
                                   "</td>
                                </tr>";
                       $cuerpo.=$this->firstSeven($destino->id,'border:solid #615E5E 1px;background:#AED7F3; color:#615E5E;',true);
               }
               $cuerpo.="</table>
                               </div>";
            }
        
        return $cuerpo;
	}

	/**
	 * Arma el html de los siete clientes que generaron trafico en esos destinos
	 * @access public
	 * @static
	 * @param int $idDestino id del destino que se va a consultar
	 * @param string $estilo el estilo que lleva la tabla que se forma
	 * @param boolean $tipo true=clientes, false=proveedores
	 * @return string $cuerpo
	 */
	public function firstSeven($idDestino,$estilo,$tipo=true)
	{
		$this->define($tipo);
                $cuerpo="";
		$sql="SELECT c.name AS {$this->titulo['sql']}, b.total_calls, b.complete_calls, b.minutes, CASE WHEN b.complete_calls=0 THEN 0 WHEN b.total_calls=0 THEN 0 ELSE (b.complete_calls*100/b.total_calls) END AS asr, CASE WHEN b.minutes=0 THEN 0 WHEN b.complete_calls=0 THEN 0 ELSE (b.minutes/b.complete_calls) END AS acd, CASE WHEN b.pdd=0 THEN 0 WHEN b.total_calls=0 THEN 0 ELSE (b.pdd/b.total_calls) END AS pdd, b.cost, b.revenue, b.margin, CASE WHEN b.minutes=0 THEN 0 WHEN b.cost=0 THEN 0 ELSE (b.cost/b.minutes)*100 END AS costmin, CASE WHEN b.minutes=0 THEN 0 WHEN b.revenue=0 THEN 0 ELSE (b.revenue/b.minutes)*100 END AS ratemin, CASE WHEN b.minutes=0 THEN 0 ELSE (CASE WHEN b.revenue=0 THEN 0 ELSE (b.revenue/b.minutes)*100 END)-(CASE WHEN b.cost=0 THEN 0 ELSE (b.cost/b.minutes)*100 END) END AS marginmin
			   FROM(SELECT {$this->carrier} AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(pdd) AS pdd
			   		FROM balance
			   		WHERE date_balance>='$this->startDate' AND date_balance<='$this->endingDate' AND {$this->destino}={$idDestino} AND id_carrier_supplier=$this->idcarrier/*<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')*/  AND {$this->destino} IS NOT NULL
			   		GROUP BY {$this->carrier}
			   		ORDER BY minutes DESC
			   		)b, carrier c
			   WHERE c.id=b.id";
		$varios=Balance::model()->findAllBySql($sql);
		foreach ($varios as $key => $uno)
		{
			$cuerpo.="<tr>
                                    <td style='border:solid #999999 1px;background:#999999;'></td>
                                         <td style='".$estilo."'>".
                                                 $uno->{$this->titulo['sql']}.
                                    "</td>
                                         <td style='".$estilo."'>".
                                                 Yii::app()->format->format_decimal($uno->cost).
                                    "</td>
                                         <td style='".$estilo."'>".
                                                 Yii::app()->format->format_decimal($uno->minutes).
                                    "</td>
                                         <td style='".$estilo."'>".
                                                 Yii::app()->format->format_decimal($uno->costmin).
                                    "</td>
                                         <td style='".$estilo."'>".
                                                 Yii::app()->format->format_decimal($uno->revenue).
                                    "</td>
                                         <td style='".$estilo."'>".
                                                 Yii::app()->format->format_decimal($uno->ratemin).
                                    "</td>
                                  </tr>";
		}
		return $cuerpo;
	}
        /**
         * 
         */
        public function summaryDestination()
        {
            $sql="SELECT x.{$this->destino} AS id, d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
					  FROM(SELECT {$this->destino}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, CASE WHEN SUM(pdd)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(pdd)/SUM(incomplete_calls+complete_calls)) END AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     					   FROM balance
     					   WHERE date_balance>='$this->startDate' AND date_balance<='$this->endingDate' AND id_carrier_supplier IN({$this->summary}) AND {$this->destino}<>(SELECT id FROM {$this->table} WHERE name = 'Unknown_Destination') AND {$this->destino} IS NOT NULL
     					   GROUP BY {$this->destino}
     					   ORDER BY margin DESC) x, {$this->table} d
					  WHERE x.margin<>0 AND x.{$this->destino} = d.id
					  ORDER BY x.margin DESC";
        
             $summary=Balance::model()->findAllBySql($sql);
             $body="<table>
                        <tr>
                           <td style='background:#4B4B4B;font-weight: bold;text-align: center;color:white;' colspan='7'>  Summary destination  </td>
                        </tr>
                        <tr>
                           <td style='{$this->headStyle}text-align: center;' colspan='2'>    Destination  </td>
                           <td style='{$this->headStyle}'>   Cost  </td>
                           <td style='{$this->headStyle}'>   Minutes  </td>
                           <td style='{$this->headStyle}'>   Cost/Min </td>
                           <td style='{$this->headStyle}'>   Revenue  </td>
                           <td style='{$this->headStyle}'>   Tarifa   </td>
                        </tr>";
             foreach ($summary as $key => $destino)
            {
                 $this->cost+=$destino->cost;
                 $this->minutes+=$destino->minutes;
                 $this->costmin+=$destino->costmin;
                 $this->revenue+=$destino->revenue;
                 $this->ratemin+=$destino->ratemin;
                    $body.="<tr>
                                    <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'colspan='2'>".
                                            $destino->destino.
                               "</td>
                                   <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                            Yii::app()->format->format_decimal($destino->cost).
                               "</td>
                                    <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                            Yii::app()->format->format_decimal($destino->minutes).
                               "</td>
                                   <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                            Yii::app()->format->format_decimal($destino->costmin).
                               "</td>
                                    <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                            Yii::app()->format->format_decimal($destino->revenue).
                               "</td>
                                    <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                            Yii::app()->format->format_decimal($destino->ratemin).
                               "</td>
                            </tr>";
            }
            $body.="<tr>
                    <td style='{$this->headStyle}text-align: center;' rowspan='2' colspan='2'>  Totals Destination  </td>
                    <td style='{$this->headStyle}'>   Cost  </td>
                    <td style='{$this->headStyle}'>   Minutes  </td>
                    <td style='{$this->headStyle}'>   Cost/Min </td>
                    <td style='{$this->headStyle}'>   Revenue  </td>
                    <td style='{$this->headStyle}'>   Tarifa   </td>
                  </tr>";
            $body.="<tr>
                            <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                     Yii::app()->format->format_decimal($this->cost).
                        "</td>
                             <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                     Yii::app()->format->format_decimal($this->minutes).
                        "</td>
                            <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                     Yii::app()->format->format_decimal($this->costmin).
                        "</td>
                             <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                     Yii::app()->format->format_decimal($this->revenue).
                        "</td>
                             <td style='border:solid #615E5E 1px;background:#AFD699; color:#615E5E;'>".
                                     Yii::app()->format->format_decimal($this->ratemin).
                        "</td>
                     </tr>
               </table>";
            return $body;
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