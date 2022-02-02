<?php

namespace App\Controller;

use App\Entity\Point;
use App\Form\Point1Type;
use App\Repository\PointRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/crud/point")
 */
class CrudPointController extends AbstractController
{
    /**
     * @Route("/", name="crud_point_index", methods={"GET"})
     */
    public function index(PointRepository $pointRepository): Response
    {
        return $this->render('crud_point/index.html.twig', [
            'points' => $pointRepository->findBy(['id_user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/new", name="crud_point_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $point = new Point();
        $form = $this->createForm(Point1Type::class, $point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($point);
            $entityManager->flush();

            return $this->redirectToRoute('crud_point_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_point/new.html.twig', [
            'point' => $point,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_point_show", methods={"GET"})
     */
    public function show(Point $point): Response
    {
        return $this->render('crud_point/show.html.twig', [
            'point' => $point,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_point_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Point $point, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Point1Type::class, $point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_point_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_point/edit.html.twig', [
            'point' => $point,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_point_delete", methods={"POST"})
     */
    public function delete(Request $request, Point $point, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$point->getId(), $request->request->get('_token'))) {
            $entityManager->remove($point);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_point_index', [], Response::HTTP_SEE_OTHER);
    }
}
