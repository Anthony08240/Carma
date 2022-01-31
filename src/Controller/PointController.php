<?php

namespace App\Controller;

use App\Entity\Point;
use App\Form\PointType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PointController extends AbstractController
{
    /**
     * @Route("/ajout-point", name="ajout-point")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        
        $point = new Point();
        
        $geoloc = $request->query->all();
        array_splice($geoloc, 0, 1);

        
        $category = $request->query->get('cat');

        $point->setCategorie($category);
        $point->setPoint($geoloc);
        $point->setIdUser($this->getUser());
        
        $form = $this->createForm(PointType::class, $point);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            

            $manager->persist($point);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('point/index.html.twig', [
            'formPoint' => $form->createView(),
        ]);
    }
}
