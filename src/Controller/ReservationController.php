<?php

namespace App\Controller;

use App\Form\ReservationFormType;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\RoomFormType;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/reservation")
 */
class ReservationController extends AbstractController
{
    private $entityManager;
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository,EntityManagerInterface $entityManager) {
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="reservation_index")
     */
    public function index(): Response
    {
        $reservations = $this->reservationRepository->findBy([]);

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'reservations' => $reservations
        ]);
    }

    /**
     * @Route("/create", name="reservation_create")
     */
    public function create(Request $request) {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType ::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/create.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation)
    {
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'project' => $reservation,
            'reservationForm' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation)
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/cancel/{id}", name="cancel_reservation")
     */
    public function cancel(Reservation $reservation)
    {
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();

        return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
    }

}
