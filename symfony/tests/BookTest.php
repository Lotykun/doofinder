<?php

namespace App\Tests;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookTest extends KernelTestCase
{
    public function testBookInstance(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = new Book();
        $book->setTitle('Title Test');
        $book->setAuthor('Author Test');
        $book->setEditorial('Editorial Test');
        $book->setDescription('Description Test');
        $bookRepository->add($book);
        $this->assertInstanceOf(Book::class, $book);
    }
}
