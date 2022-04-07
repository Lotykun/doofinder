<?php

namespace App\Tests;

use App\Entity\Book;
use App\Repository\BookRepository;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class ApiTest extends WebTestCase
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

    public function testListApiBooks()
    {
        $client = static::createClient();
        $books = $this->populate();
        $client->request('GET', '/api/');
        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($content);
    }

    public function testListApiBook()
    {
        $client = static::createClient();
        $books = $this->populate();
        $client->request('GET', '/api/' . $books[0]->getId());
        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($content);
        $this->assertEquals('Title Test 1', $content['title']);
    }

    public function testListApiBookNotFound()
    {
        $client = static::createClient();
        $books = $this->populate();
        $client->request('GET', '/api/test-error');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testCreateApiBook(){
        $data = array(
            'title' => 'Titulo de pruebas 1',
            'author' => 'Author de pruebas',
            'editorial' => 'Editorial de pruebas',
            'description' => 'Descripcion de pruebas',
        );
        $client = static::createClient();
        $client->request('POST', '/api/new', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($data));
        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($content);
        $this->assertEquals('Titulo de pruebas 1', $content['title']);
    }

    public function testCreateApiBookError(){
        $data = array();
        $client = static::createClient();
        $client->request('POST', '/api/new', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($data));
        $content = json_decode($client->getResponse()->getContent(),true);
        $this->assertResponseStatusCodeSame(401);
        $this->assertEquals('Param data is required', $content);
    }

    public function testEditApiBook(){
        $data = array(
            'title' => 'Titulo de pruebas 2',
            'author' => 'Author de pruebas 2',
            'editorial' => 'Editorial de pruebas 2',
            'description' => 'Descripcion de pruebas 2',
        );
        $client = static::createClient();
        $client->request('POST', '/api/new', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($data));
        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($content);
        $this->assertEquals('Titulo de pruebas 2', $content['title']);

        $data = array(
            'title' => 'Titulo de pruebas 3',
        );
        $client->request('POST', '/api/' . $content['id'] . '/edit', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($data));
        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($content);
        $this->assertEquals('Titulo de pruebas 3', $content['title']);
    }

    public function testRemoveApiBook(){
        $data = array(
            'title' => 'Titulo de pruebas 4',
            'author' => 'Author de pruebas 4',
            'editorial' => 'Editorial de pruebas 4',
            'description' => 'Descripcion de pruebas 4',
        );
        $client = static::createClient();
        $client->request('POST', '/api/new', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($data));
        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($content);
        $this->assertEquals('Titulo de pruebas 4', $content['title']);

        $data = array();
        $client->request('POST', '/api/' . $content['id'] . '/delete', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($data));
        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('Book Deleted!', $content);
    }
}
