<?php

namespace App\Controller;

use App\Entity\Point;
use App\Form\PointType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PointController extends AbstractController
{
    /**
     * @Route("/ajout-point", name="ajout-point")
     */
    public function index(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        
        $point = new Point();
        
        $geoloc = $request->query->all();
        $location = array_splice($geoloc, 0);

        
        $point->setPoint($location);
        $point->setIdUser($this->getUser());
        
        $form = $this->createForm(PointType::class, $point);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('img')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('img_upload'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents

                $point->setImg($newFilename);
            }

            $manager->persist($point);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('point/index.html.twig', [
            'formPoint' => $form->createView(),
        ]);
    }
}
