# TBoileau/FormHandlerBundle

[![Build Status](https://travis-ci.org/TBoileau/FormHandlerBundle.svg?branch=master)](https://travis-ci.org/TBoileau/FormHandlerBundle) 
[![Maintainability](https://api.codeclimate.com/v1/badges/ee1e8f121f70b75a7712/maintainability)](https://codeclimate.com/github/TBoileau/FormHandlerBundle/maintainability) 
[![Test Coverage](https://api.codeclimate.com/v1/badges/ee1e8f121f70b75a7712/test_coverage)](https://codeclimate.com/github/TBoileau/FormHandlerBundle/test_coverage) 
[![Latest Stable Version](https://poser.pugx.org/tboileau/form-handler-bundle/v/stable)](https://packagist.org/packages/tboileau/form-handler-bundle)
[![Total Downloads](https://poser.pugx.org/tboileau/form-handler-bundle/downloads)](https://packagist.org/packages/tboileau/form-handler-bundle)
[![License](https://poser.pugx.org/tboileau/form-handler-bundle/license)](https://packagist.org/packages/tboileau/form-handler-bundle)

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require tboileau/form-handler-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
<?php
// config/bundles.php

return [
    //...
    TBoileau\FormHandlerBundle\TBoileauFormHandlerBundle::class => ['all' => true],
    //...
];

```

## Create a new handler

### Step 1 : Use the maker to generate your handler

Open a command console, enter your project directory and execute the following command to generate a new handler :

```console
$ php bin/console make:handler HandlerName

  Enter the form type class attach to this handler (e.g. FooType):
  > FooType
  
  created: src/Handler/FooHandler.php

  Success !
```

Your new handler is now created :

```php
<?php
// App\Handler\FooHandler.php

namespace App\Handler;

use App\Form\FooType;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\FormHandlerBundle\Handler;

class FooHandler extends Handler
{
    /**
     * @return string
     */
    public static function getFormType(): string
    {
        return FooType::class;
    }

    /**
     * @return Response
     */
    public function onSuccess(): Response
    {

    }
}
```

### Step 2 : Example of simple handler with Doctrine

The static method `getFormType` must return the class name. And `onSuccess` is the method called after the form is submitted and valid.

```php
<?php
// App\Handler\FooHandler.php

namespace App\Handler;

use App\Form\FooType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\FormHandlerBundle\Handler;

class FooHandler extends Handler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * FooHandler constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @return string
     */
    public static function getFormType(): string
    {
        return FooType::class;
    }

    /**
     * @return Response
     */
    public function onSuccess(): Response
    {
        $foo = $this->form->getData();
        $this->entityManager->persist($foo);
        $this->flush();
        $this->flashBag->add("success", "Foo added");
        return new RedirectResponse($this->router->generate("foo_index"));
    }
}
```

## Full configuration

If you want, you could override than handler master class for fully control (see [Handler](src/Handler.php)).

## Use a form handler

You must simply add your new form handler in argument's of your controller's action, like this :

```php
<?php

namespace App\Controller;

use App\Handler\FooHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TBoileau\FormHandlerBundle\HandlerManager;

class DefaultController extends Controller
{
    /**
     * @Route("/foo/add", name="foo_add")
     * @param FooHandler $fooHandler 
     * @return Response
     */
    public function add(FooHandler $fooHandler): Response
    {
        return $fooHandler->handle(new Foo(), [], 'foo/add.html.twig');
    }
}
```