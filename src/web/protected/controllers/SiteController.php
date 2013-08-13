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
        if(isset($_POST['ContactForm']))
        {
            $model->attributes=$_POST['ContactForm'];
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
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
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
    * las especificaciones seran recibidas desde el array $_POST
    */
    public function actionMail()
    {
        $fecha=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_POST['fecha']))
        {
            $fecha=(string)$_POST['fecha'];
            if(isset($_POST['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="Alto Impacto Retail (+1$) de día ".$fecha;
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
            }
            if(isset($_POST['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="Alto Impacto (+10$) del día ".$fecha;
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
            } 
            if(isset($_POST['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="Posicion Neta del día ".$fecha;
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
            }
            if(isset($_POST['lista']['otros']))
            {
                $correos['otros']['asunto']="Otros de día ".$fecha;
                $correos['otros']['cuerpo']="Prueba de Otros";
            }
            if(isset($_POST['lista']['otros2']))
            {
                $correos['otros2']['asunto']="Otros 2 de día ".$fecha;
                $correos['otros2']['cuerpo']="Prueba de Otros 2";
            } 
        }
        foreach($correos as $key => $correo)
        {
            Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto']);
        }
        echo "Su correo fue enviado exitosamente :)";
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
                $archivos['altoImpactoRetail']['nombre']="Alto Impacto Retail (+1$) de día ".$fecha;
                $archivos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
            }
            if(isset($_GET['lista']['AI10']))
            {
                $archivos['altoImpacto']['nombre']="Alto Impacto (+10$) del día ".$fecha;
                $archivos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
            } 
            if(isset($_GET['lista']['PN']))
            {
                $archivos['posicionNeta']['nombre']="Posicion Neta del día ".$fecha;
                $archivos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
            }
            if(isset($_GET['lista']['otros']))
            {
                $archivos['otros']['nombre']="Otros de día ".$fecha;
                $archivos['otros']['cuerpo']="Prueba de Otros";
            }
            if(isset($_GET['lista']['otros2']))
            {
                $archivos['otros2']['nombre']="Otros 2 de día ".$fecha;
                $archivos['otros2']['cuerpo']="Prueba de Otros 2";
            } 
        }
        foreach($archivos as $key => $archivo)
        {
            $this->genExcel($archivo['nombre'],$archivo['cuerpo']);
        }
    }
    
    public function genExcel($nombre,$cuerpo)
    {
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$nombre.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $cuerpo;
    }
}
?>



