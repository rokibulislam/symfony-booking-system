<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Room;
use App\Entity\Reservation;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'super_admin',
            'email' => 'super_admin@admin.com',
            'password' => 'admin12345',
            'fullName' => 'Micro Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
    ];

    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher) {
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function load(ObjectManager $manager): void
    {
         $this->loadUsers($manager);
         $this->loadRooms($manager);
         $this->loadReservation($manager);
    }

    private function loadUsers(ObjectManager $manager) {

        foreach (self::USERS as $userData) {
            $user = new User();

            $user->setName( $userData['username'] );

            $user->setEmail($userData['email']);

            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $userData['password']
                )
            );

            $user->setRoles($userData['roles']);

//            $this->addReference($userData['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadRooms(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $room = new Room();
            $room->setTitle('Room'. $i);
            $room->setDescription('test description');
            $room->setPrice(10.50 * $i);
            $room->setStatus(true);
            $room->setNumber($i);
            $manager->persist($room);
        }

        $manager->flush();
    }

    private function loadReservation(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            $reservation = new Reservation();
            $reservation->setStartDate($date);
            $reservation->setEndTime($date);
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
