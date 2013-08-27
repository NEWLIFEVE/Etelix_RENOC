<?php
class Perdidas extends Reportes
{
    public static function reporte($fecha)
    {
        $cuerpo="<div>
                  <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                  <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Cliente','Destino','Proveedor','Margen','Minutos','Costo', 'Revenue'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //Selecciono los totales por clientes
        $sql="SELECT c.name AS cliente, d.name AS destino, s.name AS proveedor, b.margin AS margin, b.minutes AS minutes, b.cost AS cost, b.revenue AS revenue
              FROM(SELECT id_carrier_customer, id_destination_int, id_carrier_supplier, SUM(margin) AS margin, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue
                FROM balance
                WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                GROUP BY id_carrier_customer, id_destination_int, id_carrier_supplier
                ORDER BY margin ASC) b,
                   carrier c,
                   carrier s,
                   destination_int d
              WHERE b.id_carrier_customer=c.id AND d.id=b.id_destination_int AND b.id_carrier_supplier=s.id AND b.margin<0
              ORDER BY margin ASC";
        $balances=Balance::model()->findAllBySql($sql);
        if($balances!=null)
        {
            foreach ($balances as $key => $balance)
            {
                $pos=$key+1;
                $cuerpo.="<tr>
                          <td style='text-align: center;".self::colorEstilo($pos)."' class='position'>".
                            $pos.
                         "</td>
                          <td style='text-align: left;".self::colorEstilo($pos)."' class='cliente'>".
                            $balance->cliente.
                         "</td>
                          <td style='text-align: left;".self::colorEstilo($pos)."' class='destino'>".
                            $balance->destino.
                         "</td>
                          <td style='text-align: left;".self::colorEstilo($pos)."' class='destino'>".
                            $balance->proveedor.
                         "</td>
                          <td style='text-align: left;".self::colorEstilo($pos)."' class='totalCalls'>".
                            Yii::app()->format->format_decimal($balance->margin,5).
                         "</td>
                          <td style='text-align: left;".self::colorEstilo($pos)."' class='completeCalls'>".
                            Yii::app()->format->format_decimal($balance->minutes).
                         "</td>
                         <td style='text-align: left;".self::colorEstilo($pos)."' class='completeCalls'>".
                            Yii::app()->format->format_decimal($balance->cost).
                         "</td>
                         <td style='text-align: left;".self::colorEstilo($pos)."' class='completeCalls'>".
                            Yii::app()->format->format_decimal($balance->revenue).
                         "</td>
                        </tr>";
            }
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='5'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.=self::cabecera(array('Ranking','Cliente','Destino','Proveedor','Margen','Minutos','Costo','Revenue'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        //Selecciono la suma de todos los totales mayores a 10 dolares de margen
        $sqlTotal="SELECT SUM(b.margin) AS margin, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue, SUM(b.minutes) AS minutes
                   FROM(SELECT SUM(margin) AS margin, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(minutes) AS minutes
                         FROM balance
                         WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
                         GROUP BY id_carrier_customer, id_destination_int, id_carrier_supplier
                        ORDER BY margin ASC) b
                   WHERE b.margin<0";     
        
        $Total=Balance::model()->findBySql($sqlTotal);
        if($Total->margin!=null)
        {
            $cuerpo.="<tr>
                        <td style='background-color:#999999; color:#FFFFFF;'> 
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF;'> 
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF;'>
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF;'>
                        TOTAL
                        </td>                        
                        <td style='background-color:#999999; color:#FFFFFF;'>".
                            Yii::app()->format->format_decimal($Total->margin,5).
                       "</td>
                        <td style='background-color:#999999; color:#FFFFFF;'>
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF;'>".
                            Yii::app()->format->format_decimal($Total->cost).
                       "</td>
                       <td style='background-color:#999999; color:#FFFFFF;'>".
                            Yii::app()->format->format_decimal($Total->revenue).
                       "</td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                        <td colspan='5'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.="</tbody></table></div>";
        return $cuerpo;
    }
}
?>