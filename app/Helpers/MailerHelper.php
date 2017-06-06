<?php
namespace App\Helpers;

/**
* 邮件辅助类
* 基于：swiftmailer/swiftmailer
* 文档：http://swiftmailer.org/docs/introduction.html
*/
class MailerHelper
{
    public static function sendMail($subject, $content, $to = ['847713844@qq.com' => 'sheng'])
    {
        $mailer = self::createMailer();

        // Create a message
        $message = (new \Swift_Message($subject))
            ->setFrom(['iiinsomnia@163.com' => 'IIInsomnia'])
            ->setTo($to)
            ->setBody($content, 'text/html', 'UTF-8');

        // Send the message
        $mailer->send($message);
    }

    public static function sendErrorMail($error, $to = ['847713844@qq.com' => 'sheng'])
    {
        $mailer = self::createMailer();

        $content = sprintf("<b>Message：</b>%s<br/><br/><b>File：</b>%s<br/><br/><b>Line：</b>%s",
                $error->getMessage(),
                $error->getFile(),
                $error->getLine()
            );

        // Create a message
        $message = (new \Swift_Message('PHP Exception'))
            ->setFrom(['iiinsomnia@163.com' => 'IIInsomnia'])
            ->setTo($to)
            ->setBody($content, 'text/html', 'UTF-8');

        // Send the message
        $mailer->send($message);
    }

    protected static function createMailer()
    {
        // Create the Transport
        $transport = (new \Swift_SmtpTransport('smtp.163.com', 25))
            ->setUsername('iiinsomnia@163.com')
            ->setPassword('wwwsh0779');

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        return $mailer;
    }
}
?>