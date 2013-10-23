<?php
/**
* Creada para generar reporte de compra venta
* @package reportes
*/
class RankingCompraVenta extends Reportes
{
    /**
     * Genera el reporte de compraventa
     * @access public
     * @static
     * @param date $inicio fecha de inicio de la consulta
     * @param date $fin fecha final para ser consultada
     * @return string $cuerpo con el cuerpo de la tabla(<tbody>)
     */
    public static function reporte($fechaInicio,$fechaFin)
    {
        $cuerpo="<tbody><tr><td>";
        //Vendedores
        $cuerpo.=self::getHtmlManagers(true,$fechaInicio,$fechaFin);
        $cuerpo.="</td></tr><tr><td>";
        //Compradores
        $cuerpo.=self::getHtmlManagers(false,$fechaInicio,$fechaFin);
        $cuerpo.="</td></tr><tr><td>";
        $cuerpo.=self::getHtmlConsolidados($fechaInicio,$fechaFin);
        $cuerpo.="</td></tr></tbody></table>";
        return $cuerpo;
    }

    /**
     * Genera el html de vendedores o compradores dependiendo de los parametros
     * @access public
     * @static
     * @param date $inicio fecha de inicio de consulta
     * @param date $fin fecha fin de la consulta
     * @param boolean $tipo si es true es vendedor, si es false es comprador
     * @return string $cuerpo html construido con los datos(solo las filas)
     */
    public static function getHtmlManagers($tipo,$fechaInicio,$fechaFin)
    {
        $titulo="Vendedor";
        $color=1;
        if($tipo==false)
        {
            $color=2;
            $titulo="Comprador";
        }
        $cuerpo="<table><thead>";
        $cuerpo.=self::cabecera(array('Ranking',$titulo,'Minutos','Revenue','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.="</thead><tbody>";
        $managers=self::getManagers($tipo,$fechaInicio,$fechaFin);
        if($managers!=null)
        {
            foreach ($managers as $key => $unManager)
            {
                $pos=$key+1;
                $cuerpo.="<tr>
                            <td style='".self::colorRankingCV($color)."'>".
                            $pos.
                            "</td>
                            <td style='".self::colorRankingCV($color)."'>".
                            $unManager->apellido.
                            "</td>
                            <td style='".self::colorRankingCV($color)."'>".
                            Yii::app()->format->format_decimal($unManager->minutes).
                            "</td>
                            <td style='".self::colorRankingCV($color)."'>".
                            Yii::app()->format->format_decimal($unManager->revenue).
                            "</td>
                            <td style='".self::colorRankingCV($color)."'>".
                            Yii::app()->format->format_decimal($unManager->margin).
                            "</td>
                            <td style='".self::colorRankingCV($color)."'>".
                            $pos.
                            "</td>
                          </tr>";
            }
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='6'>No se encontraron resultados</td>
                      </tr>";
        }
        $cuerpo.=self::cabecera(array('Ranking',$titulo,'Minutos','Revenue','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        //total vendedores
        $cuerpo.=self::getHtmlTotalManagers($tipo,$fechaInicio,$fechaFin);
        $cuerpo.="</tbody></table>";
        return $cuerpo;
    }

    /**
     * Genera el HTML de los totales para cada tipo de managers
     * @access public
     * @static
     * @param date $inicio fecha de inicio de consulta
     * @param date $fin fecha fin de la consulta
     * @param boolean $tipo si es true es vendedor, si es false es comprador
     * @return string $cuerpo html construido con los datos(solo las filas)
     */
    public static function getHtmlTotalManagers($tipo=true,$fechaInicio,$fechaFin)
    {
        $cuerpo="";
        $total=self::getTotalManagers($tipo,$fechaInicio,$fechaFin);
        if($total!=false)
        {
            $cuerpo.="<tr>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
                            </td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
                            TOTAL
                            </td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
                            Yii::app()->format->format_decimal($total->minutes).
                            "</td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
                            Yii::app()->format->format_decimal($total->revenue).
                            "</td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
                            Yii::app()->format->format_decimal($total->margin).
                            "</td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
                            </td>
                          </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='6'>No se encontraron resultados</td>
                      </tr>";
        }
        return $cuerpo;
    }

    /**
     * Metodo encargado de generar el HTML de la tabla de consolidados
     * @access public
     * @static
     * @param date $inicio fecha de inicio que se va a consultar
     * @param date $fin es la fecha final a ser consultada.
     * @return string $cuerpo es el HTML en tabla de los datos consultados(solo las filas)
     */
    public static function getHtmlConsolidados($fechaInicio,$fechaFin)
    {
        $cuerpo="<table><thead>";
        $cuerpo.=self::cabecera(array('Ranking','Consolidado (Ventas + Compras)','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.="</thead><tbody>";
        $consolidados=self::getConsolidados($fechaInicio,$fechaFin);
        if($consolidados!=false)
        {
            foreach($consolidados as $key => $consolidado)
            {
                $pos=$key+1;
                $cuerpo.="<tr>
                            <td style='".self::colorRankingCV(3)."'>".
                            $pos.
                            "</td>
                            <td style='".self::colorRankingCV(3)."'>".
                            $consolidado->apellido.
                            "</td>
                            <td style='".self::colorRankingCV(3)."'>".
                            Yii::app()->format->format_decimal($consolidado->margin).
                            "</td>
                            <td style='".self::colorRankingCV(3)."'>".
                            $pos.
                            "</td>
                          </tr>";
            }
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='4'>No se encontraron resultados</td>
                      </tr>";
        }
        $cuerpo.=self::cabecera(array('Ranking','Consolidado (Ventas + Compras)','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.=self::getHtmlTotalConsolidado($fechaInicio,$fechaFin);
        $cuerpo.="</tbody></table>";
        return $cuerpo;
    }

    /**
     * metodo que genera la fila con el total de consolidados
     * @access public
     * @static
     * @param date $inicio fecha de inicio de la consulta
     * @param date $fin fecha fin de la consulta
     * @return string $cuerpo las filas de la tabla con los datos consultados
     */
    public static function getHtmlTotalConsolidado($fechaInicio,$fechaFin)
    {
        $cuerpo="";
        $totalConsolidado=self::getTotalConsolidado($fechaInicio,$fechaFin);
        if($totalConsolidado!=false)
        {
            $cuerpo.="<tr>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
                            </td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
                            Total Consolidado
                            </td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>".
                            Yii::app()->format->format_decimal($totalConsolidado->margin).
                            "</td>
                            <td style='background-color:#999999; color:#FFFFFF; text-align:center;'>
                            </td>
                          </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='4'>No se encontraron resultados</td>
                      </tr>";
        }
        $totalMargin=self::getMargenTotal($fechaInicio,$fechaFin);
        if($totalMargin!=false)
        {
            $cuerpo.="<tr>
                        <td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>
                        </td>
                        <td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>
                        Total Margen
                        </td>
                        <td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>".
                        Yii::app()->format->format_decimal($totalMargin->margin).
                        "</td>
                        <td style='background-color:#615E5E; color:#FFFFFF; text-align:center;'>
                        </td>
                      </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='4'>No se encontraron resultados</td>
                      </tr>";
        }
        return $cuerpo;
    }

    /**
     * Obtiene los datos de los managers en un periodo de tiempo
     * @access public
     * @static
     * @param boolean $tipo si es true es vendedor, si es false es comprador
     * @param date $inicio fecha de inicio de consulta
     * @param date $fin fecha fin de la consulta
     * @return CActiveRecord $managers
     */
    public static function getManagers($tipo,$fechaInicio,$fechaFin)
    {
        $manager="id_carrier_customer";
        if($tipo==false)
        {
            $manager="id_carrier_supplier";
        }
        $sql="SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
              FROM(SELECT {$manager}, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance 
                   WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$manager})b,
                   managers m,
                   carrier_managers cm
              WHERE m.id = cm.id_managers AND b.{$manager} = cm.id_carrier
              GROUP BY m.name, m.lastname
              ORDER BY margin DESC";

        $managers=Balance::model()->findAllBySql($sql);
        return $managers;
    }

    /**
     * Obtiene el total de los managers en un periodo de tiempo
     * @access public
     * @static
     * @param date $inicio fecha de inicio de consulta
     * @param date $fin fecha fin de la consulta
     * @param boolean $tipo si es true es vendedor, si es false es comprador
     * @return CActiveRecord $total
     */
    public static function getTotalManagers($tipo=true,$fechaInicio,$fechaFin)
    {
        $manager="id_carrier_customer";
        if($tipo==false)
        {
            $manager="id_carrier_supplier";
        }
        $sql="SELECT SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
              FROM(SELECT {$manager}, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance 
                   WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY {$manager})b";
        $total=Balance::model()->findBySql($sql);
        if($total->minutes!=null)
        {
            return $total;
        }
        else
        {
            return false;
        }
    }

    /**
     * Metodo encargado de conseguir los datos de los consolidados
     * @access public
     * @static
     * @param date $inicio fecha de inicio que se va a consultar
     * @param date $fin es la fecha final a ser consultada.
     * @return string $cuerpo es el HTML en tabla de los datos consultados(solo las filas)
     */
    public static function getConsolidados($fechaInicio,$fechaFin)
    {
        $sql="SELECT m.name AS nombre, m.lastname AS apellido, SUM(cs.margin) AS margin
              FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY id_carrier_customer
                   UNION
                   SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance 
                   WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                   GROUP BY id_carrier_supplier)cs,
                   managers m,
                   carrier_managers cm
              WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier
              GROUP BY m.name, m.lastname
              ORDER BY margin DESC";
        $consolidados=Balance::model()->findAllBySql($sql);
        if($consolidados!=null)
        {
            return $consolidados;
        }
        else
        {
            return false;
        }
    }

    /**
     * metodo que genera la fila con el total de consolidados
     * @access public
     * @static
     * @param date $inicio fecha de inicio de la consulta
     * @param date $fin fecha fin de la consulta
     * @return string $cuerpo las filas de la tabla con los datos consultados
     */
    public static function getTotalConsolidado($fechaInicio,$fechaFin)
    {
         $sql="SELECT SUM(cs.margin) AS margin
               FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                    GROUP BY id_carrier_customer
                    UNION
                    SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                    FROM balance
                    WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                    GROUP BY id_carrier_supplier)cs";
        $total=Balance::model()->findBySql($sql);
        if($total->margin!=null)
        {
            return $total;
        }
        else
        {
            return false;
        }
    }

    /**
     * Metodo que retorna el total de margen de un periodo especifico
     * @access public
     * @static
     * @param date $fechaInicio
     * @param date $fechaFin
     * @return CActiveRecord
     */
    public static function getMargenTotal($fechaInicio,$fechaFin)
    {
        $sql="SELECT CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
              FROM balance
              WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')";

        $margin=Balance::model()->findBySql($sql);
        if($margin->margin!=null)
        {
            return $margin;
        }
        else
        {
            return false;
        }
    }

    /**
     * Recibe un objeto de modelo y un apellido, retorna una fila <tr> con los datos del objeto
     * @access protected
     * @static
     * @param string $apellido
     * @param CActiveRecord $objeto
     * @param int $number es la posicion que tiene en el ranking
     * @param int $column es el numero del mes
     * @param int $last es el numero del ultimo mes
     * @return string
     */
    protected static function getRowManagers($apellido,$objeto,$number,$column,$last,$tipo=true)
    {
        $left=$right="";
        $n=array(
            true=>array('par'=>1,'impar'=>4),
            false=>array('par'=>2,'impar'=>5)
            );
        if ($column%2==0){
            $color=$n[$tipo]['par'];
        }else{
            $color=$n[$tipo]['impar'];
        }
        $style=self::colorRankingCV($color);
        foreach ($objeto as $key => $value)
        {
            if($column==0)
            {
                $left="<td>".$number."</td><td>".$value->apellido."</td>";
            }
            if($column>=$last)
            {
                $right="<td>".$number."</td>";
            }
            if($value->apellido == $apellido)
            {
                return "<tr style='".$style."'>".$left."<td>".Yii::app()->format->format_decimal($value->minutes)."</td><td>".Yii::app()->format->format_decimal($value->revenue)."</td><td>".Yii::app()->format->format_decimal($value->margin)."</td>".$right."</tr>";
            }
        }
        return "<tr style='".$style."'>".$left."<td>--</td><td>--</td><td>--</td>".$right."</tr>";
    }

    /**
     *
     */
    protected static function getRowTotalManagers($objeto,$column,$last)
    {
        $left=$right="";
        if($column==0)
        {
            $left="<td></td><td>TOTAL</td>";
        }
        if($column>=$last)
        {
            $right="<td></td>";
        }
        return "<tr style='background-color:#999999; color:#FFFFFF; text-align:center;'>".$left."<td>".Yii::app()->format->format_decimal($objeto->minutes)."</td><td>".Yii::app()->format->format_decimal($objeto->revenue)."</td><td>".Yii::app()->format->format_decimal($objeto->margin)."</td>".$right."</tr>";
    }

    /**
     * Recibe un objeto de modelo y un apellido, retorna una fila <tr> con los datos del objeto
     * @access protected
     * @static
     * @param string $apellido
     * @param CActiveRecord $objeto
     * @param int $column es el numero del mes
     * @param int $last es el numero del ultimo mes
     * @return string
     */
    protected static function getRowConsolidado($apellido,$objeto,$number,$column,$last)
    {
        if ($column%2==0){
            $color=3;
        }else{
            $color=6;
        }
        $style=self::colorRankingCV($color);
        $left=$right="";
        foreach ($objeto as $key => $value)
        {
            if($column==0)
            {
                $left="<td>".$number."</td><td>".$value->apellido."</td>";
            }
            if($column>=$last)
            {
                $right="<td>".$number."</td>";
            }
            if($value->apellido == $apellido)
            {
                return "<tr style='".$style."'>".$left."<td>".Yii::app()->format->format_decimal($value->margin)."</td>".$right."</tr>";
            }
        }
    }

    /**
     *
     */
    protected static function getRowTotalConsolidado($objeto,$column,$last,$type=true)
    {
        $left=$right="";
        $n=array(true=>'Total Consolidado',false=>'Total Margen');
        if($column==0)
        {
            $left="<td></td><td>".$n[$type]."</td>";
        }
        if($column>=$last)
        {
            $right="<td></td>";
        }
        return "<tr style='background-color:#999999; color:#FFFFFF; text-align:center;'>".$left."<td>".Yii::app()->format->format_decimal($objeto->margin)."</td>".$right."</tr>";
    }

    /**
     * Retorna la cabecera de la tabla para los managers
     * @access protected
     * @static
     * @param
     */
    protected static function getHeadManagers($type,$column,$last)
    {
        if($type)
        {
            $manager="Vendedor";
        }
        else
        {
            $manager="Comprador";
        }
        $array=array('Minutos','Revenue','Margin');
        if($column==0)
        {
            $array=array('Ranking',$manager,'Minutos','Revenue','Margin');
        }
        if($column>=$last)
        {
            $array=array('Minutos','Revenue','Margin','Ranking');
        }
        return self::cabecera($array,'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
    }

    /**
     * Retorna la cabecera de la tabla para los consolidados
     * @access protected
     * @static
     * @param
     */
    protected static function getHeadConsolidados($column,$last)
    {
        $array=array('Margin');
        if($column==0)
        {
            $array=array('Ranking','Consolidado (Ventas + Compras)','Margin');
        }
        if($column>=$last)
        {
            $array=array('Margin','Ranking');
        }
        return self::cabecera($array,'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
    }

}
?>