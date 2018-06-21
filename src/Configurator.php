<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 21/06/18
 * Time: 23:38
 */

namespace TBoileau\FormHandlerBundle;


use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class Configurator
{

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * Configurator constructor.
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param Environment $twig
     * @param RequestStack $requestStack
     * @param FlashBagInterface $flashBag
     */
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Environment $twig, RequestStack $requestStack, FlashBagInterface $flashBag)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->twig = $twig;
        $this->requestStack = $requestStack;
        $this->flashBag = $flashBag;
    }

    /**
     * @param HandlerInterface $handler
     */
    public function configure(HandlerInterface $handler)
    {
        $handler
            ->setFlashBag($this->flashBag)
            ->setFormFactory($this->formFactory)
            ->setTwig($this->twig)
            ->setRouter($this->router)
            ->setRequestStack($this->requestStack)
        ;
    }
}