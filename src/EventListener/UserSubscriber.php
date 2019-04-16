<?php

namespace App\EventListener;

use App\Entity\UserPreferences;
use App\Event\UserRegisterEvent;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Tests\Authentication\Token\PreAuthenticatedTokenTest;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * UserSubscriber constructor.
     * @param Mailer $mailer
     * @param EntityManagerInterface $manager
     * @param string $defaultLocale
     */
    public function __construct(Mailer $mailer, EntityManagerInterface $manager, string $defaultLocale)
    {
        $this->mailer = $mailer;
        $this->manager = $manager;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $preferences = new UserPreferences();
        $preferences->setLocale($this->defaultLocale);

        $user = $event->getUser();
        $user->setPreferences($preferences);

        $this->manager->flush();
        $this->mailer->sendConfirmationEmail($event->getUser());
    }
}