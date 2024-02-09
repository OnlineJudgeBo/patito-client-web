<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IMailService;
use PHPMailer;

require(__DIR__ . "/../../../Infraestructure/Phpmailer/class.phpmailer.php");
require(__DIR__ . "/../../../Infraestructure/Phpmailer/class.smtp.php");

class MailService implements IMailService
{
    private $mailer;

    public function __construct()
    {

        $this->mailer = new PHPMailer();
    }

    public function sendRecoveryPasswordEmail($email, $encodePassword)
    {
        $this->mailer->IsSMTP();
        $this->mailer->IsHTML(true);
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = "ssl";
        $this->mailer->Host = "smtp.gmail.com";
        $this->mailer->Port = 465;
        $this->mailer->Username = "acm.icpc.umsa@gmail.com";
        $this->mailer->Password = "qmrtolnhjblhijau";
        $this->mailer->AddAddress("starsaminf@gmail.com");
        $this->mailer->Subject = "Juez Virtual";
        $this->mailer->Body = "Tu contraseña de reinicio es : <a href='http://localhost:8080/updatepassword.php?token=" . urlencode($encodePassword) . "'>Cambiar clave</a>";
        $this->mailer->Send();
    }
}
