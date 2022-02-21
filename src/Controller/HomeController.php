<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository,EntityManagerInterface $entityManager) {
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $rooms = $this->roomRepository->findBy([]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'rooms' => $rooms
        ]);
    }

    /**
     * @Route("/rooms", name="rooms_list")
     */
    public function room_list(): Response
    {
        $rooms = $this->roomRepository->findBy([]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'rooms' => $rooms
        ]);
    }

    /**
     * @Route("/rooms/{id}", name="room_details")
     */
    public function room_details($id): Response
    {
        $room = $this->roomRepository->find($id);

        return $this->render('home/details.html.twig', [
            'controller_name' => 'HomeController',
            'room' => $room
        ]);
    }
}
