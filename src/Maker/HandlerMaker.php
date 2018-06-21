<?php

namespace TBoileau\FormHandlerBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;
use TBoileau\FormHandlerBundle\Handler;

/**
 * Class HandlerMaker
 * @package TBoileau\FormHandlerBundle\Maker
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerMaker extends AbstractMaker
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * HandlerMaker constructor.
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getCommandName(): string
    {
        return 'make:handler';
    }

    /**
     * {@inheritdoc}
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription("Create a new form handler class")
            ->addArgument('form-handler-class', InputArgument::REQUIRED, 'Choose a name for your form handler class (e.g. <fg=yellow>FooHandler</>)')
            ->addArgument('form-type', InputArgument::REQUIRED, 'Enter the form type class attach to this handler (e.g. <fg=yellow>FooType</>)')
        ;
    }


    /**
     * {@inheritdoc}
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $handlerClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('form-handler-class'),
            'Handler\\',
            'Handler'
        );
        $generator->generateClass(
            $handlerClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/handler.tpl.php',
            [
                "form" => $input->getArgument("form-type")
            ]
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }

}