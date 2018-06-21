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
     * @var string
     */
    private $projectDir;

    /**
     * HandlerMaker constructor.
     * @param FileManager $fileManager
     * @param string $projectDir
     */
    public function __construct(FileManager $fileManager, string $projectDir)
    {
        $this->projectDir = $projectDir;
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
//            ->addArgument('auto-edit-services', InputArgument::REQUIRED, 'Do you want auto edit <fg=yellow>config/services.yaml</>')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (null === $input->getArgument('form-type')) {
            $argument = $command->getDefinition()->getArgument('form-type');
            $formFinder = $this->fileManager->createFinder('src/Form/')->depth('<1')->name("*.php");
            $classes = [];
            foreach ($formFinder as $item) {
                if (!$item->getRelativePathname()) {
                    continue;
                }
                $classes[] = str_replace('/', '\\', str_replace('.php', '', $item->getRelativePathname()));
            }
            $question = new Question($argument->getDescription());
            $question->setValidator(function ($answer) use ($classes) {
                return Validator::existsOrNull($answer, $classes);
            });
            $question->setAutocompleterValues($classes);
            $question->setMaxAttempts(3);
            $input->setArgument('form-type', $io->askQuestion($question));
        }
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

//        if($input->getArgument("auto-edit-services")) {
//            $yaml = Yaml::parseFile($this->projectDir.DIRECTORY_SEPARATOR."config/services.yaml");
//
//            $yaml["services"][$handlerClassNameDetails->getFullName()] = [
//                "parent" => Handler::class,
//                "autowire" => true,
//                "autoconfigure" => false,
//                "public" => false
//            ];
//
//            file_put_contents($this->projectDir.DIRECTORY_SEPARATOR."config/services.yaml", Yaml::dump($yaml, 3));
//        }

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }

}