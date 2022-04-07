<?php

namespace App\Tests;

use App\Entity\Book;
use App\Repository\BookRepository;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class WebTest extends WebTestCase
{
    private function populate(){
        $books = array();
        for ($i = 1; $i <= 10; $i++) {
            $bookRepository = static::getContainer()->get(BookRepository::class);
            $book = new Book();
            $book->setTitle('Title Test ' . $i);
            $book->setAuthor('Author Test ' . $i);
            $book->setEditorial('Editorial Test ' . $i);
            $book->setDescription('Description Test ' . $i);
            $book->setImage('historia-interminable-624b1de4e328b.jpg');
            $bookRepository->add($book);
            $books[] = $book;
        }
        return $books;
    }

    public function testListBooks()
    {
        $client = static::createClient();
        $books = $this->populate();
        $crawler = $client->request('GET', '/book/');
        $this->assertResponseIsSuccessful();
        $this->assertCount(10, $crawler->filter('.mb-5'));
    }
}
