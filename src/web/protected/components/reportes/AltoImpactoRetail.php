<?php
/**
* @package reportes
*/
class AltoImpactoRetail extends Reportes
{
	/**
	* @param $fecha date fecha a ser consultada
	* @return string cuerpo de la tabla del reporte
	*/
	public static function reporte($fecha)
	{
		$cuerpo="<div>
                  <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                  <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Cliente RP','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente RP','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        /*Total por cliente con mas de 1 dolar de margen*/
        $sqlClientes="SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                      FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance 
                           WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_destination_int IS NOT NULL
                           GROUP BY id_carrier_customer) x, carrier c
                      WHERE x.margin>1 AND x.id_carrier_customer=c.id
                      ORDER BY x.margin DESC";
        $clientes=Balance::model()->findAllBySql($sqlClientes);

        if($clientes!=null)
        {
            $max=count($clientes);
            foreach ($clientes as $key => $cliente)
            {
                $pos=self::ranking($key+1,$max);
                $cuerpo.=self::color($key+1);
                $cuerpo.="<td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='clienteRp'>".
                            $cliente->cliente.
                        "</td>
                         <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($cliente->total_calls).
                        "</td>
                         <td style='text-align: left;' class='completecalls'>".
                            Yii::app()->format->format_decimal($cliente->complete_calls).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($cliente->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($cliente->asr).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($cliente->acd).
                        "</td>
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($cliente->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($cliente->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($cliente->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($cliente->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($cliente->margin_percentage)."%
                         </td>
                         <td style='text-align: left;' class='clienteRp'>".
                            $cliente->cliente.
                        "</td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                    </tr>";         
            }
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        /*Suma de totales por cliente con mas de 1 dolar de margen*/
        $sqlClientesTotal="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin
                           FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                FROM balance 
                                WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                GROUP BY id_carrier_customer) x
                           WHERE x.margin>1";
        $clientesTotal=Balance::model()->findBySql($sqlClientesTotal);
        if($clientesTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='etiqueta'>
                          TOTAL
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clientesTotal->total_calls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotal->complete_calls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($clientesTotal->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>
                        <td style='text-align: center;' class='acd'>
                        </td>
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($clientesTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($clientesTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($clientesTotal->margin).
                       "</td>
                        <td style='text-align: center;' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='etiqueta'>
                          TOTAL
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>";  
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        /*Suma de totales por cliente en general*/
        $sqlClientesTotalCompleto="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, (SUM(x.complete_calls)*100/SUM(x.total_calls)) AS asr, (SUM(x.minutes)/SUM(x.complete_calls)) AS acd, SUM(x.pdd)/SUM(x.total_calls) AS pdd, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
                                   FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                        FROM balance 
                                        WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                        GROUP BY id_carrier_customer) x";
        $clientesTotalCompleto=Balance::model()->findBySql($sqlClientesTotalCompleto);
        if($clientesTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->total_calls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->complete_calls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->margin_percentage)."%
                        </td>
                        <td style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>"; 
           
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
                                array('','','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    '',
                                    ''));
        if($clientesTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='totalCalls'>".
                            Yii::app()->format->format_decimal(($clientesTotal->total_calls/$clientesTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($clientesTotal->complete_calls/$clientesTotalCompleto->complete_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='minutes'>".
                            Yii::app()->format->format_decimal(($clientesTotal->minutes/$clientesTotalCompleto->minutes)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($clientesTotal->cost/$clientesTotalCompleto->cost)*(100))."%
                        </td>
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($clientesTotal->revenue/$clientesTotalCompleto->revenue)*(100))."%
                        </td>
                        <td style='text-align: right;' class='margin'>".
                            Yii::app()->format->format_decimal(($clientesTotal->margin/$clientesTotalCompleto->margin)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>
                </table>
                <br>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='13'>No se encontraron resultados</td>
                     </tr>
                     </table>
            <br>";
        }
        $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Destino RP','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','Destino RP','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        /*Total por destino con mas de 1 dolar de margen*/
        $sqlDestinos="SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
                      FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance
                           WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
                           GROUP BY id_destination
                           ORDER BY margin DESC) x, destination d
                      WHERE x.margin > 1 AND x.id_destination = d.id
                      ORDER BY x.margin DESC";

        $destinos=Balance::model()->findAllBySql($sqlDestinos);
        if($destinos!=null)
        {
            $max=count($destinos);
            foreach($destinos as $key => $destino)
            {
                $pos=self::ranking($key+1,$max);
                $cuerpo.=self::colorDestino($destino->destino);
                $cuerpo.="<td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destino->total_calls).
                        "</td>
                         <td style='text-align: left;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destino->complete_calls).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($destino->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($destino->asr).
                        "</td>           
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($destino->acd).
                        "</td>           
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($destino->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($destino->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($destino->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($destino->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destino->margin_percentage).
                        "</td>  
                         <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal($destino->costmin).
                        "</td>
                         <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destino->ratemin).
                        "</td>
                         <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destino->marginmin).
                        "</td>
                         <td style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                         "</td>
                     </tr>";
            }
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        /*Suma de totales por destino con mas de 1 dolar de margen*/
        $sqlDestinosTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                           FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                FROM balance 
                                WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
                                GROUP BY id_destination
                                ORDER BY margin DESC) balance
                           WHERE margin>1";

        $destinosTotal=Balance::model()->findBySql($sqlDestinosTotal);
        if($destinosTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        <td style='text-align: center;' class='etiqueta'>
                          TOTAL
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->total_calls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->complete_calls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($destinosTotal->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>           
                        <td style='text-align: center;' class='acd'>
                        </td>           
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosTotal->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>
                        </td>  
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinosTotal->costmin).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinosTotal->ratemin).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinosTotal->marginmin).
                       "</td>
                        <td style='text-align: center;' class='etiqueta'>
                          TOTAL
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        /*Suma de totales por destino en general*/
        $sqlDestinosTotalCompleto="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                                   FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                        FROM balance 
                                        WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
                                        GROUP BY id_destination
                                        ORDER BY margin DESC) balance";
        $destinosTotalCompleto=Balance::model()->findBySql($sqlDestinosTotalCompleto);
        if($destinosTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        <td style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->total_calls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->complete_calls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->margin_percentage).
                       "</td>
                        <td style='text-align: center;' class='etiqueta'>                
                        </td> 
                        <td style='text-align: center;' class='etiqueta'>                
                        </td> 
                        <td style='text-align: center;' class='etiqueta'>                
                        </td>
                        <td style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        </tr>";    
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='17'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','',''),
                                array('','','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    '',
                                    ''));
        if($destinosTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        <td style='text-align: right;' class='totalCalls'>".
                            Yii::app()->format->format_decimal(($destinosTotal->total_calls/$destinosTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($destinosTotal->complete_calls/$destinosTotalCompleto->complete_calls)*(100))."%
                        </td>           
                        <td style='text-align: right;' class='minutes'>".
                            Yii::app()->format->format_decimal(($destinosTotal->minutes/$destinosTotalCompleto->minutes)*(100))."%
                        </td>           
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>           
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>           
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>    
                        </td>           
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($destinosTotal->cost/$destinosTotalCompleto->cost)*(100))."%
                        </td>           
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($destinosTotal->revenue/$destinosTotalCompleto->revenue)*(100))."%
                        </td>           
                        <td style='text-align: right;' class='margin'>".
                            Yii::app()->format->format_decimal(($destinosTotal->margin/$destinosTotalCompleto->margin)*(100))."%
                        </td>  
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>          
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>    
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>   
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>   
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>   
                        </td> 
                        </tr>
                    </table>
                    <br>";
          }
          else
          {
             $cuerpo.="<tr>
                        <td colspan='17'>No se encontraron resultados</td>
                     </tr>
                     </table>
                     <br>";
          }
        /*****RPRO*****/
        /*Totales por cliente con mas de 1 dollar de margen RPRO*/
        $sqlClientesRpro="SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                          FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                               FROM balance 
                               WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_destination_int IS NOT NULL
                               GROUP BY id_carrier_customer) x, carrier c
                          WHERE x.margin>1 AND x.id_carrier_customer=c.id
                          ORDER BY x.margin DESC";
        $clientesRpro=Balance::model()->findAllBySql($sqlClientesRpro);
        if($clientesRpro!=null)
        {
            $cuerpo.="<table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <thead>";
            $cuerpo.=self::cabecera(array('Ranking','Cliente RPRO','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente RPRO','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
            $cuerpo.="</thead>
                 <tbody>";
            $max=count($clientesRpro);
            foreach ($clientesRpro as $key => $clienteRpro)
            {
                $pos=self::ranking($key+1,$max);
                $cuerpo.=self::color($key+1);
                $cuerpo.="<td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='clienteRp'>".
                            $clienteRpro->cliente.
                        "</td>
                         <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clienteRpro->total_calls).
                        "</td>
                         <td style='text-align: left;' class='completecalls'>".
                            Yii::app()->format->format_decimal($clienteRpro->complete_calls).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($clienteRpro->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($clienteRpro->asr).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($clienteRpro->acd).
                        "</td>
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($clienteRpro->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($clienteRpro->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($clienteRpro->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($clienteRpro->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($clienteRpro->margin_percentage)."%
                         </td>
                         <td style='text-align: left;' class='clienteRp'>".
                            $clienteRpro->cliente.
                        "</td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                    </tr>";         
            }
            /* suma de Totales por cliente con mas de 1 dollar de margen RPRO*/
            $sqlClientesTotalRpro="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
                                   FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                        FROM balance 
                                        WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                        GROUP BY id_carrier_customer) x
                                   WHERE x.margin>1";
            $clientesTotalRpro=Balance::model()->findBySql($sqlClientesTotalRpro);
            if($clientesTotalRpro->total_calls!=null)
            {
                $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: center;' class='etiqueta'>
                              TOTAL
                            </td>
                            <td style='text-align: center;' class='totalCalls'>".
                                Yii::app()->format->format_decimal($clientesTotalRpro->total_calls).
                           "</td>
                            <td style='text-align: center;' class='completeCalls'>".
                                Yii::app()->format->format_decimal($clientesTotalRpro->complete_calls).
                           "</td>
                            <td style='text-align: center;' class='minutes'>".
                                Yii::app()->format->format_decimal($clientesTotalRpro->minutes).
                           "</td>
                            <td style='text-align: center;' class='asr'>
                            </td>
                            <td style='text-align: center;' class='acd'>
                            </td>
                            <td style='text-align: center;' class='pdd'>
                            </td>
                            <td style='text-align: center;' class='cost'>".
                                Yii::app()->format->format_decimal($clientesTotalRpro->cost).
                           "</td>
                            <td style='text-align: center;' class='revenue'>".
                                Yii::app()->format->format_decimal($clientesTotalRpro->revenue).
                           "</td>
                            <td style='text-align: center;' class='margin'>".
                                Yii::app()->format->format_decimal($clientesTotalRpro->margin).
                           "</td>
                            <td style='text-align: center;' class='vacio'>
                            </td>
                            <td style='text-align: center;' class='etiqueta'>
                              TOTAL
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                        </tr>";  
            }
            /* suma de totales Totales por cliente  RPRO*/
            $sqlClientesTotalCompletoRpro="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, (SUM(x.complete_calls)*100/SUM(x.total_calls)) AS asr, (SUM(x.minutes)/SUM(x.complete_calls)) AS acd, SUM(x.pdd)/SUM(x.total_calls) AS pdd, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
                                           FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                                FROM balance 
                                                WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                                GROUP BY id_carrier_customer) x";
            $clientesTotalCompletoRpro=Balance::model()->findBySql($sqlClientesTotalCompletoRpro);
            if($clientesTotalCompletoRpro->total_calls!=null)
            {
                $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: center;' class='etiqueta'>
                              Total
                            </td>
                            <td style='text-align: center;' class='totalCalls'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->total_calls).
                           "</td>
                            <td style='text-align: center;' class='completeCalls'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->complete_calls).
                           "</td>
                            <td style='text-align: center;' class='minutes'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->minutes).
                           "</td>
                            <td style='text-align: center;' class='asr'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->asr).
                           "</td>
                            <td style='text-align: center;' class='acd'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->acd).
                           "</td>
                            <td style='text-align: center;' class='pdd'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->pdd).
                           "</td>
                            <td style='text-align: center;' class='cost'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->cost).
                           "</td>
                            <td style='text-align: center;' class='revenue'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->revenue).
                           "</td>
                            <td style='text-align: center;' class='margin'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->margin).
                           "</td>
                            <td style='text-align: center;' class='margin_percentage'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->margin_percentage)."%
                            </td>
                            <td style='text-align: center;' class='etiqueta'>
                              Total
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                        </tr>"; 
               
            }
            $cuerpo.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
                                array('','','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    '',
                                    ''));
            if($clientesTotalRpro->total_calls!=null)
            {
                $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: right;' class='totalCalls'>".
                                Yii::app()->format->format_decimal(($clientesTotalRpro->total_calls/$clientesTotalCompletoRpro->total_calls)*(100))."%
                            </td>
                            <td style='text-align: right;' class='completeCalls'>".
                                Yii::app()->format->format_decimal(($clientesTotalRpro->complete_calls/$clientesTotalCompletoRpro->complete_calls)*(100))."%
                            </td>
                            <td style='text-align: right;' class='minutes'>".
                                Yii::app()->format->format_decimal(($clientesTotalRpro->minutes/$clientesTotalCompletoRpro->minutes)*(100))."%
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: right;' class='cost'>".
                                Yii::app()->format->format_decimal(($clientesTotalRpro->cost/$clientesTotalCompletoRpro->cost)*(100))."%
                            </td>
                            <td style='text-align: right;' class='revenue'>".
                                Yii::app()->format->format_decimal(($clientesTotalRpro->revenue/$clientesTotalCompletoRpro->revenue)*(100))."%
                            </td>
                            <td style='text-align: right;' class='margin'>".
                                Yii::app()->format->format_decimal(($clientesTotalRpro->margin/$clientesTotalCompletoRpro->margin)*(100))."%
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                        </tr>
                    </table>
                    <br>";
            }
            $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Destino RPRO','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','Destino RPRO','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
            /*Totales por destino con mas de 1 dollar de margen RPRO*/
            $sqlDestinosRpro="SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
                              FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                   FROM balance
                                   WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
                                   GROUP BY id_destination
                                   ORDER BY margin DESC) x, destination d
                              WHERE x.margin > 1 AND x.id_destination = d.id
                              ORDER BY x.margin DESC";
            $destinosRpro=Balance::model()->findAllBySql($sqlDestinosRpro);
            if($destinosRpro!=null)
            {
                $max=count($destinosRpro);
                foreach($destinosRpro as $key => $destinoRpro)
                {
                    $pos=self::ranking($key+1,$max);
                    $cuerpo.=self::colorDestino($destinoRpro->destino);
                    $cuerpo.="<td style='text-align: center;' class='position'>".
                                $pos.
                            "</td>
                             <td style='text-align: left;' class='destino'>".
                                $destinoRpro->destino.
                            "</td>
                             <td style='text-align: left;' class='totalCalls'>".
                                Yii::app()->format->format_decimal($destinoRpro->total_calls).
                            "</td>
                             <td style='text-align: left;' class='completecalls'>".
                                Yii::app()->format->format_decimal($destinoRpro->complete_calls).
                            "</td>
                             <td style='text-align: left;' class='minutes'>".
                                Yii::app()->format->format_decimal($destinoRpro->minutes).
                            "</td>
                             <td style='text-align: left;' class='asr'>".
                                Yii::app()->format->format_decimal($destinoRpro->asr).
                            "</td>           
                             <td style='text-align: left;' class='acd'>".
                                Yii::app()->format->format_decimal($destinoRpro->acd).
                            "</td>           
                             <td style='text-align: left;' class='pdd'>".
                                Yii::app()->format->format_decimal($destinoRpro->pdd).
                            "</td>
                             <td style='text-align: left;' class='cost'>".
                                Yii::app()->format->format_decimal($destinoRpro->cost).
                            "</td>
                             <td style='text-align: left;' class='revenue'>".
                                Yii::app()->format->format_decimal($destinoRpro->revenue).
                            "</td>
                             <td style='text-align: left;' class='margin'>".
                                Yii::app()->format->format_decimal($destinoRpro->margin).
                            "</td>
                             <td style='text-align: left;' class='margin_percentage'>".
                                Yii::app()->format->format_decimal($destinoRpro->margin_percentage).
                            "</td>  
                             <td style='text-align: center;' class='costmin'>".
                                Yii::app()->format->format_decimal($destinoRpro->costmin).
                            "</td>
                             <td style='text-align: left;' class='ratemin'>".
                                Yii::app()->format->format_decimal($destinoRpro->ratemin).
                            "</td>
                             <td style='text-align: left;' class='marginmin'>".
                                Yii::app()->format->format_decimal($destinoRpro->marginmin).
                            "</td>
                             <td style='text-align: left;' class='destino'>".
                                $destinoRpro->destino.
                            "</td>
                             <td style='text-align: center;' class='position'>".
                                $pos.
                             "</td>
                         </tr>";
                }
            }
            /*Suma de totales por destino con mas de 1 dolar de margen RPRO*/
            $sqlDestinosTotalRpro="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                                   FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                        FROM balance 
                                        WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
                                        GROUP BY id_destination
                                        ORDER BY margin DESC) balance
                                   WHERE margin>1";
            $destinosTotalRpro=Balance::model()->findBySql($sqlDestinosTotalRpro);
            if($destinosTotalRpro->total_calls!=null)
            {
                $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                            <td style='text-align: center;' class='etiqueta'>
                              TOTAL
                            </td>
                            <td style='text-align: center;' class='totalCalls'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->total_calls).
                           "</td>
                            <td style='text-align: center;' class='completeCalls'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->complete_calls).
                           "</td>
                            <td style='text-align: center;' class='minutes'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->minutes).
                           "</td>
                            <td style='text-align: center;' class='asr'>
                            </td>           
                            <td style='text-align: center;' class='acd'>
                            </td>           
                            <td style='text-align: center;' class='pdd'>
                            </td>
                            <td style='text-align: center;' class='cost'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->cost).
                           "</td>
                            <td style='text-align: center;' class='revenue'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->revenue).
                           "</td>
                            <td style='text-align: center;' class='margin'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->margin).
                           "</td>
                            <td style='text-align: center;' class='margin_percentage'>
                            </td>  
                            <td style='text-align: center;' class='costmin'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->costmin).
                           "</td>
                            <td style='text-align: center;' class='ratemin'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->ratemin).
                           "</td>
                            <td style='text-align: center;' class='marginmin'>".
                                Yii::app()->format->format_decimal($destinosTotalRpro->marginmin).
                           "</td>
                           <td style='text-align: center;' class='etiqueta'>
                              TOTAL
                            </td> 
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                        </tr>";
            }
            /*Suma de totales por destino en general RPRO*/
            $sqlDestinosTotalCompletoRpro="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                                           FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                                FROM balance 
                                                WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
                                                GROUP BY id_destination
                                                ORDER BY margin DESC) balance";
            $destinosTotalCompletoRpro=Balance::model()->findBySql($sqlDestinosTotalCompletoRpro);
            if($destinosTotalCompletoRpro->total_calls!=null)
            {
                $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                            <td style='text-align: center;' class='etiqueta'>
                              Total
                            </td>
                            <td style='text-align: center;' class='totalCalls'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->total_calls).
                           "</td>
                            <td style='text-align: center;' class='completeCalls'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->complete_calls).
                           "</td>
                            <td style='text-align: center;' class='minutes'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->minutes).
                           "</td>
                            <td style='text-align: center;' class='asr'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->asr).
                           "</td>
                            <td style='text-align: center;' class='acd'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->acd).
                           "</td>
                            <td style='text-align: center;' class='pdd'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->pdd).
                           "</td>
                            <td style='text-align: center;' class='cost'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->cost).
                           "</td>
                            <td style='text-align: center;' class='revenue'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->revenue).
                           "</td>
                            <td style='text-align: center;' class='margin'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->margin).
                           "</td>
                            <td style='text-align: center;' class='margin_percentage'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->margin_percentage).
                           "</td>
                            <td style='text-align: center;' class='etiqueta'>                
                            </td> 
                            <td style='text-align: center;' class='etiqueta'>                
                            </td> 
                            <td style='text-align: center;' class='etiqueta'>                
                            </td>
                            <td style='text-align: center;' class='etiqueta'>
                              Total
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                            </tr>";    
            }
            $cuerpo.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','',''),
                                array('','','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    '',
                                    ''));
            if($destinosTotalRpro->total_calls!=null)
            {
                $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                            <td style='text-align: right;' class='totalCalls'>".
                                Yii::app()->format->format_decimal(($destinosTotalRpro->total_calls/$destinosTotalCompletoRpro->total_calls)*(100))."%
                            </td>
                            <td style='text-align: right;' class='completeCalls'>".
                                Yii::app()->format->format_decimal(($destinosTotalRpro->complete_calls/$destinosTotalCompletoRpro->complete_calls)*(100))."%
                            </td>           
                            <td style='text-align: right;' class='minutes'>".
                                Yii::app()->format->format_decimal(($destinosTotalRpro->minutes/$destinosTotalCompletoRpro->minutes)*(100))."%
                            </td>           
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>           
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>           
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>    
                            </td>           
                            <td style='text-align: right;' class='cost'>".
                                Yii::app()->format->format_decimal(($destinosTotalRpro->cost/$destinosTotalCompletoRpro->cost)*(100))."%
                            </td>           
                            <td style='text-align: right;' class='revenue'>".
                                Yii::app()->format->format_decimal(($destinosTotalRpro->revenue/$destinosTotalCompletoRpro->revenue)*(100))."%
                            </td>           
                            <td style='text-align: right;' class='margin'>".
                                Yii::app()->format->format_decimal(($destinosTotalRpro->margin/$destinosTotalCompletoRpro->margin)*(100))."%
                            </td>  
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>          
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>    
                            </td> 
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>   
                            </td> 
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>   
                            </td> 
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>   
                            </td> 
                            </tr>
                        </table>
                        <br>";
            }
        }
        $cuerpo.="</div>";
        return $cuerpo;
	}
}
?>