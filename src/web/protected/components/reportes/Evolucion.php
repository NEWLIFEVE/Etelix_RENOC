<?php
/**
* Encargada de la construccion del archivo excel con los reportes de evolucion
*/
class Evolucion extends Reportes
{
    function __construct($fecha)
    {
        $this->fecha=$fecha;
    }
   
    /**
     * realiza la consulta de base de datos
     * @access  public
     * @param $columna string que columna consultar en base de datos
     * @param $dias int la cantidad de dias
     * @return $model el modelo con los datos
     */
    public function getData($columna,$dias)
    {
        $select="";
        if($columna=="margin")
        {
            $select="CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin";
        }
        elseif($columna=="revenue")
        {
            $select="SUM(revenue) AS revenue";
        }
        elseif($columna=="asr")
        {
            $select="(SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr";
        }
        elseif($columna=="aloc")
        {
            $select="CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd";
        }
        elseif($columna=="llamadas")
        {
            $select="SUM(incomplete_calls+complete_calls) AS total_calls";
        }
        $dias=$dias-1;
        $sql="SELECT date_balance, SUM(minutes) AS minutes, {$select}
              FROM balance
              WHERE date_balance>='{$this->fecha}'::date - '{$dias} days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
              GROUP BY date_balance
              ORDER BY date_balance DESC";
        $model=Balance::model()->findAllBySql($sql);
        return $model;
    }

    /**
     * realiza la consulta de base de datos
     * @access  public
     * @param $columna string que columna consultar en base de datos
     * @param $dias int la cantidad de dias
     * @return $model el modelo con los datos
     */
    public function getDataPerdidas($dias)
    {
        $dias=$dias-1;
        $sql="SELECT b.date_balance, SUM(b.minutes) AS minutes, SUM(b.margin) AS margin
              FROM(SELECT date_balance, SUM(minutes) AS minutes, CAST(0 AS double precision) AS margin
                   FROM balance
                   WHERE date_balance>='{$this->fecha}'::date - '{$dias} days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
                   GROUP BY date_balance
                   UNION
                   SELECT date_balance, CAST(0 AS double precision) AS minutes, CASE WHEN ABS(SUM(revenue-cost))<ABS(SUM(margin)) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
                   FROM balance
                   WHERE date_balance>='{$this->fecha}'::date - '{$dias} days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND margin<0
                   GROUP BY date_balance)b
              GROUP BY b.date_balance
              ORDER BY b.date_balance DESC";
        $model=Balance::model()->findAllBySql($sql);
        return $model;
    }

    /**
     *
     */
    public function genExcel($nombre)
    {
        $estilos=array(
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
                    'argb'=>'FFC6D9F1',
                    ),
                )
            );
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("RENOC")
                             ->setLastModifiedBy("RENOC")
                             ->setTitle("RENOC Evolucion")
                             ->setSubject("RENOC Evolucion")
                             ->setDescription("Reportes de Evolucion")
                             ->setKeywords("RENOC Evolucion")
                             ->setCategory("Evolucion RENOC");
        $objPHPExcel->getActiveSheet()->setTitle('Min vs $ 15 dias');
        $nombresHojas=array(
            'Min vs $ 60 dias',
            'Min vs Perdidas 15 dias',
            'Min vs Perdidas 60 dias',
            'Min vs Revenue 15 dias',
            'Min vs Revenue 60 dias',
            'Min vs ASR 15 dias',
            'Min vs ASR 60 dias',
            'Min vs ALOC 15 dias',
            'Min vs ALOC 60 dias',
            'Min vs Llamadas 15 dias',
            'Min vs Llamadas 60 dias'
            );
        foreach ($nombresHojas as $key => $value) 
        {
            $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel,$value);
            $objPHPExcel->addSheet($myWorkSheet);
        }

        $resultados[0]=$this->getData('margin',15);
        $resultados[1]=$this->getData('margin',60);
        $resultados[2]=$this->getDataPerdidas(15);
        $resultados[3]=$this->getDataPerdidas(60);
        $resultados[4]=$this->getData('revenue',15);
        $resultados[5]=$this->getData('revenue',60);
        $resultados[6]=$this->getData('asr',15);
        $resultados[7]=$this->getData('asr',60);
        $resultados[8]=$this->getData('aloc',15);
        $resultados[9]=$this->getData('aloc',60);
        $resultados[10]=$this->getData('llamadas',15);
        $resultados[11]=$this->getData('llamadas',60);

        $letra=array('A','B','C');
        $nombresColumnas=array('Margen','Margen','Pérdida','Pérdida','Revenue','Revenue','ASR','ASR','ALOC','ALOC','Llamadas Totales','Llamadas Totales');
        $nombresDatos=array('margin','margin','margin','margin','revenue','revenue','asr','asr','acd','acd','total_calls','total_calls');
        foreach ($nombresColumnas as $key => $value)
        {
            $objPHPExcel->setActiveSheetIndex($key)->setCellValue('A1', 'Fecha')->setCellValue('B1', 'Minutos')->setCellValue('C1', $value);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            foreach ($resultados[$key] as $llave => $valores)
            {
                $pos=$llave+2;
                $seg=strtotime($valores->date_balance);
                $fecha=date("m/d/Y",$seg);
                $objPHPExcel->setActiveSheetIndex($key)->setCellValue('A'.$pos, $fecha)->setCellValue('B'.$pos, $valores->minutes)->setCellValue('C'.$pos, $valores->$nombresDatos[$key]);
                $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($estilos);
            }
        }

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $ruta=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR;
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($ruta.$nombre);
    }
}
?>

