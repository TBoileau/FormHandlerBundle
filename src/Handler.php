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
abstract class Handler
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
     * EventSubscriber constructor.
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
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onRender(): Response
    {
        return new Response($this->twig->render($this->view, ["form" => $this->form->createView()] + $this->extraData));
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
        $this->form = $this->formFactory->create($this->getFormType(), $this->data, $this->options);
        return $this->form;
    }

    /**
     * @param null $data
     * @param array $options
     * @param string $view
     * @param array $extraData
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle($data = null, $options = [], $view = '', $extraData = [])
    {
        $this->data = $data;
        $this->options = $options;
        $this->view = $view;
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
     * @return Response
     */
    public abstract function onSuccess(): Response;

    /**
     * @return string
     */
    public abstract static function getFormType(): string;
}