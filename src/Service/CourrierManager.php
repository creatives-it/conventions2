<?php
// source https://symfony.com/doc/current/service_container.html
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class CourrierManager
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyOfCourrier(): bool
    {
        $courrier = "";

        $email = (new Email())
            ->from('admin@example.com')
            ->to('yassineelarabi@gmail.com')
            ->subject('Nouveau courrier!')
            ->text('Un courrier vient de vous être envoyé: '.$courrier);

        $this->mailer->send($email);

        // ...

        return true;
    }
}