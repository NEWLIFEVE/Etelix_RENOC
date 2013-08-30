<?php
/**
* @package reportes
*/
class DistComercial extends Reportes
{
	/**
	* @param $fecha date fecha a ser consultada
	* @return $cuerpo string cuerpo del reporte
	*/
	public static function reporte($fecha)
	{
		$sql="SELECT m.name||' '||m.lastname AS vendedor, c.name AS operador, m.position
              FROM carrier c, managers m, carrier_managers cm
              WHERE m.id = cm.id_managers AND c.id = cm.id_carrier AND cm.end_date IS NULL AND cm.start_date <= '2013-08-20'
              ORDER BY vendedor ASC";
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
                        </tr>
                    </thead>
                    </tbody>";
        $vendedores=Managers::model()->findAllBySql($sql);
        if($vendedores!=null)
        {
            $nombre=null;
            $numero=1;
            $posicion=null;
            foreach ($vendedores as $key => $vendedor)
            {
                $pos=$key+1;
                $com=$key-1;
                $posicion=$vendedor->position;
                if($key>0)
                {
                    if($vendedores[$com]->vendedor==$vendedor->vendedor)
                    {
                        $nombre="";
                        $posicion="";
                        $numero=$numero+1;
                    }
                    else
                    {
                        $nombre=$vendedor->vendedor;
                        $posicion=$vendedor->position;
                        $numero=1;
                    }
                }
                else
                {
                    $nombre=$vendedor->vendedor;
                }
                $cuerpo.="<tr>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$posicion."</td>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$nombre."</td>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$numero."</td>
                            <td style='".self::colorVendedor($vendedor->vendedor)."'>".$vendedor->operador."</td>
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