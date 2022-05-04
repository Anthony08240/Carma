<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TextExplicatifController extends AbstractController
{
    /**
     * @Route("/text_explicatif", name="text_explicatif")
     */
    public function index(): Response
    {
        return $this->render('text_explicatif/index.html.twig', [
            'controller_name' => 'TextExplicatifController',
        ]);
    }
}
