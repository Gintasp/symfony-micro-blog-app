<?php

namespace App\Twig;


use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('like', function ($obj) {
                return $obj instanceof LikeNotification;
            })
        ];
    }

    public function getGlobals()
    {
        return [];
    }
}