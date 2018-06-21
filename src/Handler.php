<?php

namespace TBoileau\FormHandlerBundle;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * Class Handler
 * @package TBoileau\FormHandlerBundle
 * @author Thomas Boileau <t-boileau@email.com>
 */
abstract class Handler implements HandlerInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

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
     * @var mixed|null
     */
    protected $data;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $extraData;

    /**
     * @param FormFactoryInterface $formFactory
     *
     * @return self
     */
    public function setFormFactory(FormFactoryInterface $formFactory): self
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * @param RouterInterface $router
     *
     * @return self
     */
    public function setRouter(RouterInterface $router): self
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @param Environment $twig
     *
     * @return self
     */
    public function setTwig(Environment $twig): self
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return self
     */
    public function setRequestStack(RequestStack $requestStack): self
    {
        $this->requestStack = $requestStack;

        return $this;
    }

    /**
     * @param FlashBagInterface $flashBag
     *
     * @return self
     */
    public function setFlashBag(FlashBagInterface $flashBag): self
    {
        $this->flashBag = $flashBag;

        return $this;
    }

    /**
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onRender(): Response
    {
        return new Response($this->twig->render($this->getView(), ["form" => $this->form->createView()] + $this->extraData));
    }

    /**
     * Before create form
     */
    public function beforeCreate()
    {

    }

    /**
     * Error
     */
    public function onError()
    {

    }

    /**
     * @return FormInterface
     */
    public function onCreate(): FormInterface
    {
        $this->form = $this->formFactory->create($this->getFormType(), $this->data, $this->getFormOptions());
        return $this->form;
    }

    /**
     * @param null $data
     * @param array $extraData
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle($data = null, $extraData = [])
    {
        $this->data = $data;
        $this->extraData = $extraData;
        $this->beforeCreate();
        $this->onCreate()->handleRequest($this->requestStack->getCurrentRequest());
        if ($this->form->isSubmitted() and $this->form->isValid()) {
            return $this->onSuccess();
        }elseif($this->form->isSubmitted() and !$this->form->isValid()) {
            $this->onError();
        }

        return $this->onRender();
    }

    /**
     * @return array
     */
    public function getFormOptions(): array
    {
        return [];
    }
}