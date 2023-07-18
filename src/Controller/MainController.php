<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }
    public function login(): Response
    {
        return $this->render('main/index.html.twig');
    }
    public function register(): Response
    {
        return $this->render('main/index.html.twig');
    }
}
