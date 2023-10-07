<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

class ContactNotification extends AbstractController
{
    private $mailer;

    private $renderer;

    public function __construct(MailerInterface $mailer, Environment $renderer){

        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {

        $body = $this->renderer->render('emails/contact.html.twig', [
            'contact' => $contact
        ]);

        $email = (new Email())
            ->from('noreply@gojoagency.fr')
            ->to('contact@gojoagency.fr')
            ->replyTo($contact->getEmail())
            ->subject('Agence : ' . $contact->getProperty()->getTitle());
        
        $email->html($body);

        $this->mailer->send($email);
       
    }
}
