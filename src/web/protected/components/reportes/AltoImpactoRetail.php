<?php
/**
* @package reportes
* @version 2.0
*/
class AltoImpactoRetail extends Reportes
{
    function __construct()
    {
        $this->_objetos=array();
        $this->_head=array(
            'styleHead'=>'text-align:center;background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
            'styleFooter'=>'text-align:center;background-color:#999999; color:#FFFFFF;',
            'styleFooterTotal'=>'text-align:center;background-color:#615E5E; color:#FFFFFF;'
            );
    }

	/**
     * Genera el string con la tablas del reporte de alto impacto retail
     * @access public
     * @param date $start
     * @param date $end
     * @return string cuerpo de la tabla del reporte
     */
	public function report($start,$end)
	{
        //
        $this->_getDays($start);
        //Obtengo los datos
        $this->_loopData($start,$end);
    }

    /**
     * Encargado de generar el array con la data
     * @since 2.0
     * @access private
     * @param date $start
     * @param date $end
     * @return array
     */
    private function _loopData($start,$end)
    {
        $startDateTemp=$startDate=self::valDates($start,$end)['startDate'];
        $endingDateTemp=$endingDate=self::valDates($start,$end)['endingDate'];
        $this->equal=self::valDates($start,$end)['equal'];
        $yesterday=DateManagement::calculateDate('-1',$startDate);
        $sevenDaysAgo=DateManagement::calculateDate('-7',$yesterday);
        $firstDay=DateManagement::getDayOne($start);

        $index=0;

        while(self::isLower($startDateTemp,$endingDate))
        {
            $endingDateTemp=self::maxDate(DateManagement::separatesDate($startDateTemp)['year']."-".DateManagement::separatesDate($startDateTemp)['month']."-".DateManagement::howManyDays($startDateTemp),$endingDate);
            //El titulo que va a llevar la seccion
            $this->_objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            //Clientes RP y R-E con mas de un dollar de margen
            $this->_objetos[$index]['customersRPWithMoreThanOneDollar']=$this->_getCustomers($startDateTemp,$endingDateTemp,'RP');
            //Total de los clientes RP y R-E con mas de un dollar de margen
            $this->_objetos[$index]['totalCustomersRPWithMoreThanOneDollar']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RP',true);
            //Total de los clientes RP y R-E
            $this->_objetos[$index]['totalCustomersRPComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RP',false);
            //Destinos RP y RE con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRPWithMoreThanOneDollar']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RP');
            //Total de los destinos RP y R-E con mas de un dollar de margen
            $this->_objetos[$index]['totalDestinationsRPWithMoreThanOneDollar']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',true);
            //Total de los destinos RP y RE
            $this->_objetos[$index]['totalDestinationsRPComplete']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',false);
            //Clientes RPRO con mas de un dollar de margen
            $this->_objetos[$index]['customersRPROWithMoreThanOneDollar']=$this->_getCustomers($startDateTemp,$endingDateTemp,'RPRO');
            //Total de los clientes RPRO con mas de un dollar de margen
            $this->_objetos[$index]['totalCustomersRPROWithMoreThanOneDollar']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RPRO',true);
            //Total de los clientes RPRO
            $this->_objetos[$index]['totalCustomersRPROComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RPRO',false);
            //Destinos RPRO con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRPROWithMoreThanOneDollar']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RPRO');
            //Total de los destinos RPRO con mas de un dollar de margen
            $this->_objetos[$index]['totalDestinationsRPROWithMoreThanOneDollar']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RPRO',true);
            //Total de los destinos RPRO
            $this->_objetos[$index]['totalDestinationsRPROComplete']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RPRO',false);

            /*Itero la fecha*/
            $startDateTemp=DateManagement::firstDayNextMonth($startDateTemp);
            $index+=1;
        }
    }

    /**
     * Encargada de traer la data correspondiente de los carriers retail
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @return array
     */
    private function _getCustomers($startDate,$endDate,$string)
    {
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //Construyo la consulta
        $sql="SELECT c.name AS carrier, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
              FROM (SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE id_carrier_customer IN ({$carriers}) AND date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_destination_int IS NOT NULL
                    GROUP BY id_carrier_customer) x, carrier c
              WHERE x.margin>1 AND x.id_carrier_customer=c.id
              ORDER BY x.margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Encargada de traer los totales de los carriers retail
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @param boolean $type, true=mayores a 1$, false=todos
     * @return CActiveRecord
     */
    private function _getTotalCustomers($startDate,$endDate,$string,$type=true)
    {
        //mayores o no al dollar
        $condition="";
        if($type) $condition="WHERE x.margin>1";
        //cual tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //Construyo la consulta
        $sql="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin
              FROM (SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE id_carrier_customer IN ({$carriers}) AND date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id 
                                                                                                                                                                    FROM carrier 
                                                                                                                                                                    WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id 
                                                                                                                                                                                                                           FROM destination_int 
                                                                                                                                                                                                                           WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                                                                                                                                                                    GROUP BY id_carrier_customer) x
              {$condition}";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Encargada de traer la data correspondiente de los destinos de los clientes retail
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @return array
     */
    private function _getDestinations($startDate,$endDate,$string)
    {
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //COnstruyo la consulta sql
        $sql="SELECT d.name AS destination, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
              FROM (SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id 
                                                                                                                FROM carrier 
                                                                                                                WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id 
                                                                                                                                                                   FROM destination 
                                                                                                                                                                   WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN ({$carriers})
                                                                                                                GROUP BY id_destination
                                                                                                                ORDER BY margin DESC) x, destination d
              WHERE x.margin > 1 AND x.id_destination=d.id
              ORDER BY x.margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Encargado de traer los destinos de retail
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @param boolean $type, true=mayores a 1$, false=todos
     * @return CActiveRecord
     */
    private function _getTotalDestinations($startDate,$endDate,$string,$type=true)
    {
        //mayores o no al dollar
        $condition="";
        if($type) $condition="WHERE margin>1";
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //Construyo la consulta sql
        $sql="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
              FROM (SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id 
                                                                                                                FROM carrier 
                                                                                                                WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id 
                                                                                                                                                                   FROM destination 
                                                                                                                                                                   WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN ({$carriers})
                                                                                                                GROUP BY id_destination
                                                                                                                ORDER BY margin DESC) balance
              {$condition}";
        return Balance::model()->findBySql($sql);
    }
}
?>