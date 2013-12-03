<?php
/**
* @package reportes
*/
class AltoImpacto extends Reportes
{
    /**
     * @var boolean
     */
    public $type;
    /**
     * Atributo encargado de almacenar la data traida de base de datos
     * @var array
     */
    private $_objetos;
    /**
     * @var array
     */
    private $_head;

    function __construct()
    {
        $this->_objetos=array();
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
	* Genera la tabla de Alto Impacto (+10$)
    * @access public
	* @param date $start fecha inicio a consultar
    * @param date $ending fecha fin a consultar
    * @param boolean $type true=completo, false=resumido
	* @return string con la tabla armada
	*/
	public function reporte($start,$end,$type=true)
	{
        //ini_set('max_execution_time', 60);
        //Consigo la data respactiva
        $this->_loopData($start,$end);
        
        //Cuento el numero de objetos en el array
        $num=count($this->_objetos);
        $last=$num-1;
        

            

        
        /*
        //Loscuento para saber que numero seguir en el resto
        $numCustomer=count($objetos[$last]['customersWithMoreThanTenDollars']);
        $numSupplier=count($objetos[$last]['providersWithMoreThanTenDollars']);
        $numDestinationExt=count($objetos[$last]['externalDestinationsMoreThanTenDollars']);
        $numDestinationInt=count($objetos[$last]['internalDestinationsWithMoreThanTenDollars']);
        //Organizo los datos
        //Organizo los datos
        $sorted['customersWithMoreThanTenDollars']=self::sort($objetos[$last]['customersWithMoreThanTenDollars'],'cliente');
        $sorted['customersWithLessThanTenDollars']=self::sort($objetos[$last]['customersWithLessThanTenDollars'],'cliente');
        $sorted['providersWithMoreThanTenDollars']=self::sort($objetos[$last]['providersWithMoreThanTenDollars'],'proveedor');
        $sorted['providersWithLessThanTenDollars']=self::sort($objetos[$last]['providersWithLessThanTenDollars'],'proveedor');
        $sorted['externalDestinationsMoreThanTenDollars']=self::sort($objetos[$last]['externalDestinationsMoreThanTenDollars'],'destino');
        $sorted['externalDestinationsLessThanTenDollars']=self::sort($objetos[$last]['externalDestinationsLessThanTenDollars'],'destino');
        $sorted['internalDestinationsWithMoreThanTenDollars']=self::sort($objetos[$last]['internalDestinationsWithMoreThanTenDollars'],'destino');
        $sorted['internalDestinationsWithLessThanTenDollars']=self::sort($objetos[$last]['internalDestinationsWithLessThanTenDollars'],'destino');
        */
        $body="<tabla>";
        $body.="</tabla>";

        /*
        $cuerpo="<table>";
        for($row=0; $row < 8; $row++)
        { 
            $cuerpo.="<tr>";
            switch ($row)
            {
                case 0:
                case 2:
                case 4:
                case 6:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td style='text-align:center;background-color:#999999; color:#FFFFFF;'></td>";
                        }
                        elseif($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td style='text-align:center;background-color:#999999; color:#FFFFFF;'>".$objetos[$col-1]['title']."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td style='text-align:center;background-color:#999999; color:#FFFFFF;'></td>";
                        }
                    }
                    break;
                    //Clientes con mas de 10$
                case 1:
                    $head=array(
                        'title'=>'Clientes (+10)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['customersWithMoreThanTenDollars'],0,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableCarriers($sorted['customersWithMoreThanTenDollars'],$objetos[$col-1]['customersWithMoreThanTenDollars'],'cliente',0,$head).
                            self::getHtmlTotalCarriers($objetos[$col-1]['clientsTotalMoreThanTenDollars'],$objetos[$col-1]['totalCustomer'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['customersWithMoreThanTenDollars'],0,false)."</td>";
                        }
                    }
                    break;
                    //Clientes con menos de 10$
                case 3:
                    $head=array(
                        'title'=>'Clientes (Resto)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['customersWithLessThanTenDollars'],$numCustomer,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableCarriers($sorted['customersWithLessThanTenDollars'],$objetos[$col-1]['customersWithLessThanTenDollars'],'cliente',$numCustomer,$head).
                            self::getHtmlTotalCarriers($objetos[$col-1]['clientsTotalLessThanTenDollars'],$objetos[$col-1]['totalCustomer'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['customersWithLessThanTenDollars'],$numCustomer,false)."</td>";
                        }
                    }
                    break;
                    //Proveedores con mas de 10$
                case 5:
                    $head=array(
                        'title'=>'Proveedor (+10)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['providersWithMoreThanTenDollars'],0,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableCarriers($sorted['providersWithMoreThanTenDollars'],$objetos[$col-1]['providersWithMoreThanTenDollars'],'proveedor',0,$head).
                            self::getHtmlTotalCarriers($objetos[$col-1]['suppliersTotalMoreThanTenDollars'],$objetos[$col-1]['totalSuppliers'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['providersWithMoreThanTenDollars'],0,false)."</td>";
                        }
                    }
                    break;
                    //Proveedores con menos de 10
                case 7:
                    $head=array(
                        'title'=>'Proveedor (Resto)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['providersWithLessThanTenDollars'],$numSupplier,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableCarriers($sorted['providersWithLessThanTenDollars'],$objetos[$col-1]['providersWithLessThanTenDollars'],'proveedor',$numSupplier,$head).
                            self::getHtmlTotalCarriers($objetos[$col-1]['suppliersTotalLessThanTenDollars'],$objetos[$col-1]['totalSuppliers'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTable($head,$sorted['providersWithLessThanTenDollars'],$numSupplier,false)."</td>";
                        }
                    }
                    break;
            }
            $cuerpo.="</tr>";
        }
        $cuerpo.="</table><table>";
        for($row=0; $row < 8; $row++)
        { 
            $cuerpo.="<tr>";
            switch ($row)
            {
                case 0:
                case 2:
                case 4:
                case 6:
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td></td>";
                        }
                        elseif($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td style='text-align:center;background-color:#999999; color:#FFFFFF;'>".$objetos[$col-1]['title']."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td></td>";
                        }
                    }
                    break;
                    //Destinos externos con mas de 10$
                case 1:
                    $head=array(
                        'title'=>'Destinos Externos (+10$)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['externalDestinationsMoreThanTenDollars'],0,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDestinations($sorted['externalDestinationsMoreThanTenDollars'],$objetos[$col-1]['externalDestinationsMoreThanTenDollars'],'destino',$head).
                            self::getHtmlTotalDestinations($objetos[$col-1]['totalExternalDestinationsMoreThanTenDollars'],$objetos[$col-1]['totalExternalDestinations'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['externalDestinationsMoreThanTenDollars'],0,false)."</td>";
                        }
                    }
                    break;
                    //Destinos externos con menos de 10$
                case 3:
                    $head=array(
                        'title'=>'Destinos Externos (Resto)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['externalDestinationsLessThanTenDollars'],$numDestinationExt,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDestinations($sorted['externalDestinationsLessThanTenDollars'],$objetos[$col-1]['externalDestinationsLessThanTenDollars'],'destino',$head).
                            self::getHtmlTotalDestinations($objetos[$col-1]['totalExternalDestinationsLessThanTenDollars'],$objetos[$col-1]['totalExternalDestinations'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['externalDestinationsLessThanTenDollars'],$numDestinationExt,false)."</td>";
                        }
                    }
                    break;
                //Destinos internos con mas de 10$
                case 5:
                    $head=array(
                        'title'=>'Destinos Internos (+10$)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['internalDestinationsWithMoreThanTenDollars'],0,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDestinations($sorted['internalDestinationsWithMoreThanTenDollars'],$objetos[$col-1]['internalDestinationsWithMoreThanTenDollars'],'destino',$head).
                            self::getHtmlTotalDestinations($objetos[$col-1]['totalInternalDestinationsWithMoreThanTenDollars'],$objetos[$col-1]['totalInternalDestinations'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['internalDestinationsWithMoreThanTenDollars'],0,false)."</td>";
                        }
                    }
                    break;
                //Destinos internos con menos de 10$
                case 7:
                    $head=array(
                        'title'=>'Destinos Internos (Resto)',
                        'styleHead'=>'background-color:#615E5E; color:#62C25E; width:10%; height:100%;',
                        'styleFooter'=>'background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for ($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['internalDestinationsWithLessThanTenDollars'],$numDestinationInt,true)."</td>";
                        }
                        elseif ($col>0 && $col<$num+1)
                        {
                            $cuerpo.="<td>".self::getHtmlTableDestinations($sorted['internalDestinationsWithLessThanTenDollars'],$objetos[$col-1]['internalDestinationsWithLessThanTenDollars'],'destino',$head).
                            self::getHtmlTotalDestinations($objetos[$col-1]['totalInternalDestinationsWithLessThanTenDollars'],$objetos[$col-1]['totalInternalDestinations'],$head)."</td>";
                            if($col!=$num)
                            {
                                $cuerpo.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $cuerpo.="<td>".self::getHtmlTableDes($head,$sorted['internalDestinationsWithLessThanTenDollars'],$numDestinationInt,false)."</td>";
                        }
                    }
                    break;
            }
            $cuerpo.="</tr>";
        }
        $cuerpo.="</table>";
        return $cuerpo;*/
	}

    /**
     *
     */
    private function _loopData($start,$end)
    {
        //verifico las fechas
        $array=self::valDates($start,$end);
        $startDateTemp=$startDate=$array['startDate'];
        $endingDateTemp=$endingDate=$array['endingDate'];
        $arrayStartTemp=null;
        $index=0;
        while (self::isLower($startDateTemp,$endingDate))
        {
            $arrayStartTemp=explode('-',$startDateTemp);
            $endingDateTemp=self::maxDate($arrayStartTemp[0]."-".$arrayStartTemp[1]."-".self::howManyDays($startDateTemp),$endingDate);

            //El titulo que va a llevar la seccion
            $this->_objetos[$index]['title']=self::reportTitle($startDateTemp,$endingDateTemp);
            /***/
            //Guardo los datos de los clientes con mas de 10 dolares de ganancia
            $this->_objetos[$index]['customersWithMoreThanTenDollars']=$this->_getCarriers($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los clientes con mas de 10 dolares de ganancia
            $this->_objetos[$index]['clientsTotalMoreThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de todos los clientes
            $this->_objetos[$index]['totalCustomer']=$this->_getTotalCompleteCarriers($startDateTemp,$endingDateTemp,true);
            //Guardo los datos de los clientes con menos de 10 dolares de ganancia 
            $this->_objetos[$index]['customersWithLessThanTenDollars']=$this->_getCarriers($startDate,$endingDate,true,false);
            //Guardo los datos de los totales de los clientes con menis de 10 dolares de ganancia
            $this->_objetos[$index]['clientsTotalLessThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,true,false);
            /***/
            //Guardo los datos de los proveedores con mas de 10 dolares de ganancia
            $this->_objetos[$index]['providersWithMoreThanTenDollars']=$this->_getCarriers($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los proveedores con mas de 10 dolares de ganancia
            $this->_objetos[$index]['suppliersTotalMoreThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de todos los proveedores
            $this->_objetos[$index]['totalSuppliers']=$this->_getTotalCompleteCarriers($startDateTemp,$endingDateTemp,false);
            //Guardo los datos de los proveedores con menos de 10 dolares de ganancia
            $this->_objetos[$index]['providersWithLessThanTenDollars']=$this->_getCarriers($startDateTemp,$endingDateTemp,false,false);
            //Gurado los datos de los totales de los proveedores con menos de 10 dolares de ganancia
            $this->_objetos[$index]['suppliersTotalLessThanTenDollars']=$this->_getTotalCarriers($startDateTemp,$endingDateTemp,false,false);
            /***/
            //Guardo los datos de los destinos externos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['externalDestinationsMoreThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalExternalDestinationsMoreThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,true,true);
            //Guardo los datos de los totales de los destinos externos
            $this->_objetos[$index]['totalExternalDestinations']=$this->_getTotalCompleteDestination($startDateTemp,$endingDateTemp,true);
            //Guardo los datos de los destinos externos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['externalDestinationsLessThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,true,false);
            //Guardo los datos de los totales de los destinos externos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalExternalDestinationsLessThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,true,false);
            /***/
            //Guardo los datos de los destinos internos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['internalDestinationsWithMoreThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los destinos internos con mas de 10 dolares de ganancia
            $this->_objetos[$index]['totalInternalDestinationsWithMoreThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,false,true);
            //Guardo los datos de los totales de los destinos internos
            $this->_objetos[$index]['totalInternalDestinations']=$this->_getTotalCompleteDestination($startDateTemp,$endingDateTemp,false);
            //Guardo los datos de los destinos internos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['internalDestinationsWithLessThanTenDollars']=$this->_getDestination($startDateTemp,$endingDateTemp,false,false);
            //Guardo los datos de los totales de los destinos internos con menos de 10 dolares de ganancia
            $this->_objetos[$index]['totalInternalDestinationsWithLessThanTenDollars']=$this->_getTotalDestination($startDateTemp,$endingDateTemp,false,false);

            /*Itero la fecha*/
            $startDateTemp=self::firstDayNextMonth($startDateTemp);
            $index+=1;
        }
    }

    /**
     * Encargado de traer los datos de los carriers
     * @access private
     * @param date $startDate fecha de inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=+10$, false=-10$
     * @return array $models
     */
    private function _getCarriers($startDate,$endingDate,$typeCarrier=true,$type=true)
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
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @param boolean $type true=margen mayor a 10$, false=margen menor a 10$
     * @return object $model
     */
    private function _getTotalCarriers($startDate,$endingDate,$typeCarrier=true,$type=true)
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
     * @param date $startDate
     * @param date $endingDate
     * @param boolean $typeCarrier true=clientes, false=proveedores
     * @return array $models
     */
    private function _getTotalCompleteCarriers($startDate,$endingDate,$typeCarrier=true)
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
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$
     * @return array $models
     */
    private function _getDestination($startDate,$endingDate,$typeDestination=true,$type=true)
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
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @param boolean $type true=+10$, false=-10$
     * @return object $model
     */
    private function _getTotalDestination($startDate,$endingDate,$typeDestination=true,$type=true)
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
     * @param date $startDate fecha inicio de la consulta
     * @param date $endingDate fecha fin de la consulta
     * @param boolean $typeDestination true=external, false=internal
     * @return object $model
     */
    private function _getTotalCompleteDestination($startDate,$endingDate,$typeDestination=true)
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

    /**
     * Obtiene el html de las tablas para los carriers
     * @access private
     * @static
     * @param array $list la lista del orden que regira la tabla
     * @param array $data Es el array con los objetos obtenidos de base de datos
     * @param string $attribute este parametro es para que la funcion funcione tanto con carrier customer como con supplier
     * @param array $head trae las caracteristicas de la cabecera de la tabla
     * @return string $body es el html de la tabla formada
     */
    /*private static function getHtmlTableCarriers($list,$data,$attribute,$position,$head)
    {
        $columns=array('Cost','Revenue','Margin');
        if(self::$type) $columns=array('TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','PN');
        $body="<table>
                  <thead>";
        $body.=self::cabecera($columns,$head['styleHead']);
        $body.="</thead>
                 <tbody>";
        if($data!=NULL)
        {
            foreach ($list as $key => $carrier)
            {
                if($position!=null)
                    $position=$position+1;
                else
                    $position=$key+1;
                $body.=self::getRow($attribute,$carrier['attribute'],$data,$position);
            }
        }
        else
        {
            $body.="<tr><td colspan='11'>No se encontraron resultados</td></tr>";
        }
        $body.="</tbody>
                 </table>";
        return $body;
    }*/

    /**
     *
     */
    /*private static function getHtmlTableDestinations($list,$data,$attribute,$head)
    {
        $columns="";
        if(self::$type) $columns.="<td>TotalCalls</td><td>CompleteCalls</td><td>Minutes</td><td>ASR</td><td>ACD</td><td>PDD</td>";
        $columns.="<td>Cost</td><td>Revenue</td><td>Margin</td>";
        if(self::$type) $columns.="<td>Margin%</td><td>Cost/Min</td><td>Rate/Min</td><td>Margin/Min</td>";

        $body="<table>
                    <thead>
                        <tr style='".$head['styleHead']."'>
                            ".$columns."
                        </tr>
                    </thead>
                 <tbody>";
        if($data!=NULL)
        {
            foreach ($list as $key => $carrier)
            {
                $position=$key+1;
                $body.=self::getRowDestinations($attribute,$carrier['attribute'],$data,$position);
            }
        }
        else
        {
            $body.="<tr><td colspan='13'>No se encontraron resultados</td></tr>";
        }
        $body.="</tbody>
                 </table>";
        return $body;
    }*/

    /**
     * Recibe un objeto de modelo y un atributo, retorna una fila <tr> con los datos del objeto
     * @access protected
     * @static
     * @param string $attribute es el atributo del objeto con el que se hará la comparacion
     * @param string $phrase es la frase con la que debe conincidir el atributo 
     * @param array $objeto es el objeto traido de base de datos
     * @param int $position es el numero para indicar el color de la fila en la tabla 
     * @return string
     */
    /*private static function getRow($attribute,$phrase,$object,$position)
    {
        $body="<tr>";
        $style=self::colorEstilo($position);
        foreach ($object as $key => $value)
        {
            if($value->$attribute == $phrase)
            {
                if(self::$type) $body.="<td style='".$style."' >".Yii::app()->format->format_decimal($value->total_calls,0)."</td>
                                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>
                                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->minutes)."</td>
                                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->asr)."</td>
                                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->acd)."</td>
                                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->pdd)."</td>";
                $body.="<td style='".$style."' >".Yii::app()->format->format_decimal($value->cost)."</td>
                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->revenue)."</td>
                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->margin)."</td>";
                if(self::$type) $body.="<td style='".$style."' >".Yii::app()->format->format_decimal($value->margin_percentage)."</td>
                                        <td style='".$style."' >".Yii::app()->format->format_decimal($value->posicion_neta)."</td>";
                $body.="</tr>";
                return $body;
            }
        }
        $colnum=3;
        if(self::$type) $colnum=11;

        for ($i=0; $i < $colnum; $i++)
        { 
            $body.="<td style='".$style."' >--</td>";
        } 
        $body.="</tr>";
        return $body;
    }*/

    /**
     * Recibe un objeto de modelo y un atributo, retorna una fila <tr> con los datos del objeto
     * @access protected
     * @static
     * @param string $attribute es el atributo del objeto con el que se hará la comparacion
     * @param string $phrase es la frase con la que debe conincidir el atributo 
     * @param array $objeto es el objeto traido de base de datos
     * @param int $position es el numero para indicar el color de la fila en la tabla 
     * @return string
     */
    /*private static function getRowDestinations($attribute,$phrase,$object,$position)
    {
        $body="";
        foreach ($object as $key => $value)
        {
            $style=self::colorDestino($value->$attribute);
            $body=$style;
            if($value->$attribute == $phrase)
            {
                if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($value->total_calls,0)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->complete_calls,0)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->minutes)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->asr)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->acd)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->pdd)."</td>";
                $body.="<td>".Yii::app()->format->format_decimal($value->cost)."</td>
                        <td>".Yii::app()->format->format_decimal($value->revenue)."</td>
                        <td>".Yii::app()->format->format_decimal($value->margin)."</td>";
                if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($value->posicion_neta)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->costmin)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->ratemin)."</td>
                                        <td>".Yii::app()->format->format_decimal($value->marginmin)."</td>";
                $body.="</tr>";
                return $body;
            }
        }
        $colnum=3;
        $body=$style;
        if(self::$type) $colnum=13;

        for ($i=0; $i < $colnum; $i++)
        { 
            $body.="<td>--</td>";
        } 
        $body.="</tr>";
        return $body;
    }*/

    /**
     * Retorna una tabla con los totales de los objetos pasados como parametros
     * @access private
     * @static
     * @param CActiveRecord $totalCondition es el objeto que totaliza los que cumplen la condicion
     * @param CACtiveRecord $total es el objeto que totaliza con o sin condicion
     * @return string
     */
    /*private static function getHtmlTotalCarriers($totalCondition,$total,$head)
    {
        $columns=array('Cost','Revenue','Margin');
        if(self::$type) $columns=array('TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','');
        $body="<table>
                    <tr style='".$head['styleFooter']."'>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($totalCondition->total_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($totalCondition->complete_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($totalCondition->minutes)."</td>
                                <td></td>
                                <td></td>
                                <td></td>";
        $body.="<td>".Yii::app()->format->format_decimal($totalCondition->cost)."</td>
                <td>".Yii::app()->format->format_decimal($totalCondition->revenue)."</td>
                <td>".Yii::app()->format->format_decimal($totalCondition->margin)."</td>";
        if(self::$type) $body.="<td></td>
                                <td></td>";
        $body.="</tr>
                <tr style='".$head['styleFooterTotal']."'>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($total->total_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($total->complete_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($total->minutes)."</td>
                                <td>".Yii::app()->format->format_decimal($total->asr)."</td>
                                <td>".Yii::app()->format->format_decimal($total->acd)."</td>
                                <td>".Yii::app()->format->format_decimal($total->pdd)."</td>";
        $body.="<td>".Yii::app()->format->format_decimal($total->cost)."</td>
                <td>".Yii::app()->format->format_decimal($total->revenue)."</td>
                <td>".Yii::app()->format->format_decimal($total->margin)."</td>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($total->margin_percentage)."%</td>
                                <td></td>";
        $body.="</tr>"
                .self::cabecera($columns,$head['styleHead'])."
                <tr style='".$head['styleFooterTotal']."'>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal(($totalCondition->total_calls/$total->total_calls)*(100))."%</td>
                                <td>".Yii::app()->format->format_decimal(($totalCondition->complete_calls/$total->complete_calls)*(100))."%</td>
                                <td>".Yii::app()->format->format_decimal(($totalCondition->minutes/$total->minutes)*(100))."%</td>
                                <td></td>
                                <td></td>
                                <td></td>";
        $body.="<td>".Yii::app()->format->format_decimal(($totalCondition->cost/$total->cost)*(100))."%</td>
                <td>".Yii::app()->format->format_decimal(($totalCondition->revenue/$total->revenue)*(100))."%</td>
                <td>".Yii::app()->format->format_decimal(($totalCondition->margin/$total->margin)*(100))."%</td>";
        if(self::$type) $body.="<td></td>
                                <td></td>";
        $body.="</tr>
                </table>";
        return $body;
    }*/

    /**
     * Retorna una tabla con los totales de los objetos pasados como parametros
     * @access private
     * @static
     * @param CActiveRecord $totalCondition es el objeto que totaliza los que cumplen la condicion
     * @param CACtiveRecord $total es el objeto que totaliza con o sin condicion
     * @return string
     */
    /*private static function getHtmlTotalDestinations($totalCondition,$total,$head)
    {
        $columns=array('Cost','Revenue','Margin');
        if(self::$type) $columns=array('TotalCalls','CompleteCalls','Minutes','ASR','ACD','PDD','Cost','Revenue','Margin','Margin%','Cost/Min','Rate/Min','Margin/Min');
        $body="<table>
                    <tr style='".$head['styleFooter']."'>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($totalCondition->total_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($totalCondition->complete_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($totalCondition->minutes)."</td>
                                <td></td>
                                <td></td>
                                <td></td>";
        $body.="<td>".Yii::app()->format->format_decimal($totalCondition->cost)."</td>
                <td>".Yii::app()->format->format_decimal($totalCondition->revenue)."</td>
                <td>".Yii::app()->format->format_decimal($totalCondition->margin)."</td>";
        if(self::$type) $body.="<td></td>
                                <td>".Yii::app()->format->format_decimal($totalCondition->costmin)."</td>
                                <td>".Yii::app()->format->format_decimal($totalCondition->ratemin)."</td>
                                <td>".Yii::app()->format->format_decimal($totalCondition->marginmin)."</td>";
        $body.="</tr>
                <tr style='".$head['styleFooterTotal']."'>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($total->total_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($total->complete_calls,0)."</td>
                                <td>".Yii::app()->format->format_decimal($total->minutes)."</td>
                                <td>".Yii::app()->format->format_decimal($total->asr)."</td>
                                <td>".Yii::app()->format->format_decimal($total->acd)."</td>
                                <td>".Yii::app()->format->format_decimal($total->pdd)."</td>";
        $body.="<td>".Yii::app()->format->format_decimal($total->cost)."</td>
                <td>".Yii::app()->format->format_decimal($total->revenue)."</td>
                <td>".Yii::app()->format->format_decimal($total->margin)."</td>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal($total->margin_percentage)."%</td>
                                <td></td>
                                <td></td>
                                <td></td>";
        $body.="</tr>"
                .self::cabecera($columns,$head['styleHead']).
                "<tr style='".$head['styleFooterTotal']."'>";
        if(self::$type) $body.="<td>".Yii::app()->format->format_decimal(($totalCondition->total_calls/$total->total_calls)*(100))."%</td>
                                <td>".Yii::app()->format->format_decimal(($totalCondition->complete_calls/$total->complete_calls)*(100))."%</td>
                                <td>".Yii::app()->format->format_decimal(($totalCondition->minutes/$total->minutes)*(100))."%</td>
                                <td></td>
                                <td></td>
                                <td></td>";
        $body.="<td>".Yii::app()->format->format_decimal(($totalCondition->cost/$total->cost)*(100))."%</td>
                <td>".Yii::app()->format->format_decimal(($totalCondition->revenue/$total->revenue)*(100))."%</td>
                <td>".Yii::app()->format->format_decimal(($totalCondition->margin/$total->margin)*(100))."%</td>";
        if(self::$type) $body.="<td></td>
                                <td></td>
                                <td></td>
                                <td></td>";
        $body.="</tr>
                </table>";
        return $body;
    }*/

    /**
     * Genera una tabla con la lista y ranking del dato pasado
     * @access private
     * @static
     * @param array $head titulo que lleva la cabezera y su estilo. ej: $array['title']="Clientes"; $array['style']="color:black";
     * @param array $list lista de nombres incluidos para contruir la tabla
     * @param boolean $type si es true es para el principio, false al final
     */
    /*private static function getHtmlTable($head,$lista,$pos,$type=true)
    {
        $body="<table>";
        if($type)
        {
            $body.=self::cabecera(array('Ranking',$head['title'],'Vendedor'),$head['styleHead']);
            foreach ($lista as $key => $value)
            {
                if($pos!=null)
                    $pos=$pos+1;
                else
                    $pos=$key+1;
                $style=self::colorEstilo($pos);
                $body.="<tr style='".$style."'><td>".$pos."</td><td>".$value['attribute']."</td><td>".CarrierManagers::getManager($value['id'])."</td></tr>";
            }
            $body.="<tr>
                        <td></td>
                        <td></td>
                        <td style='".$head['styleFooter']."'>TOTAL</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style='".$head['styleFooterTotal']."'>Total</td>
                    </tr>";
        }
        else
        {
            $body.=self::cabecera(array('Vendedor',$head['title'],'Ranking'),$head['styleHead']);
            foreach ($lista as $key => $value)
            {
                if($pos!=null)
                    $pos=$pos+1;
                else
                    $pos=$key+1;
                $style=self::colorEstilo($pos);
                $body.="<tr style='".$style."'><td>".CarrierManagers::getManager($value['id'])."</td><td>".$value['attribute']."</td><td>".$pos."</td></tr>";
            }
            $body.="<tr>
                        <td style='".$head['styleFooter']."'>TOTAL</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style='".$head['styleFooterTotal']."'>Total</td>
                        <td></td>
                        <td></td>
                    </tr>";
        }
        $body.="</table>";
        return $body;
    }*/

    /**
     * Genera una tabla con la lista y ranking del dato pasado
     * @access private
     * @static
     * @param array $head titulo que lleva la cabezera y su estilo. ej: $array['title']="Clientes"; $array['style']="color:black";
     * @param array $list lista de nombres incluidos para contruir la tabla
     * @param boolean $type si es true es para el principio, false al final
     */
    /*private static function getHtmlTableDes($head,$lista,$pos,$type=true)
    {
        $body="<table>";
        if($type)
        {
            $body.=self::cabecera(array('Ranking',$head['title']),$head['styleHead']);
            foreach ($lista as $key => $value)
            {
                if($pos!=null)
                    $pos=$pos+1;
                else
                    $pos=$key+1;
                $style=self::colorDestino($value['attribute']);
                $body.=$style."<td>".$pos."</td><td>".$value['attribute']."</td></tr>";
            }
            $body.="<tr>
                        <td></td>
                        <td style='".$head['styleFooter']."'>TOTAL</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style='".$head['styleFooterTotal']."'>Total</td>
                    </tr>";
        }
        else
        {
            $body.=self::cabecera(array($head['title'],'Ranking'),$head['styleHead']);
            foreach ($lista as $key => $value)
            {
                if($pos!=null)
                    $pos=$pos+1;
                else
                    $pos=$key+1;
                $style=self::colorDestino($value['attribute']);
                $body.=$style."<td>".$value['attribute']."</td><td>".$pos."</td></tr>";
            }
            $body.="<tr>
                        <td style='".$head['styleFooter']."'>TOTAL</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style='".$head['styleFooterTotal']."'>Total</td>
                        <td></td>
                    </tr>";
        }
        $body.="</table>";
        return $body;
    }*/

    

    

    

    

    

    
}
?>