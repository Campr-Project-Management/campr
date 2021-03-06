<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\User;

/**
 * Create a new user object (firstname, lastname and role are optionally).
 *
 * Command usage: tss:app:user-create email@email.com password username firstname lastname ROLE_ADMIN
 */
class UserCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tss:app:user-create')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the new user')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user')
            ->addOption('first_name', null, InputArgument::OPTIONAL, 'The first name of the new user', 'John')
            ->addOption('last_name', null, InputArgument::OPTIONAL, 'The last name of the new user', 'Doe')
            ->addOption('role', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Roles to set to the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $username = $input->getArgument('username') ?? $email;
        $firstName = $input->getOption('first_name');
        $lastName = $input->getOption('last_name');
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
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRoles($roles);
        $user->setIsEnabled(true);
        $user->setIsSuspended(false);
        $user->setActivatedAt(new \DateTime());

        $em->persist($user);
        $em->flush();

        $output->writeln('<info>User created.</info>');
    }
}
