<?php

declare(strict_types=1);

namespace Infrastructure\Command;

use Domain\Model\Room\Room;
use Domain\Model\Room\RoomRepository;
use Domain\Model\Session\Session;
use Domain\Model\Session\SessionRepository;
use Domain\Model\User\User;
use Domain\Model\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InsertFixturesDataCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoomRepository $roomRepository,
        private readonly SessionRepository $sessionRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $io->section('Starting data insertion...');

            // Create Rooms
            $therapyRoom1 = new Room();
            $therapyRoom1->name = 'Sala Fisioterapia 1';
            $therapyRoom1->capacity = 1;
            $therapyRoom1Id = $this->roomRepository->create($therapyRoom1);

            $therapyRoom2 = new Room();
            $therapyRoom2->name = 'Sala Fisioterapia 2';
            $therapyRoom2->capacity = 1;
            $therapyRoom2Id = $this->roomRepository->create($therapyRoom2);

            $multiPurposeRoom = new Room();
            $multiPurposeRoom->name = 'Sala Multiusos';
            $multiPurposeRoom->capacity = 20;
            $multiPurposeRoomId = $this->roomRepository->create($multiPurposeRoom);

            // Create Users
            $managerIds = [];
            $userIds = [];

            // Create 2 managers
            $managerData = [
                [
                    'username' => 'carmen.lopez@example.com',
                    'firstName' => 'Carmen',
                    'lastName' => 'López García',
                    'idNumber' => 'M112345678',
                ],
                [
                    'username' => 'antonio.martinez@example.com',
                    'firstName' => 'Antonio',
                    'lastName' => 'Martínez Ruiz',
                    'idNumber' => 'M212345678',
                ],
            ];

            foreach ($managerData as $data) {
                $manager = new User();
                $manager->username = $data['username'];
                $manager->password = 'password';
                $manager->firstName = $data['firstName'];
                $manager->lastName = $data['lastName'];
                $manager->idNumber = $data['idNumber'];
                $manager->birthDate = new \DateTimeImmutable('1980-01-01');
                $manager->roles = ['ROLE_MANAGER'];

                $managerIds[] = $this->userRepository->create($manager);
            }

            // Create 18 regular users
            $userData = [
                ['María', 'Sánchez Pérez'], ['José', 'García Fernández'],
                ['Ana', 'Martín López'], ['David', 'Rodríguez Gómez'],
                ['Laura', 'González Torres'], ['Carlos', 'Pérez Ruiz'],
                ['Isabel', 'Fernández Díaz'], ['Miguel', 'López Santos'],
                ['Elena', 'Torres Vega'], ['Pablo', 'Ramírez Castro'],
                ['Lucía', 'Moreno Gil'], ['Juan', 'Jiménez Ortiz'],
                ['Sara', 'Díaz Morales'], ['Alberto', 'Vega Campos'],
                ['Patricia', 'Castro Serrano'], ['Roberto', 'Santos Navarro'],
                ['Cristina', 'Gil Romero'], ['Daniel', 'Ortiz Molina'],
            ];

            foreach ($userData as $i => $names) {
                $user = new User();
                $user->username = strtolower(str_replace(' ', '.', $names[0] . '.' . $names[1])) . '@example.com';
                $user->password = 'password';
                $user->firstName = $names[0];
                $user->lastName = $names[1];
                $user->idNumber = 'U' . ($i + 1) . '12345678';
                $user->birthDate = new \DateTimeImmutable('1990-01-01');
                $user->roles = ['ROLE_USER'];

                $userIds[] = $this->userRepository->create($user);
            }

            // Create 20 Physiotherapy sessions
            for ($i = 1; $i <= 20; ++$i) {
                $day = str_pad(\strval(rand(1, 28)), 2, '0', STR_PAD_LEFT);
                $hour = str_pad(\strval(rand(9, 17)), 2, '0', STR_PAD_LEFT);

                $physiotherapySession = new Session();
                $physiotherapySession->startDateTime = new \DateTimeImmutable("2025-02-{$day} {$hour}:00:00");
                $physiotherapySession->endDateTime = (new \DateTimeImmutable("2025-02-{$day} {$hour}:00:00"))->modify('+1 hour');
                $physiotherapySession->priceWithVat = 50.00;
                $physiotherapySession->vatPercentage = 21.00;
                $physiotherapySession->category = 'fisioterapia';
                $physiotherapySession->capacity = 1;
                $physiotherapySession->roomId = 0 === rand(0, 1) ? $therapyRoom1Id : $therapyRoom2Id;

                // Assign random manager and user
                $physiotherapySession->professionalIds = [$managerIds[array_rand($managerIds, 1)]];
                $physiotherapySession->clientIds = [$userIds[array_rand($userIds, 1)]];

                $this->sessionRepository->create($physiotherapySession);
            }

            // Create 3 Pilates sessions
            $pilatesSchedule = [
                '2025-02-19 10:00:00',
                '2025-02-21 16:00:00',
                '2025-02-28 11:00:00',
            ];

            foreach ($pilatesSchedule as $dateTime) {
                $pilatesSession = new Session();
                $pilatesSession->startDateTime = new \DateTimeImmutable($dateTime);
                $pilatesSession->endDateTime = (new \DateTimeImmutable($dateTime))->modify('+1 hour');
                $pilatesSession->priceWithVat = 75.00;
                $pilatesSession->vatPercentage = 21.00;
                $pilatesSession->category = 'pilates';
                $pilatesSession->capacity = 9;
                $pilatesSession->roomId = $multiPurposeRoomId;

                // Add one random manager
                $pilatesSession->professionalIds = [$managerIds[array_rand($managerIds, 1)]];

                // Add 9 random clients
                $pilatesSession->clientIds = array_map(
                    fn ($key) => $userIds[$key],
                    array_rand($userIds, 9)
                );

                $this->sessionRepository->create($pilatesSession);
            }

            // Create Workshop session
            $workshopSession = new Session();
            $workshopSession->startDateTime = new \DateTimeImmutable('2025-02-21 15:00:00');
            $workshopSession->endDateTime = new \DateTimeImmutable('2025-02-21 16:00:00');
            $workshopSession->priceWithVat = 30.00;
            $workshopSession->vatPercentage = 21.00;
            $workshopSession->category = 'charla';
            $workshopSession->capacity = 15;
            $workshopSession->roomId = $multiPurposeRoomId;

            // Add both managers
            $workshopSession->professionalIds = array_map(
                fn ($key) => $userIds[$key],
                array_rand($managerIds, 2)
            );

            // Add 15 random clients
            $workshopSession->clientIds = array_map(
                fn ($key) => $userIds[$key],
                array_rand($userIds, 15)
            );

            $this->sessionRepository->create($workshopSession);

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('An error occurred while inserting test data: '
                . $e->getMessage() . PHP_EOL
                . $e->getFile() . ':' . $e->getLine());

            return Command::FAILURE;
        }
    }
}
