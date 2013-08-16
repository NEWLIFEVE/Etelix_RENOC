<?php
/**
*
*/
class reportes extends CApplicationComponent
{
    public $tipo;

    /**
    * Init method for the application component mode.
    */
    public function init() 
    {
        
    }

    /**
    *
    */
    public function AltoImpacto($fecha)
    {
        //$conection = pg_connect("host=192.168.1.239 port=5432 dbname=sori user=postgres password=123");
        /********************************* SENTENCIAS SQL - COMIENZO *********************************/
        //Selecciono los totales por clientes
        $sqlClientes="SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                      FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                         FROM balance
                         WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                         GROUP BY id_carrier_customer
                         ORDER BY margin DESC) x, carrier c
                      WHERE x.margin > 10 AND x.id_carrier_customer = c.id
                      ORDER BY x.margin DESC";
        //Selecciono la suma de todos los totales mayores a 10 dolares de margen
        $sqlClientesTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                            FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                               FROM balance
                               WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                               GROUP BY id_carrier_customer
                               ORDER BY margin DESC) balance
                            WHERE margin>10";
        //Selecciono la suma de todos los totales
        $sqlClientesTotalCompleto ="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                                    FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                         FROM balance
                                         WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                         GROUP BY id_carrier_customer
                                         ORDER BY margin DESC) balance";
        // Selecciono los totales por proveedoresn de mas de 10 dolares de margen
        $sqlProveedores="SELECT c.name AS proveedor, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                          FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                             FROM balance
                             WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                             GROUP BY id_carrier_supplier
                             ORDER BY margin DESC) x, carrier c
                          WHERE x.margin > 10 AND x.id_carrier_supplier = c.id
                          ORDER BY x.margin DESC";
        // Selecciono la suma de totales de los proveedores con mas de 10 dolares de margen
        $sqlProveedoresTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                              FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                 FROM balance
                                 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                 GROUP BY id_carrier_supplier
                                 ORDER BY margin DESC) balance
                              WHERE margin>10";
        // Selecciono la suma de todos los proveedores
        $sqlProveedoresTotalCompleto="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                                      FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                         FROM balance
                                         WHERE date_balance='$fecha'
                                         AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                         GROUP BY id_carrier_supplier
                                         ORDER BY margin DESC) balance";
/*REVISAR DESTINOS****************************************************************************************************************/        
        // selecciono los totales de los destinos de mas de 10 dolares de marger
        $sqlDestinos="SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
                      FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance
                           WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
                           GROUP BY id_destination
                           ORDER BY margin DESC) x, destination d
                      WHERE x.margin > 10 AND x.id_destination = d.id
                      ORDER BY x.margin DESC";
        // Selecciono la suma de los totales de los destinos con mas de 10 doleres de margen
        $sqlDestinosTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                            FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                 FROM balance 
                                 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
                                 GROUP BY id_destination
                                 ORDER BY margin DESC) balance
                            WHERE margin>10";
        // Selecciono los totales de todos los destinos 
        $sqlDestinosTotalCompleto="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                                    FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                         FROM balance 
                                         WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
                                         GROUP BY id_destination
                                         ORDER BY margin DESC) balance";
        /* ----------------------- SENTENCIAS SQL - FIN  ------------------------------------ */

        /*********************** GENERACION CODIGO HTML - COMIENZO *************************/

        $email="<div>
                    <h1 style='color:#615E5E; border: 0 none; font:150% Arial,Helvetica,sans-serif; margin: 0; padding-left: 550;margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                      
                    </h1>
                    <h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                       
                    </h2>
                    <br/>
                    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                        <tr>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Ranking
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                                Cliente
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                TotalCalls
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                CompleteCalls
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Minutes
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                ASR
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                ACD
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                PDD
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Cost
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Revenue
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Margin
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                                Cliente
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Margin%
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Ranking
                            </th>
                        </tr>";

        $clientes=Balance::model()->findAllBySql($sqlClientes);
        if($clientes!=null)
        {
            foreach ($clientes as $key => $cliente)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: center;' class='position'>".
                            $pos.
                        "</td><td style='text-align: left;' class='cliente'>".
                            $cliente->cliente.
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
                         </td><td style='text-align: left;' class='cliente'>".
                            $cliente->cliente.
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($cliente->margin_percentage)."%
                         </td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                         </tr>";
            }
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
               
        $email.="<tr>
                    <td style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Ranking 
                    </td>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Client
                    </td>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        TotalCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        CompleteCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Minutes
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ASR
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ACD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        PDD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cost
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Revenue
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin
                    </th>
                    <td style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cliente
                    </td>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <td style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'> 
                        Ranking
                    </td>
                    </tr>";
        
        $clientesTotal=Balance::model()->findBySql($sqlClientesTotal);
        if($clientesTotal->total_calls!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                        <td style='text-align: center;' class='asr'>".
                            //Yii::app()->format->format_decimal($clientesTotal->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            //Yii::app()->format->format_decimal($clientesTotal->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            //Yii::app()->format->format_decimal($clientesTotal->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($clientesTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($clientesTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($clientesTotal->margin).
                       "</td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'> 
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($clientesTotal->margin_percentage)."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'> 
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $clientesTotalCompleto=Balance::model()->findBySql($sqlClientesTotalCompleto);
        if($clientesTotalCompleto->total_calls!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->margin_percentage)."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        if($clientesTotalCompleto->total_calls!=null)
        {
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
                    <td style='text-align: right;' class='margin'>".
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
              $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>
                     </table>
            <br>";
            }

        $email.="<h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin: 0; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                   
                 </h2>
                 <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <tr>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Ranking
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                            Proveedor
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            TotalCalls
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            CompleteCalls
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Minutes
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ASR
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ACD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            PDD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Cost
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Revenue
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                            Proveedor
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin%
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Ranking
                        </th>
                    </tr>";
        $proveedores=Balance::model()->findAllBySql($sqlProveedores);
        if($proveedores!=null)
        {
            foreach($proveedores as $key => $proveedor)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: center;' class='ranking'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='supplier'>".
                            $proveedor->proveedor.
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
                        <td style='text-align: left;' class='supplier'>".
                            $proveedor->proveedor.
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($proveedor->margin_percentage)."%
                         </td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                    </tr>";
            }
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }

        $email.="<tr>
                    <td style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Ranking
                    </td>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Proveedor
                    </td>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        TotalCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        CompleteCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Minutes
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ASR
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ACD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        PDD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cost
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Revenue
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin
                    </th>
                    <td style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Proveedor
                    </td>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <td style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Ranking
                    </td>
                </tr>";

        $proveedoresTotal=Balance::model()->findBySql($sqlProveedoresTotal);
        if($proveedoresTotal->total_calls!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='ranking'>
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
                        <td style='text-align: center;' class='asr'>".
                            //Yii::app()->format->format_decimal($proveedoresTotal->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            //Yii::app()->format->format_decimal($proveedoresTotal->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            //Yii::app()->format->format_decimal($proveedoresTotal->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->margin).
                       "</td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->margin_percentage)."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $proveedoresTotalCompleto=Balance::model()->findBySql($sqlProveedoresTotalCompleto);
        if($proveedoresTotalCompleto->total_calls!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->margin_percentage)."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        if($proveedoresTotal->total_calls!=null)
        {
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>
                    </table>
                <br>";
          }
        $email.="<h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin: 0; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                 </h2>
                 <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <tr>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Ranking
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>
                            Destino
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            TotalCalls
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            CompleteCalls
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Minutes
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ASR
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ACD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            PDD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Cost
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Revenue
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>
                            Destino
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin%
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Cost/Min
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Rate/Min
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin/Min
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Rankin
                        </th>
                    </tr>";
        $destinos=Balance::model()->findAllBySql($sqlDestinos);
        if($destinos!=null)
        {
            foreach($destinos as $key => $destino)
            {
                $pos=$key+1;
                $email.=$this->colorDestino($destino->destino);
                $email.="<td style='text-align: center;' class='diferencialBancario'>".
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
                         <td style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destino->margin_percentage).
                        "</td>
                         <td style='text-align: left;' class='costmin'>".
                            Yii::app()->format->format_decimal($destino->costmin).
                        "</td>
                         <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destino->ratemin).
                        "</td>
                         <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destino->marginmin).
                        "</td>
                         <td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                    </tr>";
            }
        }
        else
        {
            $email.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.="<tr>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Ranking
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>
                        Destino
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        TotalCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        CompleteCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Minutes
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ASR
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ACD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        PDD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cost
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Revenue
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>
                        Destino
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cost/Min
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Rate/Min
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin/Min
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Rankin
                    </th>
                </tr>";
        $destinosTotal=Balance::model()->findBySql($sqlDestinosTotal);
        if($destinosTotal->total_calls!=null)
        {
             $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                        <td style='text-align: center;' class='asr'>".
                            //Yii::app()->format->format_decimal($destinosTotal->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            //Yii::app()->format->format_decimal($destinosTotal->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            //Yii::app()->format->format_decimal($destinosTotal->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosTotal->margin).
                       "</td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinosTotal->margin_percentage).
                       "</td>
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinosTotal->costmin).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinosTotal->ratemin).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinosTotal->marginmin).
                       "</td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $destinosTotalCompleto=Balance::model()->findBySql($sqlDestinosTotalCompleto);
        if($destinosTotalCompleto->total_calls!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->margin_percentage).
                       "</td>
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->costmin).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->ratemin).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->marginmin).
                       "</td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        if($destinosTotal->total_calls!=null)
        {
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
        </div>";
      }
      else
      {
        $email.="<tr>
                  <td colspan='15'>No se encontraron resultados</td>
                </tr>";
      }
        return $email;
    }
    /**
    * @param $fecha fecha para ser consuldada
    */
    public function AltoIMpactoRetail($fecha)
    {
       /************************ SENTENCIAS SQL - COMIENZO *********************************/
       
        
        
        
        
        
        /*----------------------- SENTENCIAS SQL - FIN  ------------------------------------*/

        /************************ GENERACION CODIGO HTML - COMIENZO *************************/
        $email="<div>";
        $email.=$this->altoImpactoHead("Cliente RP");
        $sqlClientes="SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                      FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance 
                           WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_destination_int IS NOT NULL
                           GROUP BY id_carrier_customer) x, carrier c
                      WHERE x.margin>1 AND x.id_carrier_customer=c.id
                      ORDER BY x.margin DESC";
        $clientes=Balance::model()->findAllBySql($sqlClientes);
        if($clientes!=null)
        {
            foreach ($clientes as $key => $cliente)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: center;' class='position'>".
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
                         <td style='text-align: left;' class='clienteRp'>".
                            $cliente->cliente.
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($cliente->margin_percentage)."%
                         </td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                    </tr>";         
            }
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $sqlClientesTotal="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin
                            FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                 FROM balance 
                                 WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                 GROUP BY id_carrier_customer) x
                            WHERE x.margin>1";
        $clientesTotal=Balance::model()->findBySql($sqlClientesTotal);
        if($clientesTotal->total_calls!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                        <td style='text-align: center;' class='etiqueta'>
                          TOTAL
                        </td>
                        <td style='text-align: center;' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>";  
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $sqlClientesTotalCompleto="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, (SUM(x.complete_calls)*100/SUM(x.total_calls)) AS asr, (SUM(x.minutes)/SUM(x.complete_calls)) AS acd, SUM(x.pdd) AS pdd, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
                                    FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                         FROM balance 
                                         WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                         GROUP BY id_carrier_customer) x";
        $clientesTotalCompleto=Balance::model()->findBySql($sqlClientesTotalCompleto);
        if($clientesTotalCompleto->total_calls!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                        <td style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->margin_percentage)."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                    </tr>"; 
           
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.=$this->altoImpactoFoot();
        if($clientesTotal->total_calls!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
            $email.="<tr>
                        <td colspan='14'>No se encontraron resultados</td>
                     </tr>
                     </table>
            <br>";
        }
        $email.=$this->altoImpactoHeadDestino("Destino RP");
        $sqlDestinos="SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
                      FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance
                           WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
                           GROUP BY id_destination
                           ORDER BY margin DESC) x, destination d
                      WHERE x.margin > 1 AND x.id_destination = d.id
                      ORDER BY x.margin DESC";
        $destinos=Balance::model()->findAllBySql($sqlDestinos);
        if($destinos!=null)
        {
            foreach($destinos as $key => $destino)
            {
                $pos=$key+1;
                $email.=$this->colorDestino($destino->destino);
                $email.="<td style='text-align: center;' class='position'>".
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
                         <td style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destino->margin_percentage).
                        "</td>  
                         <td style='text-align: left;' class='costmin'>".
                            Yii::app()->format->format_decimal($destino->costmin).
                        "</td>
                         <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destino->ratemin).
                        "</td>
                         <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destino->marginmin).
                        "</td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                         "</td>
                     </tr>";
            }
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $sqlDestinosTotal="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                            FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                 FROM balance 
                                 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
                                 GROUP BY id_destination
                                 ORDER BY margin DESC) balance
                            WHERE margin>1";
        $destinosTotal=Balance::model()->findBySql($sqlDestinosTotal);
        if($destinosTotal->total_calls!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                        <td style='text-align: center;' class='etiqueta'>
                          TOTAL
                        </td> 
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
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $sqlDestinosTotalCompleto="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
                                    FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                         FROM balance 
                                         WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
                                         GROUP BY id_destination
                                         ORDER BY margin DESC) balance;";
        $destinosTotalCompleto=Balance::model()->findBySql($sqlDestinosTotalCompleto);
        if($destinosTotalCompleto->total_calls!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
                        <td style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->margin_percentage).
                       "</td>
                        <td style='text-align: center;' class='etiqueta'>                
                        </td> 
                        <td style='text-align: center;' class='etiqueta'>                
                        </td> 
                        <td style='text-align: center;' class='etiqueta'>                
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        </tr>";    
        }
        else
        {
            $email.="<tr>
                        <td colspan='17'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.=$this->altoImpactoFootdDestino();
        if($destinosTotal->total_calls!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
             $email.="<tr>
                        <td colspan='17'>No se encontraron resultados</td>
                     </tr>
                     </table>
                     <br>";
          }
        /*****RPRO*****/
        $sqlClientesRpro="SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
                          FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                               FROM balance 
                               WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_destination_int IS NOT NULL
                               GROUP BY id_carrier_customer) x, carrier c
                          WHERE x.margin>1 AND x.id_carrier_customer=c.id
                          ORDER BY x.margin DESC";
        $clientesRpro=Balance::model()->findAllBySql($sqlClientesRpro);
        if($clientesRpro!=null)
        {
            $email.=$this->altoImpactoHead("Cliente RPRO");
            foreach ($clientesRpro as $key => $clienteRpro)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: center;' class='position'>".
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
                         <td style='text-align: left;' class='clienteRp'>".
                            $clienteRpro->cliente.
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($clienteRpro->margin_percentage)."%
                         </td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                    </tr>";         
            }
            $sqlClientesTotalRpro="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
                                FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                     FROM balance 
                                     WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                     GROUP BY id_carrier_customer) x
                                WHERE x.margin>1";
            $clientesTotalRpro=Balance::model()->findBySql($sqlClientesTotalRpro);
            if($clientesTotalRpro->total_calls!=null)
            {
                $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                            <td style='text-align: center;' class='etiqueta'>
                              TOTAL
                            </td>
                            <td style='text-align: center;' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                        </tr>";  
            }
            $sqlClientesTotalCompletoRpro="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, (SUM(x.complete_calls)*100/SUM(x.total_calls)) AS asr, (SUM(x.minutes)/SUM(x.complete_calls)) AS acd, SUM(x.pdd) AS pdd, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
                                      FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                           FROM balance 
                                           WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                           GROUP BY id_carrier_customer) x";
            $clientesTotalCompletoRpro=Balance::model()->findBySql($sqlClientesTotalCompletoRpro);
            if($clientesTotalCompletoRpro->total_calls!=null)
            {
                $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                            <td style='text-align: center;' class='etiqueta'>
                              Total
                            </td>
                            <td style='text-align: center;' class='margin_percentage'>".
                                Yii::app()->format->format_decimal($clientesTotalCompletoRpro->margin_percentage)."%
                            </td>
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                            </td>
                        </tr>"; 
               
            }
            $email.=$this->altoImpactoFoot();
            if($clientesTotalRpro->total_calls!=null)
            {
                $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
            $email.=$this->altoImpactoHeadDestino("Destino RPRO");
            $sqlDestinosRpro="SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
                          FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                               FROM balance
                               WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
                               GROUP BY id_destination
                               ORDER BY margin DESC) x, destination d
                          WHERE x.margin > 1 AND x.id_destination = d.id
                          ORDER BY x.margin DESC";
            $destinosRpro=Balance::model()->findAllBySql($sqlDestinosRpro);
            if($destinosRpro!=null)
            {
                foreach($destinosRpro as $key => $destinoRpro)
                {
                    $pos=$key+1;
                    $email.=$this->colorDestino($destinoRpro->destino);
                    $email.="<td style='text-align: center;' class='position'>".
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
                             <td style='text-align: left;' class='destino'>".
                                $destinoRpro->destino.
                            "</td>
                             <td style='text-align: left;' class='margin_percentage'>".
                                Yii::app()->format->format_decimal($destinoRpro->margin_percentage).
                            "</td>  
                             <td style='text-align: left;' class='costmin'>".
                                Yii::app()->format->format_decimal($destinoRpro->costmin).
                            "</td>
                             <td style='text-align: left;' class='ratemin'>".
                                Yii::app()->format->format_decimal($destinoRpro->ratemin).
                            "</td>
                             <td style='text-align: left;' class='marginmin'>".
                                Yii::app()->format->format_decimal($destinoRpro->marginmin).
                            "</td>
                             <td style='text-align: center;' class='position'>".
                                $pos.
                             "</td>
                         </tr>";
                }
            }
            $sqlDestinosTotalRpro="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                              FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                   FROM balance 
                                   WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
                                   GROUP BY id_destination
                                   ORDER BY margin DESC) balance
                              WHERE margin>1";
            $destinosTotalRpro=Balance::model()->findBySql($sqlDestinosTotalRpro);
            if($destinosTotalRpro->total_calls!=null)
            {
                $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
                            <td style='text-align: center;' class='etiqueta'>
                              TOTAL
                            </td> 
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
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                        </tr>";
            }
            $sqlDestinosTotalCompletoRpro="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
                                        FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                                           FROM balance 
                                           WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
                                           GROUP BY id_destination
                                           ORDER BY margin DESC) balance;";
            $destinosTotalCompletoRpro=Balance::model()->findBySql($sqlDestinosTotalCompletoRpro);
            if($destinosTotalCompletoRpro->total_calls!=null)
            {
                $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
                            <td style='text-align: center;' class='etiqueta'>
                              Total
                            </td>
                            <td style='text-align: center;' class='margin_percentage'>".
                                Yii::app()->format->format_decimal($destinosTotalCompletoRpro->margin_percentage).
                           "</td>
                            <td style='text-align: center;' class='etiqueta'>                
                            </td> 
                            <td style='text-align: center;' class='etiqueta'>                
                            </td> 
                            <td style='text-align: center;' class='etiqueta'>                
                            </td> 
                            <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                            </td> 
                            </tr>";    
            }
            $email.=$this->altoImpactoFootdDestino();
            if($destinosTotalRpro->total_calls!=null)
            {
                $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
        $email.="</div>";


/*----------------------- GENERACION CODIGO HTML - FIN -----------------------------*/

    return $email;
    }

    /**
    * Encargado de generar el cuerpo del reporte de posicion neta
    * @param $fecha date es la fecha que se necesita el reporte
    * @return un string con el cuerpo del reporte
    */
    public function posicionNeta($fecha)
    {
        $sqlCien="SELECT o.name AS operador, m.name AS vendedor, c.minutes AS vminutes, c.revenue AS vrevenue, c.margin AS vmargin, s.minutes AS cminutes, s.cost AS ccost, s.margin AS cmargin, (c.revenue-s.cost) AS posicion_neta, (c.margin+s.margin) AS Margen_total
                  FROM (SELECT id_carrier_customer, SUM(minutes) AS minutes, SUM(revenue) AS revenue, SUM(margin) AS margin
                        FROM balance
                        WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_customer
                        ORDER BY id_carrier_customer) c,
                       (SELECT id_carrier_supplier, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(margin) AS margin
                        FROM balance
                        WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_supplier
                        ORDER BY id_carrier_supplier) s,
                        carrier o,
                        managers m,
                        carrier_managers cm
                  WHERE c.id_carrier_customer = s.id_carrier_supplier AND c.id_carrier_customer = o.id AND cm.id_carrier = o.id AND cm.id_managers = m.id
                  ORDER BY posicion_neta DESC";

        $email="<div>
                    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                        <tr>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Ranking
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                                Operador
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Vendedor
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Vminutes
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Vrevenue
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Vmargin
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Cminutes
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Ccosto
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Cmargin
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Posicion Neta
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Margen Total
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                                Operador
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Ranking
                            </th>
                        </tr>";
         
//$miarray = array('leon','salamanca','zamora');
//echo count($miarray); // Resultado: 3
        $posicionNeta=Balance::model()->findAllBySql($sqlCien);
        if($posicionNeta!=null)
        { 
            //$conto=count($posicionNeta)/2;
            foreach($posicionNeta as $key => $operador)
            {  
              $pos=$key+1;
                //$pos=($conto-1)-($key+1);
//                $pos=$conto-$menor;
                $email.=$this->color($pos);
                $email.="<td style='text-align: center;' class='numero'>".
                            $pos. 
                        "</td>
                         <td style='text-align: center;' class='operador'>".
                            $operador->operador.
                        "</td>
                         <td style='text-align: center;' class='vendedor'>".
                            $operador->vendedor.
                        "</td>
                         <td style='text-align: center;' class='vminutes'>".
                            Yii::app()->format->format_decimal($operador->vminutes).
                        "</td>
                         <td style='text-align: center;' class='vrevenue'>".
                            Yii::app()->format->format_decimal($operador->vrevenue).
                        "</td>
                         <td style='text-align: center;' class='vmargin'>".
                            Yii::app()->format->format_decimal($operador->vmargin).
                        "</td>
                         <td style='text-align: center;' class='cminutes'>".
                            Yii::app()->format->format_decimal($operador->cminutes).
                        "</td>
                        <td style='text-align: center;' class='ccost'>".
                            Yii::app()->format->format_decimal($operador->ccost).
                        "</td>
                        <td style='text-align: center;' class='cmargin'>".
                            Yii::app()->format->format_decimal($operador->cmargin).
                        "</td>
                        <td style='text-align: center;' class='posicionNeta'>".
                            Yii::app()->format->format_decimal($operador->posicion_neta).
                        "</td>
                        <td style='text-align: center;' class='margenTotal'>".
                            Yii::app()->format->format_decimal($operador->margen_total).
                        "</td>
                         <td style='text-align: center;' class='operador'>".
                            $operador->operador.
                        "</td>
                        <td style='text-align: center;' class='numero'>".
                            $pos.
                        "</td>
                    </tr>";
            }
          }
          else
          {
            $email.="<tr>
                      <td colspan='13'>No se encontraron resultados</td>
                     </tr>";
          }
          $email.="</table>
            </div>";
        return $email;
    }

    /**
    * Metodo encargado de generar el reporte de distribucion comercial
    * @param $fecha date la fecha que se quiere consultar
    */
    public function distComercial($fecha)
    {
      $sql="SELECT m.name AS vendedor, c.name AS operador 
            FROM carrier c, managers m, carrier_managers cm
            WHERE m.id = cm.id_managers AND c.id = cm.id_carrier AND cm.end_date IS NULL AND cm.start_date <= '$fecha'
            ORDER BY m.name ASC";
      $email="<table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                        <tr>
                            <th colspan='2' style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                                Responsable
                            </th>
                            <th></th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Operador
                            </th>
                        </tr>";
      $vendedores=Balance::model()->findAllBySql($sql);
      if($vendedores!=null)
      {
        $nombre=null;
        $numero=1;
        foreach ($vendedores as $key => $vendedor)
        {
          $pos=$key+1;
          $com=$key-1;
          if($key>0)
          {
            if($vendedores[$com]->vendedor==$vendedor->vendedor)
            {
              $nombre="";
              $numero=$numero+1;
            }
            else
            {
              $nombre=$vendedor->vendedor;
              $numero=1;
            }
          }
          else
          {
            $nombre=$vendedor->vendedor;
          }
          
          $email.="<tr>
                  <td></td>
                  <td>".$nombre."</td>
                  <td>".$numero."</td>
                  <td>".$vendedor->operador."</td>
                 </tr>";
        }
        $email.="</table>";
      }
      else
      {
        $email.="<tr>
                  <td colspan='4'>No se encontraron resultados</td>
                </tr>
                </table>";
      }
      return $email;
    }

    /**
    * Metodo encargado de pintar las filas de los reportes
    * @param int $pos es un numero indicando que color debe regresar
    */
    public function color($pos)
    {
        $color=null;
        $j=0;
        for($i=1;$i<=$pos;$i++)
        { 
            if($j>=4)
            {
                $j=1;
            }
            else
            {
                $j=$j+1;
            }
        }
        switch($j)
        {
            case 1:
                $color="<tr style='background-color:#FFC8AE; color:#584E4E;'>";
                break;
            case 2:
                $color="<tr style='background-color:#B3A5CF; color:#584E4E;'>";
                break;
            case 3:
                $color="<tr style='background-color:#AFD699; color:#584E4E;'>";
                break;
            case 4:
                $color="<tr style='background-color:#F8B6C9; color:#584E4E;'>";
                break;
        }
        return $color;
    }
    /**
    * @param $var string a identificar
    * @return string con la fila coloreada
    */
    public function colorDestino($var)
    {
        if(substr_count($var, 'USA') >= 1 || substr_count($var, 'CANADA') >= 1)
        {
            $color="<tr style='background-color:#F3F3F3; color:#584E4E;'>";
        }
        elseif(substr_count($var, 'SPAIN') >= 1 ||
                substr_count($var, 'ROMANIA') >= 1 ||
                substr_count($var, 'BELGIUM') >= 1 ||
                substr_count($var, 'PAKISTAN') >= 1 ||
                substr_count($var, 'ANTIGUA') >= 1 ||
                substr_count($var, 'UGANDA') >= 1 ||
                substr_count($var, 'NETHERLANDS') >= 1 ||
                substr_count($var, 'THAILAND') >= 1 ||
                substr_count($var, 'CHINA') >= 1 ||
                substr_count($var, 'DENMARK') >= 1 ||
                substr_count($var, 'RUSSIA') >= 1 ||
                substr_count($var, 'AUSTRIA') >= 1 ||
                substr_count($var, 'NORWAY') >= 1 ||
                substr_count($var, 'MAURITANIA') >= 1 ||
                substr_count($var, 'FINLAND') >= 1 ||
                substr_count($var, 'UNITED KINGDOM') >= 1 ||
                substr_count($var, 'ITALY') >= 1 ||
                substr_count($var, 'SWITZERLAND ') >= 1 ||
                substr_count($var, 'VIETNAM') >= 1 ||
                substr_count($var, 'SATELLITE') >= 1 ||
                substr_count($var, 'JAPAN ') >= 1 ||
                substr_count($var, 'IRELAND') >= 1 ||
                substr_count($var, 'ISRAEL ') >= 1 ||
                substr_count($var, 'AUSTRALIA') >= 1)
        {
            $color="<tr style='background-color:#8BA0AC; color:#584E4E;'>";
        }
        elseif(substr_count($var, 'PERU') >= 1 ||
                substr_count($var, 'CHILE') >= 1 ||
                substr_count($var, 'ECUADOR') >= 1 ||
                substr_count($var, 'PARAGUAY') >= 1 ||
                substr_count($var, 'BRAZIL') >= 1 ||
                substr_count($var, 'BOLIVIA') >= 1 ||
                substr_count($var, 'ARGENTINA') >= 1 ||
                substr_count($var, 'URUGUAY') >= 1)
        {
            $color="<tr style='background-color:#AED7F3; color:#584E4E;'>";
        }
        elseif(substr_count($var, 'COLOMBIA') >= 1)
        {
            $color="<tr style='background-color:#BEE2C1; color:#584E4E;'>";
        }
        elseif(substr_count($var, 'VENEZUELA') >= 1)
        {
            $color="<tr style='background-color:#F0D0AE; color:#584E4E;'>";
        }
        elseif(substr_count($var, 'MEXICO') >= 1 ||
                substr_count($var, 'PANAMA') >= 1 ||
                substr_count($var, 'CUBA') >= 1 ||
                substr_count($var, 'BARBADOS') >= 1 ||
                substr_count($var, 'ARUBA') >= 1 ||
                substr_count($var, 'DOMINICAN REPUBLIC ') >= 1 ||
                substr_count($var, 'HONDURAS') >= 1 ||
                substr_count($var, 'HAITI') >= 1 ||
                substr_count($var, 'SALVADOR') >= 1)
        {
            $color="<tr style='background-color:#EDF0AE; color:#584E4E;'>";
        }
        else
        {
            $color="<tr>";
        }
        return $color;
    }
    /**
    * Metodo de cabecera de las tablas en los reportes, retorna el inicio de la tabla
    * @param $nombre string nombre del reporte
    * @return string con tabla
    */
    public function altoImpactoHead($nombre)
    {
        return "<table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <tr>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Ranking
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>".
                            $nombre.
                       "</th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            TotalCalls
                        </th> 
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            CompleteCalls
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Minutes
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ASR
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ACD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            PDD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Cost
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Revenue
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>".
                            $nombre.
                       "</th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin%
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Ranking
                        </th>
                    </tr>";
    }
    public function altoImpactoHeadDestino($nombre=null)
    {
        return "<table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <tr>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Ramking
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>".
                            $nombre.
                       "</th>     
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            TotalCalls
                        </th> 
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            CompleteCalls
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Minutes
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ASR
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            ACD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            PDD
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Cost
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Revenue
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>".
                            $nombre.
                       "</th> 
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin%
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Cost/Min
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Rate/Min
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin/Min
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Ramking
                        </th>
                    </tr>";
    }
    /**
    * Metodo que retorna el pie de las tablas de alto impacto
    */
    public function altoImpactoFoot($nombre=null)
    {
        return "<tr>
                    <th>
                    </th>
                    <th>
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        TotalCalls
                    </th> 
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        CompleteCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Minutes
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ASR
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ACD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        PDD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cost
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Revenue
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin
                    </th>
                    <th>
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <th>
                    </th>
                </tr>";
    }
    public function altoImpactoFootdDestino($nombre=null)
    {
        return "<tr>
                    <th>
                    </th>
                    <th>
                    </th>     
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        TotalCalls
                    </th> 
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        CompleteCalls
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Minutes
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ASR
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        ACD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        PDD
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cost
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Revenue
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin
                    </th>
                    <th>
                    </th> 
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Cost/Min
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Rate/Min
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin/Min
                    </th>
                    <th>
                    </th>
                </tr>";
    }
}
?>
