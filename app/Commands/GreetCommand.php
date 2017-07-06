<?php
namespace App\Commands;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GreetCommand extends Command
{
    protected $container;

    protected $commandName = 'greet';
    protected $commandDescription = "Greets Someone";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Who do you want to greet?";

    protected $commandOptionName = "cap"; // should be specified like "app:greet John --cap"
    protected $commandOptionDescription = 'If set, it will greet in uppercase letters';

    function __construct(ContainerInterface $c)
    {
        parent::__construct();

        $this->container = $c;
    }

    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument($this->commandArgumentName, InputArgument::OPTIONAL, $this->commandArgumentDescription)
            ->addOption($this->commandOptionName, null, InputOption::VALUE_NONE, $this->commandOptionDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Hello';
        $name = $input->getArgument($this->commandArgumentName);

        if (!empty($name)) {
            $text = 'Hello '.$name;
        }

        if ($input->getOption($this->commandOptionName)) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }
}