<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/comment")
 */
class CommentController extends AbstractController
{
    private $entityManager;
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository,EntityManagerInterface $entityManager) {
        $this->commentRepository = $commentRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="comment_index")
     */
    public function index(): Response
    {
        $comments = $this->commentRepository->findAll([]);

        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
            'comments' => $comments
        ]);
    }


    /**
     * @Route("/create", name="comment_create")
     */
    public function create(Request $request) {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/create.html.twig', [
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment)
    {

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();
            return $this->redirectToRoute('comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'commentForm' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment)
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('comment_index', [], Response::HTTP_SEE_OTHER);
    }
}
