<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Console\Exception\RuntimeException;
use App\Utils\Validator;

#[AsCommand(
    name: 'app:add-user',
    description: 'Creates users and stores them in the database',
)]
class AddUserCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private Validator $validator,
        private UserRepository $users
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('firstname', InputArgument::OPTIONAL, 'The firstname of the new user')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'The lastname of the new user')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator');
    }

    /**
     * This optional method is the first one executed for a command after configure()
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        //symfony style
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute().
     * to check if some  options/arguments are missing and ask the user for those values.
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('firstname')
            && null !== $input->getArgument('lastname')
            && null !== $input->getArgument('email')
            && null !== $input->getArgument('password')) {
            return;
        }

        $this->io->title('Add User Command');
        $this->io->text([
            'If you prefer to not use this interactive wizard, write the',
            'arguments required by this command as follows:',
            '',
            ' $ symfony console app:add-user firstname lastname email country city gender password',
            '',
            'Please fill out the next arguments: ',
        ]);

        // Ask for the firstname if it's not defined
        $firstname = $input->getArgument('firstname');
        if (null !== $firstname) {
            $this->io->text(' > <info>Firstname</info>: '.$firstname);
        } else {
            $firstname = $this->io->ask('Firstname', null, [$this->validator, 'validateUsername']);
            $input->setArgument('firstname', $firstname);
        }




        // Ask for the lastname if it's not defined
        $lastname = $input->getArgument('lastname');
        if (null !== $lastname) {
            $this->io->text(' > <info>Lastname</info>: '.$lastname);
        } else {
            $lastname = $this->io->ask('Lastname', null, [$this->validator, 'validateUsername']);
            $input->setArgument('lastname', $lastname);
        }



        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: '.$email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }


        // Ask for the password if it's not defined
        /** @var string|null $password */
        $password = $input->getArgument('password');

        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: '.u('*')->repeat(u($password)->length()));
        } else {
            $password = $this->io->askHidden('Password (your type will be hidden)', [$this->validator, 'validatePassword']);
            $input->setArgument('password', $password);
        }
    }

    /**
     * This method is executed after interact() and initialize().
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        /** @var string $firstname */
        $firstname = $input->getArgument('firstname');


        /** @var string $lastname */
        $lastname = $input->getArgument('lastname');


        /** @var string $email */
        $email = $input->getArgument('email');


        /** @var string $plainPassword */
        $plainPassword = $input->getArgument('password');

        $isAdmin = $input->getOption('admin');

        // make sure to validate the user data is correct
        $this->validateUserData($firstname , $lastname , $email, $plainPassword);

        // create the user and hash its password
        $user = new User();
        $user->setFirstName($firstname);
        $user->setLastName($lastname);
        $user->setEmail($email);
        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created: %s (%s)', $isAdmin ? 'Administrator user' : 'User', $user->getFirstName(), $user->getEmail()));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

    private function validateUserData(string $firstname, string $lastname , string $email ,string $plainPassword,): void
    {
        // first check if a user with the same username already exists.
        $existingUser = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }

        // validate user data if is not this input means interactive.
        $this->validator->validateUsername($firstname);
        $this->validator->validateUsername($lastname);
        $this->validator->validateEmail($email);
        $this->validator->validatePassword($plainPassword);
        // check if a user with the same email already exists.
        $existingEmail = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }
}
