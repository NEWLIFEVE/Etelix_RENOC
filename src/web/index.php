<?php
date_default_timezone_set('America/Caracas');
//Definimos nuestro servidor de produccion 
define('SERVER_NAME_PROD','renoc.sacet.com.ve');
//Definimos nuestro servidor de preproduccion 
define('SERVER_NAME_PRE_PROD','devr.sacet.com.ve');
//Definimos nuestro servidor de desarrollo 
define('SERVER_NAME_DEV','renoc.local');
//Obtenemos el nombre del servidor actual 
$server=$_SERVER['SERVER_NAME'];

$yii=dirname(__FILE__).'/../../../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
switch ($server)
{
	case SERVER_NAME_PROD:
		defined('YII_DEBUG') or define('YII_DEBUG',false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
		break;
	case SERVER_NAME_PRE_PROD:
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
	case SERVER_NAME_DEV:
	default:
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
		break;
}

require_once($yii);
Yii::createWebApplication($config)->run();