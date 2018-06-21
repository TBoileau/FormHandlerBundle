<?php

namespace TBoileau\FormHandlerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use TBoileau\LifecycleBundle\Configurator;

class FormHandlerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('t_boileau.form_handler');


        foreach($taggedServices as $id => $attributes) {
            $definition = $container->getDefinition($id);

            $definition->addMethodCall("setFlashBag", [new Reference("session.flash_bag")]);
            $definition->addMethodCall("setFormFactory", [new Reference("form.factory")]);
            $definition->addMethodCall('setTwig', [new Reference("twig")]);
            $definition->addMethodCall('setRouter', [new Reference("router")]);
            $definition->addMethodCall("setRequestStack", [new Reference("request_stack")]);
        }
    }
}