<?php

namespace App\Controller;

use App\BusinessLogic\BooksKeeping\Users\UsersManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(UsersManagerService $usersManagerService, Request $request)
    {
        $limit    = $request->request->get('limit', 5);
        $page     = $request->request->get('page', 0);
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_OK)
            ->setData($usersManagerService->getAllUsers($page, $limit));
        return $response;
    }
}
