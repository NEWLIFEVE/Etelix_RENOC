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

        //Cuento el numero de objetos dentro del array
        $num=count($this->_objetos);
        $last=$num-1;


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
            $this->_objetos[$index]['customersRPOneDollar']=$this->_getCustomers($startDateTemp,$endingDateTemp,'RP',true);
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
            $this->_objetos[$index]['totalCustomersRPOneDollar']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RP',true);
            //Total de los clientes RP y R-E con mas de un dollar de margen del dia de ayer
            if($this->equal) $this->_objetos[$index]['totalCustomersRPYesterday']=$this->_getTotalCustomers($yesterday,$yesterday,'RP',false);
            //Total del promedio de los clientes RP y R-E de mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalCustomersRPAverage']=$this->_getTotalAvgCustomers($sevenDaysAgo,$yesterday,'RP');
            //Total de lo que va de mes de los clientes RP y R-E con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalCustomersRPAccumulated']=$this->_getTotalCustomers($firstday,$startDate,'RP',false);
            //Total del promedio de los ultimos siete deias de clientes RP y R-E con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalCustomersRPForecast']=array_sum($this->_objetos[$index]['customersRPForecast']);
            //total del mes anterior de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPPreviousMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RP',false);
            //Total del tercer mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPThirdMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RP',false);
            //total del cuarto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPFourthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            //Total del quimto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPFifthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            //Total del sexto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPSixthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            //Total del septimo mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPSeventhMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            //Total de los clientes RP y R-E
            $this->_objetos[$index]['totalCustomersRPComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RP',false);
            ///////////////////////////////////////////////////
            //Destinos RP y RE con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRPOneDollar']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RP',true);
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
            $this->_objetos[$index]['totalDestinationsRPOneDollar']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',true);
            //Total de los destinos RP y R-E del dia anterior
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPYesterday']=$this->_getTotalDestinations($yesterday,$yesterday,'RP',false);
            //Promedio de los destinos RP y R-E
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPAverage']=$this->_getTotalAvgDestinations($sevenDaysAgo,$yesterday,'RP');
            //Acumulado en lo que va de mes de destinos RP y R-E
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPAccumulated']=$this->_getTotalDestinations($firstDay,$startDate,'RP',false);
            //Pronosticos para fin del mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPForecast']=array_sum($this->_objetos[$index]['destinationsRPForecast']);
            //Total de destinos RP y R-E del mes anterior
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPPreviousMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RP',false);
            //Total de destinos RP y R-E del tercer mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPThirdMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RP',false);
            //Total de destinos RP y R-E del cuarto mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPFourthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            //Total de destinos RP y R-E del quinto mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPFifthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            //Total de destinos RP y R-E del sexto mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPSixthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            //Total de destinos RP y R-E del septimo mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPSeventhMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            //Total de los destinos RP y RE
            $this->_objetos[$index]['totalDestinationsRPComplete']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',false);
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //Clientes RPRO con mas de un dollar de margen
            $this->_objetos[$index]['customersRPROOneDollar']=$this->_getCustomers($startDateTemp,$endingDateTemp,'RPRO',true);
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
            $this->_objetos[$index]['totalCustomersRPROOneDollar']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RPRO',true);
            //Total de los clientes RPRO con mas de un dollar de margen del dia de ayer
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROYesterday']=$this->_getTotalCustomers($yesterday,$yesterday,'RPRO',false);
            //Total del promedio de los clientes RPRO de mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROAverage']=$this->_getTotalAvgCustomers($sevenDaysAgo,$yesterday,'RPRO');
            //Total de lo que va de mes de los clientes RPRO con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROAccumulated']=$this->_getTotalCustomers($firstday,$startDate,'RPRO',false);
            //Total del promedio de los ultimos siete deias de clientes RPRO con mas de un dollar
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROForecast']=array_sum($this->_objetos[$index]['customersRPROForecast']);
            //total del mes anterior de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROPreviousMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RPRO',false);
            //Total del tercer mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROThirdMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RPRO',false);
            //total del cuarto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROFourthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            //Total del quimto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROFifthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            //Total del sexto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROSixthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            //Total del septimo mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalCustomersRPROSeventhMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            //Total de los clientes RPRO
            $this->_objetos[$index]['totalCustomersRPROComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RPRO',false);
            ///////////////////////////////////////////////////
            //Destinos RPRO con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRPROOneDollar']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RPRO',true);
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
            $this->_objetos[$index]['totalDestinationsRPROOneDollar']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RPRO',true);
            //Total de los destinos RPRO del dia anterior
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROYesterday']=$this->_getTotalDestinations($yesterday,$yesterday,'RPRO',false);
            //Promedio de los destinos RPRO
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROAverage']=$this->_getTotalAvgDestinations($sevenDaysAgo,$yesterday,'RPRO');
            //Acumulado en lo que va de mes de destinos RPRO
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROAccumulated']=$this->_getTotalDestinations($firstDay,$startDate,'RPRO',false);
            //Pronosticos para fin del mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROForecast']=array_sum($this->_objetos[$index]['destinationsRPROForecast']);
            //Total de destinos RPRO del mes anterior
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROPreviousMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],'RPRO',false);
            //Total de destinos RPRO del tercer mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROThirdMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],'RPRO',false);
            //Total de destinos RPRO del cuarto mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROFourthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            //Total de destinos RPRO del quinto mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROFifthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            //Total de destinos RPRO del sexto mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROSixthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            //Total de destinos RPRO del septimo mes
            if($this->equal) $this->_objetos[$index]['totalDestinationsRPROSeventhMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            //Total de los destinos RPRO
            $this->_objetos[$index]['totalDestinationsRPROComplete']=$this->_getTotalDestinations($startDateTemp,$endingDateTemp,'RP',false);

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
        $sql="SELECT c.name AS carrier, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
              FROM (SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
                    FROM (SELECT date_balance, id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
}
?>