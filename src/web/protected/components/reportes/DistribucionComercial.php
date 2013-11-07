<?php
/**
* @package reportes
*/
class DistribucionComercial extends Reportes
{
    /**
     * Contiene el objeto del excel
     * @access private
     * @var $excel;
     */
    private $excel;

    /**
     * Instancio el objeto del excel
     */
    function __construct()
    {
        $this->excel=new PHPExcel();
        $this->excel->getProperties()->setCreator("RENOC")->setLastModifiedBy("RENOC")->setTitle("RENOC Distribucion Comercial")->setSubject("RENOC Distribucion Comercial")->setDescription("Reportes de Distribucion Comercial")->setKeywords("RENOC Reportes Distribucion Comercial")->setCategory("Distribucion Comercial Reportes");
    }

    public function genExcel($name)
    {    
        $titles=array(
            'A'=>'Cargo',
            'B'=>'Responsable',
            'C'=>'Posicion',
            'D'=>'Operador',
            'E'=>'Compañia',
            'F'=>'Termino Pago',
            'G'=>'Monetizable',
            'H'=>'Dias de Disputa',
            'I'=>'Limite de Credito',
            'J'=>'Limite de Compra',
            'K'=>'Unidad de Produccion'
            );
        $this->setDataToSheet('Vendedor',self::getData('vendedor'),$titles);
        $this->excel->setActiveSheetIndex(0);
        $this->writeFile($name);
    }

    /**
     * Introduce los datos recibidos en la hoja excel con los nombres indicados
     * @access private
     * @param string $name es el nombre que va a llevar la hoja de estilo
     * @param CActiveRecord $data es el objeto con el modelo del reporte
     * @param array $titles son los titulos que llevara la tabla creada,
     * para facilitar la carga de los nombres de las columnas colocar la letra que acompaña cada tituto
     * @return void
     */
    private function setDataToSheet($name,$data,$titles)
    {
        $hoja = new PHPExcel_Worksheet($this->excel,$name);
        $this->excel->addSheet($hoja,0);
        $this->excel->setActiveSheetIndexByName($name);
        //Asigno los nombres de las columnas al principio
        foreach ($titles as $column => $value)
        {
            $row=1;
            $this->excel->getActiveSheet()->setCellValue($column.$row,$value);
        }
        //cargo los datos en las celdas
        foreach ($data as $key => $registro)
        {
            $row=$key+2;
            $pos=$key+1;
            $this->excel->getActiveSheet()
                        ->setCellValue("A".$row,$registro->position)
                        ->setCellValue("B".$row,$registro->vendedor)
                        ->setCellValue("C".$row,$pos)
                        ->setCellValue("D".$row,$registro->operador)
                        ->setCellValue("E".$row,$registro->company)
                        ->setCellValue("F".$row,$registro->termino_pago)
                        ->setCellValue("G".$row,$registro->monetizable)
                        ->setCellValue("H".$row,$registro->dias_disputa)
                        ->setCellValue("I".$row,$registro->limite_credito)
                        ->setCellValue("J".$row,$registro->limite_compra)
                        ->setCellValue("K".$row,$registro->production_unit);
        }
    }

    /**
     * Escribe el excel en la ruta asignada
     * @access private
     * @return void
     */
    private function writeFile($name)
    {
        $ruta=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR;
        $writer=PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $writer->save($ruta.$name);
    }


	/**
	* @param $fecha date fecha a ser consultada
	* @return $cuerpo string cuerpo del reporte
	*/
	/*public static function reporte($type)
	{
        switch($type)
        {
            case 'carrier':
                $order="operador";
                break;
            case 'company':
                $order="company";
                break;
            case 'monetizable':
                $order="monetizable";
                break;
            case 'pago':
                $order="termino_pago";
                break;
            case 'unidad':
                $order="production_unit";
                break;
            case 'vendedor':
                $order="vendedor";
                break;
        }
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
                            <th style='background-color:#615E5E; color:#62C25E; width:10%; height:100%;'>
                                Unidad de produccion
                            </th>
                        </tr>
                    </thead>
                    </tbody>";
        $vendedores=self::getData($type);
        if($vendedores!=null)
        {
            $registro=array();
            $registro['posicion']=1;
            $registro['estilo']=1;
            foreach ($vendedores as $key => $vendedor)
            {
                $com=$key-1;
                $registro['cargo']=$vendedor->position;
                $registro['operador']=$vendedor->operador;
                $registro['company']=$vendedor->company;
                $registro['vendedor']=$vendedor->vendedor;
                $registro['monetizable']=$vendedor->monetizable;
                $registro['termino_pago']="(".$vendedor->termino_pago.")";
                $registro['production_unit']=$vendedor->production_unit;
                if($key>0)
                {
                    if($vendedores[$com]->$order==$vendedor->$order)
                    {
                        $registro['posicion']+=1;
                    }
                    else
                    {
                        $registro['posicion']=1;
                        $registro['estilo']+=1;
                    }
                    if($vendedores[$com]->vendedor==$vendedor->vendedor)
                    {
                        $registro['vendedor']="";
                        $registro['cargo']="";
                    }
                    if($vendedores[$com]->termino_pago==$vendedor->termino_pago)
                    {
                        $registro['termino_pago']="";
                    }
                    if($vendedores[$com]->monetizable==$vendedor->monetizable)
                    {
                        $registro['monetizable']="";
                    }
                    if($vendedores[$com]->company==$vendedor->company)
                    {
                        $registro['company']="";
                    }
                    if($vendedores[$com]->operador==$vendedor->operador)
                    {
                        $registro['operador']="";
                    }
                    if($vendedores[$com]->production_unit==$vendedor->production_unit)
                    {
                        $registro['production_unit']="";
                    }
                }
                $cuerpo.="<tr>
                            <td style='".self::color($registro['estilo'])."'>".$registro['cargo']."</td>
                            <td style='".self::color($registro['estilo'])."'>".$registro['vendedor']."</td>
                            <td style='".self::color($registro['estilo'])."'>".$registro['posicion']."</td>
                            <td style='".self::color($registro['estilo'])."'>".$registro['operador']."</td>
                            <td style='".self::color($registro['estilo'],$registro['company'])."'>".$registro['company']."</td>
                            <td style='text-align: right;".self::color($registro['estilo'],$registro['termino_pago'])."'>".$registro['termino_pago']."</td>
                            <td style='".self::color($registro['estilo'],$registro['monetizable'])."'>".$registro['monetizable']."</td>
                            <td style='".self::color($registro['estilo'],$vendedor->dias_disputa)."'>".$vendedor->dias_disputa."</td>
                            <td style='".self::color($registro['estilo'],$vendedor->limite_credito)."'>".$vendedor->limite_credito."</td>
                            <td style='".self::color($registro['estilo'],$vendedor->limite_compra)."'>".$vendedor->limite_compra."</td>
                            <td style='".self::color($registro['estilo'],$registro['production_unit'])."'>".$registro['production_unit']."</td>
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
	}*/

    /**
     * Metodo encargado de traer los datos
     * @access private
     * @static
     * @param date $startDate es la fecha de inicio de la consulta
     * @param date $endingDate es la fecha de fin de la consulta
     * @param string $type es el tipo de orden que se le dará a los datos
     * @return array $managers es el arreglo con todos los objetos  
     */
    private static function getData($type="carrier")
    {
        switch($type)
        {
            case 'carrier':
                $order="ORDER BY operador ASC, vendedor ASC, id_tp ASC";
                break;
            case 'company':
                $order="ORDER BY company ASC,vendedor ASC, id_tp ASC";
                break;
            case 'monetizable':
                $order="ORDER BY id_mon ASC,vendedor ASC, id_tp ASC";
                break;
            case 'pago':
                $order="ORDER BY id_tp ASC,vendedor ASC, id_mon ASC";
                break;
            case 'unidad':
                $order="ORDER BY production_unit ASC,vendedor ASC, id_tp ASC";
                break;
            case 'vendedor':
                $order="ORDER BY vendedor ASC, operador ASC";
                break;
        }
        $sql="SELECT m.name||' '||m.lastname AS vendedor, c.name AS operador, m.position, y.name AS company, z.name AS monetizable, tp.name AS termino_pago, d.days AS dias_disputa, cl.amount AS limite_credito, pl.amount AS limite_compra, z.id AS id_mon, tp.id AS id_tp, CASE WHEN x.up=1 THEN 'Presidencia' ELSE 'Ventas' END AS production_unit
              FROM carrier c, managers m, carrier_managers cm, company y, contrato x, termino_pago tp, contrato_termino_pago ctp, monetizable z, contrato_monetizable cz, days_dispute_history d, credit_limit cl, purchase_limit pl 
              WHERE m.id=cm.id_managers AND c.id=cm.id_carrier AND cm.end_date IS NULL AND cm.start_date<=current_date AND x.id_carrier=c.id AND x.end_date IS NULL AND x.id_company=y.id AND x.id=ctp.id_contrato AND ctp.end_date IS NULL AND ctp.id_termino_pago=tp.id AND x.id=cz.id_contrato AND cz.end_date IS NULL AND cz.id_monetizable=z.id AND x.id=d.id_contrato AND d.end_date IS NULL AND x.id=cl.id_contrato AND cl.end_date IS NULL AND x.id=pl.id_contrato AND pl.end_date IS NULL
              UNION
              SELECT m.name||' '||m.lastname AS vendedor, c.name AS operador, m.position,'Sin Asignar' AS company, 'Sin Asignar' AS monetizable, 'Sin Asignar' AS termino_pago, -1 AS dias_disputa, -1 AS limite_credito, -1 AS limite_compra, 100 AS id_mon,100 AS id_tp, 'Sin Asignar' AS production_unit
              FROM carrier c, managers m, carrier_managers cm
              WHERE c.id NOT IN (SELECT DISTINCT(id_carrier) FROM contrato) AND m.id=cm.id_managers AND c.id=cm.id_carrier AND cm.end_date IS NULL AND cm.start_date<=current_date ".
              $order;
        return Managers::model()->findAllBySql($sql);
    }

    /**
     * @access public
     * @static
     * @param $var
     * @param $alarmaStr
     * @param $alarmaInt
     * @return string $color
     */
    /*public static function color($var,$alarma=NULL)
    {
        $color=null;
        $j=0;
        for($i=1;$i<=$var;$i++)
        { 
            if($j>=14)
            {
                $j=1;
            }
            else
            {
                $j=$j+1;
            }
        }
        if(isset($alarma) && substr_count($alarma, 'Sin Asignar') >= 1 || $alarma < 0)
        {
            $color="color:white;";
        }
        else
        {
            $color="color:#584E4E;";
        }
        
        if($j==1)
        {
            $color.="background-color:#fe6500; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==2)
        {
            $color.="background-color:#4aabc5; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==3)
        {
            $color.="background-color:#DDCBCB; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==4)
        {
            $color.="background-color:#3BA7DA; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==5)
        {
            $color.="background-color:#ffcc99; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==6)
        {
            $color.="background-color:#cc99ff; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==7)
        {
            $color.="background-color:rgb(104, 173, 104); border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==8)
        {
            $color.="background-color:#ff8080; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==9)
        {
            $color.="background-color:#c0504d; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==10)
        {
            $color.="background-color:#ff9900; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==11)
        {
            $color.="background-color:#c0c0c0; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==12)
        {
            $color.="background-color:#00b0f0; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==13)
        {
            $color.="background-color:#7DDADA; border: 1px solid rgb(121, 115, 115)";
        }
        elseif($j==14)
        {
            $color.="background-color:#7DDADA; border: 1px solid rgb(121, 115, 115)";
        }
        return $color;
    }*/
}
?>