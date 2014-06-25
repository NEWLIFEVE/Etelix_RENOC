<?php
/**
* Creada para generar reporte de compra venta
* @version 3.2.1
* @package reportes
*/
class RankingCompraVenta extends Reportes
{
    function __construct()
    {
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
        $num=count($this->_objetos);
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
        //rutinarios
        $sorted['sellers']=self::sortByList($lastnames,$this->_objetos[$last]['sellers'],'apellido');
        $sorted['buyers']=self::sortByList($lastnames,$this->_objetos[$last]['buyers'],'apellido');
        $sorted['consolidated']=self::sortByList($lastnames,$this->_objetos[$last]['consolidated'],'apellido');
        
        //Cuento el numero de managers por cada tipo
        $numSellers=count($this->_objetos[$last]['sellers']);
        $numBuyers=count($this->_objetos[$last]['buyers']);
        $numConsolidated=count($this->_objetos[$last]['consolidated']);
        
        //especificos
        if(self::valDates($start,$end)['equal']==false)
        {
        	$sorted['sellers']=self::_getManagersMissing($sorted['sellers'],$lastnames);
        	$sorted['buyers']=self::_getManagersMissing($sorted['buyers'],$lastnames);
        	$sorted['consolidated']=self::_getManagersMissing($sorted['consolidated'],$lastnames);
      
        	 //Cuento el numero de managers por cada tipo
	        $numSellers=count($sorted['sellers']);
	        $numBuyers=count($sorted['buyers']);
	        $numConsolidated=count($sorted['consolidated']);
        }
         
        $body="<table>";
        for($row=1; $row<$numSellers+$numBuyers+$numConsolidated+16; $row++)
        { 
            $body.="<tr>";   
            for($col=1; $col<=2+($num*$span); $col++)
            { 
                //Celda vacia superior izquierda de sellers y buyers
                if(($row==1 || $row==$numSellers+6) && $col==1)
                {
                    $body.="<td colspan='2' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Celda vacia superior izquierda de consolidado
                if($row==$numSellers+$numBuyers+11 && $col==1)
                {
                    $body.="<td colspan='4' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Celda vacia superior derecha de sellers y buyers
                if(($row==1 || $row==$numSellers+6) && $col==2+($num*$span))
                {
                    $body.="<td colspan='2' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Celda vacia superior derecha de consolidado
                if($row==$numSellers+$numBuyers+11 && $col==2+($num*$span))
                {
                    $body.="<td colspan='4' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }

                //Cabecera izquiera superior e inferior de las tablas de sellers
                if(($row==2 || $row==$numSellers+3) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Vendedor</td>";
                }
                //Cabecera izquiera superior e inferior de las tablas de buyers
                if(($row==$numSellers+7 || $row==$numSellers+$numBuyers+8) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Comprador</td>";
                }
                //Cabecera izquiera superior e inferior de las tablas de consolidated
                if(($row==$numSellers+$numBuyers+12 || $row==$numSellers+$numBuyers+$numConsolidated+13) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."' colspan='3'>Consolidado (Ventas + Compras)</td>";
                }
                //Cabecera derecha superior e inferior de sellers
                if(($row==2 || $row==$numSellers+3) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Vendedor</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                //Cabecera derecha superior e inferior de buyers
                if(($row==$numSellers+7 || $row==$numSellers+$numBuyers+8) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Comprador</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                //cabecera derecha superior e inferior de consolidated
                if(($row==$numSellers+$numBuyers+12 || $row==$numSellers+$numBuyers+$numConsolidated+13) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."' colspan='3'>Consolidado (Ventas + Compras)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }

                //Cabecera Izquiera de totales de sellers y buyers
                if(($row==$numSellers+4 || $row==$numSellers+$numBuyers+9) && $col==1)
                {
                    $body.="<td style='".$this->_head['styleFooter']."'></td><td style='".$this->_head['styleFooter']."'>Total</td>";
                }
                //Cabecera izquierda de totales de consolidated
                if($row==$numSellers+$numBuyers+$numConsolidated+14 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleFooter']."'></td><td style='".$this->_head['styleFooter']."' colspan='3'>Total</td>";
                }
                //Cabecera Derecha de totales de sellers y buyers
                if(($row==$numSellers+4 || $row==$numSellers+$numBuyers+9) && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."'>Total</td><td style='".$this->_head['styleFooter']."'></td>";
                }
                //Cabecera Derecha de totales de consolidated
                if($row==$numSellers+$numBuyers+$numConsolidated+14 && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."' colspan='3'>Total</td><td style='".$this->_head['styleFooter']."'></td>";
                }

                //Cabecera izquierda de los totales al final de consolidados
                if($row==$numSellers+$numBuyers+$numConsolidated+15 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleFooterTotal']."'></td><td style='".$this->_head['styleFooterTotal']."' colspan='3'>Total</td>";
                }
                //Cabecera derecha de los totales al final de consolidados
                if($row==$numSellers+$numBuyers+$numConsolidated+15 && $col==2+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooterTotal']."' colspan='3'>Total</td><td style='".$this->_head['styleFooterTotal']."'></td>";
                }

                //Nombres de los managers vendedores izquierda
                if(($row>2 && $row<$numSellers+3) && $col==1)
                {
                    $pos=$row-2;
                    $body.=$this->_getNames($pos,$sorted['sellers'][$row-3],'styleBodySellers');
                }
                //Nombres de los managers vendedores derecha
                if(($row>2 && $row<$numSellers+3) && $col==2+($num*$span))
                {
                    $pos=$row-2;
                    $body.=$this->_getNames($pos,$sorted['sellers'][$row-3],'styleBodySellers',false);
                }

                //Nombres de los managers compradores izquierda
                if(($row>$numSellers+7 && $row<$numSellers+$numBuyers+8) && $col==1)
                {
                    $pos=$row-$numSellers-7;
                    $body.=$this->_getNames($pos,$sorted['buyers'][$row-$numSellers-8],'styleBodyBuyers');
                }
                //Nombres de los managers compradores derecha
                if(($row>$numSellers+7 && $row<$numSellers+$numBuyers+8) && $col==2+($num*$span))
                {
                    $pos=$row-$numSellers-7;
                    $body.=$this->_getNames($pos,$sorted['buyers'][$row-$numSellers-8],'styleBodyBuyers',false);
                }

                //Nombres de los managers compradores/vendedores izquierda
                if(($row>$numSellers+$numBuyers+12 && $row<$numSellers+$numBuyers+$numConsolidated+13) && $col==1)
                {
                    $pos=$row-$numSellers-$numBuyers-12;
                    $body.=$this->_getNamesConsolidated($pos,$sorted['consolidated'][$row-$numSellers-$numBuyers-13],'styleBodyConsolidated');
                }
                //Nombres de los managers compradores/vendedores derecha
                if(($row>$numSellers+$numBuyers+12 && $row<$numSellers+$numBuyers+$numConsolidated+13) && $col==2+($num*$span))
                {
                    $pos=$row-$numSellers-$numBuyers-12;
                    $body.=$this->_getNamesConsolidated($pos,$sorted['consolidated'][$row-$numSellers-$numBuyers-13],'styleBodyConsolidated',false);
                }
                
                //Titulo de cada mes para diferenciar la data compradores/vendedores
                if(($row==1 || $row==$numSellers+6) && self::validColumn(2,$col,$num,$span))
                {
                    $body.="<td colspan='".$span."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex(2,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Titulo de cada mes para diferenciar la data Consolidado
                if($row==$numSellers+$numBuyers+11 && self::validColumn(2,$col,$num,$span))
                {
                    $nuevospan=$span-2;
                    $body.="<td colspan='".$nuevospan."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex(2,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Escribe los headers de las columnas de las tablas
                if(($row==2 
                 || $row==$numSellers+3 
                 || $row==$numSellers+7 
                 || $row==$numSellers+$numBuyers+8) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHeaderManages(true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Escribe los headers de las columnas de la tabla consolidada
                if(($row==$numSellers+$numBuyers+12 || $row==$numSellers+$numBuyers+$numConsolidated+13) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHeaderManages(false);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }

                //Data de vendedores
                if(($row>2 && $row<$numSellers+3) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getRow(self::validIndex(2,$col,$span),'sellers',$sorted['sellers'][$row-3],'styleBodySellers',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                if($row==$numSellers+4 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotal(self::validIndex(2,$col,$span),'totalVendors','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                
                //Data de compradores
                if(($row>$numSellers+7 && $row<$numSellers+$numBuyers+8) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getRow(self::validIndex(2,$col,$span),'buyers',$sorted['buyers'][$row-$numSellers-8],'styleBodyBuyers',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                if($row==$numSellers+$numBuyers+9 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotal(self::validIndex(2,$col,$span),'totalBuyers','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }

                //Data de consolidada
                if(($row>$numSellers+$numBuyers+12 && $row<$numSellers+$numBuyers+$numConsolidated+13) && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getRow(self::validIndex(2,$col,$span),'consolidated',$sorted['consolidated'][$row-$numSellers-$numBuyers-13],'styleBodyConsolidated',false);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Data total consolidada
                if($row==$numSellers+$numBuyers+$numConsolidated+14 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotal(self::validIndex(2,$col,$span),'totalConsolidated','styleFooter',false);
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Data total de total ;)
                if($row==$numSellers+$numBuyers+$numConsolidated+15 && self::validColumn(2,$col,$num,$span))
                {
                    $body.=$this->_getHtmlTotalMargen(self::validIndex(2,$col,$span),'totalMargen','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex(2,$col,$span))) $body.="<td></td>";
                }
                //Titulo meses anteriores
                if(($row==1 || $row==$numSellers+6 || $row==$numSellers+$numBuyers+11) && $col==2+($num*$span))
                {
                    if($this->equal) $body.="<td colspan='10' style='text-align:center;background-color:#BFBEBE;color:#FFFFFF;'>Meses Anteriores</td>";
                }
                //titulo de los meses
                if(($row==2 
                 || $row==$numSellers+3
                 || $row==$numSellers+7
                 || $row==$numSellers+$numBuyers+8 
                 || $row==$numSellers+$numBuyers+12
                 || $row==$numSellers+$numBuyers+$numConsolidated+13) && $col==2+($num*$span))
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
                //data de los cuatro meses anteriores de los vendedores
                if(($row>2 && $row<$numSellers+3) && $col==2+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowMonths('sellers',$sorted['sellers'][$row-3],'styleBodySellers');
                }
                if($row==$numSellers+4 && $col==2+($num*$span))
                {
                    if($this->equal) $body.=$this->_getHtmlTotalMonth('totalVendors','styleFooter');
                }
                //data de los cuatro meses anteriores de los compradores
                if(($row>$numSellers+7 && $row<$numSellers+$numBuyers+8) && $col==2+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowMonths('buyers',$sorted['buyers'][$row-$numSellers-8],'styleBodyBuyers');
                }
                //total de los cuatro meses de los compradores
                if($row==$numSellers+$numBuyers+9 && $col==2+($num*$span))
                {
                    if($this->equal) $body.=$this->_getHtmlTotalMonth('totalBuyers','styleFooter');
                }
                //data de los cuatro meses anteriores de los compradores
                if(($row>$numSellers+$numBuyers+12 && $row<$numSellers+$numBuyers+$numConsolidated+13) && $col==2+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowConsolidatedMonths($sorted['consolidated'][$row-$numSellers-$numBuyers-13],'styleBodyConsolidated');
                }
                if($row==$numSellers+$numBuyers+$numConsolidated+14 && $col==2+($num*$span))
                {
                    if($this->equal) $body.=$this->_getHtmlTotalMonth('totalConsolidated','styleFooter');
                }
                if($row==$numSellers+$numBuyers+$numConsolidated+15 && $col==2+($num*$span))
                {
                    if($this->equal) $body.=$this->_getHtmlTotalMonth('totalMargen','styleFooterTotal');
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
        $yesterday=DateManagement::calculateDate('-1',$startDateTemp);
        $sevenDaysAgo=DateManagement::calculateDate('-7',$yesterday);
        $firstDay=DateManagement::getDayOne($start);
        $endingDateTemp=$endingDate=$array['endingDate'];
        $this->equal=$array['equal'];

        $arrayStartTemp=null;
        $index=0;
        while (self::isLower($startDateTemp,$endingDate))
        {
        	
            $arrayStartTemp=explode('-',$startDateTemp);
            $endingDateTemp=self::maxDate($arrayStartTemp[0]."-".$arrayStartTemp[1]."-".DateManagement::howManyDays($startDateTemp),$endingDate);
            //El titulo que va a llevar la seccion
            $this->_objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            /*Guardo todos los vendedores*/
            $this->_objetos[$index]['sellers']=$this->_getManagers($startDateTemp,$endingDateTemp,true);
            /*Guardo los totales de los vendedores*/
            $this->_objetos[$index]['totalVendors']=$this->_getTotalManagers($startDateTemp,$endingDateTemp,true);
            /*El total del margen por vendedor mes anterior*/
            if($this->equal) $this->_objetos[$index]['sellersPreviousMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],true);
            //tercer mes
            if($this->equal) $this->_objetos[$index]['sellersThirdMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true);
            //cuarto mes
            if($this->equal) $this->_objetos[$index]['sellersFourthMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true);
            //quinto mes
            if($this->equal) $this->_objetos[$index]['sellersFifthMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true);
            //sexto mes
            if($this->equal) $this->_objetos[$index]['sellersSixthMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true);
            //sexto mes
            if($this->equal) $this->_objetos[$index]['sellersSeventhMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true);
            /*Guardo los totales de los vendedores*/
            if($this->equal) $this->_objetos[$index]['totalVendorsPreviousMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],true);
            //Tercer Mes
            if($this->equal) $this->_objetos[$index]['totalVendorsThirdMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],true);
            //Cuarto Mes
            if($this->equal) $this->_objetos[$index]['totalVendorsFourthMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],true);
            //Quinto Mes
            if($this->equal) $this->_objetos[$index]['totalVendorsFifthMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],true);
            //Sexto Mes
            if($this->equal) $this->_objetos[$index]['totalVendorsSixthMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],true);
            //Septimo mes
            if($this->equal) $this->_objetos[$index]['totalVendorsSeventhMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],true);
            //Titulo tercer mes
            if($this->equal) $this->_objetos[$index]['titleThirdMonth']=$this->reportTitle(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            //Titulo Cuarto Mes
            if($this->equal) $this->_objetos[$index]['titleFourthMonth']=$this->reportTitle(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //Titulo Quinto Mes
            if($this->equal) $this->_objetos[$index]['titleFifthMonth']=$this->reportTitle(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            //Titulo Sexto Mes
            if($this->equal) $this->_objetos[$index]['titleSixthMonth']=$this->reportTitle(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            //Titulo del septimo mes
            if($this->equal) $this->_objetos[$index]['titleSeventhMonth']=$this->reportTitle(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            /*Guardo los valores de vendedores del dia anterior*/
            if($this->equal) $this->_objetos[$index]['sellersYesterday']=$this->_getManagers($yesterday,$yesterday,true);
            /*Guardo los totales de los vendedores del dia de ayer*/
            if($this->equal) $this->_objetos[$index]['totalVendorsYesterday']=$this->_getTotalManagers($yesterday,$yesterday,true);
            /*Guardo el promedio por vendedores de 7 dias atras*/
            if($this->equal) $this->_objetos[$index]['sellersAverage']=$this->_getAvgMarginManagers($sevenDaysAgo,$yesterday,true);
            /*Guardo el promedio total*/
            if($this->equal) $this->_objetos[$index]['totalVendorsAverage']=$this->_getTotalAvgMarginManagers($sevenDaysAgo,$yesterday,true);
            /*Guardo el acumulado que lleva hasta el dia en consulta*/
            if($this->equal) $this->_objetos[$index]['sellersAccumulated']=$this->_getManagers($firstDay,$startDate,true);
            /*Guardo el total de los acumulados hasta el dia de la consulta*/
            if($this->equal) $this->_objetos[$index]['totalVendorsAccumulated']=$this->_getTotalManagers($firstDay,$startDate,true);
            /*Guardo los pronosticos de los vendedores*/
            if($this->equal) $this->_objetos[$index]['sellersForecast']=$this->_closeOfTheMonth($lastnames,$index,'sellersAverage','sellersAccumulated');
            /*guardo los totales de cierre de mes*/
            if($this->equal) $this->_objetos[$index]['totalVendorsClose']=array_sum($this->_objetos[$index]['sellersForecast']);


            /*Guardo los totales de los compradores*/
            $this->_objetos[$index]['buyers']=$this->_getManagers($startDateTemp,$endingDateTemp,false);
            /*Guardo los totales de todos los compradores*/
            $this->_objetos[$index]['totalBuyers']=$this->_getTotalManagers($startDateTemp,$endingDateTemp,false);
            /*El total del margen por compradores mes anterior*/
            if($this->equal) $this->_objetos[$index]['buyersPreviousMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],false);
            //tercer mes
            if($this->equal) $this->_objetos[$index]['buyersThirdMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false);
            //cuarto mes
            if($this->equal) $this->_objetos[$index]['buyersFourthMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false);
            //quinto mes
            if($this->equal) $this->_objetos[$index]['buyersFifthMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false);
            //sexto mes
            if($this->equal) $this->_objetos[$index]['buyersSixthMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false);
            //Septimo mes
            if($this->equal) $this->_objetos[$index]['buyersSeventhMonth']=$this->_getManagers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false);
            /*Guardo los totales de los compradores*/
            if($this->equal) $this->_objetos[$index]['totalBuyersPreviousMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday'],false);
            //Tercer Mes
            if($this->equal) $this->_objetos[$index]['totalBuyersThirdMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday'],false);
            //Cuarto Mes
            if($this->equal) $this->_objetos[$index]['totalBuyersFourthMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],false);
            //Quinto Mes
            if($this->equal) $this->_objetos[$index]['totalBuyersFifthMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],false);
            //Sexto Mes
            if($this->equal) $this->_objetos[$index]['totalBuyersSixthMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],false);
            //Septimo mes
            if($this->equal) $this->_objetos[$index]['totalBuyersSeventhMonth']=$this->_getTotalManagers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],false);
            /*Guardo los valores de compradores del dia anterior*/
            if($this->equal) $this->_objetos[$index]['buyersYesterday']=$this->_getManagers($yesterday,$yesterday,false);
            /*Guardo los totales de los compradores del dia de ayer*/
            if($this->equal) $this->_objetos[$index]['totalBuyersYesterday']=$this->_getTotalManagers($yesterday,$yesterday,false);
            /*Guardo el promedio por compradores de 7 dias atras*/
            if($this->equal) $this->_objetos[$index]['buyersAverage']=$this->_getAvgMarginManagers($sevenDaysAgo,$yesterday,false);
            /*Guardo el promedio total*/
            if($this->equal) $this->_objetos[$index]['totalBuyersAverage']=$this->_getTotalAvgMarginManagers($sevenDaysAgo,$yesterday,false);
            /*Guardo el acumulado que lleva hasta el dia en consulta*/
            if($this->equal) $this->_objetos[$index]['buyersAccumulated']=$this->_getManagers($firstDay,$startDate,false);
            /*Guardo el total de los acumulados hasta el dia de la consulta*/
            if($this->equal) $this->_objetos[$index]['totalBuyersAccumulated']=$this->_getTotalManagers($firstDay,$startDate,false);
            /*Guardo los pronosticos de los vendedores*/
            if($this->equal) $this->_objetos[$index]['buyersForecast']=$this->_closeOfTheMonth($lastnames,$index,'buyersAverage','buyersAccumulated');
            /*guardo los totales de cierre de mes*/
            if($this->equal) $this->_objetos[$index]['totalBuyersClose']=array_sum($this->_objetos[$index]['buyersForecast']);


            /*guardo los totales de los compradores y vendedores consolidado*/
            $this->_objetos[$index]['consolidated']=$this->_getConsolidados($startDateTemp,$endingDateTemp);
            /*Guardo el total de los consolidados*/
            $this->_objetos[$index]['totalConsolidated']=$this->_getTotalConsolidado($startDateTemp,$endingDateTemp);
            /*Guardo el margen total de ese periodo*/
            $this->_objetos[$index]['totalMargen']=$this->_getTotalMargen($startDateTemp,$endingDateTemp);
            /*guardo los totales de los compradores y vendedores consolidado*/
            if($this->equal) $this->_objetos[$index]['consolidatedPreviousMonth']=$this->_getConsolidados(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday']);
            //Tercer mes
            if($this->equal) $this->_objetos[$index]['consolidatedThirdMonth']=$this->_getConsolidados(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            //Cuarto mes
            if($this->equal) $this->_objetos[$index]['consolidatedFourthMonth']=$this->_getConsolidados(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //Quinto mes
            if($this->equal) $this->_objetos[$index]['consolidatedFifthMonth']=$this->_getConsolidados(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            //Sexto mes
            if($this->equal) $this->_objetos[$index]['consolidatedSixthMonth']=$this->_getConsolidados(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            //Septimo mes
            if($this->equal) $this->_objetos[$index]['consolidatedSeventhMonth']=$this->_getConsolidados(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            /*Guardo el total de los consolidados*/
            if($this->equal) $this->_objetos[$index]['totalConsolidatedPreviousMonth']=$this->_getTotalConsolidado(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday']);
            //Tercer Mes
            if($this->equal) $this->_objetos[$index]['totalConsolidatedThirdMonth']=$this->_getTotalConsolidado(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            //Cuarto Mes
            if($this->equal) $this->_objetos[$index]['totalConsolidatedFourthMonth']=$this->_getTotalConsolidado(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //Quinto Mes
            if($this->equal) $this->_objetos[$index]['totalConsolidatedFifthMonth']=$this->_getTotalConsolidado(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            //Sexto Mes
            if($this->equal) $this->_objetos[$index]['totalConsolidatedSixthMonth']=$this->_getTotalConsolidado(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            //Septimo mes
            if($this->equal) $this->_objetos[$index]['totalConsolidatedSeventhMonth']=$this->_getTotalConsolidado(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            /*Guardo el margen total de ese periodo*/
            if($this->equal) $this->_objetos[$index]['totalMargenPreviousMonth']=$this->_getTotalMargen(DateManagement::leastOneMonth($startDate)['firstday'],DateManagement::leastOneMonth($startDate)['lastday']);
            //Tercer Mes
            if($this->equal) $this->_objetos[$index]['totalMargenThirdMonth']=$this->_getTotalMargen(DateManagement::leastOneMonth($startDate,'-2')['firstday'],DateManagement::leastOneMonth($startDate,'-2')['lastday']);
            //Cuarto Mes
            if($this->equal) $this->_objetos[$index]['totalMargenFourthMonth']=$this->_getTotalMargen(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday']);
            //Quinto Mes
            if($this->equal) $this->_objetos[$index]['totalMargenFifthMonth']=$this->_getTotalMargen(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday']);
            //Sexto Mes
            if($this->equal) $this->_objetos[$index]['totalMargenSixthMonth']=$this->_getTotalMargen(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday']);
            //Septimo mes
            if($this->equal) $this->_objetos[$index]['totalMargenSeventhMonth']=$this->_getTotalMargen(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday']);
            /*guardo los totales de los compradores y vendedores consolidado del dia de ayer*/
            if($this->equal) $this->_objetos[$index]['consolidatedYesterday']=$this->_getConsolidados($yesterday,$yesterday);
            /*Guardo el total de los consolidados del dia de ayer*/
            if($this->equal) $this->_objetos[$index]['totalConsolidatedYesterday']=$this->_getTotalConsolidado($yesterday,$yesterday);
            /*Guardo el margen total de ese periodo del dia de ayer*/
            if($this->equal) $this->_objetos[$index]['totalMargenYesterday']=$this->_getTotalMargen($yesterday,$yesterday);
            /*Guardo el promedio de los margenes consolidados*/
            if($this->equal) $this->_objetos[$index]['consolidatedAverage']=$this->_getAvgConsolidatedManagers($sevenDaysAgo,$yesterday);
            /*Guardo el proomedio total de los margenes consolidados*/
            if($this->equal) $this->_objetos[$index]['totalConsolidatedAverage']=$this->_getTotalAvgConsolidatedManagers($sevenDaysAgo,$yesterday);
            /*Guardo el promedio del margen total*/
            if($this->equal) $this->_objetos[$index]['totalMargenAverage']=$this->_getAvgTotalMargin($sevenDaysAgo,$yesterday);
            /*guardo los totales de los compradores y vendedores consolidado*/
            if($this->equal) $this->_objetos[$index]['consolidatedAccumulated']=$this->_getConsolidados($firstDay,$startDate);
            /*guardo el total de los acumulados hasta la fecha consultada*/
            if($this->equal) $this->_objetos[$index]['totalConsolidatedAccumulated']=$this->_getTotalConsolidado($firstDay,$startDate);
            /*Guardo el total de los margenes acumulados hasta esa fecha*/
            if($this->equal) $this->_objetos[$index]['totalMargenAccumulated']=$this->_getTotalMargen($firstDay,$startDate);
            /*Guardo los pronosticos de los vendedores*/
            if($this->equal) $this->_objetos[$index]['consolidatedForecast']=$this->_closeOfTheMonth($lastnames,$index,'consolidatedAverage','consolidatedAccumulated');
            /*guardo los totales de cierre de mes*/
            if($this->equal) $this->_objetos[$index]['totalConsolidatedClose']=array_sum($this->_objetos[$index]['consolidatedForecast']);

            /*Itero la fecha*/
            $startDateTemp=DateManagement::firstDayNextMonth($startDateTemp);
            $index+=1;
        }
    }
    /**
     * llena el array de managers con los managers faltantes en ese array
     * @param unknown_type $start
     * @param unknown_type $end
     */
    private function _getManagersMissing($managersInc,$lastnames)
    {
		foreach ($managersInc as $key => $manager)
        {
            $temp=array_search($manager, $lastnames);
            unset($lastnames[$temp]);
        }
        return array_merge($managersInc,$lastnames);
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
        	if($type==false) $manager="id_carrier_supplier";

                 $sql="  SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
                    FROM(SELECT {$manager}, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                         FROM balance
                         WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                         GROUP BY {$manager})b,
                         managers m,
                         (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_managers 
                          FROM carrier_managers
                          WHERE start_date<='{$startDate}') cm
                    WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier AND cm.end_date>='{$endingDate}'
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
        if($type==false) $manager="id_carrier_supplier";
        $sql="SELECT SUM(d.minutes) AS minutes, SUM(d.revenue) AS revenue, SUM(d.margin) AS margin
              FROM (SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
                    FROM (SELECT {$manager}, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                          GROUP BY {$manager})b, managers m, carrier_managers cm
                    WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier AND cm.end_date='{$endingDate}'
                    GROUP BY m.name, m.lastname
                    ORDER BY margin DESC) d";
        return Balance::model()->findBySql($sql);
//         WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier AND cm.end_date is NULL

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
              FROM(SELECT id_carrier_customer AS id, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY id_carrier_customer
                   UNION
                   SELECT id_carrier_supplier AS id, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
                     FROM (SELECT id_carrier_customer AS id, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                           FROM balance
                           WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                           GROUP BY id_carrier_customer
                           UNION
                           SELECT id_carrier_supplier AS id, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
        if($type==false) $manager="id_carrier_supplier";
        $sql="SELECT d.nombre, d.apellido, AVG(d.margin) AS margin
              FROM(SELECT m.name AS nombre, m.lastname AS apellido, b.date_balance AS date_balance, SUM(b.margin) AS margin
                   FROM(SELECT {$manager},date_balance, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
        if($type==false) $manager="id_carrier_supplier";
        $sql="SELECT SUM(t.margin) AS margin
              FROM (SELECT d.nombre, d.apellido, AVG(d.margin) AS margin
                    FROM (SELECT m.name AS nombre, m.lastname AS apellido, b.date_balance AS date_balance, SUM(b.margin) AS margin
                          FROM (SELECT {$manager},date_balance, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
                    FROM (SELECT id_carrier_customer AS id, date_balance, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                          FROM balance
                          WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                          GROUP BY id_carrier_customer, date_balance
                          UNION
                          SELECT id_carrier_supplier AS id, date_balance, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
                          FROM (SELECT id_carrier_customer AS id, date_balance, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                FROM balance
                                WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                                GROUP BY id_carrier_customer, date_balance
                                UNION
                                SELECT id_carrier_supplier AS id, date_balance, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
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
        $sql="SELECT SUM(margin) AS margin
              FROM (SELECT CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                    GROUP BY date_balance) b";
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
              FROM (SELECT CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, date_balance
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
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=null;
        $margin=$previous=$average=$previousMonth=null;
        foreach ($this->_objetos[$index][$index2] as $key => $value)
        {
            if($value->apellido == $phrase)
            {               
                if($type==true) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                if($type==true) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $margin=$value->margin;
            }
        }
        if($this->equal)
        {
            foreach ($this->_objetos[$index][$index2.'Yesterday'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $c5="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                    $previous=$value->margin;
                }
            }
            $c4="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previous,$margin)."</td>";
            foreach ($this->_objetos[$index][$index2.'Average'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                    $average=$value->margin;
                }
            }
            $c6="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average,$margin)."</td>";
            foreach ($this->_objetos[$index][$index2.'Accumulated'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                }
            }
            $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2.'Forecast'][$phrase])."</td>";
            foreach ($this->_objetos[$index][$index2.'PreviousMonth'] as $key => $value)
            {
                if($value->apellido == $phrase)
                {
                    $c11="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                    $previousMonth=$value->margin;
                }
            }
            $c10="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previousMonth,$this->_objetos[$index][$index2.'Forecast'][$phrase])."</td>";
        }
        
        if($type==true)
        {
            if($c1==null) $c1="<td style='".$this->_head[$style]."'>--</td>";
            if($c2==null) $c2="<td style='".$this->_head[$style]."'>--</td>";
        }
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>--</td>";
        if($this->equal)
        {
            if($c4==null) $c4="<td style='".$this->_head[$style]."'></td>";
            if($c5==null) $c5="<td style='".$this->_head[$style]."'>--</td>";
            if($c6==null) $c6="<td style='".$this->_head[$style]."'></td>";
            if($c7==null) $c7="<td style='".$this->_head[$style]."'>--</td>";
            if($c8==null) $c8="<td style='".$this->_head[$style]."'>--</td>";
            if($c9==null) $c9="<td style='".$this->_head[$style]."'>--</td>";
            if($c10==null) $c10="<td style='".$this->_head[$style]."'></td>";
            if($c11==null) $c11="<td style='".$this->_head[$style]."'>--</td>";
        } 
        $body=$c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11;
        return $body;
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
    private function _getRowMonths($index,$phrase,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$third=$fourth=$fifth=$sixth=null;            
        $margin=$this->_objetos[0][$index.'Forecast'][$phrase];
        foreach ($this->_objetos[0][$index.'ThirdMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FourthMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'FifthMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SixthMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0][$index.'SeventhMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        if($c1==null) $c1="<td style='".$this->_head[$style]."'>--</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>--</td>";
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>--</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'>--</td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'>--</td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'>--</td>";
        if($c7==null) $c7="<td style='".$this->_head[$style]."'>--</td>";
        if($c8==null) $c8="<td style='".$this->_head[$style]."'>--</td>";
        if($c9==null) $c9="<td style='".$this->_head[$style]."'>--</td>";
        if($c10==null) $c10="<td style='".$this->_head[$style]."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
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
    private function _getRowConsolidatedMonths($phrase,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        $margin=$third=$fourth=$fifth=$sixth=null;
        $margin=$this->_objetos[0]['consolidatedForecast'][$phrase];
        foreach ($this->_objetos[0]['consolidatedThirdMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0]['consolidatedFourthMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0]['consolidatedFifthMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0]['consolidatedSixthMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        foreach ($this->_objetos[0]['consolidatedSeventhMonth'] as $key => $value)
        {
            if($value->apellido == $phrase)
            {
                $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($value->margin,$margin)."</td>";
                $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        if($c1==null) $c1="<td style='".$this->_head[$style]."'>--</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>--</td>";
        if($c2==null) $c2="<td style='".$this->_head[$style]."'>--</td>";
        if($c3==null) $c3="<td style='".$this->_head[$style]."'>--</td>";
        if($c4==null) $c4="<td style='".$this->_head[$style]."'>--</td>";
        if($c5==null) $c5="<td style='".$this->_head[$style]."'>--</td>";
        if($c6==null) $c6="<td style='".$this->_head[$style]."'>--</td>";
        if($c7==null) $c7="<td style='".$this->_head[$style]."'>--</td>";
        if($c8==null) $c8="<td style='".$this->_head[$style]."'>--</td>";
        if($c9==null) $c9="<td style='".$this->_head[$style]."'>--</td>";
        if($c10==null) $c10="<td style='".$this->_head[$style]."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     * Retorna una tabla con los totales de los _objetos pasados como parametros
     * @access private
     * @param CActiveRecord $total es el objeto que totaliza los que cumplen la condicion
     * @return string
     */
    private function _getHtmlTotal($index,$index2,$style,$type=true)
    {
        $total=$this->_objetos[$index][$index2];
        if($this->equal) $yesterday=$this->_objetos[$index][$index2.'Yesterday'];
        if($this->equal) $average=$this->_objetos[$index][$index2.'Average'];
        if($this->equal) $accumulated=$this->_objetos[$index][$index2.'Accumulated'];
        if($this->equal) $close=$this->_objetos[$index][$index2.'Close'];
        if($this->equal) $previousMonth=$this->_objetos[$index][$index2.'PreviousMonth'];

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
     * Retorna una tabla con los totales de los _objetos pasados como parametros
     * @access private
     * @param CActiveRecord $total es el objeto que totaliza los que cumplen la condicion
     * @return string
     */
    private function _getHtmlTotalMonth($index,$style)
    {
        $margin=$third=$fourth=$fifth=$sixth=null;
        $margin=$this->_objetos[0][$index.'Close'];
        $third=$this->_objetos[0][$index.'ThirdMonth'];
        $fourth=$this->_objetos[0][$index.'FourthMonth'];
        $fifth=$this->_objetos[0][$index.'FifthMonth'];
        $sixth=$this->_objetos[0][$index.'SixthMonth'];
        $seventh=$this->_objetos[0][$index.'SeventhMonth'];
        $body="";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($third->margin,$margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($third->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($fourth->margin,$margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($fourth->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($fifth->margin,$margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($fifth->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($sixth->margin,$margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($sixth->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($seventh->margin,$margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($seventh->margin)."</td>";
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
        $data=$this->_objetos[$index][$index2];
        if($this->equal) $yesterday=$this->_objetos[$index][$index2.'Yesterday'];
        if($this->equal) $average=$this->_objetos[$index][$index2.'Average'];
        if($this->equal) $accumulated=$this->_objetos[$index][$index2.'Accumulated'];
        if($this->equal) $previousMonth=$this->_objetos[$index][$index2.'PreviousMonth'];
        $body="";
        $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($data->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday->margin,$data->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average->margin,$data->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated->margin+$this->_forecast($average->margin))."</td>";
        if($this->equal) $this->_objetos[0]['totalMargenClose']=$accumulated->margin+$this->_forecast($average->margin);
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previousMonth->margin,$accumulated->margin+$this->_forecast($average->margin))."</td>";
        if($this->equal) $body.="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($previousMonth->margin)."</td>";
        return $body;
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