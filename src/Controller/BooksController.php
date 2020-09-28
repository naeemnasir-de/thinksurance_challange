<?php

namespace App\Controller;

use App\BusinessLogic\BooksKeeping\Books\BooksManagerService;
use App\BusinessLogic\BooksKeeping\Users\UsersManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BooksController extends AbstractController
{
    /**
     * @Route("/books", name="books")
     */
    public function index(BooksManagerService $booksManagerService, Request $request)
    {
        $limit    = $request->request->get('limit', 5);
        $page     = $request->request->get('page', 0);
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_OK)
            ->setData($booksManagerService->getAllBooks($page, $limit));
        return $response;
    }


    /**
     * @Route("/books/purchase", name="books")
     *

     */
    public function purchaseBook(BooksManagerService $booksManagerService, Request $request)
    {
        $response = new JsonResponse();
        $userId   = $request->get(UsersManagerService::USER_ID);
        $bookId   = $request->get(BooksManagerService::BOOK_ID);
        if ($userId === null || $bookId === null) {
            $response->setStatusCode(Response::HTTP_FORBIDDEN)
                ->setData('UserId and BookId are required');
            return $response;
        }
        $result = $booksManagerService->purchaseBook($bookId, $userId);
        if ($result === true) {
            $response->setStatusCode(Response::HTTP_OK)
                ->setData('book purchased');
        }
        else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setData($booksManagerService->getLastErrorMessage());
        }


        return $response;
    }
}
