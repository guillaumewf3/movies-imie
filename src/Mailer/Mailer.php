<?php

namespace App\Mailer;

use Symfony\Bridge\Doctrine\RegistryInterface;

class Mailer
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        //var_dump($doctrine);
    }

    public function send()
    {
        //$this->doctrine;
        //code pour envoyer le mail
    }

}