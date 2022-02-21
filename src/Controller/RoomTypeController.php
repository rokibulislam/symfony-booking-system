<?php
namespace App\Controller;

use App\Entity\Room;
use App\Entity\RoomType;
use App\Form\RoomFormType;
use App\Form\RoomTypeFormType;
use App\Repository\RoomTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/room_type")
 */
class RoomTypeController extends AbstractController
{
    private $entityManager;
    private $roomTypeRepository;

    public function __construct(RoomTypeRepository $roomTypeRepository,EntityManagerInterface $entityManager) {
        $this->roomTypeRepository = $roomTypeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="roomtype_index")
     */
    public function index(): Response
    {
        $roomtypes = $this->roomTypeRepository->findAll([]);

        return $this->render('room_type/index.html.twig', [
            'controller_name' => 'RoomTypeController',
            'roomtypes' => $roomtypes
        ]);
    }

    /**
     * @Route("/create", name="roomtype_create")
     */
    public function create(Request $request): Response
    {
        $roomtype = new RoomType();
        $form = $this->createForm(RoomTypeFormType::class, $roomtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($roomtype);
            $this->entityManager->flush();

            return $this->redirectToRoute('roomtype_index');
        }

        return $this->render('room_type/create.html.twig', [
            'roomtypeForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="roomtype_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RoomType $roomType)
    {

        $form = $this->createForm(RoomTypeFormType::class, $roomType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('roomtype_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room_type/edit.html.twig', [
            'roomtypeForm' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="roomtype_delete", methods={"POST"})
     */
    public function delete(Request $request, RoomType $roomType)
    {
        if ($this->isCsrfTokenValid('delete'.$roomType->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($roomType);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('roomtype_index', [], Response::HTTP_SEE_OTHER);
    }
}
