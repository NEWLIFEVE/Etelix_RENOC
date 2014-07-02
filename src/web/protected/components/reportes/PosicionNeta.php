<?php
/**
 * @package reportes
 * @version 1.0}
 */
class PosicionNeta extends Reportes
{
    function __construct()
    {
        $this->equal=false;
        $this->_head=array(
            'styleHead'=>'text-align:center;background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
            'styleFooter'=>'text-align:center;background-color:#999999; color:#FFFFFF;',
            'styleFooterTotal'=>'text-align:center;background-color:#615E5E; color:#FFFFFF;'
            );
    }

    /**
     * @param $fecha date fecha que va a ser consultada
     * @return $cuerpo string con el cuerpo de la tabla
     */
    public function reporte($start,$end)
    {
        $this->_getDays($start);
        $this->_loopData($start,$end);
        //Cuento el numero de objetos en el array
        $num=count($this->_objetos);
        $last=$num-1;
        if(!$this->equal)
        {
            $span=8;
            $colu=3;
        }
        else
        {
            $span=16;
            $colu=7;
        }

        /*
        $sorted['carriers']=self::sort($this->_objetos[$last]['carriers'],'carrier');
        $carriers=count($this->_objetos[$last]['carriers']);
        */

        //Consulta.
        $sorted['carriers']=self::sort($this->_getCarriersThirtyDays($start,$end),'name');
        //Se cuentan los registros de la Consulta
        $carriers=count($this->_getCarriersThirtyDays($start,$end));
        

        $body="<table>";
        for($row=1;$row<=$carriers+4;$row++)
        {
            $body.="<tr>";
            for($col=1;$col<=$colu+($num*$span);$col++)
            {
                //Espacio gris al principio y al final de las columnas en la primera fila
                if($row==1 && ($col==1 || $col==3+($num*$span)))
                {
                    $body.="<td colspan='3' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Columna central superior que encierra el titulo de la tabla
                if($row==1 && self::validColumn(3,$col,$num,$span))
                {
                    $body.="<td colspan='".$span."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex(3,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //titulo que incluye los meses anteriores
                if($row==1 && $col==6+($num+$span))
                {
                    if($this->equal) $body.="<td colspan='10' style='text-align:center;background-color:#BFBEBE;color:#FFFFFF;'>Meses Anteriores</td>";
                }
                // cabecera a la izquierda y al principio del reporte
                if(($row==2||$row==$carriers+3) && $col==1)
                {
                    $body.=$this->_getHeader(true);
                }
                //cabecera a la derecha y al principio
                if(($row==2||$row==$carriers+3) && $col==3+($num*$span))
                {
                    $body.=$this->_getHeader(false);
                }
                //cabecera central
                if(($row==2||$row==$carriers+3)  && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getHeader(null);
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //titulo de los meses
                if(($row==2||$row==$carriers+3) && $col==6+($num+$span))
                {
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleThirdMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleFourthMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleFifthMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleSixthMonth']."</td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleSeventhMonth']."</td>";
                }
                //Nombres de los carriers izquierda
                if($row>2 && $row<=$carriers+2 && $col==1)
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-2;
                    //le resto las dos filas delante y uno mas para que empiece en cero
                    $body.=$this->_getNames($pos,$sorted['carriers'][$row-3],true);
                }
                //para totales
                if($row==$carriers+4 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleFooter']."'></td><td style='".$this->_head['styleFooter']."'></td><td style='".$this->_head['styleFooter']."'>TOTAL</td>";
                }
                //Nombres de los carriers derecha
                if($row>2 && $row<=$carriers+2 && $col==3+($num*$span))
                {
                    //le resto las siete filas que tiene delante
                    $pos=$row-2;
                    //le resto las dos filas delante y uno mas para que empiece en cero
                    $body.=$this->_getNames($pos,$sorted['carriers'][$row-3],false);
                }
                //para totales
                if($row==$carriers+4 && $col==3+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."'>TOTAL</td><td style='".$this->_head['styleFooter']."'></td><td style='".$this->_head['styleFooter']."'></td>";
                }
                //data de los carriers
                if($row>2 && $row<=$carriers+2 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRow(self::validIndex(3,$col,$span),'carriers','carrier',$sorted['carriers'][$row-3],self::colorEstilo($row-2));

                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //data de los meses anteriores
                if($row>2 && $row<=$carriers+2 && $col==6+($num+$span))
                {
                    if($this->equal) $body.=$this->_getRowMonths('carriers',$sorted['carriers'][$row-3]['attribute'],self::colorEstilo($row-2));
                }
                //
                if($row==$carriers+4 && self::validColumn(3,$col,$num,$span))
                {
                    $body.=$this->_getRowTotal(self::validIndex(3,$col,$span),'styleFooter');
                    if(!$this->equal && $last>(self::validIndex(3,$col,$span))) $body.="<td></td>";
                }
                //
                if($row==$carriers+4 && $col==6+($num+$span))
                {
                    if($this->equal) $body.=$this->_getRowTotalMonth('styleFooter');
                }
            }
            $body.="</tr>";
        }

        $body.="</table>";
        return $body;
    }

    /* Metodo encargardo de buscar los nombres vendedores en los 30 dias
    */

    private function _getCarriersThirtyDays($startDate,$endDate)
    {
      if(empty($endDate))
      {
        $endDate=$startDate;
      }
      $sql="SELECT c.name
        FROM
        carrier c,
        balance b
        WHERE b.date_balance>='".$startDate."' AND b.date_balance<='".$endDate."' AND b.id_carrier_customer=c.id
        GROUP BY c.name";
        return Carrier::model()->findAllBySql($sql);
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
        $sql="SELECT b.carrier AS carrier, SUM(b.posicion_neta) AS posicion_neta
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
                    ORDER BY posicion_neta DESC) b
               GROUP BY b.carrier";
        return Balance::model()->findBySql($sql);
    }
    /**
     * @access private
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
            if($this->equal) $this->_objetos[$index]['totalCarriersAverage']=$this->_getTotalAvgCarriers($sevenDaysAgo,$yesterday);
            //traigo el acumulado de los carrier hasta la fecha
            if($this->equal) $this->_objetos[$index]['carriersAccumulated']=$this->_getCarriers($firstDay,$startDate);
            //traigo el total del acumulado de los carriers
            if($this->equal) $this->_objetos[$index]['totalCarriersAccumulated']=$this->_getTotalCarriers($firstDay,$startDate);
            //Pronostico de los carrier
            if($this->equal) $this->_objetos[$index]['carriersForecast']=$this->_closeOfTheMonth(null,$index,'carriersAverage','carriersAccumulated','carrier','posicion_neta');
            // Total de los pronosticos de los carriers
            if($this->equal) $this->_objetos[$index]['totalCarriersForecast']=array_sum($this->_objetos[$index]['carriersForecast']);
            // Mes anterior
            if($this->equal) $this->_objetos[$index]['carriersPreviousMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday']);
            // Total mes anterior
            if($this->equal) $this->_objetos[$index]['totalCarriersPreviousMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday']);
            // Tercer Mes
            if($this->equal) $this->_objetos[$index]['carriersThirdMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            // titulo tercer mes
            if($this->equal) $this->_objetos[$index]['titleThirdMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            //Totales tercer mes
            if($this->equal) $this->_objetos[$index]['totalCarriersThirdMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            // Cuarto Mes
            if($this->equal) $this->_objetos[$index]['carriersFourthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //Titulo cuarto mes
            if($this->equal) $this->_objetos[$index]['titleFourthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //Totales cuarto mes
            if($this->equal) $this->_objetos[$index]['totalCarriersFourthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            // Quinto Mes
            if($this->equal) $this->_objetos[$index]['carriersFifthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            // Titulo quinto mes
            if($this->equal) $this->_objetos[$index]['titleFifthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            //Totales quinto mes
            if($this->equal) $this->_objetos[$index]['totalCarriersFifthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            // Sexto Mes
            if($this->equal) $this->_objetos[$index]['carriersSixthMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            // Titulo sexto mes
            if($this->equal) $this->_objetos[$index]['titleSixthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            //Totales sexto mes
            if($this->equal) $this->_objetos[$index]['totalCarriersSixthMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            // Septimo Mes
            if($this->equal) $this->_objetos[$index]['carriersSeventhMonth']=$this->_getCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            // Titulo septimo mes
            if($this->equal) $this->_objetos[$index]['titleSeventhMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            //Totales septimo mes
            if($this->equal) $this->_objetos[$index]['totalCarriersSeventhMonth']=$this->_getTotalCarriers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            //Itero la fecha
            $startDateTemp=DateManagement::firstDayNextMonth($startDateTemp);
            $index+=1;
        }
    }

    /**
     *
     */
    private function _getHeader($type)
    {
        if($type===true) $array=array('Ranking','Operador','Vendedor');
        if($type===false) $array=array('Vendedor','Operador','Ranking');
        if($type===null)
        {
            $array[]='Vminutes';
            $array[]='Vrevenue';
            $array[]='Vmargin';
            $array[]='Cminutes';
            $array[]='Ccost';
            $array[]='Cmargin';
            $array[]='Margen Total';
            $array[]='Posicion Neta';
            if($this->equal) $array[]='';
            if($this->equal) $array[]='Dia Anterior';
            if($this->equal) $array[]='';
            if($this->equal) $array[]='Promedio 7D';
            if($this->equal) $array[]='Acumulado Mes';
            if($this->equal) $array[]='Proyeccion Mes';
            if($this->equal) $array[]='';
            if($this->equal) $array[]='Mes Anterior';
        }
        return $this->header($array,'styleHead');
    }

    /**
     * Retorna la fila con el nombre del manager y la posicion indicada
     * @access protected
     * @param int $pos posicion del manager
     * @param array $value datos del carrier
     * @param boolean $type, true es izquierda, false es derecha
     * @return string la celda construida
     */
    protected function _getNames($pos,$value,$type=true)
    {
        $style=self::colorEstilo($pos);
        if($type) 
            return "<td style='".$style."'>{$pos}</td><td style='".$style."'>{$value['attribute']}</td><td style='".$style."'>".CarrierManagers::getManager($value['id'])."</td>";
        else
            return "<td style='".$style."'>".CarrierManagers::getManager($value['id'])."</td><td style='".$style."'>{$value['attribute']}</td><td style='".$style."'>{$pos}</td>";
    }

    /**
     *
     */
    private function _getRow($index,$index2,$attribute,$phrase,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=null;
        //oreach ($this->_objetos[$index][$index2] as $key => $value)
        $posicion_neta=null;
        foreach ($this->_objetos[$index][$index2] as $key => $value) 
        {
            if($value->$attribute==$phrase['attribute'])
            {
                $c1="<td style='".$style."'>".Yii::app()->format->format_decimal($value->vminutes)."</td>";
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->vrevenue)."</td>";
                $c3="<td style='".$style."'>".Yii::app()->format->format_decimal($value->vmargin)."</td>";
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->cminutes)."</td>";
                $c5="<td style='".$style."'>".Yii::app()->format->format_decimal($value->ccost)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->cmargin)."</td>";
                $c7="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margen_total)."</td>";
                $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
                //$posicion_neta=$value->posicion_neta;
                if(isset($value->posicion_neta))
                {
                  $posicion_neta=$value->posicion_neta;                 
                }else
                {
                  $posicion_neta=null;
                }

            }
            
        }
        if($this->equal)
        {
            foreach ($this->_objetos[$index][$index2.'Yesterday'] as $key => $yesterday)
            {
                if($yesterday->$attribute==$phrase['attribute'])
                {
                    $c9="<td style='".$style."'>".$this->_upOrDown($yesterday->posicion_neta,$posicion_neta)."</td>";
                    $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($yesterday->posicion_neta)."</td>";
                }
                
            }
            foreach ($this->_objetos[$index][$index2.'Average'] as $key => $average)
            {
                if($average->$attribute==$phrase['attribute'])
                {
                    $c11="<td style='".$style."'>".$this->_upOrDown($average->posicion_neta,$posicion_neta)."</td>";
                    $c12="<td style='".$style."'>".Yii::app()->format->format_decimal($average->posicion_neta)."</td>";
                }
            }
            foreach ($this->_objetos[$index][$index2.'Accumulated'] as $key => $accumulated)
            {
                if($accumulated->$attribute==$phrase['attribute'])
                {
                    $c13="<td style='".$style."'>".Yii::app()->format->format_decimal($accumulated->posicion_neta)."</td>";
                }
            }
            if(isset($this->_objetos[$index][$index2."Forecast"][$phrase['attribute']]))
            {
              $c14="<td style='".$style."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Forecast"][$phrase['attribute']])."</td>";

            }
            foreach ($this->_objetos[$index][$index2.'PreviousMonth'] as $key => $PreviousMonth)
            {
                if($PreviousMonth->$attribute==$phrase['attribute'])
                {
                    $c15="<td style='".$style."'>".$this->_upOrDown($PreviousMonth->posicion_neta,$posicion_neta)."</td>";
                    $c16="<td style='".$style."'>".Yii::app()->format->format_decimal($PreviousMonth->posicion_neta)."</td>";
                }
            }
        }
        if($c1==null) $c1="<td style='".$style."'>--</td>";
        if($c2==null) $c2="<td style='".$style."'>--</td>";
        if($c3==null) $c3="<td style='".$style."'>--</td>";
        if($c4==null) $c4="<td style='".$style."'>--</td>";
        if($c5==null) $c5="<td style='".$style."'>--</td>";
        if($c6==null) $c6="<td style='".$style."'>--</td>";
        if($c7==null) $c7="<td style='".$style."'>--</td>";
        if($c8==null) $c8="<td style='".$style."'>--</td>";
        if($c9==null && $this->equal) $c9="<td style='".$style."'>--</td>";
        if($c10==null && $this->equal) $c10="<td style='".$style."'>--</td>";
        if($c11==null && $this->equal) $c11="<td style='".$style."'>--</td>";
        if($c12==null && $this->equal) $c12="<td style='".$style."'>--</td>";
        if($c13==null && $this->equal) $c13="<td style='".$style."'>--</td>";
        if($c14==null && $this->equal) $c14="<td style='".$style."'>--</td>";
        if($c15==null && $this->equal) $c15="<td style='".$style."'>--</td>";
        if($c16==null && $this->equal) $c16="<td style='".$style."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16;
    }

    /**
     * Retorna las celdas con la data que conincida dentro del index consultado y el apellido pasado como parametro
     * @access private
     * @param string $index es el index superior donde se encutra la data
     * @param string $index2 es el index inferior donde se encuentra la data
     * @param string $phrase es el apallido que debe coincidir la data
     * @param string $style el nombre del estilo asignado 
     * @param $type true=minutes,revenue,margin false=margin
     * @return string
     */
    private function _getRowMonths($index,$phrase,$style,$attribute=null)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $posicion_neta=$third=$fourth=$fifth=$sixth=null; 
        
        if(isset($this->_objetos[0][$index.'Forecast'][$phrase]))
          $posicion_neta=$this->_objetos[0][$index.'Forecast'][$phrase];
        else
          $posicion_neta=null;
        
        foreach ($this->_objetos[0][$index.'ThirdMonth'] as $key => $value)
        {
            if($value->carrier == $phrase)
            {
                $c1="<td style='".$style."'>".$this->_upOrDown($value->posicion_neta,$posicion_neta)."</td>";
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FourthMonth'] as $key => $value)
        {
            if($value->carrier == $phrase)
            {
                $c3="<td style='".$style."'>".$this->_upOrDown($value->posicion_neta,$posicion_neta)."</td>";
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FifthMonth'] as $key => $value)
        {
            if($value->carrier == $phrase)
            {
                $c5="<td style='".$style."'>".$this->_upOrDown($value->posicion_neta,$posicion_neta)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SixthMonth'] as $key => $value)
        {
            if($value->carrier == $phrase)
            {
                $c7="<td style='".$style."'>".$this->_upOrDown($value->posicion_neta,$posicion_neta)."</td>";
                $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SeventhMonth'] as $key => $value)
        {
            if($value->carrier == $phrase)
            {
                $c9="<td style='".$style."'>".$this->_upOrDown($value->posicion_neta,$posicion_neta)."</td>";
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
            }
        }
        if($c1==null) $c1="<td style='".$style."'>--</td>";
        if($c2==null) $c2="<td style='".$style."'>--</td>";
        if($c3==null) $c3="<td style='".$style."'>--</td>";
        if($c4==null) $c4="<td style='".$style."'>--</td>";
        if($c5==null) $c5="<td style='".$style."'>--</td>";
        if($c6==null) $c6="<td style='".$style."'>--</td>";
        if($c7==null) $c7="<td style='".$style."'>--</td>";
        if($c8==null) $c8="<td style='".$style."'>--</td>";
        if($c9==null) $c9="<td style='".$style."'>--</td>";
        if($c10==null) $c10="<td style='".$style."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     *
     */
    private function _getRowTotal($index,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=null;
        $total=$this->_objetos[$index]['totalCarriers'];
        if($c1==null) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->vminutes)."</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->vrevenue)."</td>";
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->vmargin)."</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->cminutes)."</td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->ccost)."</td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->cmargin)."</td>";
        if($c7==null) $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->margen_total)."</td>";
        if($c8==null) $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->posicion_neta)."</td>";
        if($c9==null && $this->equal) $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[$index]['totalCarriersYesterday']->posicion_neta,$total->posicion_neta)."</td>";
        if($c10==null && $this->equal) $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index]['totalCarriersYesterday']->posicion_neta)."</td>";
        if($c11==null && $this->equal) $c11="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[$index]['totalCarriersAverage']->posicion_neta,$total->posicion_neta)."</td>";
        if($c12==null && $this->equal) $c12="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index]['totalCarriersAverage']->posicion_neta)."</td>";
        if($c13==null && $this->equal) $c13="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index]['totalCarriersAccumulated']->posicion_neta)."</td>";
        if($c14==null && $this->equal) $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index]['totalCarriersForecast'])."</td>";
        if($c15==null && $this->equal) $c15="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[$index]['totalCarriersPreviousMonth']->posicion_neta,$total->posicion_neta)."</td>";
        if($c16==null && $this->equal) $c16="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index]['totalCarriersPreviousMonth']->posicion_neta)."</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16;
    }
    /**
     *
     */
    private function _getRowTotalMonth($style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        
        
        if(isset($this->_objetos[0]['totalCarriersForecast']))
          $forecast=$this->_objetos[0]['totalCarriersForecast'];
        else 
           $forecast=null; 
         

        $forecast=$this->_objetos[0]['totalCarriersForecast'];
         
        if($c1==null) $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0]['totalCarriersThirdMonth']->posicion_neta,$forecast)."</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0]['totalCarriersThirdMonth']->posicion_neta)."</td>";
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0]['totalCarriersFourthMonth']->posicion_neta,$forecast)."</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0]['totalCarriersFourthMonth']->posicion_neta)."</td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0]['totalCarriersFifthMonth']->posicion_neta,$forecast)."</td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0]['totalCarriersFifthMonth']->posicion_neta)."</td>";
        if($c7==null) $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0]['totalCarriersSixthMonth']->posicion_neta,$forecast)."</td>";
        if($c8==null) $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0]['totalCarriersSixthMonth']->posicion_neta)."</td>";
        if($c9==null) $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[0]['totalCarriersSeventhMonth']->posicion_neta,$forecast)."</td>";
        if($c10==null) $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[0]['totalCarriersSeventhMonth']->posicion_neta)."</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }
}
?>