<?php

namespace App\EventListener;


use App\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * UserSubscriber constructor.
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $environment)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $body = $this->environment->render('email/registration.html.twig', [
            'user' => $event->getUser()
        ]);
        $message = (new \Swift_Message())
            ->setSubject('Welcome to the micro post app!')
            ->setFrom('micropost@micropost.com')
            ->setTo($event->getUser()->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}