<?php
/**
* @var $this SiteController
*/
class SiteController extends Controller
{
    protected $letra;
    /**
     * Declares class-based actions.
     * @access public
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
                ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
                ),
            );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     * @access public
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if(!Yii::app()->user->isGuest)
        {
            $this->render('index');
        }
        else
        {
            $model = new LoginForm;
            // if it is ajax validation request
            if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            // collect user input data
            if(isset($_POST['LoginForm']))
            {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if($model->validate() && $model->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            // display the login form
            $this->render('login', array('model' => $model));
        }
    }

    /**
     * This is the action to handle external exceptions.
     * @access public
     */
    public function actionError()
    {
        if($error = Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        } 
    }

    /**
     * renderiza vista rutinarios
     * @access public
     */
    public function actionRutinarios()
    {
        $this->render('rutinarios');
    }

    /**
     * Renderiza vista personalizados
     * @access public
     */
    public function actionPersonalizados()
    {
        $this->render('personalizados');
    }

    /**
     * Renderiza vista especificos
     * @access public
     */
    public function actionEspecificos()
    {
        $this->render('especificos');
    }

    /**
     * @access public
     */
    public function actionContact()
    {
        $model=new ContactForm;
        if(isset($_GET['ContactForm']))
        {
            $model->attributes=$_GET['ContactForm'];
            if($model->validate())
            {
                $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
                $headers="From: $name <{$model->email}>\r\n".
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";
                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     * @access public
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        // if it is ajax validation request
        if(isset($_GET['ajax']) && $_GET['ajax'] === 'login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if(isset($_GET['LoginForm']))
        {
            $model->attributes=$_GET['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     * @access public
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionRanking()
    {
        $inicio=$_GET['inicio'];
        $fin=$_GET['fin'];
        $correo=Yii::app()->reportes->RankingCompraVenta($inicio,$fin);
        $this->genExcel('Rankin CompraVenta de '.str_replace("-","",$inicio).' al '.str_replace("-","",$fin),$correo);
    }

    public function actionCalidad()
    {
        $inicio=$_GET['inicio'];
        $fin=$_GET['fin'];
        $carrier=$_GET['carrier'];
        $correo=Yii::app()->reportes->Calidad($inicio,$fin,$carrier);
        $this->genExcel('Rankin Calidad de '.Carrier::getName($carrier)." al ".str_replace("-","",$inicio).' al '.str_replace("-","",$fin),$correo);
    }


    /**
     * Action encargada de envuiar por mail el tipo de reporte seleccionado,
     * las especificaciones seran recibidas desde el array $_GET
     * @access public
     */
    public function actionMail()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_POST['startDate']);
        $startDate=null;
        $endingDate=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_POST['startDate']))
        {
            $startDate=(string)$_POST['startDate'];
            if(isset($_POST['endingDate'])) $endingDate=$_POST['endingDate'];
            //Ranking Compra Venta
            if(isset($_POST['lista']['compraventa']))
            {
                $correos['compraventa']['asunto']="RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$startDate);
                $correos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($startDate,$endingDate);
                $correos['compraventa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$startDate).".xls";
            }
            //Perdidas
            if(isset($_POST['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC".$this->letra." Perdidas al ".str_replace("-","",$startDate);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($startDate);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Perdidas al ".str_replace("-","",$startDate).".xls";
            }
            // Alto Impacto Retail
            if(isset($_POST['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$startDate);
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($startDate);
                $correos['altoImpactoRetail']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$startDate).".xls";
            }
            //Alto Impacto +10$
            if(isset($_POST['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$startDate);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate);
                $correos['altoImpacto']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$startDate).".xls";
            }
            //Alto Impacto +10$ por Vendedor
            if(isset($_POST['lista']['AI10V']))
            {
                $correos['altoImpactoVendedor']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$startDate);
                $correos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($startDate);
                $correos['altoImpactoVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$startDate).".xls";
            }
            //Posicion Neta
            if(isset($_POST['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$startDate);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($startDate);
                $correos['posicionNeta']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$startDate).".xls";
            }
            //Posicion Neta por vendedor
            if(isset($_POST['lista']['PNV']))
            {
                $correos['posicionNetaVendedor']['asunto']="RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$startDate);
                $correos['posicionNetaVendedor']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($startDate);
                $correos['posicionNetaVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_POST['lista']['ADI']))
            {
                $correos['ADI']['asunto']="RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$startDate);
                $correos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,false);
                $correos['ADI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Destino External
            if(isset($_POST['lista']['ADE']))
            {
                $correos['ADE']['asunto']="RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$startDate);
                $correos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,true);
                $correos['ADE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_POST['lista']['ACI']))
            {
                $correos['ACI']['asunto']="RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$startDate);
                $correos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,false);
                $correos['ACI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Clientes External
            if(isset($_POST['lista']['ACE']))
            {
                $correos['ACE']['asunto']="RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$startDate);
                $correos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,true);
                $correos['ACE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_POST['lista']['API']))
            {
                $correos['API']['asunto']="RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$startDate);
                $correos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,false);
                $correos['API']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Proveedores External
            if(isset($_POST['lista']['APE']))
            {
                $correos['APE']['asunto']="RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$startDate);
                $correos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,true);
                $correos['APE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Vendedor
            if(isset($_POST['lista']['DCV']))
            {
                $correos['DCV']['asunto']="DC Vendedor al ".str_replace("-","",$startDate);
                $correos['DCV']['cuerpo']=Yii::app()->reportes->distComercialVendedor($startDate);
                $correos['DCV']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Vendedor al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Terminos de Pago
            if(isset($_POST['lista']['DCTP']))
            {
                $correos['DCTP']['asunto']="DC Termino Pago al ".str_replace("-","",$startDate);
                $correos['DCTP']['cuerpo']=Yii::app()->reportes->distComercialTerminoPago($startDate);
                $correos['DCTP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Termino Pago al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Monetizable
            if(isset($_POST['lista']['DCM']))
            {
                $correos['DCM']['asunto']="DC Monetizable al ".str_replace("-","",$startDate);
                $correos['DCM']['cuerpo']=Yii::app()->reportes->distComercialMonetizable($startDate);
                $correos['DCM']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Monetizable al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Compañia
            if(isset($_POST['lista']['DCCom']))
            {
                $correos['DCCom']['asunto']="DC Compania al ".str_replace("-","",$startDate);
                $correos['DCCom']['cuerpo']=Yii::app()->reportes->distComercialCompany($startDate);
                $correos['DCCom']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Compania al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Carrier
            if(isset($_POST['lista']['DCCarrier']))
            {
                $correos['DCCarrier']['asunto']="DC Carrier al ".str_replace("-","",$startDate);
                $correos['DCCarrier']['cuerpo']=Yii::app()->reportes->distComercialCarrier($startDate);
                $correos['DCCarrier']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Unidad de Produccion
            if(isset($_POST['lista']['DCUP']))
            {
                $correos['DCUP']['asunto']="DC Unidad de Produccion al ".str_replace("-","",$startDate);
                $correos['DCUP']['cuerpo']=Yii::app()->reportes->distComercialUnidadProduccion($startDate);
                $correos['DCUP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$startDate).".xls";
            }
            if(isset($_POST['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$nombre);
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
            }
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        { 
            if(stripos($correo['asunto'],"Evolucion")==false)
            {
                $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            }
            Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta']);
        }
        echo "Mensaje Enviado";
    }

    /**
     * @access public
     */
    public function actionExcel()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_GET['startDate']);
        $startDate=null;
        $endingDate=null;
        $archivos=array();
        if(isset($_GET['startDate']))
        {
            $startDate=(string)$_GET['startDate'];
            if(isset($_GET['endingDate'])) $endingDate=$_GET['endingDate'];
            if(isset($_GET['lista']['compraventa']))
            {
                $archivos['compraventa']['nombre']="RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$startDate);
                $archivos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($startDate,$endingDate);
            }
            if(isset($_GET['lista']['perdidas']))
            {
                $archivos['perdidas']['nombre']="RENOC".$this->letra." Perdidas al ".str_replace("-","",$startDate);
                $archivos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($startDate);
            }
            if(isset($_GET['lista']['AIR']))
            {
                $archivos['altoImpactoRetail']['nombre']="RENOC".$this->letra." Alto Impacto RETAIL (+1$) al ".str_replace("-","",$startDate);
                $archivos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($startDate);
            }
            if(isset($_GET['lista']['AI10']))
            {
                $archivos['altoImpacto']['nombre']="RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$startDate);
                $archivos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate);
            } 
            if(isset($_GET['lista']['AI10V']))
            {
                $archivos['altoImpactoVendedor']['nombre']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$startDate);
                $archivos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($startDate);
            } 
            if(isset($_GET['lista']['PN']))
            {
                $archivos['posicionNeta']['nombre']="RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$startDate);
                $archivos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($startDate);
            }
            if(isset($_GET['lista']['PNV']))
            {
                $archivos['posicionNetaVendedor']['nombre']="RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$startDate);
                $archivos['posicionNetaVendedor']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($startDate);
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_GET['lista']['ADI']))
            {
                $archivos['ADI']['nombre']="RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$startDate);
                $archivos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,false);
            }
            //Arbol de Trafico Destino External
            if(isset($_GET['lista']['ADE']))
            {
                $archivos['ADE']['nombre']="RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$startDate);
                $archivos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,true);
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_GET['lista']['ACI']))
            {
                $archivos['ACI']['nombre']="RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$startDate);
                $archivos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,false);
            }
            //Arbol de Trafico Clientes External
            if(isset($_GET['lista']['ACE']))
            {
                $archivos['ACE']['nombre']="RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$startDate);
                $archivos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,true);
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_GET['lista']['API']))
            {
                $archivos['API']['nombre']="RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$startDate);
                $archivos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,false);
            }
            //Arbol de Trafico Proveedores External
            if(isset($_GET['lista']['APE']))
            {
                $archivos['APE']['nombre']="RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$startDate);
                $archivos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,true);
            }
            //Distribucion Comercial por Vendedor
            if(isset($_GET['lista']['DCV']))
            {
                $archivos['DCV']['nombre']="DC Vendedor al ".str_replace("-","",$startDate);
                $archivos['DCV']['cuerpo']=Yii::app()->reportes->distComercialVendedor($startDate);
            }
            //Distribucion Comercial por Terminos de Pago
            if(isset($_GET['lista']['DCTP']))
            {
                $archivos['DCTP']['nombre']="DC Termino Pago al ".str_replace("-","",$startDate);
                $archivos['DCTP']['cuerpo']=Yii::app()->reportes->distComercialTerminoPago($startDate);
            }
            //Distribucion Comercial por Monetizable
            if(isset($_GET['lista']['DCM']))
            {
                $archivos['DCM']['nombre']="DC Monetizable al ".str_replace("-","",$startDate);
                $archivos['DCM']['cuerpo']=Yii::app()->reportes->distComercialMonetizable($startDate);
            }
            //Distribucion Comercial por Compañia
            if(isset($_GET['lista']['DCCom']))
            {
                $archivos['DCCom']['nombre']="DC Compania al ".str_replace("-","",$startDate);
                $archivos['DCCom']['cuerpo']=Yii::app()->reportes->distComercialCompany($startDate);
            }
            //Distribucion Comercial por Carrier
            if(isset($_GET['lista']['DCCarrier']))
            {
                $archivos['DCCarrier']['nombre']="DC Carrier al ".str_replace("-","",$startDate);
                $archivos['DCCarrier']['cuerpo']=Yii::app()->reportes->distComercialCarrier($startDate);
            }
            //Distribucion Comercial por Unidad de Produccion
            if(isset($_GET['lista']['DCUP']))
            {
                $archivos['DCUP']['nombre']="DC Unidad de Produccion al ".str_replace("-","",$startDate);
                $archivos['DCUP']['cuerpo']=Yii::app()->reportes->distComercialUnidadProduccion($startDate);
            }
            if(isset($_GET['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
                $archivos['Ev']['nombre']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate);
                $archivos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$nombre);
            }
        }
        foreach($archivos as $key => $archivo)
        {
            $this->genExcel($archivo['nombre'],$archivo['cuerpo']);
        }
    }

    /**
     * Action encargada de enviar por mail el tipo de reporte seleccionado,
     * las especificaciones seran recibidas desde el array $_GET
     * @access public
     */
    public function actionMaillista()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_POST['startDate']);
        $startDate=null;
        $endingDate=null;
        $correos=null;
        //$user="renoc@etelix.com";
        $user="mmzmm3z@gmail.com";
        if(isset($_POST['startDate']))
        {
            $startDate=(string)$_POST['startDate'];
            if(isset($_POST['endingDate'])) $endingDate=$_POST['endingDate'];
            //Ranking Compra Venta
            if(isset($_POST['lista']['compraventa']))
            {
                $correos['compraventa']['asunto']="RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$startDate);
                $correos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($startDate,$endingDate);
                $correos['compraventa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$startDate).".xls";
            }
            //Perdidas
            if(isset($_POST['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC".$this->letra." Perdidas al ".str_replace("-","",$startDate);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($startDate);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Perdidas al ".str_replace("-","",$startDate).".xls";
            }
            // Alto Impacto Retail
            if(isset($_POST['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$startDate);
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($startDate);
                $correos['altoImpactoRetail']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$startDate).".xls";
            }
            //Alto Impacto +10$
            if(isset($_POST['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$startDate);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate);
                $correos['altoImpacto']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$startDate).".xls";
            }
            //Alto Impacto +10$ por Vendedor
            if(isset($_POST['lista']['AI10V']))
            {
                $correos['altoImpactoVendedor']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$startDate);
                $correos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($startDate);
                $correos['altoImpactoVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$startDate).".xls";
            }
            //Posicion Neta
            if(isset($_POST['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$startDate);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($startDate);
                $correos['posicionNeta']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$startDate).".xls";
            }
            //Posicion Neta por vendedor
            if(isset($_POST['lista']['PNV']))
            {
                $correos['posicionNetaVendedor']['asunto']="RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$startDate);
                $correos['posicionNetaVendedor']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($startDate);
                $correos['posicionNetaVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_POST['lista']['ADI']))
            {
                $correos['ADI']['asunto']="RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$startDate);
                $correos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,false);
                $correos['ADI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Destino External
            if(isset($_POST['lista']['ADE']))
            {
                $correos['ADE']['asunto']="RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$startDate);
                $correos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,true);
                $correos['ADE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_POST['lista']['ACI']))
            {
                $correos['ACI']['asunto']="RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$startDate);
                $correos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,false);
                $correos['ACI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Clientes External
            if(isset($_POST['lista']['ACE']))
            {
                $correos['ACE']['asunto']="RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$startDate);
                $correos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,true);
                $correos['ACE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_POST['lista']['API']))
            {
                $correos['API']['asunto']="RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$startDate);
                $correos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,false);
                $correos['API']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Proveedores External
            if(isset($_POST['lista']['APE']))
            {
                $correos['APE']['asunto']="RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$startDate);
                $correos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,true);
                $correos['APE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Vendedor
            if(isset($_POST['lista']['DCV']))
            {
                $correos['DCV']['asunto']="DC Vendedor al ".str_replace("-","",$startDate);
                $correos['DCV']['cuerpo']=Yii::app()->reportes->distComercialVendedor($startDate);
                $correos['DCV']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Vendedor al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Terminos de Pago
            if(isset($_POST['lista']['DCTP']))
            {
                $correos['DCTP']['asunto']="DC Termino Pago al ".str_replace("-","",$startDate);
                $correos['DCTP']['cuerpo']=Yii::app()->reportes->distComercialTerminoPago($startDate);
                $correos['DCTP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Termino Pago al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Monetizable
            if(isset($_POST['lista']['DCM']))
            {
                $correos['DCM']['asunto']="DC Monetizable al ".str_replace("-","",$startDate);
                $correos['DCM']['cuerpo']=Yii::app()->reportes->distComercialMonetizable($startDate);
                $correos['DCM']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Monetizable al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Compañia
            if(isset($_POST['lista']['DCCom']))
            {
                $correos['DCCom']['asunto']="DC Compania al ".str_replace("-","",$startDate);
                $correos['DCCom']['cuerpo']=Yii::app()->reportes->distComercialCompany($startDate);
                $correos['DCCom']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Compania al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Carrier
            if(isset($_POST['lista']['DCCarrier']))
            {
                $correos['DCCarrier']['asunto']="DC Carrier al ".str_replace("-","",$startDate);
                $correos['DCCarrier']['cuerpo']=Yii::app()->reportes->distComercialCarrier($startDate);
                $correos['DCCarrier']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$startDate).".xls";
            }
            //Distribucion Comercial por Unidad de Produccion
            if(isset($_POST['lista']['DCUP']))
            {
                $correos['DCUP']['asunto']="DC Unidad de Produccion al ".str_replace("-","",$startDate);
                $correos['DCUP']['cuerpo']=Yii::app()->reportes->distComercialUnidadProduccion($startDate);
                $correos['DCUP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$startDate).".xls";
            }
            if(isset($_POST['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$nombre);
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
            }
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        {
            if(stripos($correo['asunto'],"Evolucion")==false)
            {
                $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            }
            if(stripos($correo['asunto'], "RETAIL"))
            {
                /*$lista=array('CarlosBuona@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta'],$lista);*/
            }
            else
            {
                Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta']);
            }
        }
        echo "Mensaje Enviado";
    }

    /**
     * @access public
     */
    public function genExcel($nombre,$html,$salida=true)
    {
        if(stripos($nombre,"Evolucion"))
        {
            header("Location: /adjuntos/{$nombre}.xlsx");
        }
        else
        {
            if($salida)
            {
                header("Content-type: application/vnd.ms-excel; charset=utf-8"); 
                header("Content-Disposition: attachment; filename={$nombre}.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $html;
            }
            else
            {
                $ruta=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR;
                $fp=fopen($ruta."$nombre.xls","w+");
                $cuerpo="
                <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset='utf-8'>
                        <meta http-equiv='Content-Type' content='application/vnd.ms-excel charset=utf-8'>
                    </head>
                    <body>";
                $cuerpo.=$html;
                $cuerpo.="</body>
                </html>";
                fwrite($fp,$cuerpo);
            }
        }
    }

    /**
     * @access public
     */
    public function vaciarAdjuntos()
    {
        $ruta=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR;
            if(is_dir($ruta))
            {
                $archivos=@scandir($ruta);
            }
            if(count($archivos)>1)
            {
                foreach ($archivos as $key => $value)
                {
                    if($key>1)
                    { 
                        if($value!='index.html')
                        {
                            unlink($ruta.$value);
                        }
                    }
                }
            }
    }
}
?>



