<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PointRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    

    /**
     * @Route("/carte", name="home")
     */
    public function index(PointRepository $pointRepository, SerializerInterface $serializer, CategoryRepository $categoryRepository): Response
    {

        $data = $pointRepository->findAll();
        $categorys = $categoryRepository->findAll(); 

        // $data = json_encode($points);

        // $jsonContent = $serializer->serialize($points, 'json');

        $points = $serializer->serialize($data, 'json', ['groups' => 'map']);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'points' => $points,
            'categorys' => $categorys
        ]);
    }
}
