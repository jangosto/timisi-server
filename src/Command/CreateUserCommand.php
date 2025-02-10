<?php

declare(strict_types=1);

namespace Infrastructure\Command;

use Domain\Model\User\User;
use Domain\Model\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    protected static $defaultDescription = 'Crea un nuevo usuario en el sistema.';

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $validator = Validation::createValidator();

        // Paso 1: Solicitar email
        $email = null;
        while (true) {
            $email = $io->ask('Email del nuevo usuario');

            $errors = $validator->validate($email, [
                new NotBlank(),
                new Email(),
            ]);

            if (\count($errors) > 0) {
                $io->error('Por favor, introduce un email válido.');
            } else {
                break;
            }
        }

        // Paso 2: Solicitar contraseña
        $password = $io->askHidden('Introduce una contraseña para el usuario');

        // Paso 3: Seleccionar roles
        $roles = $this->userRepository->getRoles();
        $assignedRoles = [];

        while (true) {
            $io->writeln('Roles disponibles:');

            foreach ($roles as $index => $role) {
                $io->writeln(\sprintf('  [%d] %s', $index + 1, $role));
            }

            $roleIndex = $io->ask('Elige un rol para añadir al usuario (deja en blanco para finalizar)', null);

            if (empty($roleIndex)) {
                break;
            }

            if (!is_numeric($roleIndex) || !isset($roles[$roleIndex - 1])) {
                $io->error('Selecciona un número válido de la lista.');
                continue;
            }

            $assignedRoles[] = $roles[$roleIndex - 1];
        }

        // Crear el usuario
        $user = new User();
        $user->username = $email;
        $user->password = $password;
        $user->roles = $assignedRoles;

        $this->userRepository->create($user);

        $io->success(\sprintf('Usuario %s creado con éxito.', $email));

        return Command::SUCCESS;
    }
}
