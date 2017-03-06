<?php

namespace AppBundle\Command;

use AppBundle\Entity\Admin;
use AppBundle\Entity\User;
use AppBundle\Utils\Config;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create new admin user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $container = $this->getContainer();

        $oldUser = $container->get('doctrine')->getRepository('AppBundle:User')->findByUsername($username);
        if ($oldUser) {
            $output->writeln('Username exists, please choose another one');
            return;
        }

        $encoder = $container->get('security.password_encoder');

        $user = new User;
        $user
            ->setUsername($username)
            ->setPassword($encoder->encodePassword($user, $password))
            ->setRoles(['ROLE_ADMIN'])
        ;

        $admin = new Admin;
        $admin
            ->setName($username)
            ->setGender(Config::GENDER_MALE)
            ->setBirthplace('Brebes')
            ->setUser($user)
        ;

        $em = $container->get('doctrine.orm.entity_manager');

        $em->persist($user);
        $em->persist($admin);
        $em->flush();

        $output->writeln('User has been created');
    }

}
