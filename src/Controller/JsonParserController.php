<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JsonParserController extends AbstractController
{
    /**
     * @Route("/json/parser", name="json_parser")
     */
    public function index()
    {
        return $this->render('json_parser/index.html.twig', [
            'controller_name' => 'JsonParserController',
        ]);
    }
}
