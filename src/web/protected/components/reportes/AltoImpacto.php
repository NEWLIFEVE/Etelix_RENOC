<?php
/**
* @package reportes
*/
class AltoImpacto extends Reportes
{
	/**
	* Genera la tabla de Alto Impacto (+10$)
	* @param date $start fecha inicio a consultar
    * @param date $ending fecha fin a consultar
	* @return string con la tabla armada
	*/
	public static function reporte($start,$end=null)
	{
        //verifico las fechas
        $array=self::valDates($start,$end);
        $startDateTemp=$startDate=$array['startDate'];
        $endingDateTemp=$endingDate=$array['endingDate'];
        $arrayStartTemp=null;
        $objetos=array();
        $index=0;
        while (self::isLower($startDateTemp,$endingDate))
        {
            $arrayStartTemp=explode('-',$startDateTemp);
            $endingDateTemp=self::maxDate($arrayStartTemp[0]."-".$arrayStartTemp[1]."-".self::howManyDays($startDateTemp),$endingDate);
            //El titulo que va a llevar la seccion
            $objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            /***/
            //Guardo los datos de los clientes con mas de 10 dolares de ganancia
            $objetos[$index]['customersWithMoreThanTenDollars']=self::getCarriers($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los clientes con mas de 10 dolares de ganancia
            $objetos[$index]['clientsTotalMoreThanTenDollars']=self::getTotalCarriers($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de todos los clientes
            $objetos[$index]['totalCustomer']=self::getTotalCompleteCarriers($startDateTemp,$endingDateTemp,true);
            //Guardo los datos de los clientes con menos de 10 dolares de ganancia 
            $objetos[$index]['customersWithLessThanTenDollars']=self::getCarriers($startDate,$endingDate,true,false);
            //Guardo los datos de los totales de los clientes con menis de 10 dolares de ganancia
            $objetos[$index]['clientsTotalLessThanTenDollars']=self::getTotalCarriers($startDateTemp,$endingDateTemp,true,false);
            /***/
            //Guardo los datos de los proveedores con mas de 10 dolares de ganancia
            $objetos[$index]['providersWithMoreThanTenDollars']=self::getCarriers($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los proveedores con mas de 10 dolares de ganancia
            $objetos[$index]['suppliersTotalMoreThanTenDollars']=self::getTotalCarriers($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de todos los proveedores
            $objetos[$index]['totalSuppliers']=self::getTotalCompleteCarriers($startDateTemp,$endingDateTemp,false);
            //Guardo los datos de los proveedores con menos de 10 dolares de ganancia
            $objetos[$index]['providersWithLessThanTenDollars']=self::getCarriers($startDateTemp,$endingDateTemp,false,false);
            //Gurado los datos de los totales de los proveedores con menos de 10 dolares de ganancia
            $objetos[$index]['suppliersTotalLessThanTenDollars']=self::getTotalCarriers($startDateTemp,$endingDateTemp,false,false);
            /***/
            //Guardo los datos de los destinos externos con mas de 10 dolares de ganancia
            $objetos[$index]['externalDestinationsMoreThanTenDollars']=self::getDestination($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia
            $objetos[$index]['totalExternalDestinationsMoreThanTenDollars']=self::getTotalDestination($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los destinos externos
            $objetos[$index]['totalExternalDestinations']=self::getTotalCompleteDestination($startDateTemp,$endingDateTemp,true);
            //Guardo los datos de los destinos externos con menos de 10 dolares de ganancia
            $objetos[$index]['externalDestinationsLessThanTenDollars']=self::getDestination($startDateTemp,$endingDateTemp,true,false);
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia
            $objetos[$index]['totalExternalDestinationsLessThanTenDollars']=self::getTotalDestination($startDateTemp,$endingDateTemp,true,false);
            /***/
            //Guardo los datos de los destinos internos con mas de 10 dolares de ganancia
            $objetos[$index]['internalDestinationsWithMoreThanTenDollars']=self::getDestination($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los destinos internos con mas de 10 dolares de ganancia
            $objetos[$index]['totalInternalDestinationsWithMoreThanTenDollars']=self::getTotalDestination($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los destinos internos
            $objetos[$index]['totalInternalDestinations']=self::getTotalCompleteDestination($startDateTemp,$endingDateTemp,false);
            //Guardo los datos de los destinos internos con menos de 10 dolares de ganancia
            $objetos[$index]['internalDestinationsWithLessThanTenDollars']=self::getDestination($startDateTemp,$endingDateTemp,false,false);
            //Guardo los datos de los totales de los destinos internos con menos de 10 dolares de ganancia
            $objetos[$index]['totalInternalDestinationsWithLessThanTenDollars']=self::getTotalDestination($startDateTemp,$endingDateTemp,false,false);
            $startDateTemp=$arrayStartTemp[0]."-".($arrayStartTemp[1]+1)."-01";
            $index+=1;
        }
        $num=count($objetos);
        $last=$num-1;
        $sorted['customersWithMoreThanTenDollars']=self::sort($objetos[$last]['customersWithMoreThanTenDollars'],'cliente');
        $cuerpo="<table>";
        for ($row=0; $row < 17; $row++)
        { 
            $cuerpo.="<tr>";
            switch ($row)
            {
                case 0:
                case 2:
                case 4:
                case 6:
                case 8:
                case 10:
                case 12:
                case 14:
                case 16:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".$objetos[$col-1]['title']."</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                case 1:
                    $head=array(
                        'title'=>'Clientes (+10)',
                        'style'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['customersWithMoreThanTenDollars'],true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Clientes(+10$)</td>";
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['customersWithMoreThanTenDollars'],false)."</td>";
                        }
                    }
                    break;
                case 3:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Clientes Resto</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                case 5:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Proveedores (+10$)</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                case 7:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Proveedores Resto</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                case 9:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Destinos (+10)</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                case 11:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Destinos Resto</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                case 13:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Destinos Internal (+10$)</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                case 15:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>Destinos Internal Resto</td>";
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
            }
            $cuerpo.="</tr>";
        }
        
        $cuerpo.="</table>";
        /*$sorted['']=
        $sorted['']=
        $sorted['']=
        $sorted['']=
        $sorted['']=
        $sorted['']=
        $sorted['']=*/



        /*$cuerpo="<div>
                  <table>
                  <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Cliente (+10$)','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente','Ranking','Vendedor','PN'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //Clientes con margen mayor a diez dolares
        $clientes=self::getCarriers($startDate,$endingDate,true,true);
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
                            Yii::app()->format->format_decimal($cliente->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($cliente->complete_calls,0).
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
                         <td style='text-align: left;' class='margin'>".
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
                          <td style='text-align: left;' class='Vendedor'>".
                            CarrierManagers::getManager($cliente->id).
                        "</td>
                          <td style='text-align: left;' class='posicionNeta'>".
                             Yii::app()->format->format_decimal($cliente->posicion_neta).
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
        //El total de clientes con mas de 10$ de margen   
        $clientesTotal=self::getTotalCarriers($startDate,$endingDate,true,true);
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
                            Yii::app()->format->format_decimal($clientesTotal->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotal->complete_calls,0).
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
        //Selecciono la suma de todos los totales de clientes
        $clientesTotalCompleto=self::getTotalCompleteCarriers($startDate,$endingDate,true);
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
                            Yii::app()->format->format_decimal($clientesTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->complete_calls,0).
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
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','',''),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
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
            /******************************************CLIENTES RESTO******************************************************/
      /*  $cuerpo.="<table>
                  <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Cliente (RESTO)','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cliente','Ranking','Vendedor','PN'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //los clientes con menos de 10$ de margen
        $clientesResto=self::getCarriers($startDate,$endingDate,true,false);
        if($clientesResto!=null)
        {
            foreach ($clientesResto as $key => $clienteResto)
            {
                $max=count($clientesResto);
                $pos=$pos+1;
                $cuerpo.=self::color($pos);
                $cuerpo.="<td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                        <td style='text-align: left;' class='cliente'>".
                            $clienteResto->cliente.
                        "</td>
                        <td style='text-align: left;' class='Vendedor'>".
                            CarrierManagers::getManager($clienteResto->id).
                        "</td>
                         <td style='text-align: left;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($clienteResto->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clienteResto->complete_calls,0).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($clienteResto->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($clienteResto->asr).
                        "</td>
                         <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal($clienteResto->acd).
                        "</td>
                        <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($clienteResto->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($clienteResto->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($clienteResto->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($clienteResto->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($clienteResto->margin_percentage)."%
                         </td>
                         </td><td style='text-align: left;' class='cliente'>".
                            $clienteResto->cliente.
                        "</td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                          <td style='text-align: left;' class='Vendedor'>".
                            CarrierManagers::getManager($clienteResto->id).
                        "</td>
                         <td style='text-align: left;' class='posicionNeta'>".
                            Yii::app()->format->format_decimal($clienteResto->posicion_neta).
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
        //Selecciono la suma de todos los totales menores a 10 dolares de margen
        $clientesTotalResto=self::getTotalCarriers($startDate,$endingDate,true,false);
        if($clientesTotalResto->total_calls!=null)
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
                            Yii::app()->format->format_decimal($clientesTotalResto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalResto->complete_calls,0).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($clientesTotalResto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>
                        <td style='text-align: center;' class='acd'>
                        </td>
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($clientesTotalResto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($clientesTotalResto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($clientesTotalResto->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>
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
                            Yii::app()->format->format_decimal($clientesTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($clientesTotalCompleto->complete_calls,0).
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
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','',''),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
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
                            Yii::app()->format->format_decimal(($clientesTotalResto->total_calls/$clientesTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($clientesTotalResto->complete_calls/$clientesTotalCompleto->complete_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='minutos'>".
                            Yii::app()->format->format_decimal(($clientesTotalResto->minutes/$clientesTotalCompleto->minutes)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($clientesTotalResto->cost/$clientesTotalCompleto->cost)*(100))."%
                        </td>
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($clientesTotalResto->revenue/$clientesTotalCompleto->revenue)*(100))."%
                        </td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal(($clientesTotalResto->margin/$clientesTotalCompleto->margin)*(100))."%
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
                            <td colspan='12'>No se encontraron resultados</td>
                          </tr>
                     </table>
            <br>";
            }
/*********************************************************************PROVEEDORES +10$*********************************************************************/
     /*   $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Proveedor (+10$)','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Proveedor','Ranking','Vendedor','PN'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        // Proveedores con margen mayor a 10$
        $proveedores=self::getCarriers($startDate,$endingDate,false,true);
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
                            Yii::app()->format->format_decimal($proveedor->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedor->complete_calls,0).
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
                           <td style='text-align: left;' class='Vendedor'>".
                            CarrierManagers::getManager($proveedor->id).
                        "</td>
                         <td style='text-align: left;' class='posicionNeta'>".
                            Yii::app()->format->format_decimal($proveedor->posicion_neta).
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
        // La suma de todos los proveedores con mas de 10$
        $proveedoresTotal=self::getTotalCarriers($startDate,$endingDate,false,true);
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
                            Yii::app()->format->format_decimal($proveedoresTotal->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotal->complete_calls,0).
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
        $proveedoresTotalCompleto=self::getTotalCompleteCarriers($startDate,$endingDate,false);
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
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->complete_calls,0).
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
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','',''),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
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
          
/************************************************************PROVEEDORES RESTO*****************************************************************************/          
     /*   $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Proveedor (RESTO)','Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Proveedor','Ranking','Vendedor','PN'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        // Proveedores con menos de 10$ de margen
        $proveedoresResto=self::getCarriers($startDate,$endingDate,false,false);
        if($proveedoresResto!=null)
        {
            foreach($proveedoresResto as $key => $proveedorResto)
            {
                $pos=$pos+1;
                $cuerpo.=self::color($pos);
                $cuerpo.="<td style='text-align: center;' class='ranking'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='supplier'>".
                            $proveedorResto->proveedor.
                        "</td>
                        <td style='text-align: left;' class='vendedor'>".
                            CarrierManagers::getManager($proveedorResto->id).
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($proveedorResto->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedorResto->complete_calls,0).
                        "</td>
                         <td style='text-align: left;' class='minutes'>".
                            Yii::app()->format->format_decimal($proveedorResto->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($proveedorResto->asr).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($proveedorResto->acd).
                        "</td>
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($proveedorResto->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($proveedorResto->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($proveedorResto->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($proveedorResto->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($proveedorResto->margin_percentage)."%
                         </td>
                         <td style='text-align: left;' class='supplier'>".
                            $proveedorResto->proveedor.
                        "</td>
                         <td style='text-align: center;' class='position'>".
                            $pos.
                        "</td>
                           <td style='text-align: left;' class='Vendedor'>".
                            CarrierManagers::getManager($proveedorResto->id).
                        "</td>
                         <td style='text-align: left;' class='posicionNeta'>".
                            Yii::app()->format->format_decimal($proveedorResto->posicion_neta).
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
        // Totales de los proveedores con menos de 10$
        $proveedoresTotalResto=self::getTotalCarriers($startDate,$endingDate,false,false);
        if($proveedoresTotalResto->total_calls!=null)
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
                            Yii::app()->format->format_decimal($proveedoresTotalResto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotalResto->complete_calls,0).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($proveedoresTotalResto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>
                        <td style='text-align: center;' class='acd'>
                        </td>
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($proveedoresTotalResto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($proveedoresTotalResto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($proveedoresTotalResto->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>
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
        // La suma total de los proveedores
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
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($proveedoresTotalCompleto->complete_calls,0).
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
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','',''),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8','background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
        if($proveedoresTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='totalCalls'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalResto->total_calls/$proveedoresTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalResto->complete_calls/$proveedoresTotalCompleto->complete_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='minutos'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalResto->minutes/$proveedoresTotalCompleto->minutes)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalResto->cost/$proveedoresTotalCompleto->cost)*(100))."%
                        </td>
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalResto->revenue/$proveedoresTotalCompleto->revenue)*(100))."%
                        </td>
                        <td style='text-align: right;' class='margin'>".
                            Yii::app()->format->format_decimal(($proveedoresTotalResto->margin/$proveedoresTotalCompleto->margin)*(100))."%
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
                        <td colspan='13'>No se encontraron resultados</td>
                     </tr>
                    </table>
                <br>";
          }
/******************************************************************DESTINOS (+10$)************************************************************************/
   /*     $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Destino (+10$)','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Destino','Ranking','Cost/Min','Rate/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //Destinos con mas de 10$ de margen
        $destinos=self::getDestination($startDate,$endingDate,true,true);
        if($destinos!=null)
        {
            foreach($destinos as $key => $destino)
            {
                $pos=$key+1;
                $cuerpo.=self::colorDestino($destino->destino);
                $cuerpo.="<td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td colspan='2' style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($destino->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destino->complete_calls,0).
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
                            Yii::app()->format->format_decimal($destino->margin_percentage)."%
                        </td>
                        <td style='text-align: left;' class='destino'>".
                            $destino->destino.
                        "</td>
                         <td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
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

                    </tr>";
            }
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }

        // La suma de los totales de destinos con mas de 10$ de margen
        $destinosTotal=self::getTotalDestination($startDate,$endingDate,true,true);
        if($destinosTotal->total_calls!=null)
        {
             $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            TOTAL
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destinosTotal->complete_calls,0).
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
                        <td style='text-align: center;' class='etiqueta'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                        TOTAL
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
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        //La suma de los totales de todos los destinos
        $destinosTotalCompleto=self::getTotalCompleteDestination($startDate,$endingDate,true);
        if($destinosTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            Total
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->complete_calls,0).
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
                         <td colspan='2' style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','','Cost/Min','Rate/Min','Margin/Min',),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8',
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
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
        if($destinosTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: left; background-color:#f8f8f8' class='vacio'>
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
/************************************************************************DESTINOS (RESTO)*****************************************************************/
    /*            $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Destino (RESTO)','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Destino','Ranking','Cost/Min','Rate/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        // Los destinos con menos de 10$ de margen
        $destinosResto=self::getDestination($startDate,$endingDate,true,false);
        if($destinosResto!=null)
        {
            foreach($destinosResto as $key => $destinoResto)
            {
                $pos=$pos+1;
                $cuerpo.=self::colorDestino($destinoResto->destino);
                $cuerpo.="<td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td colspan='2' style='text-align: left;' class='destino'>".
                            $destinoResto->destino.
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($destinoResto->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinoResto->complete_calls,0).
                        "</td>
                         <td style='text-align: left;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinoResto->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($destinoResto->asr).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($destinoResto->acd).
                        "</td>
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinoResto->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($destinoResto->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinoResto->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($destinoResto->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinoResto->margin_percentage)."%
                        </td>
                        <td style='text-align: left;' class='destino'>".
                            $destinoResto->destino.
                        "</td>
                         <td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinoResto->costmin).
                        "</td>
                         <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinoResto->ratemin).
                        "</td>
                         <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinoResto->marginmin).
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

        //La suma de los totales de destinos con menos de 10$ de margen
        $destinosTotalResto=self::getTotalDestination($startDate,$endingDate,true,false);
        if($destinosTotalResto->total_calls!=null)
        {
             $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            TOTAL
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->complete_calls,0).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>
                        <td style='text-align: center;' class='acd'>
                        </td>
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->margin).
                       "</td>
                        <td style='text-align: center;' class='etiqueta'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                        TOTAL
                        </td>
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->costmin).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->ratemin).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinosTotalResto->marginmin).
                       "</td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        if($destinosTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            Total
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosTotalCompleto->complete_calls,0).
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
                         <td colspan='2' style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','','Cost/Min','Rate/Min','Margin/Min',),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8',
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
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
        if($destinosTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='totalCalls'>".
                            Yii::app()->format->format_decimal(($destinosTotalResto->total_calls/$destinosTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($destinosTotalResto->complete_calls/$destinosTotalCompleto->complete_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='minutes'>".
                            Yii::app()->format->format_decimal(($destinosTotalResto->minutes/$destinosTotalCompleto->minutes)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($destinosTotalResto->cost/$destinosTotalCompleto->cost)*(100))."%
                        </td>
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($destinosTotalResto->revenue/$destinosTotalCompleto->revenue)*(100))."%
                        </td>
                        <td style='text-align: right;' class='margin'>".
                            Yii::app()->format->format_decimal(($destinosTotalResto->margin/$destinosTotalCompleto->margin)*(100))."%
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
            <br>";
        }
        else
        {
            $cuerpo.="<tr>
                      <td colspan='15'>No se encontraron resultados</td>
                    </tr>
                    </table>
                    <br>";
        }
        $cuerpo.="<table>
                 <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Destino Internal (+10$)','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Destino Internal (+10$)','Ranking','Cost/Min','Rate/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //Destinos Internos con mas de 10$ de margen
        $destinosInternal=self::getDestination($startDate,$endingDate,false,true);
        if($destinosInternal!=null)
        {
            foreach($destinosInternal as $key => $destinoInternal)
            {
                $pos=$key+1;
                $cuerpo.=self::colorDestino($destinoInternal->destino);
                $cuerpo.="<td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td colspan='2' style='text-align: left;' class='destino'>".
                            $destinoInternal->destino.
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($destinoInternal->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinoInternal->complete_calls,0).
                        "</td>
                         <td style='text-align: left;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinoInternal->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($destinoInternal->asr).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($destinoInternal->acd).
                        "</td>
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinoInternal->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($destinoInternal->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinoInternal->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($destinoInternal->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinoInternal->margin_percentage)."%
                        </td>
                        <td style='text-align: left;' class='destino'>".
                            $destinoInternal->destino.
                        "</td>
                         <td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinoInternal->costmin).
                        "</td>
                         <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinoInternal->ratemin).
                        "</td>
                         <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinoInternal->marginmin).
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

        // La suma de los totales de destinos internos con mas de 10$ de margen
        $destinosInternosTotal=self::getTotalDestination($startDate,$endingDate,false,true);
        if($destinosInternosTotal->total_calls!=null)
        {
             $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            TOTAL
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->complete_calls,0).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>
                        <td style='text-align: center;' class='acd'>
                        </td>
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->margin).
                       "</td>
                        <td style='text-align: center;' class='etiqueta'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                        TOTAL
                        </td>
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->costmin).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->ratemin).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinosInternosTotal->marginmin).
                       "</td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        //La suma de los totales de todos los destinos
        $destinosInternosTotalCompleto=self::getTotalCompleteDestination($startDate,$endingDate,false);
        if($destinosInternosTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            Total
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->complete_calls,0).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->margin_percentage).
                       "</td>
                         <td colspan='2' style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','','Cost/Min','Rate/Min','Margin/Min',),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8',
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
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
        if($destinosInternosTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='totalCalls'>".
                            Yii::app()->format->format_decimal(($destinosInternosTotal->total_calls/$destinosInternosTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($destinosInternosTotal->complete_calls/$destinosInternosTotalCompleto->complete_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='minutes'>".
                            Yii::app()->format->format_decimal(($destinosInternosTotal->minutes/$destinosInternosTotalCompleto->minutes)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($destinosInternosTotal->cost/$destinosInternosTotalCompleto->cost)*(100))."%
                        </td>
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($destinosInternosTotal->revenue/$destinosInternosTotalCompleto->revenue)*(100))."%
                        </td>
                        <td style='text-align: right;' class='margin'>".
                            Yii::app()->format->format_decimal(($destinosInternosTotal->margin/$destinosInternosTotalCompleto->margin)*(100))."%
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
        $cuerpo.=self::cabecera(array('Ranking','Destino Internal (RESTO)','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Destino Internal (RESTO)','Ranking','Cost/Min','Rate/Min','Margin/Min'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //Destinos Internos con menos de 10$ de margen
        $destinosInternalResto=self::getDestination($startDate,$endingDate,false,false);
        if($destinosInternalResto!=null)
        {
            foreach($destinosInternalResto as $key => $destinoInternalResto)
            {
                $pos=$pos+1;
                $cuerpo.=self::colorDestino($destinoInternalResto->destino);
                $cuerpo.="<td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td colspan='2' style='text-align: left;' class='destino'>".
                            $destinoInternalResto->destino.
                        "</td>
                         <td style='text-align: left;' class='totalcalls'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->total_calls,0).
                        "</td>
                         <td style='text-align: left;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->complete_calls,0).
                        "</td>
                         <td style='text-align: left;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->minutes).
                        "</td>
                         <td style='text-align: left;' class='asr'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->asr).
                        "</td>
                         <td style='text-align: left;' class='acd'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->acd).
                        "</td>
                         <td style='text-align: left;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->pdd).
                        "</td>
                         <td style='text-align: left;' class='cost'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->cost).
                        "</td>
                         <td style='text-align: left;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->revenue).
                        "</td>
                         <td style='text-align: left;' class='margin'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->margin).
                        "</td>
                         <td style='text-align: left;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->margin_percentage)."%
                        </td>
                        <td style='text-align: left;' class='destino'>".
                            $destinoInternalResto->destino.
                        "</td>
                         <td style='text-align: center;' class='diferencialBancario'>".
                            $pos.
                        "</td>
                         <td style='text-align: left;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->costmin).
                        "</td>
                         <td style='text-align: left;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->ratemin).
                        "</td>
                         <td style='text-align: left;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinoInternalResto->marginmin).
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

        // La suma de los totales de destinos internos con mas de 10$ de margen
        $destinosInternosRestoTotal=self::getTotalDestination($startDate,$endingDate,false,false);
        if($destinosInternosRestoTotal->total_calls!=null)
        {
             $cuerpo.="<tr style='background-color:#999999; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            TOTAL
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completecalls'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->complete_calls,0).
                       "</td>
                        <td style='text-align: center;' class='minutos'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>
                        </td>
                        <td style='text-align: center;' class='acd'>
                        </td>
                        <td style='text-align: center;' class='pdd'>
                        </td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->margin).
                       "</td>
                        <td style='text-align: center;' class='etiqueta'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                        TOTAL
                        </td>
                        <td style='text-align: center;' class='costmin'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->costmin).
                       "</td>
                        <td style='text-align: center;' class='ratemin'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->ratemin).
                       "</td>
                        <td style='text-align: center;' class='marginmin'>".
                            Yii::app()->format->format_decimal($destinosInternosRestoTotal->marginmin).
                       "</td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        //La suma de los totales de todos los destinos
        if($destinosInternosTotalCompleto->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: center;' class='etiqueta'>
                            Total
                        </td>
                        <td style='text-align: center;' class='totalCalls'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->total_calls,0).
                       "</td>
                        <td style='text-align: center;' class='completeCalls'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->complete_calls,0).
                       "</td>
                        <td style='text-align: center;' class='minutes'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->minutes).
                       "</td>
                        <td style='text-align: center;' class='asr'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->asr).
                       "</td>
                        <td style='text-align: center;' class='acd'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->acd).
                       "</td>
                        <td style='text-align: center;' class='pdd'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->pdd).
                       "</td>
                        <td style='text-align: center;' class='cost'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->cost).
                       "</td>
                        <td style='text-align: center;' class='revenue'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->revenue).
                       "</td>
                        <td style='text-align: center;' class='margin'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->margin).
                       "</td>
                        <td style='text-align: center;' class='margin_percentage'>".
                            Yii::app()->format->format_decimal($destinosInternosTotalCompleto->margin_percentage).
                       "</td>
                         <td colspan='2' style='text-align: center;' class='etiqueta'>
                          Total
                        </td>
                        <td style='text-align: center;' class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                        <td style='text-align: left; class='marginmin'>
                        </td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='15'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.=self::cabecera(array('','','','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','','','Cost/Min','Rate/Min','Margin/Min',),
                                array('background-color:#f8f8f8','background-color:#f8f8f8','background-color:#f8f8f8',
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
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                                    'background-color:#f8f8f8',
                                    'background-color:#f8f8f8'));
        if($destinosInternosRestoTotal->total_calls!=null)
        {
            $cuerpo.="<tr style='background-color:#615E5E; color:#FFFFFF;'>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td colspan='2' style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='totalCalls'>".
                            Yii::app()->format->format_decimal(($destinosInternosRestoTotal->total_calls/$destinosInternosTotalCompleto->total_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='completeCalls'>".
                            Yii::app()->format->format_decimal(($destinosInternosRestoTotal->complete_calls/$destinosInternosTotalCompleto->complete_calls)*(100))."%
                        </td>
                        <td style='text-align: right;' class='minutes'>".
                            Yii::app()->format->format_decimal(($destinosInternosRestoTotal->minutes/$destinosInternosTotalCompleto->minutes)*(100))."%
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: left; background-color:#f8f8f8' class='vacio'>
                        </td>
                        <td style='text-align: right;' class='cost'>".
                            Yii::app()->format->format_decimal(($destinosInternosRestoTotal->cost/$destinosInternosTotalCompleto->cost)*(100))."%
                        </td>
                        <td style='text-align: right;' class='revenue'>".
                            Yii::app()->format->format_decimal(($destinosInternosRestoTotal->revenue/$destinosInternosTotalCompleto->revenue)*(100))."%
                        </td>
                        <td style='text-align: right;' class='margin'>".
                            Yii::app()->format->format_decimal(($destinosInternosRestoTotal->margin/$destinosInternosTotalCompleto->margin)*(100))."%
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
                <br>";
          }
          else
          {
            $cuerpo.="<tr>
                        <td colspan='13'>No se encontraron resultados</td>
                     </tr>
                    </table>
                <br>";
          }*/
        return $cuerpo;
	}

    /**
     * Genera una tabla con la lista y ranking del dato pasado
     * @access private
     * @static
     * @param array $head titulo que lleva la cabezera y su estilo. ej: $array['title']="Clientes"; $array['style']="color:black";
     * @param array $list lista de nombres incluidos para contruir la tabla
     * @param boolean $type si es true es para el principio, false al final
     */
    private static function getHtmlTable($head,$lista,$type=true)
    {
        $body="<table>";
        if($type)
        {
            $body.=self::cabecera(array('Ranking',$head['title'],'Vendedor'),$head['style']);
            foreach ($lista as $key => $value)
            {
                $pos=$key+1;
                $body.="<tr><td>".$pos."</td><td>".$value['attribute']."</td><td>".CarrierManagers::getManager($value['id'])."</td></tr>";
            }
        }
        else
        {
            $body.=self::cabecera(array('Vendedor',$head['title'],'Ranking'),$head['style']);
            foreach ($lista as $key => $value)
            {
                $pos=$key+1;
                $body.="<tr><td>".CarrierManagers::getManager($value['id'])."</td><td>".$value['attribute']."</td><td>".$pos."</td></tr>";
            }
        }
        $body.="</table>";
        return $body;
    }

    /**
     * Encargado de traer los datos de los carriers
     * @access private
     * @static
     * @param date $startDate fecha de inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=+10$, false=-10$
     * @return array $models
     */
    private static function getCarriers($startDate,$endingDate,$typeCarrier=true,$type=true)
    {
        if($type)
            $condicion="x.margin>10";
        else
            $condicion="x.margin<10";

        if($typeCarrier)
        {
            $titulo="cliente";
            $select="id_carrier_customer";
        }
        else
        {
            $titulo="proveedor";
            $select="id_carrier_supplier";
        }

        $sql="SELECT c.name AS {$titulo}, x.{$select} AS id, x.total_calls, x.complete_calls, x.minutes, x.asr,x.acd, x.pdd, x.cost, x.revenue, x.margin, CASE WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage, cs.posicion_neta AS posicion_neta
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$select}
                   ORDER BY margin DESC) x,
                  (SELECT id,SUM(vrevenue-ccost) AS posicion_neta
                   FROM(SELECT id_carrier_customer AS id,SUM(revenue) AS vrevenue, CAST(0 AS double precision) AS ccost
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_customer
                        UNION
                        SELECT id_carrier_supplier AS id,CAST(0 AS double precision) AS vrevenue, SUM(cost) AS ccost
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_supplier
                        ORDER BY id ASC)t
                   GROUP BY id
                   ORDER BY posicion_neta DESC)cs,
                   carrier c
              WHERE {$condicion} AND x.{$select}=c.id AND x.{$select}=cs.id
              ORDER BY x.margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * trae el total de todos los carriers
     * @access private
     * @static
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=margen mayor a 10$, false=margen menor a 10$
     * @return object $model
     */
    private static function getTotalCarriers($startDate,$endingDate,$typeCarrier=true,$type=true)
    {
        if($type)
            $condicion="margin>10";
        else
            $condicion="margin<10";

        if($typeCarrier)
            $select="id_carrier_customer";
        else
            $select="id_carrier_supplier";
        
        $sql="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance
              WHERE {$condicion}";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna el total de todos los clientes en la fecha especificada
     * @access private
     * @static
     * @param date $startDate
     * @param date $endingDate
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @return array $models
     */
    private static function getTotalCompleteCarriers($startDate,$endingDate,$typeCarrier=true)
    {
        if($typeCarrier)
            $select="id_carrier_customer";
        else
            $select="id_carrier_supplier";

        $sql="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna la data de los destinos
     * @access private
     * @static
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$
     * @return array $models
     */
    private static function getDestination($startDate,$endingDate,$typeDestination=true,$type=true)
    {
        if($type)
            $condicion="x.margin>10";
        else
            $condicion="x.margin<10";

        if($typeDestination)
        {
            $select="id_destination";
            $table="destination";
        }
        else
        {
            $select="id_destination_int";
            $table="destination_int";
        }

        $sql="SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, CASE WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage, CASE WHEN x.minutes=0 THEN 0 ELSE(x.cost/x.minutes)*100 END AS costmin, CASE WHEN x.minutes=0 THEN 0 ELSE(x.revenue/x.minutes)*100 END AS ratemin, CASE WHEN x.minutes=0 THEN 0 ELSE((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) END AS marginmin
                      FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                           FROM balance
                           WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$select}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$select} IS NOT NULL
                           GROUP BY {$select}
                           ORDER BY margin DESC) x, {$table} d
                      WHERE {$condicion} AND x.{$select}=d.id
                      ORDER BY x.margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Retorna el total de la data de los destinos
     * @access private
     * @static
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$
     * @return object $model
     */
    private static function getTotalDestination($startDate,$endingDate,$typeDestination=true,$type=true)
    {
        if($type)
            $condicion="margin>10";
        else
            $condicion="margin<10";

        if($typeDestination)
        {
            $select="id_destination";
            $table="destination";
        }
        else
        {
            $select="id_destination_int";
            $table="destination_int";
        }

        $sql="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$select}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$select} IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance
              WHERE {$condicion}";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna el total de la data de todos los destinos
     * @access private
     * @static
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @return object $model
     */
    private static function getTotalCompleteDestination($startDate,$endingDate,$typeDestination=true)
    {
        if($typeDestination)
        {
            $select="id_destination";
            $table="destination";
        }
        else
        {
            $select="id_destination_int";
            $table="destination_int";
        }
        $sql="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
              FROM(SELECT {$select}, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND {$select}<>(SELECT id FROM {$table} WHERE name='Unknown_Destination') AND {$select} IS NOT NULL
                   GROUP BY {$select}
                   ORDER BY margin DESC) balance";
        return Balance::model()->findBySql($sql);
    }
}
?>