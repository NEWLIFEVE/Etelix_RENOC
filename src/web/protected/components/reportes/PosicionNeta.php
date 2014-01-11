<?php
/**
* @package reportes
* @version 1.0
*/
class PosicionNeta extends Reportes
{
    /**
    * @param $fecha date fecha que va a ser consultada
    * @return $cuerpo string con el cuerpo de la tabla
    */
	public function reporte($start,$end)
	{
        $this->_getDays($start);

        $this->_loopData($start,$end);
        
        

        $cuerpo="<div>
                    <table >
                        <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Operador','Vendedor','Vminutes','Vrevenue','Vmargin','Cminutes','Ccost','Cmargin','Margen Total','Posicion Neta','Operador','Ranking','Vendedor'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="<thead>
                  <tbody>";
         
        $posicionNeta=Balance::model()->findAllBySql($sqlCien);
        if($posicionNeta!=null)
        { 
            $max=count($posicionNeta);
            foreach($posicionNeta as $key => $operador)
            {  

                //$pos=self::ranking($key+1,$max);
                $pos=$key+1;
                $cuerpo.="<tr>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='ranking'>".
                                $pos. 
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='operador'>".
                                $operador->operador.
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='vendedor'>".
                                CarrierManagers:: getManager($operador->id).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='vminutes'>".
                                Yii::app()->format->format_decimal($operador->vminutes).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='vrevenue'>".
                                Yii::app()->format->format_decimal($operador->vrevenue).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='vmargin'>".
                                Yii::app()->format->format_decimal($operador->vmargin).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='cminutes'>".
                                Yii::app()->format->format_decimal($operador->cminutes).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='ccost'>".
                                Yii::app()->format->format_decimal($operador->ccost).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='cmargin'>".
                                Yii::app()->format->format_decimal($operador->cmargin).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='posicionNeta'>".
                                Yii::app()->format->format_decimal($operador->margen_total).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='margenTotal'>".
                                Yii::app()->format->format_decimal($operador->posicion_neta).
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='operador'>".
                                $operador->operador.
                           "</td>
                            <td style='text-align: center;".self::colorEstilo($key+1)."' class='ranking'>".
                                $pos.
                           "</td>
                           <td style='text-align: center;".self::colorEstilo($key+1)."' class='vendedor'>".
                                CarrierManagers:: getManager($operador->id).
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
        $cuerpo.=self::cabecera(array('Ranking','Operador','Vendedor','Vminutes','Vrevenue','Vmargin','Cminutes','Ccost','Cmargin','Margen Total','Posicion Neta','Operador','Ranking','Vendedor'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $Total=Balance::model()->findBySql($sqlTotal);
        if($Total!=null)
        { 
            $cuerpo.="<tr>
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
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='margenTotal'>".
                            Yii::app()->format->format_decimal($Total->margen_total).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='posicionNeta'>".
                            Yii::app()->format->format_decimal($Total->posicion_neta).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='operador'>
                         TOTAL
                         </td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vacio'>
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                      <td colspan='13'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.="</tbody></table>";
        $cuerpo.="</div>";
        return $cuerpo;
	}
    /**
     * Metodo encargado de conseguir los carriers para generar el reporte
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @return array
     */
    private function _getCarriers($startDate,$endDate)
    {
        $sql="SELECT o.name AS carrier, cs.id, cs.vminutes, cs.vrevenue, cs.vmargin, cs.cminutes, cs.ccost, cs.cmargin, cs.posicion_neta, cs.margen_total
              FROM(SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
                   FROM(SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS vmargin, CAST(0 AS double precision) AS cminutes, CAST(0 AS double precision) AS ccost, CAST(0 AS double precision) AS cmargin
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_customer
                        UNION
                        SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, CAST(0 AS double precision) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS cmargin
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_supplier)t
                   GROUP BY id
                   ORDER BY posicion_neta DESC)cs, carrier o
              WHERE o.id=cs.id
              ORDER BY cs.posicion_neta DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Metodo que consigue el total de las fechas pasadas como parametros
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @return CActiveRecord
     */
    private function _getTotalCarriers($startDate,$endDate)
    {
        $sql="SELECT SUM(cs.vminutes) AS vminutes, SUM(cs.vrevenue) AS vrevenue, SUM(cs.vmargin) AS vmargin, SUM(cs.cminutes) AS cminutes, SUM(cs.ccost) AS ccost, SUM(cs.cmargin) AS cmargin, SUM(cs.posicion_neta) AS posicion_neta, SUM(cs.margen_total) AS margen_total
              FROM (SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
                    FROM (SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS vmargin, CAST(0 AS double precision) AS cminutes, CAST(0 AS double precision) AS ccost, CAST(0 AS double precision) AS cmargin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                          GROUP BY id_carrier_customer
                          UNION
                          SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, CAST(0 AS double precision) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS cmargin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                          GROUP BY id_carrier_supplier)t
                    GROUP BY id
                    ORDER BY posicion_neta DESC)cs";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Metodo encargado de regresar 
     */
    private function _getAvgCarriers($startDate,$endDate)
    {
        $sql="SELECT o.name AS carrier, cs.id, AVG(cs.posicion_neta) AS posicion_neta
              FROM (SELECT id, date_balance, SUM(vrevenue-ccost) AS posicion_neta
                    FROM (SELECT id_carrier_customer AS id, date_balance, SUM(revenue) AS vrevenue, CAST(0 AS double precision) AS ccost
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                          GROUP BY id_carrier_customer, date_balance
                          UNION
                          SELECT id_carrier_supplier AS id, date_balance, CAST(0 AS double precision) AS vrevenue, SUM(cost) AS ccost
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                          GROUP BY id_carrier_supplier, date_balance) t
                    GROUP BY id, date_balance
                    ORDER BY posicion_neta DESC) cs, carrier o
              WHERE o.id=cs.id
              GROUP BY cs.id, o.name
              ORDER BY posicion_neta DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     *
     */
    private function _getTotalAvgCarriers($startDate,$endDate)
    {
        $sql="SELECT SUM(b.posicion_neta) AS posicion_neta
              FROM (SELECT o.name AS carrier, cs.id, AVG(cs.posicion_neta) AS posicion_neta
                    FROM (SELECT id, date_balance, SUM(vrevenue-ccost) AS posicion_neta
                          FROM (SELECT id_carrier_customer AS id, date_balance, SUM(revenue) AS vrevenue, CAST(0 AS double precision) AS ccost
                                FROM balance
                                WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                GROUP BY id_carrier_customer, date_balance
                                UNION
                                SELECT id_carrier_supplier AS id, date_balance, CAST(0 AS double precision) AS vrevenue, SUM(cost) AS ccost
                                FROM balance
                                WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                GROUP BY id_carrier_supplier, date_balance) t
                          GROUP BY id, date_balance
                          ORDER BY posicion_neta DESC) cs, carrier o
                    WHERE o.id=cs.id
                    GROUP BY cs.id, o.name
                    ORDER BY posicion_neta DESC) b";
        return Balance::model()->findBySql($sql);
    }

    /**
     * @access private
     */
    private function _loopData($startDate,$endDate)
    {
        $startDateTemp=self::valDates($startDate,$endDate)['startDate'];
        $endingDateTemp=self::valDates($startDate,$endDate)['endingDate'];
        $yesterday=DateManagement::calculateDate('-1',$startDateTemp);
        $sevenDaysAgo=DateManagement::calculateDate('-7',$yesterday);
        $firstDay=DateManagement::getDayOne($startDate);
        $this->equal=self::valDates($startDate,$endDate)['equal'];
        $index=0;

        while(self::isLower($startDateTemp,$endingDate))
        {
            $endingDateTemp=self::maxDate(DateManagement::separatesDate($startDateTemp)['year']."-".DateManagement::separatesDate($startDateTemp)['month']."-".DateManagement::howManyDays($startDateTemp),$endingDate);
            //El titulo que va a llevar la seccion
            $this->_objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            //La data de los carriers
            $this->_objetos[$index]['carriers']=$this->_getCarriers($startDateTemp,$endingDateTemp);
            //El total de los carriers traidos de base de datos
            $this->_objetos[$index]['totalCarriers']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp);
            //traigo la date de los carriers del dia anterior
            if($this->equal) $this->_objetos[$index]['carriersYesterday']=$this->_getCarriers($yesterday,$yesterday);
            //traigo totales de los carriers traidos de base de datos
            if($this->equal) $this->_objetos[$index]['totalCarriersYesterday']=$this->_getTotalCarriers($yesterday,$yesterday);
            // Average de los carriers
            if($this->equal) $this->_objetos[$index]['carriersAverage']=$this->_getAvgCarriers($sevenDaysAgo,$yesterday);
            // totales de los averages
            if($this->equal) $this->objetos[$index]['totalCarrierAverage']=$this->_getTotalAvgCarriers($sevenDaysAgo,$yesterday);
            //traigo el acumulado de los carrier hasta la fecha
            if($this->equal) $this->_objetos[$index]['carriersAccumulated']=$this->_getCarriers($firstDay,$startDate);
            //traigo el total del acumulado de los carriers
            if($this->equal) $this->_objetos[$index]['totalCarriersAccumulated']=$this->_getTotalCarriers($firstDay,$startDate);
            //Pronostico de los carrier
            if($this->equal) $this->_objetos[$index]['carriersForecast']=$this->_closeOfTheMonth(null,$index,'carriersAverage','carriersAccumulated','carrier');
            // Total de los pronosticos de los carriers
            if($this->equal) $this->_objetos[$index]['totalCarriersForecast']=array_sum($this->[$index]['carriersForecast']);
        }
    }
}
?>