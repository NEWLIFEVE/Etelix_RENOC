<?php
/**
* @package reportes
*/
class PosicionNetaVendedor extends Reportes
{
	private $pos;
	private $vendedor="";

	function __construct($fecha)
	{
		$this->fecha=$fecha;
	}
	/**
	*
	*/
	public function reporte()
	{
		$sql="SELECT o.name AS operador, m.id, m.lastname AS vendedor, cs.vminutes, cs.vrevenue, cs.vmargin, cs.cminutes, cs.ccost, cs.cmargin, cs.posicion_neta, cs.margen_total
			  FROM(SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
			  	   FROM(SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS vmargin, CAST(0 AS double precision) AS cminutes, CAST(0 AS double precision) AS ccost, CAST(0 AS double precision) AS cmargin
			  	   	    FROM balance
			  	   	    WHERE date_balance='{$this->fecha}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
			  	   	    GROUP BY id_carrier_customer
			  	   	    UNION
			  	   	    SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, CAST(0 AS double precision) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS cmargin
			  	   	    FROM balance
			  	   	    WHERE date_balance='{$this->fecha}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
			  	   	    GROUP BY id_carrier_supplier)t
				   GROUP BY id
				   ORDER BY posicion_neta DESC)cs, carrier o, managers m, carrier_managers cm
			  WHERE o.id=cs.id AND cm.start_date<='{$this->fecha}' AND cm.end_date IS NULL AND cm.id_carrier=cs.id AND cm.id_managers=m.id
			  ORDER BY m.lastname, cs.posicion_neta DESC";
        $cuerpo="<div>
        			<table>
        				<thead>";
        $cuerpo.=self::cabecera(array('Ranking','Operador','Vendedor','Vminutes','Vrevenue','Vmargin','Cminutes','Ccost','Cmargin','Margen Total','Posicion Neta','Operador','Ranking','Vendedor'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $cuerpo.="</thead>
        			<tbody>";
        $valores=Balance::model()->findAllBySql($sql);
        foreach ($valores as $key => $valor)
        {
        	$this->posicion($valor->vendedor);
            $estilo=self::colorEstilo($key+1);
        	$cuerpo.="<tr>
        				<td style='{$estilo}'>".
        					$this->pos.
        			 "</td>
        				<td style='{$estilo}'>".
        					$valor->operador.
        			 "</td>
        				<td style='{$estilo}'>".
        					$valor->vendedor.
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->vminutes).
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->vrevenue).
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->vmargin).
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->cminutes).
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->ccost).
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->cmargin).
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->margen_total).
        			 "</td>
        				<td style='{$estilo}'>".
        					Yii::app()->format->format_decimal($valor->posicion_neta).
        			 "</td>
        				<td style='{$estilo}'>".
        					$valor->operador.
        			 "</td>
        				<td style='{$estilo}'>".
        					$this->pos.
        			 "</td>
        				<td style='{$estilo}'>".
        					$valor->vendedor.
        			 "</td>
        			  </tr>";
            if(isset($valores[$key+1]))
            {
                if($valores[$key+1]->id!=$valor->id)
                {
                    $cuerpo.=$this->totales($valor->id);
                }
            }
            else
            {
                $cuerpo.=$this->totales($valor->id);
            }
        }
        $sqlTotal="SELECT SUM(cs.vminutes) AS vminutes, SUM(cs.vrevenue) AS vrevenue, SUM(cs.vmargin) AS vmargin, SUM(cs.cminutes) AS cminutes, SUM(cs.ccost) AS ccost, SUM(cs.cmargin) AS cmargin, SUM(cs.posicion_neta) AS posicion_neta, SUM(cs.margen_total) AS margen_total
                   FROM
                   (SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
                   FROM(SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS vmargin, CAST(0 AS double precision) AS cminutes, CAST(0 AS double precision) AS ccost, CAST(0 AS double precision) AS cmargin
                        FROM balance
                        WHERE date_balance='{$this->fecha}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_customer
                        UNION
                        SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, CAST(0 AS double precision) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS cmargin
                        FROM balance
                        WHERE date_balance='{$this->fecha}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
                        GROUP BY id_carrier_supplier)t
                   GROUP BY id
                   ORDER BY posicion_neta DESC)cs";
        $cuerpo.=self::cabecera(array('Ranking','Operador','Vendedor','Vminutes','Vrevenue','Vmargin','Cminutes','Ccost','Cmargin','Margen Total','Posicion Neta','Operador','Ranking','Vendedor'),'background-color:#615E5E; color:#62C25E; width:10%; height:100%;');
        $Total=Balance::model()->findBySql($sqlTotal);
        if($Total!=null)
        { 
            $cuerpo.="<tr>
                      <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='ranking'>
                      </td>
                      <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='operador'>
                      </td>
                      <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vendedor'>
                      TOTAL
                      </td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vminutes'>".
                            Yii::app()->format->format_decimal($Total->vminutes).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vrevenue'>".
                            Yii::app()->format->format_decimal($Total->vrevenue).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vmargin'>".
                            Yii::app()->format->format_decimal($Total->vmargin).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='cminutes'>".
                            Yii::app()->format->format_decimal($Total->cminutes).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='ccost'>".
                            Yii::app()->format->format_decimal($Total->ccost).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='cmargin'>".
                            Yii::app()->format->format_decimal($Total->cmargin).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='margenTotal'>".
                            Yii::app()->format->format_decimal($Total->margen_total).
                        "</td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='posicionNeta'>".
                            Yii::app()->format->format_decimal($Total->posicion_neta).
                        "</td>
                         <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='operador'>
                         TOTAL
                         </td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vacio'>
                        </td>
                        <td style='background-color:#999999; color:#FFFFFF; text-align:center;' class='vacio'>
                        </td>
                    </tr>";
        }
        else
        {
            $cuerpo.="<tr>
                      <td colspan='13'>No se encontraron resultados</td>
                     </tr>";
        }
        $cuerpo.="</tbody>
        		</table>
        	</div>";
        return $cuerpo;
    }

    /**
    *
    */
    public function totales($id_managers)
    {
    	$sql="SELECT SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) as cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vmargin)+SUM(cmargin) AS margen_total, SUM(vrevenue)-SUM(ccost) AS posicion_neta
			  FROM(SELECT SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS vmargin, CAST(0 AS double precision) AS cminutes, CAST(0 AS double precision) AS ccost, CAST(0 AS double precision) AS cmargin
			       FROM balance b, carrier_managers cm
			       WHERE date_balance='{$this->fecha}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL AND b.id_carrier_customer=cm.id_carrier AND cm.start_date<='{$this->fecha}' AND cm.end_date IS NULL AND cm.id_managers={$id_managers}
			       UNION
			       SELECT CAST(0 AS double precision) AS vminutes, CAST(0 AS double precision) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS cmargin
			       FROM balance b, carrier_managers cm
			       WHERE date_balance='{$this->fecha}' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL AND b.id_carrier_supplier=cm.id_carrier AND cm.start_date<='{$this->fecha}' AND cm.end_date IS NULL AND cm.id_managers={$id_managers})t";
		$total=Balance::model()->findBySql($sql);
		$cuerpo="<tr style='background-color:#BDBDBD;  color:#FFFFFF;'>
					<td>
						TOTAL
					</td>
					<td>
						TOTAL
					</td>
					<td>
						TOTAL
					</td>
					<td>".
						Yii::app()->format->format_decimal($total->vminutes).
				   "</td>
					<td>".
						Yii::app()->format->format_decimal($total->vrevenue).
				   "</td>
					<td>".
						Yii::app()->format->format_decimal($total->vmargin).
				   "</td>
					<td>".
						Yii::app()->format->format_decimal($total->cminutes).
				   "</td>
					<td>".
						Yii::app()->format->format_decimal($total->ccost).
				   "</td>
					<td>".
						Yii::app()->format->format_decimal($total->cmargin).
				   "</td>
					<td>".
						Yii::app()->format->format_decimal($total->margen_total).
				   "</td>
					<td>".
						Yii::app()->format->format_decimal($total->posicion_neta).
				   "</td>
					<td>
						TOTAL
					</td>
					<td>
						TOTAL
					</td>
					<td>
						TOTAL
					</td>
				 </tr>";
		return $cuerpo;
    }
    /**
    *
    */
    public function posicion($vendedor)
    {
    	if($this->vendedor===$vendedor)
    	{
    		$this->pos=$this->pos+1;
    	}
    	else
    	{
            $this->vendedor=$vendedor;
    		$this->pos=1;
    	}
    }
}
?>