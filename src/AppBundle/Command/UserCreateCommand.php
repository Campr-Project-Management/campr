<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\User;

class UserCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:user-create')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the new user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the new user')
            ->addOption('role', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Roles to set to the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $roles = $input->getOption('role');

        $em = $this->getContainer()->get('doctrine')->getManager();

        if ($em->getRepository(User::class)->findOneBy(['email' => $email])) {
            $output->writeln(sprintf(
                '<error>Unable to create user with email "%s" as the email is already taken.</error>',
                $email
            ));

            return -1;
        }

        $user = new User();
        $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($user);
        $user
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($encoder->encodePassword($password, $user->getSalt()))
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setRoles($roles)
            ->setIsEnabled(true)
            ->setIsSuspended(false)
        ;

        $em->persist($user);
        $em->flush();

        $output->writeln('<info>User created.</info>');
    }
}
