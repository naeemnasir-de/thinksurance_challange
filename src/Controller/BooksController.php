<?php

namespace App\Controller;

use App\BusinessLogic\BooksKeeping\Books\BooksManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class BooksController extends AbstractController
{
    /**
     * @Route("/books", name="books")
     */
    public function index(BooksManagerService $booksManagerService, Request $request)
    {
        $limit = $request->request->get('limit', 5);
        $page = $request->request->get('page', 0);
        $response = new JsonResponse();
        $response->setStatusCode(200);
        $response->setData($booksManagerService->getAllBooks($page, $limit));
        return $response;
    }



    /**
     * @Route("/books/purchase", name="books")
     *
    
     */
    public function purchaseBook(BooksManagerService $booksManagerService, Request $request)
    {
        $response = new JsonResponse();
        $userId = $request->get('user_id');
        $bookId = $request->get('book_id');
        if($userId === null || $bookId === null){
            $response->setStatusCode(403);
            $response->setData('UserId and BookId are required');
            return $response;
        }
        $result = $booksManagerService->purchaseBook($bookId, $userId);
        if ($result === true){
            $response->setStatusCode(200);
            $response->setData('book purchased');
        }
        else{
            $response->setStatusCode(500);
            $response->setData($booksManagerService->getLastErrorMessage());
        }


        return $response;
    }
}
