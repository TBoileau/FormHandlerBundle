<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 21/06/18
 * Time: 23:42
 */

namespace TBoileau\FormHandlerBundle;


use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{

    /**
     * @return string
     */
    public function getView(): string;

    /**
     * @return Response
     */
    public function onSuccess(): Response;

    /**
     * @return string
     */
    public static function getFormType(): string;
}