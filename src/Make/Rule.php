<?php
/**
 * Created by PhpStorm.
 * User: qucaixian
 * Date: 2019/9/21
 * Time: 13:59
 */

namespace App\Make;


use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Validator\Validation;

class Rule extends AbstractMaker
{
    public static function getCommandName (): string
    {
        return 'make:rule';
    }

    public function configureCommand (Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new validator and constraint class')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the validator class (e.g. <fg=yellow>EnabledValidator</>)')
            ->setHelp(file_get_contents(__DIR__.'/../../vendor/symfony/maker-bundle/src/Resources/help/MakeValidator.txt'));
    }

    public function generate (InputInterface $input, ConsoleStyle $io, Generator $generator)
    {

        $validatorClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Rule\\',
            'Validator'
        );

        $constraintFullClassName = Str::removeSuffix($validatorClassNameDetails->getFullName(), 'Validator');

        $generator->generateClass(
            $validatorClassNameDetails->getFullName(),
            'validator/Validator.tpl.php',
            [
                'constraint_class_name' => $constraintFullClassName,
            ]
        );

        $generator->generateClass(
            $constraintFullClassName,
            'validator/Constraint.tpl.php',
            []
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your new constraint & validators and add your logic.',
            'Find the documentation at <fg=yellow>http://symfony.com/doc/current/validation/custom_constraint.html</>',
        ]);
    }

    public function configureDependencies (DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            Validation::class,
            'validator'
        );
    }
}