<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 08/05/18
 * Time: 01:49
 */

namespace App\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\FormHandlerBundle\Handler;
use App\Form\FooType;

class FooHandler extends Handler
{
    public function onSuccess(): Response
    {
        return new RedirectResponse($this->router->generate("index"));
    }

    public function getView(): string
    {
        return "index.html.twig";
    }

    public static function getFormType(): string
    {
        return FooType::class;
    }

}