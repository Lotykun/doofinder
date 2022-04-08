<?php

namespace App\Tests;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class BookFormTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();

        // or if you also need to read constraints from annotations
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'Titulo de testeo Form',
            'author' => 'Autor de testeo Form',
            'editorial' => 'Editorial de testeo Form',
            'description' => 'Description de testeo Form',
        ];

        $model = new Book();
        $form = $this->factory->create(BookType::class, $model);

        $expected = new Book();
        $expected->setTitle($formData['title']);
        $expected->setAuthor($formData['author']);
        $expected->setEditorial($formData['editorial']);
        $expected->setDescription($formData['description']);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
    }

    public function testCustomFormView()
    {
        $formData = new Book();
        $formData->setTitle('titulo de prueba');
        $view = $this->factory->create(BookType::class, $formData)->createView();

        $this->assertArrayHasKey('value', $view->vars);
        $this->assertSame('titulo de prueba', $view->vars['value']->getTitle());
    }
}
