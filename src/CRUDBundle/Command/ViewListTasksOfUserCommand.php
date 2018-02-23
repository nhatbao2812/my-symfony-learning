<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/22/18
 * Time: 11:49 AM
 */
namespace CRUDBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ViewListTasksOfUserCommand extends Command
{
    protected function configure()
    {
        // ...
        $this->setName('app:view-list')
            ->setDescription('View list to do tasks of user')
            ->setHelp('This command is used to view list to do tasks of user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}