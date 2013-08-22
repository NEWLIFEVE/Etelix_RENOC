<?php
class Perdidas extends Reportes
{
    public static function reporte($fecha)
    {
        $cuerpo="<div>
                  <table style='font:13px/150% Arial,Helvetica,sans-serif;'>
                  <thead>";
        $cuerpo.=self::cabecera(array('Ranking','Cliente','Destino','Proveedor','Minutos','Perdidas'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
                 <tbody>";
        //Selecciono los totales por clientes
        $sql="SELECT c.name AS cliente, d.name AS destino, s.name AS proveedor, b.minutes AS minutes, b.margin AS margin
                      FROM(SELECT id_carrier_customer, id_destination_int, id_carrier_supplier, SUM(minutes) AS minutes, SUM(margin) AS margin
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
                            Yii::app()->format->format_decimal($balance->minutes).
                         "</td>
                          <td style='text-align: left;".self::colorEstilo($pos)."' class='completeCalls'>".
                            Yii::app()->format->format_decimal($balance->margin,5).
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
        $cuerpo.=self::cabecera(array('Ranking','Cliente','Destino','Proveedor','Minutos','Perdidas'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        //Selecciono la suma de todos los totales mayores a 10 dolares de margen
        $sqlTotal="SELECT SUM(b.margin) AS margin
                   FROM(SELECT SUM(margin) AS margin
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
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF;'>
                        TOTAL
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF;'>".
                            Yii::app()->format->format_decimal($Total->margin,5).
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