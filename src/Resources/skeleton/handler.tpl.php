<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use App\Form\<?= $form ?>;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\FormHandlerBundle\Handler;

class <?= $class_name ?> extends Handler
{
    /**
     * @return string
     */
    public static function getFormType(): string
    {
        return <?= $form ?>::class;
    }

    /**
     * @return string
     */
    public function getView(): string
    {
        return "";
    }

    /**
     * @return Response
     */
    public function onSuccess(): Response
    {

    }
}