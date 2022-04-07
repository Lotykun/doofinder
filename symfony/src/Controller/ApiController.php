<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Validator\ApiRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/", name="app_api_book_index", methods={"GET"})
     *
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->json($bookRepository->findAll());
    }

    /**
     * @Route("/{id}", name="app_api_book_show", methods={"GET"})
     *
     * @param Book $book
     * @return Response
     */
    public function show(Book $book): Response
    {
        return $this->json($book);
    }

    /**
     * @Route("/new", name="app_api_book_new", methods={"POST"})
     *
     * @param Request $request
     * @param BookRepository $bookRepository
     * @param SluggerInterface $slugger
     * @param ApiRequestValidator $apiRequestValidator
     * @return Response
     */
    public function new(Request $request, BookRepository $bookRepository, SluggerInterface $slugger, ApiRequestValidator $apiRequestValidator): Response
    {
        try {
            $params = $request->request->all();
            //$params = json_decode($request->getContent(), true);
            if ($apiRequestValidator->validateCreateBook($params)) {
                $book = new Book();
                $imageFile = $request->files->get('image');
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        $imageFile->move($this->getParameter('images_directory'), $newFilename);
                    } catch (FileException $e) {
                        throw new BadRequestHttpException('"file" is required');
                    }
                    $book->setImage($newFilename);
                }
                $book->setTitle($params['title']);
                $book->setAuthor($params['author']);
                $book->setEditorial($params['editorial']);
                if (isset($params['description']) && !empty($params['description'])){
                    $book->setDescription($params['description']);
                }
                $bookRepository->add($book);
            } else {
                throw new BadRequestHttpException($apiRequestValidator->getMessage());
            }
        } catch (BadRequestHttpException $e) {
            return $this->json($e->getMessage(),401);
        } catch (\Exception $e){
            return $this->json($e->getMessage(),500);
        }
        return $this->json($book);
    }

    /**
     * @Route("/{id}/edit", name="app_api_book_edit", methods={"POST"})
     *
     * @param Request $request
     * @param Book $book
     * @param BookRepository $bookRepository
     * @param SluggerInterface $slugger
     * @param ApiRequestValidator $apiRequestValidator
     * @return Response
     */
    public function edit(Request $request, Book $book, BookRepository $bookRepository, SluggerInterface $slugger, ApiRequestValidator $apiRequestValidator): Response
    {
        try {
            $params = $request->request->all();
            if (isset($params['title']) && !empty($params['title'])){
                $book->setTitle($params['title']);
            }
            if (isset($params['author']) && !empty($params['author'])){
                $book->setTitle($params['author']);
            }
            if (isset($params['editorial']) && !empty($params['editorial'])){
                $book->setTitle($params['editorial']);
            }
            if (isset($params['description']) && !empty($params['description'])){
                $book->setDescription($params['description']);
            }
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($this->getParameter('images_directory'), $newFilename);
                } catch (FileException $e) {
                    throw new BadRequestHttpException('"file" is required');
                }
                $book->setImage($newFilename);
            }
            $bookRepository->add($book);
        } catch (BadRequestHttpException $e) {
            return $this->json($e->getMessage(),401);
        } catch (\Exception $e){
            return $this->json($e->getMessage(),500);
        }
        return $this->json($book);
    }

    /**
     * @Route("/{id}/delete", name="app_api_book_delete", methods={"POST"})
     *
     * @param Request $request
     * @param Book $book
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function delete(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        try {
            $bookRepository->remove($book);
        } catch (\Exception $e){
            return $this->json($e->getMessage(),500);
        }
        return $this->json("Book Deleted!");
    }
}
