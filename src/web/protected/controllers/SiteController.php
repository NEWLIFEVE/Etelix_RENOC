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
            if(isset($_GET['ajax']) && $_GET['ajax'] === 'login-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            // collect user input data
            if(isset($_GET['LoginForm']))
            {
                $model->attributes = $_GET['LoginForm'];
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
            }
            if(isset($_GET['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
            } 
            if(isset($_GET['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC Posicion Neta del día ".str_replace("-","",$fecha);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
            }
            if(isset($_GET['lista']['DC']))
            {
                $correos['distribucionComercial']['asunto']="Distribución Comercial al ".str_replace("-","",$fecha);
                $correos['distribucionComercial']['cuerpo']=Yii::app()->reportes->distComercial($fecha);
            }
        }
        foreach($correos as $key => $correo)
        {
            Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto']);
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
            if(isset($_GET['lista']['PN']))
            {
                $archivos['posicionNeta']['nombre']="RENOC Posicion Neta del día ".str_replace("-","",$fecha);
                $archivos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
            }
            if(isset($_GET['lista']['DC']))
            {
                $archivos['distribucionComercial']['nombre']="Distribucion Comercial al ".str_replace("-","",$fecha);
                $archivos['distribucionComercial']['cuerpo']=Yii::app()->reportes->distComercial($fecha);
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
        $user="angelo08121987@gmail.com";
        if(isset($_GET['fecha']))
        {
            $fecha=(string)$_GET['fecha'];
            if(isset($_GET['lista']['AIR']))
            {
                $correos['altoImpactoRetail']['asunto']="RENOC Alto Impacto RETAIL (+1$) al  ".str_replace("-","",$fecha);
                $correos['altoImpactoRetail']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($fecha);
            }
            if(isset($_GET['lista']['AI10']))
            {
                $correos['altoImpacto']['asunto']="RENOC Alto Impacto (+10$) al ".str_replace("-","",$fecha);
                $correos['altoImpacto']['cuerpo']=Yii::app()->reportes->AltoImpacto($fecha);
            } 
            if(isset($_GET['lista']['PN']))
            {
                $correos['posicionNeta']['asunto']="RENOC Posicion Neta del día ".str_replace("-","",$fecha);
                $correos['posicionNeta']['cuerpo']=Yii::app()->reportes->posicionNeta($fecha);
            }
            if(isset($_GET['lista']['DC']))
            {
                $correos['distribucionComercial']['asunto']="Distribución Comercial al ".str_replace("-","",$fecha);
                $correos['distribucionComercial']['cuerpo']=Yii::app()->reportes->distComercial($fecha);
            }
        }
        foreach($correos as $key => $correo)
        {
            Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto']);
        }
        echo "Mensaje Enviado";
    }
    public function genExcel($nombre,$cuerpo)
    {
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$nombre.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $cuerpo;
    }
    
    
    public function actionViewTest() {
 
    // Render view and get content
    // Notice the last argument being `true` on render()
    $content = $this->render('rutinarios', array(
        'Test' => 'TestText 123',
    ), true);
 
    // Plain text content
    $plainTextContent = "This is my Plain Text Content for those with cheap emailclients ;-)\nThis is my second row of text";
 
    // Get mailer
    $SM = Yii::app()->swiftMailer;
 
    // Get config
    $mailHost = 'mail.example.com';
    $mailPort = 25; // Optional
 
    // New transport
    $Transport = $SM->smtpTransport($mailHost, $mailPort);
 
    // Mailer
    $Mailer = $SM->mailer($Transport);
 
    // New message
    $Message = $SM
        ->newMessage('My subject')
        ->setFrom(array('angelo08121987@gmail.com' => 'angelo'))
        ->setTo(array('recipient@example.com' => 'Recipient Name'))
        ->addPart($content, 'text/html')
        ->setBody($plainTextContent);
 
    // Send mail
    $result = $Mailer->send($Message);
}
    
    
    
     public function actionPrueba ()
        {
            $perrito= ((2*9)+8)/14;
        echo $perrito.'bolivares fuertes';
        $perro =4;
        $angelo='me siento mal';
        $espacio= '<br>';
        $n= 'como hago';
        $resultado= $perrito.$perro.$angelo. $espacio. $n;
        echo $resultado;
        }
  
    
    
}
?>



