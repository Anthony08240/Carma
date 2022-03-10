<?php

namespace App\Controller;

use App\Entity\Point;
use App\Form\PointType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PointController extends AbstractController
{
    /**
     * @Route("/ajout-point", name="ajout-point")
     */
    public function index(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, HttpClientInterface $httpClient): Response
    {
        
        $point = new Point();

        // api adresse datagouv

        $user = $this->getUser();

        $rue = $user->getAdresse();

        $cp = $user->getCodepostal();

        $ville = $user->getVille();

       $response = $httpClient->request('GET', 'https://api-adresse.data.gouv.fr/search/?q='. $rue . '+' . $cp . '+' . $ville, [
           'headers' => [
               'Accept' => 'application/json',
               'Content-Type' => 'application/json'
           ],
           'query' => [
               'format' => 'json',
               'inc' => 'geometry',
               'limit' => '1'
           ]
        ]);

        $data = $response->toArray();

        $features = $data['features'];

        $filter = $features[0];

        $geometry = $filter['geometry'];

        $coordinates = $geometry['coordinates'];

        $latitude = $coordinates[1];

        $longitude = $coordinates[0];

        $arr = array(
            'latitude' => $latitude, 
            'longitude' => $longitude
        );

        // set user

        $point->setPoint($arr);
        $point->setIdUser($this->getUser());
        
        // create form

        $form = $this->createForm(PointType::class, $point);
        $form->handleRequest($request);
        
        // if form is valid and submited
        
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
