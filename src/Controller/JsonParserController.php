<?php

namespace App\Controller;

use App\BusinessLogic\FilesImporter\JsonFilesImporterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        if ($data === null) {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setData($jsonFilesImporter->getLastErrorMessage());
            return $response;
        }

        $response->setStatusCode(Response::HTTP_OK)
            ->setData($data->render());

        return $response;
    }
}
