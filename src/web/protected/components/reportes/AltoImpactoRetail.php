<?php
/**
* @package reportes
* @version 2.0
*/
class AltoImpactoRetail extends Reportes
{
    private $_totals;
    function __construct()
    {
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

        //Cuento el numero de objetos dentro del array
        $num=count($this->_objetos);
        $last=$num-1;

        //Sumo los carriers para saber cuantas filas voy a generar
        $numCRP=count($this->_objetos[$last]['customersRP'])+2;
        $numDRP=count($this->_objetos[$last]['destinationsRP'])+6;
        $numCRPRO=count($this->_objetos[$last]['customersRPRO'])+6;
        $numDRPRO=count($this->_objetos[$last]['destinationsRPRO'])+6;
        $total=$numCRP+1/*+$numDRP+$numCRPRO+$numDRPRO*/;

        //establezco el orden que va a regir las tablas
        $sorted['customersRP']=self::sort($this->_objetos[$last]['customersRP'],'carrier');
        $sorted['destinationsRP']=self::sort($this->_objetos[$last]['destinationsRP'],'destination');
        $sorted['customersRPRO']=self::sort($this->_objetos[$last]['customersRPRO'],'carrier');
        $sorted['destinationsRPRO']=self::sort($this->_objetos[$last]['destinationsRPRO'],'destination');

        //este numero es por la cantidad de columnas en los carriers
        $span=18;
        $spanDes=13;
        //este numero sale de la cantidad de columnas que identifican el registro, ranking, carrier/destino
        $before=2;
        $body="<table>";
        for($row=1; $row<=$total; $row++)
        {
            $body.="<tr>";
            //$body.="<td>".$row."</td>";
            for($col=1; $col<=$before+($num*$span); $col++)
            {
                //Celdas vacias izquierda y derecha en la tabla
                if(($row==1 
                 /*|| $row==$numCRP+5
                 || $row==$numCRP+$numDRP+5
                 || $row==$numCRP+$numDRP+$numCRPRO+5
                 || $row==$numCRP+$numDRP+$numCRPRO+$numDRPRO+5*/) && ($col==1 || $col==$before+($num*$span)))
                {
                    $body.="<td colspan='{$before}' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Titulo de cada mes para diferenciar la data 
                if(($row==1 
                 /*|| $row==$numCRP+5
                 || $row==$numCRP+$numDRP+5
                 || $row==$numCRP+$numDRP+$numCRPRO+5
                 || $row==$numCRP+$numDRP+$numCRPRO+$numDRPRO+5*/) && self::validColumn($before,$col,$num,$span))
                {
                    $body.="<td colspan='".$span."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex($before,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Titulo de meses anteriores
                if($row==1 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.="<td colspan='10' style='text-align:center;background-color:#BFBEBE;color:#FFFFFF;'>Meses Anteriores</td>";
                }
                //Cabecera superior izquierda de los clientes RP y R-E
                if($row==2 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Clientes RP (+1)</td>";
                }
                //Cabecera con las columnas
                if($row==2 && self::validColumn($before,$col,$num,$span))
                {
                    $body.=$this->_getHeaderCarriers();
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //titulo de los meses
                if($row==2 && $col==$before+($num*$span))
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
                //Cabecera superior derecha de los clientes RP y R-E
                if($row==2 && $col==$before+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Clientes RP (+1)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                //Nombres de los carriers izquierda RP y R-E
                if(($row>2  && $row<=$numCRP) && $col==1)
                {
                    $pos=$row-2;
                    $body.=$this->_getNames($pos,$sorted['customersRP'][$row-3],true);
                }
                //Nombres de los carriers derecha RP y R-E
                if(($row>2  && $row<=$numCRP) && $col==$before+($num*$span))
                {
                    $pos=$row-2;
                    $body.=$this->_getNames($pos,$sorted['customersRP'][$row-3],true);
                }
                //data de los clientes RP y R-E
                if(($row>2  && $row<=$numCRP) && self::validColumn($before,$col,$num,$span))
                {
                    $pos=$row-2;
                    $body.=$this->_getRow(self::validIndex($before,$col,$span),'customersRP','carrier',$sorted['customersRP'][$row-3],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //data de los clientes RP y R-E meses anteriores
                if(($row>2  && $row<=$numCRP) && $col==$before+($num*$span))
                {
                    $pos=$row-2;
                    if($this->equal) $body.=$this->_getRowMonth('customersRP','carrier',$sorted['customersRP'][$row-3],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Celdas izquierda de total
                if($row==$numCRP+1 && $col==1)
                {
                    $body.="<td></td><td style='text-align:center;background-color:#999999;color:#FFFFFF;'>TOTAL</td>";
                }
                //Totales de Clientes RP y R-E
                if($row==$numCRP+1 && self::validColumn($before,$col,$num,$span))
                {
                    $body.=$this->_getRowTotal(self::validIndex($before,$col,$span),'totalcustomersRP','styleFooter',true);
                }
                //Celdas derecha de total
                if($row==$numCRP+1 && $col==$before+($num*$span))
                {
                    $body.="<td style='text-align:center;background-color:#999999;color:#FFFFFF;'>TOTAL</td><td></td>";
                }
                //Totales altos de meses anteriores
                if($row==$numCRP+1 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowTotalMonth('totalcustomersRP','styleFooter',true);
                }
            }
            $body.="</tr>";
        }
        $body.="</table>";
        return $body;
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
            //El titulo de tercer mes
            $this->_objetos[$index]['titleThirdMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            //El titulo de cuerto mes
            $this->_objetos[$index]['titleFourthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //El titulo de quinto mes
            $this->_objetos[$index]['titleFifthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            //El titulo de sexto mes
            $this->_objetos[$index]['titleSixthMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            //El titulo de septimo mes
            $this->_objetos[$index]['titleSeventhMonth']=self::reportTitle(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            //Clientes RP y R-E con mas de un dollar de margen
            $this->_objetos[$index]['customersRP']=$this->_getCustomers($startDateTemp,$endingDateTemp,'RP',true);
            //Clientes RP y R-E con mas de un dollar de margen del dia anterior
            if($this->equal) $this->_objetos[$index]['customersRPYesterday']=$this->_getCustomers($yesterday,$yesterday,'RP',false);
            //Promedio de clientes RP y R-E de los ultimos 7 dias
            if($this->equal) $this->_objetos[$index]['customersRPAverage']=$this->_getAvgCustomers($sevenDaysAgo,$yesterday,'RP');
            //Margen acumulado de los clientes RP y R-E en lo que va de mes
            if($this->equal) $this->_objetos[$index]['customersRPAccumulated']=$this->_getCustomers($firstDay,$startDate,'RP',false);
            //Guardo la proyeccion para el final del mes
            if($this->equal) $this->_objetos[$index]['customersRPForecast']=$this->_closeOfTheMonth(null,$index,'customersRPAverage','customersRPAccumulated','carrier');
            //Guardo el margen del mes anterior de clientes RP y R-E
            if($this->equal) $this->_objetos[$index]['customersRPPreviousMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RP',false);
            //Guardo el margen del tercer mes de clientes RP y R-E
            if($this->equal) $this->_objetos[$index]['customersRPThirdMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RP',false);
            //Guardo el margen del cuarto mes de clientes RP y R-E
            if($this->equal) $this->_objetos[$index]['customersRPFourthMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            //Guardo el margen del quinto mes de clientes RP y R-E
            if($this->equal) $this->_objetos[$index]['customersRPFifthMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            //Guardo el margen del sexto mes de clientes RP y R-E
            if($this->equal) $this->_objetos[$index]['customersRPSixthMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            //Guardo el margen del septimo mes de clientes RP y R-E
            if($this->equal) $this->_objetos[$index]['customersRPSeventhMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            //Total de los clientes RP y R-E con mas de un dollar de margen
            $this->_objetos[$index]['totalcustomersRP']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RP',true);
            //Total de los clientes RP y R-E con mas de un dollar de margen del dia de ayer
            if($this->equal) $this->_objetos[$index]['totalcustomersRPYesterday']=$this->_getTotalCustomers($yesterday,$yesterday,'RP',false);
            //Total del promedio de los clientes RP y R-E de mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalcustomersRPAverage']=$this->_getTotalAvgCustomers($sevenDaysAgo,$yesterday,'RP');
            //Total de lo que va de mes de los clientes RP y R-E con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalcustomersRPAccumulated']=$this->_getTotalCustomers($firstDay,$startDate,'RP',false);
            //Total del promedio de los ultimos siete deias de clientes RP y R-E con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalcustomersRPForecast']=array_sum($this->_objetos[$index]['customersRPForecast']);
            //total del mes anterior de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPPreviousMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RP',false);
            //Total del tercer mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPThirdMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totalcustomersRPThirdMonth']=0;
            //total del cuarto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPFourthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totalcustomersRPFourthMonth']=0;
            //Total del quimto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPFifthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totalcustomersRPFifthMonth']=0;
            //Total del sexto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPSixthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totalcustomersRPSixthMonth']=0;
            //Total del septimo mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPSeventhMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totalcustomersRPSeventhMonth']=0;
            //Total de los clientes RP y R-E
            $this->_objetos[$index]['totalcustomersRPComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RP',false);
            ///////////////////////////////////////////////////
            //Destinos RP y RE con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRP']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RP',true);
            //Destino RP y R-E del dia anterior
            if($this->equal) $this->_objetos[$index]['destinationsRPYesterday']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RP',false);
            //Promedio de destinos de los ultimos 7 dias
            if($this->equal) $this->_objetos[$index]['destinationsRPAverage']=$this->_getAvgDestinations($sevenDaysAgo,$yesterday,'RP');
            //Acumulado de los destinos en lo que va de mes
            if($this->equal) $this->_objetos[$index]['destinationsRPAccumulated']=$this->_getDestinations($firstDay,$startDate,'RP',false);
            // Pronostico de los destinos 
            if($this->equal) $this->_objetos[$index]['destinationsRPForecast']=$this->_closeOfTheMonth(null,$index,'destinationsRPAverage','destinationsRPAccumulated','destination');
            //Destinos del mes anteior
            if($this->equal) $this->_objetos[$index]['destinationsRPPreviousMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RP',false);
            //Destinos del tercer mes
            if($this->equal) $this->_objetos[$index]['destinationsRPThirdMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RP',false);
            //Destinos del cuarto mes
            if($this->equal) $this->_objetos[$index]['destinationsRPFourthMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            //Destinos del quinto mes
            if($this->equal) $this->_objetos[$index]['destinationsRPFifthMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            //Destinos del sexto mes
            if($this->equal) $this->_objetos[$index]['destinationsRPSixthMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            //Destinos del septimo mes
            if($this->equal) $this->_objetos[$index]['destinationsRPSeventhMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            //Total de los destinos RP y R-E con mas de un dollar de margen
            $this->_objetos[$index]['totaldestinationsRP']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',true);
            //Total de los destinos RP y R-E del dia anterior
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPYesterday']=$this->_getTotalDestinations($yesterday,$yesterday,'RP',false);
            //Promedio de los destinos RP y R-E
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPAverage']=$this->_getTotalAvgDestinations($sevenDaysAgo,$yesterday,'RP');
            //Acumulado en lo que va de mes de destinos RP y R-E
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPAccumulated']=$this->_getTotalDestinations($firstDay,$startDate,'RP',false);
            //Pronosticos para fin del mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPForecast']=array_sum($this->_objetos[$index]['destinationsRPForecast']);
            //Total de destinos RP y R-E del mes anterior
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPPreviousMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RP',false);
            //Total de destinos RP y R-E del tercer mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPThirdMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totaldestinationsRPThirdMonth']=0;
            //Total de destinos RP y R-E del cuarto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPFourthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totaldestinationsRPFourthMonth']=0;
            //Total de destinos RP y R-E del quinto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPFifthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totaldestinationsRPFifthMonth']=0;
            //Total de destinos RP y R-E del sexto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPSixthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totaldestinationsRPSixthMonth']=0;
            //Total de destinos RP y R-E del septimo mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPSeventhMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            if($this->equal) $this->_totals['totaldestinationsRPSeventhMonth']=0;
            //Total de los destinos RP y RE
            $this->_objetos[$index]['totaldestinationsRPComplete']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',false);
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //Clientes RPRO con mas de un dollar de margen
            $this->_objetos[$index]['customersRPRO']=$this->_getCustomers($startDateTemp,$endingDateTemp,'RPRO',true);
            //Clientes RPRO con mas de un dollar de margen del dia anterior
            if($this->equal) $this->_objetos[$index]['customersRPROYesterday']=$this->_getCustomers($yesterday,$yesterday,'RPRO',false);
            //Promedio de clientes RPRO de los ultimos 7 dias
            if($this->equal) $this->_objetos[$index]['customersRPROAverage']=$this->_getAvgCustomers($sevenDaysAgo,$yesterday,'RPRO');
            //Margen acumulado de los clientes RPRO en lo que va de mes
            if($this->equal) $this->_objetos[$index]['customersRPROAccumulated']=$this->_getCustomers($firstDay,$startDate,'RPRO',false);
            //Guardo la proyeccion para el final del mes
            if($this->equal) $this->_objetos[$index]['customersRPROForecast']=$this->_closeOfTheMonth(null,$index,'customersRPROAverage','customersRPROAccumulated','carrier');
            //Guardo el margen del mes anterior de clientes RPRO
            if($this->equal) $this->_objetos[$index]['customersRPROPreviousMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RPRO',false);
            //Guardo el margen del tercer mes de clientes RPRO
            if($this->equal) $this->_objetos[$index]['customersRPROThirdMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RPRO',false);
            //Guardo el margen del cuarto mes de clientes RPRO
            if($this->equal) $this->_objetos[$index]['customersRPROFourthMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            //Guardo el margen del quinto mes de clientes RPRO
            if($this->equal) $this->_objetos[$index]['customersRPROFifthMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            //Guardo el margen del sexto mes de clientes RPRO
            if($this->equal) $this->_objetos[$index]['customersRPROSixthMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            //Guardo el margen del septimo mes de clientes RPRO
            if($this->equal) $this->_objetos[$index]['customersRPROSeventhMonth']=$this->_getCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            //Total de los clientes RPRO con mas de un dollar de margen
            $this->_objetos[$index]['totalcustomersRPRO']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RPRO',true);
            //Total de los clientes RPRO con mas de un dollar de margen del dia de ayer
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROYesterday']=$this->_getTotalCustomers($yesterday,$yesterday,'RPRO',false);
            //Total del promedio de los clientes RPRO de mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROAverage']=$this->_getTotalAvgCustomers($sevenDaysAgo,$yesterday,'RPRO');
            //Total de lo que va de mes de los clientes RPRO con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROAccumulated']=$this->_getTotalCustomers($firstDay,$startDate,'RPRO',false);
            //Total del promedio de los ultimos siete deias de clientes RPRO con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROForecast']=array_sum($this->_objetos[$index]['customersRPROForecast']);
            //total del mes anterior de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROPreviousMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RPRO',false);
            //Total del tercer mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROThirdMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totalcustomersRPROThirdMonth']=0;
            //total del cuarto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROFourthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totalcustomersRPROFourthMonth']=0;
            //Total del quimto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROFifthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totalcustomersRPROFifthMonth']=0;
            //Total del sexto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROSixthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totalcustomersRPROSixthMonth']=0;
            //Total del septimo mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROSeventhMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totalcustomersRPROSeventhMonth']=0;
            //Total de los clientes RPRO
            $this->_objetos[$index]['totalcustomersRPROComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RPRO',false);
            ///////////////////////////////////////////////////
            //Destinos RPRO con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRPRO']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RPRO',true);
            //Destino RPRO del dia anterior
            if($this->equal) $this->_objetos[$index]['destinationsRPROYesterday']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RPRO',false);
            //Promedio de destinos de los ultimos 7 dias
            if($this->equal) $this->_objetos[$index]['destinationsRPROAverage']=$this->_getAvgDestinations($sevenDaysAgo,$yesterday,'RPRO');
            //Acumulado de los destinos en lo que va de mes
            if($this->equal) $this->_objetos[$index]['destinationsRPROAccumulated']=$this->_getDestinations($firstDay,$startDate,'RPRO',false);
            // Pronostico de los destinos 
            if($this->equal) $this->_objetos[$index]['destinationsRPROForecast']=$this->_closeOfTheMonth(null,$index,'destinationsRPROAverage','destinationsRPROAccumulated','destination');
            //Destinos del mes anteior
            if($this->equal) $this->_objetos[$index]['destinationsRPROPreviousMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RPRO',false);
            //Destinos del tercer mes
            if($this->equal) $this->_objetos[$index]['destinationsRPROThirdMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RPRO',false);
            //Destinos del cuarto mes
            if($this->equal) $this->_objetos[$index]['destinationsRPROFourthMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            //Destinos del quinto mes
            if($this->equal) $this->_objetos[$index]['destinationsRPROFifthMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            //Destinos del sexto mes
            if($this->equal) $this->_objetos[$index]['destinationsRPROSixthMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            //Destinos del septimo mes
            if($this->equal) $this->_objetos[$index]['destinationsRPROSeventhMonth']=$this->_getDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            //Total de los destinos RPRO con mas de un dollar de margen
            $this->_objetos[$index]['totaldestinationsRPRO']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RPRO',true);
            //Total de los destinos RPRO del dia anterior
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROYesterday']=$this->_getTotaldestinations($yesterday,$yesterday,'RPRO',false);
            //Promedio de los destinos RPRO
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROAverage']=$this->_getTotalAvgDestinations($sevenDaysAgo,$yesterday,'RPRO');
            //Acumulado en lo que va de mes de destinos RPRO
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROAccumulated']=$this->_getTotalDestinations($firstDay,$startDate,'RPRO',false);
            //Pronosticos para fin del mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROForecast']=array_sum($this->_objetos[$index]['destinationsRPROForecast']);
            //Total de destinos RPRO del mes anterior
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROPreviousMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RPRO',false);
            //Total de destinos RPRO del tercer mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROThirdMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totaldestinationsRPROThirdMonth']=0;
            //Total de destinos RPRO del cuarto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROFourthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totaldestinationsRPROFourthMonth']=0;
            //Total de destinos RPRO del quinto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROFifthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totaldestinationsRPROFifthMonth']=0;
            //Total de destinos RPRO del sexto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROSixthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totaldestinationsRPROSixthMonth']=0;
            //Total de destinos RPRO del septimo mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROSeventhMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            if($this->equal) $this->_totals['totaldestinationsRPROSeventhMonth']=0;
            //Total de los destinos RPRO
            $this->_objetos[$index]['totaldestinationsRPROComplete']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',false);

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
     * @param boolean $type 
     * @return array
     */
    private function _getCustomers($startDate,$endDate,$string,$type=true)
    {
        $condition="WHERE x.margin>1 AND x.id_carrier_customer=c.id";
        if(!$type) $condition="WHERE x.id_carrier_customer=c.id";
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //Construyo la consulta
        $sql="SELECT c.name AS carrier, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, CASE WHEN x.pdd=0 THEN 0 WHEN x.total_calls=0 THEN 0 ELSE x.pdd/x.total_calls END AS pdd, x.cost, x.revenue, x.margin, CASE WHEN x.revenue=0 THEN 0 WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage
              FROM (SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE id_carrier_customer IN ({$carriers}) AND date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_destination_int IS NOT NULL
                    GROUP BY id_carrier_customer) x, carrier c
              {$condition}
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
    private function _getDestinations($startDate,$endDate,$string,$type=true)
    {
        $condition="WHERE x.margin > 1 AND x.id_destination=d.id";
        if(!$type) $condition="WHERE x.id_destination=d.id";
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //COnstruyo la consulta sql
        $sql="SELECT d.name AS destination, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, CASE WHEN x.pdd=0 THEN 0 WHEN x.total_calls=0 THEN 0 ELSE x.pdd/x.total_calls END AS pdd, x.cost, x.revenue, x.margin, CASE WHEN x.revenue=0 THEN 0 WHEN x.cost=0 THEN 0 ELSE (((x.revenue*100)/x.cost)-100) END AS margin_percentage, CASE WHEN x.cost=0 THEN 0 WHEN x.minutes=0 THEN 0 ELSE (x.cost/x.minutes)*100 END AS costmin, CASE WHEN x.revenue=0 THEN 0 WHEN x.minutes=0 THEN 0 ELSE (x.revenue/x.minutes)*100 END AS ratemin, CASE WHEN x.revenue=0 THEN 0 WHEN x.minutes=0 THEN 0 WHEN x.cost=0 THEN 0 ELSE ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) END AS marginmin
              FROM (SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN ({$carriers})
                    GROUP BY id_destination
                    ORDER BY margin DESC) x, destination d
              {$condition}
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

    /**
     * Encargada de traer el average correspondiente de los carriers retail
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @return array
     */
    private function _getAvgCustomers($startDate,$endDate,$string)
    {
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //Construyo la consulta
        $sql="SELECT c.name AS carrier, x.id_carrier_customer, AVG(x.margin) AS margin
              FROM (SELECT date_balance, id_carrier_customer, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE id_carrier_customer IN ({$carriers}) AND date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_destination_int IS NOT NULL
                    GROUP BY id_carrier_customer, date_balance) x, carrier c
              WHERE x.id_carrier_customer=c.id
              GROUP BY x.id_carrier_customer, c.name";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Encargada de traer el average total correspondiente de los carriers retail
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @return array
     */
    private function _getTotalAvgCustomers($startDate,$endDate,$string)
    {
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //Construyo la consulta
        $sql="SELECT SUM(d.margin) AS margin
              FROM (SELECT c.name AS carrier, x.id_carrier_customer, AVG(x.margin) AS margin
                    FROM (SELECT date_balance, id_carrier_customer, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                          FROM balance
                          WHERE id_carrier_customer IN ({$carriers}) AND date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_destination_int IS NOT NULL
                          GROUP BY id_carrier_customer, date_balance) x, carrier c
                    WHERE x.id_carrier_customer=c.id
                    GROUP BY x.id_carrier_customer, c.name) d";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Encargada de traer el promedio de los destinos en el rango de fecha pasado como parametro
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @return array
     */
    private function _getAvgDestinations($startDate,$endDate,$string)
    {
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //COnstruyo la consulta sql
        $sql="SELECT b.id_destination, d.name AS destination, AVG(b.margin) AS margin
              FROM (SELECT date_balance, id_destination, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN ({$carriers})
                    GROUP BY date_balance, id_destination) b, destination d
              WHERE b.id_destination=d.id
              GROUP BY b.id_destination, d.name";
        return Balance::model()->findAllBySql($sql);
    }

    /**
     * Encargada de traer el total del promedio de los destinos en el rango de fecha pasado como parametro
     * @since 2.0
     * @access private
     * @param date $startDate
     * @param date $endDate
     * @param string $string, "RP" trae las RP y R-E, "RPRO" trae las RPRO 
     * @return array
     */
    private function _getTotalAvgDestinations($startDate,$endDate,$string)
    {
        //Que tipo de cliente
        if($string=="RPRO") $carriers="SELECT id FROM carrier WHERE name LIKE 'RPRO%'";
        if($string=="RP") $carriers="SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%'";
        //COnstruyo la consulta sql
        $sql="SELECT SUM(x.margin) AS margin
              FROM (SELECT b.id_destination, d.name AS destination, AVG(b.margin) AS margin
                    FROM (SELECT date_balance, id_destination, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN ({$carriers})
                          GROUP BY date_balance, id_destination) b, destination d
                    WHERE b.id_destination=d.id
                    GROUP BY b.id_destination, d.name) x";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Encargada de generar las columnas que van para la cabecera de carriers
     * @since 2.0
     * @access private
     * @return string
     */
    private function _getHeaderCarriers()
    {
        $c1="<td style='".$this->_head['styleHead']."'>Total Calls</td>";
        $c2="<td style='".$this->_head['styleHead']."'>Complete Calls</td>";
        $c3="<td style='".$this->_head['styleHead']."'>Minutes</td>";
        $c4="<td style='".$this->_head['styleHead']."'>ASR</td>";
        $c5="<td style='".$this->_head['styleHead']."'>ACD</td>";
        $c6="<td style='".$this->_head['styleHead']."'>PDD</td>";
        $c7="<td style='".$this->_head['styleHead']."'>Cost</td>";
        $c8="<td style='".$this->_head['styleHead']."'>Revenue</td>";
        $c9="<td style='".$this->_head['styleHead']."'>Margin</td>";
        $c10="<td style='".$this->_head['styleHead']."'>Margin%</td>";
        $c11="<td style='".$this->_head['styleHead']."'></td>";
        $c12="<td style='".$this->_head['styleHead']."'>Dia Anterior</td>";
        $c13="<td style='".$this->_head['styleHead']."'></td>";
        $c14="<td style='".$this->_head['styleHead']."'>Promedio 7D</td>";
        $c15="<td style='".$this->_head['styleHead']."'>Acumulado Mes</td>";
        $c16="<td style='".$this->_head['styleHead']."'>Proyeccion Mes</td>";
        $c17="<td style='".$this->_head['styleHead']."'></td>";
        $c18="<td style='".$this->_head['styleHead']."'>Mes Anterior</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18;
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
            return "<td style='".$style."'>{$pos}</td><td style='".$style."'>{$value['attribute']}</td>";
        else
            return "<td style='".$style."'>{$value['attribute']}</td><td style='".$style."'>{$pos}</td>";
    }

    /**
     * Encargada de generar las columnas con la data
     * @since 2.0
     * @access private
     * @param string $index es el index superior donde se encutra la data
     * @param string $index2 es el index inferior donde se encuentra la data, customersRP, customersRPYesterday, customersRPAverage, customersRPForecast
     * @param string $attribute es el atributo con el que el siguiente parametro deberá coincidir
     * @param string $phrase el dato que debe coincidir
     * @return string
     */
    private function _getRow($index,$index2,$attribute,$phrase,$style)
    {
        $margin=$c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=null;
        foreach($this->_objetos[$index][$index2] as $key => $value)
        {
            if($value->$attribute == $phrase['attribute'])
            {               
                $c1="<td style='".$style."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
                $c3="<td style='".$style."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
                $c5="<td style='".$style."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
                $c7="<td style='".$style."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
                $c8="<td style='".$style."' >".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $margin=$value->margin;
                $c9="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
            }
        }
        foreach($this->_objetos[$index][$index2."Yesterday"] as $key => $yesterday)
        {
            if($yesterday->$attribute == $phrase['attribute'])
            {
                $c11="<td style='".$style."'>".$this->_upOrDown($yesterday->margin,$margin)."</td>";
                $c12="<td style='".$style."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
            }
        }
        foreach($this->_objetos[$index][$index2."Average"] as $key => $average)
        {
            if($average->$attribute == $phrase['attribute'])
            {
                $c13="<td style='".$style."'>".$this->_upOrDown($average->margin,$margin)."</td>";
                $c14="<td style='".$style."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
            }
        }
        foreach($this->_objetos[$index][$index2."Accumulated"] as $key => $accumulated)
        {
            if($accumulated->$attribute == $phrase['attribute'])
            {
                $c15="<td style='".$style."'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
            }
        }
        $c16="<td style='".$style."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Forecast"][$phrase['attribute']])."</td>";
        foreach ($this->_objetos[$index][$index2."PreviousMonth"] as $key => $previousMonth)
        {
            if($previousMonth->$attribute == $phrase['attribute'])
            {
                $c17="<td style='".$style."'>".$this->_upOrDown($previousMonth->margin,$this->_objetos[$index][$index2."Forecast"][$phrase['attribute']])."</td>";
                $c18="<td style='".$style."'>".Yii::app()->format->format_decimal($previousMonth->margin)."</td>";
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
        if($c11==null) $c11="<td style='".$style."'>--</td>";
        if($c12==null) $c12="<td style='".$style."'>--</td>";
        if($c13==null) $c13="<td style='".$style."'>--</td>";
        if($c14==null) $c14="<td style='".$style."'>--</td>";
        if($c15==null) $c15="<td style='".$style."'>--</td>";
        if($c16==null) $c16="<td style='".$style."'>--</td>";
        if($c17==null) $c17="<td style='".$style."'>--</td>";
        if($c18==null) $c18="<td style='".$style."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18;
    }

    /**
     * Encargada de generar las columnas con la data de los meses anteriores
     * @since 2.0
     * @access private
     * @param string $index es el index superior donde se encutra la data
     * @param string $index2 es el index inferior donde se encuentra la data, customersRP, customersRPYesterday, customersRPAverage, customersRPForecast
     * @param string $attribute es el atributo con el que el siguiente parametro deberá coincidir
     * @param string $phrase el dato que debe coincidir
     * @return string
     */
    private function _getRowMonth($index,$attribute,$phrase,$style)
    {
        $margin=$c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$this->_objetos[0][$index."Forecast"][$phrase['attribute']];
        foreach($this->_objetos[0][$index."ThirdMonth"] as $key => $third)
        {
            if($third->$attribute == $phrase['attribute'])
            {               
                $c1="<td style='".$style."'>".$this->_upOrDown($third->margin,$margin)."</td>";
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($third->margin)."</td>";
                $this->_totals["total".$index."ThirdMonth"]+=$third->margin;
            }
        }
        foreach($this->_objetos[0][$index."FourthMonth"] as $key => $fourth)
        {
            if($fourth->$attribute == $phrase['attribute'])
            {               
                $c3="<td style='".$style."'>".$this->_upOrDown($fourth->margin,$margin)."</td>";
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($fourth->margin)."</td>";
                $this->_totals["total".$index."FourthMonth"]+=$fourth->margin;
            }
        }
        foreach($this->_objetos[0][$index."FifthMonth"] as $key => $fifth)
        {
            if($fifth->$attribute == $phrase['attribute'])
            {               
                $c5="<td style='".$style."'>".$this->_upOrDown($fifth->margin,$margin)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($fifth->margin)."</td>";
                $this->_totals["total".$index."FifthMonth"]+=$fifth->margin;
            }
        }
        foreach($this->_objetos[0][$index."SixthMonth"] as $key => $sixth)
        {
            if($sixth->$attribute == $phrase['attribute'])
            {               
                $c7="<td style='".$style."'>".$this->_upOrDown($sixth->margin,$margin)."</td>";
                $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($sixth->margin)."</td>";
                $this->_totals["total".$index."SixthMonth"]+=$sixth->margin;
            }
        }
        foreach($this->_objetos[0][$index."SeventhMonth"] as $key => $seventh)
        {
            if($seventh->$attribute == $phrase['attribute'])
            {               
                $c9="<td style='".$style."'>".$this->_upOrDown($seventh->margin,$margin)."</td>";
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($seventh->margin)."</td>";
                $this->_totals["total".$index."SeventhMonth"]+=$seventh->margin;
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
     * Encargado de generar el html de la fila de totales
     * @since 2.0
     * @access private
     * @param int $index
     * @param string $index2
     * @param string $style
     * @param boolean $type
     * @return string
     */
    private function _getRowTotal($index,$index2,$style,$type=true)
    {
        $c4=$c5=$c6=$c10=null;
        //Total calls
        $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->total_calls)."</td>";
        //Complete calls
        $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->complete_calls)."</td>";
        //Minutes
        $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->minutes)."</td>";
        //ASR
        if(!$type) $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->asr)."</td>";
        //ACD
        if(!$type) $c5="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->acd)."</td>";
        //PDD
        if(!$type) $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->pdd)."</td>";
        //Cost
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->cost)."</td>";
        //Revenue
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->revenue)."</td>";
        //Margin
        $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->margin)."</td>";
        //Margin Percentage
        if(!$type) $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2]->margin_percentage)."</td>";
        //Simbolo dia anterior
        $c11="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[$index][$index2."Yesterday"]->margin,$this->_objetos[$index][$index2]->margin)."</td>";
        //Dia Anterior
        $c12="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Yesterday"]->margin)."</td>";
        //Simbolo promedio
        $c13="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[$index][$index2."Average"]->margin,$this->_objetos[$index][$index2]->margin)."</td>";
        //Promedio
        $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Average"]->margin)."</td>";
        //Acumulado
        $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Accumulated"]->margin)."</td>";
        //Proyeccion
        $c16="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Forecast"])."</td>";
        //Simbolo del mes anterior
        $c17="<td style='".$this->_head[$style]."'>".$this->_upOrDown($this->_objetos[$index][$index2."PreviousMonth"]->margin,$this->_objetos[$index][$index2."Forecast"])."</td>";
        //Mes anterior
        $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."PreviousMonth"]->margin)."</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'></td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'></td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'></td>";
        if($c10==null) $c10="<td style='".$this->_head[$style]."'></td>";

        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18;
    }

    /**
     * Encargado de traer el total de meses anteriores
     */
    private function _getRowTotalMonth($index,$style,$type=true)
    {
        $margin=$c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$this->_objetos[0][$index."Forecast"];
        $third=$this->_objetos[0][$index."ThirdMonth"]->margin;
        if($type) $third=$this->_totals[$index."ThirdMonth"];
        $fourth=$this->_objetos[0][$index."FourthMonth"]->margin;
        if($type) $fourth=$this->_totals[$index."FourthMonth"];
        $fifth=$this->_objetos[0][$index."FifthMonth"]->margin;
        if($type) $fifth=$this->_totals[$index."FifthMonth"];
        $sixth=$this->_objetos[0][$index."SixthMonth"]->margin;
        if($type) $sixth=$this->_totals[$index."SixthMonth"];
        $seventh=$this->_objetos[0][$index."SeventhMonth"]->margin;
        if($type) $seventh=$this->_totals[$index."SeventhMonth"];
        //Simbolo del tercer mes
        $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($third,$margin)."</td>";
        //Tercer mes
        $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($third)."</td>";
        //Simbolo del cuarto mes
        $c3="<td style='".$style."'>".$this->_upOrDown($fourth,$margin)."</td>";
        //Cuarto mes
        $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($fourth)."</td>";
        //Simbolo del quinto mes
        $c5="<td style='".$style."'>".$this->_upOrDown($fifth,$margin)."</td>";
        //Quinto mes
        $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($fifth)."</td>";
        //Simbolo de sexto mes
        $c7="<td style='".$style."'>".$this->_upOrDown($sixth,$margin)."</td>";
        //Sexto mes
        $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($sixth)."</td>";
        //Simbolo del septimo mes
        $c9="<td style='".$style."'>".$this->_upOrDown($seventh,$margin)."</td>";
        //Septimo mes
        $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($seventh)."</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }
}
?>