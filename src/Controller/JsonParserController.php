<?php

namespace App\Controller;

use App\BusinessLogic\FilesImporter\JsonFilesImporterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class JsonParserController extends AbstractController
{
    /**
     * @Route("/json/parser", name="json_parser")
     */
    public function index(JsonFilesImporterService $jsonFilesImporter)
    {
        $response = new JsonResponse();
        $data = $jsonFilesImporter->importFiles();
        if ($data === null){
            $response->setStatusCode(500);
            $response->setData($jsonFilesImporter->getLastErrorMessage());
            return $response;
        }

        $response->setStatusCode(200);
        $response->setData($data->render());

        return $response;
    }
}
