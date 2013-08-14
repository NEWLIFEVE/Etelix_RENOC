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
                      FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(incomplete_calls+complete_calls)) AS acd, SUM(pdd_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
                           FROM balance
                           WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                           GROUP BY id_carrier_customer
                           ORDER BY margin DESC) x, carrier c
                      WHERE x.margin > 10 AND x.id_carrier_customer = c.id
                      ORDER BY x.margin DESC";
        //Selecciono la suma de todos los totales mayores a 10 dolares de margen
        $sqlClientesTotal="SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                            FROM(
                                SELECT b.id_carrier_customer AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE  WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                                FROM balance b, carrier c, destination_int d
                                WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
                                GROUP BY b.id_carrier_customer
                                ORDER BY MARGEN DESC) x, carrier c
                            WHERE x.MARGEN > 10 AND x.CLIENTE = c.id;";
        //Selecciono la suma de todos los totales
        $sqlClientesTotalCompleto ="SELECT 'TOTAL' AS etiqueta, SUM(complete_calls+incomplete_calls) AS TotalCalls, SUM(complete_calls) AS CompleteCalls, SUM(minutes) AS Minutos, SUM(PDD) AS Pdd, SUM(COST) AS Cost, SUM(REVENUE) AS Revenue, SUM(margin) AS Margin
                                    FROM balance b, carrier c, destination_int d
                                    WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'";
        // Selecciono los totales por proveedoresn de mas de 10 dolares de margen
        $sqlProveedores="SELECT c.name AS Proveedor, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos,x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
                         FROM(
                            SELECT b.id_carrier_supplier AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                            FROM balance b, carrier c, destination_int d
                            WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
                            GROUP BY b.id_carrier_supplier
                            ORDER BY MARGEN DESC) x, carrier c
                         WHERE x.MARGEN > 10 AND x.CLIENTE = c.id
                         ORDER BY x.MARGEN DESC;";
        // Selecciono la suma de totales de los proveedores con mas de 10 dolares de margen
        $sqlProveedoresTotal="SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                              FROM(
                                SELECT b.id_carrier_supplier AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                                FROM balance b, carrier c, destination_int d
                                WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
                                GROUP BY b.id_carrier_supplier
                                ORDER BY MARGEN DESC) x, carrier c
                                WHERE x.MARGEN > 10 AND x.CLIENTE = c.id;";
        // Selecciono la suma de todos los proveedores
        $sqlProveedoresTotalCompleto="SELECT 'TOTAL' AS etiqueta, SUM(complete_calls+incomplete_calls) AS TotalCalls, SUM(complete_calls) AS CompleteCalls, SUM(minutes) AS Minutos, SUM(PDD) AS Pdd, SUM(COST) AS Cost, SUM(REVENUE) AS Revenue, SUM(margin) AS Margin
                                      FROM balance b, carrier c, destination_int d
                                      WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%';";
/*REVISAR DESTINOS****************************************************************************************************************/        
        // selecciono los totales de los destinos de mas de 10 dolares de marger
        $sqlDestinos="SELECT x.CLIENTE AS Destino, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos,x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
                      FROM(
                        SELECT d.name AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                        FROM balance b, destination d, carrier c
                        WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_destination = d.id AND d.name NOT LIKE 'Unk%' AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unk%'
                        GROUP BY d.name
                        ORDER BY MARGEN DESC) x
                      WHERE x.MARGEN > 10
                      ORDER BY x.MARGEN DESC";
        // Selecciono la suma de los totales de los destinos con mas de 10 doleres de margen
        $sqlDestinosTotal="SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                           FROM(
                            SELECT d.name AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                            FROM balance b, destination d, carrier c
                            WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_destination = d.id AND d.name NOT LIKE 'Unk%' AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unk%'
                            GROUP BY d.name
                            ORDER BY MARGEN DESC) x
                           WHERE x.MARGEN > 10";
        // Selecciono los totales de todos los destinos 
        $sqlDestinosTotalCompleto="SELECT 'TOTAL' AS etiqueta, SUM(b.complete_calls+b.incomplete_calls) AS TotalCalls, SUM(b.complete_calls) AS CompleteCalls, SUM(b.minutes) AS Minutos, SUM(b.PDD) AS Pdd, SUM(b.cost) AS Cost, SUM(b.revenue) AS Revenue, SUM(b.margin) AS Margin
                                   FROM balance b, destination d, carrier c
                                   WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_destination = d.id AND d.name NOT LIKE 'Unk%' AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unk%'";
        /* ----------------------- SENTENCIAS SQL - FIN  ------------------------------------ */

        /*********************** GENERACION CODIGO HTML - COMIENZO *************************/

        $email="<div>
                    <h1 style='color:#615E5E; border: 0 none; font:150% Arial,Helvetica,sans-serif; margin: 0; padding-left: 550;margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                        Alto Impacto (+10$) ".$fecha."
                    </h1>
                    <h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                        Por Clientes (Ventas)
                    </h2>
                    <br/>
                    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                        <tr>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                                Client
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
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Margin%
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Position
                            </th>
                        </tr>";

        $clientes=Balance::model()->findAllBySql($sqlClientes);
        if($clientes!=null)
        {
            foreach ($clientes as $key => $cliente)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: left;' class='cliente'>".
                            $cliente->cliente.
                        "</td>
                         <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($cliente->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($cliente->completecalls).
                        "</td>
                         <td style='text-align: left;' class='minutos'>".
                            Yii::app()->format->format_decimal($cliente->minutos).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal(($cliente->completecalls*100)/$cliente->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal(($cliente->minutos/$cliente->completecalls)).
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
                            Yii::app()->format->format_decimal((($cliente->revenue*100)/$cliente->cost)-100)."%
                         </td>
                         <td style='text-align: center;' class='position'>
                            $pos
                         </td>
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
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Position
                    </th>
                    </tr>";
        
        $clientesTotal=Balance::model()->findBySql($sqlClientesTotal);
        if($clientesTotal->etiqueta!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $clientesTotal->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clientesTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotal->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($clientesTotal->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($clientesTotal->completecalls*100)/$clientesTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($clientesTotal->minutos/$clientesTotal->completecalls)).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($clientesTotal->pdd).
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
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal((($clientesTotal->revenue*100)/$clientesTotal->cost)-100)."%
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
        if($clientesTotalCompleto->etiqueta!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $clientesTotalCompleto->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($clientesTotalCompleto->completecalls*100)/$clientesTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($clientesTotalCompleto->minutos/$clientesTotalCompleto->completecalls)).
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
                            Yii::app()->format->format_decimal((($clientesTotalCompleto->revenue*100)/$clientesTotalCompleto->cost)-100)."%
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
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                    <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                    </td>
                    <td style='text-align: right;' class='totalCalls'>".
                        Yii::app()->format->format_decimal(($clientesTotal->totalcalls/$clientesTotalCompleto->totalcalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='completeCalls'>".
                        Yii::app()->format->format_decimal(($clientesTotal->completecalls/$clientesTotalCompleto->completecalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='minutos'>".
                        Yii::app()->format->format_decimal(($clientesTotal->minutos/$clientesTotalCompleto->minutos)*(100))."%
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
            </table>";

        $email.="<h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin: 0; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                    Por Proveedores (Compras)
                 </h2>
                 <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <tr>
                        <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                            Supplier
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
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Margin%
                        </th>
                        <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                            Position
                        </th>
                    </tr>";
        $proveedores=Balance::model()->findAllBySql($sqlProveedores);
        if($proveedores!=null)
        {
            foreach($proveedores as $key => $proveedor)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: left;' class='supplier'>".
                            $proveedor->proveedor.
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($proveedor->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedor->completecalls).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($proveedor->minutos).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal(($proveedor->completecalls*100)/$proveedor->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal(($proveedor->minutos/$proveedor->completecalls)).
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
                            Yii::app()->format->format_decimal((($proveedor->revenue*100)/$proveedor->cost)-100)."%
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
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Supplier
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
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Position
                    </th>
                </tr>";

        $proveedoresTotal=Balance::model()->findBySql($sqlProveedoresTotal);
        if($proveedoresTotal->etiqueta!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $proveedoresTotal->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->completecalls*100)/$proveedoresTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($proveedoresTotal->minutos/$proveedoresTotal->completecalls)).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->pdd).
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
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal((($proveedoresTotal->revenue*100)/$proveedoresTotal->cost)-100)."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='position'>
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
        if($proveedoresTotalCompleto->etiqueta!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $proveedoresTotalCompleto->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalCompleto->completecalls*100)/$proveedoresTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalCompleto->minutos/$proveedoresTotalCompleto->completecalls)).
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
                            Yii::app()->format->format_decimal((($proveedoresTotalCompleto->revenue*100)/$proveedoresTotalCompleto->cost)-100)."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='position'>
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                    <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                    </td>
                    <td style='text-align: right;' class='totalCalls'>".
                        Yii::app()->format->format_decimal(($proveedoresTotal->totalcalls/$proveedoresTotalCompleto->totalcalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='completeCalls'>".
                        Yii::app()->format->format_decimal(($proveedoresTotal->completecalls/$proveedoresTotalCompleto->completecalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='minutos'>".
                        Yii::app()->format->format_decimal(($proveedoresTotal->minutos/$proveedoresTotalCompleto->minutos)*(100))."%
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
                </tr>
            </table>";
        $email.="<h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin: 0; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                    Por Destinos
                 </h2>
                 <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <tr>
                        <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>
                            Destination
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
                            Position
                        </th>
                    </tr>";
        $destinos=Balance::model()->findAllBySql($sqlDestinos);
        if($destinos!=null)
        {
            foreach($destinos as $key => $destino)
            {
                $pos=$key+1;
                $email.=$this->colorDestino($destino->destino);
                $email.="<td style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($destino->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destino->completecalls).
                        "</td>
                         <td style='text-align: left;' class='minutos'>".
                            Yii::app()->format->format_decimal($destino->minutos).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal(($destino->completecalls*100)/$destino->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal(($destino->minutos/$destino->completecalls)).
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
                            Yii::app()->format->format_decimal((($destino->revenue*100)/$destino->cost)-100).
                        "</td>
                         <td style='text-align: left;' class='costmin'>".
                            Yii::app()->format->format_decimal(($destino->cost/$destino->minutos)*(100)).
                        "</td>
                         <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal(($destino->revenue/$destino->minutos)*(100)).
                        "</td>
                         <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal((($destino->revenue/$destino->minutos)*(100))-(($destino->cost/$destino->minutos)*(100))).
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
                        Destination
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
                        Position
                    </th>
                </tr>";
        $destinosTotal=Balance::model()->findBySql($sqlDestinosTotal);
        if($destinosTotal->etiqueta!=null)
        {
             $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $destinosTotal->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinosTotal->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($destinosTotal->completecalls*100)/$destinosTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($destinosTotal->minutos/$destinosTotal->completecalls)).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinosTotal->pdd).
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
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal((($destinosTotal->revenue*100)/$destinosTotal->cost)-100).
                       "</td>
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal(($destinosTotal->cost/$destinosTotal->minutos)*(100)).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal(($destinosTotal->revenue/$destinosTotal->minutos)*(100)).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal((($destinosTotal->revenue/$destinosTotal->minutos)*(100))-(($destinosTotal->cost/$destinosTotal->minutos)*(100))).
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
        if($destinosTotalCompleto->etiqueta!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                                    $destinosTotalCompleto->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($destinosTotalCompleto->completecalls*100)/$destinosTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($destinosTotalCompleto->minutos/$destinosTotalCompleto->completecalls)).
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
                            Yii::app()->format->format_decimal((($destinosTotalCompleto->revenue*100)/$destinosTotalCompleto->cost)-100).
                       "</td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
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
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                    <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                    </td>
                    <td style='text-align: right;' class='totalCalls'>".
                        Yii::app()->format->format_decimal(($destinosTotal->totalcalls/$destinosTotalCompleto->totalcalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='completeCalls'>".
                        Yii::app()->format->format_decimal(($destinosTotal->completecalls/$destinosTotalCompleto->completecalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='minutos'>".
                        Yii::app()->format->format_decimal(($destinosTotal->minutos/$destinosTotalCompleto->minutos)*(100))."%
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
                </tr>
            </table>
        </div>";
        return $email;
    }
    /**
    * @param $fecha fecha para ser consuldada
    */
    public function AltoIMpactoRetail($fecha)
    {
       /************************ SENTENCIAS SQL - COMIENZO *********************************/
       $sqlClientes="SELECT x.CLIENTE AS Cliente, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos,x.PDD AS Pdd,x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
                     FROM(
                        SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                        FROM balance b, carrier c
                        WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'RP %'
                        GROUP BY c.name
                        UNION
                        SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                        FROM balance b, carrier c
                        WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'R-E%'
                        GROUP BY c.name) x
                    WHERE x.MARGEN > 1
                    ORDER BY x.MARGEN DESC;";
        $sqlClientesTotal="SELECT 'TOTAL' AS etiqueta, sum(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                           FROM(
                            SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                            FROM balance b, carrier c
                            WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'RP %'
                            GROUP BY c.name
                            UNION
                            SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                            FROM balance b, carrier c
                            WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'R-E%'
                            GROUP BY c.name) x
                           WHERE x.MARGEN > 1;";
        $sqlClientesTotalCompleto = "SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                                     FROM(
                                        SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
                                        FROM balance b, carrier c
                                        WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'RP %'
                                        GROUP BY c.name
                                        UNION
                                        SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
                                        FROM balance b, carrier c
                                        WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'R-E%'
                                        GROUP BY c.name) x";
        $sqlDestinos="SELECT x.CLIENTE AS destino, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos, x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
                      FROM(
                        SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                        FROM balance b, destination d, carrier c
                        WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'RP%'
                        GROUP BY d.name
                        UNION
                        SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                        FROM balance b, destination d, carrier c
                        WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'R-E%'
                        GROUP BY d.name) x
                      WHERE x.MARGEN > 1
                      ORDER BY x.MARGEN DESC;";
        $sqlDestinosTotal="SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                           FROM(
                            SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                            FROM balance b, destination d, carrier c
                            WHERE b.date_balance = '2013-08-05' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'RP%'
                            GROUP BY d.name
                            UNION
                            SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                            FROM balance b, destination d, carrier c
                            WHERE b.date_balance = '2013-08-05' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'R-E%'
                            GROUP BY d.name) x
                           WHERE x.MARGEN > 1;";
        $sqlDestinosTotalCompleto="SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos,SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                                    FROM(
                                        SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
                                        FROM balance b, destination d, carrier c
                                        WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'RP%'
                                        GROUP BY d.name
                                        UNION
                                        SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
                                        FROM balance b, destination d, carrier c
                                        WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'R-E%'
                                        GROUP BY d.name) x;";
        /*----------------------- SENTENCIAS SQL - FIN  ------------------------------------*/

        /************************ GENERACION CODIGO HTML - COMIENZO *************************/
        $email="<div>
                    <h1 style='color:#615E5E; border: 0 none; font:150% Arial,Helvetica,sans-serif; margin: 0; padding-left: 550;margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                        Alto Impacto RETAIL (+1$)
                    </h1>
                    <h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                        Por Clientes (Ventas)
                    </h2>
                    <br/>
                    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                        <tr>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%;'>
                                Client RP
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
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Margin%
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Position
                            </th>
                        </tr>";


        $clientes=Balance::model()->findAllBySql($sqlClientes);
        if($clientes!=null)
        {
            foreach ($clientes as $key => $cliente)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: left;' class='clienteRp'>".
                            $cliente->cliente.
                        "</td>
                         <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($cliente->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='completecalls'>".
                            Yii::app()->format->format_decimal($cliente->completecalls).
                        "</td>
                         <td style='text-align: left;' class='minutos'>".
                            Yii::app()->format->format_decimal($cliente->minutos).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal(($cliente->completecalls*100)/$cliente->totalcalls).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal(($cliente->minutos/$cliente->completecalls)).
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
                            Yii::app()->format->format_decimal(($cliente->revenue*100)/$cliente->cost)."%
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
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Client RP
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
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Margin%
                    </th>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Position
                    </th>
                </tr>";
        $clientesTotal=Balance::model()->findBySql($sqlClientesTotal);
        if($clientesTotal->etiqueta!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $clientesTotal->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clientesTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotal->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($clientesTotal->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($clientesTotal->completecalls*100)/$clientesTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($clientesTotal->minutos/$clientesTotal->completecalls)).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($clientesTotal->pdd).
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
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal((($clientesTotal->revenue*100)/$clientesTotal->cost)-100)."%
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
        if($clientesTotalCompleto->etiqueta!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $clientesTotalCompleto->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($clientesTotalCompleto->completecalls*100)/$clientesTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($clientesTotalCompleto->minutos/$clientesTotalCompleto->completecalls)).
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
                            Yii::app()->format->format_decimal((($clientesTotalCompleto->revenue*100)/$clientesTotalCompleto->cost)-100)."%
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
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                    <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                    </td>
                    <td style='text-align: right;' class='totalCalls'>".
                        Yii::app()->format->format_decimal(($clientesTotal->totalcalls/$clientesTotalCompleto->totalcalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='completeCalls'>".
                        Yii::app()->format->format_decimal(($clientesTotal->completecalls/$clientesTotalCompleto->completecalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='minutos'>".
                        Yii::app()->format->format_decimal(($clientesTotal->minutos/$clientesTotalCompleto->minutos)*(100))."%
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
            </table>";
        $email.="<h2 style='color:#615E5E; border: 0 none; font:120% Arial,Helvetica,sans-serif; margin: 0; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                    Por Destinos
                </h2>
                <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <tr>
                        <th style='background-color:#615E5E; color:#62C25E; width:40%; height:100%;'>
                            Destination RP
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
                            Position
                        </th>
                    </tr>";
        $destinos=Balance::model()->findAllBySql($sqlDestinos);
        if($destinos!=null)
        {
            foreach($destinos as $key => $destino)
            {
                $pos=$key+1;
                $email.=$this->colorDestino($destino->destino);
                $email.="<td style='text-align: left;' class='destino'>".
                            $destino->destino.
                       "</td>
                        <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destino->totalcalls).
                       "</td>
                        <td style='text-align: left;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destino->completecalls).
                       "</td>
                        <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($destino->minutos).
                       "</td>
                        <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal(($destino->completecalls*100)/$destino->totalcalls).
                       "</td>           
                        <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal(($destino->minutos/$destino->completecalls)).
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
                            Yii::app()->format->format_decimal((($destino->revenue*100)/$destino->cost)-100).
                       "</td>  
                        <td style='text-align: left;' class='costmin'>".
                            Yii::app()->format->format_decimal(($destino->cost/$destino->minutos)*(100)).
                       "</td>
                        <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal(($destino->revenue/$destino->minutos)*(100)).
                       "</td>
                        <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal((($destino->revenue/$destino->minutos)*(100))-(($destino->cost/$destino->minutos)*(100))).
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
        $email.="<tr>
                    <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                        Destination RP
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
                        Position
                    </th>
                </tr>";
        $destinosTotal=Balance::model()->findBySql($sqlDestinosTotal);
        if($destinosTotal->etiqueta!=null)
        {
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $destinosTotal->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($destinosTotal->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($destinosTotal->completecalls*100)/$destinosTotal->totalcalls).
                       "</td>           
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($destinosTotal->minutos/$destinosTotal->completecalls)).
                       "</td>           
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinosTotal->pdd).
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
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal((($destinosTotal->revenue*100)/$destinosTotal->cost)-100).
                       "</td>  
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal(($destinosTotal->cost/$destinosTotal->minutos)*(100)).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal(($destinosTotal->revenue/$destinosTotal->minutos)*(100)).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal((($destinosTotal->revenue/$destinosTotal->minutos)*(100))-(($destinosTotal->cost/$destinosTotal->minutos)*(100))).
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
        $destinosTotalCompleto=Balance::model()->findBySql($sqlDestinosTotalCompleto);
        if($destinosTotalCompleto->etiqueta!=null)
        {
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: center;' class='etiqueta'>".
                            $destinosTotalCompleto->etiqueta.
                       "</td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->completecalls).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->minutos).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal(($destinosTotalCompleto->completecalls*100)/$destinosTotalCompleto->totalcalls).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal(($destinosTotalCompleto->minutos/$destinosTotalCompleto->completecalls)).
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
                            Yii::app()->format->format_decimal((($destinosTotalCompleto->revenue*100)/$destinosTotalCompleto->cost)-100).
                       "</td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>                
                        </td> 
                        </tr>";    
        }
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                    <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                    </td>
                    <td style='text-align: right;' class='totalCalls'>".
                        Yii::app()->format->format_decimal(($destinosTotal->totalcalls/$destinosTotalCompleto->totalcalls)*(100))."%
                    </td>
                    <td style='text-align: right;' class='completeCalls'>".
                        Yii::app()->format->format_decimal(($destinosTotal->completecalls/$destinosTotalCompleto->completecalls)*(100))."%
                    </td>           
                    <td style='text-align: right;' class='minutos'>".
                        Yii::app()->format->format_decimal(($destinosTotal->minutos/$destinosTotalCompleto->minutos)*(100))."%
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
                    </tr>
                </table>
            </div>";


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
        $sql="SELECT operador.name AS Operador, vendedor.name AS Vendedor, customer.Vminutes, customer.Vrevenue, customer.Vmargin, supplier.Cminutes, supplier.Ccost, supplier.Cmargin, (customer.Vrevenue-supplier.Ccost) AS Posicion_neta, (customer.Vmargin+supplier.Cmargin) AS Margen_total
            FROM
                (SELECT id_carrier_customer, SUM(minutes) AS Vminutes, SUM(revenue) AS Vrevenue, SUM(margin) AS Vmargin 
                FROM balance 
                WHERE date_balance = '$fecha' 
                GROUP BY id_carrier_customer) customer,
                (SELECT id_carrier_supplier, SUM(minutes) AS Cminutes, SUM(cost) AS Ccost, SUM(margin) AS Cmargin 
                FROM balance 
                WHERE date_balance = '$fecha' 
                GROUP BY id_carrier_supplier) supplier,
                carrier operador, 
                managers vendedor, 
                carrier_managers cm 
            WHERE customer.id_carrier_customer = supplier.id_carrier_supplier AND operador.id = customer.id_carrier_customer AND cm.id_carrier = customer.id_carrier_customer AND cm.id_managers = vendedor.id
            ORDER BY Posicion_neta DESC";

        $email="<div>
                    <h1 style='color:#615E5E; border: 0 none; font:150% Arial,Helvetica,sans-serif; margin: 0; padding-left: 550;margin-bottom: -22px; background-color: #f8f8f8; vertical-align: baseline; background: url('http://fullredperu.com/themes/mattskitchen/img/line_hor.gif') repeat-x scroll 0 100% transparent;'>
                        Posicion Neta ".$fecha."
                    </h1>
                    <br/>
                    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                        <tr>
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
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Numero
                            </th>
                        </tr>";

        $posicionNeta=Balance::model()->findAllBySql($sql);
        if($posicionNeta!=null)
        {
            foreach($posicionNeta as $key => $operador)
            {
                $pos=$key+1;
                $email.=$this->color($pos);
                $email.="<td style='text-align: center;' class='operador'>".
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
                        <td style='text-align: center;' class='numero'>".
                            $pos.
                        "</td>
                    </tr>";
            }
            $email.="</table>
            </div>";
        }
        return $email;
    }

    public function AltoIMpactoExcel() {
        
    }

    public function AltoIMpactoRetailExcel() {
        
    }

    public function PosicionNetaExcel() {
        
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
}
?>
