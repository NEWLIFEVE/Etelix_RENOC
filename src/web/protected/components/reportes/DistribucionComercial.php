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

    public function genExcel($name,$startDate)
    {    
        $titles=array(
            'A'=>'Cargo',
            'B'=>'Responsable',
            'C'=>'Posicion',
            'D'=>'Operador',
            'E'=>'Compañia',
            'F'=>'Termino Pago Vendedor',
            'G'=>'Termino Pago Cliente',
            'H'=>'Monetizable',
            'I'=>'Dias de Disputa',
            'J'=>'Limite de Credito',
            'K'=>'Limite de Compra',
            'L'=>'Unidad de Producción',
            'M'=>'Estado',
            );
        $hojas=array('Operador','Compañia','Monetizable','Termino de Pago','Unidad de Producción','Vendedor','Estado');
        foreach ($hojas as $key => $value)
        {
            $this->setDataToSheet($value,self::getData($startDate,$value),$titles,$key);
        }
        $this->excel->setActiveSheetIndex(0);
        try
        {
            $this->writeFile($name);
        }
        catch(Exception $e)
        {
            echo "Exception capturada: ", $e->getMessage(), "\n";
        }
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
                $order="carrier";
                break;
            case 'Compañia':
                $order="company"; // =
                break;
            case 'Monetizable':
                $order="monetizable";
                break;
            case 'Termino de Pago':
                $order="customer_payment_term";
                break;
            case 'Unidad de Producción':
                $order="production_unit";
                break;
            case 'Vendedor':
                $order="seller";
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
        $this->excel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($estilosCabecera);
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
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        //cargo los datos en las celdas
        $registro=array();
        $registro['posicion']=1;
        $registro['estilo']=1;
        foreach ($data as $key => $vendedor)
        {
            $com=$key-1;
            $registro['cargo']=$vendedor->position;
            $registro['carrier']=$vendedor->carrier;
            $registro['company']=$vendedor->company; // =
            $registro['seller']=$vendedor->seller;
            $registro['monetizable']=$vendedor->monetizable;
            $registro['vendor_payment_term']="(".$vendedor->vendor_payment_term.")";
            $registro['customer_payment_term']="(".$vendedor->customer_payment_term.")";
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
            $this->excel->getActiveSheet()->setCellValue("B".$row,$registro['seller']);
            $this->excel->getActiveSheet()->setCellValue("C".$row,$registro['posicion']);
            $this->excel->getActiveSheet()->setCellValue("D".$row,$registro['carrier']);
            $this->excel->getActiveSheet()->setCellValue("E".$row,$registro['company']); // =
            $this->excel->getActiveSheet()->setCellValue("F".$row,$registro['vendor_payment_term']);
            $this->excel->getActiveSheet()->setCellValue("G".$row,$registro['customer_payment_term']);
            $this->excel->getActiveSheet()->setCellValue("H".$row,$registro['monetizable']);
            $this->excel->getActiveSheet()->setCellValue("I".$row,$vendedor->days_dispute);
            $this->excel->getActiveSheet()->setCellValue("J".$row,$vendedor->credit_limit);
            $this->excel->getActiveSheet()->setCellValue("K".$row,$vendedor->purchase_limit);
            $this->excel->getActiveSheet()->setCellValue("L".$row,$registro['production_unit']);
            $this->excel->getActiveSheet()->setCellValue("M".$row,$registro['status']);
            //Aplico el estilo
            $this->excel->getActiveSheet()->getStyle("A".$row.":M".$row)->applyFromArray(self::color($registro['estilo']));
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
    private static function getData($startDate,$type="Operador")
    {
        switch($type)
        {
            case 'Operador':
                $order="ORDER BY carrier ASC, seller ASC, id_tp ASC";
                break;
            case 'Compañia':
                $order="ORDER BY company ASC, seller ASC, id_tp ASC"; // =
                break;
            case 'Monetizable':
                $order="ORDER BY id_mon ASC, seller ASC, id_tp ASC";
                break;
            case 'Termino de Pago':
                $order="ORDER BY id_tp ASC, seller ASC, id_mon ASC";
                break;
            case 'Unidad de Producción':
                $order="ORDER BY production_unit ASC, seller ASC, id_tp ASC";
                break;
            case 'Vendedor':
                $order="ORDER BY seller ASC, carrier ASC";
                break;
            case 'Estado':
                $order="ORDER BY status ASC, seller ASC, carrier ASC";
                break;
        }    



             $sql="SELECT seller,
              position,
              carrier,
              company,
              CASE WHEN (SELECT name
               FROM monetizable,
               (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_monetizable
                 FROM contrato_monetizable
                 WHERE start_date<='{$startDate}') contrato_monetizable
                  WHERE contrato_id=contrato_monetizable.id_contrato AND contrato_monetizable.id_monetizable=monetizable.id LIMIT 1) IS NULL THEN 'Sin Asignar'
                  ELSE (SELECT name
                    FROM monetizable,
                    (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_monetizable
                      FROM contrato_monetizable
                      WHERE start_date<='{$startDate}') contrato_monetizable
                  WHERE contrato_id=contrato_monetizable.id_contrato AND contrato_monetizable.id_monetizable=monetizable.id LIMIT 1) END AS monetizable,
                  CASE WHEN (SELECT name
                   FROM termino_pago,
                   (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                     FROM contrato_termino_pago
                     WHERE start_date<='{$startDate}') ctp
                  WHERE ctp.id_contrato=contrato_id  AND ctp.id_termino_pago=termino_pago.id AND ctp.end_date>='{$startDate}' LIMIT 1) IS NULL THEN 'Sin Asignar'
                  ELSE (SELECT name
                   FROM termino_pago,
                   (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                     FROM contrato_termino_pago
                     WHERE start_date<='{$startDate}') ctp
                  WHERE ctp.id_contrato=contrato_id  AND ctp.id_termino_pago=termino_pago.id AND ctp.end_date>='{$startDate}' LIMIT 1) END AS customer_payment_term,
                  CASE WHEN (SELECT name
                   FROM termino_pago,
                   (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                     FROM contrato_termino_pago_supplier
                     WHERE start_date<='{$startDate}') ctp
                  WHERE ctp.id_contrato=contrato_id  AND ctp.id_termino_pago_supplier=termino_pago.id AND ctp.end_date>='{$startDate}' LIMIT 1) IS NULL THEN 'Sin Asignar'
                  ELSE (SELECT name
                   FROM termino_pago,
                   (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier
                     FROM contrato_termino_pago_supplier
                     WHERE start_date<='{$startDate}' ) ctp
                  WHERE ctp.id_contrato=contrato_id  AND ctp.id_termino_pago_supplier=termino_pago.id AND ctp.end_date>='{$startDate}' LIMIT 1) END AS vendor_payment_term,
                  (SELECT days
                    FROM (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, days
                      FROM days_dispute_history
                      WHERE start_date<='{$startDate}') ddh
                  WHERE ddh.id_contrato=contrato_id AND ddh.end_date>='{$startDate}' LIMIT 1) AS days_dispute,
                  (SELECT amount
                   FROM (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, amount
                    FROM credit_limit
                    WHERE start_date<='{$startDate}') cl
                  WHERE cl.id_contrato=contrato_id AND cl.end_date>='{$startDate}' LIMIT 1) AS credit_limit,
                  (SELECT amount
                    FROM (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, amount
                      FROM purchase_limit
                      WHERE start_date<='{$startDate}') pl
                  WHERE pl.id_contrato=contrato_id AND pl.end_date>='{$startDate}' LIMIT 1) AS purchase_limit,
                  (SELECT monetizable.id
                    FROM monetizable,
                    (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_monetizable
                      FROM contrato_monetizable
                      WHERE start_date<='{$startDate}') contrato_monetizable
                  WHERE contrato_id=contrato_monetizable.id_contrato AND contrato_monetizable.id_monetizable=monetizable.id LIMIT 1) AS id_mon, 
                  (SELECT termino_pago.id
                   FROM termino_pago,
                   (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
                     FROM contrato_termino_pago
                     WHERE start_date<='{$startDate}') ctp
                  WHERE ctp.id_contrato=contrato_id  AND ctp.id_termino_pago=termino_pago.id AND ctp.end_date>='{$startDate}' LIMIT 1) AS id_tp, 
                  CASE WHEN up=1 THEN 'Presidencia' ELSE 'Ventas' END AS production_unit, 
                  CASE WHEN status=1 THEN 'Activo' WHEN status=0 THEN 'Inactivo' WHEN status IS NULL THEN 'Sin Asignar' END AS status
                  FROM
                  (SELECT id AS id,
                    name AS carrier,
                    CASE WHEN (SELECT name
                      FROM managers, 
                      (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_managers
                        FROM carrier_managers
                        WHERE start_date<='{$startDate}') carrier_manager
                  WHERE c.id=id_carrier AND id_managers=managers.id AND end_date>='{$startDate}' LIMIT 1) IS NULL THEN 'Sin Asignar' 
                  ELSE (SELECT name
                    FROM managers, 
                    (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_managers
                      FROM carrier_managers
                      WHERE start_date<= '{$startDate}') carrier_manager
                  WHERE c.id=id_carrier AND id_managers=managers.id AND end_date>= '{$startDate}' LIMIT 1) END AS seller,
                  CASE WHEN (SELECT position
                    FROM managers,
                    (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_managers
                      FROM carrier_managers
                      WHERE start_date<='{$startDate}') carrier_manager
                  WHERE c.id=id_carrier AND id_managers=managers.id AND end_date>='{$startDate}' LIMIT 1) IS NULL THEN 'Sin Asignar'
                  ELSE (SELECT position
                    FROM managers,
                    (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_managers
                      FROM carrier_managers
                      WHERE start_date<='{$startDate}') carrier_manager
                  WHERE c.id=id_carrier AND id_managers=managers.id AND end_date>= '{$startDate}' LIMIT 1) END AS position,
                  (SELECT id
                   FROM (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                     FROM contrato
                     WHERE sign_date<='{$startDate}') contrato
                  WHERE contrato.end_date>= '{$startDate}' AND contrato.id_carrier=c.id LIMIT 1) AS contrato_id,
                  (SELECT id_carrier
                   FROM (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                     FROM contrato
                     WHERE sign_date<='{$startDate}') contrato
                  WHERE contrato.end_date>= '{$startDate}' AND contrato.id_carrier=c.id LIMIT 1) AS carrier_contrato,
                  CASE WHEN (SELECT company.name
                   FROM company,
                   (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                     FROM contrato
                     WHERE sign_date<='{$startDate}') contrato
                  WHERE contrato.id_company=company.id AND contrato.end_date>= '{$startDate}' AND contrato.id_carrier=c.id LIMIT 1) IS NULL THEN 'Sin Contrato'
                  ELSE (SELECT company.name
                   FROM company,
                   (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                     FROM contrato
                     WHERE sign_date<='{$startDate}') contrato
                  WHERE contrato.id_company=company.id AND contrato.end_date>= '{$startDate}' AND contrato.id_carrier=c.id LIMIT 1) END AS company,
                  status AS status,
                  (SELECT up
                   FROM (SELECT id, sign_date, production_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_carrier, id_company, up, bank_fee
                     FROM contrato
                     WHERE sign_date<='{$startDate}') contrato
                  WHERE contrato.end_date>= '{$startDate}' AND contrato.id_carrier=c.id LIMIT 1) AS up
                  FROM carrier c
                  WHERE name <> 'Unknown_Carrier') carriers ".$order;

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