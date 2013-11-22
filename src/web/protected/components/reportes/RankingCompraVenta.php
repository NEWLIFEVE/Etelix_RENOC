<?php
/**
* Creada para generar reporte de compra venta
* @package reportes
*/
class RankingCompraVenta extends Reportes
{
    /**
     * @var array
     */
    public $objetos;
    /**
     * @var boolean
     */
    public $equal;

    function __construct()
    {
        $this->objetos=array();
        $this->equal=false;
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
        $this->loopData($start,$end);
        
        //Cuento el numero de objetos en el array
        $num=count($this->objetos);
        $last=$num-1;
        $lastnames=self::getLastNameManagers();
        /*Arrays ordenados*/
        $sorted['sellers']=self::sortByList($lastnames,$this->objetos[$last]['sellers'],'apellido');
        $sorted['buyers']=self::sortByList($lastnames,$this->objetos[$last]['buyers'],'apellido');
        $sorted['consolidated']=self::sortByList($lastnames,$this->objetos[$last]['consolidated'],'apellido');
        $body="<table>";
        for($row=0; $row<4; $row++)
        { 
            $body.="<tr>";
            switch($row)
            {
                case 0:
                case 2:
                    for($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $body.="<td style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                        }
                        elseif($col>0 && $col<$num+1)
                        {
                            $body.="<td style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->objetos[$col-1]['title']."</td>";
                            if($col!=$num)
                            {
                                $body.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $body.="<td style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                        }
                    }
                    break;
                case 1:
                    $head=array(
                        'title'=>'Vendedor',
                        'styleHead'=>'background-color:#295FA0; color:#ffffff; width:10%; height:100%;',
                        'styleBody'=>self::colorRankingCV(1),
                        'styleFooter'=>'text-align:center;background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $body.="<td>".$this->getHtmlTable($head,$sorted['sellers'],$type=true)."<br></td>";
                        }
                        elseif($col>0 && $col<$num+1)
                        {
                            $body.="<td>".$this->getHtmlTableData($sorted['sellers'],$col-1,'sellers','apellido',$head,true).
                                        $this->getHtmlTotal($this->objetos[$col-1]['totalVendors'],$head,true)."<br></td>";
                            if($col!=$num)
                            {
                                $body.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $body.="<td>".$this->getHtmlTable($head,$sorted['sellers'],$type=false)."<br></td>";
                        }
                    }
                    break;
                case 3:
                    $head=array(
                        'title'=>'Comprador',
                        'styleHead'=>'background-color:#295FA0; color:#ffffff; width:10%; height:100%;',
                        'styleBody'=>self::colorRankingCV(2),
                        'styleFooter'=>'text-align:center;background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $body.="<td>".$this->getHtmlTable($head,$sorted['buyers'],$type=true)."<br></td>";
                        }
                        elseif($col>0 && $col<$num+1)
                        {
                            $body.="<td>".$this->getHtmlTableData($sorted['buyers'],$col-1,'buyers','apellido',$head,true).
                            $this->getHtmlTotal($this->objetos[$col-1]['totalBuyers'],$head,true)."<br></td>";
                            if($col!=$num)
                            {
                                $body.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $body.="<td>".$this->getHtmlTable($head,$sorted['buyers'],$type=false)."<br></td>";
                        }
                    }
                    break;
            }
            $body.="</tr>";
        }
        $body.="</table>";
        $body.="<table>";
        for($row=0; $row<2; $row++)
        { 
            $body.="<tr>";
            switch($row)
            {
                case 0:
                    for($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $body.="<td style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                        }
                        elseif($col>0 && $col<$num+1)
                        {
                            $body.="<td style='text-align:center;background-color:#999999;color:#FFFFFF;'>".$this->objetos[$col-1]['title']."</td>";
                            if($col!=$num)
                            {
                                $body.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $body.="<td style='text-align:center;background-color:#999999;color:#FFFFFF;'></td>";
                        }
                    }
                    break;
                case 1:
                    $head=array(
                        'title'=>'Consolidado (Ventas + Compras)',
                        'styleHead'=>'background-color:#295FA0; color:#ffffff; width:10%; height:100%;',
                        'styleBody'=>self::colorRankingCV(3),
                        'styleFooter'=>'text-align:center;background-color:#999999; color:#FFFFFF;',
                        'styleFooterTotal'=>'background-color:#615E5E; color:#FFFFFF;'
                        );
                    for($col=0; $col < $num+2; $col++)
                    { 
                        if($col==0)
                        {
                            $body.="<td>".$this->getHtmlTable($head,$sorted['consolidated'],$type=true)."
                            <table><tr style='background-color:#615E5E; color:#FFFFFF; text-align:center;'><td></td><td>Total Margen</td></tr></table><br></td>";
                        }
                        elseif($col>0 && $col<$num+1)
                        {
                            $body.="<td>".$this->getHtmlTableData($sorted['consolidated'],$col-1,'consolidated','apellido',$head,false).
                            $this->getHtmlTotal($this->objetos[$col-1]['totalConsolidated'],$head,false).
                            $this->getHtmlTotalMargen($this->objetos[$col-1]['totalMargen'])."<br></td>";
                            if($col!=$num)
                            {
                                $body.="<td style='width:5px;'></td>";
                            }
                        }
                        else
                        {
                            $body.="<td>".$this->getHtmlTable($head,$sorted['consolidated'],$type=false)."
                            <table><tr style='background-color:#615E5E; color:#FFFFFF; text-align:center;'><td>Total Margen</td><td></td></tr></table><br></td>";
                        }
                    }
                    break;
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
    private function loopData($start,$end)
    {
        //verifico las fechas
        $array=self::valDates($start,$end);
        $startDateTemp=$startDate=$array['startDate'];
        $yesterday=Utility::calculateDate('-1',$startDateTemp);
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
            $this->objetos[$index]['sellers']=$this->getManagers($startDateTemp,$endingDateTemp,true);
            /*Guardo los totales de los vendedores*/
            $this->objetos[$index]['totalVendors']=$this->getTotalManagers($startDateTemp,$endingDateTemp,true);
            /*Guardo los valores de vendedores del dia anterior*/
            if($this->equal) $this->objetos[$index]['sellersYesterday']=$this->getManagers($yesterday,$yesterday,true);
            /*Guardo los totales de los vendedores del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalVendorsYesterday']=$this->getTotalManagers($yesterday,$yesterday,true);
            /*Guardo los totales de los compradores*/
            $this->objetos[$index]['buyers']=$this->getManagers($startDateTemp,$endingDateTemp,false);
            /*Guardo los totales de todos los compradores*/
            $this->objetos[$index]['totalBuyers']=$this->getTotalManagers($startDateTemp,$endingDateTemp,false);
            /*Guardo los valores de compradores del dia anterior*/
            if($this->equal) $this->objetos[$index]['buyersYesterday']=$this->getManagers($yesterday,$yesterday,false);
            /*Guardo los totales de los compradores del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalBuyersYesterday']=$this->getTotalManagers($yesterday,$yesterday,false);
            /*guardo los totales de los compradores y vendedores consolidado*/
            $this->objetos[$index]['consolidated']=$this->getConsolidados($startDateTemp,$endingDateTemp);
            /*Guardo el total de los consolidados*/
            $this->objetos[$index]['totalConsolidated']=$this->getTotalConsolidado($startDateTemp,$endingDateTemp);
            /*Guardo el margen total de ese periodo*/
            $this->objetos[$index]['totalMargen']=$this->getTotalMargen($startDateTemp,$endingDateTemp);
            /*guardo los totales de los compradores y vendedores consolidado del dia de ayer*/
            if($this->equal) $this->objetos[$index]['consolidatedYesterday']=$this->getConsolidados($yesterday,$yesterday);
            /*Guardo el total de los consolidados del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalConsolidatedYesterday']=$this->getTotalConsolidado($yesterday,$yesterday);
            /*Guardo el margen total de ese periodo del dia de ayer*/
            if($this->equal) $this->objetos[$index]['totalMargenYesterday']=$this->getTotalMargen($yesterday,$yesterday);
            /*Itero la fecha*/
            $startDateTemp=$arrayStartTemp[0]."-".($arrayStartTemp[1]+1)."-01";
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
    private function getManagers($startDate,$endingDate,$type)
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
              WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier
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
    private function getTotalManagers($startDate,$endingDate,$type)
    {
        $manager="id_carrier_customer";
        if($type==false)
        {
            $manager="id_carrier_supplier";
        }
        $sql="SELECT SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
              FROM(SELECT {$manager}, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance 
                   WHERE date_balance>='{$startDate}' AND date_balance<='{$endingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$manager})b";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Metodo encargado de conseguir los datos de los consolidados
     * @access private
     * @param date $startDate fecha de inicio que se va a consultar
     * @param date $endingDate es la fecha final a ser consultada.
     * @return array
     */
    private function getConsolidados($startDate,$edingDate)
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
              WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier
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
    private function getTotalConsolidado($startDate,$edingDate)
    {
         $sql="SELECT SUM(cs.margin) AS margin
               FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                    GROUP BY id_carrier_customer
                    UNION
                    SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                    GROUP BY id_carrier_supplier)cs";
        return Balance::model()->findBySql($sql);
    }

    /**
     * Genera una tabla con la lista y ranking del dato pasado
     * @access private
     * @static
     * @param array $head titulo que lleva la cabecera y su estilo. ej: $array['title']="Clientes"; $array['style']="color:black";
     * @param array $list lista de nombres incluidos para contruir la tabla
     * @param string $name es la frase que va acompañada en la cabecera
     * @param boolean $type si es true es para el principio, false al final
     * @param boolean 
     */
    private function getHtmlTable($head,$lista,$type=true)
    {
        $body="<table>";
        $pos=0;
        if($type)
        {   
            $body.=self::cabecera(array('Ranking',$head['title']),$head['styleHead']);
            foreach ($lista as $key => $value)
            {
                $pos=$pos+1;
                $body.="<tr style='".$head['styleBody']."'><td>".$pos."</td><td>".$value."</td></tr>";
            }
            $body.=self::cabecera(array('Ranking',$head['title']),$head['styleHead']);
            $body.="<tr style='text-align:center;background-color:#999999;color:#FFFFFF;'><td></td><td>Total</td></tr>";
        }
        else
        {
            $body.=self::cabecera(array($head['title'],'Ranking'),$head['styleHead']);
            foreach ($lista as $key => $value)
            {
                $pos=$pos+1;
                $body.="<tr style='".$head['styleBody']."'><td>".$value."</td><td>".$pos."</td></tr>";
            }
            $body.=self::cabecera(array($head['title'],'Ranking'),$head['styleHead']);
            $body.="<tr style='text-align:center;background-color:#999999;color:#FFFFFF;'><td>Total</td><td></td></tr>";
        }
        $body.="</table>";
        return $body;
    }

    /**
     * Recibe un objeto de modelo y un atributo, retorna una fila <tr> con los datos del objeto
     * @access private
     * @param string $attribute es el atributo del objeto con el que se hará la comparacion
     * @param string $phrase es la frase con la que debe conincidir el atributo 
     * @param array $objeto es el objeto traido de base de datos
     * @param int $position es el numero para indicar el color de la fila en la tabla 
     * @param $type true=minutes,revenue,margin false=margin
     * @return string
     */
    private function getRow($attribute,$phrase,$index,$index2,$position,$head,$type=true)
    {
        $primera=$segunda=$tercera=$cuarta=null;
        foreach ($this->objetos[$index][$index2] as $key => $value)
        {
            if($value->$attribute == $phrase)
            {               
                if($type==true) $primera="<td>".Yii::app()->format->format_decimal($value->minutes)."</td>";
                if($type==true) $segunda="<td>".Yii::app()->format->format_decimal($value->revenue)."</td>";
                $tercera="<td>".Yii::app()->format->format_decimal($value->margin)."</td>";         
            }
        }
        if($this->equal)
        {
            foreach ($this->objetos[$index][$index2.'Yesterday'] as $key => $value)
            {
                if($value->$attribute == $phrase) $cuarta="<td>".Yii::app()->format->format_decimal($value->margin)."</td>";
            }
        }
        if($type==true)
        {
            if($primera==null) $primera="<td>--<td>";
            if($segunda==null) $segunda="<td>--</td>";
        }
        if($tercera==null) $tercera="<td>--</td>";
        if($this->equal)
        {
            if($cuarta==null) $cuarta="<td>--</td>";
        } 
        $body="<tr style='".$head['styleBody']."'>".$primera.$segunda.$tercera.$cuarta."</tr>";
        return $body;
    }

    /**
     * Genera el cuerpo de la tabla con la data de los managers
     * @access private
     * @param array $list es la lista de apellidos ordenados para sacarlos en la fila
     * @param 
     */
    private function getHtmlTableData($list,$index,$index2,$attribute,$head,$type=true)
    {
        $columns=array('Margin');
        if($type==true) $columns=array_merge(array('Minutes','Revenue'),$columns);
        if($this->equal) $columns=array_merge($columns,array('Ayer','Promedio','Acumulado','Cierre Mes'));

        $body="<table>
                    <thead>";
                        $body.=self::cabecera($columns,$head['styleHead']);
                    $body.="</thead>
                 <tbody>";
        if($this->objetos[$index][$index2]!=NULL)
        {
            $pos=0;
            foreach ($list as $key => $manager)
            {
                $pos=$pos+1;
                $body.=$this->getRow($attribute,$manager,$index,$index2,$pos,$head,$type);
            }
        }
        else
        {
            $body.="<tr><td colspan='3'>No se encontraron resultados</td></tr>";
        }
        $body.="</tbody>
                 </table>";
        return $body;
    }

    /**
     * Retorna una tabla con los totales de los objetos pasados como parametros
     * @access private
     * @param CActiveRecord $total es el objeto que totaliza los que cumplen la condicion
     * @return string
     */
    private function getHtmlTotal($total,$head,$type=true)
    {
        $columns=array('Margin');
        if($type==true) $columns=array_merge(array('Minutes','Revenue'),$columns);
        if($this->equal) $columns=array_merge($columns,array('Ayer','Promedio','Acumulado','Cierre Mes'));

        $body="<table>";
        $body.=self::cabecera($columns,$head['styleHead']);
                    $body.="<tr style='".$head['styleFooter']."'>";
        if($type==true) $body.="<td>".Yii::app()->format->format_decimal($total->minutes)."</td>";
        if($type==true) $body.="<td>".Yii::app()->format->format_decimal($total->revenue)."</td>";
        $body.="<td>".Yii::app()->format->format_decimal($total->margin)."</td></tr>
                </table>";
        return $body;
    }

    /**
     * Metodo que retorna el total de margen de un periodo especifico
     * @access private
     * @param date $startDate
     * @param date $edingDate
     * @return CActiveRecord
     */
    public function getTotalMargen($startDate,$edingDate)
    {
        $sql="SELECT CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
              FROM balance
              WHERE date_balance>='{$startDate}' AND date_balance<='{$edingDate}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')";

        return Balance::model()->findBySql($sql);
    }

    /**
     * Retorna el html del total del margen
     * @access private
     * @param CActiveRecord $data el objeto que se va a imprimir
     * @return string
     */
    private function getHtmlTotalMargen($data)
    {
        return "<table>
                    <tr style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>
                        <td>".Yii::app()->format->format_decimal($data->margin)."</td>
                    </tr>
                </table>";
    }
}
?>