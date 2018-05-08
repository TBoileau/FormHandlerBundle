<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 08/05/18
 * Time: 01:48
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FooType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('bar');
    }
}
