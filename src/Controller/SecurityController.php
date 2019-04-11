<?php
/**
 * Created by PhpStorm.
 * User: gplon
 * Date: 2019-04-05
 * Time: 22:37
 */

namespace App\Controller;


use App\Entity\MicroPost;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * SecurityController constructor.
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/user/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return new Response($this->twig->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]
        ));
    }

    /**
     * @Route("/user/logout", name="security_logout")
     */
    public function logout()
    {

    }
}