<?php
/**
* @package reportes
*/
class AltoImpacto extends Reportes
{
	/**
	* Genera la tabla de Alto Impacto (+10$)
	* @param $fecha date fecha a consultar
	* @return string con la tabla armada
	*/
	public static function reporte($fecha)
	{
        $cuerpo="<div>
                  <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                  <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Cliente','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //Selecciono los totales por clientes
        $sqlClientes="SELECT c.name AS cliente, x.id_carrier_customer AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                      FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance
                           WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                           GROUP BY id_carrier_customer
                           ORDER BY margin DESC) x, carrier c
                      WHERE x.margin > 10 AND x.id_carrier_customer = c.id
                      ORDER BY x.margin DESC";
        $clientes=Balance::model()->findAllBySql($sqlClientes);
        if($clientes!=null)
        {
            foreach ($clientes as $key => $cliente)
            {
                $pos=$key+1;
                $cuerpo.=self::color($pos);
                $cuerpo.="<td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                        <td style='text-align: left;' class='cliente'>".
                            $cliente->cliente.
                        "</td>
                        <td style='text-align: left;' class='Vendedor'>".
                            CarrierManagers::getManager($cliente->id).
                        "</td>
                         <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($cliente->total_calls).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($cliente->complete_calls).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($cliente->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($cliente->asr).
                        "</td>
                         <td style='text-align: center;' class='acd'>".
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
                         <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($cliente->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($cliente->margin_percentage)."%
                         </td>
                         </td><td style='text-align: left;' class='cliente'>".
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
        //Selecciono la suma de todos los totales mayores a 10 dolares de margen
        $sqlClientesTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                              FROM balance
                              WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                              GROUP BY id_carrier_customer
                              ORDER BY margin DESC) balance
                           WHERE margin>10";     
        
        $clientesTotal=Balance::model()->findBySql($sqlClientesTotal);
        if($clientesTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'> 
                        </td>
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
                        <td style='text-align: center;' class='minutos'>".
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
                        <td style='text-align: center;' class='margin_percentage'>
                        </td>
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
        //Selecciono la suma de todos los totales
        $sqlClientesTotalCompleto ="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                                    FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                         FROM balance
                                         WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                         GROUP BY id_carrier_customer
                                         ORDER BY margin DESC) balance";
        $clientesTotalCompleto=Balance::model()->findBySql($sqlClientesTotalCompleto);
        if($clientesTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
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
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
                                array('','','','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
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
        if($clientesTotalCompleto->total_calls!=null)
        {
        $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                    <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                    </td>
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
                    <td style='text-align: right;' class='minutos'>".
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
                    <td style='text-align: center;' class='margin'>".
                        Yii::app()->format->format_decimal(($clientesTotal->margin/$clientesTotalCompleto->margin)*(100))."%
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
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>
                     </table>
            <br>";
            }

        $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Proveedor','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Proveedor','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        // Selecciono los totales por proveedores con de mas de 10 dolares de margen
        $sqlProveedores="SELECT c.name AS proveedor, x.id_carrier_supplier AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                         FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                              FROM balance
                              WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                              GROUP BY id_carrier_supplier
                              ORDER BY margin DESC) x, carrier c
                         WHERE x.margin > 10 AND x.id_carrier_supplier = c.id
                         ORDER BY x.margin DESC";
        $proveedores=Balance::model()->findAllBySql($sqlProveedores);
        if($proveedores!=null)
        {
            foreach($proveedores as $key => $proveedor)
            {
                $pos=$key+1;
                $cuerpo.=self::color($pos);
                $cuerpo.="<td style='text-align: center;' class='ranking'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='supplier'>".
                            $proveedor->proveedor.
                        "</td>
                        <td style='text-align: left;' class='vendedor'>".
                            CarrierManagers::getManager($proveedor->id).
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($proveedor->total_calls).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedor->complete_calls).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($proveedor->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($proveedor->asr).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($proveedor->acd).
                        "</td>
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($proveedor->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($proveedor->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($proveedor->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($proveedor->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($proveedor->margin_percentage)."%
                         </td>
                         <td style='text-align: left;' class='supplier'>".
                            $proveedor->proveedor.
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
                        <td colspan='13'>No se encontraron resultados</td>
                     </tr>";
        }
        // Selecciono la suma de totales de los proveedores con mas de 10 dolares de margen
        $sqlProveedoresTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                              FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                  FROM balance
                                  WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                  GROUP BY id_carrier_supplier
                                  ORDER BY margin DESC) balance
                              WHERE margin>10";
        $proveedoresTotal=Balance::model()->findBySql($sqlProveedoresTotal);
        if($proveedoresTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='ranking'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='etiqueta'>
                            TOTAL
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->total_calls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->complete_calls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>
                        <td style='text-align: center;' class='acd'>
                        </td>
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>
                        </td>
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
        // Selecciono la suma de todos los proveedores
        $sqlProveedoresTotalCompleto="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                                      FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                           FROM balance
                                           WHERE date_balance='$fecha'
                                           AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                           GROUP BY id_carrier_supplier
                                           ORDER BY margin DESC) balance";
        $proveedoresTotalCompleto=Balance::model()->findBySql($sqlProveedoresTotalCompleto);
        if($proveedoresTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='etiqueta'>
                            Total
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->total_calls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->complete_calls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->margin_percentage)."%
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
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
                                array('','','','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
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
        if($proveedoresTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='totalCalls'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->total_calls/$proveedoresTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->complete_calls/$proveedoresTotalCompleto->complete_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='minutos'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->minutes/$proveedoresTotalCompleto->minutes)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->cost/$proveedoresTotalCompleto->cost)*(100))."%
                        </td>
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->revenue/$proveedoresTotalCompleto->revenue)*(100))."%
                        </td>
                        <td style='text-align: right;' class='margin'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->margin/$proveedoresTotalCompleto->margin)*(100))."%
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
        $cuerpo.=self::cabecera(array('Ranking','Destino','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Destino','Margin%','Cost/Min','Rate/Min','Margin/Min','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        // selecciono los totales de los destinos de mas de 10 dolares de marger
        $sqlDestinos="SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
                      FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance
                           WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
                           GROUP BY id_destination
                           ORDER BY margin DESC) x, destination d
                      WHERE x.margin > 10 AND x.id_destination = d.id
                      ORDER BY x.margin DESC";

        $destinos=Balance::model()->findAllBySql($sqlDestinos);
        if($destinos!=null)
        {
            foreach($destinos as $key => $destino)
            {
                $pos=$key+1;
                $cuerpo.=self::colorDestino($destino->destino);
                $cuerpo.="<td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($destino->total_calls).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destino->complete_calls).
                        "</td>
                         <td style='text-align: left;' class='minutos'>".
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
                         <td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                    </tr>";
            }
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }

        // Selecciono la suma de los totales de los destinos con mas de 10 doleres de margen
        $sqlDestinosTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                           FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                FROM balance 
                                WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
                                GROUP BY id_destination
                                ORDER BY margin DESC) balance
                           WHERE margin>10";

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
                        <td style='text-align: center;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->complete_calls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
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
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        // Selecciono los totales de todos los destinos 
        $sqlDestinosTotalCompleto="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                                   FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                        FROM balance 
                                        WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
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
                        <td style='text-align: center;' class='costmin'>
                        </td>
                        <td style='text-align: center;' class='ratemin'>
                        </td>
                        <td style='text-align: center;' class='marginmin'>
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
                        <td colspan='15'>No se encontraron resultados</td>
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
                    </tbody>
                </table>
            </div>";
        }
        else
        {
            $cuerpo.="<tr>
                      <td colspan='15'>No se encontraron resultados</td>
                    </tr>";
        }
        return $cuerpo;
	}
}
?>