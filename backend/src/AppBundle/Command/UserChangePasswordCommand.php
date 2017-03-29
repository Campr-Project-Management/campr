<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Change password  and role (optionally) for specific user.
 *
 * Command usage: tss:app:user-change-password email@email.com new_password ROLE_ADMIN
 */
class UserChangePasswordCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tss:app:user-change-password')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password to set to the user')
            ->addOption('role', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Roles to set to the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $roles = $input->getOption('role');

        $em = $this->getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln(sprintf(
                '<error>Unable find user with email "%s".</error>',
                $email
            ));

            return -1;
        }

        $user->setPlainPassword($password);

        if ($roles) {
            $user->setRoles($roles);
        }

        $em->persist($user);
        $em->flush();

        $output->writeln('<info>User updated.</info>');
    }
}
