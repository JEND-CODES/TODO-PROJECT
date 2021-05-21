<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Tasktodo;
use App\Form\TasktodoType;
use App\Repository\TasktodoRepository;
use App\Handler\PagingHandler;
use App\Security\Voter\TaskVoter;

class TaskController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
	 * @var AuthorizationCheckerInterface
	 */
	private $authorization;

    /**
     * @var TasktodoRepository
     */
    private $tasktodoRepo;

    /**
     * @var PagingHandler
     */
    private $pagingHandler;

    public function __construct(
        Security $security, 
        EntityManagerInterface $manager, 
        AuthorizationCheckerInterface $authorization,
        TasktodoRepository $tasktodoRepo,
        PagingHandler $pagingHandler
        )
    {
        $this->security = $security;
        $this->manager = $manager;
        $this->authorization = $authorization;
        $this->tasktodoRepo = $tasktodoRepo;
        $this->pagingHandler = $pagingHandler;
    }

    /**
     * @Route("/tasks", name="task_list", methods={"GET"})
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        $pageValues = $this->pagingHandler->handle($request);

        if (!$pageValues[0] || !$pageValues[1]) {
            $start = 0;
            $limit = 10;
        } else {
            $start = (int) strip_tags($pageValues[0]);
            $limit = (int) strip_tags($pageValues[1]);
        }

        $tasks = $this->tasktodoRepo->findBy(array(), array('createdAt' => 'DESC'));

        return $this->render('task/list.html.twig', [
            'limit' => $limit,
            'start' => $start,
            'tasks' => $tasks
        ]);

    }

    /**
     * @Route("/tasks/done", name="task_done", methods={"GET"})
     * @return Response
     */
    public function listDone(Request $request): Response
    {
        $pageValues = $this->pagingHandler->handle($request);

        if (!$pageValues[0] || !$pageValues[1]) {
            $start = 0;
            $limit = 10;
        } else {
            $start = (int) strip_tags($pageValues[0]);
            $limit = (int) strip_tags($pageValues[1]);
        }

        $tasks = $this->tasktodoRepo->findBy(array('isDone' => true), array('freshDate' => 'DESC'));

        return $this->render('task/list.html.twig', [
            'limit' => $limit,
            'start' => $start,
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create", methods={"GET","POST"})
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $task = new Tasktodo();

        $form = $this->createForm(TasktodoType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUsertodo($this->security->getUser());

            $this->manager->persist($task);

            $this->manager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit", requirements={"id": "\d+"}, methods={"GET","POST"})
     * @return Response
     */
    public function editAction(Tasktodo $task, Request $request): Response
    {
        $form = $this->createForm(TasktodoType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setFreshDate(new \Datetime());

            $this->manager->persist($task);

            $this->manager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle", requirements={"id": "\d+"}, methods={"GET", "POST"})
     * @return Response
     */
    public function toggleTaskAction(Tasktodo $task): Response
    {
        $task->toggle(!$task->isDone());

        $task->setFreshDate(new \Datetime());

        $this->manager->persist($task);

        $this->manager->flush();

        if ($task->isDone() === true) {

            $this->addFlash('success', sprintf('La tâche " %s " a bien été marquée comme faite.', $task->getTitle()));

        } else {

            $this->addFlash('success', sprintf('La tâche " %s " est réouverte.', $task->getTitle()));

        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete", requirements={"id": "\d+"}, methods={"GET", "DELETE"})
     * @return RedirectResponse
     */
    public function deleteTaskAction(Tasktodo $task): RedirectResponse
    {

        if (!$this->authorization->isGranted(TaskVoter::DELETE, $task)) {

			$this->addFlash('error', 'Erreur. Opération réservée aux auteurs des tâches ou aux administrateurs');

            return $this->redirectToRoute('task_list');

		}

        $this->manager->remove($task);
            
        $this->manager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');

    }
    
}
