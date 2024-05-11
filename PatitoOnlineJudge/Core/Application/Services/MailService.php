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
        $this->mailer->IsSMTP();
        $this->mailer->IsHTML(true);
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = "ssl";
        $this->mailer->Host = "smtp.gmail.com";
        $this->mailer->Port = 465;
        $this->mailer->Username = "acm.icpc.umsa@gmail.com";
        $this->mailer->Subject = "Juez Virtual";
        $this->mailer->Password = "qmrtolnhjblhijau";
    }

    public function sendWelcomeEmail($email, $userId) {
        $this->mailer->AddAddress($email);
        $this->mailer->Body = $this->buildWelcomeMessage($userId);
        $this->mailer->Send();
    }

    public function sendRecoveryPasswordEmail($email, $encodePassword, $userId)
    {
        $this->mailer->AddAddress($email);
        $this->mailer->Body = $this->buildMessage($encodePassword, current($userId));
        $this->mailer->Send();
    }

    private function buildMessage($encodePassword, $user)
    {
        $sms = "<div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto;'>
            <h2 style='color: #4A90E2;'>Hola,</h2>
            <p>Solicitaste restablecer tu contraseña. A continuación se te enviará el nombre de usuario de tu cuenta:</p>
            <table style='border-collapse: collapse; width: 50%; margin: auto; background-color: #f9f9f9;'>
                <tr>
                    <td style='border: 1px solid #e1e1e1; padding: 10px; color: #555;'>Nombre de usuario:</td>
                    <td style='border: 1px solid #e1e1e1; padding: 10px; font-weight: bold; color: #555;'>{$user["user_id"]}</td>
                </tr>
            </table>
            <br>
            <p>Para restablecer tu contraseña, haz clic en el siguiente enlace: <a href='https://juezvirtual.com/updatepassword.php?token=" . urlencode($encodePassword) . "' style='color: #3278b3; text-decoration: none; font-weight: bold;'>Cambiar contraseña</a></p>
            <p>O copia el siguiente enlace y pégalo en tu navegador:</p>
            <p><a href='https://juezvirtual.com/updatepassword.php?token=" . urlencode($encodePassword) . "' style='color: #3278b3; text-decoration: none;'>https://juezvirtual.com/updatepassword.php?token=" . urlencode($encodePassword) . "</a></p>
        </div>";
        return $sms;
    }

    private function buildWelcomeMessage($user)
    {
        $sms = "<div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto;'>
            <h2 style='color: #4A90E2;'>¡Bienvenido al Juez Virtual, {$user->nick}!</h2>
            <p>Aquí están los detalles de tu cuenta para que puedas comenzar:</p>
            <table style='border-collapse: collapse; width: 50%; margin: auto; background-color: #f9f9f9;'>
                <tr>
                    <td style='border: 1px solid #e1e1e1; padding: 10px; color: #555;'>Nombre de usuario:</td>
                    <td style='border: 1px solid #e1e1e1; padding: 10px; font-weight: bold; color: #555;'>{$user->userId}</td>
                </tr>
            </table>
            <br>
            <p>Happy Hacking!!!<br>
            No dudes en contactarme si necesitas ayuda.</p>
            <p>O a través de Telegram:
                <a href='https://t.me/zsams'>@zsams</a>
            </p>
            <p>Para iniciar sesión, por favor visita: <a href='https://juezvirtual.com/login.php' style='color: #3278b3; text-decoration: none; font-weight: bold;'>Iniciar sesión</a></p>
        </div>";
        return $sms;
    }
}
