<?php

namespace TBoileau\FormHandlerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TBoileau\FormHandlerBundle\DependencyInjection\Compiler\FormHandlerPass;

/**
 * Class TBoileauFormHandlerBundle
 * @package TBoileau\FormHandlerBundle
 * @author Thomas Boileau <t-boileau@email.com>
 */
class TBoileauFormHandlerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormHandlerPass());
    }

}