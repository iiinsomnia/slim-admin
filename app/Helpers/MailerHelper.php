<?php
namespace App\Helpers;

/**
* 邮件辅助类
* 基于：swiftmailer/swiftmailer
* 文档：http://swiftmailer.org/docs/introduction.html
*/
class MailerHelper
{
    // 发送邮件
    public static function sendMail($subject, $content, $to = [])
    {
        $mailer = self::createMailer();

        // Create a message
        $message = (new \Swift_Message($subject))
            ->setFrom(env('MAIL_USERNAME', 'demo@example.com'), env('MAIL_TITLE', ''))
            ->setTo($to)
            ->setBody($content, 'text/html', 'UTF-8');

        // Send the message
        $mailer->send($message);
    }

    // 发送错误日志邮件
    public static function sendErrorMail($e)
    {
        $mailer = self::createMailer();

        $trace = str_replace('#', '<br/>#', $e->getTraceAsString());

        $content = sprintf("<table><tbody><tr><td><b>Message</b></td><td>%s</td></tr><tr><td><b>File</b></td><td>%s</td></tr><tr><td><b>Line</b></td><td>%s</td></tr><tr><td><b>Trace</b></td><td>%s</td></tr></tbody></table>",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $trace
        );

        $to = explode(',', env('ERROR_MAIL_TO', ''));

        // Create a message
        $message = (new \Swift_Message('PHP Exception'))
            ->setFrom(env('MAIL_USERNAME', 'demo@example.com'), env('MAIL_TITLE', ''))
            ->setTo($to)
            ->setBody($content, 'text/html', 'UTF-8');

        // Send the message
        $mailer->send($message);
    }

    public static function createMailer()
    {
        // Create the Transport
        $transport = (new \Swift_SmtpTransport(env('MAIL_HOST', 'smtp.exmail.qq.com'), env('MAIL_PORT', 25)))
            ->setUsername(env('MAIL_USERNAME', 'demo@example.com'))
            ->setPassword(env('MAIL_PASSWORD', ''));

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        return $mailer;
    }
}
?>