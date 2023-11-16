<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    public function __construct(private readonly WeatherUtil $weatherUtil)
    {}

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter] bool $twig = false,
    ): JsonResponse|Response
    {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($format == 'json'){
            if($twig) {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }
            return $this->json([
                'city'=>$city,
                'country'=>$country,
                'measurements'=>array_map(fn(Measurement $m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getCelsius(),
                    'fahrenheit'=> $m->getFahrenheit()
                ], $measurements)
                ,
            ]);
        }else if ($format == 'csv'){
            if($twig){
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }
            $csvData = implode(',', ['Date', 'Celsius', 'City', 'Country']);

            foreach ($measurements as $measurement) {
                $csvData .= "\n".implode(',', [
                        $measurement->getDate()->format('Y-m-d'),
                        $measurement->getCelsius(),
                        $measurement->getFahrenheit(),
                        $city,
                        $country,
                    ]);
            }
            $response = new Response($csvData."\n");

            return $response;
        }else{
            return $this->json(['message'=>'wrong format selected']);
        }
    }
}
