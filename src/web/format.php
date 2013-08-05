<?php

$num = 1120045.2356;
$english_format_number2 = number_format($num, 10, ',', '.');
$numtext=strval($english_format_number2);

$position = strpos($numtext, ',');
$numsub = substr($numtext,0,$position+3);   

function formatearFecha($fecha, $tipo=NULL) {

        if($tipo==NULL){
            
            $arrayFecha = explode("/", $fecha);

            if (strlen($arrayFecha[0]) == 1) {
                $arrayFecha[0] = "0" . $arrayFecha[0];
            }
            if (strlen($arrayFecha[1]) == 1) {
                $arrayFecha[1] = "0" . $arrayFecha[1];
            }

            $fechaFinal = $arrayFecha[2] . "-" . $arrayFecha[0] . "-" . $arrayFecha[1];
            return $fechaFinal;
        }
        
        if($tipo=='etelixPeru'){
            
            $arrayFecha = explode(" ", $fecha);
            return $arrayFecha[0];
            
        }
        
    }
    
$fecha = '07/24/2013';
$fecha_mod = formatearFecha($fecha);


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php echo "FechaOriginal: ".$fecha;?>
<br/>
<br/>
<?php echo "FechaModificada: ".$fecha_mod;?>
<br/>
<br/>
<?php echo "Original: ".$num;?>
<br/>
<br/>
<?php echo "Formateado: ".$english_format_number2;?>
<br/>
<br/>
<?php echo "Num en TEXTO: ".$numtext;?>
<br/>
<br/>
<?php echo "poscion del punto: ".$position;?>
<br/>
<br/>
<?php echo "Num en TEXTO subs: ".$numsub;?>
<br/>
<br/>
