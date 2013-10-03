<?php
/**
* @package reportes
*/
class DistComercialTerminoPago extends Reportes
{
	/**
	* @param $fecha date fecha a ser consultada
	* @return $cuerpo string cuerpo del reporte
	*/
	public static function reporte($fecha)
	{
		$sql="SELECT 
m.name||' '||m.lastname AS vendedor, c.name AS operador, m.position, y.name as company, z.name as monetizable, tp.name as termino_pago, d.days as dias_disputa, cl.amount as limite_credito, pl.amount as limite_compra,  z.id as id_mon, tp.id as id_tp

FROM 
carrier c, managers m, carrier_managers cm, company y, contrato x, termino_pago tp,
contrato_termino_pago ctp, monetizable z, contrato_monetizable cz,
days_dispute_history d, credit_limit cl, purchase_limit pl 

WHERE 
m.id = cm.id_managers AND 
c.id = cm.id_carrier AND 
cm.end_date IS NULL AND 
cm.start_date <= current_date AND 
x.id_carrier = c.id AND x.end_date IS NULL AND x.id_company = y.id AND
x.id = ctp.id_contrato AND ctp.end_date IS NULL AND ctp.id_termino_pago = tp.id AND
x.id = cz.id_contrato AND cz.end_date IS NULL AND cz.id_monetizable = z.id AND
x.id = d.id_contrato AND d.end_date IS NULL AND
x.id = cl.id_contrato AND cl.end_date IS NULL AND
x.id =pl.id_contrato AND pl.end_date IS NULL 

UNION
select m.name||' '||m.lastname AS vendedor, c.name AS operador, m.position,'Sin Asignar' as company, 'Sin Asignar' as monetizable, 'Sin Asignar' as termino_pago, -1 as dias_disputa, -1 as limite_credito, -1 as limite_compra,  100 as id_mon,100 as id_tp
from carrier c, managers m, carrier_managers cm
where c.id not in (select distinct(id_carrier) from contrato) AND m.id = cm.id_managers AND 
c.id = cm.id_carrier AND 
cm.end_date IS NULL AND 
cm.start_date <= current_date 
ORDER BY id_tp ASC,vendedor ASC, id_mon ASC";
        $cuerpo="<table >
                    <thead>
                        <tr>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%; border: 1px black;'>
                                Cargo
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:15%; height:100%; border: 1px black;'>
                                Responsable
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Pos
                                </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Operador
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Compania
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Termino Pago
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Monetizable
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Dias Disputa
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Limite Credito
                            </th>
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Limite Compra
                            </th>
                        </tr>
                    </thead>
                    </tbody>";
        $vendedores=Managers::model()->findAllBySql($sql);
        if($vendedores!=null)
        {
            $nombre=null;
            $nombreV=null;
            $numero=1;
            $posicion=null;
            foreach ($vendedores as $key => $vendedor)
            {
                $pos=$key+1;
                $com=$key-1;
                $posicion=$vendedor->position;
                if($key>0)
                {
                    if($vendedores[$com]->termino_pago==$vendedor->termino_pago)
                    {
                        $nombre="";
                        $numero=$numero+1;
                    }
                    else
                    {
                        $nombre="-".$vendedor->termino_pago."-";
                        $numero=1;
                    }
                                        
                    if($vendedores[$com]->vendedor==$vendedor->vendedor)
                    {
                        $nombreV="";
                        $posicion="";
                        
                    }
                    else
                    {
                        $nombreV=$vendedor->vendedor;
                        $posicion=$vendedor->position;
                        
                    }
                    
                }
                else
                {
                    $nombre="-".$vendedor->termino_pago."-";
                    $nombreV=$vendedor->vendedor;
                }
                $cuerpo.="<tr>
                            <td style='".self::colorTP($vendedor->termino_pago)."'>".$posicion."</td>
                            <td style='".self::colorTP($vendedor->termino_pago)."'>".$nombreV."</td>
                            <td style='".self::colorTP($vendedor->termino_pago)."'>".$numero."</td>
                            <td style='".self::colorTP($vendedor->termino_pago)."'>".$vendedor->operador."</td>
                            <td style='".self::colorTP($vendedor->termino_pago,$vendedor->company)."'>".$vendedor->company."</td>
                            <td style='text-align: right;".self::colorTP($vendedor->termino_pago,$vendedor->termino_pago)."'>".$nombre."</td>
                            <td style='".self::colorTP($vendedor->termino_pago,$vendedor->monetizable)."'>".$vendedor->monetizable."</td>
                            <td style='".self::colorTP($vendedor->termino_pago,NULL,$vendedor->dias_disputa)."'>".$vendedor->dias_disputa."</td>
                            <td style='".self::colorTP($vendedor->termino_pago,NULL,$vendedor->limite_credito)."'>".$vendedor->limite_credito."</td>
                            <td style='".self::colorTP($vendedor->termino_pago,NULL,$vendedor->limite_compra)."'>".$vendedor->limite_compra."</td>
                        </tr>";
            } 
        }
        else
        {
            $cuerpo.="<tr>
                  <td colspan='4'>No se encontraron resultados</td>
                </tr>";
        }
        $cuerpo.="</tbody></table>";
        return $cuerpo;
	} 
}
?>