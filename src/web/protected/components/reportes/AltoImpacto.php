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
        $body="<table><tr>";
        foreach ($objetos as $key => $objeto)
        {
            $body.="<td>".$objeto['title']."</td>";
        }
        $body.="</tr><tr>";
        foreach ($objetos as $key => $objeto)
        {
            $body.="<td>".self::getHtmlTableCarriers($objeto['customersWithMoreThanTenDollars'],'Clientes (+10$)','cliente')."</td><td></td>";
        }
        $body.="</tr><tr><td></td></tr><tr>";
        foreach ($objetos as $key => $objeto)
        {
            $body.="<td>".self::getHtmlTableCarriers($objeto['customersWithLessThanTenDollars'],'Clientes (Resto)','cliente')."</td><td></td>";
        }
        $body.="</tr><tr><td></td></tr><tr>";
        foreach ($objetos as $key => $objeto)
        {
            $body.="<td>".self::getHtmlTableCarriers($objeto['providersWithMoreThanTenDollars'],'Proveedores (+10$)','proveedor')."</td><td></td>";
        }
        $body.="</tr><tr><td></td></tr><tr>";
        foreach ($objetos as $key => $objeto)
        {
            $body.="<td>".self::getHtmlTableCarriers($objeto['providersWithLessThanTenDollars'],'Proveedores (Resto)','proveedor')."</td><td></td>";
        }
        /*$body.="</tr><tr><td></td></tr><tr>";
        foreach ($objetos as $key => $objeto)
        {
            $body.="<td>".self::getHtmlTableCarriers($objeto['externalDestinationsMoreThanTenDollars'],'Destinos Externos (+10$)','proveedor')."</td><td></td>";
        }
        $body.="</tr><tr><td></td></tr><tr>";
        foreach ($objetos as $key => $objeto)
        {
            $body.="<td>".self::getHtmlTableCarriers($objeto['externalDestinationsLessThanTenDollars'],'Proveedores (Resto)','proveedor')."</td><td></td>";
        }*/
        $body.="</tr></table>";

        

        /*for ($row=0; $row < 17; $row++)
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
                            $cuerpo.="<td>".self::getHtmlTableCarriers($sorted['customersWithMoreThanTenDollars'],$objetos[$col-1]['customersWithMoreThanTenDollars'],'cliente')."</td>";
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['customersWithMoreThanTenDollars'],false)."</td>";
                        }
                    }
                    break;
                /*case 3:
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
                    break;*/
           /* }
            $cuerpo.="</tr>";
        }*/
        
        return $body;
	}

    /**
     * Obtiene el html de las tablas para los carriers
     */
    private static function getHtmlTableCarriers($data,$name,$attribute)
    {
        $body="<table>
                  <thead>";
        $body.=self::cabecera(array('Ranking',$name,'Vendedor','TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','PN','Vendedor',$name,'Ranking'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $body.="</thead>
                 <tbody>";
        foreach ($data as $key => $carrier)
        {
            $pos=$key+1;
            $style=self::colorEstilo($pos);
            $body.="
                        <td style='".$style."text-align: center;' class='position'>".$pos."</td>
                        <td style='".$style."text-align: left;' class='carrier'>".$carrier->$attribute."</td>
                        <td style='".$style."text-align: left;' class='Vendedor'>".CarrierManagers::getManager($carrier->id)."</td>
                        <td style='".$style."text-align: left;' class='totalCalls'>".Yii::app()->format->format_decimal($carrier->total_calls,0)."</td>
                        <td style='".$style."text-align: left;' class='completeCalls'>".Yii::app()->format->format_decimal($carrier->complete_calls,0)."</td>
                        <td style='".$style."text-align: left;' class='minutes'>".Yii::app()->format->format_decimal($carrier->minutes)."</td>
                        <td style='".$style."text-align: left;' class='asr'>".Yii::app()->format->format_decimal($carrier->asr)."</td>
                        <td style='".$style."text-align: center;' class='acd'>".Yii::app()->format->format_decimal($carrier->acd)."</td>
                        <td style='".$style."text-align: left;' class='pdd'>".Yii::app()->format->format_decimal($carrier->pdd)."</td>
                        <td style='".$style."text-align: left;' class='cost'>".Yii::app()->format->format_decimal($carrier->cost)."</td>
                        <td style='".$style."text-align: left;' class='revenue'>".Yii::app()->format->format_decimal($carrier->revenue)."</td>
                        <td style='".$style."text-align: left;' class='margin'>".Yii::app()->format->format_decimal($carrier->margin)."</td>
                        <td style='".$style."text-align: left;' class='margin_percentage'>".Yii::app()->format->format_decimal($carrier->margin_percentage)."%</td>
                        <td style='".$style."text-align: left;' class='posicionNeta'>".Yii::app()->format->format_decimal($carrier->posicion_neta)."</td>
                        <td style='".$style."text-align: left;' class='Vendedor'>".CarrierManagers::getManager($carrier->id)."</td>
                        <td style='".$style."text-align: left;' class='carrier'>".$carrier->$attribute."</td>
                        <td style='".$style."text-align: center;' class='position'>".$pos."</td>
                    </tr>";
        }
        $body.="</tbody>
                 </table>";
        return $body;
    }

    /**
     * Recibe un objeto de modelo y un apellido, retorna una fila <tr> con los datos del objeto
     * @access protected
     * @static
     * @param string $apellido
     * @param CActiveRecord $objeto
     * @return string
     */
    private static function getRowManagers($attribute,$name,$object,$position)
    {
        $style=self::colorEstilo($position);
        foreach ($object as $key => $value)
        {
            if($value->$attribute == $name)
            {
                return "<tr style='".$style."'>
                    <td>".Yii::app()->format->format_decimal($value->total_calls)."</td>
                    <td>".Yii::app()->format->format_decimal($value->complete_calls)."</td>
                    <td>".Yii::app()->format->format_decimal($value->minutes)."</td>
                    <td>".Yii::app()->format->format_decimal($value->asr)."</td>
                    <td>".Yii::app()->format->format_decimal($value->acd)."</td>
                    <td>".Yii::app()->format->format_decimal($value->pdd)."</td>
                    <td>".Yii::app()->format->format_decimal($value->cost)."</td>
                    <td>".Yii::app()->format->format_decimal($value->revenue)."</td>
                    <td>".Yii::app()->format->format_decimal($value->margin)."</td>
                    <td>".Yii::app()->format->format_decimal($value->margin_percentage)."</td>
                    <td>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>
                    </tr>";
            }
        }
        return "<tr style='".$style."'><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td></tr>";
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