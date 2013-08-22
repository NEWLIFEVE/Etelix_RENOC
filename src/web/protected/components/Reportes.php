<?php
/**
* Clase de reportes
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

    public function AltoImpactoVendedor($fecha)
    {
      $variable=AltoImpactoVendedor::Vendedor($fecha);
      return $variable;
    }
    public function Perdidas($fecha)
    {
      $variable=Perdidas::reporte($fecha);
      return $variable;
    }
    /**
    *
    */
    public function AltoImpacto($fecha)
    {
        /*********************** GENERACION CODIGO HTML - COMIENZO *************************/
        $email="<div>
                  <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                  <thead>";
        $email.=self::cabecera(array('Ranking','Cliente','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $email.="</thead>
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
            $max=count($clientes);
            foreach ($clientes as $key => $cliente)
            {
                $pos=self::ranking($key+1,$max);
                $email.=self::color($key+1);
                $email.="<td style='text-align: center;' class='position'>".
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
            $email.="<tr>
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
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
            $email.="<tr>
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
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
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
        $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
              $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>
                     </table>
            <br>";
            }

        $email.="<table>
                 <thead>";
        $email.=self::cabecera(array('Ranking','Proveedor','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Proveedor','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $email.="</thead>
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
            $max=count($proveedores);
            foreach($proveedores as $key => $proveedor)
            {
                $pos=self::ranking($key+1,$max);
                $email.=self::color($key+1);
                $email.="<td style='text-align: center;' class='ranking'>".
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
            $email.="<tr>
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
            $email.="<tr style='background-color:#999999; color:#FFFFFF;'>
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
            $email.="<tr>
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
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
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
            $email.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
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
            $email.="<tr>
                        <td colspan='13'>No se encontraron resultados</td>
                     </tr>
                    </table>
                <br>";
          }

        $email.="<table>
                 <thead>";
        $email.=self::cabecera(array('Ranking','Destino','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Destino','Margin%','Cost/Min','Rate/Min','Margin/Min','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $email.="</thead>
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
            $max=count($destinos);
            foreach($destinos as $key => $destino)
            {
                $pos=self::ranking($key+1,$max);
                $email.=self::colorDestino($destino->destino);
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
            $email.="<tr>
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
            $email.="<tr>
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
            $email.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','',''),
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
                    </tbody>
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
        $email="<div>
                  <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                  <thead>";
        $email.=self::cabecera(array('Ranking','Cliente RP','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente RP','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $email.="</thead>
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
                $email.=self::color($key+1);
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
            $email.="<tr>
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
            $email.="<tr>
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
            $email.="<tr>
                        <td colspan='12'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
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
                        <td colspan='13'>No se encontraron resultados</td>
                     </tr>
                     </table>
            <br>";
        }
        $email.="<table>
                 <thead>";
        $email.=self::cabecera(array('Ranking','Destino RP','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','Destino RP','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $email.="</thead>
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
                $email.=self::colorDestino($destino->destino);
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
            $email.="<tr>
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
            $email.="<tr>
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
            $email.="<tr>
                        <td colspan='17'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','',''),
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
            $email.="<table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <thead>";
            $email.=self::cabecera(array('Ranking','Cliente RPRO','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente RPRO','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
            $email.="</thead>
                 <tbody>";
            $max=count($clientesRpro);
            foreach ($clientesRpro as $key => $clienteRpro)
            {
                $pos=self::ranking($key+1,$max);
                $email.=self::color($key+1);
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
            $email.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','',''),
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
            $email.="<table>
                 <thead>";
        $email.=self::cabecera(array('Ranking','Destino RPRO','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','Destino RPRO','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $email.="</thead>
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
                    $email.=self::colorDestino($destinoRpro->destino);
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
            $email.=self::cabecera(array('','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min','',''),
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
        return $email;
    }

    /**
    * Encargado de generar el cuerpo del reporte de posicion neta
    * @param $fecha date es la fecha que se necesita el reporte
    * @return un string con el cuerpo del reporte
    */
    public function posicionNeta($fecha)
    {
        $sqlCien="SELECT o.name AS operador, cs.id, cs.vminutes, cs.vrevenue, cs.vmargin, cs.cminutes, cs.ccost, cs.cmargin, cs.posicion_neta, cs.margen_total
                    FROM(SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
                         FROM(SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, SUM(margin) AS vmargin, CAST(0 AS double precision) AS cminutes, SUM(cost) AS ccost, CAST(0 AS double precision) AS cmargin
                              FROM balance
                              WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                              GROUP BY id_carrier_customer
                              UNION
                              SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, SUM(revenue) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, SUM(margin) AS cmargin
                              FROM balance
                              WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                              GROUP BY id_carrier_supplier)t
                         GROUP BY id
                         ORDER BY posicion_neta DESC)cs,
                        carrier o
                    WHERE o.id=cs.id
                    ORDER BY cs.posicion_neta DESC";
        $sqlTotal="SELECT SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(posicion_neta) AS posicion_neta, SUM(margen_total) AS margen_total
                       FROM(SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
                            FROM(SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, SUM(margin) AS vmargin, CAST(0 AS double precision) AS cminutes, SUM(cost) AS ccost, CAST(0 AS double precision) AS cmargin
                            FROM balance
                            WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                            GROUP BY id_carrier_customer
                            UNION
                            SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, SUM(revenue) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, SUM(margin) AS cmargin
                            FROM balance
                            WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                            GROUP BY id_carrier_supplier)t
                       GROUP BY id
                       ORDER BY posicion_neta DESC) t";

        $email="<div>
                    <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <thead>";
        $email.=self::cabecera(array('Ranking','Operador','Vendedor','Vminutes','Vrevenue','Vmargin','Cminutes','Ccost','Cmargin','Posicion Neta','Margen Total','Operador','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $email.="<thead>
                 <tbody>";
         
        $posicionNeta=Balance::model()->findAllBySql($sqlCien);
        if($posicionNeta!=null)
        { 
            $max=count($posicionNeta);
            foreach($posicionNeta as $key => $operador)
            {  

                $pos=self::ranking($key+1,$max);
                $email.=self::color($key+1);
                $email.="<td style='text-align: center;' class='ranking'>".
                            $pos. 
                        "</td>
                         <td style='text-align: center;' class='operador'>".
                            $operador->operador.
                        "</td>
                         <td style='text-align: center;' class='vendedor'>".
                            CarrierManagers:: getManager($operador->id).
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
                        <td style='text-align: center;' class='ranking'>".
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
        $email.=self::cabecera(array('Ranking','Operador','Vendedor','Vminutes','Vrevenue','Vmargin','Cminutes','Ccost','Cmargin','Posicion Neta','Margen Total','Operador','Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $Total=Balance::model()->findBySql($sqlTotal);
        if($Total!=null)
        { 
            $email.="<tr>
                      <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='ranking'>
                      </td>
                      <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='operador'>
                      </td>
                      <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vendedor'>
                      TOTAL
                      </td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vminutes'>".
                            Yii::app()->format->format_decimal($Total->vminutes).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vrevenue'>".
                            Yii::app()->format->format_decimal($Total->vrevenue).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vmargin'>".
                            Yii::app()->format->format_decimal($Total->vmargin).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='cminutes'>".
                            Yii::app()->format->format_decimal($Total->cminutes).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='ccost'>".
                            Yii::app()->format->format_decimal($Total->ccost).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='cmargin'>".
                            Yii::app()->format->format_decimal($Total->cmargin).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='posicionNeta'>".
                            Yii::app()->format->format_decimal($Total->posicion_neta).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='margenTotal'>".
                            Yii::app()->format->format_decimal($Total->margen_total).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='operador'>
                         TOTAL
                         </td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $email.="<tr>
                      <td colspan='13'>No se encontraron resultados</td>
                     </tr>";
        }
        $email.="</tbody></table>";
        $email.="</div>";
        return $email;
    }
    /**
    * Metodo encargado de generar el reporte de distribucion comercial
    * @param $fecha date la fecha que se quiere consultar
    */
    public function distComercial($fecha)
    {
        $sql="SELECT m.name AS vendedor, c.name AS operador, m.position
              FROM carrier c, managers m, carrier_managers cm
              WHERE m.id = cm.id_managers AND c.id = cm.id_carrier AND cm.end_date IS NULL AND cm.start_date <= '2013-08-20'
              ORDER BY m.name ASC";
        $email="<table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                    <thead>
                        <tr>
                            <th colspan='2' style='background-color:#615E5E; color:#62C25E; width:15%; height:100%; border: 1px black;'>
                                Responsable
                            </th>
                            <th></th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Operador
                            </th>
                        </tr>
                    </thead>
                    </tbody>";
        $vendedores=Managers::model()->findAllBySql($sql);
        if($vendedores!=null)
        {
            $nombre=null;
            $numero=1;
            $posicion=null;
            foreach ($vendedores as $key => $vendedor)
            {
                $pos=$key+1;
                $com=$key-1;
                $posicion=$vendedor->position;
                if($key>0)
                {
                    if($vendedores[$com]->vendedor==$vendedor->vendedor)
                    {
                        $nombre="";
                        $posicion="";
                        $numero=$numero+1;
                    }
                    else
                    {
                        $nombre=$vendedor->vendedor;
                        $posicion=$vendedor->position;
                        $numero=1;
                    }
                }
                else
                {
                    $nombre=$vendedor->vendedor;
                }
                $email.="<tr>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$posicion."</td>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$nombre."</td>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$numero."</td>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$vendedor->operador."</td>
                        </tr>";
            } 
        }
        else
        {
            $email.="<tr>
                  <td colspan='4'>No se encontraron resultados</td>
                </tr>";
        }
        $email.="</tbody></table>";
        return $email;
    }
    /**
    * Metodo encargado de pintar las filas de los reportes
    * @param int $pos es un numero indicando que color debe regresar
    */
    public static function color($pos)
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
    public static function colorEstilo($pos)
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
                $color="background-color:#FFC8AE; color:#584E4E;";
                break;
            case 2:
                $color="background-color:#B3A5CF; color:#584E4E;";
                break;
            case 3:
                $color="background-color:#AFD699; color:#584E4E;";
                break;
            case 4:
                $color="background-color:#F8B6C9; color:#584E4E;";
                break;
        }
        return $color;
    }
    public static function colorVendedor($var)
    {
        $color=null;
        if(substr_count($var, 'Leandro') >= 1)
        {
            $color="background-color:#fe6500; color:black;";
        }
        elseif(substr_count($var, 'Juan Carlos Lopez Silva') >= 1)
        {
            $color="background-color:#4aabc5; color:black;";
        }
        elseif(substr_count($var, 'Jose Ramon Olivar') >= 1)
        {
            $color="background-color:#333399; color:black;";
        }
        elseif(substr_count($var, 'Juan Carlos Robayo') >= 1)
        {
            $color="background-color:#00ffff; color:black;";
        }
        elseif(substr_count($var, 'Jaime Laguna') >= 1)
        {
            $color="background-color:#ffcc99; color:black;";
        }
        elseif(substr_count($var, 'Carlos Pinango') >= 1)
        {
            $color="background-color:#cc99ff; color:black;";
        }
        elseif(substr_count($var, 'Augusto Cardenas') >= 1)
        {
            $color="background-color:#00ff00; color:black;";
        }
        elseif(substr_count($var, 'Luis Ernesto Barbaran') >= 1)
        {
            $color="background-color:#ff8080; color:black;";
        }
        elseif(substr_count($var, 'Alonso Van Der Biest') >= 1)
        {
            $color="background-color:#c0504d; color:black;";
        }
        elseif(substr_count($var, 'Soiret Solarte') >= 1)
        {
            $color="background-color:#ff9900; color:black;";
        }
        elseif(substr_count($var, 'Ernesto Da Rocha') >= 1)
        {
            $color="background-color:#c0c0c0; color:black;";
        }
        elseif(substr_count($var, 'Diana Mirakyan') >= 1)
        {
            $color="background-color:#00b0f0; color:black;";
        }
        
        return $color;
    }
    
     function mitad($pos, $posicionNeta) {
        $mitad = ($posicionNeta / 2) + 1;
        if ($pos < $mitad) {
            return $pos;
        } else {
            $diferencia = $pos - $mitad;
            $pos = ($mitad - $diferencia) - 1;
            return "-" . $pos;
        }
    }
    /**
    * @param $var string a identificar
    * @return string con la fila coloreada
    */
    public static function colorDestino($var)
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
    * Se encarga de crear una fila con los datos pasados
    * @param $etiquetas array lista de etiquetas para lacabeceras
    * @param $estilos string con los estilos para la fila
    * @return string con la fila construida
    */
    public static function cabecera($etiquetas,$estilos)
    {
        if(count($etiquetas)>1)
        {
            $cabecera="<tr>";
            if(count($estilos)>1)
            {
                foreach($etiquetas as $key => $value)
                {
                    $cabecera.="<th style='".$estilos[$key]."'>".$value."</th>";
                }
            }
            else
            {
                foreach ($etiquetas as $key => $value)
                {
                    $cabecera.="<th style='".$estilos."'>".$value."</th>";
                }
            }
            $cabecera.="</tr>";
        }
        return $cabecera;
    }

    /**
    * Metodo encargado de realizar los rankings
    * @param $posicion int valor a rankear
    * @param $max int valor a dividir
    * @return $valor int
    */
    public static function ranking($pos,$max)
    {
        if($max>10)
        {
            $mitad=($max/2)+1;
            if($pos<$mitad)
            {
                return $pos;
            }
            else
            {
                $diferencia=$pos-$mitad;
                $valor=($mitad-$diferencia)-1;
                return "-".$valor;
            }
        }
        else
        {
            return $pos;
        }
    }
}
?>
