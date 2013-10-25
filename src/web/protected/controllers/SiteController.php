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
        $this->letra=Log::preliminar($_POST['fecha']);
        $fecha=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_POST['fecha']))
        {
            $fecha=(string)$_POST['fecha'];
            //Ranking Compra Venta
            if(isset($_POST['lista']['compraventa']))
            {
                $correos['compraventa']['asunto']="RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$fecha);
                $correos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($fecha);
                $correos['compraventa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$fecha).".xls";
            }
            //Perdidas
            if(isset($_POST['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC".$this->letra." Perdidas al ".str_replace("-","",$fecha);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($fecha);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Perdidas al ".str_replace("-","",$fecha).".xls";
            }
            // Alto Impacto Retail
            if(isset($_POST['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$fecha);
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
                $correos['altoImpactoRetail']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$fecha).".xls";
            }
            //Alto Impacto +10$
            if(isset($_POST['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
                $correos['altoImpacto']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$fecha).".xls";
            }
            //Alto Impacto +10$ por Vendedor
            if(isset($_POST['lista']['AI10V']))
            {
                $correos['altoImpactoVendedor']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha);
                $correos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($fecha);
                $correos['altoImpactoVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha).".xls";
            }
            //Posicion Neta
            if(isset($_POST['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$fecha);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
                $correos['posicionNeta']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$fecha).".xls";
            }
            //Posicion Neta por vendedor
            if(isset($_POST['lista']['PNV']))
            {
                $correos['posicionNetaVendedor']['asunto']="RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$fecha);
                $correos['posicionNetaVendedor']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($fecha);
                $correos['posicionNetaVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_POST['lista']['ADI']))
            {
                $correos['ADI']['asunto']="RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$fecha);
                $correos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($fecha,false);
                $correos['ADI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Destino External
            if(isset($_POST['lista']['ADE']))
            {
                $correos['ADE']['asunto']="RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$fecha);
                $correos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($fecha,true);
                $correos['ADE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_POST['lista']['ACI']))
            {
                $correos['ACI']['asunto']="RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$fecha);
                $correos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,true,false);
                $correos['ACI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Clientes External
            if(isset($_POST['lista']['ACE']))
            {
                $correos['ACE']['asunto']="RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$fecha);
                $correos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,true,true);
                $correos['ACE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_POST['lista']['API']))
            {
                $correos['API']['asunto']="RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$fecha);
                $correos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,false,false);
                $correos['API']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Proveedores External
            if(isset($_POST['lista']['APE']))
            {
                $correos['APE']['asunto']="RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$fecha);
                $correos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,false,true);
                $correos['APE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Vendedor
            if(isset($_POST['lista']['DCV']))
            {
                $correos['DCV']['asunto']="DC Vendedor al ".str_replace("-","",$fecha);
                $correos['DCV']['cuerpo']=Yii::app()->reportes->distComercialVendedor($fecha);
                $correos['DCV']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Vendedor al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Terminos de Pago
            if(isset($_POST['lista']['DCTP']))
            {
                $correos['DCTP']['asunto']="DC Termino Pago al ".str_replace("-","",$fecha);
                $correos['DCTP']['cuerpo']=Yii::app()->reportes->distComercialTerminoPago($fecha);
                $correos['DCTP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Termino Pago al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Monetizable
            if(isset($_POST['lista']['DCM']))
            {
                $correos['DCM']['asunto']="DC Monetizable al ".str_replace("-","",$fecha);
                $correos['DCM']['cuerpo']=Yii::app()->reportes->distComercialMonetizable($fecha);
                $correos['DCM']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Monetizable al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Compañia
            if(isset($_POST['lista']['DCCom']))
            {
                $correos['DCCom']['asunto']="DC Compania al ".str_replace("-","",$fecha);
                $correos['DCCom']['cuerpo']=Yii::app()->reportes->distComercialCompany($fecha);
                $correos['DCCom']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Compania al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Carrier
            if(isset($_POST['lista']['DCCarrier']))
            {
                $correos['DCCarrier']['asunto']="DC Carrier al ".str_replace("-","",$fecha);
                $correos['DCCarrier']['cuerpo']=Yii::app()->reportes->distComercialCarrier($fecha);
                $correos['DCCarrier']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Unidad de Produccion
            if(isset($_POST['lista']['DCUP']))
            {
                $correos['DCUP']['asunto']="DC Unidad de Produccion al ".str_replace("-","",$fecha);
                $correos['DCUP']['cuerpo']=Yii::app()->reportes->distComercialUnidadProduccion($fecha);
                $correos['DCUP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_POST['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha).".xlsx";
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($fecha,$nombre);
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha).".xlsx";
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
        $this->letra=Log::preliminar($_GET['fecha']);
        $fecha=null;
        $archivos=array();
        if(isset($_GET['fecha']))
        {
            $fecha=(string)$_GET['fecha'];
            if(isset($_GET['lista']['compraventa']))
            {
                $archivos['compraventa']['nombre']="RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$fecha);
                $archivos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($fecha);
            }
            if(isset($_GET['lista']['perdidas']))
            {
                $archivos['perdidas']['nombre']="RENOC".$this->letra." Perdidas al ".str_replace("-","",$fecha);
                $archivos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($fecha);
            }
            if(isset($_GET['lista']['AIR']))
            {
                $archivos['altoImpactoRetail']['nombre']="RENOC".$this->letra." Alto Impacto RETAIL (+1$) al ".str_replace("-","",$fecha);
                $archivos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
            }
            if(isset($_GET['lista']['AI10']))
            {
                $archivos['altoImpacto']['nombre']="RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $archivos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
            } 
            if(isset($_GET['lista']['AI10V']))
            {
                $archivos['altoImpactoVendedor']['nombre']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha);
                $archivos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($fecha);
            } 
            if(isset($_GET['lista']['PN']))
            {
                $archivos['posicionNeta']['nombre']="RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$fecha);
                $archivos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
            }
            if(isset($_GET['lista']['PNV']))
            {
                $archivos['posicionNetaVendedor']['nombre']="RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$fecha);
                $archivos['posicionNetaVendedor']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($fecha);
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_GET['lista']['ADI']))
            {
                $archivos['ADI']['nombre']="RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$fecha);
                $archivos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($fecha,false);
            }
            //Arbol de Trafico Destino External
            if(isset($_GET['lista']['ADE']))
            {
                $archivos['ADE']['nombre']="RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$fecha);
                $archivos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($fecha,true);
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_GET['lista']['ACI']))
            {
                $archivos['ACI']['nombre']="RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$fecha);
                $archivos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,true,false);
            }
            //Arbol de Trafico Clientes External
            if(isset($_GET['lista']['ACE']))
            {
                $archivos['ACE']['nombre']="RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$fecha);
                $archivos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,true,true);
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_GET['lista']['API']))
            {
                $archivos['API']['nombre']="RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$fecha);
                $archivos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,false,false);
            }
            //Arbol de Trafico Proveedores External
            if(isset($_GET['lista']['APE']))
            {
                $archivos['APE']['nombre']="RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$fecha);
                $archivos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,false,true);
            }
            //Distribucion Comercial por Vendedor
            if(isset($_GET['lista']['DCV']))
            {
                $archivos['DCV']['nombre']="DC Vendedor al ".str_replace("-","",$fecha);
                $archivos['DCV']['cuerpo']=Yii::app()->reportes->distComercialVendedor($fecha);
            }
            //Distribucion Comercial por Terminos de Pago
            if(isset($_GET['lista']['DCTP']))
            {
                $archivos['DCTP']['nombre']="DC Termino Pago al ".str_replace("-","",$fecha);
                $archivos['DCTP']['cuerpo']=Yii::app()->reportes->distComercialTerminoPago($fecha);
            }
            //Distribucion Comercial por Monetizable
            if(isset($_GET['lista']['DCM']))
            {
                $archivos['DCM']['nombre']="DC Monetizable al ".str_replace("-","",$fecha);
                $archivos['DCM']['cuerpo']=Yii::app()->reportes->distComercialMonetizable($fecha);
            }
            //Distribucion Comercial por Compañia
            if(isset($_GET['lista']['DCCom']))
            {
                $archivos['DCCom']['nombre']="DC Compania al ".str_replace("-","",$fecha);
                $archivos['DCCom']['cuerpo']=Yii::app()->reportes->distComercialCompany($fecha);
            }
            //Distribucion Comercial por Carrier
            if(isset($_GET['lista']['DCCarrier']))
            {
                $archivos['DCCarrier']['nombre']="DC Carrier al ".str_replace("-","",$fecha);
                $archivos['DCCarrier']['cuerpo']=Yii::app()->reportes->distComercialCarrier($fecha);
            }
            //Distribucion Comercial por Unidad de Produccion
            if(isset($_GET['lista']['DCUP']))
            {
                $archivos['DCUP']['nombre']="DC Unidad de Produccion al ".str_replace("-","",$fecha);
                $archivos['DCUP']['cuerpo']=Yii::app()->reportes->distComercialUnidadProduccion($fecha);
            }
            if(isset($_GET['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha).".xlsx";
                $archivos['Ev']['nombre']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha);
                $archivos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($fecha,$nombre);
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
        $this->letra=Log::preliminar($_POST['fecha']);
        $fecha=null;
        $correos=null;
        $user="renoc@etelix.com";
        if(isset($_POST['fecha']))
        {
            $fecha=(string)$_POST['fecha'];
            //Ranking Compra Venta
            if(isset($_POST['lista']['compraventa']))
            {
                $correos['compraventa']['asunto']="RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$fecha);
                $correos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($fecha);
                $correos['compraventa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Ranking CompraVenta al ".str_replace("-","",$fecha).".xls";
            }
            //Perdidas
            if(isset($_POST['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC".$this->letra." Perdidas al ".str_replace("-","",$fecha);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($fecha);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Perdidas al ".str_replace("-","",$fecha).".xls";
            }
            // Alto Impacto Retail
            if(isset($_POST['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$fecha);
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
                $correos['altoImpactoRetail']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$fecha).".xls";
            }
            //Alto Impacto +10$
            if(isset($_POST['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
                $correos['altoImpacto']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) al ".str_replace("-","",$fecha).".xls";
            }
            //Alto Impacto +10$ por Vendedor
            if(isset($_POST['lista']['AI10V']))
            {
                $correos['altoImpactoVendedor']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha);
                $correos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($fecha);
                $correos['altoImpactoVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha).".xls";
            }
            //Posicion Neta
            if(isset($_POST['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$fecha);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
                $correos['posicionNeta']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta al ".str_replace("-","",$fecha).".xls";
            }
            //Posicion Neta por vendedor
            if(isset($_POST['lista']['PNV']))
            {
                $correos['posicionNetaVendedor']['asunto']="RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$fecha);
                $correos['posicionNetaVendedor']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($fecha);
                $correos['posicionNetaVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Posicion Neta por Vendedor al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_POST['lista']['ADI']))
            {
                $correos['ADI']['asunto']="RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$fecha);
                $correos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($fecha,false);
                $correos['ADI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos Internal al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Destino External
            if(isset($_POST['lista']['ADE']))
            {
                $correos['ADE']['asunto']="RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$fecha);
                $correos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($fecha,true);
                $correos['ADE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Destinos External al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_POST['lista']['ACI']))
            {
                $correos['ACI']['asunto']="RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$fecha);
                $correos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,true,false);
                $correos['ACI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes Internal al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Clientes External
            if(isset($_POST['lista']['ACE']))
            {
                $correos['ACE']['asunto']="RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$fecha);
                $correos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,true,true);
                $correos['ACE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_POST['lista']['API']))
            {
                $correos['API']['asunto']="RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$fecha);
                $correos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,false,false);
                $correos['API']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores Internal al ".str_replace("-","",$fecha).".xls";
            }
            //Arbol de Trafico Proveedores External
            if(isset($_POST['lista']['APE']))
            {
                $correos['APE']['asunto']="RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$fecha);
                $correos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($fecha,false,true);
                $correos['APE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Proveedores External al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Vendedor
            if(isset($_POST['lista']['DCV']))
            {
                $correos['DCV']['asunto']="DC Vendedor al ".str_replace("-","",$fecha);
                $correos['DCV']['cuerpo']=Yii::app()->reportes->distComercialVendedor($fecha);
                $correos['DCV']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Vendedor al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Terminos de Pago
            if(isset($_POST['lista']['DCTP']))
            {
                $correos['DCTP']['asunto']="DC Termino Pago al ".str_replace("-","",$fecha);
                $correos['DCTP']['cuerpo']=Yii::app()->reportes->distComercialTerminoPago($fecha);
                $correos['DCTP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Termino Pago al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Monetizable
            if(isset($_POST['lista']['DCM']))
            {
                $correos['DCM']['asunto']="DC Monetizable al ".str_replace("-","",$fecha);
                $correos['DCM']['cuerpo']=Yii::app()->reportes->distComercialMonetizable($fecha);
                $correos['DCM']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Monetizable al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Compañia
            if(isset($_POST['lista']['DCCom']))
            {
                $correos['DCCom']['asunto']="DC Compania al ".str_replace("-","",$fecha);
                $correos['DCCom']['cuerpo']=Yii::app()->reportes->distComercialCompany($fecha);
                $correos['DCCom']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Compania al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Carrier
            if(isset($_POST['lista']['DCCarrier']))
            {
                $correos['DCCarrier']['asunto']="DC Carrier al ".str_replace("-","",$fecha);
                $correos['DCCarrier']['cuerpo']=Yii::app()->reportes->distComercialCarrier($fecha);
                $correos['DCCarrier']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$fecha).".xls";
            }
            //Distribucion Comercial por Unidad de Produccion
            if(isset($_POST['lista']['DCUP']))
            {
                $correos['DCUP']['asunto']="DC Unidad de Produccion al ".str_replace("-","",$fecha);
                $correos['DCUP']['cuerpo']=Yii::app()->reportes->distComercialUnidadProduccion($fecha);
                $correos['DCUP']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."DC Carrier al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_POST['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha).".xlsx";
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($fecha,$nombre);
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Evolucion al ".str_replace("-","",$fecha).".xlsx";
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
                $lista=array('CarlosBuona@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta'],$lista);
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



