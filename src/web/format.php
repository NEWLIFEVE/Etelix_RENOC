<?php

$num = 1120045.2356;
$english_format_number2 = number_format($num, 10, ',', '.');
$numtext=strval($english_format_number2);

$position = strpos($numtext, ',');
$numsub = substr($numtext,0,$position+3);   





/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
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
