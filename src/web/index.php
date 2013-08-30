<?php
//
//     function format_decimal($num,$decimales=3)
//    {        
//        $english_format_number2 = number_format($num, 10, ',', '.');
//        $numtext=strval($english_format_number2);
//        $position = strpos($numtext, ',');
//        $numsub = substr($numtext,0,$position+$decimales); 
//        return $numsub;
//    }
//    
//$num=0.0000055;
//$str1=  format_decimal($num);
//if (strcmp($str1, "0,00")==0){
//
//    echo 'SI';
//    echo strcmp($str1, "0");
//    echo $str1;
//}else{
//    echo 'NO ';
//    echo strcmp($str1, "0");
//    echo $str1;
//}
//
//if ($str1=="0,00"){
//    echo 'tambien';
//}else{
//    echo 'tampoco';
//}


// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
