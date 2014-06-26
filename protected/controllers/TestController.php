<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TestController extends Controller
{
    public function actionTestEmail()
    {
        //$model = new EmailMessages();
        
//        $sender = 'noreply@p5partners.net';
//        $sender_name = 'P5 Marketing Incorporated';
//        $recipient = 'oliver.candelario@gmail.com';
//        $subject = 'Testmail';
//        $message_body = 'Test only';
        
        //Mailer::sendMails($sender, $sender_name, $recipient, $subject, $message_body);
        Mailer::testmail();//test
        
        /*
        if(Yii::app()->params['log_message'])
        {
            $model->log_messages($sender, $sender_name, $recipient, $subject, $message_body);
        }
        
        Yii::app()->mailer->Host = 'smtpout.asia.secureserver.net';
        //Yii::app()->mailer->Host = 'localhost';
        Yii::app()->mailer->IsHTML(TRUE);
        //Yii::app()->mailer->IsMail();
        Yii::app()->mailer->IsSMTP();
        Yii::app()->mailer->SMTPDebug  = 2; 
        Yii::app()->mailer->SMTPAuth = true;
        Yii::app()->mailer->Port = 80;
        Yii::app()->mailer->Username = 'noreply@p5partners.net';
        Yii::app()->mailer->Password = 'Uyes$Lzqm%RO!uB';
        Yii::app()->mailer->SetFrom = $sender;
        Yii::app()->mailer->FromName = $sender_name;
        Yii::app()->mailer->AddAddress($recipient);
        Yii::app()->mailer->Subject = $subject;
        Yii::app()->mailer->Body = $message_body;
        Yii::app()->mailer->Send();
        //Yii::app()->mailer->SmtpSend();
        Yii::app()->mailer->ClearAddresses();
         * 
         */
    }
}
