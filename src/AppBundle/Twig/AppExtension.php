<?php

namespace AppBundle\Twig;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;

class AppExtension extends \Twig_Extension
{
    private $requestStack;
    private $router;

    public function __construct(RequestStack $requestStack, Router $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('avatar', [$this, 'avatarFunction']),
        ];
    }

    public function getName()
    {
        return 'app_extension';
    }

    public function avatarFunction(User $user)
    {
        static $avatars = [];

        if (!array_key_exists($user->getId(), $avatars)) {
            $avatars[$user->getId()] = $user->getAvatar() ? $this->router->generate('avatar', ['file'=>$user->getAvatar()]) :
                $this->requestStack->getCurrentRequest()->getBasepath().'/adminlte/img/user1-128x128.jpg';
        }

        return $avatars[$user->getId()];
    }
}
