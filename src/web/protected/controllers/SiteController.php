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

    /**
     * Action encargada de envuiar por mail el tipo de reporte seleccionado,
     * las especificaciones seran recibidas desde el array $_GET
     * @access public
     */
    public function actionMail()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_POST['startDate']);
        $startDate=$endingDate=$carrier=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_POST['startDate']))
        {
            $startDate=(string)$_POST['startDate'];
            if(isset($_POST['endingDate'])) $endingDate=$_POST['endingDate'];
            if(isset($_POST['carrier'])) $carrier=$_POST['carrier'];
            //Ranking Compra Venta
            if(isset($_POST['lista']['compraventa']))
            {
                $correos['compraventa']['asunto']="RENOC".$this->letra." Ranking CompraVenta".self::reportTitle($startDate,$endingDate);
                $correos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($startDate,$endingDate);
                $correos['compraventa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Ranking CompraVenta".$correos['compraventa']['asunto'].".xls";
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
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate,$endingDate);
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
            //Distribucion Comercial
            if(isset($_POST['lista']['DC']))
            {
                $correos['DC']['nombre']="RENOC".$this->letra." Distribucion Comercial.xlsx";
                $correos['DC']['asunto']="RENOC".$this->letra." Distribucion Comercial";
                $correos['DC']['cuerpo']=Yii::app()->reportes->DistribucionComercial($correos['DC']['nombre']);
                $correos['DC']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['DC']['nombre'];
            }
            if(isset($_POST['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$nombre);
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
            }
            if(isset($_POST['lista']['calidad']))
            {
                $correos['calidad']['asunto']="RENOC Calidad ".$carrier." desde ".str_replace("-","",$startDate).$endTitle;
                $correos['calidad']['cuerpo']=Yii::app()->reportes->Calidad($startDate,$endingDate,Carrier::model()->find("name=:nombre",array(':nombre'=>$carrier))->id);
                $correos['calidad']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Calidad ".$carrier." desde ".str_replace("-","",$startDate).$endTitle.".xls";
            }
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        { 
            //Esto es para que no descargue los archivos cuando se genere uno de estos reportes
            if(stripos($correo['asunto'],"Evolucion")==false && stripos($correo['asunto'],"Comercial")==false)
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
        $startDate=$endingDate=$carrier=null;
        $archivos=array();
        if(isset($_GET['startDate']))
        {
            $startDate=(string)$_GET['startDate'];
            if(isset($_GET['endingDate'])) $endingDate=$_GET['endingDate'];
            if(isset($_GET['carrier'])) $carrier=$_GET['carrier'];
            if(isset($_GET['lista']['compraventa']))
            {
                $archivos['compraventa']['nombre']="RENOC".$this->letra." Ranking CompraVenta".self::reportTitle($startDate,$endingDate);
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
            //Distribucion Comercial
            if(isset($_GET['lista']['DC']))
            {
                $archivos['DC']['nombre']="RENOC".$this->letra." Distribucion Comercial.xlsx";
                $archivos['DC']['cuerpo']=Yii::app()->reportes->DistribucionComercial($archivos['DC']['nombre']);
            }
            if(isset($_GET['lista']['Ev']))
            {
                $archivos['Ev']['nombre']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
                $archivos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$archivos['Ev']['nombre']);
            }
            if(isset($_GET['lista']['calidad']))
            {
                $archivos['calidad']['nombre']="RENOC Calidad ".$carrier." desde ".str_replace("-","",$startDate).$endTitle;
                $archivos['calidad']['cuerpo']=Yii::app()->reportes->Calidad($startDate,$endingDate,Carrier::model()->find("name=:nombre",array(':nombre'=>$carrier))->id);
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
        $startDate=$endingDate=$carrier=null;
        $endTitle="";
        if(isset($_POST['endingDate']) && $_POST['endingDate']!=null)
        {
            $endTitle=" al ".str_replace("-","",$_POST['endingDate']);
        }
        $correos=null;
        $user="renoc@etelix.com";
        if(isset($_POST['startDate']))
        {
            $startDate=(string)$_POST['startDate'];
            if(isset($_POST['endingDate'])) $endingDate=$_POST['endingDate'];
            if(isset($_POST['carrier'])) $carrier=$_POST['carrier'];
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
            //Distribucion Comercial
            if(isset($_POST['lista']['DC']))
            {
                $correos['DC']['nombre']="RENOC".$this->letra." Distribucion Comercial.xlsx";
                $correos['DC']['asunto']="RENOC Distribucion Comercial";
                $correos['DC']['cuerpo']=Yii::app()->reportes->DistribucionComercial($correos['DC']['nombre']);
                $correos['DC']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['DC']['nombre'];
            }
            if(isset($_POST['lista']['Ev']))
            {
                $nombre="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$nombre);
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Evolucion al ".str_replace("-","",$startDate).".xlsx";
            }
            if(isset($_POST['lista']['calidad']))
            {
                $correos['calidad']['asunto']="RENOC Calidad ".$carrier." desde ".str_replace("-","",$startDate).$endTitle;
                $correos['calidad']['cuerpo']=Yii::app()->reportes->Calidad($startDate,$endingDate,Carrier::model()->find("name=:nombre",array(':nombre'=>$carrier))->id);
                $correos['calidad']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Calidad ".$carrier." desde ".str_replace("-","",$startDate).$endTitle.".xls";
            }
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        {
            //esto es para evitar que cuando sea alguno de estos reportes no descargue el archivo
            if(stripos($correo['asunto'],"Evolucion")==false && stripos($correo['asunto'],"Comercial")==false)
            {
                $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            }
            if(stripos($correo['asunto'], "RETAIL"))
            {
                $lista=array('CarlosBuona@etelix.com','sig@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta'],$lista);
            }
            elseif (stripos($correo['asunto'], "Calidad"))
            {
                $userDif="ceo@etelix.com";
                $lista=array('alvaroquitana@etelix.com','eykiss@etelix.com','txadmin@netuno.net','sig@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $userDif, $correo['asunto'],$correo['ruta'],$lista);
            }
            else
            {
                $lista=array('sig@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta'],$lista);
            }
        }
        echo "Mensaje Enviado";
    }

    /**
     * @access public
     */
    public function genExcel($nombre,$html,$salida=true)
    {
        if(stripos($nombre,"Evolucion") || stripos($nombre,"Comercial"))
        {
            header("Location: /adjuntos/{$nombre}");
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

    /**
     * Metodo encargado de ajustar nombre de archivo dependiendo de las fechas,
     * si se le pasa una sola fecha, retornará algo como: al $fecha
     * si se le pasan dos fechas retornará algo como: desde $fecha hasta $fecha
     * la las fechas completan principio y fin de un mismo mes retornará algom como: al $mes
     * @access private
     * @static
     * @param date $start fecha incial
     * @param date $end fecha fin
     * @return string con el texto apropiado
     */
    private static function reportTitle($start,$end=null)
    {
        if($end==null)
        {
            return " al ".str_replace("-","",$start);
        }
        else
        {
            return Reportes::reportTitle($start,$end);
        }
    }
}
?>



