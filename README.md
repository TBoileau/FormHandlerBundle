# TBoileau/FormHandlerBundle

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require <package-name>
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