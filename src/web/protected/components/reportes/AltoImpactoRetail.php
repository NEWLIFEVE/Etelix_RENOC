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
        $total=$numCRP+$numDRP+$numCRPRO+9/*+$numDRP+$numCRPRO+$numDRPRO*/;

        //establezco el orden que va a regir las tablas
        $sorted['customersRP']=self::sort($this->_objetos[$last]['customersRP'],'carrier');
        $sorted['destinationsRP']=self::sort($this->_objetos[$last]['destinationsRP'],'destination');
        $sorted['customersRPRO']=self::sort($this->_objetos[$last]['customersRPRO'],'carrier');
        $sorted['destinationsRPRO']=self::sort($this->_objetos[$last]['destinationsRPRO'],'destination');

        //este numero es por la cantidad de columnas en los carriers
        $span=18;
        if(!$this->equal) $span=10;
        $spanDes=21;
        if(!$this->equal) $spanDes=13;
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
                 || $row==$numCRP+5) && ($col==1 || $col==$before+($num*$span)))
                {
                    $body.="<td colspan='{$before}' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //celdas vacias debajo de los destinos RP y R-E
                if($row==$numCRP+$numDRP+5 && ($col==1 || $col==$before+($num*$span)))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td colspan='{$before}' style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                }
                //Titulo de cada mes para diferenciar la data 
                if($row==1 && self::validColumn($before,$col,$num,$span))
                {
                    $body.="<td colspan='".$span."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex($before,$col,$span)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Titulo de cada mes de cliente RPRO
                if($row==$numCRP+$numDRP+5 && self::validColumn($before,$col,$num,$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td colspan='".$span."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex($before,$col,$span)]['title']."</td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && !$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Titulo de meses anteriores
                if(($row==1 
                 || $row==$numCRP+5) && $col==$before+($num*$span))
                {
                    if($this->equal) $body.="<td colspan='10' style='text-align:center;background-color:#BFBEBE;color:#FFFFFF;'>Meses Anteriores</td>";
                }
                //Titulo de meses anteriores de clientes RPRO
                if($row==$numCRP+$numDRP+5 && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td colspan='10' style='text-align:center;background-color:#BFBEBE;color:#FFFFFF;'>Meses Anteriores</td>";
                }
                //Cabecera superior izquierda de los clientes RP y R-E
                if($row==2 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Clientes RP (+1)</td>";
                }
                //Cabecera superior izquierda de los clientes RPRO
                if($row==$numCRP+$numDRP+6 && $col==1)
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Clientes RPRO (+1)</td>";
                }
                //Cabecera con las columnas de clientes RPRO
                if(($row==$numCRP+$numDRP+6
                 || $row==$numCRP+$numDRP+$numCRPRO+3) && self::validColumn($before,$col,$num,$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.=$this->_getHeaderCarriers();
                    if($this->_objetos[$last]['customersRPRO']!=null && !$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Cabecera con las columnas de clientes RP y R-E
                if(($row==2 
                 || $row==$numCRP+3) && self::validColumn($before,$col,$num,$span))
                {
                    $body.=$this->_getHeaderCarriers();
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Cabecera superior derecha de los clientes RP y R-E
                if($row==2 && $col==$before+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Clientes RP (+1)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                //Cabecera superior derecha de los clientes RPRO
                if($row==$numCRP+$numDRP+6 && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td style='".$this->_head['styleHead']."'>Clientes RPRO (+1)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                //Celdas vacias izquierda y derecha
                if(($row==$numCRP+3
                //Celdas vacias para porcentajes clientes RP y R-E
                 || $row==$numCRP+4
                //Celdas vacias para cabecera despues de totales de Destinos RP y R-E
                 || $row==$numCRP+$numDRP+3
                //Celdas vacias para porcentajes despues de destinos RP y R-E
                 || $row==$numCRP+$numDRP+4) && ($col==1 || $col==$before+($num*$span)))
                {
                    $body.="<td></td><td></td>";
                }
                //Cabecera superior derecha de los Destinos RP y R-E
                if($row==$numCRP+6 && $col==$before+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Destinos RP (+1)</td><td style='".$this->_head['styleHead']."'>Ranking</td>";
                }
                //titulo de los meses
                if(($row==2 
                 || $row==$numCRP+3
                 || $row==$numCRP+6
                 //debajo de los destinos RP y R-E
                 || $row==$numCRP+$numDRP+3) && $col==$before+($num*$span))
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
                    $body.=$this->_getNames($pos,$sorted['customersRP'][$row-3],false);
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
                }
                //Celdas izquierda de total clientes RP y R-E, Destinos RP y R-E
                if(($row==$numCRP+1
                 || $row==$numCRP+$numDRP+1) && $col==1)
                {
                    $body.="<td></td><td style='".$this->_head['styleFooter']."'>TOTAL</td>";
                }
                //Totales de Clientes RP y R-E
                if($row==$numCRP+1 && self::validColumn($before,$col,$num,$span))
                {
                    $body.=$this->_getRowTotal(self::validIndex($before,$col,$span),'totalcustomersRP','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Celdas derecha de total
                if(($row==$numCRP+1
                 || $row==$numCRP+$numDRP+1) && $col==$before+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooter']."'>TOTAL</td><td></td>";
                }
                //Totales altos de meses anteriores
                if($row==$numCRP+1 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowTotalMonth('totalcustomersRP','styleFooter',true);
                }
                //Celda izquierda de total completo de clientes
                if(($row==$numCRP+2
                 || $row==$numCRP+$numDRP+2) && $col==1)
                {
                    $body.="<td></td><td style='".$this->_head['styleFooterTotal']."'>TOTAL</td>";
                }
                //totales completos
                if($row==$numCRP+2 && self::validColumn($before,$col,$num,$span))
                {
                    $body.=$this->_getRowTotal(self::validIndex($before,$col,$span),'totalcustomersRP','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Celdas derecha total
                if(($row==$numCRP+2
                 || $row==$numCRP+$numDRP+2) && $col==$before+($num*$span))
                {
                    $body.="<td style='".$this->_head['styleFooterTotal']."'>TOTAL</td><td></td>";
                }
                //Totales completos de meses anteriores
                if($row==$numCRP+2 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowTotalMonth('totalcustomersRP','styleFooter',false);
                }
                
                if($row==$numCRP+4 && self::validColumn($before,$col,$num,$span))
                {
                    $body.=$this->_getRowPercentage(self::validIndex($before,$col,$span),'totalcustomersRP','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCRP+4 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowPercentageMonth('totalcustomersRP','styleFooterTotal');
                }
                //Titulo mes de destinos
                if($row==$numCRP+5 && self::validColumn($before,$col,$num,$spanDes))
                {
                    $body.="<td colspan='".$spanDes."' style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->_objetos[self::validIndex($before,$col,$spanDes)]['title']."</td>";
                    if(!$this->equal && $last>(self::validIndex($before,$col,$spanDes))) $body.="<td></td>";
                }
                //Cabecera superior izquierda de los clientes RP y R-E
                if($row==$numCRP+6 && $col==1)
                {
                    $body.="<td style='".$this->_head['styleHead']."'>Ranking</td><td style='".$this->_head['styleHead']."'>Destinos RP (+1)</td>";
                }
                //Cabecera con las columnas
                if(($row==$numCRP+6
                //Cabecera para porcentajes de destinos RP y R-E
                 || $row==$numCRP+$numDRP+3) && self::validColumn($before,$col,$num,$span))
                {
                    $body.=$this->_getHeaderDestinations();
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Nombres de los Destinos izquierda RP y R-E
                if(($row>$numCRP+6  && $row<=$numCRP+$numDRP) && $col==1)
                {
                    $pos=$row-$numCRP-6;
                    $body.=$this->_getNamesDestinations($pos,$sorted['destinationsRP'][$row-$numCRP-7],true);
                }
                //data de los destinos RP y R-E
                if(($row>$numCRP+6  && $row<=$numCRP+$numDRP) && self::validColumn($before,$col,$num,$spanDes))
                {
                    $pos=$row-$numCRP-6;
                    $body.=$this->_getRowDestinations(self::validIndex($before,$col,$spanDes),'destinationsRP','destination',$sorted['destinationsRP'][$row-$numCRP-7],self::colorDestino($sorted['destinationsRP'][$row-$numCRP-7]['attribute']));
                    if(!$this->equal && $last>(self::validIndex($before,$col,$spanDes))) $body.="<td></td>";
                }
                //Nombres de los Destinos derecha RP y R-E
                if(($row>$numCRP+6  && $row<=$numCRP+$numDRP) && $col==$before+($num*$span))
                {
                    $pos=$row-$numCRP-6;
                    $body.=$this->_getNamesDestinations($pos,$sorted['destinationsRP'][$row-$numCRP-7],false);
                }
                //data de los clientes RP y R-E meses anteriores
                if(($row>$numCRP+6  && $row<=$numCRP+$numDRP) && $col==$before+($num*$span))
                {
                    $pos=$row-$numCRP-6;
                    if($this->equal) $body.=$this->_getRowMonth('destinationsRP','destination',$sorted['destinationsRP'][$row-$numCRP-7],self::colorDestino($sorted['destinationsRP'][$row-$numCRP-7]['attribute']));
                }
                //Totales de Destinos RP y R-E
                if($row==$numCRP+$numDRP+1 && self::validColumn($before,$col,$num,$spanDes))
                {
                    $body.=$this->_getRowTotalDestinations(self::validIndex($before,$col,$spanDes),'totaldestinationsRP','styleFooter',true);
                    if(!$this->equal && $last>(self::validIndex($before,$col,$spanDes))) $body.="<td></td>";
                }
                //Totales completos de meses anteriores
                if($row==$numCRP+$numDRP+1 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowTotalMonth('totaldestinationsRP','styleFooter',true);
                }
                //Totales de Destinos RP y R-E
                if($row==$numCRP+$numDRP+2 && self::validColumn($before,$col,$num,$spanDes))
                {
                    $body.=$this->_getRowTotalDestinations(self::validIndex($before,$col,$spanDes),'totaldestinationsRP','styleFooterTotal',false);
                    if(!$this->equal && $last>(self::validIndex($before,$col,$spanDes))) $body.="<td></td>";
                }
                //Totales completos de meses anteriores
                if($row==$numCRP+$numDRP+2 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowTotalMonth('totaldestinationsRP','styleFooterTotal',false);
                }
                //Porcentajes de desrinos RP y R-E
                if($row==$numCRP+$numDRP+4 && self::validColumn($before,$col,$num,$spanDes))
                {
                    $body.=$this->_getRowPercentageDestinations(self::validIndex($before,$col,$span),'totaldestinationsRP','styleFooterTotal');
                    if(!$this->equal && $last>(self::validIndex($before,$col,$spanDes))) $body.="<td></td>";
                }
                //Procentajes de destinos RP y R-E meses anteriores
                if($row==$numCRP+$numDRP+4 && $col==$before+($num*$span))
                {
                    if($this->equal) $body.=$this->_getRowPercentageMonth('totaldestinationsRP','styleFooterTotal');
                }
                //Nombres de los carriers izquierda RPRO
                if(($row>$numCRP+$numDRP+6  && $row<=$numCRP+$numDRP+$numCRPRO) && $col==1)
                {
                    $pos=$row-$numCRP-$numDRP-$numCRPRO+1;
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.=$this->_getNames($pos,$sorted['customersRPRO'][$row-$numCRP-$numDRP-$numCRPRO],true);
                }
                //data de los clientes RPRO
                if(($row>$numCRP+$numDRP+6  && $row<=$numCRP+$numDRP+$numCRPRO) && self::validColumn($before,$col,$num,$span))
                {
                    $pos=$row-$numCRP-$numDRP-$numCRPRO+1;
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.=$this->_getRow(self::validIndex($before,$col,$span),'customersRPRO','carrier',$sorted['customersRPRO'][$row-$numCRP-$numDRP-$numCRPRO],self::colorEstilo($pos));
                    if(!$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Nombres de los carriers derecha RPRO
                if(($row>$numCRP+$numDRP+6  && $row<=$numCRP+$numDRP+$numCRPRO) && $col==$before+($num*$span))
                {
                    $pos=$row-$numCRP-$numDRP-$numCRPRO+1;
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.=$this->_getNames($pos,$sorted['customersRPRO'][$row-$numCRP-$numDRP-$numCRPRO],false);
                }
                //data de los clientes RP y R-E meses anteriores
                if(($row>$numCRP+$numDRP+6  && $row<=$numCRP+$numDRP+$numCRPRO) && $col==$before+($num*$span))
                {
                    $pos=$row-$numCRP-$numDRP-$numCRPRO+1;
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.=$this->_getRowMonth('customersRPRO','carrier',$sorted['customersRPRO'][$row-$numCRP-$numDRP-$numCRPRO],self::colorEstilo($pos));
                }
                //Celdas izquierda de total clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+1 && $col==1)
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td></td><td style='".$this->_head['styleFooter']."'>TOTAL</td>";
                }
                //Totales de Clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+1 && self::validColumn($before,$col,$num,$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.=$this->_getRowTotal(self::validIndex($before,$col,$span),'totalcustomersRPRO','styleFooter',true);
                    if($this->_objetos[$last]['customersRPRO']!=null && !$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Celdas derecha de total clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+1 && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td style='".$this->_head['styleFooter']."'>TOTAL</td><td></td>";
                }
                //Totales altos de meses anteriores de clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+1 && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.=$this->_getRowTotalMonth('totalcustomersRPRO','styleFooter',true);
                }
                //Celdas izquierda de total completo clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+2 && $col==1)
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td></td><td style='".$this->_head['styleFooterTotal']."'>TOTAL</td>";
                }
                //Totales completos de Clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+2 && self::validColumn($before,$col,$num,$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.=$this->_getRowTotal(self::validIndex($before,$col,$span),'totalcustomersRPRO','styleFooterTotal',false);
                    if($this->_objetos[$last]['customersRPRO']!=null && !$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                //Celdas derecha de total completos clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+2 && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td style='".$this->_head['styleFooterTotal']."'>TOTAL</td><td></td>";
                }
                //Totales completos de meses anteriores de clientes RPRO
                if($row==$numCRP+$numDRP+$numCRPRO+2 && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.=$this->_getRowTotalMonth('totalcustomersRPRO','styleFooterTotal',false);
                }
                //Celdas vacias izquierda y derecha de clientes RPRO
                if(($row==$numCRP+$numDRP+$numCRPRO+3
                //Celdas vacias para porcentajes clientes RPRO
                 || $row==$numCRP+$numDRP+$numCRPRO+4) && ($col==1 || $col==$before+($num*$span)))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.="<td></td><td></td>";
                }
                //titulo de los meses anteriores de clientes RPRO
                if(($row==$numCRP+$numDRP+6
                 || $row==$numCRP+$numDRP+$numCRPRO+3) && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleThirdMonth']."</td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleFourthMonth']."</td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleFifthMonth']."</td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleSixthMonth']."</td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'></td>";
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.="<td style='".$this->_head['styleHead']."'>".$this->_objetos[0]['titleSeventhMonth']."</td>";
                }
                if($row==$numCRP+$numDRP+$numCRPRO+4 && self::validColumn($before,$col,$num,$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null) $body.=$this->_getRowPercentage(self::validIndex($before,$col,$span),'totalcustomersRPRO','styleFooterTotal');
                    if($this->_objetos[$last]['customersRPRO']!=null && !$this->equal && $last>(self::validIndex($before,$col,$span))) $body.="<td></td>";
                }
                if($row==$numCRP+$numDRP+$numCRPRO+4 && $col==$before+($num*$span))
                {
                    if($this->_objetos[$last]['customersRPRO']!=null && $this->equal) $body.=$this->_getRowPercentageMonth('totalcustomersRPRO','styleFooterTotal');
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
            $this->_objetos[$index]['totalcustomersRP']['total_calls']=0;
            $this->_objetos[$index]['totalcustomersRP']['complete_calls']=0;
            $this->_objetos[$index]['totalcustomersRP']['minutes']=0;
            $this->_objetos[$index]['totalcustomersRP']['cost']=0;
            $this->_objetos[$index]['totalcustomersRP']['revenue']=0;
            $this->_objetos[$index]['totalcustomersRP']['margin']=0;
            $this->_objetos[$index]['totalcustomersRP']['yesterday']=0;
            $this->_objetos[$index]['totalcustomersRP']['average']=0;
            $this->_objetos[$index]['totalcustomersRP']['accumulated']=0;
            $this->_objetos[$index]['totalcustomersRP']['forecast']=0;
            $this->_objetos[$index]['totalcustomersRP']['previous_month']=0;
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
            if($this->equal) $this->_objetos[$index]['totalcustomersRP']['third_month']=0;
            //total del cuarto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPFourthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRP']['fourth_month']=0;
            //Total del quimto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPFifthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRP']['fifth_month']=0;
            //Total del sexto mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPSixthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRP']['sixth_month']=0;
            //Total del septimo mes de clientes RP y R-E con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPSeventhMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRP']['seventh_month']=0;
            //Total de los clientes RP y R-E
            $this->_objetos[$index]['totalcustomersRPComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RP',false);
            ///////////////////////////////////////////////////
            //Destinos RP y RE con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRP']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RP',true);
            //Destino RP y R-E del dia anterior
            if($this->equal) $this->_objetos[$index]['destinationsRPYesterday']=$this->_getDestinations($yesterday,$yesterday,'RP',false);
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
            $this->_objetos[$index]['totaldestinationsRP']['total_calls']=0;
            $this->_objetos[$index]['totaldestinationsRP']['complete_calls']=0;
            $this->_objetos[$index]['totaldestinationsRP']['minutes']=0;
            $this->_objetos[$index]['totaldestinationsRP']['cost']=0;
            $this->_objetos[$index]['totaldestinationsRP']['revenue']=0;
            $this->_objetos[$index]['totaldestinationsRP']['margin']=0;
            $this->_objetos[$index]['totaldestinationsRP']['costmin']=0;
            $this->_objetos[$index]['totaldestinationsRP']['ratemin']=0;
            $this->_objetos[$index]['totaldestinationsRP']['marginmin']=0;
            $this->_objetos[$index]['totaldestinationsRP']['yesterday']=0;
            $this->_objetos[$index]['totaldestinationsRP']['average']=0;
            $this->_objetos[$index]['totaldestinationsRP']['accumulated']=0;
            $this->_objetos[$index]['totaldestinationsRP']['forecast']=0;
            $this->_objetos[$index]['totaldestinationsRP']['previous_month']=0;
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
            if($this->equal) $this->_objetos[$index]['totaldestinationsRP']['third_month']=0;
            //Total de destinos RP y R-E del cuarto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPFourthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRP']['fourth_month']=0;
            //Total de destinos RP y R-E del quinto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPFifthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRP']['fifth_month']=0;
            //Total de destinos RP y R-E del sexto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPSixthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRP']['sixth_month']=0;
            //Total de destinos RP y R-E del septimo mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPSeventhMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RP',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRP']['seventh_month']=0;
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
            $this->_objetos[$index]['totalcustomersRPRO']['total_calls']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['complete_calls']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['minutes']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['cost']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['revenue']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['margin']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['yesterday']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['average']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['accumulated']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['forecast']=0;
            $this->_objetos[$index]['totalcustomersRPRO']['previous_month']=0;
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
            if($this->equal) $this->_objetos[$index]['totalcustomersRPRO']['third_month']=0;
            //total del cuarto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROFourthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRPRO']['fourth_month']=0;
            //Total del quimto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROFifthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRPRO']['fifth_month']=0;
            //Total del sexto mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROSixthMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRPRO']['sixth_month']=0;
            //Total del septimo mes de clientes RPRO con mas de un dollar de margen
            if($this->equal) $this->_objetos[$index]['totalcustomersRPROSeventhMonth']=$this->_getTotalCustomers(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totalcustomersRPRO']['seventh_month']=0;
            //Total de los clientes RPRO
            $this->_objetos[$index]['totalcustomersRPROComplete']=$this->_getTotalCustomers($startDateTemp,$endingDateTemp,'RPRO',false);
            ///////////////////////////////////////////////////
            //Destinos RPRO con mas de un dollar de margen
            $this->_objetos[$index]['destinationsRPRO']=$this->_getDestinations($startDateTemp,$endingDateTemp,'RPRO',true);
            //Destino RPRO del dia anterior
            if($this->equal) $this->_objetos[$index]['destinationsRPROYesterday']=$this->_getDestinations($yesterday,$yesterday,'RPRO',false);
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
            $this->_objetos[$index]['totaldestinationsRPRO']['total_calls']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['complete_calls']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['minutes']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['cost']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['revenue']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['margin']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['costmin']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['ratemin']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['marginmin']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['yesterday']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['average']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['accumulated']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['forecast']=0;
            $this->_objetos[$index]['totaldestinationsRPRO']['previous_month']=0;
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
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPRO']['third_month']=0;
            //Total de destinos RPRO del cuarto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROFourthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-3')['firstday'],DateManagement::leastOneMonth($startDate,'-3')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPRO']['fourth_month']=0;
            //Total de destinos RPRO del quinto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROFifthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-4')['firstday'],DateManagement::leastOneMonth($startDate,'-4')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPRO']['fifth_month']=0;
            //Total de destinos RPRO del sexto mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROSixthMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-5')['firstday'],DateManagement::leastOneMonth($startDate,'-5')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPRO']['sixth_month']=0;
            //Total de destinos RPRO del septimo mes
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPROSeventhMonth']=$this->_getTotalDestinations(DateManagement::leastOneMonth($startDate,'-6')['firstday'],DateManagement::leastOneMonth($startDate,'-6')['lastday'],'RPRO',false);
            if($this->equal) $this->_objetos[$index]['totaldestinationsRPRO']['seventh_month']=0;
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
        $sql="SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.asr) AS asr, SUM(x.acd) AS acd, SUM(x.pdd) AS pdd, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (SUM(x.revenue)*100)/(SUM(x.cost)-100) AS margin_percentage
              FROM (SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE id_carrier_customer IN ({$carriers}) AND date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
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
        $sql="SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(asr) AS asr, SUM(acd) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(cost)=0 THEN 0 ELSE (SUM(revenue)*100)/(SUM(cost)-100) END AS margin_percentage, CASE WHEN SUM(cost)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 ELSE (SUM(cost)/SUM(minutes))*100 END AS costmin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 ELSE (SUM(revenue)/SUM(minutes))*100 END AS ratemin, CASE WHEN SUM(revenue)=0 THEN 0 WHEN SUM(minutes)=0 THEN 0 WHEN SUM(cost)=0 THEN 0 ELSE ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) END AS marginmin
              FROM (SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 WHEN SUM(incomplete_calls+complete_calls)=0 THEN 0 ELSE (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) END AS asr, CASE WHEN SUM(minutes)=0 THEN 0 WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$endDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN ({$carriers})
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
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=null;
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
        if($this->equal) $c11="<td style='".$this->_head['styleHead']."'></td>";
        if($this->equal) $c12="<td style='".$this->_head['styleHead']."'>Dia Anterior</td>";
        if($this->equal) $c13="<td style='".$this->_head['styleHead']."'></td>";
        if($this->equal) $c14="<td style='".$this->_head['styleHead']."'>Promedio 7D</td>";
        if($this->equal) $c15="<td style='".$this->_head['styleHead']."'>Acumulado Mes</td>";
        if($this->equal) $c16="<td style='".$this->_head['styleHead']."'>Proyeccion Mes</td>";
        if($this->equal) $c17="<td style='".$this->_head['styleHead']."'></td>";
        if($this->equal) $c18="<td style='".$this->_head['styleHead']."'>Mes Anterior</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18;
    }

    /**
     * Encargada de generar las columnas que van para la cabecera de destinos
     * @since 2.0
     * @access private
     * @return string
     */
    private function _getHeaderDestinations()
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
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
        $c11="<td style='".$this->_head['styleHead']."'>Cost/Min</td>";
        $c12="<td style='".$this->_head['styleHead']."'>Rate/Min</td>";
        $c13="<td style='".$this->_head['styleHead']."'>Margin/Min</td>";
        if($this->equal) $c14="<td style='".$this->_head['styleHead']."'></td>";
        if($this->equal) $c15="<td style='".$this->_head['styleHead']."'>Dia Anterior</td>";
        if($this->equal) $c16="<td style='".$this->_head['styleHead']."'></td>";
        if($this->equal) $c17="<td style='".$this->_head['styleHead']."'>Promedio 7D</td>";
        if($this->equal) $c18="<td style='".$this->_head['styleHead']."'>Acumulado Mes</td>";
        if($this->equal) $c19="<td style='".$this->_head['styleHead']."'>Proyeccion Mes</td>";
        if($this->equal) $c20="<td style='".$this->_head['styleHead']."'></td>";
        if($this->equal) $c21="<td style='".$this->_head['styleHead']."'>Mes Anterior</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
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
     * Retorna la fila con el nombre del manager y la posicion indicada
     * @access protected
     * @param int $pos posicion del manager
     * @param array $value datos del carrier
     * @param boolean $type, true es izquierda, false es derecha
     * @return string la celda construida
     */
    protected function _getNamesDestinations($pos,$value,$type=true)
    {
        $style=self::colorDestino($value['attribute']);
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
                $this->_objetos[$index]['total'.$index2]['total_calls']+=$value->total_calls;
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
                $this->_objetos[$index]['total'.$index2]['complete_calls']+=$value->complete_calls;
                $c3="<td style='".$style."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                $this->_objetos[$index]['total'.$index2]['minutes']+=$value->minutes;
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
                $c5="<td style='".$style."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
                $c7="<td style='".$style."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
                $this->_objetos[$index]['total'.$index2]['cost']+=$value->cost;
                $c8="<td style='".$style."' >".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $this->_objetos[$index]['total'.$index2]['revenue']+=$value->revenue;
                $margin=$value->margin;
                $c9="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $this->_objetos[$index]['total'.$index2]['margin']+=$value->margin;
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
            }
        }
        if($this->equal)
        {
            foreach($this->_objetos[$index][$index2."Yesterday"] as $key => $yesterday)
            {
                if($yesterday->$attribute == $phrase['attribute'])
                {
                    $c11="<td style='".$style."'>".$this->_upOrDown($yesterday->margin,$margin)."</td>";
                    $c12="<td style='".$style."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['yesterday']+=$yesterday->margin;
                }
            }
            foreach($this->_objetos[$index][$index2."Average"] as $key => $average)
            {
                if($average->$attribute == $phrase['attribute'])
                {
                    $c13="<td style='".$style."'>".$this->_upOrDown($average->margin,$margin)."</td>";
                    $c14="<td style='".$style."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['average']+=$average->margin;
                }
            }
            foreach($this->_objetos[$index][$index2."Accumulated"] as $key => $accumulated)
            {
                if($accumulated->$attribute == $phrase['attribute'])
                {
                    $c15="<td style='".$style."'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['accumulated']+=$accumulated->margin;
                }
            }
            $c16="<td style='".$style."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Forecast"][$phrase['attribute']])."</td>";
            $this->_objetos[$index]['total'.$index2]['forecast']+=$this->_objetos[$index][$index2."Forecast"][$phrase['attribute']];
            foreach ($this->_objetos[$index][$index2."PreviousMonth"] as $key => $previousMonth)
            {
                if($previousMonth->$attribute == $phrase['attribute'])
                {
                    $c17="<td style='".$style."'>".$this->_upOrDown($previousMonth->margin,$this->_objetos[$index][$index2."Forecast"][$phrase['attribute']])."</td>";
                    $c18="<td style='".$style."'>".Yii::app()->format->format_decimal($previousMonth->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['previous_month']+=$previousMonth->margin;
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
        if($c9==null) $c9="<td style='".$style."'>--</td>";
        if($c10==null) $c10="<td style='".$style."'>--</td>";
        if($this->equal && $c11==null) $c11="<td style='".$style."'>--</td>";
        if($this->equal && $c12==null) $c12="<td style='".$style."'>--</td>";
        if($this->equal && $c13==null) $c13="<td style='".$style."'>--</td>";
        if($this->equal && $c14==null) $c14="<td style='".$style."'>--</td>";
        if($this->equal && $c15==null) $c15="<td style='".$style."'>--</td>";
        if($this->equal && $c16==null) $c16="<td style='".$style."'>--</td>";
        if($this->equal && $c17==null) $c17="<td style='".$style."'>--</td>";
        if($this->equal && $c18==null) $c18="<td style='".$style."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18;
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
    private function _getRowDestinations($index,$index2,$attribute,$phrase,$style)
    {
        $margin=$c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
        foreach($this->_objetos[$index][$index2] as $key => $value)
        {
            if($value->$attribute == $phrase['attribute'])
            {               
                $c1="<td style='".$style."'>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>";
                $this->_objetos[$index]['total'.$index2]['total_calls']+=$value->total_calls;
                $c2="<td style='".$style."'>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>";
                $this->_objetos[$index]['total'.$index2]['complete_calls']+=$value->complete_calls;
                $c3="<td style='".$style."'>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                $this->_objetos[$index]['total'.$index2]['minutes']+=$value->minutes;
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($value->asr)."</td>";
                $c5="<td style='".$style."'>".Yii::app()->format->format_decimal($value->acd)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($value->pdd)."</td>";
                $c7="<td style='".$style."'>".Yii::app()->format->format_decimal($value->cost)."</td>";
                $this->_objetos[$index]['total'.$index2]['cost']+=$value->cost;
                $c8="<td style='".$style."' >".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $this->_objetos[$index]['total'.$index2]['revenue']+=$value->revenue;
                $margin=$value->margin;
                $c9="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin)."</td>";
                $this->_objetos[$index]['total'.$index2]['margin']+=$value->margin;
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($value->margin_percentage)."%</td>";
                $c11="<td style='".$style."'>".Yii::app()->format->format_decimal($value->costmin)."</td>";
                $this->_objetos[$index]['total'.$index2]['costmin']+=$value->costmin;
                $c12="<td style='".$style."'>".Yii::app()->format->format_decimal($value->ratemin)."</td>";
                $this->_objetos[$index]['total'.$index2]['ratemin']+=$value->ratemin;
                $c13="<td style='".$style."'>".Yii::app()->format->format_decimal($value->marginmin)."</td>";
                $this->_objetos[$index]['total'.$index2]['marginmin']+=$value->marginmin;
            }
        }
        if($this->equal)
        {
            foreach($this->_objetos[$index][$index2."Yesterday"] as $key => $yesterday)
            {
                if($yesterday->$attribute == $phrase['attribute'])
                {
                    $c14="<td style='".$style."'>".$this->_upOrDown($yesterday->margin,$margin)."</td>";
                    $c15="<td style='".$style."'>".Yii::app()->format->format_decimal($yesterday->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['yesterday']+=$yesterday->margin;
                }
            }
            foreach($this->_objetos[$index][$index2."Average"] as $key => $average)
            {
                if($average->$attribute == $phrase['attribute'])
                {
                    $c16="<td style='".$style."'>".$this->_upOrDown($average->margin,$margin)."</td>";
                    $c17="<td style='".$style."'>".Yii::app()->format->format_decimal($average->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['average']+=$average->margin;
                }
            }
            foreach($this->_objetos[$index][$index2."Accumulated"] as $key => $accumulated)
            {
                if($accumulated->$attribute == $phrase['attribute'])
                {
                    $c18="<td style='".$style."'>".Yii::app()->format->format_decimal($accumulated->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['accumulated']+=$accumulated->margin;
                }
            }
            $c19="<td style='".$style."'>".Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Forecast"][$phrase['attribute']])."</td>";
            $this->_objetos[$index]['total'.$index2]['forecast']+=$this->_objetos[$index][$index2."Forecast"][$phrase['attribute']];
            foreach ($this->_objetos[$index][$index2."PreviousMonth"] as $key => $previousMonth)
            {
                if($previousMonth->$attribute == $phrase['attribute'])
                {
                    $c20="<td style='".$style."'>".$this->_upOrDown($previousMonth->margin,$this->_objetos[$index][$index2."Forecast"][$phrase['attribute']])."</td>";
                    $c21="<td style='".$style."'>".Yii::app()->format->format_decimal($previousMonth->margin)."</td>";
                    $this->_objetos[$index]['total'.$index2]['previous_month']+=$previousMonth->margin;
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
        if($c9==null) $c9="<td style='".$style."'>--</td>";
        if($c10==null) $c10="<td style='".$style."'>--</td>";
        if($c11==null) $c11="<td style='".$style."'>--</td>";
        if($c12==null) $c12="<td style='".$style."'>--</td>";
        if($c13==null) $c13="<td style='".$style."'>--</td>";
        if($this->equal && $c14==null) $c14="<td style='".$style."'>--</td>";
        if($this->equal && $c15==null) $c15="<td style='".$style."'>--</td>";
        if($this->equal && $c16==null) $c16="<td style='".$style."'>--</td>";
        if($this->equal && $c17==null) $c17="<td style='".$style."'>--</td>";
        if($this->equal && $c18==null) $c18="<td style='".$style."'>--</td>";
        if($this->equal && $c19==null) $c19="<td style='".$style."'>--</td>";
        if($this->equal && $c20==null) $c20="<td style='".$style."'>--</td>";
        if($this->equal && $c21==null) $c21="<td style='".$style."'>--</td>";
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
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
                $this->_objetos[0]["total".$index]["third_month"]+=$third->margin;
            }
        }
        foreach($this->_objetos[0][$index."FourthMonth"] as $key => $fourth)
        {
            if($fourth->$attribute == $phrase['attribute'])
            {               
                $c3="<td style='".$style."'>".$this->_upOrDown($fourth->margin,$margin)."</td>";
                $c4="<td style='".$style."'>".Yii::app()->format->format_decimal($fourth->margin)."</td>";
                $this->_objetos[0]["total".$index]["fourth_month"]+=$fourth->margin;
            }
        }
        foreach($this->_objetos[0][$index."FifthMonth"] as $key => $fifth)
        {
            if($fifth->$attribute == $phrase['attribute'])
            {               
                $c5="<td style='".$style."'>".$this->_upOrDown($fifth->margin,$margin)."</td>";
                $c6="<td style='".$style."'>".Yii::app()->format->format_decimal($fifth->margin)."</td>";
                $this->_objetos[0]["total".$index]["fifth_month"]+=$fifth->margin;
            }
        }
        foreach($this->_objetos[0][$index."SixthMonth"] as $key => $sixth)
        {
            if($sixth->$attribute == $phrase['attribute'])
            {               
                $c7="<td style='".$style."'>".$this->_upOrDown($sixth->margin,$margin)."</td>";
                $c8="<td style='".$style."'>".Yii::app()->format->format_decimal($sixth->margin)."</td>";
                $this->_objetos[0]["total".$index]["sixth_month"]+=$sixth->margin;
            }
        }
        foreach($this->_objetos[0][$index."SeventhMonth"] as $key => $seventh)
        {
            if($seventh->$attribute == $phrase['attribute'])
            {               
                $c9="<td style='".$style."'>".$this->_upOrDown($seventh->margin,$margin)."</td>";
                $c10="<td style='".$style."'>".Yii::app()->format->format_decimal($seventh->margin)."</td>";
                $this->_objetos[0]["total".$index]["seventh_month"]+=$seventh->margin;
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
     * @param boolean $type true=mas de 1$ y false=menos de un dollar
     * @return string
     */
    private function _getRowTotal($index,$index2,$style,$type=true)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=null;
        //Total calls
        $total_calls=$this->_objetos[$index][$index2]['total_calls'];
        if(!$type) $total_calls=$this->_objetos[$index][$index2."Complete"]->total_calls;
        $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total_calls)."</td>";
        //Complete calls
        $complete_calls=$this->_objetos[$index][$index2]['complete_calls'];
        if(!$type) $complete_calls=$this->_objetos[$index][$index2."Complete"]->complete_calls;
        $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($complete_calls)."</td>";
        //Minutes
        $minutes=$this->_objetos[$index][$index2]['minutes'];
        if(!$type) $minutes=$this->_objetos[$index][$index2."Complete"]->minutes;
        $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($minutes)."</td>";
        //ASR
        $asr="";
        if(!$type) $asr=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->asr);
        $c4="<td style='".$this->_head[$style]."'>".$asr."</td>";
        //ACD
        $acd="";
        if(!$type) $acd=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->acd);
        $c5="<td style='".$this->_head[$style]."'>".$acd."</td>";
        //PDD
        $pdd="";
        if(!$type) $pdd=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->pdd);
        $c6="<td style='".$this->_head[$style]."'>".$pdd."</td>";
        //Cost
        $cost=$this->_objetos[$index][$index2]['cost'];
        if(!$type) $cost=$this->_objetos[$index][$index2."Complete"]->cost;
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($cost)."</td>";
        //Revenue
        $revenue=$this->_objetos[$index][$index2]['revenue'];
        if(!$type) $revenue=$this->_objetos[$index][$index2."Complete"]->revenue;
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($revenue)."</td>";
        //Margin
        $margin=$this->_objetos[$index][$index2]['margin'];
        if(!$type) $margin=$this->_objetos[$index][$index2."Complete"]->margin;
        $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($margin)."</td>";
        //Margin Percentage
        $margin_percentage="";
        if(!$type) $margin_percentage=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->margin_percentage);
        $c10="<td style='".$this->_head[$style]."'>".$margin_percentage."%</td>";
        if($this->equal)
        {
            //Simbolo dia anterior
            $yesterday=$this->_objetos[$index][$index2]['yesterday'];
            if(!$type) $yesterday=$this->_objetos[$index][$index2."Yesterday"]->margin;
            $c11="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday,$margin)."</td>";
            //Dia Anterior
            $c12="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday)."</td>";
            //Simbolo promedio
            $average=$this->_objetos[$index][$index2]['average'];
            if(!$type) $average=$this->_objetos[$index][$index2."Average"]->margin;
            $c13="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average,$margin)."</td>";
            //Promedio
            $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($average)."</td>";
            //Acumulado
            $accumulated=$this->_objetos[$index][$index2]['accumulated'];
            if(!$type) $accumulated=$this->_objetos[$index][$index2."Accumulated"]->margin;
            $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated)."</td>";
            //Proyeccion
            $forecast=$this->_objetos[$index][$index2]['forecast'];
            if(!$type) $forecast=$this->_objetos[$index][$index2."Forecast"];
            $c16="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($forecast)."</td>";
            //Simbolo del mes anterior
            $previousMonth=$this->_objetos[$index][$index2]['previous_month'];
            if(!$type) $previousMonth=$this->_objetos[$index][$index2."PreviousMonth"]->margin;
            $c17="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previousMonth,$forecast)."</td>";
            //Mes anterior
            $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($previousMonth)."</td>";
        }

        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18;
    }

    /**
     * Encargado de generar el html de la fila de totales
     * @since 2.0
     * @access private
     * @param int $index
     * @param string $index2
     * @param string $style
     * @param boolean $type true=mas de 1$ y false=menos de un dollar
     * @return string
     */
    private function _getRowTotalDestinations($index,$index2,$style,$type=true)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
        //Total calls
        $total_calls=$this->_objetos[$index][$index2]['total_calls'];
        if(!$type) $total_calls=$this->_objetos[$index][$index2."Complete"]->total_calls;
        $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($total_calls)."</td>";
        //Complete calls
        $complete_calls=$this->_objetos[$index][$index2]['complete_calls'];
        if(!$type) $complete_calls=$this->_objetos[$index][$index2."Complete"]->complete_calls;
        $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($complete_calls)."</td>";
        //Minutes
        $minutes=$this->_objetos[$index][$index2]['minutes'];
        if(!$type) $minutes=$this->_objetos[$index][$index2."Complete"]->minutes;
        $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($minutes)."</td>";
        //ASR
        $asr="";
        if(!$type) $asr=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->asr);
        $c4="<td style='".$this->_head[$style]."'>".$asr."</td>";
        //ACD
        $acd="";
        if(!$type) $acd=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->acd);
        $c5="<td style='".$this->_head[$style]."'>".$acd."</td>";
        //PDD
        $pdd="";
        if(!$type) $pdd=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->pdd);
        $c6="<td style='".$this->_head[$style]."'>".$pdd."</td>";
        //Cost
        $cost=$this->_objetos[$index][$index2]['cost'];
        if(!$type) $cost=$this->_objetos[$index][$index2."Complete"]->cost;
        $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($cost)."</td>";
        //Revenue
        $revenue=$this->_objetos[$index][$index2]['revenue'];
        if(!$type) $revenue=$this->_objetos[$index][$index2."Complete"]->revenue;
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($revenue)."</td>";
        //Margin
        $margin=$this->_objetos[$index][$index2]['margin'];
        if(!$type) $margin=$this->_objetos[$index][$index2."Complete"]->margin;
        $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($margin)."</td>";
        //Margin Percentage
        $margin_percentage="";
        if(!$type) $margin_percentage=Yii::app()->format->format_decimal($this->_objetos[$index][$index2."Complete"]->margin_percentage);
        $c10="<td style='".$this->_head[$style]."'>".$margin_percentage."%</td>";
        //Cost/Min
        $costmin=$this->_objetos[$index][$index2]['costmin'];
        if(!$type) $costmin="";
        $c11="<td style='".$this->_head[$style]."'>".$costmin."</td>";
        //Rate/Min
        $ratemin=$this->_objetos[$index][$index2]['ratemin'];
        if(!$type) $ratemin="";
        $c12="<td style='".$this->_head[$style]."'>".$ratemin."</td>";
        //Margin/Min
        $marginmin=$this->_objetos[$index][$index2]['marginmin'];
        if(!$type) $marginmin="";
        $c13="<td style='".$this->_head[$style]."'>".$marginmin."</td>";
        if($this->equal)
        {
            //Simbolo dia anterior
            $yesterday=$this->_objetos[$index][$index2]['yesterday'];
            if(!$type) $yesterday=$this->_objetos[$index][$index2."Yesterday"]->margin;
            $c14="<td style='".$this->_head[$style]."'>".$this->_upOrDown($yesterday,$margin)."</td>";
            //Dia Anterior
            $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($yesterday)."</td>";
            //Simbolo promedio
            $average=$this->_objetos[$index][$index2]['average'];
            if(!$type) $average=$this->_objetos[$index][$index2."Average"]->margin;
            $c16="<td style='".$this->_head[$style]."'>".$this->_upOrDown($average,$margin)."</td>";
            //Promedio
            $c17="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($average)."</td>";
            //Acumulado
            $accumulated=$this->_objetos[$index][$index2]['accumulated'];
            if(!$type) $accumulated=$this->_objetos[$index][$index2."Accumulated"]->margin;
            $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($accumulated)."</td>";
            //Proyeccion
            $forecast=$this->_objetos[$index][$index2]['forecast'];
            if(!$type) $forecast=$this->_objetos[$index][$index2."Forecast"];
            $c19="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($forecast)."</td>";
            //Simbolo del mes anterior
            $previousMonth=$this->_objetos[$index][$index2]['previous_month'];
            if(!$type) $previousMonth=$this->_objetos[$index][$index2."PreviousMonth"]->margin;
            $c20="<td style='".$this->_head[$style]."'>".$this->_upOrDown($previousMonth,$forecast)."</td>";
            //Mes anterior
            $c21="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($previousMonth)."</td>";
        }

        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
    }

    /**
     * Encargado de traer el total de meses anteriores
     */
    private function _getRowTotalMonth($index,$style,$type=true)
    {
        $margin=$c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        //Margin
        $margin=$this->_objetos[0][$index."Forecast"];
        //Tercer mes
        $third=$this->_objetos[0][$index]["third_month"];
        if(!$type) $third=$this->_objetos[0][$index."ThirdMonth"]->margin;
        $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown($third,$margin)."</td>";
        $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($third)."</td>";
        //Cuarto mes
        $fourth=$this->_objetos[0][$index]["fourth_month"];
        if(!$type) $fourth=$this->_objetos[0][$index."FourthMonth"]->margin;
        $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown($fourth,$margin)."</td>";
        $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($fourth)."</td>";
        //Quinto mes
        $fifth=$this->_objetos[0][$index]["fifth_month"];
        if(!$type) $fifth=$this->_objetos[0][$index."FifthMonth"]->margin;
        $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown($fifth,$margin)."</td>";
        $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($fifth)."</td>";
        //Sexto mes
        $sixth=$this->_objetos[0][$index]["sixth_month"];
        if(!$type) $sixth=$this->_objetos[0][$index."SixthMonth"]->margin;
        $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown($sixth,$margin)."</td>";
        $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($sixth)."</td>";
        //Septimo mes
        $seventh=$this->_objetos[0][$index]["seventh_month"];
        if(!$type) $seventh=$this->_objetos[0][$index."SeventhMonth"]->margin;
        $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown($seventh,$margin)."</td>";
        $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal($seventh)."</td>";
        
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }

    /**
     * Retorna las columnas de los index indicados, en este caso el calculo de porcentajes de los carriers seleccionado y el total de los carriers.
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones:
     *      - clientsTotalMoreThanTenDollars 
     *      - clientsTotalLessThanTenDollars
     *      - suppliersTotalMoreThanTenDollars
     *      * suppliersTotalLessThanTenDollars
     * @param string $index3 index secundario del objeto traido de base de datos sin condiciones, es decir el total
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @return string 
     */
    private function _getRowPercentage($index,$index2,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=null;
        //Total Calls
        $total_calls_condition=$this->_objetos[$index][$index2]['total_calls'];
        $total_calls_complete=$this->_objetos[$index][$index2."Complete"]->total_calls;
        if($total_calls_condition!=0 && $total_calls_complete!=0) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($total_calls_condition/$total_calls_complete)*(100))."%</td>";
        else $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Complete Calls
        $complete_calls_condition=$this->_objetos[$index][$index2]['complete_calls'];
        $complete_calls_complete=$this->_objetos[$index][$index2."Complete"]->complete_calls;
        if($complete_calls_condition!=0 && $complete_calls_complete!=0) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($complete_calls_condition/$complete_calls_complete)*(100))."%</td>";
        else $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Minutes
        $minutes_condition=$this->_objetos[$index][$index2]['minutes'];
        $minutes_complete=$this->_objetos[$index][$index2."Complete"]->minutes;
        if($minutes_condition!=0 && $minutes_complete!=0) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($minutes_condition/$minutes_complete)*(100))."%</td>";
        else $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //ASR
        $c4="<td style='".$this->_head[$style]."'></td>";
        //ACD
        $c5="<td style='".$this->_head[$style]."'></td>";
        //PDD
        $c6="<td style='".$this->_head[$style]."'></td>";
        //Cost
        $cost_condition=$this->_objetos[$index][$index2]['cost'];
        $cost_complete=$this->_objetos[$index][$index2."Complete"]->cost;
        if($cost_condition!=0 && $cost_complete!=0) $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($cost_condition/$cost_complete)*(100))."%</td>";
        else $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Revenue
        $revenue_condition=$this->_objetos[$index][$index2]['revenue'];
        $revenue_complete=$this->_objetos[$index][$index2."Complete"]->revenue;
        if($revenue_condition!=0 && $revenue_complete!=0) $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal(($revenue_condition/$revenue_complete)*(100))."%</td>";
        else $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Margin
        $margin_condition=$this->_objetos[$index][$index2]['margin'];
        $margin_complete=$this->_objetos[$index][$index2."Complete"]->margin;
        if($margin_condition!=0 && $margin_complete!=0) $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($margin_condition/$margin_complete)*(100))."%</td>";
        else $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Margin Percentage
        $c10="<td style='".$this->_head[$style]."'></td>";
        if($this->equal)
        {
            //Yesterday
            $yesterday_condition=$this->_objetos[$index][$index2]['yesterday'];
            $yesterday_complete=$this->_objetos[$index][$index2."Yesterday"]->margin;
            if($yesterday_condition!=0 && $yesterday_complete!=0 && $margin_condition!=0 && $margin_complete!=0)
            {
                $c11="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($yesterday_condition/$yesterday_complete)*(100),($margin_condition/$margin_complete)*(100))."</td>";
                $c12="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($yesterday_condition/$yesterday_complete)*(100))."%</td>";
            }
            else
            {
                $c11="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
                $c12="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            }
            //Average
            $average_condition=$this->_objetos[$index][$index2]['average'];
            $average_complete=$average=$this->_objetos[$index][$index2."Average"]->margin;
            if($average_condition!=0 && $average_complete!=0 && $margin_condition!=0 && $margin_complete!=0)
            {
                $c13="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($average_condition/$average_complete)*(100),($margin_condition/$margin_complete)*(100))."</td>";
                $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($average_condition/$average_complete)*(100))."%</td>";
            }
            else
            {
                $c13="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
                $c14="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            }
            //Accumulated
            $accumulated_condition=$this->_objetos[$index][$index2]['accumulated'];
            $accumulated_complete=$this->_objetos[$index][$index2."Accumulated"]->margin;
            if($accumulated_condition!=0 && $accumulated_complete!=0) $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($accumulated_condition/$accumulated_complete)*(100))."%</td>";
            else $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            //Forecast
            $forecast_condition=$this->_objetos[$index][$index2]['forecast'];
            $forecast_complete=$this->_objetos[$index][$index2."Forecast"];
            if($forecast_condition!=0 && $forecast_complete!=0) $c16="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($forecast_condition/$forecast_complete)*(100))."%</td>";
            else $c16="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            //Previous Month
            $previous_month_condition=$this->_objetos[$index][$index2]['previous_month'];
            $previous_month_complete=$this->_objetos[$index][$index2."PreviousMonth"]->margin;
            if($previous_month_condition!=0 && $previous_month_complete!=0 && $forecast_condition!=0 && $forecast_complete!=0)
            {
                $c17="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($previous_month_condition/$previous_month_complete)*(100),($forecast_condition/$forecast_complete)*(100))."</td>";
                $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($previous_month_condition/$previous_month_complete)*(100))."%</td>";
            }
            else
            {
                $c17="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
                $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            }
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18;
    }

    /**
     * Retorna las columnas de los index indicados, en este caso el calculo de porcentajes de los carriers seleccionado y el total de los carriers.
     * @access private
     * @param string $index index superior de los objetos
     * @param string $index2 index secundario del objeto traido de base de datos con las condiciones:
     *      - clientsTotalMoreThanTenDollars 
     *      - clientsTotalLessThanTenDollars
     *      - suppliersTotalMoreThanTenDollars
     *      * suppliersTotalLessThanTenDollars
     * @param string $index3 index secundario del objeto traido de base de datos sin condiciones, es decir el total
     * @param string $style es el estilo que se el asigna a las columnas en ese instante
     * @return string 
     */
    private function _getRowPercentageDestinations($index,$index2,$style)
    {
        $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=null;
        //Total Calls
        $total_calls_condition=$this->_objetos[$index][$index2]['total_calls'];
        $total_calls_complete=$this->_objetos[$index][$index2."Complete"]->total_calls;
        if($total_calls_condition!=0 && $total_calls_complete!=0) $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($total_calls_condition/$total_calls_complete)*(100))."%</td>";
        else $c1="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Complete Calls
        $complete_calls_condition=$this->_objetos[$index][$index2]['complete_calls'];
        $complete_calls_complete=$this->_objetos[$index][$index2."Complete"]->complete_calls;
        if($complete_calls_condition!=0 && $complete_calls_complete!=0) $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($complete_calls_condition/$complete_calls_complete)*(100))."%</td>";
        else $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Minutes
        $minutes_condition=$this->_objetos[$index][$index2]['minutes'];
        $minutes_complete=$this->_objetos[$index][$index2."Complete"]->minutes;
        if($minutes_condition!=0 && $minutes_complete!=0) $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($minutes_condition/$minutes_complete)*(100))."%</td>";
        else $c3="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //ASR
        $c4="<td style='".$this->_head[$style]."'></td>";
        //ACD
        $c5="<td style='".$this->_head[$style]."'></td>";
        //PDD
        $c6="<td style='".$this->_head[$style]."'></td>";
        //Cost
        $cost_condition=$this->_objetos[$index][$index2]['cost'];
        $cost_complete=$this->_objetos[$index][$index2."Complete"]->cost;
        if($cost_condition!=0 && $cost_complete!=0) $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($cost_condition/$cost_complete)*(100))."%</td>";
        else $c7="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Revenue
        $revenue_condition=$this->_objetos[$index][$index2]['revenue'];
        $revenue_complete=$this->_objetos[$index][$index2."Complete"]->revenue;
        if($revenue_condition!=0 && $revenue_complete!=0) $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal(($revenue_condition/$revenue_complete)*(100))."%</td>";
        else $c8="<td style='".$this->_head[$style]."' >".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Margin
        $margin_condition=$this->_objetos[$index][$index2]['margin'];
        $margin_complete=$this->_objetos[$index][$index2."Complete"]->margin;
        if($margin_condition!=0 && $margin_complete!=0) $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($margin_condition/$margin_complete)*(100))."%</td>";
        else $c9="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        //Margin Percentage
        $c10="<td style='".$this->_head[$style]."'></td>";
        //Cost/Min
        $c11="<td style='".$this->_head[$style]."'></td>";
        //Rate/Min
        $c12="<td style='".$this->_head[$style]."'></td>";
        //Margin/Min
        $c13="<td style='".$this->_head[$style]."'></td>";
        if($this->equal)
        {
            //Yesterday
            $yesterday_condition=$this->_objetos[$index][$index2]['yesterday'];
            $yesterday_complete=$this->_objetos[$index][$index2."Yesterday"]->margin;
            if($yesterday_condition!=0 && $yesterday_complete!=0 && $margin_condition!=0 && $margin_complete!=0)
            {
                $c14="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($yesterday_condition/$yesterday_complete)*(100),($margin_condition/$margin_complete)*(100))."</td>";
                $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($yesterday_condition/$yesterday_complete)*(100))."%</td>";
            }
            else
            {
                $c14="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
                $c15="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            }
            //Average
            $average_condition=$this->_objetos[$index][$index2]['average'];
            $average_complete=$average=$this->_objetos[$index][$index2."Average"]->margin;
            if($average_condition!=0 && $average_complete!=0 && $margin_condition!=0 && $margin_complete!=0)
            {
                $c16="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($average_condition/$average_complete)*(100),($margin_condition/$margin_complete)*(100))."</td>";
                $c17="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($average_condition/$average_complete)*(100))."%</td>";
            }
            else
            {
                $c16="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
                $c17="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            }
            //Accumulated
            $accumulated_condition=$this->_objetos[$index][$index2]['accumulated'];
            $accumulated_complete=$this->_objetos[$index][$index2."Accumulated"]->margin;
            if($accumulated_condition!=0 && $accumulated_complete!=0) $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($accumulated_condition/$accumulated_complete)*(100))."%</td>";
            else $c18="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            //Forecast
            $forecast_condition=$this->_objetos[$index][$index2]['forecast'];
            $forecast_complete=$this->_objetos[$index][$index2."Forecast"];
            if($forecast_condition!=0 && $forecast_complete!=0) $c19="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($forecast_condition/$forecast_complete)*(100))."%</td>";
            else $c19="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            //Previous Month
            $previous_month_condition=$this->_objetos[$index][$index2]['previous_month'];
            $previous_month_complete=$this->_objetos[$index][$index2."PreviousMonth"]->margin;
            if($previous_month_condition!=0 && $previous_month_complete!=0 && $forecast_condition!=0 && $forecast_complete!=0)
            {
                $c20="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($previous_month_condition/$previous_month_complete)*(100),($forecast_condition/$forecast_complete)*(100))."</td>";
                $c21="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($previous_month_condition/$previous_month_complete)*(100))."%</td>";
            }
            else
            {
                $c20="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
                $c21="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
            }
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10.$c11.$c12.$c13.$c14.$c15.$c16.$c17.$c18.$c19.$c20.$c21;
    }

    /**
     * Encargado de traer el total de meses anteriores
     */
    private function _getRowPercentageMonth($index,$style)
    {
        $margin=$c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=null;
        //Forecast
        $forecast_condition=$this->_objetos[0][$index]['forecast'];
        $forecast_complete=$forecast=$this->_objetos[0][$index."Forecast"];
        //Tercer mes
        $third_condition=$this->_objetos[0][$index]["third_month"];
        $third_complete=$this->_objetos[0][$index."ThirdMonth"]->margin;
        if($forecast_condition!=0 && $forecast_complete!=0 && $third_condition!=0 && $third_complete!=0)
        {
            $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($third_condition/$third_complete)*(100),($forecast_condition/$forecast_complete)*(100))."</td>";
            $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($third_condition/$third_complete)*(100))."%</td>";
        }
        else
        {
            $c1="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
            $c2="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        }
        //Cuarto mes
        $fourth_condition=$this->_objetos[0][$index]["fourth_month"];
        $fourth_complete=$this->_objetos[0][$index."FourthMonth"]->margin;
        if($forecast_condition!=0 && $forecast_complete!=0 && $fourth_condition!=0 && $fourth_complete!=0)
        {
            $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($fourth_condition/$fourth_complete)*(100),($forecast_condition/$forecast_complete)*(100))."</td>";
            $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($fourth_condition/$fourth_complete)*(100))."%</td>";
        }
        else
        {
            $c3="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
            $c4="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        }
        //Quinto mes
        $fifth_condition=$this->_objetos[0][$index]["fifth_month"];
        $fifth_complete=$this->_objetos[0][$index."FifthMonth"]->margin;
        if($forecast_condition!=0 && $forecast_complete!=0 && $fifth_condition!=0 && $fifth_complete!=0)
        {
            $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($fifth_condition/$fifth_complete)*(100),($forecast_condition/$forecast_complete)*(100))."</td>";
            $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($fifth_condition/$fifth_complete)*(100))."%</td>";
        }
        else
        {
            $c5="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
            $c6="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        }
        //Sexto mes
        $sixth_condition=$this->_objetos[0][$index]["sixth_month"];
        $sixth_complete=$this->_objetos[0][$index."SixthMonth"]->margin;
        if($forecast_condition!=0 && $forecast_complete!=0 && $sixth_condition!=0 && $sixth_complete!=0)
        {
            $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($sixth_condition/$sixth_complete)*(100),($forecast_condition/$forecast_complete)*(100))."</td>";
            $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($sixth_condition/$sixth_complete)*(100))."%</td>";
        }
        else
        {
            $c7="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
            $c8="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        }
        //Sexto mes
        $seventh_condition=$this->_objetos[0][$index]["seventh_month"];
        $seventh_complete=$this->_objetos[0][$index."SeventhMonth"]->margin;
        if($forecast_condition!=0 && $forecast_complete!=0 && $seventh_condition!=0 && $seventh_complete!=0)
        {
            $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown(($seventh_condition/$seventh_complete)*(100),($forecast_condition/$forecast_complete)*(100))."</td>";
            $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal(($seventh_condition/$seventh_complete)*(100))."%</td>";
        }
        else
        {
            $c9="<td style='".$this->_head[$style]."'>".$this->_upOrDown((0)*(100),(0)*(100))."</td>";
            $c10="<td style='".$this->_head[$style]."'>".Yii::app()->format->format_decimal((0)*(100))."%</td>";
        }
        return $c1.$c2.$c3.$c4.$c5.$c6.$c7.$c8.$c9.$c10;
    }
}
?>