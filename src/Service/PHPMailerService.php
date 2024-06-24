<?php
// src/Service/PHPMailerService.php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twig\Environment;

class PHPMailerService
{
    private $mailer;
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->mailer = new PHPMailer(true);
        $this->twig = $twig;
    }

    public function sendEmail(string $to, string $subject, string $template, array $context = []): void
    {
        try {
            // Debug: Check environment variables
            // error_log('SMTP_HOST: ' . $_ENV['SMTP_HOST']);
            // error_log('SMTP_USERNAME: ' . $_ENV['SMTP_USERNAME']);
            // error_log('SMTP_PASSWORD: ' . $_ENV['SMTP_PASSWORD']);
            
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['SMTP_HOST'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['SMTP_USERNAME'];
            $this->mailer->Password = $_ENV['SMTP_PASSWORD'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = 587;

            // // UTF-8 settings
            $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
            $this->mailer->Encoding = PHPMailer::ENCODING_BASE64;

                        
            // Recipients
            $this->mailer->setFrom('evan.moreau@etik.com', 'Le Phare De L\'Ame');
            $this->mailer->addAddress($to);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $this->twig->render($template, $context);

            $this->mailer->send();
        } catch (Exception $e) {
            // Log error or handle exception
            error_log("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
        }
    }
}
