<?php

namespace App\Tests\Mailer;


use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;

class MailerTest extends TestCase
{
    public function testConfirmationEmail()
    {
        $user = new User();
        $user->setEmail('test@test.com');

        $switfMailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        $switfMailer->expects($this->once())->method('send')->with($this->callback(function ($subject) {
            $stringMessage = (string)$subject;
            return strpos($stringMessage, 'From: john@doe.com') !== false;
        }));
        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())->method('render')->with('email/registration.html.twig', [
            'user' => $user
        ]);

        $mailer = new Mailer($switfMailer, $twig, 'john@doe.com');
        $mailer->sendConfirmationEmail($user);
    }
}