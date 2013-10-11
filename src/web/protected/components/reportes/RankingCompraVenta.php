<?php
/**
* Creada para generar reporte de compra venta
* @package reportes
*/
class RankingCompraVenta extends Reportes
{
    /**
     * genera el reporte de compraventa
     * @access public
     * @param date $inicio fecha de inicio de la consulta
     * @param date $fin fecha final para ser consultada
     * @return string $cuerpo con el cuerpo de la tabla(<tbody>)
     */
    public static function reporte($fechaInicio,$fechaFin)
    {
        $cuerpo="<tbody><tr><td>";
        //Vendedores
        $cuerpo.=RankingCompraVenta::managers(true,$fechaInicio,$fechaFin);
        $cuerpo.="</td></tr><tr><td>";
        //Compradores
        $cuerpo.=RankingCompraVenta::managers(false,$fechaInicio,$fechaFin);
        $cuerpo.="</td></tr><tr><td>";
        $cuerpo.=RankingCompraVenta::consolidados($fechaInicio,$fechaFin);
        $cuerpo.="</td></tr></tbody></table>";
        return $cuerpo;
    }

    /**
     * Genera el html de vendedores o compradores dependiendo de los parametros
     * @access public
     * @param date $inicio fecha de inicio de consulta
     * @param date $fin fecha fin de la consulta
     * @param boolean $tipo si es true es vendedor, si es false es comprador
     * @return string $cuerpo html construido con los datos(solo las filas)
     */
    public static function managers($tipo=true,$fechaInicio,$fechaFin)
    {
        $manager="id_carrier_customer";
        $titulo="Vendedor";
        $color=1;
        if($tipo==false)
        {
            $manager="id_carrier_supplier";
            $color=2;
            $titulo="Comprador";
        }
        $cuerpo="<table><thead>";
        $cuerpo.=self::cabecera(array('Ranking',$titulo,'Minutos','Revenue','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.="</thead><tbody>";


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
        $cuerpo.=self::totales($tipo,$fechaInicio,$fechaFin);
        $cuerpo.="</tbody></table>";
        return $cuerpo;
    }

    /**
     * Genera el HTML de los totales para cada tipo de managers
     * @access public
     * @param date $inicio fecha de inicio de consulta
     * @param date $fin fecha fin de la consulta
     * @param boolean $tipo si es true es vendedor, si es false es comprador
     * @return string $cuerpo html construido con los datos(solo las filas)
     */
    public static function totales($tipo=true,$fechaInicio,$fechaFin)
    {
        $cuerpo="";
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
     * @param date $inicio fecha de inicio que se va a consultar
     * @param date $fin es la fecha final a ser consultada.
     * @return string $cuerpo es el HTML en tabla de los datos consultados(solo las filas)
     */
    public static function consolidados($fechaInicio,$fechaFin)
    {
        $cuerpo="<table><thead>";
        $cuerpo.=self::cabecera(array('Ranking','Consolidado (Ventas + Compras)','Margin','Ranking'),'background-color:#295FA0; color:#ffffff; width:10%; height:100%;');
        $cuerpo.="</thead><tbody>";
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
        $cuerpo.=self::totalConsolidado($fechaInicio,$fechaFin);
        $cuerpo.="</tbody></table>";
        return $cuerpo;
    }

    /**
     * metodo que genera la fila con el total de consolidados
     * @access public
     * @param date $inicio fecha de inicio de la consulta
     * @param date $fin fecha fin de la consulta
     * @return string $cuerpo las filas de la tabla con los datos consultados
     */
    public static function totalConsolidado($fechaInicio,$fechaFin)
    {
        $cuerpo="";
         $sqlTotalConsolidado="SELECT SUM(cs.margin) AS margin
                               FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                    FROM balance
                                    WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                                    GROUP BY id_carrier_customer
                                    UNION
                                    SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                                    FROM balance 
                                    WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                                    GROUP BY id_carrier_supplier)cs";
        $totalConsolidado=Balance::model()->findBySql($sqlTotalConsolidado);
        if($totalConsolidado->margin!=null)
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
        $sql="SELECT SUM(margin) AS margin
              FROM balance
              WHERE date_balance>='{$fechaInicio}' AND date_balance<='{$fechaFin}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')";
        $totalMargin=Balance::model()->findBySql($sql);
        if($totalMargin->margin!=null)
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

    public static function retornaFecha($titulo, $inicio, $fin)
    {
        return "Por ".$titulo." a la fecha inicial ".$inicio." y la fecha fin ".$fin;
    }
}
?>