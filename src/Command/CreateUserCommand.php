<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user.',
)]
class CreateUserCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        string $name = null
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
            ->addArgument('role', InputArgument::REQUIRED, 'role');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->enterUsername($input, $output);
        $this->enterPassword($input, $output);
        $this->enterRole($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('password');
        $role = $input->getArgument('role');

        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user
           ->setUsername($username)
           ->setPassword($hashedPassword)
           ->setRoles([$role]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('User created with success.');

        return Command::SUCCESS;
    }

    private function enterUsername(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new Question('USER USERNAME :');

        $question->setValidator(function ($answer) {
            if (!is_string($answer)) {
                throw new \RuntimeException(
                    "You must provide an username"
                );
            }

            if (!$this->isAvailableUsername($answer)) {
                throw new \RuntimeException(
                    "This username is already used by one user"
                );
            }

            return $answer;
        });
        $question->setMaxAttempts(2);
        $username = $helper->ask($input, $output, $question);

        $input->setArgument('username', $username);
    }

    private function enterPassword(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new Question('USER PASSWORD :');

        $question->setValidator(function ($answer) {
            if (!is_string($answer)) {
                throw new \RuntimeException(
                    "You must provide a password"
                );
            }
            $passwordRegex = '/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*[-+_!@#$%^&*., ?]).{8,}$/';
            if (!preg_match($passwordRegex, $answer)) {
                throw new \RuntimeException(
                    "Password must contain MIN 8 characters with one uppercase, one number and one special character"
                );
            }

            return $answer;
        });
        $question->setMaxAttempts(2);

        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $question);
        $input->setArgument('password', $password);
    }

    private function enterRole(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'SELECT USER ROLE',
            [
                'ROLE_USER',
                'ROLE_ADMIN'
            ],
            'ROLE_USER'
        );
        $question->setErrorMessage('INVALID USER ROLE');
        $role = $helper->ask($input, $output, $question);
        $input->setArgument('role', $role);
    }

    private function isAvailableUsername(string $username): bool
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => $username]);
        return is_null($user);
    }
}
