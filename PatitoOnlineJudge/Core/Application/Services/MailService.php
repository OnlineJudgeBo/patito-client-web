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
        $this->mailer->AddAddress($email);
        $this->mailer->Subject = "Juez Virtual";
        $this->mailer->Body = $this->buildMessage($encodePassword);
        $this->mailer->Send();
    }

    private function buildMessage($encodePassword)
    {
        $userId = $_SESSION['user_id'];
        $sms = "Hola,<br>
        Solicitaste restablecer tu contraseña. A continuación se te enviará el nombre de usuario de tu cuenta:
        <table>
            <tr>
                <td>Nombre de usuario:</td>
                <td>{$userId}</td>
            </tr>
        </table>
        <br>
        Para restablecer tu contraseña, haz clic en el siguiente enlace: <a href='https://jv.umsa.bo/oj/updatepassword.php?token=" . urlencode($encodePassword) . "'>Cambiar contraseña</a>
        <br> o copia el siguiente enlace y pégalo en tu navegador: 
        https://jv.umsa.bo/oj/updatepassword.php?token=" . urlencode($encodePassword);
        return $sms;
    }
}
