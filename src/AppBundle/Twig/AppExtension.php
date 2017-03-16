<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
        ];
    }

    public function getName()
    {
        return 'app_extension';
    }
}
