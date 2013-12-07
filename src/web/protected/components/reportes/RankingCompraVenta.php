<?php
/**
* Creada para generar reporte de compra venta
* @version 3.0
* @package reportes
*/
class RankingCompraVenta extends Reportes
{
    /**
     * @var array
     */
    public $objetos;

    /**
     * @var int
     */
    public $days;

    function __construct()
    {
        $this->objetos=array();
        $this->equal=false;
        $this->_head=array(
            'styleHead'=>'text-align:center;background-color:#295FA0; color:#ffffff; width:10%; height:100%;',
            'styleBodySellers'=>self::colorRankingCV(1),
            'styleBodyBuyers'=>self::colorRankingCV(2),
            'styleBodyConsolidated'=>self::colorRankingCV(3),
            'styleFooter'=>'text-align:center;background-color:#999999; color:#FFFFFF;',
            'styleFooterTotal'=>'text-align:center;background-color:#615E5E; color:#FFFFFF;'
            );
    }
    /**
     * Genera el reporte de compraventa
     * @access public
     * @param date $start fecha de inicio de la consulta
     * @param date $end fecha final para ser consultada
     * @return string $cuerpo con el cuerpo de la tabla(<tbody>)
     */
    public function reporte($start,$end)
    {
        //Especifico la fecha final del mes que estoy consultado
        $this->_getDays($start);
        //Cargo en memoria toda la data a ser impresa en HTML
        $this->_loopData($start,$end);
        
        //Cuento el numero de objetos en el array
        $num=count($this->objetos);
        $last=$num-1;
        if($num==1)
        {
            $span=11;
        }
        else
        {
            $span=3;
        }
        $lastnames=self::getLastNameManagers();
        /*Arrays ordenados*/
        $sorted['sellers']=self::sortByList($lastnames,$this->objetos[$last]['sellers'],'apellido');
        $sorted['buyers']=self::sortByList($lastnames,$this->objetos[$last]['buyers'],'apellido');
        $sorted['consolidated']=self::sortByList($lastnames,$this->objetos[$last]['consolidated'],'apellido');
        
        $body="<table>";
        for($row=1; $row < 55; $row++)
        { 
            $body.="<tr>";   
            for($col=1; $col <= 2+($num*$span); $col++)
            { 
                //Celda vacia superior izquierda
                if(($row==1 || $row==19) && $col==1)
                {
                    $body.="<td colspan='2' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                if($row==37 && $col==1)
                {
                    $body.="<td colspan='4' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Celda vacia superior derecha
                if(($row==1 || $row==19) && $col==2+($num*$span))
                {
                    $body.="<td colspan='2' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                if($row==37 && $col==2+($num*$span))
                {
                    $body.="<td colspan='4' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }

                //Cabecera izquiera superior e inferior de las tablas
                if(($row==2 || $row==16) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Vendedor</td>";
                }
                if(($row==20 || $row==34) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Comprador</td>";
                }
                if(($row==38 || $row==52) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."' colspan='3'>Consolidado (Ventas + Compras)</td>";
                }
                //Cabecera derecha superior e inferior de las tablas
                if(($row==2 || $row==16) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Vendedor</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                if(($row==20 || $row==34) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Comprador</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                if(($row==38 || $row==52) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."' colspan='3'>Consolidado (Ventas + Compras)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera Izquiera de totales
                if(($row==17 || $row==35) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleFooter']."'></td><td style='".$this->_head['styleFooter']."'>Total</td>";
                }
                if($row==53 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleFooter']."'></td><td style='".$this->_head['styleFooter']."' colspan='3'>Total</td>";
                }
                //Cabecera Derecha de totales
                if(($row==17 || $row==35) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."'>Total</td><td style='".$this->_head['styleFooter']."'></td>";
                }
                if($row==53 && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."' colspan='3'>Total</td><td style='".$this->_head['styleFooter']."'></td>";
                }

                //Cabecera de los totales al final de consolidados
                if($row==54 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleFooterTotal']."'></td><td style='".$this->_head['styleFooterTotal']."' colspan='3'>Total</td>";
                }
                if($row==54 && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooterTotal']."' colspan='3'>Total</td><td style='".$this->_head['styleFooterTotal']."'></td>";
                }

                //Nombres de los managers vendedores izquierda
                if($row>2 && $row<16 && $col==1)
                {
                    $pos=$row-2;
                    $body.=$this->_getNames($pos,$sorted['sellers'][$row-3],'styleBodySellers');
                }
                //Nombres de los managers vendedores derecha
                if(($row>2 && $row<16) && $col==2+($num*$span))
                {
                    $pos=$row-2;
                    $body.=$this->_getNames($pos,$sorted['sellers'][$row-3],'styleBodySellers',false);
                }

                //Nombres de los managers compradores izquierda
                if($row>20 && $row<34 && $col==1)
                {
                    $pos=$row-20;
                    $body.=$this->_getNames($pos,$sorted['buyers'][$row-21],'styleBodyBuyers');
                }
                //Nombres de los managers compradores derecha
                if(($row>20 && $row<34) && $col==2+($num*$span))
                {
                    $pos=$row-20;
                    $body.=$this->_getNames($pos,$sorted['buyers'][$row-21],'styleBodyBuyers',false);
                }

                //Nombres de los managers compradores/vendedores izquierda
                if(($row>38 && $row<52) && $col==1)
                {
                    $pos=$row-38;
                    $body.=$this->_getNamesConsolidated($pos,$sorted['consolidated'][$row-39],'styleBodyConsolidated');
                }
                //Nombres de los managers compradores/vendedores derecha
                if(($row>38 && $row<52) && $col==2+($num*$span))
                {
                    $pos=$row-38;
                    $body.=$this->_getNamesConsolidated($pos,$sorted['consolidated'][$row-39],'styleBodyConsolidated',false);
                }
                
                //Titulo de cada mes para diferenciar la data compradores/vendedores
                if(($row==1 || $row==19) && self::validColumn(2,$col,$num,$span))
                {
                    $body.="<td colspan='".$span."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->objetos[self::validIndex(2,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Titulo de cada mes para diferenciar la data Consolidado
                if($row==37 && self::validColumn(2,$col,$num,$span))
                {
                    $nuevospan=$span-2;
                    $body.="<td colspan='".$nuevospan."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->objetos[self::validIndex(2,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Escribe los headers de las columnas de las tablas
                if(($row==2 || $row==16 || $row==20 || $row==34) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHeaderManages(true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Escribe los headers de las columnas de la tabla consolidada
                if(($row==38 || $row==52) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHeaderManages(false);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }

                //Data de vendedores
                if(($row>2 && $row<16) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getRow(self::validIndex(2,$col,$span),'sellers',$sorted['sellers'][$row-3],'styleBodySellers',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                if($row==17 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotal(self::validIndex(2,$col,$span),'totalVendors','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                
                //Data de compradores
                if(($row>20 && $row<34) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getRow(self::validIndex(2,$col,$span),'buyers',$sorted['buyers'][$row-21],'styleBodyBuyers',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                if($row==35 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotal(self::validIndex(2,$col,$span),'totalBuyers','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }

                //Data de consolidada
                if(($row>38 && $row<52) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getRow(self::validIndex(2,$col,$span),'consolidated',$sorted['consolidated'][$row-39],'styleBodyConsolidated',false);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Data total consolidada
                if($row==53 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotal(self::validIndex(2,$col,$span),'totalConsolidated','styleFooter',false);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Data total de total ;)
                if($row==54 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotalMargen(self::validIndex(2,$col,$span),'totalMargen','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
            }           
            $body.="</tr>";            
        }
        $body.="</table>";
        return $body;
    }

    /**
     * Encargado de hacer el loop de busqueda de base de datos retornando un array con el numero total de datos
     * @access private
     * @param date $star fecha de inicio
     * @param date $end fecha fin
     * @return void
     */
    private function _loopData($start,$end)
    {
        $lastnames=self::getLastNameManagers();
        //verifico las fechas
        $array=self::valDates($start,$end);
        $startDateTemp=$startDate=$array['startDate'];
        $yesterday=Utility::calculateDate('-1',$startDateTemp);
        $sevenDaysAgo=Utility::calculateDate('-7',$yesterday);
        $firstDay=Utility::getDayOne($start);
        $endingDateTemp=$endingDate=$array['endingDate'];
        $this->equal=$array['equal'];

        $arrayStartTemp=null;
        $index=0;
        while (self::isLower($startDateTemp,$endingDate))
        {
            $arrayStartTemp=explode('-',$startDateTemp);
            $endingDateTemp=self::maxDate($arrayStartTemp[0]."-".$arrayStartTemp[1]."-".self::howManyDays($startDateTemp),$endingDate);
            //El titulo que va a llevar la seccion
            $this->objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            /*Guardo todos los vendedores*/
            $this->objetos[$index]['sellers']=$this->_getManagers($startDateTemp,$endingDateTemp,true);
            /*Guardo los totales de los vendedores*/
            $this->objetos[$index]['totalVendors']=$this->_getTotalManagers($startDateTemp,$endingDateTemp,true);
            /*El total del margen por vendedor mes anterior*/
            if($this->equal) $this->objetos[$index]['sellersPreviousMonth']=$this->_getManagers($this->leastOneMonth($startDate)['firstday'],$this->leastOneMonth($startDate)['lastday'],true);
            /*Guardo los totales de los vendedores*/
            if($this->equal) $this->objetos[$index]['totalVendorsPreviousMonth']=$this->_getTotalManagers($this->leastOneMonth($startDate)['firstday'],$this->leastOneMonth($startDate)['lastday'],true);
            /*Guardo los valores de vendedores del dia anterior*/
            if($this->equal) $this->objetos[$index]['sellersYesterday']=$this->_getManagers($yesterday,$yesterday,true);
            /*Guardo los totales de los vendedores del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalVendorsYesterday']=$this->_getTotalManagers($yesterday,$yesterday,true);
            /*Guardo el promedio por vendedores de 7 dias atras*/
            if($this->equal) $this->objetos[$index]['sellersAverage']=$this->_getAvgMarginManagers($sevenDaysAgo,$yesterday,true);
            /*Guardo el promedio total*/
            if($this->equal) $this->objetos[$index]['totalVendorsAverage']=$this->_getTotalAvgMarginManagers($sevenDaysAgo,$yesterday,true);
            /*Guardo el acumulado que lleva hasta el dia en consulta*/
            if($this->equal) $this->objetos[$index]['sellersAccumulated']=$this->_getManagers($firstDay,$startDate,true);
            /*Guardo el total de los acumulados hasta el dia de la consulta*/
            if($this->equal) $this->objetos[$index]['totalVendorsAccumulated']=$this->_getTotalManagers($firstDay,$startDate,true);
            /*Guardo los pronosticos de los vendedores*/
            if($this->equal) $this->objetos[$index]['sellersForecast']=$this->_closeOfTheMonth($lastnames,$index,'sellersAverage','sellersAccumulated');
            /*guardo los totales de cierre de mes*/
            if($this->equal) $this->objetos[$index]['totalVendorsClose']=array_sum($this->objetos[$index]['sellersForecast']);


            /*Guardo los totales de los compradores*/
            $this->objetos[$index]['buyers']=$this->_getManagers($startDateTemp,$endingDateTemp,false);
            /*Guardo los totales de todos los compradores*/
            $this->objetos[$index]['totalBuyers']=$this->_getTotalManagers($startDateTemp,$endingDateTemp,false);
            /*El total del margen por compradores mes anterior*/
            if($this->equal) $this->objetos[$index]['buyersPreviousMonth']=$this->_getManagers($this->leastOneMonth($startDate)['firstday'],$this->leastOneMonth($startDate)['lastday'],false);
            /*Guardo los totales de los compradores*/
            if($this->equal) $this->objetos[$index]['totalBuyersPreviousMonth']=$this->_getTotalManagers($this->leastOneMonth($startDate)['firstday'],$this->leastOneMonth($startDate)['lastday'],false);
            /*Guardo los valores de compradores del dia anterior*/
            if($this->equal) $this->objetos[$index]['buyersYesterday']=$this->_getManagers($yesterday,$yesterday,false);
            /*Guardo los totales de los compradores del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalBuyersYesterday']=$this->_getTotalManagers($yesterday,$yesterday,false);
            /*Guardo el promedio por compradores de 7 dias atras*/
            if($this->equal) $this->objetos[$index]['buyersAverage']=$this->_getAvgMarginManagers($sevenDaysAgo,$yesterday,false);
            /*Guardo el promedio total*/
            if($this->equal) $this->objetos[$index]['totalBuyersAverage']=$this->_getTotalAvgMarginManagers($sevenDaysAgo,$yesterday,false);
            /*Guardo el acumulado que lleva hasta el dia en consulta*/
            if($this->equal) $this->objetos[$index]['buyersAccumulated']=$this->_getManagers($firstDay,$startDate,false);
            /*Guardo el total de los acumulados hasta el dia de la consulta*/
            if($this->equal) $this->objetos[$index]['totalBuyersAccumulated']=$this->_getTotalManagers($firstDay,$startDate,false);
            /*Guardo los pronosticos de los vendedores*/
            if($this->equal) $this->objetos[$index]['buyersForecast']=$this->_closeOfTheMonth($lastnames,$index,'buyersAverage','buyersAccumulated');
            /*guardo los totales de cierre de mes*/
            if($this->equal) $this->objetos[$index]['totalBuyersClose']=array_sum($this->objetos[$index]['buyersForecast']);


            /*guardo los totales de los compradores y vendedores consolidado*/
            $this->objetos[$index]['consolidated']=$this->_getConsolidados($startDateTemp,$endingDateTemp);
            /*Guardo el total de los consolidados*/
            $this->objetos[$index]['totalConsolidated']=$this->_getTotalConsolidado($startDateTemp,$endingDateTemp);
            /*Guardo el margen total de ese periodo*/
            $this->objetos[$index]['totalMargen']=$this->_getTotalMargen($startDateTemp,$endingDateTemp);
            /*guardo los totales de los compradores y vendedores consolidado*/
            $this->objetos[$index]['consolidatedPreviousMonth']=$this->_getConsolidados($this->leastOneMonth($startDate)['firstday'],$this->leastOneMonth($startDate)['lastday']);
            /*Guardo el total de los consolidados*/
            $this->objetos[$index]['totalConsolidatedPreviousMonth']=$this->_getTotalConsolidado($this->leastOneMonth($startDate)['firstday'],$this->leastOneMonth($startDate)['lastday']);
            /*Guardo el margen total de ese periodo*/
            $this->objetos[$index]['totalMargenPreviousMonth']=$this->_getTotalMargen($this->leastOneMonth($startDate)['firstday'],$this->leastOneMonth($startDate)['lastday']);
            /*guardo los totales de los compradores y vendedores consolidado del dia de ayer*/
            if($this->equal) $this->objetos[$index]['consolidatedYesterday']=$this->_getConsolidados($yesterday,$yesterday);
            /*Guardo el total de los consolidados del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalConsolidatedYesterday']=$this->_getTotalConsolidado($yesterday,$yesterday);
            /*Guardo el margen total de ese periodo del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalMargenYesterday']=$this->_getTotalMargen($yesterday,$yesterday);
            /*Guardo el promedio de los margenes consolidados*/
            if($this->equal) $this->objetos[$index]['consolidatedAverage']=$this->_getAvgConsolidatedManagers($sevenDaysAgo,$yesterday);
            /*Guardo el proomedio total de los margenes consolidados*/
            if($this->equal) $this->objetos[$index]['totalConsolidatedAverage']=$this->_getTotalAvgConsolidatedManagers($sevenDaysAgo,$yesterday);
            /*Guardo el promedio del margen total*/
            if($this->equal) $this->objetos[$index]['totalMargenAverage']=$this->_getAvgTotalMargin($sevenDaysAgo,$yesterday);
            /*guardo los totales de los compradores y vendedores consolidado*/
            if($this->equal) $this->objetos[$index]['consolidatedAccumulated']=$this->_getConsolidados($firstDay,$startDate);
            /*guardo el total de los acumulados hasta la fecha consultada*/
            if($this->equal) $this->objetos[$index]['totalConsolidatedAccumulated']=$this->_getTotalConsolidado($firstDay,$startDate);
            /*Guardo el total de los margenes acumulados hasta esa fecha*/
            if($this->equal) $this->objetos[$index]['totalMargenAccumulated']=$this->_getTotalMargen($firstDay,$startDate);
            /*Guardo los pronosticos de los vendedores*/
            if($this->equal) $this->objetos[$index]['consolidatedForecast']=$this->_closeOfTheMonth($lastnames,$index,'consolidatedAverage','consolidatedAccumulated');
            /*guardo los totales de cierre de mes*/
            if($this->equal) $this->objetos[$index]['totalConsolidatedClose']=array_sum($this->objetos[$index]['consolidatedForecast']);

            /*Itero la fecha*/
            $startDateTemp=self::firstDayNextMonth($startDateTemp);
            $index+=1;
        }
    }

    /**
     * Obtiene los datos de los managers en un periodo de tiempo
     * @access private
     * @param date $startDate fecha de inicio de consulta
     * @param date $edingDate fecha fin de la consulta
     * @param boolean $type si es true es vendedor, si es false es comprador
     * @return array
     */
    private function _getManagers($startDate,$endingDate,$type)
    {
        $manager="id_carrier_customer";
        if($type==false)
        {
            $manager="id_carrier_supplier";
        }
        $sql="SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
              FROM(SELECT {$manager}, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance 
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$manager})b,
                   managers m,
                   carrier_managers cm
              WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier AND cm.end_date IS NULL
              GROUP BY m.name, m.lastname
              ORDER BY margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Obtiene el total de los managers en un periodo de tiempo
     * @access private
     * @param date $startDate fecha de inicio de consulta
     * @param date $edingDate fecha fin de la consulta
     * @param boolean $type si es true es vendedor, si es false es comprador
     * @return CActiveRecord 
     */
    private function _getTotalManagers($startDate,$endingDate,$type)
    {
        $manager="id_carrier_customer";
        if($type==false)
        {
            $manager="id_carrier_supplier";
        }
        $sql="SELECT SUM(d.minutes) AS minutes, SUM(d.revenue) AS revenue, SUM(d.margin) AS margin
              FROM (SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
                    FROM (SELECT {$manager}, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                          GROUP BY {$manager})b, managers m, carrier_managers cm
                    WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier AND cm.end_date IS NULL
                    GROUP BY m.name, m.lastname
                    ORDER BY margin DESC) d";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Metodo encargado de conseguir los datos de los consolidados
     * @access private
     * @param date $startDate fecha de inicio que se va a consultar
     * @param date $endingDate es la fecha final a ser consultada.
     * @return array
     */
    private function _getConsolidados($startDate,$edingDate)
    {
        $sql="SELECT m.name AS nombre, m.lastname AS apellido, SUM(cs.margin) AS margin
              FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY id_carrier_customer
                   UNION
                   SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance 
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY id_carrier_supplier)cs,
                   managers m,
                   carrier_managers cm
              WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier AND cm.end_date IS NULL
              GROUP BY m.name, m.lastname
              ORDER BY margin DESC";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * metodo que genera la fila con el total de consolidados
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $edingDate fecha fin de la consulta
     * @return CActiveRecord
     */
    private function _getTotalConsolidado($startDate,$edingDate)
    {
         $sql="SELECT SUM(d.margin) AS margin
               FROM (SELECT m.name AS nombre, m.lastname AS apellido, SUM(cs.margin) AS margin
                     FROM (SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                           FROM balance
                           WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                           GROUP BY id_carrier_customer
                           UNION
                           SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                           FROM balance
                           WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                           GROUP BY id_carrier_supplier)cs, managers m, carrier_managers cm
                     WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier AND cm.end_date IS NULL
                     GROUP BY m.name, m.lastname
                     ORDER BY margin DESC) d";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Obtiene los margenes de cada manager en promedio por dia entre el rango de fechas pasado
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $type true=vendedor, false=comprador
     * @return array
     */
    private function _getAvgMarginManagers($startDate,$endingDate,$type)
    {
        $manager="id_carrier_customer";
        if($type==false)
        {
            $manager="id_carrier_supplier";
        }
        $sql="SELECT d.nombre, d.apellido, AVG(d.margin) AS margin
              FROM(SELECT m.name AS nombre, m.lastname AS apellido, b.date_balance AS date_balance, SUM(b.margin) AS margin
                   FROM(SELECT {$manager},date_balance, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                        FROM balance
                        WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                        GROUP BY {$manager}, date_balance
                        ORDER BY date_balance)b, managers m, carrier_managers cm
                   WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier AND cm.end_date IS NULL
                   GROUP BY m.name, m.lastname, b.date_balance
                   ORDER BY margin DESC, date_balance) d
              GROUP BY d.nombre, d.apellido";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Obtiene el total del promedio de managers
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $edingDate fecha fin de la consulta
     * @param boolean $type true=vendedor, false=comprador
     * @return CActiveRecord
     */
    private function _getTotalAvgMarginManagers($startDate,$edingDate,$type)
    {
        $manager="id_carrier_customer";
        if($type==false)
        {
            $manager="id_carrier_supplier";
        }
        $sql="SELECT SUM(t.margin) AS margin
              FROM (SELECT d.nombre, d.apellido, AVG(d.margin) AS margin
                    FROM (SELECT m.name AS nombre, m.lastname AS apellido, b.date_balance AS date_balance, SUM(b.margin) AS margin
                          FROM (SELECT {$manager},date_balance, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                FROM balance
                                WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                                GROUP BY {$manager}, date_balance
                                ORDER BY date_balance)b, managers m, carrier_managers cm
                          WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier AND cm.end_date IS NULL
                          GROUP BY m.name, m.lastname, b.date_balance
                          ORDER BY margin DESC, date_balance) d
                    GROUP BY d.nombre, d.apellido)t";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Obtiene los margenes consolidades de cada manager primediado entre el rango de fechas pasado como parametro
     * @access private
     * @param date $startDate fecha de inicio
     * @param date $endingDate fecha fin de la consulta
     * @return array
     */
    private function _getAvgConsolidatedManagers($startDate,$endingDate)
    {
        $sql="SELECT d.nombre, d.apellido, AVG(d.margin) AS margin
              FROM (SELECT m.name AS nombre, m.lastname AS apellido, cs.date_balance, SUM(cs.margin) AS margin
                    FROM (SELECT id_carrier_customer AS id, date_balance, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                          GROUP BY id_carrier_customer, date_balance
                          UNION
                          SELECT id_carrier_supplier AS id, date_balance, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                          GROUP BY id_carrier_supplier, date_balance)cs, managers m, carrier_managers cm
                    WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier AND cm.end_date IS NULL
                    GROUP BY m.name, m.lastname, cs.date_balance
                    ORDER BY margin DESC) d
              GROUP BY d.nombre, d.apellido";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Obtiene el promedio total de los margenes de un periodo especifico
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @return CActiverecord
     */
    private function _getTotalAvgConsolidatedManagers($startDate,$endingDate)
    {
        $sql="SELECT SUM(t.margin) AS margin
              FROM (SELECT d.nombre, d.apellido, AVG(d.margin) AS margin
                    FROM (SELECT m.name AS nombre, m.lastname AS apellido, cs.date_balance, SUM(cs.margin) AS margin
                          FROM (SELECT id_carrier_customer AS id, date_balance, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                FROM balance
                                WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                                GROUP BY id_carrier_customer, date_balance
                                UNION
                                SELECT id_carrier_supplier AS id, date_balance, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                FROM balance
                                WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                                GROUP BY id_carrier_supplier, date_balance)cs, managers m, carrier_managers cm
                          WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier AND cm.end_date IS NULL
                          GROUP BY m.name, m.lastname, cs.date_balance
                          ORDER BY margin DESC) d
                    GROUP BY d.nombre, d.apellido) t";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Metodo que retorna el total de margen de un periodo especifico
     * @access private
     * @param date $startDate
     * @param date $edingDate
     * @return CActiveRecord
     */
    private function _getTotalMargen($startDate,$edingDate)
    {
        $sql="SELECT CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
              FROM balance
              WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')";

        return Balance::model()->findBySql($sql);
    }

    /**
     * Encargado de obtener el promedio total de los margenes totales de unn rango de fechas
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $edingDate fecha fin de la consulta
     * @return CActiveRecord
     */
    private function _getAvgTotalMargin($startDate,$endingDate)
    {
        $sql="SELECT AVG(b.margin) AS margin
              FROM (SELECT CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, date_balance
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                    GROUP BY date_balance) b";
        return Balance::model()->findBySql($sql);
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
    private function _getRow($index,$index2,$phrase,$style,$type=true)
    {
        $uno=$dos=$tres=$cuatro=$cinco=$seis=$siete=$ocho=$nueve=$diez=$once=null;
        $margin=$previous=$average=$previousMonth=null;
        foreach ($this->objetos[$index][$index2] as $key => $value)
        {
            if($value->apellido == $phrase)
            {               
                if($type==true) $uno="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                if($type==true) $dos="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $tres="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $margin=$value->margin;
            }
        }
        if($this->equal)
        {
            foreach ($this->objetos[$index][$index2.'Yesterday'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $cinco="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                    $previous=$value->margin;
                }
            }
            $cuatro="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previous,$margin)."</td>";
            foreach ($this->objetos[$index][$index2.'Average'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $siete="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                    $average=$value->margin;
                }
            }
            $seis="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average,$margin)."</td>";
            foreach ($this->objetos[$index][$index2.'Accumulated'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $ocho="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                }
            }
            $nueve="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->objetos[$index][$index2.'Forecast'][$phrase])."</td>";
            foreach ($this->objetos[$index][$index2.'PreviousMonth'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $once="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                    $previousMonth=$value->margin;
                }
            }
            $diez="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previousMonth,$this->objetos[$index][$index2.'Forecast'][$phrase])."</td>";
        }
        
        if($type==true)
        {
            if($uno==null) $uno="<td style='".$this->_head[$style]."'>--<td>";
            if($dos==null) $dos="<td style='".$this->_head[$style]."'>--</td>";
        }
        if($tres==null) $tres="<td style='".$this->_head[$style]."'>--</td>";
        if($this->equal)
        {
            if($cuatro==null) $cuatro="<td style='".$this->_head[$style]."'></td>";
            if($cinco==null) $cinco="<td style='".$this->_head[$style]."'>--</td>";
            if($seis==null) $seis="<td style='".$this->_head[$style]."'></td>";
            if($siete==null) $siete="<td style='".$this->_head[$style]."'>--</td>";
            if($ocho==null) $ocho="<td style='".$this->_head[$style]."'>--</td>";
            if($nueve==null) $nueve="<td style='".$this->_head[$style]."'>--</td>";
            if($diez==null) $siete="<td style='".$this->_head[$style]."'></td>";
            if($once==null) $siete="<td style='".$this->_head[$style]."'>--</td>";
        } 
        $body=$uno.$dos.$tres.$cuatro.$cinco.$seis.$siete.$ocho.$nueve.$diez.$once;
        return $body;
    }

    /**
     * Retorna una tabla con los totales de los objetos pasados como parametros
     * @access private
     * @param CActiveRecord $total es el objeto que totaliza los que cumplen la condicion
     * @return string
     */
    private function _getHtmlTotal($index,$index2,$style,$type=true)
    {
        $total=$this->objetos[$index][$index2];
        if($this->equal) $yesterday=$this->objetos[$index][$index2.'Yesterday'];
        if($this->equal) $average=$this->objetos[$index][$index2.'Average'];
        if($this->equal) $accumulated=$this->objetos[$index][$index2.'Accumulated'];
        if($this->equal) $close=$this->objetos[$index][$index2.'Close'];
        if($this->equal) $previousMonth=$this->objetos[$index][$index2.'PreviousMonth'];

        $body="";
        if($type==true) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->minutes)."</td>";
        if($type==true) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->revenue)."</td>";
        $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday->margin,$total->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average->margin,$total->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($close)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previousMonth->margin,$close)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($previousMonth->margin)."</td>";
        return $body;
    }

    /**
     * Retorna el html del total del margen
     * @access private
     * @param string $index
     * @param string $index2
     * @param string $style
     * @return string
     */
    private function _getHtmlTotalMargen($index,$index2,$style)
    {
        $data=$this->objetos[$index][$index2];
        if($this->equal) $yesterday=$this->objetos[$index][$index2.'Yesterday'];
        if($this->equal) $average=$this->objetos[$index][$index2.'Average'];
        if($this->equal) $accumulated=$this->objetos[$index][$index2.'Accumulated'];
        if($this->equal) $previousMonth=$this->objetos[$index][$index2.'PreviousMonth'];
        $body="";
        $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($data->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday->margin,$data->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average->margin,$data->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated->margin+$this->_forecast($average->margin))."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previousMonth->margin,$accumulated->margin+$this->_forecast($average->margin))."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($previousMonth->margin)."</td>";
        return $body;
    }

    /**
     * Determina el numero de dias que hay desde la fecha pasada hasta el fin del mes
     * @access private
     * @param date $date
     * @return void
     */
    private function _getDays($date)
    {
        $arrayDate=explode('-',$date);
        $newDate=$arrayDate[0]."-".$arrayDate[1]."-".self::howManyDays($date);
        $this->days=self::howManyDaysBetween($date,$newDate);
    }

    /**
     * Retorna el valor pasado como parametro multiplicado por la variable days
     * @access private
     * @param float $data
     * @return float
     */
    private function _forecast($data)
    {
        return ($data*$this->days);
    }

    /**
     * calcula el pronostico de cierre
     * @access private
     * @param array $phrase lista de elementos para iterar
     * @param string $index es la ubicacion dentro del array $this->objetos
     * @param string $average es la ubicacion del promedio dentro del array $this->objetos[$index]
     * @param string $accumulated es la ubicacion del acumulado dentro del array $this->objetos[$index]
     * @return array un array con los datos calculados
     */
    private function _closeOfTheMonth($phrase,$index,$average,$accumulated)
    {
        $array=array();
        foreach ($phrase as $key => $lastname)
        {
            foreach ($this->objetos[$index][$average] as $key => $avg)
            {
                if($avg->apellido==$lastname)
                {
                    foreach ($this->objetos[$index][$accumulated] as $key => $acum)
                    {
                        if($acum->apellido==$avg->apellido)
                        {
                            $array[$acum->apellido]=$acum->margin+$this->_forecast($avg->margin);
                        }
                    }
                }

            }
        }
        foreach ($phrase as $key => $value)
        {
            if(!isset($array[$value]))
            {
                $array[$value]=0;
            }
        }
        return $array;
    }

    /**
     * Metodo encargado de colocar un simbolo de subida o bajada en el html
     * @access private
     * @param int $previous es el valor anterior
     * @param int $actual es el valor actual a revisar
     * @return string
     */
    private function _upOrDown($previous,$actual)
    {
        if($previous!=null || $previous!="")
        {
            if($actual>$previous)
            {
                return "<font style='color:green;'>&#9650;</font>";
            }
            elseif($actual<$previous)
            {
                return "<font style='color:red;'>&#9660;</font>";
            }
            else
            {
                return "<font>=</font>";
            }
        }
        return "--";
    }

    /**
     * Retorna la cabecera de la data de managers
     * @access private
     * @param boolean $type true = compradores/vendedores, false = consolidados
     * @return string celdas construidas
     */
    private function _getHeaderManages($type=true)
    {
        $body="";
        if($type==true) $body.="<td style='".$this->_head['styleHead']."'>Minutes</td>";
        if($type==true) $body.="<td style='".$this->_head['styleHead']."'>Revenue</td>";
        $body.="<td style='".$this->_head['styleHead']."'>Margin</td>";
        if($this->equal) $body.="<td style='".$this->_head['styleHead']."'></td><td style='".$this->_head['styleHead']."'>Dia Anterior</td><td style='".$this->_head['styleHead']."'></td><td style='".$this->_head['styleHead']."'>Promedio 7D</td><td style='".$this->_head['styleHead']."'>Acumulado Mes</td><td style='".$this->_head['styleHead']."'>Proyeccion Mes</td><td style='".$this->_head['styleHead']."'></td><td style='".$this->_head['styleHead']."'>Mes Anterior</td>";
        return $body;
    }

    /**
     * Retorna la fila con el nombre del manager y la posicion indicada
     * @access private
     * @param int $pos posicion del manager
     * @param string $phrase es el nombre del manager
     * @param string $style es el estilo asignado al tipo de manager
     * @param boolean $type, true es izquierda, false es derecha
     * @return string la celda construida
     */
    private function _getNamesConsolidated($pos,$phrase,$style,$type=true)
    {
        if($type) 
            return "<td style='".$this->_head[$style]."'>{$pos}</td><td style='".$this->_head[$style]."' colspan='3'>{$phrase}</td>";
        else
            return "<td style='".$this->_head[$style]."' colspan='3'>{$phrase}</td><td style='".$this->_head[$style]."'>{$pos}</td>";
    }
}
?>