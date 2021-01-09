<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{



    /**
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {

        return $this->render('home/home.html.twig');
    }

    /**
     * @Route("/inscription", name="home_register_choice")
     */
    public function registerChoice()
    {
        return $this->render('home/choice.html.twig');
    }

    /**
     * @Route("/mentions", name="home_mentions")
     */
    public function mentionsLegales()
    {
        return $this->render('home/mentions.html.twig');
    }
}
