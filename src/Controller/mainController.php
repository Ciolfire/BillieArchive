<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class mainController extends AbstractController
{
    /**
     * @Route("/{_locale<%supported_locales%>?%default_locale%}/", name="index")
     */
    public function index()
    {
      return $this->render('index.html.twig', [
      ]);
    }
}