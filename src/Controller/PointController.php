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

        // set utilisateur

        $point->setPoint($arr);
        $point->setIdUser($this->getUser());
        
        // creation du formulaire

        $form = $this->createForm(PointType::class, $point);
        $form->handleRequest($request);
        
        // si le formulaire est soumis et validée
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('img')->getData();

            // cette condition est nécessaire car le champ 'brochure' n'est pas obligatoire
            // donc le fichier doit être traité uniquement lorsqu'un fichier est téléchargé

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);

                // ceci est nécessaire pour inclure en toute sécurité le nom du fichier dans l'URL

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Déplacer le fichier dans le répertoire où sont stockées les fichier

                try {
                    $brochureFile->move(
                        $this->getParameter('img_upload'),
                        $newFilename
                    );
                } catch (FileException $e) {
               
                // ... gérer l'exception si quelque chose se passe pendant le téléchargement du fichier

                }

                $point->setImg($newFilename);
            }

            // enregistrer les donnée dans la base de donnée

            $manager->persist($point);
            $manager->flush();

            // redirige vers la carte

            return $this->redirectToRoute('home');
        }

        return $this->render('point/index.html.twig', [
            'formPoint' => $form->createView(),
        ]);
    }
}
