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
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '300M');
        $this->excel=new PHPExcel();
        $this->excel->getProperties()->setCreator("RENOC")->setLastModifiedBy("RENOC")->setTitle("RENOC Distribucion Comercial")->setSubject("RENOC Distribucion Comercial")->setDescription("Reportes de Distribucion Comercial")->setKeywords("RENOC Reportes Distribucion Comercial")->setCategory("Distribucion Comercial Reportes");
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_wincache;
        $cacheSettings = array( 'cacheTime'=>600);
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
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
            'K'=>'Unidad de Produccion',
            'L'=>'Estado'
            );
        $hojas=array('Operador','Compañia','Monetizable','Termino de Pago','Unidad de Producción','Vendedor','Estado');
        foreach ($hojas as $key => $value)
        {
            $this->setDataToSheet($value,self::getData($value),$titles,$key);
        }
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
    private function setDataToSheet($name,$data,$titles,$index)
    {
        switch($name)
        {
            case 'Operador':
                $order="operador";
                break;
            case 'Compañia':
                $order="company";
                break;
            case 'Monetizable':
                $order="monetizable";
                break;
            case 'Termino de Pago':
                $order="termino_pago";
                break;
            case 'Unidad de Producción':
                $order="production_unit";
                break;
            case 'Vendedor':
                $order="vendedor";
                break;
            case 'Estado':
                $order="status";
                break;
        }
        $hoja = new PHPExcel_Worksheet($this->excel,$name);
        $this->excel->addSheet($hoja,$index);
        $this->excel->setActiveSheetIndexByName($name);
        //Asigno los nombres de las columnas al principio
        foreach ($titles as $column => $value)
        {
            $row=1;
            $this->excel->getActiveSheet()->setCellValue($column.$row,$value);
        }
        $estilosCabecera=array(
            'font'=>array(
                'bold'=>true,
                'color'=>array(
                    'argb'=>'FF62C25E'
                    ),
                ),
            'aligment'=>array(
                'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
            'borders'=>array(
                'allborders'=>array(
                    'style'=>PHPExcel_Style_Border::BORDER_THICK,
                    'color'=>array(
                        'argb'=>'00000000',
                        )
                    )
                ),
            'fill'=>array(
                'type'=>PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor'=>array(
                    'argb'=>'FF615E5E',
                    ),
                )
            );
        //Asigno colores a la primra fila
        $this->excel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($estilosCabecera);
        //Habilito un  auto tamaño en las columnas
        $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        //cargo los datos en las celdas
        $registro=array();
        $registro['posicion']=1;
        $registro['estilo']=1;
        foreach ($data as $key => $vendedor)
        {
            $com=$key-1;
            $registro['cargo']=$vendedor->position;
            $registro['operador']=$vendedor->operador;
            $registro['company']=$vendedor->company;
            $registro['vendedor']=$vendedor->vendedor;
            $registro['monetizable']=$vendedor->monetizable;
            $registro['termino_pago']="(".$vendedor->termino_pago.")";
            $registro['production_unit']=$vendedor->production_unit;
            $registro['status']=$vendedor->status;
            if($key>0)
            {
                if($data[$com]->$order==$vendedor->$order)
                {
                    $registro['posicion']+=1;
                }
                else
                {
                    $registro['posicion']=1;
                    $registro['estilo']+=1;
                }
                /*if($data[$com]->vendedor==$vendedor->vendedor)
                {
                    $registro['vendedor']="";
                    $registro['cargo']="";
                }
                if($data[$com]->termino_pago==$vendedor->termino_pago)
                {
                    $registro['termino_pago']="";
                }
                if($data[$com]->monetizable==$vendedor->monetizable)
                {
                    $registro['monetizable']="";
                }
                if($data[$com]->company==$vendedor->company)
                {
                    $registro['company']="";
                }
                if($data[$com]->operador==$vendedor->operador)
                {
                    $registro['operador']="";
                }
                if($data[$com]->production_unit==$vendedor->production_unit)
                {
                    $registro['production_unit']="";
                }*/
            }
            $row=$key+2;
            $this->excel->getActiveSheet()->setCellValue("A".$row,$registro['cargo']);
            $this->excel->getActiveSheet()->setCellValue("B".$row,$registro['vendedor']);
            $this->excel->getActiveSheet()->setCellValue("C".$row,$registro['posicion']);
            $this->excel->getActiveSheet()->setCellValue("D".$row,$registro['operador']);
            $this->excel->getActiveSheet()->setCellValue("E".$row,$registro['company']);
            $this->excel->getActiveSheet()->setCellValue("F".$row,$registro['termino_pago']);
            $this->excel->getActiveSheet()->setCellValue("G".$row,$registro['monetizable']);
            $this->excel->getActiveSheet()->setCellValue("H".$row,$vendedor->dias_disputa);
            $this->excel->getActiveSheet()->setCellValue("I".$row,$vendedor->limite_credito);
            $this->excel->getActiveSheet()->setCellValue("J".$row,$vendedor->limite_compra);
            $this->excel->getActiveSheet()->setCellValue("K".$row,$registro['production_unit']);
            $this->excel->getActiveSheet()->setCellValue("L".$row,$registro['status']);
            //Aplico el estilo
            $this->excel->getActiveSheet()->getStyle("A".$row.":L".$row)->applyFromArray(self::color($registro['estilo']));                

            
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
     * Metodo encargado de traer los datos
     * @access private
     * @static
     * @param date $startDate es la fecha de inicio de la consulta
     * @param date $endingDate es la fecha de fin de la consulta
     * @param string $type es el tipo de orden que se le dará a los datos
     * @return array $managers es el arreglo con todos los objetos  
     */
    private static function getData($type="Operador")
    {
        switch($type)
        {
            case 'Operador':
                $order="ORDER BY operador ASC, vendedor ASC, id_tp ASC";
                break;
            case 'Compañia':
                $order="ORDER BY company ASC,vendedor ASC, id_tp ASC";
                break;
            case 'Monetizable':
                $order="ORDER BY id_mon ASC,vendedor ASC, id_tp ASC";
                break;
            case 'Termino de Pago':
                $order="ORDER BY id_tp ASC,vendedor ASC, id_mon ASC";
                break;
            case 'Unidad de Producción':
                $order="ORDER BY production_unit ASC,vendedor ASC, id_tp ASC";
                break;
            case 'Vendedor':
                $order="ORDER BY vendedor ASC, operador ASC";
                break;
            case 'Estado':
                $order="ORDER BY status ASC, vendedor ASC, operador ASC";
                break;
        }
        $sql="SELECT m.name||' '||m.lastname AS vendedor, c.name AS operador, m.position, y.name AS company, z.name AS monetizable, tp.name AS termino_pago, d.days AS dias_disputa, cl.amount AS limite_credito, pl.amount AS limite_compra, z.id AS id_mon, tp.id AS id_tp, CASE WHEN x.up=1 THEN 'Presidencia' ELSE 'Ventas' END AS production_unit, CASE WHEN c.status=1 THEN 'Activo' WHEN c.status=0 THEN 'Inactivo' WHEN c.status IS NULL THEN 'Sin Asignar' END AS status
              FROM carrier c, managers m, carrier_managers cm, company y, contrato x, termino_pago tp, contrato_termino_pago ctp, monetizable z, contrato_monetizable cz, days_dispute_history d, credit_limit cl, purchase_limit pl 
              WHERE m.id=cm.id_managers AND c.id=cm.id_carrier AND cm.end_date IS NULL AND cm.start_date<=current_date AND x.id_carrier=c.id AND x.end_date IS NULL AND x.id_company=y.id AND x.id=ctp.id_contrato AND ctp.end_date IS NULL AND ctp.id_termino_pago=tp.id AND x.id=cz.id_contrato AND cz.end_date IS NULL AND cz.id_monetizable=z.id AND x.id=d.id_contrato AND d.end_date IS NULL AND x.id=cl.id_contrato AND cl.end_date IS NULL AND x.id=pl.id_contrato AND pl.end_date IS NULL
              UNION
              SELECT m.name||' '||m.lastname AS vendedor, c.name AS operador, m.position, 'Sin Asignar' AS company, 'Sin Asignar' AS monetizable, 'Sin Asignar' AS termino_pago, -1 AS dias_disputa, -1 AS limite_credito, -1 AS limite_compra, 100 AS id_mon, 100 AS id_tp, 'Sin Asignar' AS production_unit, 'Sin Asignar' AS status
              FROM carrier c, managers m, carrier_managers cm WHERE c.id NOT IN (SELECT DISTINCT(id_carrier) FROM contrato) AND m.id=cm.id_managers AND c.id=cm.id_carrier AND cm.end_date IS NULL AND cm.start_date<=current_date ".
              $order;
        return Managers::model()->findAllBySql($sql);
    }

    /**
     * Metodo encargado de devolver el arrya con el estilo indicado para cada columna
     * @access public
     * @static
     * @param $var
     * @param $alarmaStr
     * @param $alarmaInt
     * @return array $color
     */
    public static function color($var,$alarma=NULL)
    {
        $colorFuente=$colorFondo=null;
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
            $colorFuente="FFFFFFFF";
        }
        else
        {
            $colorFuente="FF584E4E;";
        }
        
        if($j==1)
        {
            $colorFondo="FFFE6500";
        }
        elseif($j==2)
        {
            $colorFondo="FF4AABC5";
        }
        elseif($j==3)
        {
            $colorFondo="FFDDCBCB";
        }
        elseif($j==4)
        {
            $colorFondo="FF3BA7DA";
        }
        elseif($j==5)
        {
            $colorFondo="FFFFCC99";
        }
        elseif($j==6)
        {
            $colorFondo="FFCC99ff";
        }
        elseif($j==7)
        {
            $colorFondo="FF68AD68";
        }
        elseif($j==8)
        {
            $colorFondo="FFff8080";
        }
        elseif($j==9)
        {
            $colorFondo="FFC0504D";
        }
        elseif($j==10)
        {
            $colorFondo="FFff9900";
        }
        elseif($j==11)
        {
            $colorFondo="FFC0C0C0";
        }
        elseif($j==12)
        {
            $colorFondo="FF00B0F0";
        }
        elseif($j==13)
        {
            $colorFondo="FF7DDADA";
        }
        elseif($j==14)
        {
            $colorFondo="FF7DDADA";
        }

        return array(
            'font'=>array(
                'color'=>array(
                    'argb'=>$colorFuente
                    ),
                ),
            'aligment'=>array(
                'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
            'borders'=>array(
                'allborders'=>array(
                    'style'=>PHPExcel_Style_Border::BORDER_THICK,
                    'color'=>array(
                        'argb'=>'00000000',
                        )
                    )
                ),
            'fill'=>array(
                'type'=>PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor'=>array(
                    'argb'=>$colorFondo,
                    ),
                )
            );
    }
}
?>