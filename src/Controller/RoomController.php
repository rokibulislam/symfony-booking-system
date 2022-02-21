<?php

namespace App\Controller;

use App\Form\RoomFormType;
use App\Repository\RoomRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Room;

/**
 * @Route("/dashboard/room")
 */
class RoomController extends AbstractController
{
    private $entityManager;
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository,EntityManagerInterface $entityManager) {
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="room_index")
     */
    public function index(): Response
    {
        $rooms = $this->roomRepository->findBy([]);

        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
            'rooms' => $rooms
        ]);
    }

    /**
     * @Route("/create", name="room_create")
     */
    public function create(Request $request,  UploaderHelper $uploaderHelper) {
        $room = new Room();
        $form = $this->createForm(RoomFormType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form['imageFilename']->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadImage($uploadedFile);
                $room->setImageFilename($newFilename);
            }

            $this->entityManager->persist($room);
            $this->entityManager->flush();

            return $this->redirectToRoute('room_index');
        }

        return $this->render('room/create.html.twig', [
            'roomForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Room $room)
    {

        $form = $this->createForm(RoomFormType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();
            return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room/edit.html.twig', [
            'roomForm' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="room_delete", methods={"POST"})
     */
    public function delete(Request $request, Room $room)
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($room);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
    }
}
