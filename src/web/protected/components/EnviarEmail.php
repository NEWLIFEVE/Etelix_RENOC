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

    public function enviar($html, $user, $asunto)
    {

        if (isset($html) && isset($html)) {
            $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
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
            $message = $html;
            $mailer->Body = $message;
            //$mailer->AddAttachment($ruta, $asunto);
            $mailer->Send();
        }
    }

}

?>