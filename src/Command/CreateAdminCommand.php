<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * A console command that creates an admin user and stores him in the database.
 *
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php bin/console app:create-admin
 *
 * To output detailed information, increase the command verbosity:
 *
 *     $ php bin/console app:create-admin -vv
 */
#[AsCommand(
    name: 'app:create-admin',
    description: 'Create a new admin user with roles ROLE_USER and ROLE_ADMIN.',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command creates a new admin user and store him in the database.')
            ->addArgument('username', InputArgument::OPTIONAL, 'User pseudo')
            ->addArgument('email', InputArgument::OPTIONAL, 'User email')
            ->addArgument('password', InputArgument::OPTIONAL, 'User password')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('password');
        $email = $input->getArgument('email');

        if (null !== $username && null !== $plainPassword && null !== $email) {
            return;
        }

        if (null !== $username) {
            $io->text(' > <info>Username</info>: '.$username);
        } else {
            $username = $io->ask('Username', 'Admin');
            $input->setArgument('username', $username);
        }

        if (null !== $email) {
            $io->text(' > <info>Email</info>: '.$email);
        } else {
            $email = $io->ask('Email', null);
            $input->setArgument('email', $email);
        }

        if (null !== $plainPassword) {
            $io->text(' > <info>Password</info>: ******');
        } else {
            $password = $io->askHidden('Password (your type will be hidden)');
            $input->setArgument('password', $password);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('password');
        $email = $input->getArgument('email');

        $user = (new User())->setUsername((string) $username)->setEmail((string) $email);
        $errors = $this->validator->validate($user);

        if (count($errors) > 0 || strlen($plainPassword) < 6) {
            throw new \Exception((string) $errors);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword)
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setIsVerified(true)
            ->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('A new administrator user was successfully created: %s (%s)', $user->getUsername(), $user->getEmail()));

        return Command::SUCCESS;
    }
}
