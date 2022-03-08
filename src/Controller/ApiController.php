<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function apiGeocoder(HttpClientInterface $httpClient): array
    {
       $rue = '1ruedumoulin';

       $cp = '08240';

       $ville = 'BoultAuxBois';

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

        // $geometry = in_array('features',$data);

        $features = $data['features'];

        $filter = $features[0];

        $geometry = $filter['geometry'];

        $coordinates = $geometry['coordinates'];

        dd($coordinates);
    }
}
