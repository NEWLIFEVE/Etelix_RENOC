<?php

class EnviarEmail extends CApplicationComponent {
    //***************************************************************************
    // Initialization
    //***************************************************************************

    /**
     * Init method for the application component mode.
     */
    public function init() {
        
    }

    public function enviar($html, $user, $asunto, $ruta)
    {
        if (isset($html) && isset($user))
        {
            $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
            $mailer = new PHPMailer();
            $mailer->IsSMTP();
            $mailer->Host = 'smtp.gmail.com';
            $mailer->Port = '587';
            //$mailer->SMTPDebug = 2;
            $mailer->SMTPSecure = 'tls';
            $mailer->Username = 'sinca.test@gmail.com';
            $mailer->SMTPAuth = true;
            $mailer->Password = "sincatest";
            $mailer->IsSMTP();
            $mailer->IsHTML(true);
            $mailer->From = 'sinca.test@gmail.com';
            $mailer->AddReplyTo('sinca.test@gmail.com');
            $mailer->AddAddress($user);
            $mailer->FromName = 'RENOC';
            $mailer->CharSet = 'UTF-8';
            $mailer->Subject = Yii::t('', $asunto);
            $mailer->AddAttachment($ruta); //Archivo adjunto
            $message = $html;
            $mailer->Body = $message;
            if($mailer->Send())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}

?>
