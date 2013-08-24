<?php
/**
* @var $this SiteController
*/
class SiteController extends Controller
{
    /**
    * Declares class-based actions.
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
    */
    public function actionRutinarios()
    {
        $this->render('rutinarios');
    }

    /**
    * Renderiza vista personalizados
    */
    public function actionPersonalizados()
    {
        $this->render('personalizados');
    }

    /**
    * renderiza vista especificos
    */
    public function actionEspecificos()
    {
        $this->render('especificos');
    }

    /**
    *
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
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
    * Action encargada de envuiar por mail el tipo de reporte seleccionado,
    * las especificaciones seran recibidas desde el array $_GET
    */
    public function actionMail()
    {
        $fecha=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_GET['fecha']))
        {
            $fecha=(string)$_GET['fecha'];
            if(isset($_GET['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="RENOC Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$fecha);
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
                $correos['altoImpactoRetail']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_GET['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
                $correos['altoImpacto']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Alto Impacto (+10$) al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_GET['lista']['AI10V']))
            {
                $correos['altoImpactoVendedor']['asunto']="RENOC Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha);
                $correos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($fecha);
                $correos['altoImpactoVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha).".xls";
            } 
            if(isset($_GET['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC Posicion Neta al ".str_replace("-","",$fecha);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
                $correos['posicionNeta']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Posicion Neta al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_GET['lista']['DC']))
            {
                $correos['distribucionComercial']['asunto']="Distribucion Comercial al ".str_replace("-","",$fecha);
                $correos['distribucionComercial']['cuerpo']=Yii::app()->reportes->distComercial($fecha);
                $correos['distribucionComercial']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."Distribucion Comercial al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_GET['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC Perdidas al ".str_replace("-","",$fecha);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($fecha);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Perdidas al ".str_replace("-","",$fecha).".xls";
            }
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        { 
            $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta']);
        }
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
        echo "Mensaje Enviado";
    }
    public function actionExcel()
    {
        $fecha=null;
        $archivos=null;
        if(isset($_GET['fecha']))
        {
            $fecha=(string)$_GET['fecha'];
            if(isset($_GET['lista']['AIR']))
            {
                $archivos['altoImpactoRetail']['nombre']="RENOC Alto Impacto RETAIL (+1$) al ".str_replace("-","",$fecha);
                $archivos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
            }
            if(isset($_GET['lista']['AI10']))
            {
                $archivos['altoImpacto']['nombre']="RENOC Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $archivos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
            } 
            if(isset($_GET['lista']['AI10V']))
            {
                $archivos['altoImpactoVendedor']['nombre']="RENOC Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha);
                $archivos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($fecha);
            } 
            if(isset($_GET['lista']['PN']))
            {
                $archivos['posicionNeta']['nombre']="RENOC Posicion Neta al ".str_replace("-","",$fecha);
                $archivos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
            }
            if(isset($_GET['lista']['DC']))
            {
                $archivos['distribucionComercial']['nombre']="Distribucion Comercial al ".str_replace("-","",$fecha);
                $archivos['distribucionComercial']['cuerpo']=Yii::app()->reportes->distComercial($fecha);
            }
            if(isset($_GET['lista']['perdidas']))
            {
                $archivos['perdidas']['nombre']="RENOC Perdidas al ".str_replace("-","",$fecha);
                $archivos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($fecha);
            }
        }
        foreach($archivos as $key => $archivo)
        {
            $this->genExcel($archivo['nombre'],$archivo['cuerpo']);
        }
    }
    /**
    * Action encargada de envuiar por mail el tipo de reporte seleccionado,
    * las especificaciones seran recibidas desde el array $_GET
    */
    public function actionMaillista()
    {
        $fecha=null;
        $correos=null;
        $user="renoc@etelix.com";
        if(isset($_POST['fecha']))
        {
            $fecha=(string)$_POST['fecha'];
            if(isset($_POST['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="RENOC Alto Impacto RETAIL (+1$) al ".str_replace("-","",$fecha);
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
                $correos['altoImpactoRetail']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Alto Impacto RETAIL (+1$) al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_POST['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
                $correos['altoImpacto']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Alto Impacto (+10$) al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_POST['lista']['AI10V']))
            {
                $correos['altoImpactoVendedor']['asunto']="RENOC Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha);
                $correos['altoImpactoVendedor']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($fecha);
                $correos['altoImpactoVendedor']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Alto Impacto (+10$) por Vendedor al ".str_replace("-","",$fecha).".xls";
            } 
            if(isset($_POST['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC Posicion Neta al ".str_replace("-","",$fecha);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
                $correos['posicionNeta']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Posicion Neta al ".str_replace("-","",$fecha).".xls";
            }
            if(isset($_POST['lista']['DC']))
            {
                $correos['distribucionComercial']['asunto']="Distribucion Comercial al ".str_replace("-","",$fecha);
                $correos['distribucionComercial']['cuerpo']=Yii::app()->reportes->distComercial($fecha);
                $correos['distribucionComercial']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."Distribucion Comercial al ".str_replace("-","",$fecha);
            }
            if(isset($_POST['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC Perdidas al ".str_replace("-","",$fecha);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($fecha);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC Perdidas al ".str_replace("-","",$fecha).".xls";
            }
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        { 
            $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta']);
        }
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
        echo "Mensaje Enviado";
    }
    public function genExcel($nombre,$html,$salida=true)
    {
        if($salida)
        {
            header("Content-type: application/excel; name='excel'");
            header("Content-Disposition: filename=$nombre.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $html;
        }
        else
        {
            $fp=@fopen("adjuntos/$nombre.xls","w+");
            $cuerpo="
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset='utf-8'>
                    <meta http-equiv='Content-Type' content='application/excel charset=utf-8'>
                </head>
                <body>
            ";
            $cuerpo.=$html;
            $cuerpo.="</body>
            </html>";
            fwrite($fp,$cuerpo);
        }
    }
    public function actionPruebaruta()
    {
        $asunto='perro';
    $name=($asunto.'.xls');
    
    $funciona='adjuntos/'.$asunto.'.xls';
    
    echo $name;
    echo '<br>';
    echo $funciona;
    }
}
?>



